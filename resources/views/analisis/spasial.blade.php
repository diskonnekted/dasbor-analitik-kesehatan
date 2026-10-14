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
        background: #FFFFFF !important;
        padding: 14px 16px !important;
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 2px 8px rgba(28, 27, 31, 0.15) !important;
        font-family: 'Roboto', sans-serif !important;
        font-weight: 500 !important;
        font-size: 11px !important;
        line-height: 20px;
        color: #1C1B1F;
    }
    .info-legend i {
        width: 14px;
        height: 14px;
        float: left;
        margin-right: 8px;
        border-radius: 4px;
        opacity: 0.9;
    }
</style>

<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <span class="md-badge mb-4">
                <i data-lucide="map" class="w-3.5 h-3.5"></i>
                Geographic Information System (GIS)
            </span>
            <h1 class="text-3xl font-medium text-md-on-surface tracking-tight mb-2">Peta Spasial Kesehatan</h1>
            <p class="text-sm text-md-on-surface-variant leading-relaxed">
                Pemetaan spasial penyebaran angka prevalensi stunting per kecamatan di Kabupaten Banjarnegara terintegrasi langsung dengan batas administratif wilayah (GeoJSON).
            </p>
        </div>
        <a href="{{ route('analisis.index') }}" class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-full bg-md-secondary-container text-md-on-secondary-container text-sm font-medium hover:shadow-md transition-shadow self-start md:self-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Menu</span>
        </a>
    </div>

    <div class="md-card overflow-hidden">
        <div class="px-6 py-4 bg-md-surface-container-low flex justify-between items-center">
            <h3 class="md-card-title">
                <i data-lucide="globe" class="w-4 h-4 text-md-primary"></i>
                Interactive Choropleth Map
            </h3>
            <span class="md-badge">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                WebGIS Live
            </span>
        </div>

        <div class="p-6">
            <p class="text-xs text-md-on-surface-variant mb-4">
                Petunjuk: Sorot kursor ke atas kecamatan untuk melihat batas administratif, atau klik wilayah tersebut untuk memunculkan detail stunting dan sarana kesehatan setempat.
            </p>
            <div class="rounded-[20px] overflow-hidden">
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

        // MD3 purple ramp (rendah -> kritis)
        function getColor(d) {
            return d >= 30 ? '#31111D' :
                   d >= 25 ? '#4A3773' :
                   d >= 20 ? '#6750A4' :
                   d >= 15 ? '#8F76C6' :
                   d >= 10 ? '#B69DF8' :
                   d > 0   ? '#E7E0EC' :
                             '#F3EDF7';
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
                            weight: 1.5,
                            opacity: 1,
                            color: '#49454F',
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
                                    weight: 3,
                                    color: '#1C1B1F',
                                    fillOpacity: 0.95
                                });
                                l.bringToFront();
                            },
                            mouseout: function(e) {
                                geojson.resetStyle(e.target);
                            }
                        });

                        var popupContent =
                            '<div style="font-family: \'Roboto\', sans-serif; font-size: 12px; color: #1C1B1F; min-width: 190px; padding: 4px;">' +
                                '<h4 style="margin: 0 0 8px 0; font-size: 13px; font-weight: 500; padding-bottom: 6px; border-bottom: 1px solid #E7E0EC;">Kec. ' + displayName + '</h4>' +
                                '<div style="display: flex; justify-content: space-between; align-items: center; margin: 6px 0; padding: 6px 10px; background: #E8DEF8; border-radius: 12px;">' +
                                    '<span style="color: #1C1B1F;">Prevalensi Stunting</span>' +
                                    '<strong style="color: #6750A4;">' + dataKec.stunting + '%</strong>' +
                                '</div>' +
                                '<div style="display: flex; justify-content: space-between; align-items: center; margin: 6px 0; padding: 6px 10px; background: #E7E0EC; border-radius: 12px;">' +
                                    '<span style="color: #1C1B1F;">Jumlah Faskes</span>' +
                                    '<strong style="color: #6750A4;">' + dataKec.faskes + '</strong>' +
                                '</div>' +
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
                colors = ['#E7E0EC', '#B69DF8', '#8F76C6', '#6750A4', '#4A3773', '#31111D'];

            div.innerHTML = '<strong style="font-size: 11px; display:block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Stunting</strong>';
            for (var i = 0; i < grades.length; i++) {
                div.innerHTML += '<i style="background:' + colors[i] + '"></i> ' + labels[i] + '<br>';
            }
            return div;
        };
        legend.addTo(map);
    });
</script>
@endsection
