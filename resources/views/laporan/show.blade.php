@extends('layouts.app')

@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="md-card overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-md-outline/10 px-6 py-5">
        <h3 class="text-lg font-medium text-md-on-surface">{{ $title }}</h3>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('laporan.export.excel', ['type' => request()->query('type')]) }}"
               class="inline-flex h-10 items-center gap-2 rounded-full bg-md-secondary-container px-5 text-sm font-medium text-md-on-secondary-container transition-all duration-300 ease-md hover:bg-md-secondary-container/70 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary focus-visible:ring-offset-2">
                <i class="fas fa-file-excel" aria-hidden="true"></i> Export Excel
            </a>
            <a href="{{ route('laporan.export.pdf', ['type' => request()->query('type')]) }}" target="_blank"
               class="inline-flex h-10 items-center gap-2 rounded-full bg-md-primary px-5 text-sm font-medium text-md-on-primary transition-all duration-300 ease-md hover:bg-md-primary/90 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary focus-visible:ring-offset-2">
                <i class="fas fa-print" aria-hidden="true"></i> Cetak / PDF
            </a>
            <a href="{{ route('laporan.index') }}"
               class="inline-flex h-10 items-center rounded-full px-5 text-sm font-medium text-md-primary transition-colors duration-200 ease-md hover:bg-md-primary/10 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary focus-visible:ring-offset-2">
                Kembali
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="md-table">
            <thead>
                <tr class="border-b border-md-outline/20">
                    <th scope="col" class="w-16">No</th>
                    @foreach($headers as $header)
                        <th scope="col">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $row)
                    <tr class="border-b border-md-outline/10 transition-colors duration-200 hover:bg-md-primary/5">
                        <td class="whitespace-nowrap">{{ $index + 1 }}</td>
                        @foreach($row as $cell)
                            <td class="whitespace-nowrap text-md-on-surface-variant">{{ is_numeric($cell) && strpos($cell, '.') !== false ? number_format((float)$cell, 2, ',', '.') : $cell }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($headers) + 1 }}" class="px-6 py-12 text-center text-md-on-surface-variant">
                            Tidak ada data tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
