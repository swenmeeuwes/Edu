<!--NEEDS VERIFICATION-->
<?php
    session_start();
    if(!isset($_SESSION["username"]) && !empty($_GET["name"]) && !empty($_GET["timestamp"])) {
        echo "Please login first.";
        header('Location: index.php');
        die();
    }
    date_default_timezone_set('Europe/Amsterdam');
    $assignments = array();

    $ini_array = parse_ini_file("../Webservice/Config/config.ini", true);
	// Create connection
	$database = new mysqli($ini_array['Database']['host'], $ini_array['Database']['username'], $ini_array['Database']['password'], $ini_array['Database']['database']);
	// Check connection
	if ($database->connect_error) {
	    die("OOPS!: " . $database->connect_error);
	}
    $stmt = $database->prepare("SELECT r.minigameName, m.skill, r.timestamp, r.beginTimestamp, a.question, a.anwser, a.correctAnwser FROM Student s INNER JOIN Assignment a ON s.studentUsername = a.username INNER JOIN Record r ON a.timestamp = r.timestamp INNER JOIN Minigame m ON m.minigameName = r.minigameName WHERE s.parentUsername = ? AND s.firstname = ? AND r.timestamp = ?");
    $stmt->bind_param("ssi", $parentUsername, $firstname, $timestamp);
    
    $parentUsername = $_SESSION["username"];
    $firstname = $_GET["name"];
    $timestamp = $_GET["timestamp"];

	$stmt->execute();

    $stmt->bind_result($minigameName, $skill, $endTimestamp, $beginTimestamp, $question, $anwser, $correctAnwser);

    while($stmt->fetch()) {
        $correct = ($anwser == $correctAnwser);
        array_push($assignments, array("question"=>$question, "anwser"=>$anwser, "correctAnwser"=>$correctAnwser, "correct"=>$correct));
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
                <li class="breadcrumb-item"><a href="details.php?name=<?php echo ucwords($_GET["name"]); ?>">Details</a></li>
                <li class="breadcrumb-item active">Assignments</li>
            </ol>
        </div>
        <div class="row">
            <h1>Resultaten van <?php echo ucwords($_GET["name"]);?></h1>
            <i>
                Op <?php echo date('d-m-Y', $beginTimestamp) ?> <br/>
                Van <?php echo date('H:i:s', $beginTimestamp) ?> tot <?php echo date('H:i:s', $endTimestamp) ?>
            </i>
            <hr/>
            <div class="col-sm-2">
                <h4>Details</h4>
                <p>
                    <ul style="list-style-type: none; padding-left: 8px;">
                        <li><span class="glyphicon glyphicon-list-alt"></span> <a href="#"><?php echo ucwords($minigameName); ?></a></li>
                        <li><span class="glyphicon glyphicon-education"></span> <?php echo ucwords($skill); ?></li>
                        <li><span class="glyphicon glyphicon-time"></span> <?php echo date('d-m-Y', $beginTimestamp) ?></li>
                        <li><span class="glyphicon glyphicon-hourglass"></span> <?php echo sprintf("%02d", floor(($endTimestamp - $beginTimestamp) / 60)) . ":" . sprintf("%02d", ($endTimestamp - $beginTimestamp) % 60); ?> </li>
                    </ul>
                </p>
            </div>
            <div class="col-sm-10">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Vraag</th>
                                <th>Correct antwoord</th>
                                <th>Gegeven antwoord</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach($assignments as $assignment) {
                                    echo "<tr>";
                                    echo "<td>" . $assignment["question"] . "</td>";
                                    echo "<td>" . $assignment["correctAnwser"] . "</td>";
                                    echo "<td>" . $assignment["anwser"] . "</td>";
                                    echo "<td>". ($assignment["correct"] ? "<span class=\"glyphicon glyphicon-ok\" style=\"color: #33cc33;\"></span>" : "<span class=\"glyphicon glyphicon-remove\" style=\"color: #e60000;\"></span>") . "</td>";
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