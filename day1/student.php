<?php

require_once __DIR__ . ("/db.php");

// select data from table
$sql_select = "SELECT * FROM students";

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
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Age</th>
                    <th>Action</th>
                </tr>
                <?php
                while ($row = $student->fetch()) { ?>
                    <tr>
                        <td> <?= $row['student_id']; ?> </td>
                        <td><?= $row['name']; ?></td>
                        <td> <?= $row['email']; ?></td>
                        <td> <?= $row['age']; ?></td>  
                       <td>
                            <a href="delete.php?student_id=<?=$row['student_id'] ?>" 
                            class="btn btn-danger" 
                            onclick="return deleteuser();">
                            Delete student
                            </a>
                        </td>

                    </tr>
                <?php
                }
                ?> </table>
        <?php  } else {
            echo "no record found";
        } ?>
    </div>
    </div>


   <script>
        function deleteuser() {
            return confirm('Are you sure you want to delete this student?');
        }
</script>


</body>

</html>