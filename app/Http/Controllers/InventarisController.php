<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Orders;
use Illuminate\Http\Request;
use App\Models\Subcontractors;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Subkontraktor;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class InventarisController extends Controller
{

    public function index()
    {
        $order = Orders::all();
        $orders = DB::table('orders')->get();

        // Hitung total pesanan
        $totalOrders = $orders->count();
        // Menghitung total produksi dari tabel orders
        $totalProduction = DB::table('orders')->sum('progress');
        // Menghitung total produk dari tabel products
        $totalProducts = DB::table('products')->count();
        // Menghitung total subkontraktor dari tabel subcontractors
        $totalSubcontractors = DB::table('subcontractors')->count();
        // Mengambil tiga produk terpopuler
        $topProducts = DB::table('orders')
            ->select('product_name', DB::raw('SUM(progress) as total_quantity'))
            ->groupBy('product_name')
            ->orderByDesc('total_quantity')
            ->limit(3)
            ->get();
        // Menghitung total pesanan per bulan
        $monthlyOrders = DB::table('orders')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total_orders'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total_orders', 'month');

        // Mengirimkan data ke view
        return view('inventaris.dashboard', compact('order','totalOrders', 'totalProduction', 'totalProducts', 'totalSubcontractors', 'topProducts', 'monthlyOrders'));
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
    Alert::success('Berhasil', 'Pesanan Telah Berhasil Diedit');
    return Redirect::to('/show_order')->with('success', 'Order updated successfully');
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
        'bahan.required' => 'Stok bahan harus diisi.',
    ];

    $validator = Validator::make($request->all(), [
        'nama' => ['required', 'string', 'regex:/^[a-zA-Z ]+$/u', 'max:255'],
        'kontak' => ['required', 'numeric'], // Menggunakan 'numeric' untuk validasi kontak
        'pekerja' => ['required', 'integer'],
        'bahan' => ['required'],
    ], $messages);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $subkontraktor = new Subcontractors();
    $subkontraktor->subkontraktor_name = $request->nama;
    $subkontraktor->contact = $request->kontak;
    $subkontraktor->employee = $request->pekerja;
    $subkontraktor->stock = $request->bahan;

    $subkontraktor->save();
    Alert::success('Berhasil', 'Subkontraktor Telah Berhasil Ditambahkan');
    return Redirect::to('/show_kontraktor')->with('success', 'Subkontraktor berhasil ditambahkan');
}


    public function edit_sub($id)
    {
        $subkontraktor = Subcontractors::find($id);
        return view('inventaris.subkontraktor.edit', compact('subkontraktor'));
    }

    public function update_sub(Request $request, $id)
    {
        $messages = [
            'nama.required' => 'Nama subkontraktor harus diisi.',
            'nama.string' => 'Nama subkontraktor harus berupa teks.',
            'nama.regex' => 'Nama subkontraktor harus hanya berisi huruf.',
            'kontak.required' => 'Kontak harus diisi.',
            'kontak.integer' => 'Kontak harus berupa angka.',
            'pekerja.required' => 'Jumlah pekerja harus diisi.',
            'pekerja.integer' => 'Jumlah pekerja harus berupa angka.',
            'bahan.required' => 'Stok bahan harus diisi.',
        ];
    
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'regex:/^[a-zA-Z ]+$/u', 'max:255'],
            'kontak' => ['required','integer'],
            'pekerja' => ['required', 'integer'],
            'bahan' => ['required'],
        ], $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $subkontraktor = new Subcontractors();
        $subkontraktor->subkontraktor_name = $request->nama;
        $subkontraktor->contact = $request->kontak;
        $subkontraktor->employee = $request->pekerja;
        $subkontraktor->stock = $request->bahan;
    
        $subkontraktor->save();
        Alert::success('Berhasil', 'Subkontraktor Telah Berhasil Diedit');
        return Redirect::to('/show_kontraktor')->with('success', 'Order updated successfully');
    }

    public function delete_sub($id)
    {
        Subcontractors::where('id', $id)->delete();
        //Alert::success('Berhasil', 'Hapus Data Produk Berhasil');
        return redirect()->back()->with('success', 'Berhasil hapus data');
    }

}
