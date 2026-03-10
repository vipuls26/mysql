<?php

session_start();
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . ('/../utility/db.php');

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
    exit;
}

try {

    // user id from session 
    $user_id = intval($_SESSION['user_id']);

    // select product from db
    $sql_select = "SELECT `product_id`, `product_name`, `product_detail`, `product_price`, `product_image`, `create_at`
                            FROM `products` WHERE `user_id` = :user_id ";
    $stmt = $connect->prepare($sql_select);

    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //print_r($products);


} catch (Exception $e) {
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
    <title>My Product</title>

    <!-- bootstrap  -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB' crossorigin='anonymous'>

    <!-- bootstrap js -->
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>

    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <!-- jquery validation -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


</head>

<body>


    <!-- navbar -->

    <?php require_once __DIR__ . ('/../utility/header.php');  ?>

    <div class="container mt-5">
        <!-- navbar -->
        <nav class="navbar navbar-expand-md navbar-light bg-white">
            <div class="container-fluid p-0">

                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="../product/add.php"> New Product </a>
                    <a class="nav-link active" href="../product/myproduct.php" aria-current="page"> My product </a>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#myNav" aria-controls="myNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fas fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="myNav">
                    <div class="navbar-nav ms-auto">
                        <a class="nav-link" href="../product/products.php">All</a>
                        <a class="nav-link" href="../product/cart.php">Cart</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- my product -->
        <div class="row">
            <?php if (!empty($products)) { ?>
                <?php foreach ($products as $product) {  ?>
                    <div class="col-12 col-md-3">
                        <div class="card shadow">
                            <img src="../asset/uploads/<?= $product['product_image']; ?>" alt="watch large" height="200px" width="200px">
                            <div class="card-footer bg-gray-200 border-top border-gray-300 p-4">
                                <a href="#" class="h5"></a>

                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <a class="btn btn-xs btn-tertiary" href="../product/edit.php?product_id=<?= $product['product_id'] ?>">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </a>
                                    <a class="btn btn-xs btn-tertiary Delete" href="../product/delete.php?product_id=<?= $product['product_id'] ?>">
                                        <i class="fa-solid fa-trash me-2"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>

                <p>no product found</p>

            <?php } ?>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            $(".Delete").click(function(){
                return confirm("Are you sure?");
            });
        });

    </script>
</body>

</html>