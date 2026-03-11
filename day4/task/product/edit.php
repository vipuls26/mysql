<?php

session_start();

// echo "<pre>";
// print_r($_GET);
// print_r($_POST);
// print_r($_FILES);
// echo "</pre>";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . ('/../utility/db.php');

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
    exit;
}

// fetch product detail from db
if (isset($_GET['product_id'])) {

    $product_id = intval($_GET['product_id']);

    $sql_select_data = "SELECT `product_id`, `product_name`, `product_detail`, `product_price`, `product_image`, `create_at`, `user_id` FROM `products` WHERE `product_id` = :product_id";
    $statment = $connect->prepare($sql_select_data);

    $statment->bindParam(':product_id', $product_id, PDO::PARAM_INT);

    $statment->execute();

    $product = $statment->fetch(PDO::FETCH_ASSOC);

    print_r($product);
}

if (($_SERVER['REQUEST_METHOD'] === "POST") && (isset($_POST['submit']))) {

    // product id for update record

    $product_update_id = $_POST['product_id'];

    // product name
    if (empty($_POST['product_name'])) {
        $productValid = "product name is required";
    } elseif (strlen($_POST['product_name']) < 3) {
        $productValid = "minimum 3 character required";
    } else {
        $product_name = trim(htmlspecialchars($_POST['product_name']));
    }


    // product detail 
    if (empty($_POST['product_detail'])) {
        $product_detailValid = "product detail are required";
    } elseif (strlen($_POST['product_detail']) < 5) {
        $product_detailValid = "minimum 5 character required";
    } else {
        $product_detail = trim(htmlspecialchars($_POST['product_detail']));
    }

    // product price 
    if (empty($_POST['product_price'])) {
        $product_priceValid = "price is required";
    } elseif (is_nan($_POST['product_price'])) {
        $product_priceValid = "price must be in decimal";
    } else {
        $product_price = trim(htmlspecialchars($_POST['product_price']));
    }

    // product image 

    if (isset($_FILES['product_img'])) {

        $file = $_FILES["product_img"]; // image imaformation

        $allowed_extension = array("png", "jpg", "jpeg");  // allowed extension

        $file_name = $file["name"]; // image name
        $file_size = $file["size"]; // image size
        $file_extension = $file["type"]; // image type

        // target directory
        $target_dir = "../asset/uploads/";

        //target file
        $target_file =  $target_dir . $_FILES["product_img"]["name"];

        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // max file size 
        $max_size = 5 * 1024 * 1024;


        if (($_FILES["product_img"]["size"] > $max_size)) {
            $product_imageValid = "image size should be smaller than 5 mb";
        } elseif (!in_array($file_ext, $allowed_extension)) {
            $product_imageValid = "only png jpg jpeg format are allowed";
        } elseif (empty($_FILES)) {
            echo $product_image = $_POST['product_image'];
        } else {
            echo $product_image = $file_name;
            move_uploaded_file($_FILES["product_img"]["tmp_name"], $target_file);
        }
    }


    if (!isset($productValid) && !isset($product_detailValid) && !isset($product_priceValid)) {

        try {
            // userid from session 

            echo $user_id = intval($_SESSION['user_id']);
            echo $product_update_id;


            if (!empty($_FILES['product_img']['name'])) {
                $sql_update_product = "UPDATE `products` 
                                            SET `product_name`=:product_name,
                                                `product_detail`=:product_detail,
                                                `product_price`=:product_price,
                                                `product_image`=:product_image,
                                                `user_id`=:user_id 
                                                    WHERE `product_id` = :product_id";

                $stmt = $connect->prepare($sql_update_product);

                $stmt->bindParam(':product_id', $product_update_id, PDO::PARAM_INT);
                $stmt->bindParam(':product_name', $product_name, PDO::PARAM_STR);
                $stmt->bindParam(':product_detail', $product_detail, PDO::PARAM_STR);
                $stmt->bindParam(':product_price', $product_price, PDO::PARAM_INT);
                $stmt->bindParam(':product_image', $product_image, PDO::PARAM_STR);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            } else {
                $sql_update_product = "UPDATE `products` 
                                            SET `product_name`=:product_name,
                                                `product_detail`=:product_detail,
                                                `product_price`=:product_price,
                                                `user_id`=:user_id 
                                                    WHERE `product_id` = :product_id";

                $stmt = $connect->prepare($sql_update_product);

                $stmt->bindParam(':product_id', $product_update_id, PDO::PARAM_INT);
                $stmt->bindParam(':product_name', $product_name, PDO::PARAM_STR);
                $stmt->bindParam(':product_detail', $product_detail, PDO::PARAM_STR);
                $stmt->bindParam(':product_price', $product_price, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            }



            $stmt->execute();

            $_SESSION['notification'] = "<div class='position-fixed top-0 end-0 p-3' style='z-index: 11' id='notification'>
                                                    <div class='toast show'>
                                                        <div class='toast-header bg-info'>
                                                            <strong class='me-auto'>Nofitication</strong>
                                                            <button type='button' class='btn-close' data-bs-dismiss='toast'></button>
                                                        </div>
                                                            
                                                        <div class='toast-body bg-info'>
                                                            <p> product updated</p>
                                                        </div>
                                                    </div>
                                                </div>";

            header("Location: ../product/myproduct.php");
            exit();
        } catch (Exception $e) {
            echo "Error : " . $e->getMessage();
        } finally {
            $stmt = null;
            $connect = null;
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit product</title>
    <!-- bootstrap  -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB' crossorigin='anonymous'>

    <!-- bootstrap js -->
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>

    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <!-- jquery  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- jquery validation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



</head>

<body>

    <!-- navbar -->

    <?php require_once __DIR__ . ('/../utility/header.php');  ?>



    <div class="container mt-5">

        <p class="fw-bold fs-4 text-center">Edit product</p>
        <div class="row g-3">


            <div class="card border-0 mx-auto">
                <div class="d-flex justify-content-center">

                    <?php if (!empty($product)) { ?>

                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id="productAdd" enctype="multipart/form-data">

                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">



                            <div class="col-12">
                                <label for="product_name" class="form-label">Product: <span class="text-danger">*</span> </label>
                                <input type="text" name="product_name" id="product_name" class="form-control border-secondary" placeholder="e.g product name" value="<?= $product['product_name'] ?>">

                                <div class="text-danger">
                                    <label id="product_name-error" class="error" for="product_name">
                                        <?php echo isset($productValid) ? $productValid : '' ?>
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="product_img" class="form-label">Image: </label>
                                <input type="file" name="product_img" id="product_img" class="form-control border-secondary" value="<?= $product['product_image'] ?>">

                                <input hidden="product_image" name="product_image" value="<?= $product['product_image'] ?>">
                                <div class="text-danger">
                                    <label id="product_img-error" class="error" for="product_img">
                                        <?php echo isset($product_imageValid) ? $product_imageValid : '' ?>
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="product_detail" class="form-label">Detail: <span class="text-danger">*</span> </label>
                                <textarea type="text" name="product_detail" id="product_detail" class="form-control  border-secondary" placeholder="e.g product detail"><?= $product['product_detail'] ?></textarea>

                                <div class="text-danger">
                                    <label id="product_detail-error" class="error" for="product_detail">
                                        <?php echo isset($product_detailValid) ? $product_detailValid : '' ?>
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="product_price" class="form-label">Price: </label>
                                <input type="number" name="product_price" id="product_price" class="form-control border-secondary" placeholder="e.g product price" value="<?= $product['product_price'] ?>">

                                <div class="text-danger">
                                    <label id="product_price-error" class="error" for="product_price">
                                        <?php echo isset($product_priceValid) ? $product_priceValid : '' ?>
                                    </label>
                                </div>

                            </div>

                            <div class="col-12 mt-3 text-center">
                                <input type="submit" name="submit" id="submit" class="btn btn-dark" value="Update">
                                <a class="btn btn-warning" href="../product/myproduct.php">cancel</a>
                            </div>

                        </form>

                    <?php } ?>

                </div>

            </div>
        </div>

       

    </div>

    <script>
        $(document).ready(function() {
            $("#productAdd").validate({
                rules: {
                    product_name: {
                        required: true,
                        minlength: 3
                    },
                    product_detail: {
                        required: true,
                        minlength: 5
                    },
                    product_price: {
                        required: true,
                    }

                },
                messages: {
                    product_name: {
                        required: "name is required",
                        minlength: "minimum 3 character are required"
                    },
                    product_detail: {
                        required: "product detail is requried",
                        minlength: "minimum 5 character are required"
                    },
                    product_price: {
                        required: "price is required",
                    }

                },

                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>