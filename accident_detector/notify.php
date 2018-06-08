<?php

	define( 'API_ACCESS_KEY', 'AAAANxGJ3H0:APA91bHXG9dQf3mtTEH0_rRDC20wc844zburykEte5FrmJRbkUbCey_Mz_RE81XLqdm5Y9dvrdZj2vhj_5Wg1KehVOdbJTBxwvD6C1Suf2GogxngfLTbX0LGQFZK32_pzvbM_Dt7A1fR');

	require 'dbconnect.php';
		
		
		
				
				$registrationIds = 'fIzw88MtpLw:APA91bHQhnxhXFuh-oObif_VeviELEkYg808EVUkuBSgl39XpJqyxKLUPRRz2G8fWnOrYaABvtjsEZDNBvNi7diQ4WLfjYGgyWHoYhWBa0DgkST_kWNvjpM9XzreHhMOBICTCYvsaCEM';
				if($registrationIds!=""){
				echo $registrationIds;
					$msg = array
					(
						'message' 	=> 'How are you?',
						'title'		=> 'General'
						//'body'	=> 'This is a subtitle. subtitle'
					);
					
					$res = array();
					$res['data']['title'] = "Title";
					$res['data']['message'] = "A emergency help request sent near 11 by you!";
					$res['data']['image'] = "http://192.168.0.104/test/image/Barcode.png";
					
					$fields = array(
						 'to' => $registrationIds,
						 'data' => $res
					   );

					
					 
					$headers = array
					(
						'Authorization: key=' . API_ACCESS_KEY,
						'Content-Type: application/json'
					);
					 
					$ch = curl_init();
					curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
					curl_setopt( $ch,CURLOPT_POST, true );
					curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
					curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
					curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
					curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
					$result = curl_exec($ch );
					curl_close( $ch );
					echo $result;
				
				}
				
		
		
	

?>