<?php
if(!defined('host')) define('host', 'dijkstra.ug.bilkent.edu.tr');
if(!defined('dbname')) define('dbname', 'ulas_kaya');
if(!defined('username')) define('username', 'ulas.kaya');
if(!defined('passwd')) define('passwd', 'Dl7eAG2b');
$mysqli = new mysqli(host, username, passwd, dbname);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
