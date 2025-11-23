<?php 
session_start();
session_destroy();
header('location:juice/index.php');
?>