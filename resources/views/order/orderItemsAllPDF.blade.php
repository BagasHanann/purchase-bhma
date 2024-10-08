<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Pembelian</title>
    <style>
        /* CSS styles for your PDF content */
        body {
            font-family: Arial, sans-serif;
            margin: 1cm 2cm;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 0.5rem;
        }
        th {
            background-color: #f0f0f0;
            text-align: left;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <h1>Daftar Semua Pembelian</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Product Name</th>
                <th>Supplier Name</th>
                <th>Stock</th>
                <th>Price</th>
                <th>Total Price</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orderItems as $index => $orderItem)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $orderItem->product->name }}</td>
                <td>{{ $orderItem->supplier->name }}</td>
                <td>{{ $orderItem->stock }}</td>
                <td>Rp {{ number_format($orderItem->price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($orderItem->total_price, 0, ',', '.') }}</td>
                <td>{{ $orderItem->date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
