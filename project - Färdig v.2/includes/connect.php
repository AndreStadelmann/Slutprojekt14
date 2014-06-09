<?php

	$host = "localhost";
	$dbname = "schoolproject";
	$username = "SchoolProject";
	$password = "1234";

	$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
	$attr = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);

	$pdo = new PDO($dsn, $username, $password, $attr);

?>