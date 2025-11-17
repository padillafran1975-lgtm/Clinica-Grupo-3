<?php
session_start();

$_SESSION = array();

session_destroy();

// Redirigir al login
header("Location: index.html");
exit();
?>