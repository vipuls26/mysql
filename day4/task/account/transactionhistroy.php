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


    // total transaction display 
    $limit = 5;
    // fetch transactions 
    $sql_transactions = "SELECT transaction_id, transaction_type, transaction_amount, update_at
                                    FROM transactions
                                    WHERE user_id = :user_id
                                    ORDER BY update_at DESC";

    $stmt = $connect->prepare($sql_transactions);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
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


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <!-- navbar -->

    <?php require_once __DIR__ . ('/../utility/header.php');  ?>

    <div class="container mt-3">

        <!-- back button -->
        <a href="detail.php" class="btn btn-dark mt-2">Back button</a>
        <!-- transaction data -->

        <div class="col-12">

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



</body>

</html>