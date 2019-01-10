<?php
session_start();

if(!isset($_SESSION['zalogowany']) && !$_SESSION['zalogowany']) {
    header('Location: login.php');
    exit();
}

if(isset($_POST['send'])) {
    require_once('connect.php');
    // ---- Errors ----------
    $error = false;
    $error_name = '';
    $error_surname = '';
    $error_phone = '';
    $error_service = '';
    $error_date = '';
    $error_time = '';
    //--------------- Validate form inputs ---------------------------
    $name = trim(filter_var($_POST['name'],FILTER_SANITIZE_STRING));
    $surname = trim(filter_var($_POST['surname'],FILTER_SANITIZE_STRING));
    $phone = trim(filter_var($_POST['phone'],FILTER_SANITIZE_NUMBER_INT));
    $date = $_POST['date'];
    $time = $_POST['time'];
    $service = trim(filter_var($_POST['service'],FILTER_SANITIZE_STRING));
    $message = trim(filter_var($_POST['message'],FILTER_SANITIZE_STRING));

    if(empty($name)) {
        $error_name = '<div class="alert alert-danger" role="alert">Nieprawidłowe imię <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }
    if(empty($surname)) {
        $error_surname = '<div class="alert alert-danger" role="alert">Nieprawidłowe nazwisko <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }
    if(empty($phone) || strlen($phone) != 9) {
        $error_name = '<div class="alert alert-danger" role="alert">Nieprawidłowy numer telefonu <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }
    if(empty($service)) {
        $error_service = '<div class="alert alert-danger" role="alert">Nieprawidłowa nazwa ugługi <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }
    if(empty($date)) {
        $error_date = '<div class="alert alert-danger" role="alert">Nieprawidłowa data <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }
    if(empty($time)) {
        $error_time = '<div class="alert alert-danger" role="alert">Nieprawidłowa godzina <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }

    if($error == false) {
        $sql = "INSERT INTO wizyty (imie,nazwisko,telefon,usluga,data,godzina,uwagi,autor) VALUES (:imie,:nazwisko,:telefon,:usluga,:data,:godzina,:uwagi,:autor)";
        $statment = $pdo->prepare($sql);
        $statment->bindValue(':imie',mb_strtolower($name, 'UTF-8'));
        $statment->bindValue(':nazwisko',mb_strtolower($surname, 'UTF-8'));
        $statment->bindValue(':telefon',$phone);
        $statment->bindValue(':usluga',mb_strtolower($service, 'UTF-8'));
        $statment->bindValue(':data',$date);
        $statment->bindValue(':godzina',$time);
        $statment->bindValue(':uwagi',mb_strtolower($message, 'UTF-8'));
        $statment->bindValue(':autor', $_SESSION['email']);

        $result = $statment->execute();

        if($result) {
            $added = '<div class="alert alert-success" role="alert">Dodano wizytę <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
            $name = '';
            $surname = '';
            $phone = '';
            $service = '';
            $date = '';
            $time = '';
            $message = '';
        }
        else {
            $no_added = '<div class="alert alert-danger" role="alert">Coś poszło nie tak. Dodaj wizytę jeszcze raz <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
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
                  <h1 class="h2">Dodaj nową wizytę</h1>
                </div>
      
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <?php 
                    if(isset($added)) {
                        echo $added;
                        unset($added);
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
                    if(isset($error_date)) {
                        echo $error_date;
                        unset($error_date);
                    }
                    if(isset($error_time)) {
                        echo $error_time;
                        unset($error_time);
                    }
                    if(isset($error_service)) {
                        echo $error_service;
                        unset($error_service);
                    }
 
                ?>
                    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="form-group">
                            <label for="name">Imię klienta <span class="red">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" value="<?php if(isset($name)) {echo $name;} ?>">
                        </div>
                        <div class="form-group">
                            <label for="surname">Nazwisko klienta <span class="red">*</span></label>
                            <input type="text" class="form-control" name="surname" id="surname" value="<?php if(isset($surname)) {echo $surname;} ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone">Telefon <span class="red">*</span></label>
                            <input type="text" class="form-control" name="phone" id="phone" value="<?php if(isset($phone)) {echo $phone;} ?>" placeholder="np. 730125963">
                        </div>
                        <div class="form-group">
                            <label for="service">Usługa <span class="red">*</span></label>
                            <input type="text" class="form-control" name="service" id="service" value="<?php if(isset($service)) {echo $service;} ?>">
                        </div>
                        <div class="form-group">
                            <label for="date">Data <span class="red">*</span></label>
                            <input type="date" class="form-control" name="date" id="date" value="<?php if(isset($date)) {echo $date;} ?>" >
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="time">Godzina <span class="red">*</span></label>
                            <input type="time" class="form-control" name="time" id="time" value="<?php if(isset($time)) {echo $time;} ?>">
                        </div>
                        <div class="form-group">
                            <label for="message">Uwagi (opcjonalnie)</label>
                            <textarea class="form-control" id="message" name="message" rows="3" value="<?php if(isset($message)) {echo $message;} ?>"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="send" name="send">Dodaj wizytę</button>
                        </div>
                    </form>
                </div>

              </main>
        
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
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