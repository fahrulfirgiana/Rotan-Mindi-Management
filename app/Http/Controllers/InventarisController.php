<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Orders;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\Subkontraktor;
use App\Models\Subcontractors;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\OrderProgressHistory;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class InventarisController extends Controller
{

    public function index()
    {
        $orders = DB::table('orders')
            ->select('id','product_name', 'quantity', 'deadline', 'progress')
            ->get();

        // Tambahkan logika untuk menghitung sedang_progress
        $orders = $orders->map(function ($order) {
            $order->sedang_progress = $this->calculateProgress($order);
            return $order;
        });

        $totalOrders = $orders->count();
        $totalProduction = DB::table('orders')->sum('progress');
        $totalProducts = DB::table('products')->count();
        $totalSubcontractors = DB::table('subcontractors')->count();
        $topProducts = DB::table('orders')
            ->select('product_name', DB::raw('SUM(progress) as total_quantity'))
            ->groupBy('product_name')
            ->orderByDesc('total_quantity')
            ->limit(3)
            ->get();
        $monthlyOrders = DB::table('orders')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total_orders'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total_orders', 'month');
        $productions = DB::table('orders')
            ->select('product_name',
                        DB::raw('SUM(progress) as total_production'),
                        DB::raw('SUM(quantity) as total_quantity'),
                        DB::raw('SUM(quantity) - SUM(progress) as total_in_progress'))
            ->groupBy('product_name', 'image')
            ->get();
        $totalInProgress = $productions->sum('total_in_progress');

        return view('inventaris.dashboard', compact('orders', 'totalOrders', 'totalProduction', 'totalProducts', 'totalSubcontractors', 'topProducts', 'monthlyOrders', 'productions', 'totalInProgress'));
    }

    private function calculateProgress($order)
    {
        // Implementasikan logika Anda untuk menghitung sedang_progress di sini
        return $order->quantity - $order->progress;
    }

    public function show_order(Request $request)
    {
        $subkontraktors = Subcontractors::select('id', 'subkontraktor_name', 'contact')->distinct()->get();
        $query = Orders::query();

        if ($request->has('subkontraktor') && $request->subkontraktor != '') {
            $query->where('subkontraktor_name', $request->subkontraktor);
        }

        if ($request->start_date || $request->end_date) {
            $start_date = Carbon::parse($request->start_date)->startOfDay()->toDateTimeString();
            $end_date = Carbon::parse($request->end_date)->endOfDay()->toDateTimeString();
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }

        $orders = $query->paginate(5);

        return view('inventaris.pesanan.order', compact('orders', 'subkontraktors'));
    }

    public function edit_pesanan($id)
    {
        $order = Orders::find($id);
        $subkontraktor = Subcontractors::all();
        return view('inventaris.pesanan.editpesanan', compact('order', 'subkontraktor'));
    }

    public function update_pesanan(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string',
            'ukuran' => 'required|string',
            'kuantitas' => 'required|integer|min:1',
            'harga' => 'required|integer|min:0',
            'deadline' => 'required|date',
            'progress' => 'required|integer|min:0|max:' . $request->kuantitas,
            'subkontraktor' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [
            'progress.max' => 'Progress tidak boleh melebihi kuantitas!'
        ]);

        $order = Orders::find($id);
        $order->product_name = $request->product_name;
        $order->size = $request->ukuran;
        $order->quantity = $request->kuantitas;
        $order->price = $request->harga;
        $order->total_price = $order->quantity * $order->price;
        $order->deadline = $request->deadline;
        $order->progress = $request->progress;
        $order->subkontraktor_name = $request->subkontraktor;

        if ($request->progress == $request->kuantitas) {
            $order->status = 'Selesai';
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $image->move('order', $imagename);
            $order->image = $imagename;
        }

        $order->save();

        // Tambahkan riwayat progres
        OrderProgressHistory::create([
            'order_id' => $order->id,
            'progress' => $request->input('progress')
        ]);

        Alert::success('Berhasil', 'Pesanan Telah Berhasil Diedit');
        return Redirect::to('/show_order')->with('success', 'Order updated successfully');
    }

    public function detail_pesanan($id)
    {
        $order = Orders::with('progressHistories')->findOrFail($id);
        return view('inventaris.pesanan.detail', compact('order'));
    }


    public function exportPDF(Request $request)
    {
        set_time_limit(300); // Set to 5 minutes

        $query = Orders::query();

        if ($request->has('subkontraktor') && $request->subkontraktor != '') {
            $query->where('subkontraktor_name', $request->subkontraktor);
        }

        if ($request->start_date || $request->end_date) {
            $start_date = Carbon::parse($request->start_date)->startOfDay()->toDateTimeString();
            $end_date = Carbon::parse($request->end_date)->endOfDay()->toDateTimeString();
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }

        $orders = $query->get();

        $pdf = Pdf::loadView('inventaris.pdf', compact('orders'));
        $fileName = 'orders.pdf';
        $filePath = storage_path($fileName);

        $pdf->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function sendMessageToWhatsApp(Request $request)
    {
        // Validasi input
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        // Inisialisasi client Guzzle
        $client = new \GuzzleHttp\Client();

        // API Endpoint dan Token
        $url = 'https://api.fonnte.com/send';
        $token = '#!vhWSuXnDuo938mFbj@';

        // Kirim permintaan API
        $response = $client->post($url, [
            'headers' => [
                'Authorization' => $token,
            ],
            'form_params' => [
                'target' => $request->phone,
                'message' => $request->message,
                'countryCode' => '62', // Kode negara, misalnya 62 untuk Indonesia
            ],
        ]);

        // Periksa respons dari Fonnte
        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody(), true);

        if ($statusCode == 200 && isset($body['status']) && $body['status'] == 'success') {
            Alert::success('Berhasil', 'Pesan berhasil dikirim ke WhatsApp');
        } else {
            Alert::error('Gagal', 'Gagal mengirim pesan ke WhatsApp');
        }

        return redirect()->back();
    }

    // SECTION SUB-KONTRAKTOR

    public function show_kontraktor()
    {
        $subkontraktors = Subcontractors::paginate(5);
        return view('inventaris.subkontraktor.index', compact('subkontraktors'));
    }

    public function show_subkontraktor()
{
    $subkontraktor = Subcontractors::all();
    return view('inventaris.subkontraktor.create', compact('subkontraktor'));
}

public function add_subkontraktor(Request $request)
{
    $messages = [
        'nama.required' => 'Nama subkontraktor harus diisi.',
        'nama.string' => 'Nama subkontraktor harus berupa teks.',
        'nama.regex' => 'Nama subkontraktor harus hanya berisi huruf.',
        'kontak.required' => 'Kontak harus diisi.',
        'kontak.numeric' => 'Kontak harus berupa angka.',
        'pekerja.required' => 'Jumlah pekerja harus diisi.',
        'pekerja.integer' => 'Jumlah pekerja harus berupa angka.',
        'bahan.required' => 'Bahan baku harus diisi.',
        'kuintal.required' => 'Jumlah kuintal harus diisi.',
    ];

    $validator = Validator::make($request->all(), [
        'nama' => ['required', 'string', 'regex:/^[a-zA-Z ]+$/u', 'max:255'],
        'kontak' => ['required', 'numeric'],
        'pekerja' => ['required', 'integer'],
        'bahan' => ['required', 'array'],
        'bahan.*' => ['required', 'string'],
        'kuintal' => ['required', 'array'],
        'kuintal.*' => ['required', 'integer'],
    ], $messages);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }


    $subkontraktor = Subcontractors::create([
        'subkontraktor_name' => $request->nama,
        'contact' => $request->kontak,
        'employee' => $request->pekerja,
    ]);

    foreach ($request->bahan as $index => $bahan) {
        Material::create([
            'subcontractor_id' => $subkontraktor->id,
            'bahan' => $bahan,
            'kuintal' => $request->kuintal[$index],
        ]);
    }

    Alert::success('Berhasil', 'Subkontraktor Telah Berhasil Ditambahkan');
    return Redirect::to('/show_kontraktor')->with('success', 'Subkontraktor berhasil ditambahkan');
}

public function edit_sub($id)
{
    $subkontraktor = Subcontractors::with('materials')->find($id);
    return view('inventaris.subkontraktor.edit', compact('subkontraktor'));
}

public function update_sub(Request $request, $id)
{
    $messages = [
        'nama.required' => 'Nama subkontraktor harus diisi.',
        'nama.string' => 'Nama subkontraktor harus berupa teks.',
        'nama.regex' => 'Nama subkontraktor harus hanya berisi huruf.',
        'kontak.required' => 'Kontak harus diisi.',
        'kontak.numeric' => 'Kontak harus berupa angka.',
        'pekerja.required' => 'Jumlah pekerja harus diisi.',
        'pekerja.integer' => 'Jumlah pekerja harus berupa angka.',
        'bahan.required' => 'Bahan baku harus diisi.',
        'kuintal.required' => 'Jumlah kuintal harus diisi.',
    ];

    $validator = Validator::make($request->all(), [
        'nama' => ['required', 'string', 'regex:/^[a-zA-Z ]+$/u', 'max:255'],
        'kontak' => ['required', 'numeric'],
        'pekerja' => ['required', 'integer'],
        'bahan' => ['required', 'array'],
        'bahan.*' => ['required', 'string'],
        'kuintal' => ['required', 'array'],
        'kuintal.*' => ['required', 'integer'],
    ], $messages);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Pastikan nilai default untuk `stock`
    $stock = $request->stock ?? 'default_stock_value';

    $subkontraktor = Subcontractors::findOrFail($id);
    $subkontraktor->update([
        'subkontraktor_name' => $request->nama,
        'contact' => $request->kontak,
        'employee' => $request->pekerja,
        'stock' => $stock,
    ]);

    // Delete existing materials
    Material::where('subcontractor_id', $subkontraktor->id)->delete();

    // Add new materials
    foreach ($request->bahan as $index => $bahan) {
        Material::create([
            'subcontractor_id' => $subkontraktor->id,
            'bahan' => $bahan,
            'kuintal' => $request->kuintal[$index],
        ]);
    }

    Alert::success('Berhasil', 'Subkontraktor Telah Berhasil Diedit');
    return Redirect::to('/show_kontraktor')->with('success', 'Subkontraktor berhasil diperbarui');
}

public function showDetails($id)
    {
        $subkontraktor = Subcontractors::with('materials')->find($id);
        if (!$subkontraktor) {
            return redirect()->back()->with('error', 'Subkontraktor tidak ditemukan');
        }

        return view('inventaris.subkontraktor.details', compact('subkontraktor'));
    }

public function delete_sub($id)
{
    Subcontractors::where('id', $id)->delete();
    return redirect()->back()->with('success', 'Berhasil hapus data');
}

}
