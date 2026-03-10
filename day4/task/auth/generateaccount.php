<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();

    require_once __DIR__ . ('/../utility/db.php');


    try {
        $email = $_SESSION['user_email'];

        // register account detail for user when user register with system
        $sql_user_data = "SELECT user_id FROM users WHERE email = :email";
        $statment = $connect->prepare($sql_user_data);

        $statment->bindParam(':email', $email);
        $statment->execute();

        $user = $statment->fetch(PDO::FETCH_ASSOC);

        print_r($user);

        // user id 
        $user_id = $user['user_id'];

        // balance when account open
        $balance = 0;

        // create account for user 
        $sql_account_insert = "INSERT INTO `accounts`(`balance`,`user_id`) 
                                    VALUES (:balance,:user_id)";
        $stmt = $connect->prepare($sql_account_insert);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':balance', $balance);

        $stmt->execute();

        header("Location: login.php");
        exit;
    } catch (Exception $e) {
        echo "Error while generating account" . $e->getMessage();
    } finally {
        $stmt = null;
        $statment = null;
        $connect = null;
    }
