<?php

    $servername = "localhost";
    $username = "root";
    $password = "Root@123";
    $dbname = "training";
    

    try {
        $connect = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //echo "database connect successfully";
    } catch (Exception $e) {
        echo "Error : " . $e->getMessage();
    }

