<?php
session_start();

if(isset($_SESSION['zalogowany']) && $_SESSION['zalogowany']) {
    header('Location: panel.php');
    exit();
}

require_once('connect.php');
require_once('password.php');

if(isset($_POST['send'])) {
    $email = trim(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
    $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));

    $sql = "SELECT * FROM users WHERE email = :email";
    $statment = $pdo->prepare($sql);
    $statment->bindValue(':email', $email);
    $statment->execute();

    $user = $statment->fetch(PDO::FETCH_ASSOC);

    if($user === false) {
        $error = '<div class="alert alert-danger" role="alert">Podany email lub hasło jest nieprawidłowe <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
    else {
        $validPassword = password_verify($password, $user['haslo']);

        if($validPassword) {
            $_SESSION['zalogowany'] = true;
            $_SESSION['salon'] = $user['salon'];
            $_SESSION['email'] = $user['email'];

            header('Location: panel.php');
            exit();
        }
        else {
            $error = '<div class="alert alert-danger" role="alert">Nieprawidłowy login lub hasło<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        }
    }
}
?>
<!doctype html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>Signin Template · Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
  </head>
  <body>
	<div class="container login-container">
        <div class="row">
            <div class="col-md-6 login-form-2 mx-auto">
                <h3>Zaloguj się</h3>
                <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                    <?php
                        if(isset($error)) {
                            echo $error;
                            unset($error);
                        }
                    ?>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Podaj email" name="email" value="<?php if(isset($email)) echo $email; ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Podaj hasło" name="password">
                    </div>
                    <div class="form-group mx-auto">
                        <input type="submit" class="btnSubmit" value="Zaloguj się" name="send">
                    </div>
                    <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <a href="forget.php" class="ForgetPwd">Zapomnaiłes hasła?</a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <a href="register.php" class="ForgetPwd">Utwórz konto</a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <a href="index.php" class="ForgetPwd">Strona główna</a>
                        </div>
                    </div>
                    </div>
                </form>
               
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="lib/bootstrap/js/vendor/popper.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace();
    </script>

    <!-- Graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
	</body>
</html>
