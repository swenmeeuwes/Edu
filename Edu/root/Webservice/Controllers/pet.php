<?php
include '../database/database_connect.php';

session_start();
$_SESSION["username"] = "Swen";

header("Content-Type:application/json");
if(!empty($_SESSION["username"])) {
	$result = select($database, $_SESSION["username"]);

	if(!empty($result)) {
		respond(200, "Pet found", $result);
	} else {
		respond(200, "Pet not found", NULL);
	}
} else if(!empty($_SESSION["username"]) && !empty($_POST['name']) && !empty($_POST['happiness']) && !empty($_POST['growth'])) {
	if(insert($database, $_SESSION["username"], $_POST['name'], $_POST['happiness'], $_POST['growth']))
        respond(200, "Pet inserted", NULL);
    else
        respond(200, "Could not insert pet", NULL);
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

function insert($database, $username, $name, $happiness, $growth) {
	// To-Do: Check for existence
	$stmt = $database->prepare("INSERT INTO Pet (username, name, happiness, growth) VALUES (?,?,?,?)");
	$stmt->bind_param("ssii", $username, $name, $happiness, $growth);

	$stmt->execute();

	$stmt->fetch();

	$stmt->close();

	return true;
}

function respond($status, $status_message, $data) {
	header("HTTP/1.1 $status $status_message");

	$response['status'] = $status;
	$response['status_message'] = $status_message;
	$response['data'] = $data;

	$json_response = json_encode($response);
	echo $json_response;
}

disconnect($database);
?>