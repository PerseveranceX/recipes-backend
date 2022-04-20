<?php 
$db_connection = mysqli_connect($_SERVER['SERVER_NAME'],"username","password","db_name");
   if(!$db_connection){
       echo "Connection error";
   }