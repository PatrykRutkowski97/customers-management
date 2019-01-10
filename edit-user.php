<?php
session_start();

if(!isset($_SESSION['zalogowany']) && !$_SESSION['zalogowany']) {
    header('Location: login.php');
    exit();
}

require_once('connect.php');

$sql = "SELECT * FROM klienci WHERE id = :id";
$result = $pdo->prepare($sql);
$result->bindValue(':id', $_GET['id']);
$result->execute();

$user = $result->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['send'])) {
    $name = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $surname = trim(filter_var($_POST['surname'], FILTER_SANITIZE_STRING));
    $phone = trim(filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT));
    //----- Errors ------------
    $error = false;
    $error_name = '';
    $error_surname = '';
    $error_phone = '';

    if(empty($name)) {
        $error_name = '<div class="alert alert-danger" role="alert">Nieprawidłowe imię <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }
    if(empty($surname)) {
        $error_name = '<div class="alert alert-danger" role="alert">Nieprawidłowe nazwisko <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }
    if(empty($phone)) {
        $error_phone = '<div class="alert alert-danger" role="alert">Nieprawidłowy numer telefonu <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $error = true;
    }

    if($error == false) {
        $sql = "UPDATE `klienci` SET `imie`=:imie,`nazwisko`=:nazwisko,`telefon`=:telefon WHERE `id`=:id";
        $result = $pdo->prepare($sql);
        $result->bindValue(':imie', $name);
        $result->bindValue(':nazwisko', $surname);
        $result->bindValue(':telefon', $phone);
        $result->bindValue(':id', $_GET['id']);
        $result->execute();

        if($result) {
            $edit = '<div class="alert alert-success" role="alert">Dane zostały zaktualizowane prawidłowo. Odswież stronę <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
            $name = '';
            $surname = '';
            $phone = '';
            $visits = '';
        }
        else {
            $edit = '<div class="alert alert-warning" role="alert">Dane nie zostały zaktualizowane prawidłowo <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        }
    }

}

if(isset($_POST['delete'])) {
    $sql = "DELETE FROM `klienci` WHERE id = :id";
    $result = $pdo->prepare($sql);
    $result->bindValue(':id', $_GET['id']);
    $result->execute();

    header('Location: clients.php');
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
        #search-form {
            margin-bottom: 30px;
        }
        .red {
            color: #F00;
        }
    </style>
  </head>

  <body>

  <?php include_once('navbar-login.php'); ?>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                  <h1 class="h2">Edytuj dane klienta</h1>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <?php
                    if(isset($edit)) {
                        echo $edit;
                        unset($edit);
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
                ?>
                    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="form-group">
                            <label for="name">Imię klienta <span class="red">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" value="<?php echo ucfirst($user['imie']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="surname">Nazwisko klienta <span class="red">*</span></label>
                            <input type="text" class="form-control" name="surname" id="surname" value="<?php echo ucfirst($user['nazwisko']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone">Telefon <span class="red">*</span></label>
                            <input type="text" class="form-control" name="phone" id="phone" value="<?php echo $user['telefon']; ?>">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" name="send" id="send">Edytuj dane</button>
                            <button type="submit" class="btn btn-danger" name="delete" id="delete">Usuń klienta</button>
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
    <script src="lib/bootstrap/js/vendor/popper.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace();
      document.getElementById('delete').addEventListener('click', e => {
          const text = confirm("Czy napewno chcesz usunąć klienta ?");
          if(text == false) {
              e.preventDefault();
          }
      });
    </script>

    <!-- Graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
  </body>
</html>