<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ . ('/../database/db.php');

    echo "<pre>";
    print_r($_POST);
    echo "</pre>";


    try {

        echo $sql_select = "SELECT product_price FROM product WHERE product_id = " . $_POST['product_id'];
        echo "<br>";

         $stmt = $connect->query($sql_select);

         if($stmt->rowCount() > 0) {
                        while ($product = $stmt->fetch()) {
                            echo $product_price = $product['product_price'];
                        }
         }
        
        if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST") {


             // total value of product 
            echo $total = intval($_POST['qty'])* intval($product_price);

            // set current id for user
            $user_id = 1;

            // qty 
            $qty = intval(trim($_POST['qty']));

            // product id
            $product_id = intval(trim($_POST['product_id']));
            // insert order value in db
            $sql_order = "INSERT INTO `product_order`(`quantity`, `total`, `product_id`, `user_Id`) 
            VALUES ('$qty','$total','$product_id','$user_id')";

            $connect->exec($sql_order);
            
            header("Location: ./product.php");

        }

    } catch ( Exception $e ) {
        echo "Error : " . $e->getMessage();
    }

?>