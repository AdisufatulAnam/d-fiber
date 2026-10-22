<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Pembayaran {{ $payment->invoice_number ?? $payment->id }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 12px; color: #1f2937;
            margin: 0; padding: 0;
            background: #f1f5f9;
        }
        .card {
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 14px;
            margin: 26px;
            padding: 28px 32px 26px;
        }

        /* Header */
        .header { width: 100%; }
        .brand { font-size: 30px; font-weight: bold; color: #1d63ed; margin: 0; line-height: 1.1; }
        .tagline { font-size: 12px; color: #64748b; margin: 4px 0 0; }
        .doc-title { text-align: right; font-size: 22px; font-weight: bold; color: #0f172a; letter-spacing: 0.5px; margin: 0; }
        .invoice-no { text-align: right; font-size: 12.5px; color: #64748b; margin: 4px 0 0; }
        .divider { border-top: 1px solid #e2e8f0; margin: 16px 0 14px; }

        /* Badge status */
        .badge {
            display: inline-block;
            background: #22c55e; color: #ffffff;
            font-size: 11px; font-weight: bold; letter-spacing: 0.6px;
            padding: 4px 14px; border-radius: 999px;
        }

        /* Info dua kolom */
        .info { width: 100%; margin-top: 10px; }
        .info td { vertical-align: top; }
        .info .col-left { width: 56%; padding: 6px 22px 2px 0; }
        .info .col-right { width: 44%; border-left: 1px solid #e2e8f0; padding: 6px 0 2px 24px; }
        .info .col-right .label { width: 72px; }
        .info .col-right .value { white-space: nowrap; }
        .meta { width: 100%; }
        .meta td { padding: 4px 0; vertical-align: top; }
        .meta .label { color: #64748b; width: 104px; }
        .meta .colon { color: #64748b; width: 12px; }
        .meta .value { color: #1f2937; }

        /* Tabel item */
        table.items { width: 100%; border: 1px solid #e2e8f0; border-collapse: collapse; margin-top: 16px; }
        table.items th { background: #2563eb; color: #ffffff; font-size: 12px; padding: 9px 14px; }
        table.items th.h-left { text-align: left; }
        table.items th.h-center { text-align: center; }
        table.items th.h-right { text-align: right; }
        table.items td { padding: 12px 14px; border-top: 1px solid #e2e8f0; color: #1f2937; }
        table.items td.t-center { text-align: center; }
        table.items td.t-right { text-align: right; }
        table.items .desc-main { font-weight: bold; font-size: 12.5px; }
        table.items .desc-sub { font-size: 10.5px; color: #94a3b8; }

        /* Total */
        .total-wrap {
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 8px;
            padding: 10px 16px;
            margin-top: 14px;
        }
        .total-wrap table { width: 100%; border-collapse: collapse; }
        .total-wrap td { padding: 0; }
        .total-label { text-align: right; font-size: 13px; color: #475569; }
        .total-sep { color: #cbd5e1; padding: 0 10px; font-size: 13px; }
        .total-amount { text-align: right; font-size: 20px; font-weight: bold; color: #1d63ed; white-space: nowrap; }

        /* Footer */
        .footer { margin-top: 18px; border-top: 1px dashed #cbd5e1; padding-top: 14px; }
        .footer-table { width: 100%; }
        .footer-table td { vertical-align: top; padding: 0; }
        .footer-icon { width: 30px; }
        .thanks { font-size: 11.5px; color: #475569; line-height: 1.5; }
        .thanks strong { color: #1d63ed; }
        .signature { margin-top: 14px; font-size: 11.5px; color: #64748b; line-height: 1.5; }
        .signature strong { color: #0f172a; }
    </style>
</head>
<body>
@php
    $wifiIcon = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBmaWxsPSJub25lIiBzdHJva2U9IiMyNTYzZWIiIHN0cm9rZS13aWR0aD0iMi4yIiBzdHJva2UtbGluZWNhcD0icm91bmQiPjxwYXRoIGQ9Ik01IDEyLjU1YTExIDExIDAgMCAxIDE0LjA4IDAiLz48cGF0aCBkPSJNMS40MiA5YTE2IDE2IDAgMCAxIDIxLjE2IDAiLz48cGF0aCBkPSJNOC41MyAxNi4xMWE2IDYgMCAwIDEgNi45NSAwIi8+PGNpcmNsZSBjeD0iMTIiIGN5PSIyMCIgcj0iMS4yIiBmaWxsPSIjMjU2M2ViIiBzdHJva2U9Im5vbmUiLz48L3N2Zz4=';
@endphp

<div class="card">
    <table class="header">
        <tr>
            <td>
                <p class="brand">{{ $brand }}</p>
                <p class="tagline">Layanan Internet WiFi</p>
            </td>
            <td style="text-align:right;">
                <p class="doc-title">PEMBAYARAN</p>
                <p class="invoice-no">{{ $payment->invoice_number ?? ('#' . $payment->id) }}</p>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <span class="badge">&#10004; LUNAS</span>

    <table class="info">
        <tr>
            <td class="col-left">
                <table class="meta">
                    <tr>
                        <td class="label">Pelanggan</td>
                        <td class="colon">:</td>
                        <td class="value"><strong>{{ $customer->name ?? $payment->customer_name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">ID Pelanggan</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $customer->qr_code ?: ($customer->id ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td class="label">No. WhatsApp</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Alamat</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $customer->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Paket</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $customer->package->name ?? '-' }}</td>
                    </tr>
                </table>
            </td>
            <td class="col-right">
                <table class="meta">
                    <tr>
                        <td class="label">Periode</td>
                        <td class="colon">:</td>
                        <td class="value"><strong>{{ $periode }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Metode</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $payment->payment_method === 'qrcode' ? 'QR Code' : 'Tunai/Manual' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $tanggal }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th class="h-left">Deskripsi</th>
                <th class="h-center">Periode</th>
                <th class="h-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <span class="desc-main">Biaya layanan internet WiFi</span><br>
                    <span class="desc-sub">{{ $customer->package->name ?? 'Paket Internet' }}</span>
                </td>
                <td class="t-center">{{ $periode }}</td>
                <td class="t-right">{{ $payment->amountLabel() }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-wrap">
        <table>
            <tr>
                <td></td>
                <td class="total-label" style="text-align:right;">Total Pembayaran</td>
                <td class="total-sep">|</td>
                <td class="total-amount" style="width:160px;">{{ $payment->amountLabel() }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="footer-icon"><img src="{{ $wifiIcon }}" width="20" height="20" alt=""></td>
                <td class="thanks">
                    Terima kasih atas kepercayaan Anda menggunakan layanan internet <strong>{{ $brand }}</strong>.<br>
                    Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas.
                </td>
            </tr>
        </table>
        <p class="signature">
            Hormat kami,<br>
            <strong>Admin {{ $brand }}</strong>
        </p>
    </div>
</div>
</body>
</html>
