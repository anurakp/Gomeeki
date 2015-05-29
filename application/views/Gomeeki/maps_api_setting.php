<?
	$Distance = "50km";
	$LimitTweets = 8;
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
	
		
		foreach($string["result"]["places"] as $LatLng) {
				if($NoLoop == 1) { 
					$LatValue = $LatLng["centroid"][1];
					$LongValue = $LatLng["centroid"][0];
				}
		$NoLoop++;
		}
	}
?>