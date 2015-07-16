<?php
	session_start();
	$con = new mysqli("localhost", "root", "Password1", "internal_dev");
	if ($con->connect_errno) {
		printf("Connect failed: %s\n", $con->connect_error);
		exit();
	};
	
	$users = new mysqli("localhost", "root", "Password1", "web_users");
	if ($users->connect_errno) {
		printf("Connect failed: %s\n", $users->connect_error);
		exit();
	};
?>