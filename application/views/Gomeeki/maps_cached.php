<?
## GET CACHED 
$GetDataCached = $this->db->query("SELECT * FROM cached WHERE cached_endtime >= '".time()."' && cached_address = '".$tweetsCity."'");
foreach($GetDataCached->result() as $GetLatLng) {
	$GetLatLngData = $GetLatLng->cached_default_latlng;
}

?>
<script>
						$(document).ready(function () {
						var map;
						var elevator;
						var myOptions = {
							zoom: 13,
							center: new google.maps.LatLng(<?php echo $GetLatLngData; ?>),
							mapTypeId: 'terrain'
						};
						
						map = new google.maps.Map($('#map_canvas')[0], myOptions);				
						var addresses = ['<?php echo $tweetsCity; ?>'];
						

							
						<?php 
						
						foreach($GetDataCached->result() as $TweetsCached) {  ?>
	
							$.getJSON('http://maps.googleapis.com/maps/api/geocode/json?address=<?php echo $tweetsCity; ?>sensor=false&z=zoom', null, function (data) {

								var latlng = new google.maps.LatLng(<?php echo $TweetsCached->cached_lat; ?>, <?php echo $TweetsCached->cached_lng; ?>);
								var contentString = String("Tweet: <?php echo $TweetsCached->cached_text; ?>");
									
								var infowindow = new google.maps.InfoWindow({
									content: contentString,
								});
									
								var marker = new google.maps.Marker({
									position: latlng,
									map: map,
									icon: "<?php echo $TweetsCached->cached_profiles_image; ?>"
								});
									google.maps.event.addListener(marker, 'click', function() {
									infowindow.open(map,marker);
								});
					
							}); /* END GET JSON */	
						<? } ?>
					}); /* END GET JSON */
			</script>