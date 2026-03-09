<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . ('/db.php');

echo "<pre>";
print_r($_POST);
echo "</pre>";



// form submit
if ($_SERVER['REQUEST_METHOD'] === "POST" && $_POST['submit']) {

    // title
    if (isset($_POST['title']) && empty($_POST['title'])) {
        $titleflag = false;
    } else {
        $titleflag = true;
        $title = htmlspecialchars(trim($_POST['title']));
    }

    if (isset($_POST['author']) && empty($_POST['author'])) {
        $authorflag = false;
    } else {
        $authorflag = true;
        $author = htmlspecialchars(trim($_POST['author']));
    }

    // category
    if (isset($_POST['category']) && empty($_POST['category'])) {
        $categoryflag = false;
    } else {
        $categoryflag = true;
        $category = htmlspecialchars(trim($_POST['category']));
    }

    // student email
    if (isset($_POST['student_email']) && empty($_POST['student_email'])) {
        $student_emailflag = false;
    } else {
        $student_emailflag = true;
        $student_email = htmlspecialchars(trim($_POST['student_email']));
    }

    // condition
    if ($titleflag && $authorflag && $categoryflag && $student_emailflag) {

        // echo "step ahead";
        // fetching student_id 
        $sql_email = "SELECT student_id FROM students WHERE email = '$student_email'";
        $res = $connect->query($sql_email);


        if ($res->rowCount() > 0) {
            while ($row = $res->fetch()) {
                $student_id = $row['student_id'];
            }
        }


        try {

            // book data insert in databse
            $sql_book = "INSERT INTO `books`(`book_title`, `book_author`, `book_category`,`student_id`) 
                                        VALUES (:title,:author,:category,:student_id)";

            $stmt = $connect->prepare($sql_book);

            
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':author', $author, PDO::PARAM_STR);
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);

            $stmt->execute();

            header("Location: ./dashboard.php");

        } catch (Exception $e) {
            echo "Error : " . $e->getMessage();
        } 
    }
}









?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>book</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB' crossorigin='anonymous'>
</head>

<body>

    <div class='container mt-5'>

        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

            <label for='bookname' class='form-label'>Book title</label>
            <select class='form-select' aria-label='book' name="title">
                <option name='The Speckled Band' selected>The Speckled Band</option>
                <option name='the Signalman'>The Signalman</option>
                <option name='Boy at Seven'> Boy at Seven</option>
                <option name='A Tale of Two Cities'>A Tale of Two Cities</option>
            </select>

            <label for='bookname' class='form-label'>Book author</label>
            <select class='form-select' aria-label='book' name="author">
                <option name='Charles Dickens' selected>Charles Dickens</option>
                <option name='John Bidwell'>John Bidwell</option>
                <option name='Charles Dickens'>Charles Dickens</option>
            </select>

            <label for='bookname' class='form-label'>Book category</label>
            <select class='form-select' name="category" aria-label='book'>
                <option name='Suspense' selected>Suspense</option>
                <option name='crime'>crime</option>
                <option name='Historical Fiction'>Historical Fiction</option>
            </select>

            <label for='bookname' class='form-label'>student name</label>
            <?php
            $sql_select = 'SELECT email , name FROM students';
            $student = $connect->query($sql_select);

            if ($student->rowCount() > 0) {

                echo "<select class='form-select' name='student_email' aria-label='student'>";
                while ($row = $student->fetch()) {
                    echo "<option name='" . $row['name'] . "'>" . $row['email'] . "</option>";
                }
                echo "</select>";
            }
            ?>
            <div class="text-center mt-3">
                <input type="submit" value="submit" name="submit" class="btn btn-dark">
            </div>

        </form>
    </div>

</body>

</html>