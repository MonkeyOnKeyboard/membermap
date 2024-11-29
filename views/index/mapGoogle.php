<?php

/** @var \Ilch\View $this */

if ($this->get('memberlocations')) : ?>
<script src="https://unpkg.com/@googlemaps/markerclustererplus/dist/index.min.js"></script>
<!-- Replace the value of the key parameter with your own API key. -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?=urlencode($this->get('apiKey')) ?>&callback=initialize&sensor=false">
</script>

<div id="map" style='width: 100%; height:530px;'></div>

<?php
$out_array = [];
/** @var Modules\Membermap\Models\MemberMap $location */
foreach ($this->get('memberlocations') as $location) {
    if ($location->getLat()) {
        $userlink     =   $this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $location->getUserid()]);
        $name         =   $location->getName();
        $lat          =   $location->getLat();
        $lng          =   $location->getLng();

        $city_array = [
            "user_link"   => $userlink,
            "names" => $name,
            "lat" => $lat,
            "lng" => $lng
        ];

        $out_array[] = $city_array;
    }
}
?>


<script type="text/javascript">
    function initialize() {
        var bounds = new google.maps.LatLngBounds();
        var geocoder = new google.maps.Geocoder();
        var mapOptions = {
                zoom: 5,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                center: new google.maps.LatLng(51.1642292, 10.4541194)
            };

        var locations = [<?php foreach ($out_array as $city) {
                            echo "[$city[lat], $city[lng], '".$city["names"]."', '".$city["user_link"]."']";
                            echo ", ";
                        } ?>
            ];
        var map = new google.maps.Map(document.getElementById('map'), mapOptions);
        var infowindow = new google.maps.InfoWindow();
        var markers=[];
        var markerCluster = new MarkerClusterer(map, markers, {
                imagePath:
                "https://cdn.rawgit.com/googlemaps/js-marker-clusterer/gh-pages/images/m",
            });
        for (var i = 0; i < locations.length; i++) {
            var a = locations[i];
            
            // Lat and Long for each marker
           var point = new google.maps.LatLng(
             parseFloat(a[0]),
             parseFloat(a[1])
           );
            var marker = new google.maps.Marker({
                map: map,
                position: point,
                title: a[2],
                url: a[3]
            });
            bounds.extend(marker.getPosition());
            map.fitBounds(bounds);

            google.maps.event.addListener(marker, 'click', (function(marker) {
                return function() {
                    infowindow.setContent("<div id='infowindow-content'><a href='" + marker.url + "' target='_blank'><h3>" + marker.title + "</h3></a></div>");
                    infowindow.open(map, marker);
                }
            })(marker));
            //<!-- below code alway lies inside the loop-->
            markers.push(marker);
            markerCluster.addMarker(marker);

        }
    }
</script> 
<?php else : ?>
<div class="alert alert-danger">
    <?=$this->getTrans('noEntries') ?>
</div>
<?php endif; ?>
