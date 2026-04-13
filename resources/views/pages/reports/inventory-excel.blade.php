<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #2f2f2f;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background: #dbeafe;
            text-align: left;
        }

        .title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 6px;
        }

        .meta {
            margin-bottom: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="title">Laporan Barang Kantor</div>
    <div class="meta">Tanggal Export: {{ now()->format('d-m-Y H:i:s') }}</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kode Barang</th>
                <th>Serial Number</th>
                <th>Kategori</th>
                <th>Merk</th>
                <th>Penanggung Jawab</th>
                <th>Kondisi</th>
                <th>Lokasi</th>
                <th>Deskripsi</th>
                <th>Update Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->kode_barang }}</td>
                    <td>{{ $row->serial_number ?: '-' }}</td>
                    <td>{{ $row->category->name ?? '-' }}</td>
                    <td>{{ $row->brand->name ?? '-' }}</td>
                    <td>{{ $row->penanggung_jawab ?: '-' }}</td>
                    <td>{{ $row->kondisi }}</td>
                    @php
                        $lokasiLabel = $row->location->name ?? '-';
                        $subLokasiLabel = $row->subLocation->name ?? '';
                    @endphp
                    <td>{{ $subLokasiLabel !== '' ? $lokasiLabel . ' -> ' . $subLokasiLabel : $lokasiLabel }}</td>
                    <td>{{ $row->deskripsi ?: '-' }}</td>
                    <td>{{ optional($row->updated_at)->format('d-m-Y H:i') ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
