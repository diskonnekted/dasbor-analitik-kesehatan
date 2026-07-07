@extends('layouts.app')

@section('title', 'Tentang Aplikasi')
@section('page-title', 'Informasi Sistem')

@section('content')
<div class="space-y-8">
    <!-- Header Section (Neo-Brutalist) -->
    <div class="bg-blue-100 border-2 border-[#171717] p-8 shadow-[4px_4px_0px_0px_#171717] flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="flex items-center gap-4 flex-wrap md:flex-nowrap">
            <img src="{{ asset('assets/jdn_logo.png') }}" alt="Logo Jaga Data Nusantara" class="h-20 w-auto bg-white p-2 border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717]">
            <div>
                <h2 class="text-3xl font-serif font-black text-[#171717] uppercase tracking-tight">Jaga Data Nusantara (JDN)</h2>
                <p class="text-xs font-mono font-bold text-neutral-600 mt-2 max-w-2xl">
                    Organisasi nirlaba yang berdedikasi untuk mewujudkan tata kelola data publik yang transparan, akurat, dan berdampak bagi kemajuan daerah di Indonesia.
                </p>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Left Column -->
        <div class="space-y-8">
            <div class="bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717]">
                <h3 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] mb-4 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
                    <i data-lucide="handshake" class="w-4.5 h-4.5 text-blue-600"></i> Dukungan untuk Banjarnegara
                </h3>
                <p class="text-xs font-mono font-bold text-neutral-600 leading-relaxed text-justify">
                    Sistem Informasi Manajemen Kesehatan ini dikembangkan secara sukarela oleh <strong>Jaga Data Nusantara (JDN)</strong> sebagai wujud dukungan teknologi kepada Pemerintah Kabupaten Banjarnegara. 
                    Aplikasi ini merupakan modul perdana dari purwarupa <strong>Dasbor Super Analitik</strong> skala penuh yang sedang kami kembangkan, di mana ke depannya akan mencakup seluruh sektor strategis daerah (Pendidikan, Ekonomi, Infrastruktur, dll).
                </p>
            </div>

            <div class="bg-teal-50 border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717]">
                <h3 class="text-sm font-serif font-black uppercase tracking-widest text-teal-950 mb-4 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
                    <i data-lucide="database" class="w-4.5 h-4.5 text-teal-700"></i> Sumber Data Terbuka
                </h3>
                <p class="text-xs font-mono font-bold text-teal-900 leading-relaxed">
                    Seluruh data primer yang ditampilkan pada modul ini ditarik, disinkronisasi, dan diverifikasi secara langsung dari ekosistem <strong>API OpenData Banjarnegara</strong>. Integrasi dua arah ini memastikan data yang tersaji selalu mutakhir dan sejalan dengan rilis resmi pemerintah.
                </p>
            </div>
        </div>

        <!-- Right Column -->
        <div class="bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717]">
            <h3 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] mb-6 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
                <i data-lucide="network" class="w-4.5 h-4.5 text-purple-600"></i> Metodologi Analisis
            </h3>
            
            <ul class="space-y-5 font-mono font-bold text-xs">
                <li class="flex items-start gap-3">
                    <div class="w-8 h-8 border-2 border-[#171717] bg-green-100 flex items-center justify-center flex-shrink-0 shadow-[1px_1px_0px_0px_#171717]">
                        <i data-lucide="map-pin" class="w-4 h-4 text-green-700"></i>
                    </div>
                    <div>
                        <h4 class="font-serif font-black uppercase text-[11px] text-[#171717]">Analisis Spasial (Choropleth)</h4>
                        <p class="text-[10px] text-neutral-500 mt-1">Pemetaan geografis berbasis poligon GeoJSON untuk memvisualisasikan zonasi prevalensi stunting per kecamatan.</p>
                    </div>
                </li>
                
                <li class="flex items-start gap-3">
                    <div class="w-8 h-8 border-2 border-[#171717] bg-blue-100 flex items-center justify-center flex-shrink-0 shadow-[1px_1px_0px_0px_#171717]">
                        <i data-lucide="trending-up" class="w-4 h-4 text-blue-700"></i>
                    </div>
                    <div>
                        <h4 class="font-serif font-black uppercase text-[11px] text-[#171717]">Time-Series Growth Trend</h4>
                        <p class="text-[10px] text-neutral-500 mt-1">Komparasi data historis antar tahun untuk menghasilkan persentase lonjakan (Growth) maupun penurunan kasus secara presisi.</p>
                    </div>
                </li>
                
                <li class="flex items-start gap-3">
                    <div class="w-8 h-8 border-2 border-[#171717] bg-yellow-100 flex items-center justify-center flex-shrink-0 shadow-[1px_1px_0px_0px_#171717]">
                        <i data-lucide="check-square" class="w-4 h-4 text-yellow-700"></i>
                    </div>
                    <div>
                        <h4 class="font-serif font-black uppercase text-[11px] text-[#171717]">Key Performance Indicator (KPI)</h4>
                        <p class="text-[10px] text-neutral-500 mt-1">Agregasi cerdas untuk menghitung metrik kompleks seperti rasio ketersediaan dokter/bidan terhadap 100.000 penduduk secara *real-time*.</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- Future Roadmap Section -->
    <div class="bg-white border-2 border-[#171717] p-8 shadow-[4px_4px_0px_0px_#171717]">
        <div class="text-center mb-8 border-b-2 border-dashed border-[#171717] pb-6">
            <span class="inline-block px-3 py-1 bg-purple-200 border-2 border-[#171717] font-mono font-black text-xs uppercase shadow-[2px_2px_0px_0px_#171717]">
                Peta Jalan JDN
            </span>
            <h3 class="text-2xl font-serif font-black text-[#171717] uppercase mt-3">Pengembangan Analitik Masa Depan</h3>
            <p class="text-neutral-500 font-mono font-bold text-xs mt-1">Sistem pendukung keputusan komprehensif fase R&D untuk rilis berikutnya:</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 font-mono font-bold text-xs">
            <!-- ML -->
            <div class="bg-neutral-50 border-2 border-[#171717] p-5 shadow-[3px_3px_0px_0px_#171717] flex flex-col justify-between hover:shadow-[4px_4px_0px_0px_#171717] transition-all">
                <div>
                    <div class="w-9 h-9 border-2 border-[#171717] bg-indigo-100 flex items-center justify-center mb-4 shadow-[1.5px_1.5px_0px_0px_#171717]">
                        <i data-lucide="brain" class="w-5 h-5 text-indigo-700"></i>
                    </div>
                    <h4 class="font-serif font-black uppercase text-[#171717] mb-2 leading-tight">Predictive AI</h4>
                    <p class="text-[10px] text-neutral-500 leading-relaxed">Peramalan potensi wabah penyakit menular (seperti DBD) beberapa bulan ke depan berdasarkan pola iklim & curah hujan.</p>
                </div>
            </div>

            <!-- Correlation -->
            <div class="bg-neutral-50 border-2 border-[#171717] p-5 shadow-[3px_3px_0px_0px_#171717] flex flex-col justify-between hover:shadow-[4px_4px_0px_0px_#171717] transition-all">
                <div>
                    <div class="w-9 h-9 border-2 border-[#171717] bg-purple-100 flex items-center justify-center mb-4 shadow-[1.5px_1.5px_0px_0px_#171717]">
                        <i data-lucide="git-merge" class="w-5 h-5 text-purple-700"></i>
                    </div>
                    <h4 class="font-serif font-black uppercase text-[#171717] mb-2 leading-tight">Korelasi Silang</h4>
                    <p class="text-[10px] text-neutral-500 leading-relaxed">Menggabungkan data spasial akses air bersih (Dinas PU) terhadap tingkat kerawanan stunting (Dinas Kesehatan).</p>
                </div>
            </div>

            <!-- NLP -->
            <div class="bg-neutral-50 border-2 border-[#171717] p-5 shadow-[3px_3px_0px_0px_#171717] flex flex-col justify-between hover:shadow-[4px_4px_0px_0px_#171717] transition-all">
                <div>
                    <div class="w-9 h-9 border-2 border-[#171717] bg-rose-100 flex items-center justify-center mb-4 shadow-[1.5px_1.5px_0px_0px_#171717]">
                        <i data-lucide="message-square" class="w-5 h-5 text-rose-700"></i>
                    </div>
                    <h4 class="font-serif font-black uppercase text-[#171717] mb-2 leading-tight">Sentimen NLP</h4>
                    <p class="text-[10px] text-neutral-500 leading-relaxed">Analisis sentimen otomatis tingkat kepuasan layanan puskesmas dari portal aduan masyarakat Pemkab.</p>
                </div>
            </div>

            <!-- Prescriptive -->
            <div class="bg-neutral-50 border-2 border-[#171717] p-5 shadow-[3px_3px_0px_0px_#171717] flex flex-col justify-between hover:shadow-[4px_4px_0px_0px_#171717] transition-all">
                <div>
                    <div class="w-9 h-9 border-2 border-[#171717] bg-teal-100 flex items-center justify-center mb-4 shadow-[1.5px_1.5px_0px_0px_#171717]">
                        <i data-lucide="scale" class="w-5 h-5 text-teal-700"></i>
                    </div>
                    <h4 class="font-serif font-black uppercase text-[#171717] mb-2 leading-tight">Prescriptive AI</h4>
                    <p class="text-[10px] text-neutral-500 leading-relaxed">Rekomendasi otomatis relokasi atau penambahan kuota dokter/bidan ke puskesmas di zona merah krisis nakes.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
