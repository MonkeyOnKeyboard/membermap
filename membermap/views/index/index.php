
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/leaflet.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/leaflet.js"></script>
    <script src="https://www.mapquestapi.com/sdk/leaflet/v2.2/mq-map.js?key=<?php echo $this->get('apiKey');?>"></script>
    <script src="https://www.mapquestapi.com/sdk/leaflet/v2.2/mq-geocoding.js?key=<?php echo $this->get('apiKey');?>"></script>

<form class="form-horizontal" method="POST" action="">
<?=$this->getTokenField() ?>

<?php 
                    $city_array = [];
                    $my_array = [];
                    $names_array = [];
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
                            
                            array_push($my_array, $city_array);
                            
                            $names_array = [
                                
                                "names" => $name,
                                "city" => $address,
                                
                            ];
                            
                            array_push($out_array, $names_array);
                            
                        }
                    }
                    
                    $js_json = json_encode($out_array, JSON_FORCE_OBJECT);
             		  ?>



<script type="text/javascript">
        window.onload = function() {

        	 	
        	
        MQ.geocode().search([
        		<?php 
        		      foreach ($my_array as $city) {
        		          echo "'$city[zip_code] $city[address] $city[country_code]'"; echo ", ";
        		      }
        		?>
             ])
             
            .on('success', function(e) {
            	var js_array =<?php echo json_encode($out_array );?>;
            	
                var results = e.result,
                	html = '',
                    group = [],
                    features,
                    marker,
                    result,
                    name,
                    city,
                    latlng,
                    prop,
                    best,
                    val,
                    map,
                    r,
                    i;

                map = L.map('map', {
                    layers: MQ.mapLayer()
                });

                 
                for (i = 0; i < results.length; i++) {
                    
                    result = results[i].best;
                    latlng = result.latlng;
					
                    html += '<div style="width:300px; float:left;">';
            html += '<p><strong>Geocoded Location #' + (i + 1) + '</strong></p>';

            for (prop in result) {
            	                
            r = result[prop];

            if (prop === 'displayLatLng') {
            val = r.lat + ', ' + r.lng;

            } else if (prop === 'mapUrl') {
            val = '<br/><img src="' + r + '"/>';

            } else {
            val = r;
            }

            html += prop + ' : ' + val + '<br/>';
            }

            html += '</div>';
						
                       // create POI markers for each location
                marker = L.marker([ latlng.lat, latlng.lng ])
                			if(result.adminArea5.indexOf(js_array[i].city)){
                				marker.bindPopup(js_array[i].names + ', ' + result.adminArea5 + ', ' + result.adminArea3);
                

                    group.push(marker);
                			}
                }

                // add POI markers to the map and zoom to the features
                features = L.featureGroup(group).addTo(map);
                map.fitBounds(features.getBounds());

                // show location information
                L.DomUtil.get('info').innerHTML = html;

            });

        
        }
    </script>
<?php 

echo "<hr>";
echo "<pre>";
print_r($js_json);
echo "</pre>";
echo "<hr>";

?>
<div id='map' style='width: 100%; height:530px;'></div>
<div id="info"></div>



</form>
