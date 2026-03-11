<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// echo "<pre>";
// print_r($_POST);
// echo "</pre>";

require_once __DIR__ . ('/../utility/db.php');

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
    exit;
}

try {

    $user_id = $_SESSION['user_id'];

    // fetch account detail
    $sql_fetch = "SELECT acc_id, balance, update_at 
                    FROM accounts 
                    WHERE user_id = :user_id";

    $stmt = $connect->prepare($sql_fetch);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // form 
    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['submit'])) {

        // transaction
        if (empty($_POST['transactionType'])) {
            $transactiontypeValid = "transaction is required";
        } else {
            $transactionType = trim($_POST['transactionType']);
        }

        // amount
        if (empty($_POST['amount'])) {
            $amountValid = "amount is required";
        } elseif (intval($_POST['amount']) <= 0) {
            $amountValid = "amount must be greater than 0";
        } else {
            $amount = intval($_POST['amount']);
        }

        // check validation 
        if (!isset($transactiontypeValid) && !isset($amountValid)) {

            // function to insert transaction detail in database after checking transation type
            function addTransaction($connect, $transactionType, $amount, $user_id, $acc_id)
            {
                $sql_insert = "INSERT INTO transactions
                                    (transaction_type, transaction_amount, acc_id, user_id)
                                        VALUES (:type, :amount, :acc_id, :user_id)";

                $stmt = $connect->prepare($sql_insert);
                $stmt->bindParam(':type', $transactionType);
                $stmt->bindParam(':amount', $amount);
                $stmt->bindParam(':acc_id', $acc_id);
                $stmt->bindParam(':user_id', $user_id);

                $stmt->execute();
            }


            // check if value is not greater than bank balance


            // check transaction type
            if ($transactionType === 'debit') {
                // user detail to check if transacrtion amount is not greater than bank balance

                $check_balance = "SELECT acc_id, balance, user_id FROM `accounts` WHERE user_id = :user_id AND balance >= :amount";

                $statment = $connect->prepare($check_balance);

                // bind parameter
                $statment->bindParam(':user_id', $user_id);
                $statment->bindParam(':amount', $amount);

                $statment->execute();

                $user_balance = $statment->fetch(PDO::FETCH_ASSOC);


                // fetching insert transaction detail
                $acc_id = $user['acc_id'];


                // insert transaction detail in db
                // function call


                if ($user_balance) {
                    addTransaction($connect, $transactionType, $amount, $user_id, $acc_id);
                     $_SESSION['notification'] = "<div class='position-fixed top-0 end-0 p-3' style='z-index: 11' id='notification'>
                                                                <div class='toast show'>
                                                                    <div class='toast-header bg-info'>
                                                                        <strong class='me-auto'>Nofitication</strong>
                                                                        <button type='button' class='btn-close' data-bs-dismiss='toast'></button>
                                                                    </div>
                                                                    
                                                                    <div class='toast-body bg-info'>
                                                                        <p> money debit from bank account</p>
                                                                    </div>
                                                                </div>
                                                            </div>";
                header("Location: detail.php");
                exit();

                } else {
                    $_SESSION['notification'] = "<div class='position-fixed top-0 end-0 p-3' style='z-index: 11' id='notification' >
                                                        <div class='toast show'>
                                                            <div class='toast-header bg-warning'>
                                                                <strong class='me-auto text-white'>Nofitication</strong>
                                                                <button type='button' class='btn-close' data-bs-dismiss='toast'></button>
                                                            </div>
                                                            
                                                            <div class='toast-body bg-warning'>
                                                                <p> insufficient bank balance</p>
                                                            </div>
                                                        </div>
                                                    </div>";
                    header("Location: detail.php");
                    exit();
                }
            } elseif ($transactionType === 'credit') {

                $acc_id = $user['acc_id'];

                // function call
                addTransaction($connect, $transactionType, $amount, $user_id, $acc_id);

                $_SESSION['notification'] = "<div class='position-fixed top-0 end-0 p-3' style='z-index: 11' id='notification'>
                                                                <div class='toast show'>
                                                                    <div class='toast-header bg-success'>
                                                                        <strong class='me-auto'>Nofitication</strong>
                                                                        <button type='button' class='btn-close' data-bs-dismiss='toast'></button>
                                                                    </div>
                                                                    
                                                                    <div class='toast-body bg-success'>
                                                                        <p> money credited to bank account</p>
                                                                    </div>
                                                                </div>
                                                            </div>";
                header("Location: detail.php");
                exit();
            } else {
                $_SESSION['notification'] = "<div class='position-fixed top-0 end-0 p-3' style='z-index: 11' id='notification'>
                                                    <div class='toast show'>
                                                        <div class='toast-header bg-warning'>
                                                            <strong class='me-auto'>Nofitication</strong>
                                                            <button type='button' class='btn-close' data-bs-dismiss='toast'></button>
                                                        </div>
                                                            
                                                        <div class='toast-body'>
                                                            <p> transaction fail</p>
                                                        </div>
                                                    </div>
                                                </div>";
                header("Location: detail.php");
                exit();
            }
        }
    }

    // total transaction display 
    $limit = 5;
    // fetch transactions 
    $sql_transactions = "SELECT transaction_id, transaction_type, transaction_amount, update_at
                                FROM transactions
                                WHERE user_id = :user_id
                                ORDER BY update_at DESC
                                LIMIT :transaction_limit
                                ";

    $stmt = $connect->prepare($sql_transactions);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':transaction_limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>account</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- jquery validation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>






</head>

<body>

    <!-- navbar -->

    <?php require_once __DIR__ . ('/../utility/header.php');  ?>


    <!-- card -->

    <div class="container mt-5">

        <div class="row justify-content-center g-4">

            <div class="col-md-3">

                <div class="card bg-info text-white text-center">
                    <div class="card-body">

                        <p class="fs-5">Account Number</p>
                        <i class="fa-regular fa-circle-user"></i>
                        <span class="fs-6">: <?= $user['acc_id'] ?></span>

                    </div>
                </div>

            </div>


            <div class="col-md-3">

                <div class="card bg-primary text-white text-center">
                    <div class="card-body">

                        <p class="fs-5">Current Balance</p>
                        <i class="fa-solid fa-rupee-sign"></i>
                        <span class="fs-6">: <?= $user['balance'] ?></span>

                    </div>
                </div>

            </div>


            <div class="col-md-3">

                <div class="card bg-danger text-white text-center">
                    <div class="card-body">

                        <p class="fs-5">Last Updated</p>
                        <i class="fa-solid fa-gauge"></i>
                        <span class="fs-6">: <?= $user['update_at'] ?></span>

                    </div>
                </div>

            </div>

        </div>

    </div>


    <!-- transaction  -->

    <div class="container mt-5">

        <div class="row g-5">


            <!-- form -->

            <div class="col-md-6">

                <div class="card p-4 border-0 shadow-sm">

                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id="transactionform">

                        <div class="mb-3">

                            <label class="form-label">Transaction Type</label>

                            <select class="form-select" name="transactionType">

                                <option value="credit">Credit</option>
                                <option value="debit">Debit</option>

                            </select>

                            <div class="text-danger mt-1">
                                <?= $transactiontypeValid ?? "" ?>
                            </div>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">Amount</label>

                            <input type="number" name="amount" class="form-control" placeholder="e.g 100"
                                value="<?php echo isset($_POST['amount']) ? $_POST['amount'] : '' ?>">

                            <div class="text-danger mt-1">
                                <label id="amount-error" class="error" for="amount"><?php echo isset($amountValid) ? $amountValid : '' ?></label>
                            </div>


                        </div>


                        <div class="text-center">

                            <button type="submit" name="submit" class="btn btn-dark" value="transaction">
                                Transaction
                            </button>

                        </div>

                    </form>

                </div>

            </div>


            <!-- transaction data -->

            <div class="col-md-6">

                <div class="card p-4 border-0 shadow-sm">

                    <h5 class="mb-3">Transaction History</h5>

                    <div class="table-responsive">

                        <table class="table table-striped table-bordered">

                            <thead class="table-dark">

                                <tr>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php if (!empty($transactions)) { ?>

                                    <?php foreach ($transactions as $row) { ?>

                                        <tr>
                                            <td class="p-3">
                                                <?php if ($row['transaction_type'] == "credit") { ?>

                                                    <span class="bg-success px-2 py-2 my-2 rounded-pill">Credit</span>

                                                <?php } else { ?>

                                                    <span class="bg-danger px-2 py-2 my-2 rounded-pill">Debit</span>

                                                <?php } ?>

                                            </td>

                                            <td class="p-3">₹ <?= $row['transaction_amount'] ?></td>

                                            <td class="p-3"><?= $row['update_at'] ?></td>

                                        </tr>

                                    <?php } ?>

                                <?php } else { ?>

                                    <tr>
                                        <td colspan="4" class="text-center">no transactions found</td>
                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

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


    <script>
        $(document).ready(function() {


            $("#transactionform").validate({
                rules: {
                    transactionType: {
                        required: true,
                    },
                    amount: {
                        required: true
                    }
                },
                messages: {
                    transactionType: {
                        required: "transaction type is required",
                    },
                    amount: {
                        required: "amount is required"
                    }

                },

                submitHandler: function(form) {
                    form.submit();
                }
            });

            // for toast notification
            setTimeout(() => {
                $('#notification').fadeOut("Fast");
            }, 3000);

        });
    </script>
</body>

</html>