<?php
	define( 'API_ACCESS_KEY', 'AAAANxGJ3H0:APA91bHXG9dQf3mtTEH0_rRDC20wc844zburykEte5FrmJRbkUbCey_Mz_RE81XLqdm5Y9dvrdZj2vhj_5Wg1KehVOdbJTBxwvD6C1Suf2GogxngfLTbX0LGQFZK32_pzvbM_Dt7A1fR');

	date_default_timezone_set('Asia/Kolkata');
	require 'dbconnect.php';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		//$speed = $_REQUEST['speed'];
		$mobile = $_REQUEST['mobile'];
		$relmob = $_REQUEST['relmob'];
		$token = $_REQUEST['token'];
		$lat1 = $_REQUEST['lat'];
		$lon1 = $_REQUEST['lon'];
		$vehicle = $_REQUEST['vehicle'];
		$accstatus = $_REQUEST['accflag'];
		$image = $_REQUEST['image'];
		$cdate = Date("d-m-Y");
		
		
		$chq = "select *,SQRT(POW(69.1 * (lat - $lat1), 2) + POW(69.1 * ($lon1 - lon) * COS(lat / 57.3), 2)) AS distance FROM notification where accident='1' and vehicle='$vehicle' and incident_date='$cdate' HAVING distance < 0.100";
		$res = mysqli_query($db,$chq);
		if(mysqli_num_rows($res)>0){
			$data = mysqli_fetch_array($res);
			$lastlat = $data['lat'];
			$lastlon = $data['lon'];
			
			$stq = "update notification set policeaction='1' where vehicle='$vehicle'";
			$rest = mysqli_query($db,$stq);
			echo '200';
			
		}else{
			
			$path = "uploads/$vehicle"."$cdate".".png";
			$imagename="$vehicle"."$cdate".".png";
			file_put_contents($path,base64_decode($image));
			
			
			$di = 100;
			$ch = 0;
			while($ch==0){
				$st = "SELECT name,area,lat,lon,SQRT(POW(69.1 * (lat - $lat1), 2) + POW(69.1 * ($lon1 - lon) * COS(lat / 57.3), 2)) AS distance FROM police HAVING distance < '$di' ORDER BY distance";
				$rest1 = mysqli_query($db,$st);
				$hospsearch = "SELECT name,area,lat,lon,SQRT(POW(69.1 * (lat - $lat1), 2) + POW(69.1 * ($lon1 - lon) * COS(lat / 57.3), 2)) AS distance FROM hospital HAVING distance < '$di' ORDER BY distance";
				$rest2 = mysqli_query($db,$hospsearch);
				if(mysqli_num_rows($rest1)<1){
					$ch = 0;
					$police = "Not found";
					echo '203';
				}else{
					$ch = 1;
					$data = mysqli_fetch_assoc($rest1);
					$police = $data['name'];
					$parea = $data['area'];
				}
				if(mysqli_num_rows($rest2)<1){
					$ch = 0;
					$hosp = "Not found";
					echo '203';
				}else{
					$ch = 1;
					$data2 = mysqli_fetch_assoc($rest2);
					$hosp = $data2['name'];
					$hosparea = $data2['area'];
				}
			}
			$ctime = Date('H:i:s');
			$cdate = Date("d-m-Y");
			$stq1 = "INSERT INTO `notification`(`vehicle`, `traffic`, `accident`, `lat`, `lon`, `incident_time`, `incident_date`, `policeaction`, `policename`, `hospname`, `imagename`) VALUES ('$vehicle','0','1','$lat1','$lon1','$ctime','$cdate','0','$police','$hosp','$imagename')";
			if(mysqli_query($db,$stq1)){
				$s = "SELECT *, SQRT(POW(69.1 * (lat - $lat1), 2) + POW(69.1 * ($lon1 - lon) * COS(lat / 57.3), 2)) AS distance FROM realtime HAVING distance < 1 ORDER BY distance";
				$re = mysqli_query($db,$s);
				if(mysqli_num_rows($re)){
					while($data = mysqli_fetch_array($re)){
							$token = $data['token'];
						
							$res1['data']['title'] = "Accident Notification";
							$res1['data']['message'] = "Accident located near by your location!";
							$res1['data']['lat'] = $lat1;
							$res1['data']['lon'] = $lon1;
							$res1['data']['accflag'] = "1";
														
							$fields = array(
								'to' => $token,
								'data' => $res1
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
							echo $token;
						
						
					}
				}
			}
			
			$phonenumber = $relmob;
			//$msgC = "Accident is detected for vehicle no ".$vehicle." at latitude : ".$lat1." and longitude : ".$lon1;
			$msgC = "Accident is detected for vehicle no  ".$vehicle." at http://www.google.com/maps/place/".$lat1.",".$lon1;
			
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL,"http://www.smswave.in/panel/sendsms.php?");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,
						"PhoneNumber=".$phonenumber."&Text=".$msgC."&user=vinodotp&password=123123&sender=CAPTCH");

			// Get server response ...
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$result = curl_exec ($ch);

			curl_close ($ch);
			
			echo '200';
		}
			
		
	}




?>