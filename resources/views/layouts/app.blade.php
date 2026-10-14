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

    @stack('styles')
</head>
<body class="bg-md-background text-md-on-surface font-sans flex flex-col h-screen overflow-hidden antialiased">

    @php
        $navItems = [
            ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
            ['route' => 'faskes.index', 'active' => 'faskes.*', 'label' => 'Faskes', 'icon' => 'building-2'],
            ['route' => 'tenaga-kesehatan.index', 'active' => 'tenaga-kesehatan.*', 'label' => 'Tenaga Medis', 'icon' => 'users'],
            ['route' => 'penyakit.index', 'active' => 'penyakit.*', 'label' => 'Penyakit', 'icon' => 'activity'],
            ['route' => 'stunting.index', 'active' => 'stunting.*', 'label' => 'Stunting & Gizi', 'icon' => 'trending-up'],
            ['route' => 'aki-akb.index', 'active' => 'aki-akb.*', 'label' => 'AKI & AKB', 'icon' => 'heart'],
            ['route' => 'imunisasi.index', 'active' => 'imunisasi.*', 'label' => 'Imunisasi', 'icon' => 'shield-check'],
            ['route' => 'posyandu.index', 'active' => 'posyandu.*', 'label' => 'Peta Posyandu', 'icon' => 'map-pin'],
            ['route' => 'sarana-kesehatan.index', 'active' => 'sarana-kesehatan.*', 'label' => 'Sarana Kesehatan', 'icon' => 'hospital'],
            ['route' => 'analisis.index', 'active' => 'analisis.*', 'label' => 'Analisa Lanjutan', 'icon' => 'line-chart'],
            ['route' => 'laporan.index', 'active' => 'laporan.*', 'label' => 'Laporan PDF', 'icon' => 'file-text'],
            ['route' => 'info', 'label' => 'Info JDN', 'icon' => 'info'],
        ];
        $navItemClass = fn ($item) => 'group flex items-center gap-3 rounded-full px-4 py-2.5 text-sm font-medium transition-colors duration-200 ease-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary focus-visible:ring-offset-2 ring-offset-md-surface-container active:scale-[0.98] ' .
            (request()->routeIs($item['active'] ?? $item['route'])
                ? 'bg-md-secondary-container text-md-on-secondary-container'
                : 'text-md-on-surface-variant hover:bg-md-primary/10 hover:text-md-on-surface');
    @endphp

    <!-- Main Shell -->
    <div class="flex flex-1 overflow-hidden">

        <!-- Navigation Drawer (MD3) -->
        <aside class="w-72 bg-md-surface-container flex-col flex-shrink-0 hidden md:flex p-4 gap-2">
            <!-- Brand -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-3 rounded-[28px] hover:bg-md-primary/5 transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary">
                <div class="w-11 h-11 rounded-2xl bg-md-primary text-md-on-primary flex items-center justify-center overflow-hidden shadow-sm">
                    <img src="{{ asset('assets/app_logo.png') }}" alt="Logo" class="w-full h-full object-cover" onerror="this.replaceWith(document.createTextNode('K'))">
                </div>
                <div>
                    <span class="block text-base font-medium leading-tight text-md-on-surface">Kesehatan</span>
                    <span class="block text-xs text-md-on-surface-variant">Kab. Banjarnegara</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto mt-2 space-y-1" aria-label="Navigasi utama">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}" class="{{ $navItemClass($item) }}">
                        <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 shrink-0"></i>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <!-- Right Content Area (Top bar + main page content + footer) -->
        <div class="flex-grow flex flex-col overflow-hidden">
            <!-- Top App Bar -->
            <header class="bg-md-background/80 backdrop-blur border-b border-md-outline/20 px-4 md:px-8 py-3 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2">
                    <button class="md:hidden rounded-full p-2.5 text-md-on-surface-variant hover:bg-md-primary/10 active:scale-95 transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary" onclick="toggleSidebar()" aria-label="Buka menu navigasi">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <h2 class="text-lg md:text-xl font-medium text-md-on-surface">@yield('page-title', 'Dasbor Kesehatan')</h2>
                </div>

                <div class="flex items-center gap-3">
                    <!-- JDN Badge -->
                    <div class="hidden sm:inline-flex items-center gap-1.5 rounded-full bg-md-secondary-container px-4 py-1.5 text-xs font-medium text-md-on-secondary-container">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5" aria-hidden="true"></i>
                        Sistem Informasi JDN
                    </div>

                    <!-- Admin Avatar -->
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-md-on-surface leading-tight">Admin Utama</p>
                            <p class="text-xs text-md-on-surface-variant">Super Admin</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-md-tertiary text-white flex items-center justify-center text-sm font-medium" aria-hidden="true">
                            AD
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content Area -->
            <main class="flex-grow overflow-y-auto p-4 md:p-8">
                <div class="mx-auto max-w-7xl">
                    @if(session('success'))
                        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-emerald-100 p-4 text-sm font-medium text-emerald-900" role="alert">
                            <i data-lucide="check-circle" class="w-5 h-5 shrink-0" aria-hidden="true"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-red-100 p-4 text-sm font-medium text-red-900" role="alert">
                            <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0" aria-hidden="true"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-md-surface-container px-6 py-3.5 flex items-center justify-between text-xs text-md-on-surface-variant shrink-0">
                <p>&copy; {{ date('Y') }} Jaga Data Nusantara (JDN) – Analitika Kesehatan</p>
                <p class="hidden sm:block">V1.2.0 · Status OK</p>
            </footer>
        </div>
    </div>

    <!-- Mobile navigation drawer -->
    <div id="mobile-sidebar" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden" onclick="toggleSidebar()">
        <div class="w-72 h-full bg-md-surface-container flex flex-col p-4 gap-2 shadow-xl" onclick="event.stopPropagation()" role="dialog" aria-label="Menu navigasi">
            <div class="flex items-center justify-between px-3 py-2">
                <span class="font-medium text-md-on-surface">Menu Utama</span>
                <button class="rounded-full p-2 text-md-on-surface-variant hover:bg-md-primary/10 active:scale-95 transition-all duration-200" onclick="toggleSidebar()" aria-label="Tutup menu">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto mt-2 space-y-1">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}" class="{{ $navItemClass($item) }}">
                        <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 shrink-0"></i>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>
    </div>

    <script>
        lucide.createIcons();
        function toggleSidebar() {
            document.getElementById('mobile-sidebar').classList.toggle('hidden');
        }
        // Default chart MD3 global — halaman tinggal mengatur dataset & palet
        if (window.Chart) {
            Chart.defaults.font.family = "'Roboto', sans-serif";
            Chart.defaults.font.size = 11;
            Chart.defaults.color = '#49454F';
            Chart.defaults.borderColor = 'rgba(121, 116, 126, 0.2)';
        }
    </script>
    @stack('scripts')
</body>
</html>
