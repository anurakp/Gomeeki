<?php
header('content-type:text/html;charset=utf-8');
date_default_timezone_set('Asia/Bangkok'); 
require_once('api/twitter/TwitterAPIExchange.php');
 
if(!empty($_GET['tweets_cityname'])) { $tweetsCity = str_ireplace("-"," ",$_GET['tweets_cityname']);} else { $tweetsCity = ""; }
if(!empty($tweetsCity)) {
	
	$Distance = "50km";
	$LimitTweets = 10;
	/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
	$settings = array(
		'oauth_access_token' => "3165230437-yHM5GHdMUfkEJyvy0XmdDEUyBTWjha1LYkkiLhk",
		'oauth_access_token_secret' => "xYWfVL7cEgTnAmb0gERhtOWPl5lDijNeU3J3TAU16fGjl",
		'consumer_key' => "IKC20BRF2GJJo9uQmOWXXLUTi",
		'consumer_secret' => "R7PUggRafXG8ukNzJHPFwSd54Gh588y0lbXPkNmnDtYb5YMmCH"
	);
	
	
	
	$url = "https://api.twitter.com/1.1/geo/search.json";
	$requestMethod = "GET";
	$getfield = '?max_results='.$LimitTweets.'&query='.$_GET['tweets_cityname'];
	
	$twitter = new TwitterAPIExchange($settings);
	
	$string = json_decode($twitter->setGetfield($getfield)
	->buildOauth($url, $requestMethod)
	->performRequest(),$assoc = TRUE);
	
	
	if(!empty($tweetsCity)) { 
		$NoLoop = 1;
		print_r($string);
		foreach($string["result"]["places"] as $LatLng) {
				if($NoLoop == 1) { 
					$LatValue = $LatLng["centroid"][1];
					$LongValue = $LatLng["centroid"][0];
				}
		$NoLoop++;
		}
	}
# END MAPS SETTING
} 

?>
<html>
<head>
<title>Gomeeki ASSIGNMENT by Anurak Piamjad</title>
<meta name="keywords" content="CzGroup Maps,Gomeeki Assignments">
<meta name="robots" content="noindex,nofollow">
<script src="<?php echo base_url(); ?>jquery/jquery.min-1.11.2.js"></script>
<script type='text/javascript' src="http://maps.google.com/maps/api/js?sensor=false&.js"></script>
<link href="<?php echo base_url(); ?>css/responsive.css" rel="stylesheet" >
</head>
<body>

<div class="wrapper">
<div class="header"><strong>MY ASSIGNMENT</strong></div>
       <div class="maps">
        
        		<div id="map_canvas"></div>
                <?php if(!empty($tweetsCity)) { ?>
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
					
							});
							
						<? } } ?>
					});
			</script>
            
            
            
			<? }  else {
			### IF EMPTY LOCATION SHOW DEFAULT MAP ###
			?>
            
			<script>
						$(document).ready(function () {
						var map;
						var elevator;
						var myOptions = {
							zoom: 5,
							center: new google.maps.LatLng(15, 100),
							mapTypeId: 'terrain'
						};
						map = new google.maps.Map($('#map_canvas')[0], myOptions);				
						
					});
			</script>>
            
			<?
			### END DEFAULT MAPS 
				}	
			?>
        </div>
        
        
        
        <?php if(empty($_GET['TweetsCitySearch']) || (!empty($_GET['TweetsCitySearch']) && $_GET['TweetsCitySearch'] == "Search")) { ?>
			<?php if(empty($_GET['TweetsCityHistory'])) { ?>
                <div class="filterTweets">
                
                <form name="tw" method="get"><input name="tweets_cityname" id="tweets_cityname" type="text" class="filterTweetsInput" placeholder="Fill City Name..."> 
                <input name="TweetsCitySearch" type="submit" class="filterButton" value="Search">
                </form><a href="<?php echo base_url(); ?>?TweetsCityHistory=History<?php if(!empty($_GET['tweets_cityname'])) { echo "&tweets_cityname=".$_GET['tweets_cityname']; } ?>"><button name="TweetsCityHistory"class="filterButton">History</button></a>
                </div>
                <script>
				$("input#tweets_cityname").on({
				  keydown: function(e) {
					if (e.which === 32)
					  return false;
				  },
				  change: function() {
					this.value = this.value.replace(/\s/g, "");
				  }
				});
				</script>
            <? } ?>
        <? } ?>
        
        
        
        
        
        <?php if(!empty($_GET['TweetsCityHistory']) && $_GET['TweetsCityHistory'] == "History") { ?>
            <div class="SearchHistoryQuery">
            <ul>
                 <li><strong><a href="<?php echo base_url(); ?><?php if(!empty($_GET['tweets_cityname'])) { echo "?tweets_cityname=".$_GET['tweets_cityname']; } ?>" title="BACK TO THE TWEETS">< BACK TO THE TWEETS</a></strong></li>
                <?php 
                    $QueryData = $this->db->query("SELECT * FROM history ORDER BY history_latest DESC");
                    foreach($QueryData->result() as $ContentHistory) {
                ?>
                    <li><a href="<?php echo base_url(); ?>?tweets_cityname=<?php echo $ContentHistory->history_name; ?>" title="<?php echo $ContentHistory->history_name; ?>"><strong><?php echo ucwords($ContentHistory->history_name); ?></strong></a> - <span style="color:#AAA;"><strong>Views:</strong> <?php echo $ContentHistory->history_freq; ?> times (Latest: <?php echo date("M d, Y - H:i:s",$ContentHistory->history_latest); ?>)</span></li>
                <? } ?>
            </ul>
            </div>
        <? } ?>
</div>
</body>
</html>



<?php 
#### UPDATE DATA #### 
if(!empty($_GET['tweets_cityname'])) {
	$DataCity = strtolower(htmlspecialchars($_GET['tweets_cityname']));
	$CheckData = $this->db->query("SELECT * FROM history WHERE history_name = '".$DataCity."' LIMIT 1");
	foreach($CheckData->result() as $DataCityArr) {
		$freq = $DataCityArr->history_freq + 1;
	}
	
	if($CheckData->num_rows() > 0) {
		$DataCity = strtolower(htmlspecialchars($_GET['tweets_cityname']));
		$data = array(
				   'history_latest' => time(),
				   'history_freq' 	=>	$freq,
				);
	
		$this->db->where('history_name', $DataCity);
		$this->db->update('history', $data); 
	} else {
		$AddNewCityName = array(
		   'history_name' 	=> $DataCity,
		   'history_freq' 	=> '1' ,
		   'history_latest' => time()
		);
		
		$this->db->insert('history', $AddNewCityName); 
	}
}
?>