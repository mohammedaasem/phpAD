<?php
require('dbconnect.php');

if($_SERVER['REQUEST_METHOD']=='POST'){
		$name = $_POST['name'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		$mob = $_POST['mob'];
		$relcontact = $_POST['relmob'];
		$address = $_POST['address'];
		$city = $_POST['city'];
		$vno = $_POST['vno'];
		$dlno = $_POST['dlno'];
		$otp = $_POST['otp'];
	

		//echo '$name';
		if($name == ''||$password == ''||$email == ''||$mob == ''||$address == ''){
			echo 'please fill all values';
		}else{
			require_once('dbconnect.php');
			$sql = "SELECT * FROM user WHERE mobile='$mob' OR vehicle1='$vno' OR dl_no='$dlno'";
			
			$check = mysqli_fetch_array(mysqli_query($db,$sql));
			
			if(isset($check)){
				echo 'Mobile or Vehicle_number or Driving license number is already registered!';
			}else{				
				$sql = "INSERT INTO user (name,mobile,relcontact,password,email,city,address,vehicle1,dl_no,otp) VALUES('$name','$mob','$relcontact','$password','$email','$city','$address','$vno','$dlno','$otp')";
				if(mysqli_query($db,$sql))
				{
					$msgC = "Your otp for verification is ".$otp;
					$ch = curl_init();

					curl_setopt($ch, CURLOPT_URL,"http://www.smswave.in/panel/sendsms.php");
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS,
								"PhoneNumber=".$mob."&Text=".$msgC."&user=vinodotp&password=123123&sender=CAPTCH");

					// Get server response ...
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

					$result = curl_exec ($ch);
					
					echo 'Registration done successfully.';
				}
				else{
					echo 'oops! Please try again!';
				}
			    } 
		}
}else{
echo 'error';
}
?>