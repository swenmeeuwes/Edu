<?php
    define("MAX_LENGTH", 16);
    $role = 0;

    $ini_array = parse_ini_file("Webservice/Config/config.ini", true);

	// Create connection
	$database = new mysqli($ini_array['Database']['host'], $ini_array['Database']['username'], $ini_array['Database']['password'], $ini_array['Database']['database']);

	// Check connection
	if ($database->connect_error) {
	    die("OOPS!: " . $database->connect_error);
	} 

    $stmt = $database->prepare("SELECT password, salt FROM User WHERE username = ?");
	$stmt->bind_param("s", $username);

    $username = $_POST["username"];
    
	$stmt->execute();

    $stmt->bind_result($password, $salt);

	$found = $stmt->fetch();

    $stmt->store_result();

	$stmt->close();


    if(empty($found)) {
        $role = 1;    

        $stmt = $database->prepare("SELECT password, salt FROM Student WHERE studentUsername = ?");
	    $stmt->bind_param("s", $username);

        $username = $_POST["username"];
    
	    $stmt->execute();

        $stmt->bind_result($password, $salt);

	    $found = $stmt->fetch();

        $stmt->store_result();

	    $stmt->close();

        if(empty($found)) {
            header("Location: login.html?error=Fout tijdens het inloggen: Gebruiker bestaat niet");
            die();
        }
    }

    // Use hash_equals (but outdated php :c)
    //echo $salt . "   " . hash("sha256", $_POST["password"] . $salt) . "  ==  " . $password;
    if(hash("sha256", $_POST["password"] . $salt) == $password) {
        session_start();
        $_SESSION["username"] = $username;

        //echo "Succes! " . $role;
        //if($role == 0)
        //    header('Location: dashboard');
        //else if ($role == 1)
        //    header('Location: app.html');
        //else
        //    header('Location: login.html?error=Your role is not yet supported, sorry :c');

        switch ($role) {
            case 0:
                header('Location: dashboard');
                break;
            case 1:
                header('Location: app.html');
                break;
            default:
                header('Location: login.html?error=Your role is not yet supported, sorry :c');
                break;
        }
    } else {
        header('Location: login.html?error=Fout tijdens het inloggen: Verkeerde wachtwoord');
    }
?>