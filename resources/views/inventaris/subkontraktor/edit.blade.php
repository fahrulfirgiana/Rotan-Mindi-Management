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
                                    <p class="title-page">Edit Data SubKontraktor</p>
                                </div>
                                <div style="border-radius:20px">
                                    <form class="forms-sample" action="{{ url('/update_sub', $subkontraktor->id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputEmail3">Nama</label>
                                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                                name="nama" id="nama" value="{{ $subkontraktor->subkontraktor_name }}"
                                                placeholder="Masukan Nama Sub Kontraktor">
                                            @error('nama')
                                            <div class="invalid-feedback message">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputEmail3">Kontak</label>
                                            <input type="number" class="form-control @error('kontak') is-invalid @enderror"
                                                name="kontak" id="kontak" value="{{ $subkontraktor->contact }}"
                                                placeholder="Masukan Kontak">
                                            @error('kontak')
                                            <div class="invalid-feedback message">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputPassword4">Pekerja</label>
                                            <input type="number"
                                                class="form-control @error('pekerja') is-invalid @enderror" name="pekerja"
                                                id="pekerja" value="{{ $subkontraktor->employee }}"
                                                placeholder="Masukan Jumlah Pekerja">
                                            @error('pekerja')
                                            <div class="invalid-feedback message">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group cs-rl">
                                            <label for="exampleInputName1">Bahan Baku</label>
                                            <input type="text" class="form-control @error('bahan') is-invalid @enderror"
                                                id="bahan" name="bahan" value="{{ $subkontraktor->stock }}"
                                                placeholder="Masukan Jumlah Bahan Baku Tersedia">
                                            @error('bahan')
                                            <div class="invalid-feedback message">
                                                {{ $message }}
                                            </div>
                                            @enderror
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
                </div>
            </main>

            <footer class="footer">
                @include('inventaris.footer')
            </footer>
        </div>
    </div>

    @include('inventaris.js')

</body>

</html>
