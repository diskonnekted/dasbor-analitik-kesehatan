<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Cetak Laporan</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; font-size: 14px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2c3e50; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 24px; color: #2c3e50; }
        .header p { margin: 5px 0 0 0; color: #7f8c8d; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #bdc3c7; padding: 8px 12px; text-align: left; }
        th { background-color: #ecf0f1; color: #2c3e50; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { text-align: right; margin-top: 50px; font-size: 12px; color: #7f8c8d; }
        .print-btn { background-color: #3498db; color: white; border: none; padding: 10px 20px; font-size: 16px; cursor: pointer; border-radius: 4px; margin-bottom: 20px; }
        .print-btn:hover { background-color: #2980b9; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn no-print">Cetak Sekarang (Print to PDF)</button>

    <div class="header">
        <h1>Jaga Data Nusantara (JDN)</h1>
        <p>Dasbor Analitik Kesehatan</p>
        <h2 style="margin-top: 15px;">{{ $title }}</h2>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px; text-align: center;">No</th>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    @foreach($row as $cell)
                        <td>{{ is_numeric($cell) && strpos($cell, '.') !== false ? number_format((float)$cell, 2, ',', '.') : $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) + 1 }}" style="text-align: center;">Tidak ada data tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak secara otomatis melalui Dasbor Analitik JDN</p>
    </div>

    <script>
        // Otomatis memunculkan dialog print saat halaman dimuat
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
