<?php

$this->title = 'Create Lugares';
$this->params['breadcrumbs'][] = ['label' => 'Lugares', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$js = <<<EOF
EOF;
$this->registerJs($js);
?>
<style>

  #map {
    height: 100%;
    overflow: visible;
  }
</style>
<div class="">
  <div id="map"></div>
  <script>
    var map;
    function initMap() {
      map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 36.778888, lng: -6.354103},
        zoom: 8
      });
    }
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGV2Cl4bWtapl5etmS5Yoz_HRWiHL-S6w&callback=initMap"
  async defer></script>
</div>
//36.778888, lng: -6.354103
