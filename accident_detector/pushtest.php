<?php

		$android_device_token = 'dH7mWzGdaR4:APA91bEtlw7tMbVsovlEw-lLyXU7IaOeOyxGSe4SDA5nzuIVHVWU2VKilrYsY8oKxoYU5SmyCt6AJAJJLBfZtGpXFqL9oHwWuJxSyU-jYD0drmTNQSGKTPy3h_wIc4s6RVzzDEzfY2OS';
 
		$res = array();
        $res['data']['title'] = "my title";
        $res['data']['message'] = "How are you?";
		 
		$result = send_android_notification($android_device_token, $res);
		 
		//dump result
		 
		var_dump($result);
		
		function send_android_notification($registration_ids, $res) {
		$fields = array(
		'to' => array($registration_ids),
		'data'=> $res,
		);
		$headers = array(
		'Authorization: key=AAAAA2ytf-4:APA91bEcYJAc3Jj5qVHusI3u0ryqwXrH8t365rmfM6cwGC9Gshjpu_8stByvSYs22rvRE4YKH9n5cJkmQMNLzIHBIX6r_yf_xny_bcbkgnc536PilnmI8aAkRusGrntq8Cq-KYFFhWgW', // FIREBASE_API_KEY_FOR_ANDROID_NOTIFICATION
		'Content-Type: application/json'
		);
		// Open connection
		$ch = curl_init();
		 
		// Set the url, number of POST vars, POST data
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		 
		// Disabling SSL Certificate support temporarly
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		 
		echo ($fields);
		// Execute post
		$result = curl_exec($ch );
		if($result === false){
		die('Curl failed:' .curl_errno($ch));
		}
		 
		// Close connection
		curl_close( $ch );
		echo $result;
		}




?>