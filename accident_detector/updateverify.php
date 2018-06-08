<?php

require 'dbconnect.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
	$id = $_POST['id'];
	$verify = $_POST['verify'];
	
	$sqlupdate="Update user set verify='$verify' where id='$id'";
	if(mysqli_query($db,$sqlupdate)){
		$response['success'] = "200";
	}
	else{
		$response['success'] = "201";
	}
	die(print_r(json_encode($response),true));
}



?>