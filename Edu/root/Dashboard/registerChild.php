<?php
    session_start();

    if(!isset($_SESSION["username"])) {
        echo "Please login first.";
        header('Location: ../login.html');
        die();
    }

    define("MAX_LENGTH", 16);

    if(empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["firstname"]) || empty($_POST["dateOfBirth"])) {
        header('Location: register.php?error=Vul alstublieft alle velden in.');
        return false;
    }

    $ini_array = parse_ini_file("../Webservice/Config/config.ini", true);

	// Create connection
	$database = new mysqli($ini_array['Database']['host'], $ini_array['Database']['username'], $ini_array['Database']['password'], $ini_array['Database']['database']);

	// Check connection
	if ($database->connect_error) {
	    die("OOPS!: " . $database->connect_error);
	} 

    $hash = generateHashWithSalt($_POST['password']);

    $stmt = $database->prepare("INSERT INTO Student (parentUsername, studentUsername, firstname, lastname, dateOfBirth, password, salt) VALUES (?,?,?,?,?,?,?)");
	$stmt->bind_param("sssssss", $parentUsername, $studentUsername, $firstname, $lastname, $dateOfBirth, $password, $salt);

    $parentUsername = $_SESSION["username"];
    $studentUsername = $_POST["username"];
    $firstname = $_POST["firstname"];
    $lastname = "";
    $dateOfBirth = $_POST["dateOfBirth"];
    $password = $hash["Hash"];
    $salt = $hash["Salt"];

	$stmt->execute();

	$stmt->fetch();

	$stmt->close();

    header('Location: index.php');

    function generateHashWithSalt($password) {
        $intermediateSalt = md5(uniqid(rand(), true));
        $salt = substr($intermediateSalt, 0, MAX_LENGTH);
        return array("Hash"=>hash("sha256", $password . $salt), "Salt"=>$salt);
    }
?>