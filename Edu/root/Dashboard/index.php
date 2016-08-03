<?php
    session_start();

    if(!isset($_SESSION["username"])) {
        echo "Please login first.";
        header('Location: ../login.html');
        die();
    }

    date_default_timezone_set('Europe/Amsterdam');

    $children = array();

    $ini_array = parse_ini_file("../Webservice/Config/config.ini", true);

	// Create connection
	$database = new mysqli($ini_array['Database']['host'], $ini_array['Database']['username'], $ini_array['Database']['password'], $ini_array['Database']['database']);

	// Check connection
	if ($database->connect_error) {
	    die("OOPS!: " . $database->connect_error);
	} 

    $stmt = $database->prepare("SELECT studentUsername, firstname FROM Student WHERE parentUsername = ? ORDER BY firstname");
	$stmt->bind_param("s", $username);

    $username = $_SESSION["username"];
    
	$stmt->execute();

    $stmt->bind_result($studentUsername, $firstname);

	while($stmt->fetch()) {
        array_push($children, array("username"=> $studentUsername, "firstname"=>$firstname, "lastResult"=>"Nooit", "progress"=>"Niet bekend"));
    }

	$stmt->close();

    foreach($children as $key=>$child) {
        $stmt = $database->prepare("SELECT MAX(timestamp) FROM Record WHERE username = ?");
	    $stmt->bind_param("s", $username);

        $username = $child["username"];
    
	    $stmt->execute();

        $stmt->bind_result($timestamp);

        $stmt->fetch();

        if($timestamp != null)
            $children[$key]["lastResult"] = date('d-m-Y H:i:s', $timestamp);

	    $stmt->close();
    }
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
                <a class="navbar-brand" href="#">Edu - Dashboard</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Home</a></li>
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
                <li class="breadcrumb-item active">Home</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="table-responsive">
                    <h4>Kinderen</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Naam</th>
                                <th>Laatste resultaat</th>
                                <th>Niveau</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach($children as $row) {
                                    echo "<tr>";
                                    echo "<td>" . $row["firstname"] . "</td>";
                                    echo "<td>" . $row["lastResult"] . "</td>";
                                    echo "<td>" . $row["progress"] . "</td>";
                                    echo "<td><a href=\"details.php?name=" . $row["firstname"] . "\">Bekijk details &raquo;</a></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="pull-right">
                    <a href="register.php">Kind toevoegen +</a>
                </div>
            </div>
        </div>
    </div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap/bootstrap.min.js"></script>
</body>
</html>