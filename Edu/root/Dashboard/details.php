<!--NEEDS VERIFICATION-->
<?php
    session_start();
    if(!isset($_SESSION["username"]) && !empty($_GET["name"])) {
        echo "Please login first.";
        header('Location: index.php');
        die();
    }
    date_default_timezone_set('Europe/Amsterdam');
    $records = array();

    $ini_array = parse_ini_file("../Webservice/Config/config.ini", true);
	// Create connection
	$database = new mysqli($ini_array['Database']['host'], $ini_array['Database']['username'], $ini_array['Database']['password'], $ini_array['Database']['database']);
	// Check connection
	if ($database->connect_error) {
	    die("Could not connect to database :c");
	}
    $stmt = $database->prepare("SELECT studentUsername, firstname, lastname, dateOfBirth, minigameName, beginTimestamp, timestamp, score FROM Student, Record WHERE studentUsername = username AND parentUsername = ? AND firstname = ? ORDER BY timestamp DESC LIMIT 10");
	$stmt->bind_param("ss", $parentUsername, $firstname);
    
    $parentUsername = $_SESSION["username"];
    $firstname = $_GET["name"];

    // CALC AGE
    // $age = 8;

	$stmt->execute();

    $stmt->bind_result($studentUsername, $firstname, $lastname, $dateOfBirth, $minigameName, $beginTimestamp, $timestamp, $score);

    $totalTime = 0;

    while($stmt->fetch()) {
        $totalTime += $timestamp - $beginTimestamp;
        array_push($records, array("minigameName"=>ucwords($minigameName), "beginTimestamp"=>$beginTimestamp, "timestamp"=>$timestamp, "beginTime"=>date('d-m-Y H:i:s', $beginTimestamp), "endTime"=>date('d-m-Y H:i:s', $timestamp), "score"=>$score));
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
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </div>
        <div class="row">
            <h1>Details van <?php echo ucwords($firstname); ?></h1>
            <hr />
            <div class="col-sm-2">
                <h4>Details</h4>
                <p>
                    <ul style="list-style-type: none; padding-left: 8px;">
                        <li><span class="glyphicon glyphicon-user"></span> <?php echo ucwords($studentUsername); ?></li>
                        <li><span class="glyphicon glyphicon-time"></span> <?php echo date('d-m-Y', $records[0]["timestamp"]) ?></li>
                        <li><span class="glyphicon glyphicon-hourglass"></span> <?php echo sprintf("%02d", floor(($totalTime) / 60)) . ":" . sprintf("%02d", ($totalTime) % 60); ?> </li>
                        <li><span class="glyphicon glyphicon-star"></span> <?php echo ucwords($minigameName); ?></li>
                    </ul>
                </p>
            </div>
            <div class="col-sm-10">
                <h4>Resultaten <span class="label label-default"><?php echo count($records); ?></span></h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Minigame</th>
                                <th>Begin tijd</th>
                                <th>Eind tijd</th>
                                <th>Tijd</th>
                                <th>Percentage goed</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach($records as $record) {
                                    echo "<tr>";
                                    echo "<td>" . $record["minigameName"] . "</td>";
                                    echo "<td>" . $record["beginTime"] . "</td>";
                                    echo "<td>" . $record["endTime"] . "</td>";
                                    echo "<td>" . sprintf("%02d", floor(($record["timestamp"] - $record["beginTimestamp"]) / 60)) . ":" . sprintf("%02d", ($record["timestamp"] - $record["beginTimestamp"]) % 60) . "</td>";
                                    echo "<td>" . ($record["score"] == 100 ? "<span class=\"glyphicon glyphicon-star\" style=\"color: #FFD800;\"></span> " : "") . $record["score"] . "%" . "</td>";
                                    echo "<td><a href=\"assignments.php?name=" . $firstname . "&timestamp=" . $record['timestamp'] . "\">Bekijk details &raquo;</a></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
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