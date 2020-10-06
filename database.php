<?php
define('DB_SERVER','localhost');
define('DB_USERNAME','root');
define('DB_PASSWORD','');
define('DB_NAME','dating_site');

$connection = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);

if(!$connection)
{
    die("ERROR : Connection error. ".$connection->connect_error);
}
?>