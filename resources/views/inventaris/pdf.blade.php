<!-- resources/views/orders/pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Data Pesanan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        img {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>
    <h2>Data Pesanan</h2>
    @php
        $totalPrice = 0;
    @endphp
    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Gambar</th>
                <th>Ukuran</th>
                <th>Kuantitas</th>
                <th>Batas Waktu</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->product_name }}</td>
                <td>
                    @if($order->image)
                        <img src="{{ public_path('order/' . $order->image) }}" alt="">
                    @else
                        No Image
                    @endif
                </td>
                <td>{{ $order->size }}</td>
                <td>{{ $order->quantity }}</td>
                <td>{{ $order->deadline }}</td>
                <td>@currency($order->price)</td>
                <td>@currency($order->total_price)</td>
            </tr>
            @php
                $totalPrice += $order->total_price;
            @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6"><strong>Total</strong></td>
                <td><strong>@currency($totalPrice)</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
