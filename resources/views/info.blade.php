@extends('layouts.app')

@section('title', 'Tentang Aplikasi')
@section('page-title', 'Informasi Sistem')

@section('content')
<div class="space-y-6 md:space-y-8">
    <!-- Header / Tentang JDN -->
    <div class="md-card flex flex-col items-start gap-6 p-8 md:flex-row md:items-center md:justify-between">
        <div class="flex flex-wrap items-center gap-5 md:flex-nowrap">
            <img src="{{ asset('assets/jdn_logo.png') }}" alt="Logo Jaga Data Nusantara" class="h-20 w-auto rounded-2xl bg-md-surface p-2">
            <div>
                <h2 class="text-2xl font-medium tracking-tight text-md-on-surface">Jaga Data Nusantara (JDN)</h2>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-md-on-surface-variant">
                    Organisasi nirlaba yang berdedikasi untuk mewujudkan tata kelola data publik yang transparan, akurat, dan berdampak bagi kemajuan daerah di Indonesia.
                </p>
            </div>
        </div>
    </div>

    <!-- Konten -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 md:gap-8">
        <!-- Kolom kiri -->
        <div class="space-y-6">
            <div class="md-card p-6">
                <h3 class="md-card-title mb-4">
                    <i data-lucide="handshake" class="h-4.5 w-4.5 text-md-primary"></i> Dukungan untuk Banjarnegara
                </h3>
                <p class="text-sm leading-relaxed text-md-on-surface-variant">
                    Sistem Informasi Manajemen Kesehatan ini dikembangkan secara sukarela oleh <strong class="text-md-on-surface">Jaga Data Nusantara (JDN)</strong> sebagai wujud dukungan teknologi kepada Pemerintah Kabupaten Banjarnegara.
                    Aplikasi ini merupakan modul perdana dari purwarupa <strong class="text-md-on-surface">Dasbor Super Analitik</strong> skala penuh yang sedang kami kembangkan, di mana ke depannya akan mencakup seluruh sektor strategis daerah (Pendidikan, Ekonomi, Infrastruktur, dll).
                </p>
            </div>

            <div class="md-insight">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-md-primary/15 text-md-primary">
                    <i data-lucide="database" class="h-5 w-5"></i>
                </div>
                <div>
                    <h3 class="mb-1 text-sm font-medium">Sumber Data Terbuka</h3>
                    <p class="text-xs leading-relaxed text-md-on-secondary-container/80">
                        Seluruh data primer yang ditampilkan pada modul ini ditarik, disinkronisasi, dan diverifikasi secara langsung dari ekosistem <strong>API OpenData Banjarnegara</strong>. Integrasi dua arah ini memastikan data yang tersaji selalu mutakhir dan sejalan dengan rilis resmi pemerintah.
                    </p>
                </div>
            </div>
        </div>

        <!-- Kolom kanan: Metodologi -->
        <div class="md-card p-6">
            <h3 class="md-card-title mb-6">
                <i data-lucide="network" class="h-4.5 w-4.5 text-md-primary"></i> Metodologi Analisis
            </h3>

            <ul class="space-y-5 text-sm">
                <li class="flex items-start gap-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-md-primary/10 text-md-primary">
                        <i data-lucide="map-pin" class="h-4 w-4"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-md-on-surface">Analisis Spasial (Choropleth)</h4>
                        <p class="mt-1 text-xs leading-relaxed text-md-on-surface-variant">Pemetaan geografis berbasis poligon GeoJSON untuk memvisualisasikan zonasi prevalensi stunting per kecamatan.</p>
                    </div>
                </li>

                <li class="flex items-start gap-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-md-tertiary/10 text-md-tertiary">
                        <i data-lucide="trending-up" class="h-4 w-4"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-md-on-surface">Time-Series Growth Trend</h4>
                        <p class="mt-1 text-xs leading-relaxed text-md-on-surface-variant">Komparasi data historis antar tahun untuk menghasilkan persentase lonjakan (Growth) maupun penurunan kasus secara presisi.</p>
                    </div>
                </li>

                <li class="flex items-start gap-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-md-primary/10 text-md-primary">
                        <i data-lucide="check-square" class="h-4 w-4"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-md-on-surface">Key Performance Indicator (KPI)</h4>
                        <p class="mt-1 text-xs leading-relaxed text-md-on-surface-variant">Agregasi cerdas untuk menghitung metrik kompleks seperti rasio ketersediaan dokter/bidan terhadap 100.000 penduduk secara *real-time*.</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- Peta Jalan -->
    <div class="md-card relative overflow-hidden p-8">
        <div class="pointer-events-none absolute -top-24 -right-16 h-72 w-72 rounded-full bg-md-tertiary/15 blur-3xl" aria-hidden="true"></div>
        <div class="relative">
            <div class="mb-8 border-b border-dashed border-md-outline/30 pb-6 text-center">
                <span class="md-badge">Peta Jalan JDN</span>
                <h3 class="mt-3 text-2xl font-medium tracking-tight text-md-on-surface">Pengembangan Analitik Masa Depan</h3>
                <p class="mt-1 text-sm text-md-on-surface-variant">Sistem pendukung keputusan komprehensif fase R&amp;D untuk rilis berikutnya:</p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['icon' => 'brain', 'tonal' => 'bg-md-primary/10 text-md-primary', 'title' => 'Predictive AI', 'desc' => 'Peramalan potensi wabah penyakit menular (seperti DBD) beberapa bulan ke depan berdasarkan pola iklim & curah hujan.'],
                    ['icon' => 'git-merge', 'tonal' => 'bg-md-tertiary/10 text-md-tertiary', 'title' => 'Korelasi Silang', 'desc' => 'Menggabungkan data spasial akses air bersih (Dinas PU) terhadap tingkat kerawanan stunting (Dinas Kesehatan).'],
                    ['icon' => 'message-square', 'tonal' => 'bg-md-primary/10 text-md-primary', 'title' => 'Sentimen NLP', 'desc' => 'Analisis sentimen otomatis tingkat kepuasan layanan puskesmas dari portal aduan masyarakat Pemkab.'],
                    ['icon' => 'scale', 'tonal' => 'bg-md-tertiary/10 text-md-tertiary', 'title' => 'Prescriptive AI', 'desc' => 'Rekomendasi otomatis relokasi atau penambahan kuota dokter/bidan ke puskesmas di zona merah krisis nakes.'],
                ] as $item)
                    <div class="group rounded-[24px] bg-md-surface-container-low p-5 transition-all duration-300 ease-md hover:-translate-y-1 hover:shadow-md">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-full {{ $item['tonal'] }} transition-transform duration-300 ease-md group-hover:scale-110">
                            <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5"></i>
                        </div>
                        <h4 class="mb-2 font-medium leading-tight text-md-on-surface">{{ $item['title'] }}</h4>
                        <p class="text-xs leading-relaxed text-md-on-surface-variant">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
