<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #{{ $order->invoice_number }} - Namina Travel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
            background-color: #f1f5f9;
            padding: 40px 20px;
            font-size: 13px;
            line-height: 1.5;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .action-bar {
            max-width: 800px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 10px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        .btn-print {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }
        .btn-print:hover {
            background: #1d4ed8;
        }
        .btn-back {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .btn-back:hover {
            background: #f8fafc;
        }
        .invoice-card {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            padding: 48px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            position: relative;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 32px;
            border-bottom: 2px solid #f1f5f9;
        }
        .brand-title {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.5px;
        }
        .brand-title span {
            color: #2563eb;
        }
        .brand-meta {
            margin-top: 8px;
            font-size: 12px;
            color: #64748b;
            line-height: 1.6;
        }
        .invoice-meta-right {
            text-align: right;
        }
        .invoice-badge-title {
            font-size: 28px;
            font-weight: 900;
            letter-spacing: 1px;
            color: #0f172a;
        }
        .invoice-number {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            font-weight: 700;
            color: #2563eb;
            margin-top: 4px;
        }
        .status-pill {
            display: inline-block;
            margin-top: 10px;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-paid {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .status-pending {
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }
        .status-rejected {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .grid-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            margin: 32px 0;
        }
        .info-box-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .info-box-name {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .info-box-meta {
            font-size: 12px;
            color: #475569;
            line-height: 1.6;
        }
        .table-invoice {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 24px 0;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .table-invoice th {
            background: #f8fafc;
            padding: 14px 16px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-invoice td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            font-size: 13px;
        }
        .table-invoice tr:last-child td {
            border-bottom: none;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 16px;
        }
        .total-box {
            width: 320px;
            background: #f8fafc;
            border-radius: 12px;
            padding: 16px 20px;
            border: 1px solid #e2e8f0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 8px;
        }
        .total-row.final {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 2px solid #e2e8f0;
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0;
        }
        .total-row.final .amount {
            color: #2563eb;
        }
        .payment-info-box {
            margin-top: 36px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .payment-info-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .payment-bca-logo {
            background: #ffffff;
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid #dbeafe;
        }
        .payment-bca-logo img {
            height: 24px;
            width: auto;
            display: block;
        }
        .payment-text h4 {
            font-size: 13px;
            font-weight: 700;
            color: #1e3a8a;
        }
        .payment-text p {
            font-size: 12px;
            color: #3b82f6;
            margin-top: 2px;
        }
        .payment-acc {
            text-align: right;
            font-family: 'JetBrains Mono', monospace;
        }
        .payment-acc-num {
            font-size: 15px;
            font-weight: 700;
            color: #1e3a8a;
        }
        .payment-acc-name {
            font-size: 11px;
            color: #3b82f6;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
        }
        .invoice-footer {
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid #f1f5f9;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .action-bar {
                display: none !important;
            }
            .invoice-card {
                border: none;
                box-shadow: none;
                padding: 20px;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- Action Bar (Hidden on Print) -->
    <div class="action-bar">
        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Detail Pesanan
        </a>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn btn-print">
                <i class="fas fa-print"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Official Invoice Document -->
    <div class="invoice-card">
        
        <!-- Header -->
        <div class="invoice-header">
            <div>
                <div class="brand-title">
                    <i class="fas fa-compass" style="color: #2563eb;"></i> NAMINA<span>TRAVEL</span>
                </div>
                <div class="brand-meta">
                    <strong>PT Namina Wisata Indonesia</strong><br>
                    Jl. Pariwisata Raya No. 88, Jakarta Selatan<br>
                    WhatsApp: +62 812-3456-7890 &bull; Email: support@naminatravel.com
                </div>
            </div>
            <div class="invoice-meta-right">
                <div class="invoice-badge-title">INVOICE</div>
                <div class="invoice-number">{{ $order->invoice_number }}</div>
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                    Tanggal: {{ $date ?? ($order->created_at ? $order->created_at->format('d M Y') : date('d M Y')) }}
                </div>
                <div>
                    @if($order->payment_status === 'paid')
                        <span class="status-pill status-paid"><i class="fas fa-check-circle"></i> LUNAS / VERIFIED</span>
                    @elseif($order->payment_status === 'pending_verification')
                        <span class="status-pill status-pending"><i class="fas fa-clock"></i> PERLU VERIFIKASI</span>
                    @elseif($order->payment_status === 'rejected')
                        <span class="status-pill status-rejected"><i class="fas fa-times-circle"></i> DITOLAK</span>
                    @else
                        <span class="status-pill status-pending"><i class="fas fa-hourglass-start"></i> MENUNGGU PEMBAYARAN</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Billing & Destination Grid -->
        <div class="grid-info">
            <div>
                <div class="info-box-title">DITAGIHKAN KEPADA (BILL TO):</div>
                <div class="info-box-name">{{ $order->customer_name ?: ($order->user->name ?? 'Pelanggan') }}</div>
                <div class="info-box-meta">
                    Email: {{ $order->customer_email ?: ($order->user->email ?? '-') }}<br>
                    No. Handphone: {{ $order->customer_phone ?: ($order->user->phone ?? '-') }}
                </div>
            </div>
            <div>
                <div class="info-box-title">RINCIAN JADWAL WISATA:</div>
                <div class="info-box-name">{{ $order->destination->name ?? 'Paket Wisata Namina' }}</div>
                <div class="info-box-meta">
                    Destinasi: {{ $order->destination->location ?? 'Indonesia' }}<br>
                    Tanggal Keberangkatan: <strong>{{ $order->departure_date ? \Carbon\Carbon::parse($order->departure_date)->format('d F Y') : '-' }}</strong><br>
                    Durasi: {{ $order->destination->duration ?? '3 Hari 2 Malam' }}
                </div>
            </div>
        </div>

        <!-- Table of Line Items -->
        <table class="table-invoice">
            <thead>
                <tr>
                    <th style="width: 50%;">Deskripsi Layanan & Paket</th>
                    <th class="text-center" style="width: 15%;">Peserta</th>
                    <th class="text-right" style="width: 15%;">Harga Satuan</th>
                    <th class="text-right" style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $unitPrice = $order->destination->price ?? 0;
                    $subtotal = $unitPrice * $order->quantity;
                    $discount = $subtotal - $order->total;
                @endphp
                <tr>
                    <td>
                        <strong>{{ $order->destination->name ?? 'Paket Perjalanan Wisata' }}</strong>
                        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                            Termasuk transportasi, akomodasi, pemandu wisata profesional & tiket objek wisata.
                        </div>
                    </td>
                    <td class="text-center"><strong>{{ $order->quantity }}</strong> Orang</td>
                    <td class="text-right">Rp {{ number_format($unitPrice, 0, ',', '.') }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Totals Calculation -->
        <div class="total-section">
            <div class="total-box">
                <div class="total-row">
                    <span>Subtotal Layanan</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                @if($discount > 0)
                    <div class="total-row" style="color: #16a34a; font-weight: 600;">
                        <span>Diskon Khusus</span>
                        <span>- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="total-row">
                    <span>Biaya Admin & Pajak</span>
                    <span>Rp 0</span>
                </div>
                <div class="total-row final">
                    <span>Total Pembayaran</span>
                    <span class="amount">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Official BCA Settlement Box -->
        <div class="payment-info-box">
            <div class="payment-info-left">
                <div class="payment-bca-logo">
                    <img src="{{ asset('images/banks/bca.png') }}" alt="Bank BCA">
                </div>
                <div class="payment-text">
                    <h4>Metode Pembayaran: Bank BCA Transfer Manual</h4>
                    <p>Pembayaran resmi atas nama pemilik usaha Namina Travel</p>
                </div>
            </div>
            <div class="payment-acc">
                <div class="payment-acc-num">0060849510</div>
                <div class="payment-acc-name">a.n. RIFQI AUFA RACHMADI</div>
            </div>
        </div>

        <!-- Invoice Footer -->
        <div class="invoice-footer">
            Dokumen ini dicetak otomatis secara elektronik oleh sistem informasi Namina Travel dan berlaku sebagai tanda bukti pemesanan yang sah.<br>
            Harap simpan invoice ini untuk ditunjukkan saat proses check-in / keberangkatan rombongan tour.
        </div>

    </div>

</body>
</html>
