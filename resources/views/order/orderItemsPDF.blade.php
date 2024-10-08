<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h4 {
            margin: 0;
        }
        .w-full {
            width: 100%;
        }
        .w-half {
            width: 50%;
        }
        .margin-top {
            margin-top: 1.25rem;
        }
        .footer {
            font-size: 0.875rem;
            padding: 1rem;
            background-color: rgb(204 255 204);
        }
        table {
            width: 100%;
            border-spacing: 0;
        }
        table.products {
            font-size: 0.875rem;
        }
        table.products th, table.products td {
            text-align: left;
            padding: 0.5rem;
        }
        table.products td {
            color: #ffffff;
        }
        table.products tr {
            background-color: rgb(0 102 0);
        }
        table.products th {
            color: #ffffff;
        }
        table tr.items {
            background-color: rgb(0 204 0);
        }
        .total {
            text-align: right;
            margin-top: 1rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <table class="w-full">
        <tr>
            <td class="w-half">
                <img src="{{ public_path('logo.png') }}" width="100" />
            </td>
            <td class="w-half">
                <h4>No Permintaan: {{ $order->request_id }}</h4>
            </td>
        </tr>
    </table>

    <div class="margin-top">
        <table class="w-full">
            <tr>
                <td class="w-half">
                    <div><h4>To:</h4></div>
                    <div>{{ $order->supplier->name }}</div>
                    <div>{{ $order->supplier->address }}</div>
                </td>
                <td class="w-half">
                    <div><h4>From:</h4></div>
                    <div>PT. Berkat Hijau Makmur Abadi</div>
                    <div>Jl. Pemuda No.34, RT.7/RW.5, Rawamangun, Kec. Pulo Gadung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13220</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="margin-top">
        <table class="products">
            <tr>
                <th>Jumlah</th>
                <th>Deskripsi Barang</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
            </tr>
            <tr class="items">
                <td>{{ $order->stock }}</td>
                <td>{{ $order->product->name }}</td>
                <td>Rp. {{ number_format($order->price, 2, ',', '.') }}</td>
                <td>Rp. {{ number_format($order->total_price, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="total">
        Total: Rp. {{ number_format($order->total_price, 2, ',', '.') }}
    </div>

    <div class="footer margin-top">
        <div>Thank you</div>
        <div>&copy; Berkat Hijau Makmur Abadi</div>
    </div>
</body>
</html>