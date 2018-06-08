<?php
	if($_SERVER['REQUEST_METHOD']=='GET'){
		$mobile = $_GET['mob'];
		$password = $_GET['password'];
		$table = "user";
	
	
		if($mobile == "" || $password == ""){
			echo 'Please enter values of userID and password';
		}
		
		require_once 'dbconnect.php';
		$query = "select * from ".$table." where mobile=".$mobile." and password=".$password."";
		
		$result = mysqli_query($db,$query);
		
		if(mysqli_num_rows($result)>0){
			$data = mysqli_fetch_array($result);
			$response['username'] = $data['name'];
			$response['id'] = $data['id'];
			$response['mobile'] = $data['mobile'];
			$response['relmob'] = $data['relcontact'];
			$response['email'] = $data['email'];
			$response['city'] = $data['city'];
			$response['address'] = $data['address'];
			$response['vehicle1'] = $data['vehicle1'];
			$response['dlno'] = $data['dl_no'];
			$response['verify'] = $data['verify'];
			$response['otp'] = $data['otp'];
			$response['success'] = "200";
			
			mysqli_close($db);
			die(print_r(json_encode($response),true));
		}else{
			$response['success'] = "201";
			$response['message'] = "Username / Password is wrong!";
			
			mysqli_close($db);
			die(print_r(json_encode($response),true));
			
		}

	}else{
		$response['success'] = "201";
		$response['message'] = "Server Method Error!";
		
		mysqli_close($db);
		die(print_r(json_encode($response),true));
	}

?>