<?php
$database = connect();


function connect() {
	$ini_array = parse_ini_file("../Config/config.ini", true);

	// Create connection
	$connection = new mysqli($ini_array['Database']['host'], $ini_array['Database']['username'], $ini_array['Database']['password'], $ini_array['Database']['database']);

	// Check connection
	if ($connection->connect_error) {
	    die("OOPS!: " . $connection->connect_error);
	} 
	//echo "Connected successfully";
	return $connection;
}

function disconnect($database) {
	$database->close();
}
?>