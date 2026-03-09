<?php

    require_once __DIR__ . ("/db.php");

    echo $delete = intval(htmlspecialchars(trim($_GET['student_id'])));

    try {
           // delete query
    $delete_sql = "DELETE FROM `students` WHERE student_id = $delete";

    $connect->exec($delete_sql);
    header("Location: ./student.php");
    } catch ( Exception $e ){
        echo "Error : " .$e->getMessage();
    }
 


?>