@extends('layouts.app')

@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">{{ $title }}</h3>
        <div class="flex gap-2">
            <a href="{{ route('laporan.export.excel', ['type' => request()->query('type')]) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm font-medium">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>
            <a href="{{ route('laporan.export.pdf', ['type' => request()->query('type')]) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm font-medium">
                <i class="fas fa-print mr-1"></i> Cetak / PDF
            </a>
            <a href="{{ route('laporan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded text-sm font-medium">
                Kembali
            </a>
        </div>
    </div>

    <div class="p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead class="bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
                    @foreach($headers as $header)
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($data as $index => $row)
                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-blue-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        @foreach($row as $cell)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ is_numeric($cell) && strpos($cell, '.') !== false ? number_format((float)$cell, 2, ',', '.') : $cell }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($headers) + 1 }}" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            Tidak ada data tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
