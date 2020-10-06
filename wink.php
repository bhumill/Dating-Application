<?php
session_start(); //Use this for creating the session

// Include database file
require_once "database.php";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
if(isset($_GET['id']))
{
    $winkSql = "INSERT INTO wink (user_id, user2_id) VALUES (?,?)";
    if($stm = $connection->prepare($winkSql))
    {
        $stm->bind_param("ss",$param_sender,$param_receiver);
        $param_sender = $_SESSION['id'];
        $param_receiver = $_GET['id'];
        if($stm->execute())
        {
            header("location: homepage.php");
        }
        else{
            echo "Something went wrong. Please try again later.";
        }
        $stm->close();
    }
    else{
        echo $connection->connect_error;
    }
}
else{
    echo "SOMETHING WENT WRONG";
}