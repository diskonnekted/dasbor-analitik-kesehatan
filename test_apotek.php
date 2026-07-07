<?php
$url = "https://opendata.banjarnegarakab.go.id/api/3/action/datastore_search?resource_id=52b6b818-bd49-40f1-83bc-cd4557b44d37&limit=3";
$response = file_get_contents($url);
if ($response) {
    $data = json_decode($response, true);
    print_r($data['result']['fields']);
    print_r($data['result']['records']);
}
