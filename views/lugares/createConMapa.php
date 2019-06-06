<?php

$this->title = 'Create Lugares';
$this->params['breadcrumbs'][] = ['label' => 'Lugares', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
  #map {
    height: 100vh;
    width: 100vw;
  }
</style>

<div id="map"></div>
<script>
    // token: pk.eyJ1IjoicGVwZW16ZXJvIiwiYSI6ImNqd2RycTRqdDE1b3g0OG1xejJkOHg3c3MifQ.oWcUjnkG4kHYNbaNaz-ntg

    var map = L.map('map', {
        center: [36.776949, -6.350443],
        zoom: 14
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoicGVwZW16ZXJvIiwiYSI6ImNqd2RycTRqdDE1b3g0OG1xejJkOHg3c3MifQ.oWcUjnkG4kHYNbaNaz-ntg', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
</script>
