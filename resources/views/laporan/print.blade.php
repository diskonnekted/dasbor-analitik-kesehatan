<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Cetak Laporan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root { --md-primary: #6750A4; --md-on-surface: #1C1B1F; --md-on-surface-variant: #49454F; --md-surface-container: #F3EDF7; --md-outline: #79747E; }
        body { font-family: 'Roboto', Arial, sans-serif; color: var(--md-on-surface); font-size: 14px; margin: 0; padding: 20px; background: #FFFBFE; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 1px solid #D9CFE4; padding-bottom: 18px; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 500; color: var(--md-primary); }
        .header .subtitle { margin: 6px 0 0; color: var(--md-on-surface-variant); }
        .header h2 { font-size: 18px; font-weight: 500; margin: 18px 0 4px; color: var(--md-on-surface); }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border-bottom: 1px solid #E7E0EC; padding: 10px 12px; text-align: left; }
        th { background-color: var(--md-surface-container); color: var(--md-on-surface); font-weight: 500; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
        tr:nth-child(even) td { background-color: #FAF8FD; }
        .footer { text-align: right; margin-top: 50px; font-size: 12px; color: var(--md-on-surface-variant); }
        .print-btn { background-color: var(--md-primary); color: #fff; border: none; padding: 12px 24px; font-family: 'Roboto', sans-serif; font-size: 15px; font-weight: 500; cursor: pointer; border-radius: 9999px; margin-bottom: 20px; transition: background-color 200ms ease; }
        .print-btn:hover { background-color: rgba(103, 80, 164, 0.9); }
        .print-btn:active { transform: scale(0.97); }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; background: #fff; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn no-print">Cetak Sekarang (Print to PDF)</button>

    <div class="header">
        <h1>Jaga Data Nusantara (JDN)</h1>
        <p class="subtitle">Dasbor Analitik Kesehatan</p>
        <h2>{{ $title }}</h2>
        <p class="subtitle">Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
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
