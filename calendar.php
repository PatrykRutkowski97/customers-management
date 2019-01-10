<?php
session_start();

if(!isset($_SESSION['zalogowany']) && !$_SESSION['zalogowany']) {
    header('Location: login.php');
    exit();
}

require_once('connect.php');

$sql = "SELECT * FROM wizyty WHERE autor = :autor";
$statment = $pdo->prepare($sql);
$statment->bindValue(':autor', $_SESSION['email']);
$statment->execute();

$user = $statment->fetch(PDO::FETCH_ASSOC);
$length = count($user);

//********************************************************** */
//*https://codingwithsara.com/how-to-code-calendar-in-php/ */
//********************************************************** */
date_default_timezone_set('Europe/Warsaw');
// Get prev & next month
if (isset($_GET['ym'])) {
    $ym = $_GET['ym'];
} else {
    // This month
    $ym = date('Y-m');
}
// Check format
$timestamp = strtotime($ym . '-01');
if ($timestamp === false) {
    $ym = date('Y-m');
    $timestamp = strtotime($ym . '-01');
}
// Today
$today = date('Y-m-j', time());
// For H3 title
$html_title = date('m-Y', $timestamp);
// Create prev & next month link     mktime(hour,minute,second,month,day,year)
$prev = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)-1, 1, date('Y', $timestamp)));
$next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)+1, 1, date('Y', $timestamp)));
// You can also use strtotime!
// $prev = date('Y-m', strtotime('-1 month', $timestamp));
// $next = date('Y-m', strtotime('+1 month', $timestamp));
// Number of days in the month
$day_count = date('t', $timestamp);
 
// 0:Sun 1:Mon 2:Tue ...
$str = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));
//$str = date('w', $timestamp);
// Create Calendar!!
$weeks = array();
$week = '';
// Add empty cell
$week .= str_repeat('<div class="calendar__day day"></div>', $str);
for ( $day = 1; $day <= $day_count; $day++, $str++) {

    $date = $ym . '-' . $day;
     
    if ($today == $date) {
        $week .= '<a href="data.php?date='. $date .'" class="today"><div class="calendar__day day ok_date">'. $day;
    } else {
        $week .= '<a href="data.php?date='. $date .'" class="link"><div class="calendar__day day ok_date" id="date">' . $day;
    }
    $week .= '</div></a>';
     
    // End of the week OR End of the month
    if ($str % 7 == 6 || $day == $day_count) {
        if ($day == $day_count) {
            // Add empty cell
            $week .= str_repeat('<div class="calendar__day day"></div>', 6 - ($str % 7));
        }
        $weeks[] = '<div class="calendar__week">' . $week . '</div>';
        // Prepare for new week
        $week = '';
    }
}

//********************************************************** */
//********************************************************** */

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
  <link href="css/calendar.css" rel="stylesheet">
  
	<style>

	li {
		list-style-type: none;
	}
	.link {
    cursor: pointer;
    color: #000;
	}
	.link:hover {
		background-color: #4db6ac;
    color: #fff;
    text-decoration: none;
	}
	</style>
  </head>

  <body>
    
    <?php include_once('navbar-login.php'); ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Kalendarz</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
            </div>
          </div>

         <!-- Calendar --------------------->
    <div class="toolbar">
      <div class="current-month h3"><a href="?ym=<?php echo $prev; ?>">&lt;</a> <?php echo $html_title; ?> <a href="?ym=<?php echo $next; ?>">&gt;</a></div>
    </div>
    <div class="calendar">
      <div class="calendar__header">
        <div>Niedziela</div>
        <div>Poniedziałek</div>
        <div>Wtorek</div>
        <div>Środa</div>
        <div>Czwartek</div>
        <div>Piątek</div>
        <div>Sobota</div>
      </div>
	  
	  <?php
        foreach ($weeks as $week) {
            echo $week;
        }
        ?>
      
    </div>

</main>
      </div>
    </div>
    
    <!-- Bootstrap -->
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace();
    </script>
    <!-- Graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    
  </body>
</html>