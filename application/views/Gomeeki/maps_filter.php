<?php if(empty($_GET['TweetsCitySearch']) || (!empty($_GET['TweetsCitySearch']) && $_GET['TweetsCitySearch'] == "Search")) { ?>
			<?php if(empty($_GET['TweetsCityHistory'])) { ?>
                <div class="filterTweets">
                
                <form name="tw" method="get"><input name="tweets_cityname" id="tweets_cityname" type="text" class="filterTweetsInput" placeholder="Fill City Name..." <?php if(!empty($tweetsCity)) { ?>value="<? echo $tweetsCity; ?>"<? } ?>> 
                <input name="TweetsCitySearch" type="submit" class="filterButton" value="Search">
                </form><a href="<?php echo base_url(); ?>?TweetsCityHistory=History<?php if(!empty($_GET['tweets_cityname'])) { echo "&tweets_cityname=".$_GET['tweets_cityname']; } ?>"><button name="TweetsCityHistory"class="filterButton">History</button></a>
                </div>
                <script>
				/**** DON'T ALLOWED SPACEBAR *****/
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