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
                                    <p class="title-page">Tambah Data Pesanan</p>
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
                                    <form class="forms-sample" action="{{ url('/add_order') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputName1">Nama Barang</label>
                                            <select class="form-control" name="produk" id="produk" onchange="changeValue(this.value)">
                                                <option value="">Pilih Produk</option>
                                                @foreach ($product as $prod)
                                                <option value="{{ $prod->product_name }}">{{ $prod->product_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputEmail3">Ukuran</label>
                                            <input type="text" class="form-control" name="ukuran" id="ukuran" placeholder="Ukuran">
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputPassword4">Kuantitas</label>
                                            <input type="number" class="form-control" name="kuantitas" id="kuantitas" placeholder="Kuantitas">
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputName1">Harga</label>
                                            <input type="hidden" name="harga" id="harga" /> <!-- Input tersembunyi -->
                                            <input type="text" id="harga_format" class="form-control" placeholder="Masukkan Harga" /> <!-- Input untuk pengguna -->
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputName1">Batas Waktu</label>
                                            <input type="date" class="form-control" name="deadline" id="deadline" placeholder="Batas Waktu">
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputName1">Nama Pembeli</label>
                                            <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Nama Pembeli">
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputName1">Alamat</label>
                                            <input type="text" class="form-control" name="address" id="address" placeholder="Alamat">
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label>Gambar</label>
                                            <br>
                                            <img class="img-padding-left" id="preview" src="{{ asset('order/preview.jpeg') }}" style="max-width: 100px; max-height:100px">
                                        </div>
                                        <div class="btn-i">
                                            <button type="submit" class="btn btn-dark">Simpan</button>
                                            <a class="btn btn-abu me-2" href="{{ url('view_order') }}">Batal</a>
                                        </div>
                                    </form>
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
                document.getElementById('harga_format').value = formatRupiah("{{ $prod->price }}", 'Rp. ');
                document.getElementById('preview').src = "{{ asset('order/'.$prod->image) }}";
            }
            @endforeach
        }

        var hargaFormatted = document.getElementById('harga_format');
        var harga = document.getElementById('harga');

        hargaFormatted.addEventListener('keyup', function(e) {
            var value = this.value.replace(/[^,\d]/g, ''); // Menghapus semua karakter non-angka
            harga.value = value; // Menyimpan nilai asli di input tersembunyi
            this.value = formatRupiah(value, 'Rp. '); // Menampilkan nilai yang diformat
        });

        function formatRupiah(angka, prefix) {
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                var separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
    </script>
</body>

</html>
