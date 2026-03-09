<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ . ('/../database/db.php');

    try {
        // fetch product from database
        $sql_product = "SELECT `product_id`, `product_name`, `product_price` FROM `product`";
        $stmt = $connect->query($sql_product);

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
        
            <div class="row">
                <?php
                    if ($stmt->rowCount() > 0) {
                        while ($product = $stmt->fetch()) {
                ?>
                
                    <div class="col-12 col-md-4 col-lg-3">
                        <form action="addproduct.php" method="post">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <h5 class="card-title">Name: <?= $product['product_name']; ?></h5>
                                    <div class="mb-2">
                                        <span class="font-bold"><strong>Price: <?= $product['product_price'] ?></strong></span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                    
                                    <input type="number" name="qty" class="form-control border-info w-50" min="1" value="1"  placeholder="1">  
                                    <input type="text" name="product_id" value="<?= $product['product_id'] ?>" hidden>
                                          
                                        <!-- <a href="./order.php?product_id=<? //= $product['product_id'] ?>" class="btn btn-warning mx-2">Buy now</a>  -->
                                       
                                        <input type="submit" value="buy now" class="btn btn-warning" name="buy now">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

             
                    <?php
                            }
                        }
                    ?>
                   
            </div>
        
    </div>

</body>

</html>