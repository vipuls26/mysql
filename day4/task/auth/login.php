<?php

    session_start();

    if (isset($_SESSION['email'])) {
    header('Location: ../user/dashboard.php');
    exit;
}

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";
    require_once __DIR__ . ('/../utility/db.php');

if (($_SERVER['REQUEST_METHOD'] === "POST") && (isset($_POST['submit']))) {


    // email
    if (empty($_POST['email']) && isset($_POST['email'])) {
        $email_validation = "email is required";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email_validation = "please enter valid email";
    } else {
        echo $email = trim($_POST['email']);
    }

    // password
    if (empty($_POST['password']) && isset($_POST['password'])) {
        $password_validation = "password is required";
    } elseif (strlen($_POST['password']) < 5) {
        $password_validation = "password at least 5 characters";
    } else {
        $password = trim($_POST['password']);
    }

    if (!empty($email) && !empty($password)) {

        try {

            // select user with same email
            $sql_qry = "SELECT * FROM `users` WHERE email = :email";
            // $sql_select =  "SELECT name , email, password, role FROM users where email = :email";
            $stmt = $connect->prepare($sql_qry);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user['email'] != $email) {
                $email_validation =  "email is not register";
            } else {

                if ($user && password_verify($password, $user['password'])) {

                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_id'] = $user['user_id'];
                    header("Location: ../user/dashboard.php");
                    
                } else {
                    $password_validation = "password is wrong";
                }
            }
        } catch (Exception $e) {
            echo "Error : " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    
    <!-- bootstrap  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- jquery validation -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- jquery validation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



</head>

<body>
    <div class="container mt-5">

        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="loginform">
            <div class="row d-flex justify-content-center">
                <div class="title text-center mb-5">Login to sytem</div>
                <div class="col-6">


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
                        <input type="submit" value="login" name="submit" class="btn btn-dark">
                    </div>

                    <div class="text-center mt-2">
                        <p>Don't have account! <a href="./register.php" class="text-decoration-none">Register</a></p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {


            $("#loginform").validate({
                rules: {
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