<style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
    </style>
<form class="form-horizontal" method="POST" action="">
  <?=$this->getTokenField() ?>
 
  <?php 
  /**
  foreach ($this->get('memberlocations') as $location) {
   
   if ($location->getCity() != "") {
 
        echo "<hr>";
        echo "<pre>";
        print_r($location);
        echo "</pre>";
        echo "<hr>";
  
        }  
  }
  
  **/
  
  
  foreach ($this->get('memberlocations') as $location) {
      
      if ($location->getCity() != "") {
          $zip_code         =   $location->getZip_code();
          $country_code     =   $location->getCountry_code();
          $address			=	$location->getCity();
          $address			=	str_replace(array('ä','ü','ö','ß'), array('ae', 'ue', 'oe', 'ss'), $address );
          $address			=	preg_replace("/[^a-zA-Z0-9\_\s]/", "", $address);
          $address			=	strtolower($address);
          $address			=	str_replace( array(' ', '--'), array('-', '-'), $address );
          echo "'$zip_code $address $country_code'"; echo ", ";
      }
  }
  
  ?>
 

</form>
