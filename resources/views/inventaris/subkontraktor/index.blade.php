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
                                                    <p class="title-page">Data Subkontraktor</p>
                                                </div>
                                                <div class="btn-i">
                                                    <a class="btn btn-secondary btn-sm"
                                                        href="{{ url('show_subkontraktor') }}"><span class="material-symbols-outlined" style="font-size: 18px; margin-right: 5px">
                                                            add_circle
                                                            </span> Tambah
                                                        Data</a>
                                                </div>
                                                <p></p>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover" style="text-align: center">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama</th>
                                                            <th>Kontak</th>
                                                            <th>Pekerja</th>
                                                            <th>Bahan Baku</th>
                                                            <th colspan="">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($subkontraktors as $subkontraktor)
                                                            <tr>
                                                                <td>{{ $subkontraktor->subkontraktor_name }}</td>
                                                                <td>{{ $subkontraktor->contact }}</td>
                                                                <td>{{ $subkontraktor->employee }}</td>
                                                                <td>{{ $subkontraktor->stock }}</td>
                                                                <td>
                                                                    <a class="btn-edit"
                                                                        href='{{ url('edit_sub', $subkontraktor->id) }}'
                                                                        title="Edit"><i
                                                                            class="fa-regular fa-pen-to-square"
                                                                            data-feather="edit"></i></a>
                                                                    <a onclick="confirmation(event)" class="btn-hapus"
                                                                        href="{{ url('delete_sub', $subkontraktor->id) }}"
                                                                        title="Hapus"><i class="fa-solid fa-trash"
                                                                            data-feather="trash-2"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    
                                                </table>
                                                <div class="pagination">
                                                    @if ($subkontraktors->onFirstPage())
                                                        <span>&laquo;</span>
                                                    @else
                                                        <a href="{{ $subkontraktors->previousPageUrl() }}"
                                                            rel="prev">&laquo;</a>
                                                    @endif
    
                                                    @php
                                                        $start = max(1, $subkontraktors->currentPage() - 2);
                                                        $end = min(
                                                            $subkontraktors->lastPage(),
                                                            $subkontraktors->currentPage() + 2,
                                                        );
                                                    @endphp
    
                                                    @if ($start > 1)
                                                        <a href="{{ $subkontraktors->url(1) }}">1</a>
                                                        @if ($start > 2)
                                                            <span>...</span>
                                                        @endif
                                                    @endif
    
                                                    @for ($page = $start; $page <= $end; $page++)
                                                        @if ($page == $subkontraktors->currentPage())
                                                            <a class="active">{{ $page }}</a>
                                                        @else
                                                            <a
                                                                href="{{ $subkontraktors->url($page) }}">{{ $page }}</a>
                                                        @endif
                                                    @endfor
    
                                                    @if ($end < $subkontraktors->lastPage())
                                                        @if ($end < $subkontraktors->lastPage() - 1)
                                                            <span>...</span>
                                                        @endif
                                                        <a
                                                            href="{{ $subkontraktors->url($subkontraktors->lastPage()) }}">{{ $subkontraktors->lastPage() }}</a>
                                                    @endif
    
                                                    @if ($subkontraktors->hasMorePages())
                                                        <a href="{{ $subkontraktors->nextPageUrl() }}"
                                                            rel="next">&raquo;</a>
                                                    @else
                                                        <span>&raquo;</span>
                                                    @endif
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

    </body>

</html>
