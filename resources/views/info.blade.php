@extends('layouts.app')

@section('title', 'Tentang Aplikasi')
@section('page-title', 'Informasi Sistem')

@section('content')
<div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-800 to-blue-600 px-8 py-10 text-white flex flex-col items-center justify-center text-center">
        <img src="{{ asset('assets/jdn_logo.png') }}" alt="Logo Jaga Data Nusantara" class="h-28 w-auto mb-6 bg-white p-2 rounded-full shadow-lg">
        <h2 class="text-3xl font-bold mb-2">Jaga Data Nusantara (JDN)</h2>
        <p class="text-blue-100 max-w-2xl mx-auto text-lg">Organisasi nirlaba yang berdedikasi untuk mewujudkan tata kelola data publik yang transparan, akurat, dan berdampak bagi kemajuan daerah di Indonesia.</p>
    </div>

    <!-- Content Section -->
    <div class="p-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Left Column -->
            <div class="space-y-8">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 border-b pb-2 mb-4 flex items-center">
                        <i class="fas fa-handshake text-blue-600 mr-3"></i> Dukungan untuk Banjarnegara
                    </h3>
                    <p class="text-gray-600 leading-relaxed text-justify">
                        Sistem Informasi Manajemen Kesehatan ini dikembangkan secara sukarela oleh <strong>Jaga Data Nusantara (JDN)</strong> sebagai wujud dukungan teknologi kepada Pemerintah Kabupaten Banjarnegara. 
                        Aplikasi ini merupakan modul perdana dari purwarupa <strong>Dasbor Super Analitik</strong> skala penuh yang sedang kami kembangkan, di mana ke depannya akan mencakup seluruh sektor strategis daerah (Pendidikan, Ekonomi, Infrastruktur, dll).
                    </p>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-gray-900 border-b pb-2 mb-4 flex items-center">
                        <i class="fas fa-database text-blue-600 mr-3"></i> Sumber Data Terbuka
                    </h3>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded text-gray-700">
                        Seluruh data primer yang ditampilkan pada modul ini ditarik, disinkronisasi, dan diverifikasi secara langsung dari ekosistem <strong>API OpenData Banjarnegara</strong>. Integrasi dua arah ini memastikan data yang tersaji selalu mutakhir dan sejalan dengan rilis resmi pemerintah.
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 border-b pb-2 mb-4 flex items-center">
                    <i class="fas fa-chart-network text-blue-600 mr-3"></i> Metodologi Analisis
                </h3>
                <p class="text-gray-600 mb-4">Aplikasi ini tidak sekadar menampilkan tabel data mentah, melainkan memprosesnya melalui berbagai lapisan analitik prediktif dan komparatif:</p>
                
                <ul class="space-y-4 mt-4">
                    <li class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <span class="flex items-center justify-center h-6 w-6 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-map-marked-alt text-xs"></i>
                            </span>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-md font-semibold text-gray-900">Analisis Spasial (Choropleth Mapping)</h4>
                            <p class="text-sm text-gray-600">Pemetaan geografis berbasis poligon GeoJSON untuk memvisualisasikan zonasi tingkat keparahan (merah/kuning/hijau) di tingkat kecamatan.</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <span class="flex items-center justify-center h-6 w-6 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-chart-line text-xs"></i>
                            </span>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-md font-semibold text-gray-900">Time-Series Growth Trend</h4>
                            <p class="text-sm text-gray-600">Komparasi data historis antar tahun untuk menghasilkan persentase lonjakan (Growth) maupun penurunan kasus secara presisi.</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <span class="flex items-center justify-center h-6 w-6 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-tachometer-alt text-xs"></i>
                            </span>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-md font-semibold text-gray-900">Key Performance Indicator (KPI)</h4>
                            <p class="text-sm text-gray-600">Agregasi cerdas untuk menghitung metrik kompleks seperti Angka Kematian Ibu/Bayi (per 100.000 kelahiran) dan rasio tenaga medis terhadap populasi.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Future Roadmap Section -->
        <div class="mt-12 bg-white border border-gray-200 rounded-lg shadow-sm p-8">
            <div class="text-center mb-8">
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">Peta Jalan JDN</span>
                <h3 class="text-2xl font-bold text-gray-900 mt-3">Pengembangan Analitik Masa Depan</h3>
                <p class="text-gray-600 mt-2 max-w-2xl mx-auto">Visi kami membangun <strong>Dasbor Super Analitik</strong> tidak berhenti di pemetaan dasar. Berikut adalah fase analitik tingkat lanjut yang sedang dalam tahap pengembangan (R&D) untuk rilis berikutnya:</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Predictive Analytics -->
                <div class="bg-gray-50 rounded-lg p-5 border border-gray-100 hover:border-blue-300 transition">
                    <div class="h-10 w-10 rounded bg-indigo-100 text-indigo-600 flex items-center justify-center mb-4">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Predictive Analytics (AI)</h4>
                    <p class="text-sm text-gray-600">Pemanfaatan <em>Machine Learning</em> untuk meramalkan potensi wabah penyakit (seperti DBD) beberapa bulan ke depan berdasarkan pola historis iklim dan curah hujan.</p>
                </div>

                <!-- Cross-Sector Correlation -->
                <div class="bg-gray-50 rounded-lg p-5 border border-gray-100 hover:border-blue-300 transition">
                    <div class="h-10 w-10 rounded bg-purple-100 text-purple-600 flex items-center justify-center mb-4">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Analisis Korelasi Silang</h4>
                    <p class="text-sm text-gray-600">Mengawinkan data lintas instansi. Contoh: Mencari benang merah antara wilayah dengan infrastruktur air bersih buruk (Dinas PU) terhadap lonjakan angka Stunting (Dinkes).</p>
                </div>

                <!-- Sentiment Analysis -->
                <div class="bg-gray-50 rounded-lg p-5 border border-gray-100 hover:border-blue-300 transition">
                    <div class="h-10 w-10 rounded bg-pink-100 text-pink-600 flex items-center justify-center mb-4">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Natural Language Processing</h4>
                    <p class="text-sm text-gray-600">Analisis Sentimen otomatis untuk mengukur Indeks Kepuasan Masyarakat terhadap layanan tiap Puskesmas secara <em>real-time</em> dari portal aduan publik.</p>
                </div>

                <!-- Prescriptive Analytics -->
                <div class="bg-gray-50 rounded-lg p-5 border border-gray-100 hover:border-blue-300 transition">
                    <div class="h-10 w-10 rounded bg-teal-100 text-teal-600 flex items-center justify-center mb-4">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Prescriptive Analytics</h4>
                    <p class="text-sm text-gray-600">Sistem pendukung keputusan yang menyarankan skenario intervensi terbaik, seperti otomatisasi rekomendasi relokasi tenaga medis ke zona merah krisis nakes.</p>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center border-t pt-6">
            <p class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} Jaga Data Nusantara (JDN). Hak Cipta Dilindungi.<br>
                <em>Dibangun dengan dedikasi untuk Banjarnegara yang lebih baik.</em>
            </p>
        </div>
    </div>
</div>
@endsection
