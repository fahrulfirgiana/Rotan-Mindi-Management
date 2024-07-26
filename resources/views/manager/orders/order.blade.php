<!DOCTYPE html>
<html lang="en">

    <head>
        @include('manager.css')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <style>
            .btn-checkbox {
                border-radius: 10px;
                background-color: #474747;
                color: white;
                padding: 0.5rem 1rem;
                display: flex;
                align-items: center;
            }

            .btn-checkbox:hover,
            .btn-checkbox:focus {
                background-color: #3b3b3b;
                color: white;
            }

            .btn-checkbox:active {
                background-color: #3b3b3b;
                color: white;
                transform: translateY(1px);
            }

            .dropdown-menu {
                padding: 10px;
            }

            .dropdown-item {
                display: flex;
                align-items: center;
            }

            .dropdown-item input[type="checkbox"] {
                margin-right: 10px;
            }

            .btn-dropdown {
                border: 1px solid #7D7D7D;
                border-radius: 10px;
                background-color: white;
                padding: 0.5rem 1rem;
                display: flex;
                align-items: center;
            }

            .btn-dropdown:hover,
            .btn-dropdown:focus {
                border: 1px solid #404040;
            }

            .btn-dropdown:active {
                border: 1px solid #404040;
                box-shadow: none;
                transform: translateY(1px);
            }
            
        </style>
    </head>

    <body>
        @include('sweetalert::alert')
        <div class="wrapper">
            @include('manager.sidebar')
            <div class="main">
                @include('manager.navbar')
                <main class="content" style="padding: 10px">
                    <div class="container-fluid p-0">
                        <div class="container-fluid py-1">
                            <div class="container">
                                <div class="card shadow-lg p-3 rounded-pill-4" style="border-radius:30px;">
                                    <div class="container-fluid">
                                        <div class="card-body">
                                            <div class="container">
                                                <div class="py-3">
                                                    <p class="title-page">Data Pesanan</p>
                                                </div>
                                                <div class="container">
                                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
                                                        <a style="border-radius:10px; margin-left:8px;" class="btn btn-secondary btn-sm mb-2 mb-md-0" href="{{ url('view_addorder') }}">
                                                            <i class="fa-solid fa-plus" data-feather="plus"></i> Tambah Data
                                                        </a>
                                                        <div class="d-flex flex-column flex-md-row align-items-center">
                                                            <button class="btn btn-dropdown mb-2 mb-md-0 me-0 me-md-3" id="openFilterModal">
                                                                <span class="material-symbols-outlined" style="font-size: 16px;">page_info</span> Filter
                                                            </button>
                                                            <div class="dropdown">
                                                                <button class="btn btn-checkbox" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    Tampilkan Kolom
                                                                    <span class="material-symbols-outlined" id="oi" style="font-size: 18px;">arrow_drop_down</span>
                                                                </button>
                                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                    <li><label class="dropdown-item"><input type="checkbox" id="toggle-all"> Semua</label></li>
                                                                    <li><label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="progress-column"> Progress</label></li>
                                                                    <li><label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="subkontraktor-column"> Sub-Kontraktor</label></li>
                                                                    <li><label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="customer_name-column"> Nama Pembeli</label></li>
                                                                    <li><label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="address-column"> Alamat</label></li>
                                                                    <li><label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="status-column"> Status</label></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover" id="order-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama Produk</th>
                                                                <th>Gambar</th>
                                                                <th>Ukuran</th>
                                                                <th>Kuantitas</th>
                                                                <th>Harga</th>
                                                                <th>Total</th>
                                                                <th>Pajak</th>
                                                                <th>Biaya Pengiriman</th>
                                                                <th>Total Keseluruhan</th>
                                                                <th>Batas Waktu</th>
                                                                <th class="progress-column hidden">Progress</th>
                                                                <th class="subkontraktor-column hidden">Sub-Kontraktor</th>
                                                                <th class="customer_name-column hidden">Pembeli</th>
                                                                <th class="address-column hidden">Alamat</th>
                                                                <th class="status-column hidden">Status</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($orders as $item)
                                                                <tr>
                                                                    <td>{{ $item->product_name }}</td>
                                                                    <td><img src="{{ url('order') . '/' . $item->image }}" alt=""></td>
                                                                    <td>{{ $item->size }}</td>
                                                                    <td>{{ $item->quantity }}</td>
                                                                    <td>@currency($item->price)</td>
                                                                    <td>@currency($item->total_price)</td>
                                                                    <td>@currency($item->tax)</td>
                                                                    <td>@currency($item->shipping_fee)</td>
                                                                    <td>@currency($item->total_with_tax_and_shipping)</td>
                                                                    <td class="order-date">{{ $item->deadline }}</td>
                                                                    <td class="progress-column hidden">{{ $item->progress ?? 'kosong' }}</td>
                                                                    <td class="subkontraktor-column hidden">{{ $item->subkontraktor_name ?? 'kosong' }}</td>
                                                                    <td class="customer_name-column hidden">{{ $item->customer_name }}</td>
                                                                    <td class="address-column hidden">{{ $item->address }}</td>
                                                                    <td class="status-column hidden">
                                                                        {!! $item->status == 'Selesai'
                                                                            ? '<span class="badge bg-success">Selesai</span>'
                                                                            : ($item->status == 'Diproses'
                                                                                ? '<span class="badge bg-warning">Belum Selesai</span>'
                                                                                : $item->status ?? 'kosong') !!}
                                                                    </td>
                                                                    <td>
                                                                        <a class="btn-edit" href='{{ url('edit_order', $item->id) }}' title="Edit">
                                                                            <i class="fa-regular fa-pen-to-square" data-feather="edit"></i>
                                                                        </a>
                                                                        <a onclick="confirmation(event)" class="btn-hapus" href="{{ url('delete_order', $item->id) }}" title="Delete">
                                                                            <i class="fa-solid fa-trash" data-feather="trash-2"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>    
                                                </div>
                                                <!-- Pagination Links -->
                                                <div class="pagination">
                                                    @if ($orders->onFirstPage())
                                                        <span>&laquo;</span>
                                                    @else
                                                        <a href="{{ $orders->previousPageUrl() }}"
                                                            rel="prev">&laquo;</a>
                                                    @endif

                                                    @php
                                                        $start = max(1, $orders->currentPage() - 2);
                                                        $end = min($orders->lastPage(), $orders->currentPage() + 2);
                                                    @endphp

                                                    @if ($start > 1)
                                                        <a href="{{ $orders->url(1) }}">1</a>
                                                        @if ($start > 2)
                                                            <span>...</span>
                                                        @endif
                                                    @endif

                                                    @for ($page = $start; $page <= $end; $page++)
                                                        @if ($page == $orders->currentPage())
                                                            <a class="active">{{ $page }}</a>
                                                        @else
                                                            <a
                                                                href="{{ $orders->url($page) }}">{{ $page }}</a>
                                                        @endif
                                                    @endfor

                                                    @if ($end < $orders->lastPage())
                                                        @if ($end < $orders->lastPage() - 1)
                                                            <span>...</span>
                                                        @endif
                                                        <a
                                                            href="{{ $orders->url($orders->lastPage()) }}">{{ $orders->lastPage() }}</a>
                                                    @endif

                                                    @if ($orders->hasMorePages())
                                                        <a href="{{ $orders->nextPageUrl() }}"
                                                            rel="next">&raquo;</a>
                                                    @else
                                                        <span>&raquo;</span>
                                                    @endif
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
        <!-- Filter Modal -->
        <div class="modal" id="filterModal">
            <article class="modal-container">
                <header class="modal-container-header">
                    <h1 class="modal-container-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                            aria-hidden="true">
                            <path fill="none" d="M0 0h24v24H0z" />
                            <path fill="currentColor"
                                d="M14 9V4H5v16h6.056c.328.417.724.785 1.18 1.085l1.39.915H3.993A.993.993 0 0 1 3 21.008V2.992C3 2.455 3.449 2 4.002 2h10.995L21 8v1h-7zm-2 2h9v5.949c0 .99-.501 1.916-1.336 2.465L16.5 21.498l-3.164-2.084A2.953 2.953 0 0 1 12 16.95V11zm2 5.949c0 .316.162.614.436.795l2.064 1.36 2.064-1.36a.954.954 0 0 0 .436-.795V13h-5v3.949z" />
                        </svg>
                        Filter Pesanan
                    </h1>
                    <button class="icon-button" id="closeFilterModal">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z" />
                            <path fill="currentColor"
                                d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
                        </svg>
                    </button>
                </header>
                <section class="modal-container-body">
                    <form method="GET" action="{{ route('orders.index') }}">
                        <div class="mb-3">
                            <label for="start-date" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start-date" class="form-control"
                                value="{{ request()->input('start_date') }}">
                        </div>
                        <div class="mb-3">
                            <label for="end-date" class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end-date" class="form-control"
                                value="{{ request()->input('end_date') }}">
                        </div>
                        <div class="modal-container-footer">
                            <button type="submit" class="button is-primary">Filter</button>
                            <a style="text-decoration: none; color: black" href="{{ route('orders.index') }}"
                                class="button is-ghost">Reset</a>
                        </div>
                    </form>
                </section>
            </article>
        </div>
        @include('manager.js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggleAll = document.getElementById('toggle-all');
                const columnToggles = document.querySelectorAll('.column-toggle');

                toggleAll.addEventListener('change', function() {
                    const checked = toggleAll.checked;
                    columnToggles.forEach(toggle => {
                        toggle.checked = checked;
                        document.querySelectorAll(`.${toggle.dataset.column}`).forEach(col => {
                            col.classList.toggle('hidden', !checked);
                        });
                    });
                });

                columnToggles.forEach(toggle => {
                    toggle.addEventListener('change', function() {
                        document.querySelectorAll(`.${toggle.dataset.column}`).forEach(col => {
                            col.classList.toggle('hidden', !toggle.checked);
                        });
                    });
                });
            });

            // JavaScript to handle opening and closing the modal
            document.getElementById('openFilterModal').addEventListener('click', function() {
                document.getElementById('filterModal').classList.add('show');
            });

            document.getElementById('closeFilterModal').addEventListener('click', function() {
                document.getElementById('filterModal').classList.remove('show');
            });

            document.getElementById('closeModalFooterButton').addEventListener('click', function() {
                document.getElementById('filterModal').classList.remove('show');
            });
        </script>
    </body>

</html>
