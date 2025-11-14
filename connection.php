<?php  

$conn = mysqli_connect('localhost','root','','juice_bar');
session_start();

if($_SESSION['my_session']){
    
}
else{
    header('location:login.php');
    
}

?>