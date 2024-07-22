<!DOCTYPE html>
<html lang="en">

<head>
    @include('manager.css')
</head>

<body>
    <div class="wrapper">
        @include('manager.sidebar')

        <div class="main">
            @include('manager.navbar')

            <main class="content">
                <div class="container-fluid p-0">
                    <div class="container">
                        <div style="border-radius:30px" class="card shadow-lg p-3 rounded-pill-4">
                            <div class="container-fluid">
                                <div class="py-3">
                                    <p class="title-page">Edit Data Pesanan</p>
                                </div>
                                <div style="border-radius:20px">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form class="forms-sample" action="{{ url('/update_order', $order->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group cs-rl">
                                            <label for="product_name">Nama Barang</label>
                                            <select class="form-control" name="product_name" id="product_name" onchange="changeValue(this.value)">
                                                <option value="{{ $order->product_name }}">Pilih Produk</option>
                                                @foreach ($product as $prod)
                                                    <option value="{{ $prod->product_name }}">{{ $prod->product_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputEmail3">Ukuran</label>
                                            <input type="text" class="form-control" name="ukuran" id="exampleInputEmail3" placeholder="Ukuran" value="{{ $order->size }}">
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputtext4">Kuantitas</label>
                                            <input type="number" class="form-control" name="kuantitas" id="exampleInputtext4" placeholder="Kuantitas" value="{{ $order->quantity }}">
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="harga">Harga</label>
                                            <input type="number" class="form-control" name="harga" id="harga" min="0" placeholder="Harga" value="{{ $order->price }}">
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputName1">Batas Waktu</label>
                                            <input type="date" class="form-control" name="deadline" id="exampleInputName1" placeholder="Batas Waktu" value="{{ $order->deadline }}">
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputName1">Nama Kustomer</label>
                                            <input type="text" class="form-control" name="cus_name" id="exampleInputName1" placeholder="Nama Kustomer" value="{{ $order->customer_name }}">
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputName1">Alamat</label>
                                            <input type="text" class="form-control" name="address" id="exampleInputName1" placeholder="Alamat" value="{{ $order->address }}">
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label>Gambar</label>
                                            <br>
                                            <img class="img-padding-left" id="preview" src="/order/{{ $order->image }}"
                                                style="max-width: 100px; max-height:100px">
                                        </div>
                                        <div class="btn-i">
                                            <button type="submit" class="btn btn-dark">Simpan</button>
                                            <button type="button" class="btn btn-abu me-2" onclick="goBack()">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="footer">
                @include('manager.footer')
            </footer>
        </div>
    </div>

    @include('manager.js')

    <script>
        function changeValue(productName) {
            @foreach ($product as $prod)
                if ("{{ $prod->product_name }}" == productName) {
                    document.getElementById('harga').value = "{{ $prod->price }}";
                    document.getElementById('preview').src = "{{ asset('order/'.$prod->image) }}";
                }
            @endforeach
        }
    </script>

</body>

</html>
