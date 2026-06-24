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
    
        <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <!-- Leaflet.js untuk Peta -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        :root {
            --primary-red: #DC2626;
            --primary-black: #1F2937;
            --primary-white: #FFFFFF;
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-red {
            background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0 hidden md:block">
            <div class="p-6">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('assets/app_logo.png') }}" alt="Logo" class="w-10 h-10 rounded-lg bg-white p-1">
                    <div>
                        <h1 class="text-lg font-bold text-red-500">KESEHATAN</h1>
                        <p class="text-xs text-gray-400">Banjarnegara Kab.</p>
                    </div>
                </div>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-6 py-3 hover:bg-red-600 transition {{ request()->routeIs('dashboard') ? 'bg-red-600 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                
                <a href="{{ route('faskes.index') }}" 
                   class="flex items-center px-6 py-3 hover:bg-red-600 transition {{ request()->routeIs('faskes.*') ? 'bg-red-600 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Fasilitas Kesehatan
                </a>
                
                <a href="{{ route('tenaga-kesehatan.index') }}" 
                   class="flex items-center px-6 py-3 hover:bg-red-600 transition {{ request()->routeIs('tenaga-kesehatan.*') ? 'bg-red-600 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Tenaga Kesehatan
                </a>
                
                <a href="{{ route('penyakit.index') }}" 
                   class="flex items-center px-6 py-3 hover:bg-red-600 transition {{ request()->routeIs('penyakit.*') ? 'bg-red-600 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Penyakit
                </a>
                
                <a href="{{ route('stunting.index') }}" 
                   class="flex items-center px-6 py-3 hover:bg-red-600 transition {{ request()->routeIs('stunting.*') ? 'bg-red-600 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Stunting & Gizi
                </a>
                
                <a href="{{ route('aki-akb.index') }}" 
                   class="flex items-center px-6 py-3 hover:bg-red-600 transition {{ request()->routeIs('aki-akb.*') ? 'bg-red-600 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    AKI & AKB
                </a>
                
                <a href="{{ route('imunisasi.index') }}" 
                   class="flex items-center px-6 py-3 hover:bg-red-600 transition {{ request()->routeIs('imunisasi.*') ? 'bg-red-600 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Imunisasi
                </a>
                
                <a href="{{ route('posyandu.index') }}" 
                   class="flex items-center px-6 py-3 hover:bg-red-600 transition {{ request()->routeIs('posyandu.*') ? 'bg-red-600 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Peta Posyandu
                </a>
                
                <a href="{{ route('analisis.index') }}" 
                   class="flex items-center px-6 py-3 hover:bg-red-600 transition {{ request()->routeIs('analisis.*') ? 'bg-red-600 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Analisa & Prediksi
                </a>
                
                <a href="{{ route('laporan.index') }}" 
                   class="flex items-center px-6 py-3 hover:bg-red-600 transition {{ request()->routeIs('laporan.*') ? 'bg-red-600 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Laporan
                </a>
                
                <a href="{{ route('info') }}" 
                   class="flex items-center px-6 py-3 hover:bg-red-600 transition {{ request()->routeIs('info') ? 'bg-red-600 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Info JDN
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b-2 border-red-600">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button class="md:hidden text-gray-600 mr-4" onclick="toggleSidebar()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h2 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="relative text-gray-600 hover:text-red-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                            </button>
                        </div>
                        
                        <!-- User Profile -->
                        <div class="flex items-center space-x-3">
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-800">Admin</p>
                                <p class="text-xs text-gray-500">Super Admin JDN</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center text-white font-bold">
                                A
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                <?php if(session('success')): ?>
                    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                <?php endif; ?>
                
                <?php if(session('error')): ?>
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                <?php endif; ?>
                
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="bg-white border-t px-6 py-4">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <p>&copy; {{ date('Y') }} Jaga Data Nusantara (JDN)</p>
                    <p>Version 1.0.0</p>
                </div>
            </footer>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>

