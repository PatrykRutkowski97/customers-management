<?php
session_start();

if(isset($_SESSION['zalogowany']) && $_SESSION['zalogowany']) { // sprawdzanie czy użytkownik nie jest już przypadkiem zalogowany
    header('Location: panel.php');
    exit();
}

require_once('connect.php');
require_once('password.php');

if(isset($_POST['add_user'])) {
    $error = false;
    $error_email = '';
    $error_name = '';
    $error_surname = '';
    $error_official = '';
    $error_city = '';
    $error_sex = '';
    $error_password = '';
    //--------------- Validate form inputs ---------------------------
    $email = trim(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
    $name = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $surname = trim(filter_var($_POST['surname'], FILTER_SANITIZE_STRING));
    $officiall_name = trim(filter_var($_POST['officiall_name'], FILTER_SANITIZE_STRING));
    $city = trim(filter_var($_POST['city'], FILTER_SANITIZE_STRING));
    $sex = trim(filter_var($_POST['sex'], FILTER_SANITIZE_STRING));
    $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
    $password2 = trim(filter_var($_POST['password2'], FILTER_SANITIZE_STRING));
    // -------------------------------------------------------------
    $sql = "SELECT COUNT(email) AS num FROM users WHERE email = :email";
    $statment = $pdo->prepare($sql);
    $statment->bindValue(':email', $email);
    $statment->execute();

    $row = $statment->fetch(PDO::FETCH_ASSOC);

    if($row['num'] > 0) {
        $error_email = '<div class="alert alert-danger" role="alert">Podany email jest już zajęty <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }

    if(empty($email)) {
        $error_email = '<div class="alert alert-danger" role="alert">Podany email jest nieprawidłowy <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }

    if(empty($name) || !$name) {
        $error_name = '<div class="alert alert-danger" role="alert">Podane imie jest nieprawidłowe <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }

    if(empty($surname) || !$surname) {
        $error_surname = '<div class="alert alert-danger" role="alert">Podane nazwisko jest nieprawidłowe <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }

    if(empty($officiall_name) || !$officiall_name) {
        $error_official = '<div class="alert alert-danger" role="alert">Podana nazwa salonu jest nieprawidłowa <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }

    if(empty($city) || !$city) {
        $error_city = '<div class="alert alert-danger" role="alert">Podane miasto jest nieprawidłowe <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }

    if(empty($sex) || !$sex) {
        $error_sex = '<div class="alert alert-danger" role="alert">Podana płeć jest nieprawidłowa <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }

    if(empty($sex) || !$sex) {
        $error_sex = '<div class="alert alert-danger" role="alert">Podana płeć jest nieprawidłowa <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }

    if($password !== $password2) {
        $error_password = '<div class="alert alert-danger" role="alert">Podane hasła muszą być identyczne <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }

    if(strlen($password) < 6) {
        $error_password = '<div class="alert alert-danger" role="alert">Podane hasło jest zbyt krótke. Minimum 6 znaków <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }

    if(empty($password) || empty($password2)) {
        $error_password = '<div class="alert alert-danger" role="alert">Podane hasło jest nieprawidłowe <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }

    if($error == false) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, array("cost" => 12));

        $sql = "INSERT INTO users (email, imie, nazwisko, salon, miasto, plec, haslo) VALUES (:email, :imie, :nazwisko, :salon, :miasto, :plec, :haslo)";
        $statment = $pdo->prepare($sql);
        $statment->bindValue(':email', mb_strtolower($email, 'UTF-8'));
        $statment->bindValue(':imie', mb_strtolower($name, 'UTF-8'));
        $statment->bindValue(':nazwisko', mb_strtolower($surname, 'UTF-8'));
        $statment->bindValue(':salon', mb_strtolower($officiall_name, 'UTF-8'));
        $statment->bindValue(':miasto', mb_strtolower($city, 'UTF-8'));
        $statment->bindValue(':plec', mb_strtolower($sex, 'UTF-8'));
        $statment->bindValue(':haslo', $passwordHash);
        
        $result = $statment->execute();

        if($result) {
            $add_user = '<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="alert-heading">Gratulacje!</h4><p>Twoje konto zostało prawidłowo zarejestrowane w serwisie HairSystem.</p><hr><p class="mb-0">Możesz się już zalgować.</p></div>';
            $email = '';
            $name = '';
            $surname = '';
            $officiall_name = '';
            $city = '';
            $sex = '';
            $password = '';
            $password2 = '';
        }
    }

};
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
                <h3>Zarejestruj się</h3>
                <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                    <?php
                        if(isset($add_user)) {
                            echo $add_user;
                            unset($add_user);
                        }
                        if(isset($error_email)) {
                            echo $error_email;
                            unset($error_email);
                        }
                        if(isset($error_name)) {
                            echo $error_name;
                            unset($error_name);
                        }
                        if(isset($error_surname)) {
                            echo $error_surname;
                            unset($error_surname);
                        }
                        if(isset($error_official)) {
                            echo $error_official;
                            unset($error_official);
                        }
                        if(isset($error_sex)) {
                            echo $error_sex;
                            unset($error_sex);
                        }
                        if(isset($error_city)) {
                            echo $error_city;
                            unset($error_city);
                        }
                        if(isset($error_password)) {
                            echo $error_password;
                            unset($error_password);
                        }
                    ?>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Podaj email" name="email" id="email" value="<?php if(isset($email)) echo $email; ?>">
                    </div>
                    <div class="form-group">
                            <input type="text" class="form-control" placeholder="Podaj imię" name="name" id="name" value="<?php if(isset($name)) echo $name; ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Podaj nazwisko" name="surname" id="surname" value="<?php if(isset($surname)) echo $surname; ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Podaj nazwę salonu" name="officiall_name" id="officiall_name" value="<?php if(isset($officiall_name)) echo $officiall_name; ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Podaj miasto" name="city" id="city" value="<?php if(isset($city)) echo $city; ?>">
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="sex" id="sex">
                            <option>Kobieta</option>
                            <option>Mężczyzna</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Podaj hasło" name="password" id="password">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Powtórz hasło" name="password2" id="password2">
                        </div>
                    <div class="form-group mx-auto">
                        <input type="submit" class="btnSubmit" value="Utwórz konto" name="add_user">
                    </div>
                    <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <a href="forget.php" class="ForgetPwd">Zapomnaiłes hasła?</a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <a href="login.php" class="ForgetPwd">Zaloguj się</a>
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
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="lib/bootstrap/js/bootstrap.js"></script>
        <script>
            $(".alert").alert('close');
        </script>
	</body>
</html>
