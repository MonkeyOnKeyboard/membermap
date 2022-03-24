<?php if ($this->get('memberlocations')) { ?>
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/openlayers/2.11/lib/OpenLayers.js"></script> 



<div id='map' style='width: 100%; height:530px;'></div>


<?php 
$city_array = [];
$out_array = [];
foreach ($this->get('memberlocations') as $location) {
    if ($location->getLat() != "")
    {
        
    if ($location->getCity() != "") {
        $name         =   $location->getName();
        $lat          =   $location->getLat();
        $lng          =   $location->getLng();

        $city_array = [
            "names" => $name,
            "lat" => $lat,
            "lng" => $lng
        ];

        array_push($out_array, $city_array);
        }
    }
}
?>


<script>

var addressPoints = [
	<?php 
        foreach ($out_array as $city) {
            echo "[$city[lat], $city[lng], '".$city["names"]."']"; echo ", ";
      }
    ?>
	];

var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ'
  })

latlng = L.latLng(51, 9);

var map = L.map('map', {center: latlng, zoom: 6, layers: [tiles], closePopupOnClick: false});

var markers = L.markerClusterGroup();

for (var i = 0; i < addressPoints.length; i++) {
  var a = addressPoints[i];
  var title = a[2];
  var marker = L.marker(new L.LatLng(a[0], a[1]), { title: title });
  marker.bindPopup(title, {autoClose: false, autoPan: false});
  markers.addLayer(marker);
}

map.addLayer(markers);

var bounds;
var markersDisplayed = false;

map.on('moveend zoomend', function(e) {
  bounds = map.getBounds();
  var zoom = map.getZoom();
  if (zoom > 16) {
    markers.eachLayer(function (layer) {
      if (bounds.contains(layer.getLatLng())) {
        markersDisplayed = true;
        layer.openPopup();
      }
    });
    }
  else if (markersDisplayed) {
    markersDisplayed = false;
    markers.eachLayer(function (layer) {
      if (bounds.contains(layer.getLatLng())) {
        layer.closePopup();
      }
    });
  }
});

markers.on('clusterclick', function (e) {
  bounds = map.getBounds();
  var zoom = map.getZoom();
  var childMarkers = e.layer.getAllChildMarkers();
  if (zoom > 16) {
    childMarkers.eachLayer(function (layer) {
      if (bounds.contains(layer.getLatLng())) {
        markersDisplayed = true;
        layer.openPopup();
      }
    });
  }
});    
</script>




<?php } else { ?>
<div class="alert alert-danger">
        <?=$this->getTrans('noEntrys') ?>
</div>
<?php } ?>

