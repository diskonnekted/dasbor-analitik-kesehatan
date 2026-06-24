<?php
$json = json_decode(file_get_contents('I:\kesehatan\aplikasi\public\assets\peta_kecamatan.geojson'), true);
print_r($json['features'][0]['properties']);
