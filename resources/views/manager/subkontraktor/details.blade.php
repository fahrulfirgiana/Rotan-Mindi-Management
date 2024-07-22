<!DOCTYPE html>
<html lang="en">

<head>
    @include('inventaris.css')
</head>

<body>
    @include('sweetalert::alert')
    <div class="wrapper">
        @include('inventaris.sidebar')

        <div class="main">
            @include('inventaris.navbar')

            <main class="content" style="padding: 10px">
                <div class="container-fluid p-0">
                    <div class="container-fluid py-4">
                        <div class="container">
                            <div style="border-radius:30px" class="card shadow-lg p-3 rounded-pill-4">
                                <div class="container-fluid">
                                    <div class="card-body">
                                        <div class="container">
                                            <div class="py-3">
                                                <p class="title-page">Detail Bahan Subkontraktor</p>
                                            </div>
                                            <div class="btn-i">
                                                <a class="btn btn-secondary btn-sm"
                                                    href="{{ url('view_kontraktor') }}"><span class="material-symbols-outlined" style="font-size: 18px; margin-right: 5px">
                                                        arrow_back
                                                        </span> Kembali</a>
                                            </div>
                                            <p></p>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" style="text-align: center">
                                                <thead>
                                                    <tr>
                                                        <th>Nama Subkontraktor</th>
                                                        <th>Kontak</th>
                                                        <th>Pekerja</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $subkontraktor->subkontraktor_name }}</td>
                                                        <td>{{ $subkontraktor->contact }}</td>
                                                        <td>{{ $subkontraktor->employee }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <h4>Bahan Baku</h4>
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover" style="text-align: center">
                                                    <thead>
                                                        <tr>
                                                            <th>Bahan</th>
                                                            <th>Kuintal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($subkontraktor->materials as $material)
                                                            <tr>
                                                                <td>{{ $material->bahan }}</td>
                                                                <td>{{ $material->kuintal }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="2">Tidak ada bahan baku.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
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
    </div>
</body>

</html>
