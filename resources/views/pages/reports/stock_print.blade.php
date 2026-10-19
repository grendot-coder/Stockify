<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Persediaan Stok — Stockify Warehouse</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'IBM Plex Sans', sans-serif;
            color: #292524;
            background: #ffffff;
            margin: 0;
            padding: 24px;
            font-size: 11px;
        }
        h1, h2, h3, .font-heading {
            font-family: 'Space Grotesk', sans-serif;
        }
        .tabular-nums, .font-mono {
            font-family: 'IBM Plex Mono', monospace;
            font-variant-numeric: tabular-nums;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        th, td {
            border: 1px solid #D6D3D1;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #FAF7F4;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.05em;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 9px;
        }
        .badge-safe { background: #ECFDF5; color: #047857; }
        .badge-low { background: #FEF3C7; color: #B45309; }
        .badge-out { background: #FEE2E2; color: #B91C1C; }
        .no-print {
            margin-bottom: 20px;
            padding: 12px;
            background: #FAF7F4;
            border: 1px solid #E7E0DA;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-print {
            background: #BD6745;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <div>
            <strong>Mode Pratinjau Cetak / PDF</strong> — Pastikan ukuran kertas diatur ke A4 (Landscape atau Portrait).
        </div>
        <button onclick="window.print()" class="btn-print">Cetak Sekarang (Print / PDF)</button>
    </div>

    <!-- Kop Dokumen Resmi -->
    <div style="border-bottom: 2px solid #292524; padding-bottom: 12px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <div style="font-family: 'Space Grotesk', sans-serif; font-size: 20px; font-weight: 700; color: #BD6745; letter-spacing: -0.02em;">
                STOCKIFY <span style="font-size: 11px; color: #78716C; font-weight: 500; font-family: 'IBM Plex Sans';">Warehouse Management System</span>
            </div>
            <h1 style="font-size: 16px; font-weight: 700; margin: 4px 0 0 0;">LAPORAN PERSEDIAAN & AUDIT NILAI STOK GUDANG</h1>
            <div style="color: #78716C; font-size: 10px; margin-top: 2px;">
                Dokumen resmi rekapitulasi inventaris fisik dan valuasi aset
            </div>
        </div>
        <div style="text-align: right; font-size: 10px; color: #78716C;">
            <div>Tanggal Cetak: <strong class="font-mono" style="color: #292524;">{{ now()->translatedFormat('d F Y, H:i') }} WIB</strong></div>
            <div>Dicetak Oleh: <strong style="color: #292524;">{{ auth()->user()->name }} ({{ strtoupper(auth()->user()->role) }})</strong></div>
        </div>
    </div>

    <!-- Ringkasan Filter & Metrik -->
    <div style="display: flex; gap: 16px; margin-bottom: 16px;">
        <div style="flex: 1; border: 1px solid #E7E0DA; padding: 10px; border-radius: 6px; background: #FAF7F4;">
            <div style="font-size: 9px; text-transform: uppercase; color: #78716C; font-weight: 600;">Total Item Terdaftar</div>
            <div style="font-size: 16px; font-weight: 700; color: #292524; margin-top: 2px;" class="font-mono">
                {{ number_format($totalItems) }} SKU
            </div>
        </div>
        <div style="flex: 1; border: 1px solid #E7E0DA; padding: 10px; border-radius: 6px; background: #FAF7F4;">
            <div style="font-size: 9px; text-transform: uppercase; color: #78716C; font-weight: 600;">Total Unit Fisik Aktual</div>
            <div style="font-size: 16px; font-weight: 700; color: #BD6745; margin-top: 2px;" class="font-mono">
                {{ number_format($totalQty) }} Unit
            </div>
        </div>
        <div style="flex: 1; border: 1px solid #E7E0DA; padding: 10px; border-radius: 6px; background: #FAF7F4;">
            <div style="font-size: 9px; text-transform: uppercase; color: #78716C; font-weight: 600;">Total Nilai Persediaan</div>
            <div style="font-size: 16px; font-weight: 700; color: #292524; margin-top: 2px;" class="font-mono">
                Rp {{ number_format($totalValuation, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- Tabel Data Stok -->
    <table>
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th style="width: 100px;">SKU Produk</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Supplier Rekanan</th>
                <th class="text-right" style="width: 70px;">Stok Fisik</th>
                <th class="text-right" style="width: 60px;">Min. Stok</th>
                <th class="text-right" style="width: 90px;">Harga Beli</th>
                <th class="text-right" style="width: 100px;">Total Valuasi</th>
                <th class="text-center" style="width: 70px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $idx => $p)
            @php
                $isOut = $p->stock <= 0;
                $isLow = !$isOut && $p->stock <= $p->min_stock;
                $val = $p->stock * $p->purchase_price;
            @endphp
            <tr>
                <td class="text-center font-mono">{{ $idx + 1 }}</td>
                <td class="font-mono" style="font-weight: 600;">{{ $p->sku }}</td>
                <td>{{ $p->name }}</td>
                <td>{{ $p->category?->name ?? '-' }}</td>
                <td>{{ $p->supplier?->name ?? '-' }}</td>
                <td class="text-right font-mono" style="font-weight: 700;">{{ number_format($p->stock) }}</td>
                <td class="text-right font-mono" style="color: #78716C;">{{ number_format($p->min_stock) }}</td>
                <td class="text-right font-mono">Rp {{ number_format($p->purchase_price, 0, ',', '.') }}</td>
                <td class="text-right font-mono" style="font-weight: 700;">Rp {{ number_format($val, 0, ',', '.') }}</td>
                <td class="text-center">
                    @if($isOut)
                        <span class="badge badge-out">HABIS</span>
                    @elseif($isLow)
                        <span class="badge badge-low">TIPIS</span>
                    @else
                        <span class="badge badge-safe">AMAN</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center" style="padding: 24px; color: #78716C;">
                    Tidak ada data stok produk ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background: #FAF7F4; font-weight: 700;">
                <td colspan="5" class="text-right">TOTAL KESELURUHAN:</td>
                <td class="text-right font-mono" style="color: #BD6745;">{{ number_format($totalQty) }}</td>
                <td class="text-right">-</td>
                <td class="text-right">-</td>
                <td class="text-right font-mono">Rp {{ number_format($totalValuation, 0, ',', '.') }}</td>
                <td class="text-center">-</td>
            </tr>
        </tfoot>
    </table>

    <!-- Tanda Tangan Pengesahan -->
    <div style="margin-top: 40px; display: flex; justify-content: space-between; page-break-inside: avoid;">
        <div style="width: 200px; text-align: center;">
            <div style="color: #78716C; margin-bottom: 50px;">Disiapkan Oleh (Staff / Petugas):</div>
            <div style="border-top: 1px solid #292524; padding-top: 4px; font-weight: 600;">
                {{ auth()->user()->name }}
            </div>
            <div style="color: #78716C; font-size: 9px;">Tanggal: {{ date('d/m/Y') }}</div>
        </div>

        <div style="width: 200px; text-align: center;">
            <div style="color: #78716C; margin-bottom: 50px;">Diverifikasi Oleh (Manajer Logistik):</div>
            <div style="border-top: 1px solid #292524; padding-top: 4px; font-weight: 600;">
                ( .................................................. )
            </div>
            <div style="color: #78716C; font-size: 9px;">Tanda Tangan & Cap Gudang</div>
        </div>
    </div>

</body>
</html>
