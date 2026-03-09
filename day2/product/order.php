<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . ('/../database/db.php');

try {
    // fetch product from database
    //$sql_order = "SELECT `order_id`, `quantity`,`order_date`,`total` ,`product_id`,`user_id` FROM `product_order`";

    if(!isset($_POST['submit']) && empty($_POST['days'])) {
         $sql_order = "SELECT product_order.order_id,product_order.quantity,product_order.product_id , product_order.total, product_order.order_date, 
                                product.product_name, product.product_price 
                                FROM product_order 
                                LEFT JOIN product 
                                ON product_order.order_id = product.product_id;";
    } else {
        $days = $_POST['days'];
        $sql_order = "SELECT product_order.order_id, product_order.quantity, product_order.product_id , product_order.total, product_order.order_date , 
                        product.product_name, product.product_price 
                        FROM product_order 
                        LEFT JOIN product 
                        ON product_order.order_id = product.product_id 
                        WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL $days DAY);";
    }

    $stmt = $connect->query($sql_order);

} catch (Exception $e) {
    echo "Error : " . $e->getMessage();
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>product</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>

<body>

    <?php require_once __DIR__ . ('/../src/header.php'); ?>

    <div class="container mt-5">
        <table class="table">
            <tr>
                <th>Order id</th>
                <th>Quantity</th>
                <th>Product name</th>
                <th>Product price</th>
                <th>Date</th>
                <th>Total</th>

            </tr>
       
            <?php
                if ($stmt->rowCount() > 0) {
                    while ($order = $stmt->fetch()) {
            ?>

            <tbody>
                <tr>
                    <td><?= $order['order_id'] ?></td>
                    <td><?= $order['quantity'] ?></td>
                    <td><?= $order['product_name'] ?></td>
                    <td><?= $order['product_price'] ?></td>
                    <td><?= $order['order_date'] ?></td>
                    <td><?= number_format($order['total']) ?></td>
                </tr>
            </tbody>
            
            <?php
                    }
                }
            ?>
        </table>


        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
             <label for="days" class="form-label">Select days to get report</label>
             
            <select class='form-select' name='days' aria-label='student'>
                <option value="1">1 day</option>
                <option value="3">3 day</option>
                <option value="7">1 week</option>
                <option value="14">2 week</option>
                <option value="30">one month</option>
                <option value="60">two month</option>
                <option value="90">three month</option>
            </select>

            <input type="submit" value="submit" name="submit" class="btn btn-dark">
        </form>
       
    </div>

</body>

</html>