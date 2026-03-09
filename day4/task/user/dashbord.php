<?php

session_start();


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . ('/../utility/db.php');

echo '<pre>';
print_r($_POST);
echo '</pre>';

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
}

try {

    if (($_SERVER['REQUEST_METHOD'] === "POST") && (isset($_POST['submit']))) {

        if (empty($_POST['transactionType']) && isset($_POST['transactionType'])) {
            $transactiontypeValid = "transaction is required";
        } else {
           echo $transactionType = trim($_POST['transactionType']);
        }

        if( empty($_POST['amount']) && isset($_POST['amount'])) {
            $amountValid = "amount is required";
        } elseif ( intval($_POST['amount']) <= 0 ) {
            $amountValid = "amount can not be zero";
        } 



    }


    $user_id = $_SESSION['user_id'];
    // fetch data from database for dashboard
    $sql_fetch = 'SELECT `acc_id`, `balance`, `update_at` FROM `accounts` WHERE user_id = :user_id';
    $stmt = $connect->prepare($sql_fetch);

    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // print_r( $user );

} catch (Exception $e) {
    echo 'Error : ' . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>User Dashobard</title>

    <!-- bootstrap  -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB' crossorigin='anonymous'>

    <!-- bootstrap js -->
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js' integrity='sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI' crossorigin='anonymous'></script>

    <!-- fontawesome -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css' />

    <!-- jquery validation -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- jquery validation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


</head>

<body>

    <!-- nav bar -->
    <nav class='navbar navbar-expand-lg navbar-dark bg-dark'>
        <div class='container-fluid'>
            <a class='navbar-brand' href='./dashbord.php'>User Dashboard</a>
            <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>
                <span class='navbar-toggler-icon'></span>
            </button>
            <div class='collapse navbar-collapse' id='navbarNav'>
                <ul class='navbar-nav ms-md-auto gap-2'>

                    <li class='nav-item dropdown rounded'>
                        <a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-bs-toggle='dropdown' aria-expanded='false'><i class='bi bi-person-fill me-2'></i>Profile</a>
                        <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='navbarDropdown'>

                            <li><a class='dropdown-item' href='../auth/logout.php'>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- container -->
    <div class='container mt-5'>

        <div class='row d-flex justify-content-center'>

            <div class='col-md-3'>
                <div class='card bg-info'>
                    <div class='card-body'>
                        <p class='balance fs-4'>Account number</p>
                        <i class='fa-regular fa-circle-user text-light'></i>
                        <span class='count-numbers fs-5 text-light'>: <?= $user['acc_id'] ?></span>
                    </div>
                </div>
            </div>

            <div class='col-md-3'>
                <div class='card bg-primary'>
                    <div class='card-body'>
                        <p class='balance fs-4'>Current Balance:</p>
                        <i class='fa-solid fa-rupee-sign text-light'></i>
                        <span class='count-numbers fs-5 text-light'>: <?= $user['balance'] ?></span>
                    </div>
                </div>
            </div>

            <div class='col-md-3'>
                <div class='card bg-danger'>
                    <div class='card-body'>
                        <p class='balance fs-4'>Last updated balance:</p>
                        <i class='fa-solid fa-gauge text-light'></i>
                        <span class='count-numbers fs-5 text-light'>: <?= $user['update_at'] ?></span>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- form for transction -->

    <div class='container mt-5'>


        <div class="row g-5">

            <!-- transaction form -->
            <div class="col-12 col-md-6">
                <div class="card px-3 py-3 border-0">
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method='post' id="formID">

                        <div class='row g-4'>
                            <div class='col-12'>
                                <label for="transactionType">Transaction type : <span class="text-danger">*</span></label>
                                <select class='form-select border-secondary' name='transactionType' id='transactionType'>
                                    <option value='credit'>Credit</option>
                                    <option value='debit'>Debit</option>
                                </select>

                                 <div class="text-danger mt-2">
                                    <label id="transactionType-error" class="error" for="transactionType"><?php echo isset($transactiontypeValid) ? $transactiontypeValid : '' ?></label>
                                </div>
                            </div>


                            <div class="col-12">
                                <label for="amount"> Amount : <span class="text-danger">*</span> </label>
                                <input type="number" name="amount" id="amount" class="form-control border-secondary" placeholder="e.g 100">

                                <div class="text-danger mt-2">
                                    <label id="amount-error" class="error" for="amount"><?php echo isset($amountValid) ? $amountValid : '' ?></label>
                                </div>
                            </div>

                            <div class="text-center">
                                <input type='submit' value='transaction' name='submit' class="btn btn-dark">
                            </div>

                        </div>

                    </form>
                </div>

            </div>


            <!-- transaction table -->
            <div class="col-12 col-md-6">
                <div class="card px-3 py-3 border-0">
                    transcation
                </div>

            </div>

        </div>

    </div>
    <!-- <script>
        $(document).ready(function() {
            
            $.validator.addMethod("num", function(value, element) {
            
                return this.optional(element) || ($.isNumeric(value) && parseFloat(value) > 0);
            }, "amount can not be 0 or less than 0");


            $("#formID").validate({

                rules: {
                    transactionType: {
                        required: true
                    },
                    amount: {
                        required: true,
                        num: true
                    }
                },

                messages: {
                    transactionType: {
                        required: "transaction type is required",
                    },
                    amount: {
                        required: "amount is required",
                    }

                },

                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script> -->
</body>

</html>