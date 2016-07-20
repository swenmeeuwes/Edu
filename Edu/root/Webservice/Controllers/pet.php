<?php
include '../database/database_connect.php';

header("Content-Type:application/json");
if(!empty($_GET['username'])) {
	$result = select($database, $_GET['username']);

	if(!empty($result)) {
		respond(200, "Pet found", $result);
	} else {
		respond(200, "Pet not found", NULL);
	}
} else {
	respond(400, "Invalid request", NULL);
}

function select($database, $username) {
	// prepare and bind
	$stmt = $database->prepare("SELECT name, happiness, growth FROM Pet WHERE username=?");
	$stmt->bind_param("s", $username);

	$stmt->execute();

	$stmt->bind_result($name, $happiness, $growth);

	$stmt->fetch();

	$stmt->close();

	return array("name"=>$name, "happiness"=>$happiness, "growth"=>$growth);
}

function respond($status, $status_message, $data) {
	header("HTTP/1.1 $status $status_message");

	$response['status'] = $status;
	$response['status_message'] = $status_message;
	$response['data'] = $data;

	$json_response = json_encode($response);
	echo $json_response;
}

?>