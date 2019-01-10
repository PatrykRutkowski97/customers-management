<?php

session_start();
unset($_SESSION['zalogowany']);
unset($_SESSION['email']);
session_destroy();
header('Location: index.php');