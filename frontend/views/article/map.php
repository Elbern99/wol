<?php
/* @var $this yii\web\View */
$config = [
  'json_path' => '/json/countries.geo.json',
  //'json_path_countires' => json_decode(file_get_contents('http://iza.loc/json/economytypes.json'));
  'json_path_country' => '/json/countrydata.json',
  'json_path_economytypes' => '/json/economytypes.json'
];

$this->registerJsFile('https://unpkg.com/leaflet@1.0.1/dist/leaflet.js');
$this->registerJsFile('/js/icon.label.js');
$this->registerJsFile('/js/map.js', ['depends'=>['yii\web\YiiAsset']]);
$this->registerCssFile('/css/leaflet.css');
?>
<script type="text/javascript">
  var mapConfig = <?= json_encode($config) ?>
</script>

<div class="map-holder">
  <div class="map-info">
    <div class="map-info-content"></div>
    <div class="icon-close"></div>
  </div>
  <div id="map"></div>
</div>
