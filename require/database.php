<?php

function openCon() 
	{
		$server = "localhost";
		$database = "";
		$username = "";
		$password = "";

		$conn = new mysqli($server, $username, $password, $database) or die("Failed to connect: %s\n". $conn -> error);
		return $conn;
	}

function closeCon() 
	{
	$conn -> close();	
	}

?>