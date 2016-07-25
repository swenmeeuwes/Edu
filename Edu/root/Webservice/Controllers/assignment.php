<?php
include '../database/database_connect.php';

session_start();

header("Content-Type:application/json");
if(!empty($_SESSION["username"]) && !empty($_POST['timestamp']) && !empty($_POST['minigameName']) && empty($_POST['question'])) {
	$result = select($database, $_POST['timestamp'], $_SESSION["username"], $_POST['minigameName']);

	if(!empty($result)) {
		respond(200, "Assignment found", $result);
	} else {
		respond(200, "Assignment not found", NULL);
	}
} else if(!empty($_POST['timestamp']) && !empty($_SESSION["username"]) && !empty($_POST['minigameName']) && !empty($_POST['question']) && (!empty($_POST['anwser']) || $_POST['anwser'] == 0) && (!empty($_POST['correctAnwser']) || $_POST['correctAnwser'] == 0)) {
	if(insert($database,  $_POST['timestamp'], $_SESSION["username"], $_POST['minigameName'], $_POST['question'], $_POST['anwser'], $_POST['correctAnwser']))
        respond(200, "Assignment inserted", NULL);
    else
        respond(200, "Could not insert assignment", NULL);
} else {
    respond(400, "Invalid request", NULL);
}

function select($database, $timestamp, $username, $minigameName) {
	$stmt = $database->prepare("SELECT question, anwser, correctAnwser FROM Assignment WHERE timestamp=? AND username=? AND minigameName=?");
	$stmt->bind_param("iss", $timestamp, $username, $minigameName);

	$stmt->execute();

	$stmt->bind_result($question, $anwser, $correctAnwser);

    $records = array();
	while($stmt->fetch()) {
        array_push($records, array("question"=>$question, "anwser"=>$anwser, "correctAnwser"=>$correctAnwser));
    }

	$stmt->close();

	return $records;
}

function insert($database, $timestamp, $username, $minigameName, $question, $anwser, $correctAnwser) {
	// To-Do: Check for existence
	$stmt = $database->prepare("INSERT INTO Assignment (timestamp, username, minigameName, question, anwser, correctAnwser) VALUES (?,?,?,?,?,?)");
	$stmt->bind_param("isssss", $timestamp, $username, $minigameName, $question, $anwser, $correctAnwser);

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