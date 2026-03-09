<?php

require_once __DIR__ . ("/db.php");

// select data from table
//$sql_select = "SELECT * FROM books";

// sql join for student name
$sql_select = "SELECT books.book_id, books.book_title, books.book_author, books.book_category, books.created_at, students.name 
                FROM books 
                LEFT JOIN students ON books.student_id = students.student_id;";

$student = $connect->query($sql_select);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-5">

    <a href="book.php" class="btn btn-dark">Issue book</a>
    <a href="student.php" class="btn btn-info">See all student</a>
    <a href="dashboard.php" class="btn btn-warning">Dashboard</a>

        <?php if ($student->rowCount() > 0) { ?>
            <table class="table">
                <tr>
                    <th>Book id</th>
                    <th>Book title</th>
                    <th>Book author</th>
                    <th>Category</th>
                    <th>created_at</th>
                    <th>student name</th>
                </tr>
                <?php
                while ($row = $student->fetch()) { ?>
                    <tr>
                        <td> <?= $row['book_id']; ?> </td>
                        <td><?= $row['book_title']; ?></td>
                        <td> <?= $row['book_author']; ?></td>
                        <td> <?= $row['book_category']; ?></td>  
                        <td> <?= $row['created_at']; ?></td>  
                        <td> <?= $row['name']; ?></td>  

                    </tr>
                <?php
                }
                ?> </table>
        <?php  } else {
            echo "no record found";
        } ?>



     <!-- <a href="" class="btn btn-warning">Dashboard</a> -->
    </div>
    </div>

</body>

</html>