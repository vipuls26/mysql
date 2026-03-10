<?php

    session_start();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ . ('/../utility/db.php');

    if (!isset($_SESSION['email'])) {
        header('Location: ../auth/login.php');
        exit;
    }


    // echo "<pre>";
    // print_r($_GET);
    // echo "</pre>";

    echo $delete_product_id = intval($_GET['product_id']);

    try {

        // delete user product from db

        $sql_delete = "DELETE FROM `products` WHERE product_id = :product_id ";

        $stmt = $connect->prepare($sql_delete);

        $stmt->bindParam(':product_id',$delete_product_id , PDO::PARAM_INT);

        $stmt->execute();

        header("Location: ../product/myproduct.php");
        exit();

    } catch ( Exception $e ) {
        echo "Error : " . $e->getMessage();
    } finally {
        $stmt = null;
        $connect = null;
    }


?>