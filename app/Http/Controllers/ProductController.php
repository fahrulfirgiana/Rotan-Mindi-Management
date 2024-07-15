<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Subcontractors;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    public function view_product()
    {
        $products = Product::paginate(5);
        return view('manager.product.product', compact('products'));
    }

    public function view_addproduct()
    {
        $dataproduct = Product::all();
        return view('manager.product.addproduct', compact('dataproduct'));
    }

    public function add_product(Request $request)
    {
        // Menghapus format mata uang sebelum validasi
        $harga = str_replace('.', '', $request->harga);

        $messages = [
            'product_name.required' => 'Nama Barang harus diisi.',
            'harga.required' => 'Harga harus diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'image.required' => 'Gambar harus diupload.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif.',
            'image.max' => 'Gambar tidak boleh lebih dari 2048 kilobytes.',
        ];

        // Validasi inputan
        $request->validate([
            'product_name' => 'required',
            'harga' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        // Menyimpan data ke database
        $product = new Product();
        $product->product_name = $request->product_name;
        $product->price = (int) $harga; // Menyimpan harga sebagai integer

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $image->move('order', $imagename);
            $product->image = $imagename;
        }

        $product->save();
        Alert::success('Berhasil', 'Produk Telah Berhasil Ditambahkan');
        return Redirect::to('/view_product')->with('success', 'Product added successfully');
    }



    public function edit_product($id)
{
    $product = Product::find($id);
    return view('manager.product.editproduct', compact('product'));
}

public function update_product(Request $request, $id)
{
    $product = Product::find($id);
    $product->product_name = $request->product_name;
    $product->price = $request->harga;

    if ($request->hasFile('image')) {
        // Hapus gambar lama jika ada
        if ($product->image && file_exists(public_path('order/' . $product->image))) {
            unlink(public_path('order/' . $product->image));
        }

        // Unggah gambar baru
        $image = $request->file('image');
        $imagename = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('order'), $imagename);
        $product->image = $imagename;
    }

    $product->save();
    Alert::success('Berhasil', 'Produk Telah Berhasil Diedit');
    return Redirect::to('/view_product')->with('success', 'Order updated successfully');
}

public function delete_product($id)
    {
        $product = Product::where('id', $id)->first();
        File::delete(public_path('order' . '/' . $product->image));

        Product::where('id', $id)->delete();
        //Alert::success('Berhasil', 'Hapus Data Produk Berhasil');
        return redirect()->back()->with('success', 'Berhasil hapus data');
    }


    public function view_kontraktor()
    {
        $subkontraktors = Subcontractors::paginate(4);
        return view('manager.subkontraktor.index', compact('subkontraktors'));
    }
}
