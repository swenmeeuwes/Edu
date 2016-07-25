<?php
    define("MAX_LENGTH", 16);

    if(empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["role"])) {
        header('Location: register.html?error=Please fill in all the fields.');
        return false;
    }

    $ini_array = parse_ini_file("Webservice/Config/config.ini", true);

	// Create connection
	$database = new mysqli($ini_array['Database']['host'], $ini_array['Database']['username'], $ini_array['Database']['password'], $ini_array['Database']['database']);

	// Check connection
	if ($database->connect_error) {
	    die("OOPS!: " . $database->connect_error);
	} 

    $hash = generateHashWithSalt($_POST['password']);

    $stmt = $database->prepare("INSERT INTO User (username, email, password, salt) VALUES (?,?,?,?)");
	$stmt->bind_param("ssss", $username, $email, $password, $salt);

    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $hash["Hash"];
    $salt = $hash["Salt"];

	$stmt->execute();

	$stmt->fetch();

	$stmt->close();

    header('Location: login.html');

    function generateHashWithSalt($password) {
        $intermediateSalt = md5(uniqid(rand(), true));
        $salt = substr($intermediateSalt, 0, MAX_LENGTH);
        return array("Hash"=>hash("sha256", $password . $salt), "Salt"=>$salt);
    }
?>