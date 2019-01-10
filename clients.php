<?php
session_start();

if(!isset($_SESSION['zalogowany']) && !$_SESSION['zalogowany']) {
    header('Location: login.php');
    exit();
}

require_once('connect.php');

$sql = "SELECT * FROM klienci WHERE zaklad = :zaklad";
$result = $pdo->prepare($sql);
$result->bindValue(':zaklad', $_SESSION['email']);
$result->execute();

$length = $result->fetchAll(PDO::FETCH_ASSOC);
$length2 = count($length);
?>
<!doctype html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>HairSystem - zarządzaj klientami</title>
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="css/dashboard.css" rel="stylesheet">
    <style>
        #search-form {
            margin-bottom: 30px;
        }
    </style>
  </head>
  <body>

  <?php include_once('navbar-login.php'); ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                  <h1 class="h2">Klienci</h1>
                </div>

                
                    <div class="col-lg-6 col-md-10 col-sm-12 col-xs-12">
                        <div class="input-group" id="search-form">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><span data-feather="search"></span></span>
                            </div>
                            <input type="text" class="form-control" id="search" placeholder="Szukaj po nazwiskach....">
                        </div>
                    </div>
                
      
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                <?php
                    if($length2 == 0) {
                        echo '<h3>W swojej bazie nie posiadasz jeszcze stałych klientów</h3>';
                    }
                    else {

                ?>
                <table class="table table-striped text-center ">
                    <thead>
                        <tr>
                        <th scope="col">Nazwisko</th>
                        <th scope="col">Imię</th>
                        <th scope="col">Telefon</th>
                        <th scope="col">Akcja</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                            for($i = 0; $i < $length2; $i++) {
                                echo '<tr>';
                                echo '<td id="surname">' . ucfirst($length[$i]['nazwisko']) . '</td>';
                                echo '<td id="name">' . ucfirst($length[$i]['imie']) . '</td>';
                                echo '<td>' . number_format($length[$i]['telefon'], 0, ',', '-') . '</td>';
                                echo '<td><a href=edit-user.php?id='. $length[$i]['id'] .'><button type="button" class="btn btn-primary btn-sm">Edytuj</button></a></td>';
                                echo '</tr>';
                            }
                        }
                    ?>
                    </tbody>
                </table>
                </div>

              </main>
        
      </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="lib/bootstrap/js/vendor/popper.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <script src="js/search.js"></script>
  </body>
</html>