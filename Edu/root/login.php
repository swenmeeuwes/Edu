<?php
    define("MAX_LENGTH", 6);

    $ini_array = parse_ini_file("Webservice/Config/config.ini", true);

	// Create connection
	$database = new mysqli($ini_array['Database']['host'], $ini_array['Database']['username'], $ini_array['Database']['password'], $ini_array['Database']['database']);

	// Check connection
	if ($database->connect_error) {
	    die("OOPS!: " . $database->connect_error);
	} 

    $stmt = $database->prepare("SELECT username, password, salt FROM User WHERE username = ?");
	$stmt->bind_param("s", $username);

    $username = $_POST["username"];
    
	$stmt->execute();

    $stmt->bind_result($username, $password, $salt);

	$stmt->fetch();

	$stmt->close();

    // Use hash_equals (but outdated php :c)
    echo $salt . "   " . hash("sha256", $_POST["password"] . $salt) . "  ==  " . $password;
    if(hash("sha256", $_POST["password"] . $salt) == $password) {
        session_start();
        $_SESSION["username"] = $username;
        //if($role == "student")
            header('Location: app.html');
        //else if ($role == "parent")
        //    header('Location: dashboard.html');
        //else
        //    header('Location: login.html?error=Your role is not yet supported, sorry :c');
    } else {
        header('Location: login.html?error=Could not login: Wrong password');;
    }
?>