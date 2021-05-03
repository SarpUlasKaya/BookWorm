<?php
include("config.php");
session_start();
function logIn($mysqli) {
	$username = $_POST['name'];
	$id = $_POST['password'];
	$_SESSION['user_id'] = $id;
	$query = " select * from student where sname = '$username' and sid = '$id'";
	if ($result = $mysqli->query($query)) {
		if ($result->num_rows==1) {
			$_SESSION['login_user'] = $username;
			header("location: welcome.php");
		}
		else {
			header("location: index.php");
		}
	}
	else {
		header( "location: index.php");
	}
}
if (isset($_POST['name']) && isset($_POST['password']) ) {
	logIn($mysqli);
}