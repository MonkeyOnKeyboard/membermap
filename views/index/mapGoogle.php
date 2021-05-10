<?php if ($this->get('memberlocations')) { ?>
<?php 
$city_array = [];
$out_array = [];
foreach ($this->get('memberlocations') as $location) {

    if ($location->getCity() != "") {
        $name         =   $location->getName();
        $userlink         =   $this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $location->getUser_id()]);
        $zip_code         =   $location->getZip_code();
        $country_code = $location->getCountry_code();
        if ($location->getStreet() != "") {
            $address			=	$location->getStreet().''.$location->getCity();;
        }else{
        $address			=	$location->getCity();
        }
        $address			=	strtolower($address);
        $address			=	str_replace(array('ä','ü','ö','ß'), array('ae', 'ue', 'oe', 'ss'), $address );
        $address			=	preg_replace("/[^a-z0-9\_\s]/", "", $address);
        $address			=	str_replace( array(' ', '--'), array('-', '-'), $address );

        $city_array = [
            "names" => $name,
            "zip_code" => $zip_code,
            "address" => $address,
            "country_code" => $country_code,
            "user_link"   => $userlink,
        ];

        array_push($out_array, $city_array);
    }
}
?>
<script src="https://unpkg.com/@googlemaps/markerclustererplus/dist/index.min.js"></script>

<script type="text/javascript">
    function initialize() {
        var bounds = new google.maps.LatLngBounds();
        var geocoder = new google.maps.Geocoder();
        var mapOptions = {
                zoom: 5,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                center: new google.maps.LatLng(51.1642292, 10.4541194)
            };

        var locations = [<?php 
                foreach ($out_array as $city) {
                echo "['$city[names]', '$city[address] $city[zip_code] $city[country_code]', '$city[user_link]'],";
                }
                ?>
            ];
        var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
        var infowindow = new google.maps.InfoWindow();
        var markers=[];
        var markerCluster = new MarkerClusterer(map, markers, {
                imagePath:
                "https://cdn.rawgit.com/googlemaps/js-marker-clusterer/gh-pages/images/m",
            });
        for (var i = 0; i < locations.length; i++) {

            geocoder.geocode({
            'address': locations[i][1]
            }, (function(locations, i) {

                return function(results, status) {
                        var marker = new google.maps.Marker({
                                map: map,
                                position: results[0].geometry.location,
                                title: locations[i][0],
                        		url: locations[i][2]
                            });
                        bounds.extend(marker.getPosition());
                        map.fitBounds(bounds);

                        google.maps.event.addListener(marker, 'click', (function(marker) {

                            return function() {
                                    infowindow.setContent("<div id='infowindow-content'><a href='" + locations[i][2] + "' target='_blank'><h3>" + locations[i][0] + "</h3></a>" + results[0]['formatted_address'] + "</div>");
                                    infowindow.open(map, marker);
                                }

                            })(marker));
                        //<!-- below code alway lies inside the loop-->
                        markers.push(marker);
                        markerCluster.addMarker(marker);

                    }
                })(locations, i));
        }
    }
</script> 

<div id="map-canvas" style='width: 100%; height:530px;'></div>

<!-- Replace the value of the key parameter with your own API key. -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $this->get('apiKey');?>&callback=initialize&sensor=false">
</script>
<?php } else { ?>
<div class="alert alert-danger">
        <?=$this->getTrans('noEntrys') ?>
</div>
<?php } ?>
