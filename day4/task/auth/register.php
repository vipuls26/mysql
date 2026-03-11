<?php

    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();

     if (isset($_SESSION['email'])) {
        header('Location: ../user/dashboard.php');
        exit;
    }

    require_once __DIR__ . ('/../utility/db.php');

    if (($_SERVER['REQUEST_METHOD'] === "POST") && (isset($_POST['submit']))) {

        //name
        if (empty($_POST['name']) && isset($_POST['name'])) {
            $name_validation = "name is required";
        } elseif (strlen($_POST['name']) < 2) {
            $name_validation = "mininmux 2 character required";
        } else {
            $name = trim($_POST['name']);
        }

        // email
        if (empty($_POST['email']) && isset($_POST['email'])) {
            $email_validation = "email is required";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $email_validation = "please enter valid email";
        } else {
            $email = trim($_POST['email']);
        }

        //password
        if (empty($_POST['password']) && isset($_POST['password'])) {
            $password_validation = "password is required";
        } elseif (strlen($_POST['password']) < 5) {
            $password_validation = "password at least 5 characters";
        } else {
            $password = trim($_POST['password']);
        }

        try {

            if (!empty($email)) {

                // check email already exists
                $sql_select = "SELECT email FROM users WHERE email = :email";
                $stmt = $connect->prepare($sql_select);
                $stmt->bindParam(":email", $email);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {

                    $email_validation = "this email already exist";
                } else {

                    // insert user data
                    $sql_insert = "INSERT INTO users (name,email,password)
                            VALUES (:name,:email,:password)";

                    $stmt = $connect->prepare($sql_insert);

                    $password_hash = password_hash($password, PASSWORD_DEFAULT);

                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $password_hash, PDO::PARAM_STR);

                    $stmt->execute();

                    $_SESSION['user_email'] = $email;


                    header("Location: generateaccount.php");
                    exit;
                }
            }
        } catch (Exception $e) {
            echo "Error : " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- jquery validation -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- jquery validation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


</head>

<body>
    <div class="container mt-5">

        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="registerform">
            <div class="row justify-content-center">
                <div class="title text-center mb-5 mt-5">register to system</div>
                <div class="col-6">
                    <div class="form-floating mb-3">
                        <input type="text" name="name" id="name" class="form-control border-secondary" placeholder="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>">
                        <label for="name">Name</label>

                        <div class="error mt-2 text-danger">
                            <label id="name-error" class="error" for="name"><?php echo isset($name_validation) ? $name_validation : ''; ?></label>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" name="email" id="email" class="form-control border-secondary" placeholder="email" value="<?php echo isset($_POST['email']) ? trim($_POST['email']) : '' ?>">
                        <label for="email">Email</label>
                        <div class="error mt-2 text-danger">
                            <label id="email-error" class="error" for="email"><?php echo isset($email_validation) ? $email_validation : ''; ?></label>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password" id="password" class="form-control border-secondary" placeholder="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '' ?>">
                        <label for="password">Password</label>
                        <div class="error mt-2 text-danger">
                            <label id="password-error" class="error" for="password"><?php echo isset($password_validation) ? $password_validation : ''; ?></label>
                        </div>
                    </div>

                    <div class="text-center">
                        <input type="submit" value="register" name="submit" class="btn btn-dark">
                    </div>

                    <div class="text-center mt-2">
                        <p>Already have account! <a href="./login.php" class="text-decoration-none">Login</a></p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {


            $("#registerform").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                },
                messages: {
                    name: {
                        required: "name is required",
                        minlength: "mininmux 2 character required"
                    },
                    email: {
                        required: "email is required",
                        email: "please enter a valid email address"
                    },
                    password: {
                        required: "password is required",
                        minlength: "password at least 5 characters"
                    },
                },

                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>