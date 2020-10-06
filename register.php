<?php
// Include config file
require_once "database.php";

// Define variables and initialize with empty values
$email = $password = $confirm_password = $fname = $lname = $date = $city = $state = $gender = $member = $picture = "";
$target = "";
$email_err = $password_err = $confirm_password_err = $fname_err = $lname_err = $date_err = $city_err = $state_err = $gender_err = $member_err = $picture_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    }
    else{
        // Prepare a select statement
        $query = "SELECT user_id FROM user WHERE email = ?";

        if($stmt = $connection->prepare($query)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();

                if($stmt->num_rows == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validating First Name
    if(empty(trim($_POST["fname"])))
    {
        $fname_err = "Enter first name";
    } elseif(!preg_match("/^[a-zA-Z-' ]*$/",$fname)){
            $fname_err = "Only letters and white space allowed";
    }else{
        $fname = trim($_POST["fname"]);
    }

    // Validating Last Name
    if(empty(trim($_POST["lname"])))
    {
        $lname_err = "Enter last name";
    } elseif(!preg_match("/^[a-zA-Z-' ]*$/",$lname)){
        $lname_err = "Only letters and white space allowed";
    }else{
        $lname = trim($_POST["lname"]);
    }

    // Validating Date
    if(empty(trim($_POST["date"])))
    {
        $date_err = "Enter a date";
    }
    else{
        $date = trim($_POST["date"]);
    }

    // Validating City
    if(empty(trim($_POST["city"])))
    {
        $city_err = "Please enter a city";
    } else{
        $city = trim($_POST["city"]);
    }

    // Validating City
    if(empty(trim($_POST["state"])))
    {
        $state_err = "Please enter a state";
    } else{
        $state = trim($_POST["state"]);
    }


    // Checking for gender
    if (empty($_POST["gender"])) {
        $gender_err = "Gender is required";
    } else {
        $gender = trim($_POST["gender"]);
    }

    // Checking for membership
    if (empty($_POST["member"])) {
        $member_err = "please select one";
    } else {
        $member = trim($_POST["member"]);
    }

    //Checking for picture
    if (!isset($_FILES['picture']))
    {
        $picture_err = "Image required";
    }else {
        // Check for image file
        $allowTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        $detectedType = exif_imagetype($_FILES['picture']['tmp_name']);
        $error = !in_array($detectedType, $allowTypes);
        if($error) {
            if(isset($_POST['Submit']))
            {
                $picture_err = "File is not an image";
            }
        }

        // Check file size
        elseif ($_FILES["picture"]["size"] > 8388608) {
            if(isset($_POST['Submit']))
            {
                $picture_err = "Sorry, your file is too large";
            }
        }
        else{
            $picture = basename($_FILES['picture']['name']);
            $target = "./images/".$picture;
        }
    }


    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($member_err) &&
       empty($fname_err) && empty($lname_err) && empty($confirm_password_err) && empty($date_err) &&
        empty($city_err) && empty($state_err) && empty($gender_err) && empty($picture_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO dating_site.user (email,password,first_name,last_name,date_of_birth,gender,city,state,picture,regular)".
            " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = $connection->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssssssss", $param_email, $param_password, $param_fname, $param_lname, $param_dob, $param_gender, $param_city, $param_state, $param_picture, $param_regular);

            // Set parameters
            $param_email = $email;
            $param_password = $password; // Creates a password hash
            $param_fname = $fname;
            $param_lname = $lname;
            $param_dob = $date;
            $param_gender = $gender;
            $param_city = $city;
            $param_state = $state;
            $param_picture = $picture;
            $param_regular = $member;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
            // Redirect to login page
                move_uploaded_file($_FILES["picture"]["tmp_name"],$target);
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }else{
            echo $connection->connect_error;
        }
    }

    // Close connection
    $connection->close();
}
?>

    <!DOCTYPE html>
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

<?php
require_once('header.php');
?>

<div class="wrapper">
    <h2>Sign Up</h2>
    <p>Please fill this form to create an account.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="col-md-6 form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
            <span class="help-block"><?php echo $email_err; ?></span>
        </div>
        <div class="col-md-6 form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password</label>
            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="col-md-6 form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
            <span class="help-block">* <?php echo $confirm_password_err; ?></span>
        </div>
        <div class="col-md-6 form-group <?php echo (!empty($fname_err)) ? 'has-error' : ''; ?>">
            <label>First Name</label>
            <input type="text" name="fname" class="form-control" value="<?php echo $fname; ?>">
            <span class="help-block">* <?php echo $fname_err; ?></span>
        </div>
        <div class="col-md-6 form-group <?php echo (!empty($lname_err)) ? 'has-error' : ''; ?>">
            <label>Last Name</label>
            <input type="text" name="lname" class="form-control" value="<?php echo $lname; ?>">
            <span class="help-block">* <?php echo $lname_err; ?></span>
        </div>
        <div class="col-md-6 form-group <?php echo (!empty($date_err)) ? 'has-error' : ''; ?>">
            <label>Date of birth</label>
            <input type="date" name="date" class="form-control" value="<?php echo $date; ?>">
            <span class="help-block">* <?php echo $date_err; ?></span>
        </div>
        <div class="form-group" <?php echo (!empty($gender_err)) ? 'has-error' : ''; ?>
            <label>Gender</label>
            <input type="radio" name="gender" value="female">Female
            <input type="radio" name="gender" value="male">Male
            <input type="radio" name="gender" value="other">Other
            <span class="help-block">* <?php echo $gender_err;?></span>
        </form>
        <div class="col-md-6 form-group <?php echo (!empty($city_err)) ? 'has-error' : ''; ?>">
            <label>City</label>
            <input type="text" name="city" class="form-control" value="<?php echo $city; ?>">
            <span class="help-block">* <?php echo $city_err; ?></span>
        </div>
        <div class="col-md-6 form-group <?php echo (!empty($state_err)) ? 'has-error' : ''; ?>">
            <label>State</label>
            <input type="text" name="state" class="form-control" value="<?php echo $state; ?>">
            <span class="help-block"><?php echo $state; ?></span>
        </div>
        <div class="col-md-12 form-group" <?php echo (!empty($member_err)) ? 'has-error' : ''; ?>
            <label>Membership</label>
            <input type="radio" name="member" value="y">Regular
            <input type="radio" name="member" value="n">Premium
            <span class="help-block">* <?php echo $member_err;?></span>
        </div>
        <div class="col-md-12 form-group" <?php echo (!empty($picture_err)) ? 'has-error' : ''; ?>
            <label>Profile picture</label>
            <input type="file" title="Upload your profile picture" id="picture"  name="picture">
            <span class="help-block"><?php echo $picture_err;?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Reset">
        </div>
<div class="sign-up-color"> <p>Already have an account? <a href="login.php">Login here</a></p></div>
    </form>
</div>
</div>

<?php
require_once('footer.php');
?>

</body>
</html>
