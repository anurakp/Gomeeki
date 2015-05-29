<?php
header('content-type:text/html;charset=utf-8');
date_default_timezone_set('Asia/Bangkok'); 
require_once('api/twitter/TwitterAPIExchange.php');

# CACHED COMMAND FOR CHECK
if(!empty($_GET['tweets_cityname'])) { $tweetsCity = str_ireplace("-"," ",$_GET['tweets_cityname']);} else { $tweetsCity = ""; }
$DataCachedTime = $this->db->query("SELECT * FROM cached WHERE cached_endtime >= '".time()."' && cached_address = '".$tweetsCity."' LIMIT 1");
if($DataCachedTime->num_rows() == 0) {
	if(!empty($tweetsCity)) { include "maps_api_setting.php"; }
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
<div class="header">
	<div><strong>MY ASSIGNMENT</strong></div>
    <?php 
	# CASE CACHED > 0
	if($DataCachedTime->num_rows() > 0) { 
	foreach($DataCachedTime->result() as $LatestShow) {
	?>
   		<div style="font-size:12px; margin-top:10px; margin-bottom:10px;">TWEETS ABOUT <? echo strtoupper($tweetsCity); ?> CONTENT AT : <? echo date("M d, Y - H:i:s",$LatestShow->cached_starttime); ?></div>
    <? } }?>
</div>
       	<div class="maps">
        	<?php if(!empty($tweetsCity)) { ?><div id="titleMaps">TWEETS ABOUT <? echo strtoupper($tweetsCity); ?></div><? } ?>
        	<div id="map_canvas"></div>
            <?php  # SHOW MAPS
				if(!empty($tweetsCity)) { 
				
					### CHECK CACHED IN DB
					if($DataCachedTime->num_rows > 0) {
						include "maps_cached.php"; 
					} else {
						$this->db->delete('cached', array('cached_address' => $tweetsCity)); 
						include "maps_include.php"; 
					}
				
						
				}  else { include "maps_default.php"; } 
			?>
        </div>
        <?php include "maps_filter.php"; # INCLUDE FILTER SEARCH FORM & HISTORY BUTTON ?>
</div>
</body>
</html>
<?php include "maps_history.php"; ?>