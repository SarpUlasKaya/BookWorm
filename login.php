<?php
include("config.php");
session_start();
function logIn($mysqli) {
	$username = $_POST['name'];
	$password = $_POST['password'];
	//$_SESSION['user_id'] = $id;
	$query = " select * from users where name = '$username' and password = '$password'";
	if ($result = $mysqli->query($query)) {
		if ($result->num_rows==1) {
			$_SESSION['login_user'] = $username;
			header("location: welcome.php");
		}
		else {
			header("location: index.html");
		}
	}
	else {
		header( "location: index.html");
	}
}
if (isset($_POST['name']) && isset($_POST['password']) ) {
	logIn($mysqli);
}