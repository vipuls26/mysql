<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../utility/db.php';

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = intval($_SESSION['user_id']);

try {
    if (isset($_GET['product_id'])) {
        $product_id = intval($_GET['product_id']);

        // check if already in cart
        $check = "SELECT cart_id, qty FROM cart WHERE user_id=:user_id AND product_id=:product_id";
        $stmt = $connect->prepare($check);
        $stmt->execute([
            ':user_id' => $user_id,
            ':product_id' => $product_id
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // increase qty
            $update = "UPDATE cart SET qty = qty + 1 WHERE cart_id=:cart_id";
            $stmt = $connect->prepare($update);
            $stmt->execute([':cart_id' => $row['cart_id']]);
        } else {
            // insert new
            $insert = "INSERT INTO cart(product_id,user_id,qty) VALUES(:product_id,:user_id,1)";
            $stmt = $connect->prepare($insert);
            $stmt->execute([
                ':product_id' => $product_id,
                ':user_id' => $user_id
            ]);
        }

        header("Location: cart.php");
        exit;
    }


    if (isset($_POST['update_qty'])) {
        $cart_id = intval($_POST['cart_id']);
        $qty = max(1, intval($_POST['qty']));

        $sql = "UPDATE cart SET qty=:qty WHERE cart_id=:cart_id AND user_id=:user_id";
        $stmt = $connect->prepare($sql);
        $stmt->execute([
            ':qty' => $qty,
            ':cart_id' => $cart_id,
            ':user_id' => $user_id
        ]);

        header("Location: cart.php");
        exit;
    }

    if (isset($_GET['remove_id'])) {
        echo $delete_cart_id = intval($_GET['remove_id']);

        $sql_delete = "DELETE FROM `cart` WHERE cart_id = :remove_id ";

        $stmt = $connect->prepare($sql_delete);

        $stmt->bindParam(':remove_id', $delete_cart_id, PDO::PARAM_INT);

        $stmt->execute();

        $_SESSION['notification'] = "<div class='position-fixed top-0 end-0 p-3' style='z-index: 11' id='notification'>
                                                            <div class='toast show'>
                                                                <div class='toast-header bg-info'>
                                                                    <strong class='me-auto'>Nofitication</strong>
                                                                    <button type='button' class='btn-close' data-bs-dismiss='toast'></button>
                                                                </div>
                                                                    
                                                                <div class='toast-body bg-info'>
                                                                    <p> product remove from cart</p>
                                                                </div>
                                                            </div>
                                                        </div>";
        // redirect to cart page
        header("Location: ../product/cart.php");
        exit();
    }




    if (isset($_GET['clear'])) {
        $sql = "DELETE FROM cart WHERE user_id=:user_id";
        $stmt = $connect->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
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

        header("Location: cart.php");
        exit;
    }


    $sql = "
                        SELECT c.cart_id, c.qty,
                        p.product_id,
                        p.product_name,
                        p.product_detail,
                        p.product_price,
                        p.product_image
                        FROM cart c
                        JOIN products p ON c.product_id=p.product_id
                        WHERE c.user_id=:user_id
                        ORDER BY c.cart_id ASC
                        ";

    $stmt = $connect->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // print_r($cart);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$total = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>


    <!-- bootstrap  -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB' crossorigin='anonymous'>

    <!-- bootstrap js -->
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>

    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <!-- jquery  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


</head>

<body>

    <!-- navbar -->

    <?php require_once __DIR__ . ('/../utility/header.php');  ?>

    <div class="container mt-5">

        <!-- navbar -->
        <nav class="navbar navbar-expand-md navbar-light bg-white">
            <div class="container-fluid p-0">

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#myNav" aria-controls="myNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fas fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="myNav">

                    <div class="navbar-nav ms-auto">

                        <a class="nav-link" href="../product/add.php"> New Product </a>
                        <a class="nav-link" href="../product/myproduct.php"> My product </a>
                        <a class="nav-link" href="../product/products.php">All</a>
                        <a class="nav-link active" aria-current="page" href="../product/cart.php">Cart</a>
                        <a class="nav-link" href="../product/myorder.php">Order</a>
                    </div>
                </div>

            </div>
        </nav>
        <hr class="border-secondary">



        <h3 class="mb-4">Shopping Cart</h3>

        <?php if (!empty($cart)) { ?>

            <div class="d-flex justify-content-end mb-3">
                <a href="cart.php?clear=1" class="btn btn-danger btn-sm clearCart">
                    Empty Cart
                </a>
            </div>

            <?php foreach ($cart as $cart) {

                $subtotal = $cart['product_price'] * $cart['qty'];
                $total += $subtotal;

            ?>

                <div class="row border rounded p-3 mb-3 align-items-center">

                    <div class="col-md-2">
                        <img src="../asset/uploads/<?= htmlspecialchars($cart['product_image']) ?>"
                            class="img-fluid">
                    </div>

                    <div class="col-md-4">
                        <h5><?= htmlspecialchars($cart['product_name']) ?></h5>
                        <p><?= htmlspecialchars($cart['product_detail']) ?></p>
                        <p class="text-muted">Price : ₹<?= $cart['product_price'] ?></p>
                    </div>

                    <div class="col-md-2">

                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">

                            <input type="hidden" name="cart_id" value="<?= $cart['cart_id'] ?>">

                            <input type="number" name="qty" value="<?= $cart['qty'] ?>" min="1" class="form-control mb-2">

                            <button type="submit" name="update_qty" class="btn btn-primary btn-sm w-100"> Update </button>

                        </form>

                    </div>

                    <div class="col-md-2">
                        <strong>Subtotal</strong><br>
                        ₹<?= $subtotal ?>
                    </div>

                    <div class="col-md-2">
                        <a href="cart.php?remove_id=<?= $cart['cart_id'] ?>" type="button" class="text-decoration-none small me-3 Delete">
                            <i class="fa-solid fa-trash"></i> Remove item
                        </a>
                    </div>

                </div>

            <?php } ?>

            <div class="d-flex justify-content-end">
                <div class="card p-3 w-25 border-0">
                    <h4>Total : ₹ <?= $total ?></h4>

                    <a href="../product/orderplace.php"
                        class="btn btn-warning btn-sm ms-2">
                        Order now
                    </a>
                </div>
            </div>


        <?php } else { ?>

            <p>
                No products in cart.

                <a href="../product/products.php" class="btn btn-warning btn-sm ms-2">
                    Start Shopping
                </a>
            </p>

        <?php } ?>

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


    <script>
        $(document).ready(function() {
            $(".clearCart").click(function() {
                return confirm("sure want to clear cart");
            });

            $(".Delete").click(function() {
                return confirm("are you sure want to remove item");
            });


            

            // for toast notification
            setTimeout(() => {
                $('#notification').fadeOut("Fast");
            }, 3000);
        });
    </script>
</body>

</html>