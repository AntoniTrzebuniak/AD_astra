<h1>Mapa stacji obserwacyjnych</h1>
<p class="subtitle">Lokalizacje stacji i planowane planetoidy na dziś</p>
<div id="map" class="obs-map"></div>
<div id="map-legend" class="panel map-legend">
    <p>Kliknij marker stacji, aby zobaczyć planowane obserwacje na dziś.</p>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    window.OBSERVATORY_MAP_API = <?= json_encode(baseUrl('api/map-data'), JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="<?= e(assetUrl('js/map.js')) ?>"></script>
