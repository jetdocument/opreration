<?php
$return = array( 
			"status"=>"Ok!",
			"data"=> array(				
				"error"=>"",
				"system"=>""));
$json = json_encode($return);
echo $return['data']['error'];