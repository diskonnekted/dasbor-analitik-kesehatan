@extends('layouts.app')

@section('title', 'Analisis Spasial')
@section('page-title', 'Peta Sebaran Kasus & Fasilitas Kesehatan')

@section('content')
<style>
    #map { 
        height: 550px; 
        width: 100%; 
        border: none;
        z-index: 1; 
    }
    .info-legend {
        background: white !important;
        padding: 12px !important;
        border: 2px solid #171717 !important;
        box-shadow: 3px 3px 0px 0px #171717 !important;
        font-family: 'Courier New', monospace !important;
        font-weight: bold !important;
        font-size: 10px !important;
        line-height: 18px;
        color: #171717;
    }
    .info-legend i {
        width: 16px;
        height: 16px;
        float: left;
        margin-right: 8px;
        border: 1px solid #171717;
    }
</style>

<div class="space-y-8">
    <!-- Hero Header Banner -->
    <div class="bg-white border-2 border-[#171717] p-8 shadow-[4px_4px_0px_0px_#171717] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#171717] text-white text-[10px] font-mono font-bold tracking-widest uppercase mb-4 border border-[#171717] shadow-[2px_2px_0px_0px_#171717]">
                <i data-lucide="map" class="w-3.5 h-3.5 text-emerald-400"></i>
                Geographic Information System (GIS)
            </div>
            <h1 class="text-3xl font-serif font-black text-[#171717] uppercase tracking-tight mb-2">Peta Spasial Kesehatan</h1>
            <p class="text-neutral-600 font-mono font-bold text-xs leading-relaxed">
                Pemetaan spasial penyebaran angka prevalensi stunting per kecamatan di Kabupaten Banjarnegara terintegrasi langsung dengan batas administratif wilayah (GeoJSON).
            </p>
        </div>
        
        <a href="{{ route('analisis.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white text-[#171717] font-mono font-black uppercase text-xs border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] hover:shadow-[4px_4px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all self-start md:self-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Menu</span>
        </a>
    </div>

    <!-- Map Container -->
    <div class="bg-white border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717]">
        <div class="px-6 py-4 border-b-2 border-[#171717] bg-[#f4f4f0] flex justify-between items-center">
            <h3 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] flex items-center gap-2">
                <i data-lucide="globe" class="w-4.5 h-4.5 text-teal-600"></i>
                Interactive Choropleth Map
            </h3>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border-2 border-[#171717] shadow-[1px_1px_0px_0px_#171717] text-[10px] font-mono font-bold tracking-wider text-emerald-700 uppercase">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                WebGIS Live
            </span>
        </div>

        <div class="p-6">
            <p class="text-xs font-mono font-bold text-neutral-500 mb-4">
                Petunjuk: Sorot kursor ke atas kecamatan untuk melihat batas administratif, atau klik wilayah tersebut untuk memunculkan detail stunting dan sarana kesehatan setempat.
            </p>
            
            <div class="border-2 border-[#171717] shadow-[3px_3px_0px_0px_#171717] overflow-hidden">
                <div id="map"></div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var map = L.map('map').setView([-7.3888, 109.6960], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Fungsi untuk menormalisasi nama kecamatan
        function normalizeName(name) {
            if (!name) return '';
            return name.toLowerCase()
                       .replace(/[\s\-]/g, '')
                       .replace('purworejo', 'purwareja')
                       .replace('klampok', 'klampok');
        }

        var mapData = {!! json_encode($mapData) !!};
        
        var dict = {};
        mapData.forEach(function(item) {
            dict[normalizeName(item.nama)] = item;
        });

        // Warna heatmap stunting
        function getColor(d) {
            return d >= 30 ? '#7f1d1d' : // Sangat kritis
                   d >= 25 ? '#b91c1c' : // Tinggi
                   d >= 20 ? '#ef4444' : // Waspada
                   d >= 15 ? '#f97316' : 
                   d >= 10 ? '#f59e0b' : 
                   d > 0   ? '#10b981' : // Aman
                             '#e5e5e5';
        }

        fetch('{{ asset("assets/peta_kecamatan.geojson") }}')
            .then(res => res.json())
            .then(data => {
                var geojson = L.geoJSON(data, {
                    style: function(feature) {
                        let kecName = feature.properties.Kecamatan || '';
                        let normName = normalizeName(kecName);
                        let stunting = dict[normName] ? dict[normName].stunting : 0;
                        
                        return {
                            fillColor: getColor(stunting),
                            weight: 2,
                            opacity: 1,
                            color: '#171717',
                            dashArray: '4',
                            fillOpacity: 0.85
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        let kecName = feature.properties.Kecamatan || 'Unknown';
                        let normName = normalizeName(kecName);
                        let dataKec = dict[normName] || { stunting: 0, faskes: 0 };
                        let displayName = dict[normName] ? dict[normName].nama : kecName;
                        
                        layer.on({
                            mouseover: function(e) {
                                var l = e.target;
                                l.setStyle({ 
                                    weight: 4, 
                                    color: '#171717', 
                                    dashArray: '', 
                                    fillOpacity: 0.95 
                                });
                                l.bringToFront();
                            },
                            mouseout: function(e) { 
                                geojson.resetStyle(e.target); 
                            }
                        });

                        var popupContent = 
                            '<div style="font-family: \'Courier New\', monospace; font-size: 11px; font-weight: bold; color: #171717; min-width: 180px; padding: 4px;">' +
                                '<h4 style="margin: 0 0 8px 0; font-size: 13px; font-weight: 900; text-transform: uppercase; border-bottom: 2px solid #171717; padding-bottom: 4px;">Kec. ' + displayName + '</h4>' +
                                '<p style="margin: 4px 0;">Stunting   : <span style="background: ' + getColor(dataKec.stunting) + '; padding: 1px 4px; border: 1px solid #171717; color: ' + (dataKec.stunting >= 20 ? 'white':'#171717') + '">' + dataKec.stunting + '%</span></p>' +
                                '<p style="margin: 4px 0;">Jml Faskes : ' + dataKec.faskes + '</p>' +
                            '</div>';
                        layer.bindPopup(popupContent);
                    }
                }).addTo(map);
            });

        // Add Legend Control
        var legend = L.control({position: 'bottomright'});
        legend.onAdd = function (map) {
            var div = L.DomUtil.create('div', 'info-legend'),
                grades = [0, 10, 15, 20, 25, 30],
                labels = ['Aman (<10%)', 'Ringan (10-15%)', 'Waspada (15-20%)', 'Tinggi (20-25%)', 'Sangat Tinggi (25-30%)', 'Kritis (>30%)'],
                colors = ['#10b981', '#f59e0b', '#f97316', '#ef4444', '#b91c1c', '#7f1d1d'];

            div.innerHTML = '<strong style="font-size: 11px; display:block; border-bottom: 2px solid #171717; margin-bottom: 6px; padding-bottom: 2px; text-transform: uppercase;">Stunting</strong>';
            for (var i = 0; i < grades.length; i++) {
                div.innerHTML += '<i style="background:' + colors[i] + '"></i> ' + labels[i] + '<br>';
            }
            return div;
        };
        legend.addTo(map);
    });
</script>
@endsection
