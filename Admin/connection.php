<?php 
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "crudproj_db";
    

    $conn = mysqli_connect($server, $user, $pass, $db);

    if (!$conn) {
        die (mysqli_connect_error());  
    }

?>