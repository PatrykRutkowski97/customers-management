<?php
session_start();

if(!isset($_SESSION['zalogowany']) && !$_SESSION['zalogowany']) {
  header('Location: login.php');
  exit();
}

require_once('connect.php');

$sql = "SELECT * FROM wizyty WHERE autor = :autor AND data = :data ORDER BY data, godzina ASC";
$statment = $pdo->prepare($sql);
$statment->bindValue(':autor', $_SESSION['email']);
$statment->bindValue(':data', $_GET['date']);
$statment->execute();

$user = $statment->fetchAll(PDO::FETCH_ASSOC);
$length = count($user);

if($length <= 0) {
  $error_database = '<div class="alert alert-warning" role="alert">Nie masz jeszcze żadnej umówionej wizyty <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
}

$today = date('Y-m-d');
$today_time = date('H:i');

?>
<!doctype html>
<html lang="en">
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
      h4 {
        margin-top: 40px;
      }
    </style>
  </head>

  <body>

  <?php include_once('navbar-login.php'); ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4" >
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Dziennik wizyt:  <?php echo date('d-m-Y', strtotime($_GET['date'])) ?></h1>
            <div class="btn-toolbar mb-2 mb-md-0">
            </div>
          </div>

          <div class="table-responsive">
            <?php
              if(isset($error_database)) {
                echo $error_database;
                unset($error_database);

                echo '<a href="calendar.php" class="btn btn-primary">Powrót</a>';
              }
              else {
                echo '<table class="table table-hover text-center">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Imię</th>';
                echo '<th>Nazwisko</th>';
                echo '<th>Usługa</th>';
                echo '<th>Godzina</th>';
                echo '<th>Data</th>';
                echo '<th>Akcja</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
               for($i = 0; $i < $length; $i++) {
                 if($user[$i]['data'] <= $today && date('H:i', strtotime($user[$i]['godzina'])) <= $today_time) {
                   $sql = "DELETE FROM `wizyty` WHERE id = :id";
                   $result = $pdo->prepare($sql);
                   $result->bindValue(':id', $user[$i]['id']);
                   $result->execute();
                 }
                 echo '<tr >';
                 echo '<td>'. ucfirst($user[$i]['imie']) .'</td>';
                 echo '<td>'. ucfirst($user[$i]['nazwisko']) .'</td>';
                 echo '<td>'. ucfirst($user[$i]['usluga']) .'</td>';
                 echo '<td>'. date('H:i', strtotime($user[$i]['godzina'])) .'</td>';
                 echo '<td>'. date('d-m-Y', strtotime($user[$i]['data'])) .'</td>';
                 echo '<td><a href="edit-visit.php?id='. $user[$i]['id'] .'" class="btn btn-info btn-sm">Edytuj</a></td>';
               }
                echo '</tbody>';
                echo '</table>';
              }

            ?>
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
      feather.replace()
    </script>

    <!-- Graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
  </body>
</html>