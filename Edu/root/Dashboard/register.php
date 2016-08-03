<!--NEEDS VERIFICATION-->
<?php
    session_start();

    if(!isset($_SESSION["username"])) {
        echo "Please login first.";
        header('Location: ../login.html');
        die();
    }

    $children = array();

    $ini_array = parse_ini_file("../Webservice/Config/config.ini", true);

	// Create connection
	$database = new mysqli($ini_array['Database']['host'], $ini_array['Database']['username'], $ini_array['Database']['password'], $ini_array['Database']['database']);

	// Check connection
	if ($database->connect_error) {
	    die("OOPS!: " . $database->connect_error);
	} 

    $stmt = $database->prepare("SELECT firstname FROM Student WHERE parentUsername = ?");
	$stmt->bind_param("s", $username);

    $username = $_SESSION["username"];
    
	$stmt->execute();

    $stmt->bind_result($firstname);

	while($stmt->fetch()) {
        array_push($children, array("firstname"=>$firstname, "lastResult"=>"Nooit", "progress"=>"Op schema"));
    }

	$stmt->close();
?>

<!-- Palette: eb3b50 en f3b670 -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Edu - Dashboard</title>

    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <link href="../css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <nav class="navbar navbar-inverse" style="border-radius: 0px;">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Edu - Dashboard</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="index.php">Home</a></li>
                    <!--<li><a href="#">Page 1</a></li>
                    <li><a href="#">Page 2</a></li>
                    <li><a href="#">Page 3</a></li>-->
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#"><span class="glyphicon glyphicon-user"></span> Welkom, <?php echo $_SESSION["username"]; ?>!</a></li>
                    <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Log uit</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Registreer kind</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <div id="error_block" class="alert alert-danger" role="alert" style="visibility: hidden;">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    <span id="error"></span>
                </div>
                <form class="form-horizontal" role="form" onsubmit="return validateForm()" name="form_register" action="registerChild.php" method="post">
                    <h1>Registreer kind</h1>
                    <hr />
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Gebruikersnaam kind</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="usernameInput" name="username" type="text" value="" placeholder="gebruikersnaam" autofocus required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Wachtwoord</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="passwordInput" name="password" type="text" value="" placeholder="wachtwoord" required>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Voornaam</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="firstnameInput" name="firstname" type="text" value="" placeholder="voornaam" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Geboortedatum</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="dateOfBirthInput" name="dateOfBirth" type="date" value="" placeholder="geboortedatum" required>
                        </div>
                    </div>

                    <div>
                        <input style="float: right;" class="btn btn-default" type="submit" value="Registreer kind" />
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function validateForm() {
        var form = document.forms["form_register"];

        /*var fieldUsername = form["username"].value;
        if (fieldUsername == null || fieldUsername == "" || fieldUsername.length < 2 || fieldUsername.length > 20) {
            document.getElementById("error_block").style.visibility = 'visible';
            document.getElementById("error").innerHTML = "Uw gebruikersnaam moet tussen de 2 en 20 letters bevatten.";
            return false;
        }

        var fieldEmail = form["email"].value;
        if (fieldEmail == null || fieldEmail == "" || fieldEmail.length < 2) {
            document.getElementById("error_block").style.visibility = 'visible';
            document.getElementById("error").innerHTML = "Uw email is niet geldig";
            return false;
        }

        var fieldPassword = form["password"].value;
        if (fieldPassword == null || fieldPassword == "" || fieldPassword.length < 5 || fieldPassword.length > 30) {
            document.getElementById("error_block").style.visibility = 'visible';
            document.getElementById("error").innerHTML = "Uw wachtwoord moet tussen de 5 en 30 letters bevatten.";
            return false;
        }*/

        return true;
    }
    </script>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap/bootstrap.min.js"></script>
</body>
</html>