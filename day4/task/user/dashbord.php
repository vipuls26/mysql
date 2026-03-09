<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . ('/../utility/db.php');

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
    exit;
}

try {

    $user_id = $_SESSION['user_id'];

    /* Fetch account details */
    $sql_fetch = "SELECT acc_id, balance, update_at 
                  FROM accounts 
                  WHERE user_id = :user_id";

    $stmt = $connect->prepare($sql_fetch);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    /* Handle transaction form */
    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['submit'])) {

        if (empty($_POST['transactionType'])) {
            $transactiontypeValid = "transaction is required";
        } else {
            $transactionType = trim($_POST['transactionType']);
        }

        if (empty($_POST['amount'])) {
            $amountValid = "amount is required";
        } elseif (intval($_POST['amount']) <= 0) {
            $amountValid = "amount must be greater than 0";
        } else {
            $amount = intval($_POST['amount']);
        }

        if (!isset($transactiontypeValid) && !isset($amountValid)) {

            $acc_id = $user['acc_id'];

            $sql_insert = "INSERT INTO transactions
                           (transaction_type, transaction_amount, acc_id, user_id)
                           VALUES (:type, :amount, :acc_id, :user_id)";

            $stmt = $connect->prepare($sql_insert);

            $stmt->bindParam(':type', $transactionType);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':acc_id', $acc_id);
            $stmt->bindParam(':user_id', $user_id);

            $stmt->execute();

            header("Location: dashbord.php");
            exit;
        }
    }

    /* Fetch transactions */
    $sql_transactions = "SELECT transaction_id, transaction_type, transaction_amount, update_at
                         FROM transactions
                         WHERE user_id = :user_id
                         ORDER BY update_at DESC
                         LIMIT 10";

    $stmt = $connect->prepare($sql_transactions);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error : " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

</head>

<body>

    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">

            <a class="navbar-brand" href="./dashbord.php">User Dashboard</a>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Profile
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="../auth/logout.php">Logout</a></li>
                        </ul>

                    </li>

                </ul>
            </div>

        </div>
    </nav>


    <!-- Account Cards -->

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-3">

                <div class="card bg-info text-white">
                    <div class="card-body">

                        <p class="fs-5">Account Number</p>
                        <i class="fa-regular fa-circle-user"></i>
                        <span class="fs-6">: <?= $user['acc_id'] ?></span>

                    </div>
                </div>

            </div>


            <div class="col-md-3">

                <div class="card bg-primary text-white">
                    <div class="card-body">

                        <p class="fs-5">Current Balance</p>
                        <i class="fa-solid fa-rupee-sign"></i>
                        <span class="fs-6">: <?= $user['balance'] ?></span>

                    </div>
                </div>

            </div>


            <div class="col-md-3">

                <div class="card bg-danger text-white">
                    <div class="card-body">

                        <p class="fs-5">Last Updated</p>
                        <i class="fa-solid fa-gauge"></i>
                        <span class="fs-6">: <?= $user['update_at'] ?></span>

                    </div>
                </div>

            </div>

        </div>

    </div>


    <!-- Transaction Section -->

    <div class="container mt-5">

        <div class="row g-5">


            <!-- Transaction Form -->

            <div class="col-md-6">

                <div class="card p-4 border-0 shadow-sm">

                    <form method="post">

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

                            <input type="number" name="amount" class="form-control" placeholder="Enter amount">

                            <div class="text-danger mt-1">
                                <?= $amountValid ?? "" ?>
                            </div>

                        </div>


                        <div class="text-center">

                            <button type="submit" name="submit" class="btn btn-dark">
                                Make Transaction
                            </button>

                        </div>

                    </form>

                </div>

            </div>


            <!-- Transaction Table -->

            <div class="col-md-6">

                <div class="card p-4 border-0 shadow-sm">

                    <h5 class="mb-3">Transaction History</h5>

                    <div class="table-responsive">

                        <table class="table table-striped table-bordered">

                            <thead class="table-dark">

                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php if (!empty($transactions)) { ?>

                                    <?php foreach ($transactions as $row) { ?>

                                        <tr>

                                            <td><?= $row['transaction_id'] ?></td>

                                            <td>

                                                <?php if ($row['transaction_type'] == "credit") { ?>

                                                    <span class="badge bg-success">Credit</span>

                                                <?php } else { ?>

                                                    <span class="badge bg-danger">Debit</span>

                                                <?php } ?>

                                            </td>

                                            <td>₹ <?= $row['transaction_amount'] ?></td>

                                            <td><?= $row['update_at'] ?></td>

                                        </tr>

                                    <?php } ?>

                                <?php } else { ?>

                                    <tr>
                                        <td colspan="4" class="text-center">No transactions found</td>
                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
