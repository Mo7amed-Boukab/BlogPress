<?php 
    $host = 'localhost'; 
    $dbname = 'blogpress'; 
    $username = 'root'; 
    $password = ''; 

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }
?>