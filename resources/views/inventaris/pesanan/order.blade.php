<!DOCTYPE html>
<html lang="en">

<head>
    @include('inventaris.css')
    <link href="{{ asset('assets/css/modal.css') }}" rel="stylesheet">
    <style>
        .btn-dropdown, .btn-checkbox {
            border: 1px solid #7D7D7D;
            border-radius: 10px;
            background-color: white;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            font-size: 13px;
        }

        .btn-dropdown:hover, .btn-dropdown:focus, .btn-checkbox:hover, .btn-checkbox:focus {
            border: 1px solid #404040;
        }

        .btn-dropdown:active, .btn-checkbox:active {
            border: 1px solid #404040;
            box-shadow: none;
            transform: translateY(1px);
        }

        .btn-checkbox {
            background-color: #474747;
            color: white;
        }

        .btn-checkbox:hover, .btn-checkbox:focus {
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

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        .form-group button {
            background-color: #007bff;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .filter-button-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .btn-dropdown, .btn-checkbox {
                font-size: 14px;
                padding: 0.5rem;
            }

            .filter-button-group {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            .btn-dropdown, .btn-checkbox {
                font-size: 12px;
                padding: 0.4rem;
            }

            .filter-button-group {
                gap: 5px;
                align-items: flex-start;
            }
        }
    </style>

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
                            <div class="card shadow-lg p-3 rounded-pill-4" style="border-radius: 30px;">
                                <div class="container-fluid">
                                    <div class="card-body">
                                        <div class="container">
                                            <div class="py-3">
                                                <p class="title-page">Data Pesanan</p>
                                            </div>

                                            <div class="btn-i">
                                                <!-- Dropdown for column visibility -->
                                                <div class="dropdown">
                                                    <button class="btn btn-checkbox" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Tampilkan Kolom
                                                        <span class="material-symbols-outlined" id="oi" style="font-size: 18px;">
                                                            arrow_drop_down
                                                        </span>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <li>
                                                            <label class="dropdown-item">
                                                                <input type="checkbox" id="toggle-all"> Semua
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label class="dropdown-item">
                                                                <input type="checkbox" class="column-toggle" data-column="status-column"> Status
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label class="dropdown-item">
                                                                <input type="checkbox" class="column-toggle" data-column="total-column"> Total Harga
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label class="dropdown-item">
                                                                <input type="checkbox" class="column-toggle" data-column="deadline-column">
                                                                Batas Waktu
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <!-- Filter button group -->
                                                <div class="filter-button-group">
                                                    <!-- Button untuk membuka modal filter -->
                                                    <button class="btn btn-dropdown" class="btn-filter" id="openFilterModal">
                                                        <span class="material-symbols-outlined" style="font-size: 16px;">
                                                            page_info
                                                        </span> Filter
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="table-responsive" style="border-radius: 8px;">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Kode</th>
                                                            <th>Gambar</th>
                                                            <th>Ukuran</th>
                                                            <th>Kuantitas</th>
                                                            <th>Harga</th>
                                                            <th class="total-column hidden">Total</th>
                                                            <th class="deadline-column hidden">Batas Waktu</th>
                                                            <th>Progress</th>
                                                            <th>Sub-Kontraktor</th>
                                                            <th class="status-column hidden">Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($orders as $order)
                                                        <tr>
                                                            <td>{{ $order->product_name }}</td>
                                                            <td>
                                                                <img src="{{ url('order') . '/' . $order->image }}" alt="">
                                                            </td>
                                                            <td>{{ $order->size }}</td>
                                                            <td>{{ $order->quantity }}</td>
                                                            <td>@currency($order->price)</td>
                                                            <td class="total-column hidden">@currency($order->total_price)</td>
                                                            <td class="deadline-column hidden">{{ $order->deadline }}</td>
                                                            <td>{{ $order->progress ?? 'kosong' }}</td>
                                                            <td>{{ $order->subkontraktor_name ?? 'kosong' }}</td>
                                                            <td class="status-column hidden">
                                                                {!! $order->status == 'Selesai'
                                                                ? '<span class="badge bg-success">Selesai</span>'
                                                                : ($order->status == 'Diproses'
                                                                ? '<span class="badge bg-warning">Belum Selesai</span>'
                                                                : $order->status ?? 'kosong') !!}
                                                            </td>
                                                            <td>
                                                                <a class="btn-edit" style="padding: 5px;" href="{{ url('edit_pesanan', $order->id) }}" title="Edit"><i class="fa-regular fa-pen-to-square" data-feather="edit"></i></a>
                                                                <a class="btn-edit" href="{{ url('detail_pesanan', $order->id) }}" title="Detail"><i class="fa-regular fa-eye" data-feather="info"></i></a>
                                                                <a class="btn-edit" href="#" title="Kirim Pesan" onclick="sendMessage('{{ $order->id }}')"><i class="fa-regular fa-envelope" data-feather="send"></i></a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <br>
                                            <div class="pagination">
                                                @if ($orders->onFirstPage())
                                                <span>&laquo;</span>
                                                @else
                                                <a href="{{ $orders->previousPageUrl() }}" rel="prev">&laquo;</a>
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
                                                <a href="{{ $orders->url($page) }}">{{ $page }}</a>
                                                @endif
                                                @endfor

                                                @if ($end < $orders->lastPage())
                                                @if ($end < $orders->lastPage() - 1)
                                                <span>...</span>
                                                @endif
                                                <a href="{{ $orders->url($orders->lastPage()) }}">{{ $orders->lastPage() }}</a>
                                                @endif

                                                @if ($orders->hasMorePages())
                                                <a href="{{ $orders->nextPageUrl() }}" rel="next">&raquo;</a>
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

    <!-- Filter Modal -->
    <div class="modal" id="filterModal">
        <article class="modal-container">
            <header class="modal-container-header">
                <h1 class="modal-container-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true">
                        <path fill="none" d="M0 0h24v24H0z" />
                        <path fill="currentColor" d="M14 9V4H5v16h6.056c.328.417.724.785 1.18 1.085l1.39.915H3.993A.993.993 0 0 1 3 21.008V2.992C3 2.455 3.449 2 4.002 2h10.995L21 8v1h-7zm-2 2h9v5.949c0 .99-.501 1.916-1.336 2.465L16.5 21.498l-3.164-2.084A2.953 2.953 0 0 1 12 16.95V11zm2 5.949c0 .316.162.614.436.795l2.064 1.36 2.064-1.36a.954.954 0 0 0 .436-.795V13h-5v3.949z" />
                    </svg>
                    Filter Pesanan
                </h1>
                <button class="icon-button" id="closeFilterModal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="none" d="M0 0h24v24H0z" />
                        <path fill="currentColor" d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
                    </svg>
                </button>
            </header>
            <section class="modal-container-body">
                <form method="GET" action="{{ route('orders.show') }}">
                    <div class="mb-3">
                        <label for="subkontraktor" class="form-label">Pilih Subkontraktor</label>
                        <select class="form-control" id="subkontraktor" name="subkontraktor">
                            <option value="">Pilih Subkontraktor</option>
                            @foreach ($subkontraktors as $subkontraktor)
                            <option value="{{ $subkontraktor->subkontraktor_name }}" {{ request()->input('subkontraktor') == $subkontraktor->subkontraktor_name ? 'selected' : '' }}>
                                {{ $subkontraktor->subkontraktor_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="start-date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start-date" class="form-control" value="{{ request()->input('start_date') }}">
                    </div>
                    <div class="mb-3">
                        <label for="end-date" class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end-date" class="form-control" value="{{ request()->input('end_date') }}">
                    </div>
                    <div class="modal-container-footer">
                        <button type="submit" class="button is-primary">Filter</button>
                        <a style="text-decoration: none; color: black" href="{{ route('orders.show') }}" class="button is-ghost">Reset</a>
                    </div>
                </form>
            </section>
        </article>
    </div>

    @include('inventaris.js')
    <script>
        // JavaScript to toggle column visibility
        document.querySelectorAll('.column-toggle').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var columnClass = this.getAttribute('data-column');
                var columns = document.querySelectorAll('.' + columnClass);
                columns.forEach(function(column) {
                    column.classList.toggle('hidden', !checkbox.checked);
                });
            });
        });

        // Toggle all columns visibility based on "Tampilkan Semua" checkbox
        document.getElementById('toggle-all').addEventListener('change', function() {
            var isChecked = this.checked;
            document.querySelectorAll('.column-toggle').forEach(function(checkbox) {
                checkbox.checked = isChecked;
                var columnClass = checkbox.getAttribute('data-column');
                var columns = document.querySelectorAll('.' + columnClass);
                columns.forEach(function(column) {
                    column.classList.toggle('hidden', !isChecked);
                });
            });
        });

        // Initial column visibility based on checkbox state
        document.querySelectorAll('.column-toggle').forEach(function(checkbox) {
            checkbox.checked = false; // All columns are hidden by default
            var columnClass = checkbox.getAttribute('data-column');
            var columns = document.querySelectorAll('.' + columnClass);
            columns.forEach(function(column) {
                column.classList.add('hidden');
            });
        });

        // JavaScript to handle opening and closing the filter modal
        document.getElementById('openFilterModal').addEventListener('click', function() {
            document.getElementById('filterModal').classList.add('show');
        });

        document.getElementById('closeFilterModal').addEventListener('click', function() {
            document.getElementById('filterModal').classList.remove('show');
        });

        // Function to send WhatsApp message
        function sendMessage(orderId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/send-message-whatsapp';
            form.style.display = 'none';

            const csrfInput = document.createElement('input');
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const orderInput = document.createElement('input');
            orderInput.name = 'order_id';
            orderInput.value = orderId;
            form.appendChild(orderInput);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>

</html>
