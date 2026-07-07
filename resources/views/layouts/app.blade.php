<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Aplikasi Analisa Kesehatan Kabupaten Banjarnegara - Dashboard Monitoring dan Evaluasi Kesehatan Masyarakat">
    <meta name="keywords" content="kesehatan, banjarnegara, analisa, dashboard, puskesmas, stunting, aki, akb">
    <meta name="author" content="Jaga Data Nusantara (JDN)">
    
    <title>@yield('title') - Analisa Kesehatan Banjarnegara</title>
    
    <!-- SEO Schema.org -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "GovernmentOrganization",
        "name": "Jaga Data Nusantara (JDN)",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('assets/app_logo.png') }}"
    }
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- FontAwesome & Lucide Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Leaflet.js untuk Peta -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-[#f4f4f0] flex flex-col h-screen overflow-hidden text-[#171717] font-sans">
    
    <!-- Main Shell -->
    <div class="flex flex-1 overflow-hidden">
        
        <!-- Sidebar Neo-Brutalist -->
        <aside class="w-64 bg-white border-r-2 border-[#171717] flex flex-col flex-shrink-0 hidden md:flex">
            <!-- Brand Logo Area -->
            <div class="p-5 border-b-2 border-[#171717] bg-red-100 flex items-center gap-3">
                <div class="w-10 h-10 border-2 border-[#171717] bg-white overflow-hidden shadow-[2px_2px_0px_0px_#171717] flex items-center justify-center font-serif font-black text-lg rotate-[-2deg] text-red-600">
                    K
                </div>
                <div>
                    <span class="font-serif font-black text-xl tracking-tighter uppercase text-[#171717] mt-1 block leading-none">
                        KESEHATAN
                    </span>
                    <span class="text-[9px] font-mono font-bold text-neutral-500 uppercase tracking-widest block mt-0.5">Kab. Banjarnegara</span>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto p-4 space-y-2">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-4 py-2.5 font-mono font-bold text-xs uppercase tracking-wider border-2 border-[#171717] transition-all hover:bg-red-50 hover:translate-x-1 {{ request()->routeIs('dashboard') ? 'bg-red-200 shadow-[2px_2px_0px_0px_#171717]' : 'bg-white shadow-[1px_1px_0px_0px_#171717]' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 mr-3 text-red-600"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('faskes.index') }}" 
                   class="flex items-center px-4 py-2.5 font-mono font-bold text-xs uppercase tracking-wider border-2 border-[#171717] transition-all hover:bg-teal-50 hover:translate-x-1 {{ request()->routeIs('faskes.*') ? 'bg-teal-200 shadow-[2px_2px_0px_0px_#171717]' : 'bg-white shadow-[1px_1px_0px_0px_#171717]' }}">
                    <i data-lucide="building-2" class="w-4 h-4 mr-3 text-teal-600"></i>
                    Faskes
                </a>
                
                <a href="{{ route('tenaga-kesehatan.index') }}" 
                   class="flex items-center px-4 py-2.5 font-mono font-bold text-xs uppercase tracking-wider border-2 border-[#171717] transition-all hover:bg-blue-50 hover:translate-x-1 {{ request()->routeIs('tenaga-kesehatan.*') ? 'bg-blue-200 shadow-[2px_2px_0px_0px_#171717]' : 'bg-white shadow-[1px_1px_0px_0px_#171717]' }}">
                    <i data-lucide="users" class="w-4 h-4 mr-3 text-blue-600"></i>
                    Tenaga Medis
                </a>
                
                <a href="{{ route('penyakit.index') }}" 
                   class="flex items-center px-4 py-2.5 font-mono font-bold text-xs uppercase tracking-wider border-2 border-[#171717] transition-all hover:bg-orange-50 hover:translate-x-1 {{ request()->routeIs('penyakit.*') ? 'bg-orange-200 shadow-[2px_2px_0px_0px_#171717]' : 'bg-white shadow-[1px_1px_0px_0px_#171717]' }}">
                    <i data-lucide="activity" class="w-4 h-4 mr-3 text-orange-600"></i>
                    Penyakit
                </a>
                
                <a href="{{ route('stunting.index') }}" 
                   class="flex items-center px-4 py-2.5 font-mono font-bold text-xs uppercase tracking-wider border-2 border-[#171717] transition-all hover:bg-amber-50 hover:translate-x-1 {{ request()->routeIs('stunting.*') ? 'bg-amber-200 shadow-[2px_2px_0px_0px_#171717]' : 'bg-white shadow-[1px_1px_0px_0px_#171717]' }}">
                    <i data-lucide="trending-up" class="w-4 h-4 mr-3 text-amber-600"></i>
                    Stunting & Gizi
                </a>
                
                <a href="{{ route('aki-akb.index') }}" 
                   class="flex items-center px-4 py-2.5 font-mono font-bold text-xs uppercase tracking-wider border-2 border-[#171717] transition-all hover:bg-rose-50 hover:translate-x-1 {{ request()->routeIs('aki-akb.*') ? 'bg-rose-200 shadow-[2px_2px_0px_0px_#171717]' : 'bg-white shadow-[1px_1px_0px_0px_#171717]' }}">
                    <i data-lucide="heart" class="w-4 h-4 mr-3 text-rose-600"></i>
                    AKI & AKB
                </a>
                
                <a href="{{ route('imunisasi.index') }}" 
                   class="flex items-center px-4 py-2.5 font-mono font-bold text-xs uppercase tracking-wider border-2 border-[#171717] transition-all hover:bg-emerald-50 hover:translate-x-1 {{ request()->routeIs('imunisasi.*') ? 'bg-emerald-200 shadow-[2px_2px_0px_0px_#171717]' : 'bg-white shadow-[1px_1px_0px_0px_#171717]' }}">
                    <i data-lucide="shield-check" class="w-4 h-4 mr-3 text-emerald-600"></i>
                    Imunisasi
                </a>
                
                <a href="{{ route('posyandu.index') }}" 
                   class="flex items-center px-4 py-2.5 font-mono font-bold text-xs uppercase tracking-wider border-2 border-[#171717] transition-all hover:bg-indigo-50 hover:translate-x-1 {{ request()->routeIs('posyandu.*') ? 'bg-indigo-200 shadow-[2px_2px_0px_0px_#171717]' : 'bg-white shadow-[1px_1px_0px_0px_#171717]' }}">
                    <i data-lucide="map-pin" class="w-4 h-4 mr-3 text-indigo-600"></i>
                    Peta Posyandu
                </a>
                
                <a href="{{ route('sarana-kesehatan.index') }}" 
                   class="flex items-center px-4 py-2.5 font-mono font-bold text-xs uppercase tracking-wider border-2 border-[#171717] transition-all hover:bg-cyan-50 hover:translate-x-1 {{ request()->routeIs('sarana-kesehatan.*') ? 'bg-cyan-200 shadow-[2px_2px_0px_0px_#171717]' : 'bg-white shadow-[1px_1px_0px_0px_#171717]' }}">
                    <i data-lucide="hospital" class="w-4 h-4 mr-3 text-cyan-600"></i>
                    Sarana Kesehatan
                </a>
                
                <a href="{{ route('analisis.index') }}" 
                   class="flex items-center px-4 py-2.5 font-mono font-bold text-xs uppercase tracking-wider border-2 border-[#171717] transition-all hover:bg-purple-50 hover:translate-x-1 {{ request()->routeIs('analisis.*') ? 'bg-purple-200 shadow-[2px_2px_0px_0px_#171717]' : 'bg-white shadow-[1px_1px_0px_0px_#171717]' }}">
                    <i data-lucide="line-chart" class="w-4 h-4 mr-3 text-purple-600"></i>
                    Analisa Lanjutan
                </a>
                
                <a href="{{ route('laporan.index') }}" 
                   class="flex items-center px-4 py-2.5 font-mono font-bold text-xs uppercase tracking-wider border-2 border-[#171717] transition-all hover:bg-neutral-100 hover:translate-x-1 {{ request()->routeIs('laporan.*') ? 'bg-neutral-200 shadow-[2px_2px_0px_0px_#171717]' : 'bg-white shadow-[1px_1px_0px_0px_#171717]' }}">
                    <i data-lucide="file-text" class="w-4 h-4 mr-3 text-neutral-600"></i>
                    Laporan PDF
                </a>
                
                <a href="{{ route('info') }}" 
                   class="flex items-center px-4 py-2.5 font-mono font-bold text-xs uppercase tracking-wider border-2 border-[#171717] transition-all hover:bg-violet-50 hover:translate-x-1 {{ request()->routeIs('info') ? 'bg-violet-200 shadow-[2px_2px_0px_0px_#171717]' : 'bg-white shadow-[1px_1px_0px_0px_#171717]' }}">
                    <i data-lucide="info" class="w-4 h-4 mr-3 text-violet-600"></i>
                    Info JDN
                </a>
            </nav>
        </aside>
        
        <!-- Right Content Area (Top bar + main page content + footer) -->
        <div class="flex-grow flex flex-col overflow-hidden">
            <!-- Top Bar Header Neo-Brutalist -->
            <header class="bg-white border-b-2 border-[#171717] px-6 py-4 flex items-center justify-between shrink-0 shadow-sm">
                <div class="flex items-center gap-3">
                    <button class="md:hidden text-gray-600 mr-2 border-2 border-[#171717] p-1.5 bg-white shadow-[2px_2px_0px_0px_#171717]" onclick="toggleSidebar()">
                        <i data-lucide="menu" class="w-5 h-5 text-[#171717]"></i>
                    </button>
                    <h2 class="text-xl font-serif font-black uppercase tracking-tight text-[#171717]">@yield('page-title', 'Dasbor Kesehatan')</h2>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- JDN Badge -->
                    <div class="hidden sm:inline-flex items-center px-3 py-1 bg-white border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] text-[10px] font-mono font-bold tracking-wider text-red-600 uppercase">
                        Sistem Informasi JDN
                    </div>
                    
                    <!-- Admin Avatar -->
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-mono font-bold text-neutral-800">Admin Utama</p>
                            <p class="text-[9px] font-mono font-bold text-neutral-500 uppercase tracking-widest leading-none">Super Admin</p>
                        </div>
                        <div class="w-9 h-9 rounded-none border-2 border-[#171717] bg-yellow-300 shadow-[2px_2px_0px_0px_#171717] flex items-center justify-center font-mono font-black text-sm">
                            AD
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content Area -->
            <main class="flex-grow overflow-y-auto p-6 md:p-8 bg-[#f4f4f0]">
                @if(session('success'))
                    <div class="mb-6 bg-emerald-100 border-2 border-[#171717] shadow-[3px_3px_0px_0px_#171717] p-4 text-emerald-800 font-mono font-bold text-xs uppercase flex items-center gap-2" role="alert">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 bg-red-100 border-2 border-[#171717] shadow-[3px_3px_0px_0px_#171717] p-4 text-red-800 font-mono font-bold text-xs uppercase flex items-center gap-2" role="alert">
                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="bg-white border-t-2 border-[#171717] px-6 py-4 flex items-center justify-between text-xs font-mono font-bold text-neutral-600 shrink-0">
                <p>&copy; {{ date('Y') }} Jaga Data Nusantara (JDN) - Analitika Kesehatan</p>
                <p class="hidden sm:block">V1.2.0 • Status: OK</p>
            </footer>
        </div>
    </div>
    
    <!-- Mobile Sidebar overlay (Simple Toggle Script) -->
    <div id="mobile-sidebar" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden" onclick="toggleSidebar()">
        <div class="w-64 h-full bg-white border-r-2 border-[#171717] flex flex-col p-5 space-y-4" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between pb-4 border-b-2 border-[#171717]">
                <span class="font-serif font-black text-lg text-red-600 uppercase">Menu Utama</span>
                <button class="border-2 border-[#171717] p-1 shadow-[2px_2px_0px_0px_#171717]" onclick="toggleSidebar()">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto space-y-2">
                <!-- Mobile Navigation Links -->
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 font-mono font-bold text-xs uppercase border-2 border-[#171717] bg-white shadow-[1px_1px_0px_0px_#171717]">
                    Dashboard
                </a>
                <a href="{{ route('faskes.index') }}" class="flex items-center px-4 py-2 font-mono font-bold text-xs uppercase border-2 border-[#171717] bg-white shadow-[1px_1px_0px_0px_#171717]">
                    Faskes
                </a>
                <a href="{{ route('tenaga-kesehatan.index') }}" class="flex items-center px-4 py-2 font-mono font-bold text-xs uppercase border-2 border-[#171717] bg-white shadow-[1px_1px_0px_0px_#171717]">
                    Tenaga Medis
                </a>
                <a href="{{ route('penyakit.index') }}" class="flex items-center px-4 py-2 font-mono font-bold text-xs uppercase border-2 border-[#171717] bg-white shadow-[1px_1px_0px_0px_#171717]">
                    Penyakit
                </a>
                <a href="{{ route('stunting.index') }}" class="flex items-center px-4 py-2 font-mono font-bold text-xs uppercase border-2 border-[#171717] bg-white shadow-[1px_1px_0px_0px_#171717]">
                    Stunting & Gizi
                </a>
                <a href="{{ route('aki-akb.index') }}" class="flex items-center px-4 py-2 font-mono font-bold text-xs uppercase border-2 border-[#171717] bg-white shadow-[1px_1px_0px_0px_#171717]">
                    AKI & AKB
                </a>
                <a href="{{ route('imunisasi.index') }}" class="flex items-center px-4 py-2 font-mono font-bold text-xs uppercase border-2 border-[#171717] bg-white shadow-[1px_1px_0px_0px_#171717]">
                    Imunisasi
                </a>
                <a href="{{ route('posyandu.index') }}" class="flex items-center px-4 py-2 font-mono font-bold text-xs uppercase border-2 border-[#171717] bg-white shadow-[1px_1px_0px_0px_#171717]">
                    Peta Posyandu
                </a>
                <a href="{{ route('sarana-kesehatan.index') }}" class="flex items-center px-4 py-2 font-mono font-bold text-xs uppercase border-2 border-[#171717] bg-white shadow-[1px_1px_0px_0px_#171717]">
                    Sarana Kesehatan
                </a>
                <a href="{{ route('analisis.index') }}" class="flex items-center px-4 py-2 font-mono font-bold text-xs uppercase border-2 border-[#171717] bg-white shadow-[1px_1px_0px_0px_#171717]">
                    Analisa Lanjutan
                </a>
                <a href="{{ route('laporan.index') }}" class="flex items-center px-4 py-2 font-mono font-bold text-xs uppercase border-2 border-[#171717] bg-white shadow-[1px_1px_0px_0px_#171717]">
                    Laporan PDF
                </a>
                <a href="{{ route('info') }}" class="flex items-center px-4 py-2 font-mono font-bold text-xs uppercase border-2 border-[#171717] bg-white shadow-[1px_1px_0px_0px_#171717]">
                    Info JDN
                </a>
            </nav>
        </div>
    </div>

    <script>
        lucide.createIcons();
        function toggleSidebar() {
            var sidebar = document.getElementById('mobile-sidebar');
            if(sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
            } else {
                sidebar.classList.add('hidden');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
