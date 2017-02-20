<?php 
Class Probability{
    
    function get_rand($proArr) {
        if(!is_array($proArr)){
            return false;
        }
        $result = '';
    
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
    
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
    
        return $result;
    } 
    
    
    function createRand($type,$minRange,$maxRange,$count,$fArr){
    	$minRange = intval($minRange);
    	$maxRange = intval($maxRange);
    	$arr = array();
    	while(count($arr)<$count){
    		$rand = mt_rand($minRange,$maxRange);
    		if(!in_array("{$type}".$rand,$arr) && !in_array("{$type}".$rand,$fArr)){
    			$arr[] = "{$type}".$rand;
    		}
    	}  	
    	return $arr;
    }
    
    public static function ajaxmsg($param,$status){
    	if(is_string($param)){
    		$data['message'] = $param;
    		$data['status'] = $status;  		
    	}else if(is_array($param)){
  			$data = $param;
    	}
    	echo json_encode($data);
    	exit;
    }
    
    public static function get_client_ip() {
    	$ip = $_SERVER['REMOTE_ADDR'];
    	if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
    		$ip = $_SERVER['HTTP_CLIENT_IP'];
    	} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
    		foreach ($matches[0] AS $xip) {
    			if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
    				$ip = $xip;
    				break;
    			}
    		}
    	}
    	return $ip;
    }
    
}

?>

