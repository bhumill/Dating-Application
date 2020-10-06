<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    $_SESSION['id']=" ";
}
require_once "database.php";
$getAllUsers = "SELECT * FROM user where user_id != '".$_SESSION['id']."'";
$result = $connection->query($getAllUsers);
if(isset($_GET["search"]))
{
    $getUserByName = "SELECT * FROM user where first_name like '".$_GET['search']."%' and user_id != '".$_SESSION['id']."'";
    $searchresult = $connection->query($getUserByName);
}
?>

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
                <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search" name="search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
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


<!-- Profile page area starts-->
<div class="album py-5 bg-light">
    <div class="container">
        <div class="row">
            <?php
            if ($result->num_rows > 0 && !isset($_GET["search"])) {
                // output data of each row
                while($row = $result->fetch_assoc()) {  ?>
                    <div class="col-md-4 ">
                        <div class="card mb-4 shadow-sm profile-bg">
                            <img src="images/<?php echo $row["picture"]; ?>">
                            <div class="card-body">
                                <h4><?php echo $row["first_name"] . ' ' . $row["last_name"]; ?></h4>
                                <h6><?php echo $row["gender"]; ?></h6>
                                <h6><?php echo $row["date_of_birth"]; ?></h6>
                                <a class="btn btn-success"  href="view_user.php?id=<?php echo $row["user_id"]; ?>">View Profile</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            elseif ($searchresult->num_rows > 0)
            {
                while($row = $searchresult->fetch_assoc())
                {?>
                    <div class="col-md-4 ">
                        <div class="card mb-4 shadow-sm profile-bg">
                            <img src="images/<?php echo $row["picture"]; ?>">
                            <div class="card-body">
                                <h4><?php echo $row["first_name"]. ' ' . $row["last_name"]; ?></h4>
                                <h6><?php echo $row["gender"];?></h6>
                                <h6><?php echo $row["date_of_birth"]; ?></h6>
                                <a class="btn btn-success" href="view_user.php?id=<?php echo $row["user_id"]; ?>">View Profile</a>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            else {
                echo "No results";
            }
            ?>
        </div>
    </div>
</div>

<?php
require_once('footer.php');
?>
</body>
</html>



<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <title>Welcome</title>-->
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">-->
<!--    <style type="text/css">-->
<!--        body{ font: 14px sans-serif; text-align: center; }-->
<!--    </style>-->
<!--</head>-->
<!--<body>-->
<!--<div class="page-header">-->
<!--    <h1>Hi, <b>--><?php //echo htmlspecialchars($_SESSION["email"]); ?><!--</b>. Welcome to our site.</h1>-->
<!--</div>-->
<!--<p>-->
<!--    <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>-->
<!--    <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>-->
<!--</p>-->
<!--</body>-->
<!--</html>-->
