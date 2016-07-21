<?php
include '../database/database_connect.php';

session_start();
$_SESSION["username"] = "Swen";

header("Content-Type:application/json");
if(!empty($_SESSION["username"]) && empty($_POST['timestamp'])) {
	$result = select($database, $_SESSION["username"]);

	if(!empty($result)) {
		respond(200, "Record found", $result);
	} else {
		respond(200, "Record not found", NULL);
	}
} else if(!empty($_POST['timestamp']) && !empty($_SESSION["username"]) && !empty($_POST['minigameName']) && !empty($_POST['score']) && !empty($_POST['difficulty'])) {
	if(insert($database,  $_POST['timestamp'], $_SESSION["username"], $_POST['minigameName'], $_POST['score'], $_POST['difficulty']))
        respond(200, "Record inserted", NULL);
    else
        respond(200, "Could not insert record", NULL);
} else {
    respond(400, "Invalid request", NULL);
}

function select($database, $username) {
	$stmt = $database->prepare("SELECT timestamp, minigameName, score, difficulty FROM Record WHERE username=?");
	$stmt->bind_param("s", $username);

	$stmt->execute();

	$stmt->bind_result($timestamp, $minigameName, $score, $difficulty);

    $records = array();
	while($stmt->fetch()) {
        array_push($records, array("timestamp"=>$timestamp, "minigameName"=>$minigameName, "score"=>$score, "difficulty"=>$difficulty));
    }

	$stmt->close();

	return $records;
}

function insert($database, $timestamp, $username, $minigameName, $score, $difficulty) {
	// To-Do: Check for existence
	$stmt = $database->prepare("INSERT INTO Record (timestamp, username, minigameName, score, difficulty) VALUES (?,?,?,?,?)");
	$stmt->bind_param("issii", $timestamp, $username, $minigameName, $score, $difficulty);

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