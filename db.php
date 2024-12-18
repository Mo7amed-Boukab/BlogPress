<?php 

    $servername = "localhost";
    $username = "mohamed";
    $password = "";
    $dbname = "blogpress";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } 

?>