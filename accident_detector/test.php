<?php

		date_default_timezone_set('Asia/Kolkata');
	define( 'API_ACCESS_KEY', 'AAAANxGJ3H0:APA91bHXG9dQf3mtTEH0_rRDC20wc844zburykEte5FrmJRbkUbCey_Mz_RE81XLqdm5Y9dvrdZj2vhj_5Wg1KehVOdbJTBxwvD6C1Suf2GogxngfLTbX0LGQFZK32_pzvbM_Dt7A1fR');
	require 'dbconnect.php';
	
		$speed = '1';
		$mobile = '7776016455';
		$token = 'fd__FMpkEBc:APA91bGW6o6vQFF6BWmOoIMsSGuCzNNjXoIhKvrIqDD9df93PtKMTY1yWw9w8GtAaHdgTCkWk9N2prf534Ufeekop73ZSqBS1_jxJJzRR2jUOHsY_Mk9e6cGK1j1mTDx19zXXhIC7M_A';
		$lat1 = '18.647937';
		$lon1 = '73.7634417';
		$vehicle = 'Mh14ga9800';
		//$notice = $_REQUEST['notice'];
		
		
		
		$chq = "select * from realtime where vehicle='$vehicle'";
		$res = mysqli_query($db,$chq);
		if(mysqli_num_rows($res)>0){
			echo '<br>1';
			$data = mysqli_fetch_array($res);
			$lastspeed = $data['speed'];
			$lastlat = $data['lat'];
			$lastlon = $data['lon'];
			
			if($speed < 20 && $lastspeed < 20){
				echo '<br>2';
				$ctime = Date('H:i:s');
				$cdate = Date("d-m-Y");
				$s = "update realtime set speed='$speed',realtime='$ctime',realdate='$cdate' where vehicle='$vehicle'";
				mysqli_query($db,$s);
				
				$sql1 = "SELECT *, SQRT(POW(69.1 * (lat - $lat1), 2) + POW(69.1 * ($lon1 - lon) * COS(lat / 57.3), 2)) AS distance FROM realtime HAVING distance < 0.150 ORDER BY distance";
				$res1 = mysqli_query($db,$sql1);
				if(mysqli_num_rows($res1)>0){					//Checking vehicle traffic threshold value
					echo '<br>3';
					while($rtr = mysqli_fetch_array($res1)){
						echo '<br>4';
						$counts = 1;
						if($rtr['speed']<20)
							$counts++;
					}
					if($counts>1){
						echo '<br>5';
						$sql2 = "SELECT *, SQRT(POW(69.1 * (lat - $lat1), 2) + POW(69.1 * ($lon1 - lon) * COS(lat / 57.3), 2)) AS distance FROM realtime_notification HAVING distance < 0.150 ORDER BY distance";
						$res2 = mysqli_query($db,$sql2);
						if(mysqli_num_rows($res2)>0){
							$data2 = mysqli_fetch_array($res2);
							$id = $data2['id'];
							$traffic = $data2['traffic'];
							$traffic  = $traffic + 1;
							$ctime = Date('H:i:s');
							$cdate = Date("d-m-Y");
							$sql3 = "update realtime_notification set traffic='$traffic', incident_date='$cdate' where id='$id'";
							if(mysqli_query($db,$sql3)){
								$response['success']="200";
								echo '<br>getting here!';
							}
						}else{
							$ctime = Date('H:i:s');
							$cdate = Date("d-m-Y");
							echo'<br>Getting in!';
							$sql4 = "INSERT INTO `notification`(`vehicle`, `traffic`, `accident`, `lat`, `lon`, `incident_time`, `incident_date`) VALUES ('$vehicle',1,0,'$lat1','$lon1','$ctime','$cdate')";
							if(mysqli_query($db,$sql4)){
								$sql5 = "INSERT INTO `realtime_notification`(`vehicle`, `traffic`, `accident`, `lat`, `lon`, `incident_time`, `incident_date`) VALUES ('$vehicle',1,0,'$lat1','$lon1','$ctime','$cdate')";
								if(mysqli_query($db,$sql5)){
										//array received from users received notifications
										$stq = "SELECT *, SQRT(POW(69.1 * (lat - $lat1), 2) + POW(69.1 * ($lon1 - lon) * COS(lat / 57.3), 2)) AS distance FROM realtime_notification HAVING distance < 2 and traffic > 0 ORDER BY distance";
										$ts = mysqli_query($db,$stq);
										if(mysqli_num_rows($ts)>0){
											while($row = $ts->fetch_assoc()){
												$token = $row['token'];
														
														$res['data']['title'] = "Traffic Notification";
														$res['data']['message'] = "Traffic located near by your location!";
														
														$fields = array(
															 'to' => $token,
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
														echo '<br>Result == '.$result;
											}
										}
									
								}else{
									$response['success'] = "201";
									$response['message'] = "Error in updating database!";
								}
							}else{
								$response['success'] = "201";
								$response['message'] = "Error in updating database!";
							}
						}
					}else{
						$response['success'] = "200";
						$response['message'] = "No traffic";
					}
				}
			}
		}else{
			$ctime = Date('H:i:s');
			$cdate = Date("d-m-Y");
			$sql = "INSERT INTO `realtime`(`vehicle`, `token`, `mobile`, `lat`, `lon`, `speed`, `realtime`, `realdate`, `trafficwarn`, `accidentwarn`, `accidentst`) VALUES ('$vehicle','$token','$mobile','$lat1','$lon1','$speed','$ctime','$cdate',0,0,0)";
			if(mysqli_query($db,$sql)){
				$response['success'] = "200";
				$response['message'] = "Information updated successfully";
			}else{
				$response['success'] = "201";
				$response['message'] = "Error in updating database!";
			}
			$stq = "SELECT fcmid,id,lat,lon, SQRT(POW(69.1 * (lat - $lat1), 2) + POW(69.1 * ($lon1 - lon) * COS(lat / 57.3), 2)) AS distance FROM realtime_notification HAVING distance < 2 and traffic > 0 ORDER BY distance";
			$ts = mysqli_query($db,$stq);
			if(mysqli_num_rows($ts)>0){
				while($row = $ts->fetch_assoc()) {
					$token = $row['token'];
														
														$res['data']['title'] = "Traffic Notification";
														$res['data']['message'] = "Traffic located near by your location!";
														
														$fields = array(
															 'to' => $token,
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
														echo '<br>Result == '.$result;
				}
			}
		}
		die(print_r(json_encode($response),true));
	



?>