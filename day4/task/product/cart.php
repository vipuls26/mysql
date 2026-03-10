<?php

session_start();

// echo "<pre>";
// print_r($_GET);
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

    $user_id = intval($_SESSION['user_id']);

    if (isset($_GET['product_id'])) {

        // getting value for user and product id

        $product_id = intval($_GET['product_id']);

        // insert cart value in db
        $sql_insert_cart = "INSERT INTO `cart`(`product_id`, `user_id`) VALUES (:product_id, :user_id)";

        $stmt = $connect->prepare($sql_insert_cart);

        // binding value 
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        $stmt->execute();

        // redirect to dashboard 
        header("Location: ../user/dashboard.php");
        exit();
    } else {
        // fetching data from db
        $sql_select_cart = "
                            SELECT c.cart_id, c.user_id, p.product_id, p.product_name, p.product_detail, p.product_price, p.product_image
                                FROM cart c
                                JOIN products p ON c.product_id = p.product_id WHERE c.user_id = :user_id
                                ORDER BY c.cart_id ASC ;
                                ";

        $stmt = $connect->prepare($sql_select_cart);

        // binding value
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        $stmt->execute();

        // cart data for current user

        $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //print_r($cart);

    }
} catch (Exception $e) {
    echo "Error : " . $e->getMessage();
} finally {
    $stmt = null;
    $connect = null;
}




?>


<!-- bootstrap  -->
<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB' crossorigin='anonymous'>

<!-- bootstrap js -->
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>

<!-- icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">


<!-- navbar -->

<?php require_once __DIR__ . ('/../utility/header.php');  ?>



<div class="container mt-5">
    <nav class="navbar navbar-expand-md navbar-light bg-white">
        <div class="container-fluid p-0">

            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../product/myproduct.php">
                    New Product
                </a>


                <a class="nav-link" href="../product/myproduct.php">
                    My product
                </a>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#myNav" aria-controls="myNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fas fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="myNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" aria-current="page" href="../product/products.php">All</a>
                    <a class="nav-link active" href="../product/cart.php">Cart</a>
                </div>
            </div>
        </div>
    </nav>
    <section>
        <div class="row">
            <h5 class="mb-4">Cart </h5>

            <div class="col-6 pt-4 mb-3">
                <div class="d-flex justify-content-end align-items-baseline">
                    <a href="#" class="btn btn-sm btn-danger">Empty Cart</a>
                </div>

                <?php if (!empty($cart)) { ?>
                    <?php foreach ($cart as $cart) {  ?>
                        <div class="row mb-4 mt-3">
                            <div class="col-sm-3 col-lg-2 col-xl-2">
                                <div class="rounded mb-3 mb-md-0">
                                    <img class="img-fluid w-100" src="../asset/uploads/<?= $cart['product_image'] ?>" alt="Sample">
                                </div>
                            </div>
                            <div class="col-sm-9 col-lg-10 col-xl-10">
                                <div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5><?= $cart['product_name'] ?></h5>
                                            <p><?= $cart['product_detail'] ?></p>
                                            <p class="mb-3 text-muted text-uppercase small">Price: ₹ <?= $cart['product_price'] ?></p>
                                        </div>

                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="#!" type="button" class="text-decoration-none small text-uppercase me-3">
                                                <i class="fa-solid fa-trash"></i> Remove item
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="mb-4">

                    <?php } ?>
                <?php } else { ?>

                    <p>no product found</p>

                <?php } ?>

            </div>

        </div>
    </section>
</div>