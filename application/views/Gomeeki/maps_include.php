<script>
						$(document).ready(function () {
						var map;
						var elevator;
						var myOptions = {
							zoom: 13,
							center: new google.maps.LatLng(<?php echo $LatValue; ?>, <?php echo $LongValue; ?>),
							mapTypeId: 'terrain'
						};
						
						map = new google.maps.Map($('#map_canvas')[0], myOptions);				
						var addresses = ['<?php echo $tweetsCity; ?>'];
						
				<?php foreach($string["result"]["places"] as $LoopMarkers) { 
						$FindTextUrl = "https://api.twitter.com/1.1/search/tweets.json";
						$FindTextQuery = "?geocode=".$LoopMarkers["centroid"][1].",".$LoopMarkers["centroid"][0].",50km&result_type=recent&count=1";
						$FindText = json_decode($twitter->setGetfield($FindTextQuery)
						->buildOauth($FindTextUrl, $requestMethod)
						->performRequest(),$assoc = TRUE);
							
							foreach($FindText["statuses"] as $MarkersTweets) {  ?>
	
							$.getJSON('http://maps.googleapis.com/maps/api/geocode/json?address=<?php echo $tweetsCity; ?>sensor=false&z=zoom', null, function (data) {

								var latlng = new google.maps.LatLng(<?php echo $LoopMarkers["centroid"][1]; ?>, <?php echo $LoopMarkers["centroid"][0]; ?>);
								var contentString = String("Tweet: <?php echo str_ireplace("\n","",str_ireplace("'","",addslashes($MarkersTweets["text"]))); ?><br>When: <?php echo $MarkersTweets["created_at"]; ?>");
									
								var infowindow = new google.maps.InfoWindow({
									content: contentString,
								});
									
								var marker = new google.maps.Marker({
									position: latlng,
									map: map,
									icon: "<?php echo $MarkersTweets["user"]["profile_image_url"]; ?>"
								});
									google.maps.event.addListener(marker, 'click', function() {
									infowindow.open(map,marker);
								});
					
							}); /* END GET JSON */
							
						<?php 
						
						########### START INSERT CAHCED TO DATABASE ########### 	
							$CachedInsert = array(
								"cached_address"			=>	$tweetsCity,
								"cached_text"				=>	str_ireplace("\n","",str_ireplace("'","",addslashes($MarkersTweets["text"])))."<br>When: ".$MarkersTweets["created_at"],
								"cached_profiles_name"		=>	$MarkersTweets["user"]["name"],
								"cached_profiles_image"		=>	$MarkersTweets["user"]["profile_image_url"],
								"cached_lat"				=>	$LoopMarkers["centroid"][1],
								"cached_lng"				=>	$LoopMarkers["centroid"][0],
								"cached_default_latlng"		=>	"$LatValue,$LongValue",
								"cached_starttime"			=>	time(),
								"cached_endtime"			=>	time()+3600,
							
							);
							$this->db->insert('cached', $CachedInsert); 
						
						
						########### END INSERT ###########	
									
							}  # END FOREACH SEARCH TWEETS
						} #END FOREACE GEO SEARCH
					?>
					});
			</script>