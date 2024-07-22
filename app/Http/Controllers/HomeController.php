<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Orders;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
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

    return view('manager.dashboard', compact('orders', 'totalOrders', 'totalProduction', 'totalProducts', 'totalSubcontractors', 'topProducts', 'monthlyOrders', 'productions', 'totalInProgress'));
}

private function calculateProgress($order)
{
    // Implementasikan logika Anda untuk menghitung sedang_progress di sini
    return $order->quantity - $order->progress;
}

    public function beranda()
    {
        $order = Orders::all();
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

    return view('manager.dashboard', compact('orders', 'totalOrders', 'totalProduction', 'totalProducts', 'totalSubcontractors', 'topProducts', 'monthlyOrders', 'productions', 'totalInProgress'));
    }

    public function view_order(Request $request)
    {
        if ($request->start_date || $request->end_date) {
            $start_date = Carbon::parse($request->start_date)->startOfDay()->toDateTimeString();
            $end_date = Carbon::parse($request->end_date)->endOfDay()->toDateTimeString();
            $orders = Orders::whereBetween('created_at', [$start_date, $end_date])->paginate(8);
        } else {
            $orders = Orders::paginate(5);
        }

        return view('manager.orders.order', compact('orders'));
    }


    public function view_addorder()
    {
        $dataorder = Orders::all();
        $product = Product::all();
        return view('manager.orders.addorder', compact('dataorder', 'product'));
    }

    public function add_order(Request $request)
    {
        $messages = [
            'produk.required' => 'Nama Barang harus diisi.',
            'ukuran.required' => 'Ukuran harus diisi.',
            'kuantitas.required' => 'Kuantitas harus diisi.',
            'kuantitas.integer' => 'Kuantitas harus berupa angka.',
            'harga.required' => 'Harga harus diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'deadline.required' => 'Batas Waktu harus diisi.',
            'deadline.date' => 'Batas Waktu harus berupa tanggal yang valid.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif.',
            'image.max' => 'Gambar tidak boleh lebih dari 2048 kilobytes.',
        ];

        $request->validate([
            'produk' => 'required',
            'ukuran' => 'required',
            'kuantitas' => 'required|integer',
            'harga' => 'required|numeric',
            'deadline' => 'required|date',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        $order = new Orders;
        $order->product_name = $request->produk;
        $order->size = $request->ukuran;
        $order->quantity = $request->kuantitas;
        $order->price = $request->harga;
        $order->total_price = $request->kuantitas * $request->harga;
        $order->deadline = $request->deadline;
        $product = Product::where('product_name', $request->produk)->first();
        if ($product) {
            $order->image = $product->image; // Ganti dengan nama kolom yang sesuai di tabel Product
        }


        $order->save();
        Alert::success('Berhasil', 'Pesanan Telah Berhasil Ditambahkan');
        return Redirect::to('/view_order')->with('success', 'Order updated successfully');
    }

    public function edit_order($id)
    {
        $order = Orders::find($id);
        $product = Product::all();
        return view('manager.orders.showedit', compact('order','product'));
    }

    public function update_order(Request $request, $id)
    {
        $messages = [
            'kuantitas.integer' => 'Kuantitas harus berupa angka.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'deadline.date' => 'Batas Waktu harus berupa tanggal yang valid.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif.',
            'image.max' => 'Gambar tidak boleh lebih dari 2048 kilobytes.',
        ];

        $request->validate([
            'kuantitas' => 'integer',
            'harga' => 'numeric',
            'deadline' => 'date',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        $order = Orders::find($id);
        $order->product_name = $request->product_name;
        $order->size = $request->ukuran;
        $order->quantity = $request->kuantitas;
        $order->price = $request->harga;
        $order->total_price = $order->quantity * $order->price;
        $order->deadline = $request->deadline;
        $order->customer_name = $request->cus_name;
        $order->address = $request->address;

        $product = Product::where('product_name', $request->product_name)->first();
        if ($product) {
            $order->image = $product->image; // Ganti dengan nama kolom yang sesuai di tabel Product
        }

        $order->save();
        Alert::success('Berhasil', 'Pesanan Telah Berhasil Diedit');
        return Redirect::to('/view_order')->with('success', 'Order updated successfully');
    }

    public function delete_order($id)
    {
        $order = Orders::where('id', $id)->first();
        File::delete(public_path('order' . '/' . $order->image));

        Orders::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Berhasil hapus data');
    }
}
