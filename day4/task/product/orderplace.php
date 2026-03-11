<?php

session_start();

// echo "<pre>";
// //print_r($_GET);
// // print_r($_POST);
// echo "</pre>";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . ('/../utility/db.php');

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
    exit;
}

if (isset($_GET['cart_id'])) {
    $cart_id = intval($_GET['cart_id']);
}

// 

$user_id = intval($_SESSION['user_id']);
$sql_cart_select = "SELECT c.cart_id , c.product_id , c.user_id , c.qty , p.product_name , p.product_detail , p.product_price , p.product_image
                                FROM cart c 
                                JOIN products p ON c.product_id = p.product_id WHERE c.user_id = :user_id";

$stmt = $connect->prepare($sql_cart_select);

$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

$stmt->execute();

$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($cart_items);
echo "</pre>";




$totalamount = 0;

foreach ($cart_items as $cart_items) {
    $totalamount +=  $cart_items['qty'] * $cart_items['product_price'];
}

echo $totalamount;




if ($totalamount) {
    $sql_checkBalanace = "SELECT user_id , balance FROM accounts WHERE user_id = :user_id AND balance > :totalamount";
    $statment = $connect->prepare($sql_checkBalanace);

    $statment->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $statment->bindParam(':totalamount', $totalamount, PDO::PARAM_INT);

    $statment->execute();

    $chechBalance = $statment->fetch(PDO::FETCH_ASSOC);

    print_r($chechBalance);





    // function to insert order in db

    function addOrder($connect, $totalamount, $order_qty, $order_status, $product_id, $user_id)
    {

        // place order
        $sql_place_order = "INSERT INTO `product_order`(`total_amount`, `order_qty` ,`order_status` , `product_id`, `user_id`) 
                                    VALUES (:total_amount, :order_qty, :order_status ,:product_id ,:product_id)";

    

        $statment_order = $connect->prepare($sql_place_order);

        $statment_order->bindParam(':total_amount', $totalamount);
        $statment_order->bindParam(':order_qty' , $order_qty);
        $statment_order->bindParam(':order_status', $order_status);
        $statment_order->bindParam(':product_id', $product_id);
        $statment_order->bindParam(':user_id', $user_id);

        //print_r($statment_order);
        $statment_order->execute();
    }

   
  

    

    if ($chechBalance) {
        // echo "order placed";
        $order_status = "order placed";


        // user id 
        // echo "<br>";
        $user_id = intval($_SESSION['user_id']);

        // product id
        // echo "<br>";
        $product_id = intval($cart_items['product_id']);

        // order qty 

        // echo "<br>";
        $order_qty = intval($cart_items['qty']);

        // total amount
        $totalamount;

        // add order to order table
        addOrder($connect, $totalamount, $order_qty, $order_status, $product_id, $user_id);

        // remove order from cart

        $sql_clearCart = "DELETE FROM `cart` WHERE `user_id` = :user_id";

        $statment_cart = $connect->prepare($sql_clearCart);

        $statment_cart->bindParam('user_id',$user_id);

        $statment_cart->execute();
        

        $_SESSION['notification'] = "<div class='position-fixed top-0 end-0 p-3' style='z-index: 11' id='notification'>
                                                        <div class='toast show'>
                                                            <div class='toast-header bg-info'>
                                                                <strong class='me-auto'>Nofitication</strong>
                                                                <button type='button' class='btn-close' data-bs-dismiss='toast'></button>
                                                            </div>
                                                                
                                                            <div class='toast-body bg-info'>
                                                                <p> order placed successfully </p>
                                                            </div>
                                                        </div>
                                                    </div>";

        header("Location: cart.php");
        exit;

    } else {
      
        // user id 
        // echo "<br>";
        $user_id = intval($_SESSION['user_id']);

        // product id
        // echo "<br>";
        $product_id = intval($cart_items['product_id']);

        // total amount
        $totalamount;

        $order_qty = intval($cart_items['qty']);

        $order_status = "bank insufficent";

        addOrder($connect, $totalamount, $order_qty, $order_status, $product_id, $user_id);

        $_SESSION['notification'] = "<div class='position-fixed top-0 end-0 p-3' style='z-index: 11' id='notification'>
                                                        <div class='toast show'>
                                                            <div class='toast-header bg-info'>
                                                                <strong class='me-auto'>Nofitication</strong>
                                                                <button type='button' class='btn-close' data-bs-dismiss='toast'></button>
                                                            </div>
                                                                
                                                            <div class='toast-body bg-info'>
                                                                <p> order failed due to balance insufficent </p>
                                                            </div>
                                                        </div>
                                                    </div>";

        header("Location: cart.php");
        exit;
    }
} 











// try {
// } catch (Exception $e) {

//     echo "Error : " . $e->getMessage();
// } finally {
//     $stmt = null;
//     $connect = null;
// }
