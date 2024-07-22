<!DOCTYPE html>
<html lang="en">
<head>
    @include('inventaris.css')
    <style>
        /* public/css/style.css */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.wrapper {
    display: flex;
}

.main {
    flex-grow: 1;
}

.content {
    padding: 20px;
}

.card {
    background-color: #fff;
    border-radius: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.title-page {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table th, table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

table th {
    background-color: #f2f2f2;
    color: #333;
}

.image-column img {
    max-width: 100px;
    border-radius: 5px;
}

.badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 5px;
    color: #fff;
    font-size: 12px;
}

.bg-success {
    background-color: #28a745;
}

.bg-warning {
    background-color: #ffc107;
}

.hidden {
    display: none;
}

.form-group {
    margin-bottom: 20px;
}

.form-group ul {
    list-style-type: none;
    padding: 0;
}

.form-group ul li {
    background-color: #f9f9f9;
    padding: 10px;
    margin-bottom: 5px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.btn-i {
    text-align: right;
}

.btn-i .btn {
    padding: 10px 20px;
    color: #fff;
    background-color: #333;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
}

.btn-i .btn:hover {
    background-color: #555;
}

    </style>
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
                                    <p class="title-page">Detail Pesanan</p>
                                </div>
                                <table>
                                    <tr>
                                        <th>Product Name</th>
                                        <td>{{ $order->product_name }}</td>
                                    </tr>
                                    <tr class="image-column">
                                        <th>Image</th>
                                        <td><img src="{{ url('order') . '/' . $order->image }}" alt="{{ $order->product_name }}"></td>
                                    </tr>
                                    <tr>
                                        <th>Size</th>
                                        <td>{{ $order->size }}</td>
                                    </tr>
                                    <tr>
                                        <th>Quantity</th>
                                        <td>{{ $order->quantity }}</td>
                                    </tr>
                                    <tr>
                                        <th>Price</th>
                                        <td>@currency($order->price)</td>
                                    </tr>
                                    <tr>
                                        <th>Total Price</th>
                                        <td>@currency($order->total_price)</td>
                                    </tr>
                                    <tr>
                                        <th>Deadline</th>
                                        <td>{{ $order->deadline }}</td>
                                    </tr>
                                    <tr>
                                        <th>Progress</th>
                                        <td>{{ $order->progress ?? 'kosong' }}</td>
                                    </tr>
                                    <tr class="subkontraktor-column">
                                        <th>Subkontraktor</th>
                                        <td>{{ $order->subkontraktor_name ?? 'kosong' }}</td>
                                    </tr>
                                    <tr class="status-column">
                                        <th>Status</th>
                                        <td>
                                            {!! $order->status == 'Selesai'
                                                ? '<span class="badge bg-success">Selesai</span>'
                                                : ($order->status == 'Diproses'
                                                ? '<span class="badge bg-warning">Belum Selesai</span>'
                                                : $order->status ?? 'kosong') !!}
                                        </td>
                                    </tr>
                                </table>

                                <div style="border-radius:20px">
                                    <div class="form-group">
                                        <label>Riwayat Progres</label>
                                        <ul>
                                            @foreach ($order->progressHistories as $history)
                                                <li>Progres: {{ $history->progress }} pada {{ $history->created_at }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="btn-i">
                                        <a href="{{ url('/show_order') }}" class="btn btn-dark">Kembali</a>
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
