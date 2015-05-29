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