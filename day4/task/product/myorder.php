<?php

    session_start();

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ . '/../utility/db.php';

    if (!isset($_SESSION['email'])) {
        header('Location: ../auth/login.php');
        exit;
    }

    echo $user_id = intval($_SESSION['user_id']);


    try {

        $sql_select_order = "SELECT 
                                    order_id, 
                                    order_qty, 
                                    order_date, 
                                    total_amount, 
                                    order_status, 
                                    product_id, 
                                    user_id 
                                FROM 
                                    product_order 
                                WHERE 
                                    user_id = :user_id 
                                ORDER BY 
                                    order_id DESC";

       

        $stmt = $connect->prepare($sql_select_order);

        $stmt->bindParam(':user_id',  $user_id);

        $stmt->execute();

        $order = $stmt->fetchAll(PDO::FETCH_ASSOC);

        print_r($order);

    } catch ( Exception $e ) {
        echo "Error : " . $e->getMessage();
    } finally {
        $stmt = null;
        $connect = null;
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>my order</title>
    <!-- bootstrap  -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB' crossorigin='anonymous'>

    <!-- bootstrap js -->
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>

    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

</head>

<body>

</body>

</html>