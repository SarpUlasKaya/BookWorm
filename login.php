<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("config.php");

session_start();
$email = $_POST['email'];
$password = $_POST['password'];

$query = " select * from users where mail_address='$email' and password='$password'";

if($result = $mysqli->query($query)) {
    if($result->num_rows==1) {
        header("location: listbooks.php");
    }
    else{
        echo "<script>
        alert('Wrong username or password');
        window.location.href='index.html';
        </script>";
    }
}
else {
    echo "<script>
    alert('DB Query failed');
    window.location.href='index.html';
    </script>";
}
?>
