<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $payment->invoice_number ?? $payment->id }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 12px; color: #1f2937; margin: 0; padding: 24px;
        }
        .header { border-bottom: 3px solid #2563eb; padding-bottom: 12px; margin-bottom: 16px; }
        .brand { font-size: 22px; font-weight: bold; color: #2563eb; margin: 0; }
        .tagline { font-size: 11px; color: #6b7280; margin: 2px 0 0; }
        .title { text-align: right; font-size: 20px; font-weight: bold; color: #111827; }
        .invoice-no { text-align: right; font-size: 12px; color: #374151; }
        .meta { width: 100%; margin-bottom: 16px; }
        .meta td { vertical-align: top; padding: 2px 0; }
        .meta .label { color: #6b7280; width: 110px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.items th { background: #2563eb; color: #fff; text-align: left; padding: 8px; font-size: 11px; }
        table.items td { padding: 8px; border-bottom: 1px solid #e5e7eb; }
        table.items .right { text-align: right; }
        .total-box { width: 100%; }
        .total-box td { padding: 4px 8px; }
        .total-box .grand { background: #eff6ff; font-size: 15px; font-weight: bold; color: #1e40af; }
        .footer { margin-top: 24px; border-top: 1px dashed #d1d5db; padding-top: 12px; font-size: 11px; color: #6b7280; }
        .status { display: inline-block; background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 4px; font-weight: bold; font-size: 11px; }
    </style>
</head>
<body>
    <table class="header" style="border:none;width:100%;">
        <tr>
            <td style="border:none;">
                <p class="brand">{{ $brand }}</p>
                <p class="tagline">Layanan Internet WiFi</p>
            </td>
            <td style="border:none;">
                <div class="title">INVOICE</div>
                <div class="invoice-no">{{ $payment->invoice_number ?? ('#' . $payment->id) }}</div>
            </td>
        </tr>
    </table>

    <table class="meta">
        <tr>
            <td>
                <div style="margin-bottom:6px;"><span class="status">LUNAS</span></div>
                <table>
                    <tr><td class="label">Pelanggan</td><td><strong>{{ $customer->name ?? $payment->customer_name }}</strong></td></tr>
                    <tr><td class="label">ID Pelanggan</td><td>{{ $customer->id ?? '-' }}</td></tr>
                    <tr><td class="label">No. WhatsApp</td><td>{{ $phone ?? '-' }}</td></tr>
                    <tr><td class="label">Alamat</td><td>{{ $customer->address ?? '-' }}</td></tr>
                    <tr><td class="label">Paket</td><td>{{ $customer->package->name ?? '-' }}</td></tr>
                </table>
            </td>
            <td style="text-align:right;">
                <table style="margin-left:auto;">
                    <tr><td class="label">Periode</td><td><strong>{{ $periode }}</strong></td></tr>
                    <tr><td class="label">Metode</td><td>{{ $payment->payment_method === 'qrcode' ? 'QR Code' : 'Tunai/Manual' }}</td></tr>
                    <tr><td class="label">Tanggal</td><td>{{ $tanggal }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Periode</th>
                <th class="right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    Biaya layanan internet WiFi<br>
                    <span style="font-size:10px;color:#6b7280;">{{ $customer->package->name ?? 'Paket Internet' }}</span>
                </td>
                <td>{{ $periode }}</td>
                <td class="right">{{ $payment->amountLabel() }}</td>
            </tr>
        </tbody>
    </table>

    <table class="total-box">
        <tr>
            <td style="text-align:right;">Total Pembayaran</td>
            <td class="grand right" style="width:140px;text-align:right;">{{ $payment->amountLabel() }}</td>
        </tr>
    </table>

    <div class="footer">
        Terima kasih atas kepercayaan Anda menggunakan layanan internet <strong>{{ $brand }}</strong>.<br>
        Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas.<br><br>
        Hormat kami,<br><strong>Admin {{ $brand }}</strong>
    </div>
</body>
</html>
