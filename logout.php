<?php 
$conn = mysqli_connect('localhost','root','','juice_bar');
session_start();
session_destroy();
header('location:login.php');
?>