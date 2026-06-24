@extends('layouts.app')

@section('title', 'Analisis Spasial')
@section('page-title', 'Peta Sebaran Kasus & Fasilitas Kesehatan')

@section('content')
<!-- Include Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<style>
    #map { height: 600px; width: 100%; border-radius: 0.5rem; z-index: 1; }
    .info-legend {
        background: white;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.4);
        line-height: 18px;
        color: #555;
    }
    .info-legend i {
        width: 18px;
        height: 18px;
        float: left;
        margin-right: 8px;
        opacity: 0.7;
    }
</style>

<div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">Peta Sebaran Stunting Banjarnegara</h3>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
            GIS Aktif
        </span>
    </div>

    <div class="p-6">
        <p class="text-gray-600 mb-4">Peta ini menampilkan titik lokasi tiap kecamatan, dengan ukuran penanda yang menyesuaikan besarnya prevalensi stunting. Klik penanda untuk melihat detail jumlah Faskes.</p>
        
        <div class="border border-gray-300 rounded-lg p-1 bg-gray-50">
            <div id="map"></div>
        </div>
    </div>
</div>

<!-- Include Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Koordinat tengah Banjarnegara
        var map = L.map('map').setView([-7.3888, 109.6960], 11);

        // Tile layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var mapData = {!! json_encode($mapData) !!};

        // Render markers based on mapData
        mapData.forEach(function(item) {
            // Tentukan warna berdasarkan tingkat stunting
            var color = '#10B981'; // Hijau (< 10%)
            if (item.stunting >= 20) {
                color = '#EF4444'; // Merah (>= 20%)
            } else if (item.stunting >= 10) {
                color = '#F59E0B'; // Kuning/Orange (10% - 19.9%)
            }
            
            // Hitung radius lingkaran berdasarkan stunting (minimal radius agar tetap terlihat)
            var radius = Math.max(8, item.stunting * 1.5);

            var circle = L.circleMarker([item.lat, item.lng], {
                color: color,
                fillColor: color,
                fillOpacity: 0.6,
                radius: radius,
                weight: 2
            }).addTo(map);

            var popupContent = 
                '<div style="min-width: 150px;">' +
                    '<h4 style="margin: 0 0 5px 0; font-weight: bold; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Kecamatan ' + item.nama + '</h4>' +
                    '<p style="margin: 3px 0;"><strong>Prevalensi Stunting:</strong> ' + item.stunting + '%</p>' +
                    '<p style="margin: 3px 0;"><strong>Jumlah Faskes:</strong> ' + item.faskes + '</p>' +
                '</div>';
            circle.bindPopup(popupContent);
        });

        // Add Legend
        var legend = L.control({position: 'bottomright'});
        legend.onAdd = function (map) {
            var div = L.DomUtil.create('div', 'info-legend'),
                grades = [0, 10, 20],
                labels = ['Rendah (< 10%)', 'Sedang (10-20%)', 'Tinggi (> 20%)'],
                colors = ['#10B981', '#F59E0B', '#EF4444'];

            div.innerHTML = '<strong>Tingkat Prevalensi</strong><br>';
            for (var i = 0; i < grades.length; i++) {
                div.innerHTML +=
                    '<i style="background:' + colors[i] + '"></i> ' + labels[i] + '<br>';
            }
            return div;
        };
        legend.addTo(map);
    });
</script>
@endsection

