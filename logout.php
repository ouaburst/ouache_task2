<?php
session_start();

unset($_SESSION['language']);
unset($_SESSION['loginPassword']);
unset($_SESSION['currentfile']);
unset($_SESSION['userLevel']);
unset($_SESSION['userID']);

setcookie("loginPassword", "", time()-(60*60*24), "/");

session_destroy();

header("Location: admin.php");

//header("Location: $redirect");

?>