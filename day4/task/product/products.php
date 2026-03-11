<?php


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

    $user_id = intval($_SESSION['user_id']);
    // select product from db
    $sql_select = "SELECT `product_id`, `product_name`, `product_detail`, `product_price`, `product_image`, `create_at`, `user_id` 
                    FROM `products` WHERE user_id != :user_id";
    $stmt = $connect->prepare($sql_select);

    $stmt->bindParam(':user_id',$user_id,PDO::PARAM_INT);

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

    <div class="container mt-5">
    <!-- navbar -->
    <nav class="navbar navbar-expand-md navbar-light bg-white">
        <div class="container-fluid p-0">

            <!-- drop down list for link -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#myNav" aria-controls="myNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fas fa-bars"></span>
            </button>

            <div class="collapse navbar-collapse" id="myNav">
                
                <div class="navbar-nav ms-auto">
                    
                    <a class="nav-link" href="../product/myproduct.php">  New Product </a>

                    <a class="nav-link" href="../product/myproduct.php"> My product </a>

                    <a class="nav-link active" aria-current="page" href="../product/products.php">All</a>

                    <a class="nav-link" href="../product/cart.php">Cart</a>

                    <a class="nav-link" href="../product/myorder.php">Order</a>
                </div>
            </div>
        </div>
    </nav>
    <hr class="border-secondary">

    <div class="row">
        <?php if (!empty($products)) { ?>
            <?php foreach ($products as $product) {  ?>
                <div class="col-12 col-md-4">
                    <div class="card shadow">
                        <img class="card-img-top" height="300px" width="100px" src="../asset/uploads/<?= $product['product_image']; ?>" alt="<?= $product['product_name'] ?>">
                        <div class="card-footer bg-gray-200 border-top border-gray-300 p-4">
                          

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="h6 mb-0 text-gray">Price : ₹ <?= $product['product_price'] ?></span>
                                <a class="btn btn-xs btn-tertiary" href="../product/cart.php?product_id=<?= $product['product_id'] ?>">
                                    <span class="fas fa-cart-plus me-2"></span> Add to cart
                                </a>
                            </div>
                            <div class="text-dark">
                                <p><?= $product['product_name'] ?></p>
                                <p> <?= $product['product_detail'] ?> </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>

            <p>no product found</p>

        <?php } ?>
    </div>


     <!-- toast notification -->

        <?php
        if (isset($_SESSION['notification'])) {
            echo $_SESSION['notification'];
            unset($_SESSION['notification']);
        } else {
            echo '';
        }

        ?>

</div>
    

