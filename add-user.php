<?php
session_start();

if(!isset($_SESSION['zalogowany']) && !$_SESSION['zalogowany']) {
    header('Location: login.php');
    exit();
}

if(isset($_POST['send'])) {
    require_once('connect.php');
    // ------ Errors ----------
    $error = false;
    $error_name = '';
    $error_surname = '';
    $error_phone = '';
    // -------- Validate form ---------
    $name = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $surname = trim(filter_var($_POST['surname'], FILTER_SANITIZE_STRING));
    $phone = trim(filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT));
    // ---------------------------------
    $sql = "SELECT COUNT(nazwisko) AS num FROM klienci WHERE nazwisko = :nazwisko AND telefon = :telefon AND imie = :imie";
    $statment = $pdo->prepare($sql);
    $statment->bindValue(':nazwisko', mb_strtolower($surname, 'UTF-8'));
    $statment->bindValue(':telefon', mb_strtolower($phone, 'UTF-8'));
    $statment->bindValue(':imie', mb_strtolower($name, 'UTF-8'));
    $statment->execute();

    $row = $statment->fetch(PDO::FETCH_ASSOC);

    if($row['num'] > 0) {
        $error_user = '<div class="alert alert-danger" role="alert">W bazie istnieje już taki klient <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }
    if(empty($name) || !is_string($name)) {
        $error_name = '<div class="alert alert-danger" role="alert">Nieprawidłowe imię <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }
    if(empty($surname)) {
        $error_surname = '<div class="alert alert-danger" role="alert">Nieprawidłowe nazwisko <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }
    if(empty($phone) || strlen($phone) != 9) {
        $error_phone = '<div class="alert alert-danger" role="alert">Nieprawidłowy numer telefonu <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }
    
    if($error == false) {
        $sql = "INSERT INTO klienci (imie,nazwisko,telefon,wizyty,zaklad) VALUES (:imie,:nazwisko,:telefon,:wizyty,:zaklad)";
        $statment = $pdo->prepare($sql);
        $statment->bindValue(':imie',mb_strtolower($name, 'UTF-8'));
        $statment->bindValue(':nazwisko',mb_strtolower($surname, 'UTF-8'));
        $statment->bindValue(':telefon',$phone);
        $statment->bindValue(':wizyty',0);
        $statment->bindValue(':zaklad',$_SESSION['email']);

        $result = $statment->execute();

        if($result) {
            $add_user = '<div class="alert alert-success" role="alert">Dodano '. $name . ' ' . $surname .' do stałych klientów <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
            $name = '';
            $surname = '';
            $phone = '';
        }
        else {
            $add_user = '<div class="alert alert-danger" role="alert">Nie można dodać '. $name . ' ' . $surname .' do stałych klientów. <b>Spróbuj jeszcze raz !</b> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
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
    <meta name="author" content="">

    <title>HairSystem - zarządzaj klientami</title>

    <!-- Bootstrap core CSS -->
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
    <style>
        .red {
            color: #F00;
        }
    </style>
  </head>

  <body>
  
  <?php include_once('navbar-login.php'); ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                  <h1 class="h2">Dodaj stałego klienta</h1>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <?php
                    if(isset($add_user)) {
                        echo $add_user;
                        unset($add_user);
                    }
                    if(isset($error_name)) {
                        echo $error_name;
                        unset($error_name);
                    }
                    if(isset($error_surname)) {
                        echo $error_surname;
                        unset($error_surname);
                    }
                    if(isset($error_phone)) {
                        echo $error_phone;
                        unset($error_phone);
                    }
                    if(isset($error_user)) {
                        echo $error_user;
                        unset($error_user);
                    }
                ?>
                    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="form-group">
                            <label for="name">Imię klienta <span class="red">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" value="<?php if(isset($name)) echo $name; ?>">
                        </div>
                        <div class="form-group">
                            <label for="surname">Nazwisko klienta <span class="red">*</span></label>
                            <input type="text" class="form-control" name="surname" id="surname" value="<?php if(isset($surname)) echo $surname; ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone">Telefon <span class="red">*</span></label>
                            <input type="text" class="form-control" name="phone" id="phone" value="<?php if(isset($phone)) echo $phone; ?>">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" name="send" id="send">Dodaj klienta</button>
                        </div>
                    </form>
                </div>

              </main>
        
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
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