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


    echo "<pre>";
    print_r($_GET);
    echo "</pre>";


try {


    // delete user product from db for product and cart table

    if (isset($_GET['product_id'])) {
        echo $delete_product_id = intval($_GET['product_id']);
        $sql_delete = "DELETE FROM `products` WHERE product_id = :product_id ";

        $stmt = $connect->prepare($sql_delete);

        $stmt->bindParam(':product_id', $delete_product_id, PDO::PARAM_INT);

        $stmt->execute();

          $_SESSION['notification'] = "<div class='position-fixed top-0 end-0 p-3' style='z-index: 11' id='notification'>
                                                    <div class='toast show'>
                                                        <div class='toast-header bg-warning'>
                                                            <strong class='me-auto'>Nofitication</strong>
                                                            <button type='button' class='btn-close' data-bs-dismiss='toast'></button>
                                                        </div>
                                                            
                                                        <div class='toast-body bg-warning'>
                                                            <p> product delete </p>
                                                        </div>
                                                    </div>
                                                </div>";

        // redirect to product page
        header("Location: ../product/myproduct.php");
        exit();
    }

   

    if(isset($_GET['id'])) {

        echo $clearCart_id = intval($_GET['id']);

        $sql_clearCart = "DELETE FROM `cart` WHERE `user_id` = :user_id";

        $stmt = $connect->prepare($sql_clearCart);

        $stmt->bindParam(':user_id',$clearCart_id,PDO::PARAM_INT);

        $stmt->execute();
           $_SESSION['notification'] = "<div class='position-fixed top-0 end-0 p-3' style='z-index: 11' id='notification'>
                                                    <div class='toast show'>
                                                        <div class='toast-header bg-warning'>
                                                            <strong class='me-auto'>Nofitication</strong>
                                                            <button type='button' class='btn-close' data-bs-dismiss='toast'></button>
                                                        </div>
                                                            
                                                        <div class='toast-body bg-warning'>
                                                            <p> all product clear from cart</p>
                                                        </div>
                                                    </div>
                                                </div>";

        header("Location: ../product/cart.php");
        exit();
    }

   

} catch (Exception $e) {
    echo "Error : " . $e->getMessage();
} finally {
    $stmt = null;
    $connect = null;
}
