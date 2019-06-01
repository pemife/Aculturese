<?php

$this->title = 'Create Lugares';
$this->params['breadcrumbs'][] = ['label' => 'Lugares', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$js = <<<EOF
EOF;
$this->registerJs($js);
?>


<div id="map"></div>
 <script>
   // token: pk.eyJ1IjoicGVwZW16ZXJvIiwiYSI6ImNqd2RycTRqdDE1b3g0OG1xejJkOHg3c3MifQ.oWcUjnkG4kHYNbaNaz-ntg

   var map = L.map('map', {
          center: [36.776949, -6.350443],
          zoom: 14
      });

   L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

   L.marker([51.5, -0.09]).addTo(map)
       .bindPopup('A pretty CSS3 popup.<br> Easily customizable.')
       .openPopup();
 </script>
