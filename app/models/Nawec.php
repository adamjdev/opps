<?php

class Nawec {
	public function XML2Array ( $xml , $recursive = false )
	{
    if ( ! $recursive )
    {
        $array = simplexml_load_string ($xml);
    }
    else
    {
        $array = $xml ;
    }

    $newArray = array () ;
    $array = ( array ) $array ;
    foreach ( $array as $key => $value )
    {
        $value = ( array ) $value ;
        if ( isset ( $value [ 0 ] ) )
        {
            $newArray [ $key ] = trim ( $value [ 0 ] ) ;
        }
        else
        {
            $newArray [ $key ] = XML2Array ( $value , true ) ;
        }
    }
    return $newArray ;
	}
	
	public function vend($meter ,$amount, $unit_id){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://10.220.0.23/cgi-bin/thin-client");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/xml")); 
		curl_setopt($ch,CURLOPT_USERAGENT,'SuprimaTerminal/1.0');
		curl_setopt($ch, CURLOPT_TIMEOUT, 20); # times out after 20s
		$data = "<?xml version=\"1.0\" ?>
		<suprima>
		<thin-client>
		<vend type=\"request\" format-by=\"client\">
		<unit-id>$unit_id</unit-id>
		<user-name>QCELL</user-name>
		<password>QPower2</password>
		<meter>
		<meter-number>$meter</meter-number>
		</meter>
		<amount>$amount</amount>
		<tendered>$amount</tendered>
		<payment method=\"cash\" />
		</vend>
		</thin-client>
		</suprima>
		";

		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		$curl_errno = curl_errno($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		$result = $this->XML2Array($output);
		if ($curl_errno > 0) {
		$return='qcell';
                return $return;
        } else {
		$success=$result['thin-client']['vend']['success'];
		if($success==1){
		$token = $result['thin-client']['vend']['token']['tk60'];
		$units = $result['thin-client']['vend']['token']['tk50'];  
		$receipt = $result['thin-client']['vend']['token']['tk10'];
		return array($success,$token,$units,$receipt);

		}else{
		$error = $result['thin-client']['vend']['error'];
		return array($success,$error);
		}
		}
	}
	
	public function pre_vend($meter ,$amount, $unit_id){

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://10.220.0.23/cgi-bin/thin-client");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/xml")); 
		curl_setopt($ch,CURLOPT_USERAGENT,'SuprimaTerminal/1.0');
		curl_setopt($ch, CURLOPT_TIMEOUT, 20); # times out after 20s

		$data = "<?xml version=\"1.0\" ?>
		<suprima>
		<thin-client>
		<consumer-chk type=\"request\" format-by=\"server\">
		<unit-id>$unit_id</unit-id>
		<user-name>QCELL</user-name>
		<password>QPower2</password>
		<meter>
		<meter-number>$meter</meter-number>
		</meter>
		<amount>$amount</amount>
		<tendered>$amount</tendered>
		<payment method=\"cash \" />
		</consumer-chk>
		</thin-client>
		</suprima>
		";

		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		$curl_errno = curl_errno($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		$result = $this->XML2Array($output);
		if ($curl_errno > 0) {
                return 3;
        } else {

		$success=$result['thin-client']['vend']['success'];
		print "success: $success";
		if (!$success){$success=$result['thin-client']['consumer-chk']['success'];}
			if($success==1){
			$first=$result['thin-client']['consumer-chk']['customer-first-name'];
			$last=$result['thin-client']['consumer-chk']['customer-name'];
			 return array ($success,$first,$last);
		 }else{ $error=$result['thin-client']['vend']['error'];
		return array ($success,$error);
		}	


		}
	}
	
}
