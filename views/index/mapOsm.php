<?php

/** @var \Ilch\View $this */

if ($this->get('memberlocations')) { ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/openlayers/2.11/lib/OpenLayers.js"></script>
<div class="text-center">
    <?php if ($this->getUser()) { ?>
    <a class="btn btn-outline-secondary" href="<?= $this->getUrl(['action' => 'treat'])?>" title="<?= $this->getTrans('mapEntry')?>"><?= $this->getTrans('mapEntry')?></a> &nbsp;
    <?php } ?>
    <a class="btn btn-outline-secondary" href="<?= $this->getUrl(['action' => 'map'])?>" title="<?= $this->getTrans('mapView')?>"><?= $this->getTrans('mapView')?></a>
</div>
<br>
<div id='map' style='width: 100%; height:530px;'></div>
<?php
$out_array = [];
/** @var Modules\Membermap\Models\MemberMap $location */
foreach ($this->get('memberlocations') as $location) {
    if ($location->getLat()) {
        $userlink     = $this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $location->getUserid()]);
        $name         = $location->getName();
        $lat          = $location->getLat();
        $lng          = $location->getLng();

        $city_array = [
            "user_link" => $userlink,
            "names"     => $name,
            "lat"       => $lat,
            "lng"       => $lng
        ];

        $out_array[] = $city_array;
    }
}
?>

<script>
    function openPopUp(id, clusterId){
        map.closePopup(); //which will close all popups
        map.eachLayer(function(layer){     //iterate over map layer
            if (layer._leaflet_id == clusterId){         // if layer is markerCluster
                layer.spiderfy(); //spiederfies our cluster
            }
        });
        map.eachLayer(function(layer){     //iterate over map rather than clusters
            if (layer._leaflet_id == id){         // if layer is marker
                layer.openPopup();
            }
        });
    }

    var locations = [<?php foreach ($out_array as $city) {
                         echo "[$city[lat], $city[lng], '".$city["names"]."', '".$city["user_link"]."']"; echo ", ";
                     } ?>
    ];

    var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ'
    })

    latlng = L.latLng(51, 9);

    var map = L.map('map', {center: latlng, zoom: 6, layers: [tiles], closePopupOnClick: false});

    var markers = L.markerClusterGroup();

    for (var i = 0; i < locations.length; i++) {
        var a = locations[i];
        var title = a[2];
        var user_link = a[3];
        var marker = L.marker(new L.LatLng(a[0], a[1]), { title: title });
        var text = '<a href="' + user_link + '" target="_blank">' + title + '</a>';
        marker.bindPopup(text, {autoClose: false, autoPan: false});
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

    markers.on('clusterclick', function(e){
        if(e.layer._zoom == 18){
            popUpText = '<ul>';
            //there are many markers inside "a". to be exact: a.layer._childCount much ;-)
            //let's work with the data:
            for (feat in e.layer._markers){
                popUpText+= '<li><u onclick=openPopUp(' + e.layer._markers[feat]._leaflet_id + ','+ e.layer._leaflet_id +')>' + e.layer._markers[feat].feature.properties['title'] +  '</u></li>';
            }
            popUpText += '</ul>';
            //as we have the content, we should add the popup to the map add the coordinate that is inherent in the cluster:
            var popup = L.popup().setLatLng([e.layer._cLatLng.lat, e.layer._cLatLng.lng]).setContent(popUpText).openOn(map); 
        }
    });
</script>
<?php } else { ?>
<div class="alert alert-danger">
    <?=$this->getTrans('noEntries') ?>
</div>
<?php } ?>
