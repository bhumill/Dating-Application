<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "database.php";

// Define variables and initialize with empty values
$new_picture = $new_target = "";
$new_picture_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{

    // Validate new picture

    if (!isset($_FILES['new_picture']))
    {
        $new_picture_err = "Image required";
    }
    else {
        // Check for image file
        $allowTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        $detectedType = exif_imagetype($_FILES['new_picture']['tmp_name']);
        $error = !in_array($detectedType, $allowTypes);
        if ($error) {
            if (isset($_POST['Change'])) {
                $new_picture_err = "File is not an image";
            }
        } // Check file size
        elseif ($_FILES["new_picture"]["size"] > 8388608) {
            if (isset($_POST['Change'])) {
                $new_picture_err = "Sorry, your file is too large";
            }
        } else {
            $new_picture = basename($_FILES['new_picture']['name']);
            $new_target = "./images/" . $new_picture;
        }
    }
    // Check input errors before updating the database
    if(empty($new_picture_err)){
        // Prepare an update statement
        $sql = "UPDATE user SET picture = ?  WHERE user_id = '". $_SESSION['id']."'";
        if($stmt = $connection->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_new_picture);

            // Set parameters
            $param_new_picture = $new_picture;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Photo updated successfully.
                move_uploaded_file($_FILES["new_picture"]["tmp_name"],$new_target);
                header("location:profile.php?id=". $_SESSION['id']);
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Photo</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Change Photo</h2>
    <p>Select a file to change the image.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group" <?php echo (!empty($new_picture_err)) ? 'has-error' : ''; ?>
            <label>Profile picture</label>
            <input type="file" title="Upload your profile picture" id="new_picture"  name="new_picture">
            <span class="help-block"><?php echo $new_picture_err;?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Change">
            <a class="btn btn-link" href="profile.php">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>