@extends('layouts.app')

@section('title', 'Menu Analisis Lanjutan')
@section('page-title', 'Menu Analisis Lanjutan')

@section('content')
<div class="space-y-6 md:space-y-8">
    <!-- Hero -->
    <div class="relative overflow-hidden rounded-[32px] md:rounded-[48px] bg-md-surface-container p-8 md:p-10">
        <div class="pointer-events-none absolute -top-24 -right-16 h-72 w-72 rounded-full bg-md-primary/25 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-28 -left-14 h-64 w-64 rounded-full bg-md-tertiary/25 blur-3xl" aria-hidden="true"></div>
        <div class="relative max-w-3xl">
            <span class="md-badge">
                <i data-lucide="line-chart" class="h-3.5 w-3.5 text-md-primary" aria-hidden="true"></i>
                Advanced Data Analytics
            </span>
            <h1 class="mt-4 text-3xl font-medium tracking-tight text-md-on-surface">Pusat Analisis &amp; Evaluasi</h1>
            <p class="mt-3 text-sm leading-relaxed text-md-on-surface-variant">
                Peralatan analisis kesehatan tingkat lanjut: temukan pola tersembunyi, prediksi tren masa depan, dan lakukan klasterisasi spasial
                data prevalensi stunting di wilayah Kabupaten Banjarnegara menggunakan Python Analytics Engine.
            </p>
        </div>
    </div>

    @if (!empty($insights))
    <!-- Temuan Kunci & Rekomendasi -->
    <section class="space-y-4">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-md-primary/10 text-md-primary">
                <i data-lucide="lightbulb" class="h-5 w-5"></i>
            </div>
            <div>
                <h2 class="text-xl font-medium tracking-tight text-md-on-surface">Temuan Kunci &amp; Rekomendasi</h2>
                <p class="text-xs text-md-on-surface-variant">Sintesis otomatis dari data terbaru yang tersedia di basis data.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($insights as $i => $ins)
                @php $ikonTone = $i % 2 === 0 ? 'bg-md-primary/10 text-md-primary' : 'bg-md-tertiary/10 text-md-tertiary'; @endphp
                <article class="md-card flex flex-col gap-3 p-5">
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full {{ $ikonTone }}">
                            <i data-lucide="{{ $ins['ikon'] }}" class="h-5 w-5"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-medium uppercase tracking-wide text-md-on-surface-variant">{{ $ins['label'] }}</p>
                            <p class="mt-0.5 text-xl font-medium leading-snug text-md-on-surface">{{ $ins['nilai'] }}</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-md-on-surface-variant">{{ $ins['temuan'] }}</p>
                    <div class="rounded-2xl bg-md-secondary-container/50 p-3">
                        <p class="mb-1 text-[10px] font-semibold uppercase tracking-wider text-md-on-secondary-container/70">Rekomendasi</p>
                        <p class="text-xs leading-relaxed text-md-on-secondary-container">{{ $ins['rekomendasi'] }}</p>
                    </div>
                    <a href="{{ $ins['url'] }}" class="group/link mt-auto inline-flex items-center gap-1.5 self-start text-xs font-medium text-md-primary transition-colors hover:text-md-primary/80">
                        {{ $ins['link'] }}
                        <i data-lucide="arrow-right" class="h-3 w-3 transition-transform duration-300 ease-md group-hover/link:translate-x-1"></i>
                    </a>
                </article>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['route' => 'analisis.korelasi', 'icon' => 'git-merge', 'tonal' => 'bg-md-tertiary/10 text-md-tertiary', 'title' => 'Analisis Korelasi', 'desc' => 'Analisis hubungan statistik antar variabel kesehatan, seperti korelasi jumlah Nakes atau Faskes dengan prevalensi stunting.', 'cta' => 'Buka Korelasi'],
            ['route' => 'analisis.klaster', 'icon' => 'grid-3x3', 'tonal' => 'bg-md-primary/10 text-md-primary', 'title' => 'Pemetaan Klaster', 'desc' => 'Pengelompokan wilayah kecamatan berdasarkan tingkat kerawanan dan kemiripan data gizi buruk serta sarana kesehatan.', 'cta' => 'Buka Klaster'],
            ['route' => 'analisis.prediksi', 'icon' => 'trending-up', 'tonal' => 'bg-md-primary/10 text-md-primary', 'title' => 'Prediksi Tren', 'desc' => 'Peramalan arah perkembangan prevalensi stunting 3 tahun ke depan menggunakan regresi linear time-series.', 'cta' => 'Buka Prediksi'],
            ['route' => 'analisis.spasial', 'icon' => 'map', 'tonal' => 'bg-md-tertiary/10 text-md-tertiary', 'title' => 'Analisis Spasial', 'desc' => 'Visualisasi data kesehatan secara geografis interaktif (GIS) untuk memetakan sebaran kasus stunting per kecamatan.', 'cta' => 'Buka Peta'],
            ['route' => 'analisis.early-warning', 'icon' => 'siren', 'tonal' => 'bg-md-tertiary/10 text-md-tertiary', 'title' => 'Peringatan Dini', 'desc' => 'Deteksi lonjakan (outbreak) penyakit menular per kecamatan dengan membandingkan kasus terkini terhadap baseline tahun-tahun sebelumnya.', 'cta' => 'Buka Peringatan'],
            ['route' => 'analisis.rasio-nakes', 'icon' => 'stethoscope', 'tonal' => 'bg-md-primary/10 text-md-primary', 'title' => 'Rasio Nakes', 'desc' => 'Perbandingan beban penyakit menular terhadap jumlah tenaga kesehatan tiap kecamatan untuk mengidentifikasi wilayah kekurangan SDM.', 'cta' => 'Buka Rasio'],
            ['route' => 'analisis.indeks-kerawanan', 'icon' => 'shield-alert', 'tonal' => 'bg-md-tertiary/10 text-md-tertiary', 'title' => 'Indeks Kerawanan', 'desc' => 'Skor gabungan (stunting, beban penyakit per nakes, kelangkaan faskes) untuk menentukan prioritas intervensi tiap kecamatan.', 'cta' => 'Buka Indeks'],
        ] as $card)
            <a href="{{ route($card['route']) }}"
               class="group md-card flex flex-col p-6 text-center transition-all duration-300 ease-md hover:-translate-y-1 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary focus-visible:ring-offset-2 active:scale-[0.98]">
                <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full {{ $card['tonal'] }} transition-transform duration-300 ease-md group-hover:scale-110">
                    <i data-lucide="{{ $card['icon'] }}" class="h-8 w-8"></i>
                </div>
                <h3 class="mb-2 text-lg font-medium text-md-on-surface">{{ $card['title'] }}</h3>
                <p class="flex-1 text-xs leading-relaxed text-md-on-surface-variant">{{ $card['desc'] }}</p>
                <span class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-md-primary/5 px-4 py-1.5 text-xs font-medium text-md-primary transition-colors duration-200 group-hover:bg-md-primary/10">
                    {{ $card['cta'] }}
                    <i data-lucide="arrow-right" class="h-3 w-3 transition-transform duration-300 ease-md group-hover:translate-x-1"></i>
                </span>
            </a>
        @endforeach
    </div>

    <!-- Rencana Analisis (mockup, belum aktif) -->
    <section class="space-y-4">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-md-tertiary/10 text-md-tertiary">
                <i data-lucide="telescope" class="h-5 w-5"></i>
            </div>
            <div>
                <h2 class="text-xl font-medium tracking-tight text-md-on-surface">Rencana Analisis Berikutnya</h2>
                <p class="text-xs text-md-on-surface-variant">Mockup modul yang sedang dipertimbangkan — menunggu kelengkapan &amp; validasi data pendukung.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ([
                ['icon' => 'microscope', 'tonal' => 'bg-md-primary/10 text-md-primary', 'title' => 'Determinan Stunting', 'desc' => 'Regresi multivariabel untuk mengukur seberapa besar pengaruh imunisasi, posyandu, nakes, dan faskes terhadap prevalensi stunting tiap kecamatan.', 'data' => ['Stunting', 'Imunisasi', 'Posyandu']],
                ['icon' => 'equal', 'tonal' => 'bg-md-tertiary/10 text-md-tertiary', 'title' => 'Pemerataan Sarana Kesehatan', 'desc' => 'Indeks disparitas (Gini ratio) distribusi faskes & nakes antar kecamatan untuk melihat ketimpangan akses layanan.', 'data' => ['Sarana Kesehatan', 'Nakes']],
                ['icon' => 'syringe', 'tonal' => 'bg-md-primary/10 text-md-primary', 'title' => 'Cakupan Imunisasi', 'desc' => 'Persentase cakupan imunisasi per kecamatan dibanding target nasional, lengkap dengan analisis kesenjangan (gap) wilayah.', 'data' => ['Imunisasi', 'Peta Kecamatan']],
                ['icon' => 'calculator', 'tonal' => 'bg-md-tertiary/10 text-md-tertiary', 'title' => 'Proyeksi Kebutuhan SDM & Faskes', 'desc' => 'Simulasi kebutuhan tenaga kesehatan & faskes 5 tahun ke depan dibanding standar minimum (WHO 4,45 nakes per 1.000 penduduk).', 'data' => ['Nakes', 'Faskes', 'Penduduk']],
                ['icon' => 'baby', 'tonal' => 'bg-md-primary/10 text-md-primary', 'title' => 'Korelasi Bidan–AKI/AKB', 'desc' => 'Hubungan kepadatan bidan desa dengan angka kematian ibu & bayi per kecamatan untuk menilai efektivitas layanan ibu-anak.', 'data' => ['AKI & AKB', 'Nakes']],
                ['icon' => 'bug', 'tonal' => 'bg-md-tertiary/10 text-md-tertiary', 'title' => 'Musiman & Hotspot DBD', 'desc' => 'Analisis tren bulanan (seasonal curve) dan identifikasi kecamatan hotspot demam berdarah sebagai dasar pemberantasan sarang nyamuk.', 'data' => ['DBD per bulan', 'Peta Kecamatan']],
            ] as $rencana)
                <article class="relative flex flex-col rounded-[28px] border-2 border-dashed border-md-outline/40 bg-md-surface-container/60 p-6">
                    <span class="md-badge absolute top-4 right-4 pointer-events-none">
                        <i data-lucide="clock" class="h-3 w-3 text-md-tertiary" aria-hidden="true"></i>
                        Rencana
                    </span>
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full {{ $rencana['tonal'] }} opacity-80">
                        <i data-lucide="{{ $rencana['icon'] }}" class="h-7 w-7"></i>
                    </div>
                    <h3 class="mb-2 pr-16 text-base font-medium text-md-on-surface">{{ $rencana['title'] }}</h3>
                    <p class="flex-1 text-xs leading-relaxed text-md-on-surface-variant">{{ $rencana['desc'] }}</p>
                    <div class="mt-4 flex flex-wrap gap-1.5">
                        @foreach ($rencana['data'] as $d)
                            <span class="rounded-full bg-md-surface-container-low px-2.5 py-1 text-[10px] font-medium text-md-on-surface-variant">{{ $d }}</span>
                        @endforeach
                    </div>
                    <span class="mt-4 inline-flex cursor-default items-center gap-1.5 self-start rounded-full bg-md-outline/10 px-4 py-1.5 text-xs font-medium text-md-on-surface-variant">
                        Dalam Perencanaan
                        <i data-lucide="clock" class="h-3 w-3"></i>
                    </span>
                </article>
            @endforeach
        </div>
    </section>
</div>
@endsection
