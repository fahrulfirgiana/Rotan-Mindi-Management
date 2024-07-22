<!DOCTYPE html>
<html lang="en">
<head>
    @include('inventaris.css')
</head>
<body>
    <div class="wrapper">
        @include('inventaris.sidebar')

        <div class="main">
            @include('inventaris.navbar')

            <main class="content">
                <div class="container-fluid p-0">
                    <div class="container">
                        <div style="border-radius:30px" class="card shadow-lg p-3 rounded-pill-4">
                            <div class="container-fluid">
                                <div class="py-3">
                                    <p class="title-page">Tambah Data SubKontraktor</p>
                                </div>
                                <div style="border-radius:20px">
                                    <form class="forms-sample" action="{{ url('/add_subkontraktor') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputEmail3">Nama</label>
                                            <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" id="nama" placeholder="Masukan Nama Sub Kontraktor" value="{{ old('nama') }}">
                                            @error('nama')
                                                <div class="invalid-feedback message">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputEmail3">Kontak</label>
                                            <input type="tel" class="form-control @error('kontak') is-invalid @enderror" name="kontak" id="kontak" placeholder="Masukan Kontak" value="{{ old('kontak') }}">
                                            @error('kontak')
                                                <div class="invalid-feedback message">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputPassword4">Pekerja</label>
                                            <input type="number" class="form-control @error('pekerja') is-invalid @enderror" name="pekerja" id="pekerja" placeholder="Masukan Jumlah Pekerja" value="{{ old('pekerja') }}">
                                            @error('pekerja')
                                                <div class="invalid-feedback message">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputName1">Bahan Baku dan Jumlah Kuintal</label>
                                            <div id="materials">
                                                <div class="material-group">
                                                    <input type="text" class="form-control @error('bahan[]') is-invalid @enderror" name="bahan[]" placeholder="Masukan Jenis Bahan Baku" value="{{ old('bahan[]') }}">
                                                    @error('bahan[]')
                                                        <div class="invalid-feedback message">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                    <input type="number" class="form-control @error('kuintal[]') is-invalid @enderror" name="kuintal[]" placeholder="Masukan Jumlah Kuintal" value="{{ old('kuintal[]') }}">
                                                    @error('kuintal[]')
                                                        <div class="invalid-feedback message">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                    <div class="cs-rl" style="padding-left: 15px;">
                                                        <button type="button" class="btn btn-success add-material">Tambah Bahan</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-i">
                                            <button type="submit" class="btn btn-dark">Simpan</button>
                                            <a class="btn btn-abu me-2" href="{{ url('show_kontraktor') }}">Batal</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </main>

            <footer class="footer">
                @include('inventaris.footer')
            </footer>
        </div>
    </div>

    @include('inventaris.js')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('.add-material').addEventListener('click', function () {
                var materialGroup = document.querySelector('.material-group').cloneNode(true);
                materialGroup.querySelectorAll('input').forEach(input => input.value = '');
                materialGroup.querySelector('.add-material').remove();
                document.querySelector('#materials').appendChild(materialGroup);
            });
        });
    </script>

</body>
</html>
