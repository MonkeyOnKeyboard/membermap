<form class="form-horizontal" method="POST" action="">
<?=$this->getTokenField() ?>


<script src="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.js"></script>
<link type="text/css" rel="stylesheet" href="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.css"/>
 
<script src="https://unpkg.com/leaflet.markercluster@1.0.6/dist/leaflet.markercluster.js"></script>
<link type="text/css" rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.0.6/dist/MarkerCluster.css"/>
<link type="text/css" rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.0.6/dist/MarkerCluster.Default.css"/>



<?php 
                    $city_array = [];
                    $out_array = [];
                    foreach ($this->get('memberlocations') as $location) {
                        
                        if ($location->getCity() != "") {
                            $name         =   $location->getName();
                            $zip_code         =   $location->getZip_code();
                            $country_code = $location->getCountry_code();
                            $address			=	$location->getCity();
                            $address			=	str_replace(array('ä','ü','ö','ß'), array('ae', 'ue', 'oe', 'ss'), $address );
                            $address			=	preg_replace("/[^a-zA-Z0-9\_\s]/", "", $address);
                            $address			=	strtolower($address);
                            $address			=	str_replace( array(' ', '--'), array('-', '-'), $address );
                            
                            
                            $city_array = [
                                "names" => $name,
                                "zip_code" => $zip_code,
                                "address" => $address,
                                "country_code" => $country_code,
                                
                                
                            ];
                            
                            array_push($out_array, $city_array);
                           
                            
                        }
                    }
                    
                    
             		  ?>



<script type="text/javascript">
        window.onload = function() {

        	L.mapquest.key = '<?php echo $this->get('apiKey');?>';	 	

        	var geocoder = L.mapquest.geocoding();
        	geocoder.geocode([<?php 
            		foreach ($out_array as $city) {
		          echo "'$city[address], $city[zip_code], $city[country_code]'"; echo ", ";
		      }
		?>], createMap);

        	
        	function createMap(error, response) {
                // Initialize the Map
                var map = L.mapquest.map('map', {
                  layers: L.mapquest.tileLayer('map'),
                  center: [0, 0],
                  zoom: 12
                });

                // Generate the feature group containing markers from the geocoded locations
                var featureGroup = generateMarkersFeatureGroup(response);

                // Add markers to the map and zoom to the features
                featureGroup.addTo(map);
                map.fitBounds(featureGroup.getBounds());
              }
        	var js_array =<?php echo json_encode($out_array );?>;
        	var markers = L.markerClusterGroup();
        		
              function generateMarkersFeatureGroup(response) {
                var group = [];
                for (var i = 0; i < response.results.length; i++) {
                  var location = response.results[i].locations[0];
                  var locationLatLng = location.latLng;

                  // Create a marker for each location
                  var title = js_array[i].names;
                  var marker = L.marker(locationLatLng, {title: title, icon: L.mapquest.icons.marker()})

                    marker.bindPopup(title);
                    markers.addLayer(marker);

                  group.push(markers);
                }
                return L.featureGroup(group);
              }
            }


        
        
</script>

<div id='map' style='width: 100%; height:530px;'></div>
</form>
