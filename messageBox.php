<?php
session_start(); //Use this for creating the session

// Include database file
require_once "database.php";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
if(!isset($_POST['send']))
{
    $_SESSION["receiver"] = $_GET['id'];
}
if(isset($_POST['send']))
{
    $msg = $_POST['message'];
    $sendSql = "INSERT INTO message (user_id, to_user_id, message) VALUES (?,?,?)";
    if($stmt = $connection->prepare($sendSql))
    {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("sss", $param_sender, $param_receiver, $param_message);

        // Set parameters
        $param_sender = $_SESSION['id'];
        $param_receiver = $_SESSION['receiver']; // Creates a password hash
        $param_message = $msg;
        if($stmt->execute())
        {
                header("location: messageBox.php?id=".$_SESSION["receiver"]);
        }
        else
            {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
    }
    else
        {
            echo $connection->connect_error;
        }
}

$getMessages = "SELECT * FROM message WHERE 
                            (user_id = '".$_SESSION['id']."' AND to_user_id = '".$_SESSION['receiver']."')
                             OR (user_id = '".$_SESSION['receiver']."' AND to_user_id = '".$_SESSION['id']."') 
                             ORDER BY chat_id ASC";
$getReceiver = "SELECT * FROM user WHERE user_id = '".$_SESSION['receiver']."'";
$profileResult = $connection->query($getReceiver);
while($topRow = $profileResult->fetch_assoc()) {  ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <link rel="canonical" href="https://getbootstrap.com/docs/3.4/examples/jumbotron/">

    <title>Dating Sites</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="homepage.php"><img src="images/logo.png"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">


            <form class="form-inline mt-2 mt-md-0 navbar-form navbar-right" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" >
<!--                <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search" name="search">-->
<!--                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>-->
                <a href="logout.php"><button type="button" id="formButton" class="btn btn-success">Sign Out</button></a>
                <a href="profile.php?id=<?php echo $_SESSION['id']; ?>"><button type="button" id="formButton" class="btn btn-success">My Profile</button></a>
            </form>

            <!--<a href="logout.php" class="btn btn-outline-success my-2 my-sm-0">
                Sign Out</a>
            <a href="profile.php?id=<?php echo $_SESSION['id']; ?>" class="btn btn-outline-success my-2 my-sm-0">
                My profile</a>-->

        </div><!--/.navbar-collapse -->
    </div>
</nav>
<div class="container">
    <div class="col-md-4"></div>
<div class="col-md-4 view-user-bg">
    <div class="card mb-4 shadow-sm">
        <img src="images/<?php echo $topRow["picture"]; ?>">
        <div class="card-body">
            <h4><?php echo $topRow["first_name"] . ' ' . $topRow["last_name"]; ?></h4>
        </div>
    </div>
    <!--message box area starts-->
    <div class="message-box">
        <?php
        $result = $connection->query($getMessages);
        if($result->num_rows == 0)
        {
            echo "<h4>Start Connecting Now</h4>";
        }
        while($row = $result->fetch_assoc())
        {?>
            <div class="messages">
                <div class="col-md-6">
                    <h3><?php if($row["to_user_id"] == $_SESSION['id'])
                        {
                            echo $row["message"];
                        }?></h3>
                </div>
                <div class="col-md-6">
                    <h3><?php if($row["to_user_id"] != $_SESSION['id'])
                        {
                            echo $row["message"];
                        }?></h3>
                </div>
            </div>
            <?php
        }?>
        <div class="col-md-12">
        <form method="post" action="messageBox.php?id=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="message" placeholder="Type Your Message">
            <input type="submit" value="Send" name="send">
        </form>
        </div>
    </div>
    <div class="col-md-4"></div>
    <!--message box area ends-->
</div><?php
}
?>

</div>
//<?php
//require_once('footer.php');
//?>
</body>
</html>