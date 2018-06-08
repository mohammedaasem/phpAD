<?php
	require 'dbconnect.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
	$id = $_POST["id"];
	
	
	$sqlupdate="select otp from user where id='$id'";
	$result = mysqli_query($db,$sqlupdate);
	if(mysqli_num_rows($result)>0){
		$data = mysqli_fetch_array($result);
		$response['otp'] = $data['otp'];
		$response['success'] = "200";
	}
	else{
		$response['success'] = "201";
	}
	die(print_r(json_encode($response),true));
}




?>