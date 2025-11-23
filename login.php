<?php
// login.php
session_start();
include 'connection.php'; // $conn

$message = '';

if (isset($_POST['login'])) {
    $email = trim($_POST['emails']);
    $password = $_POST['password'];
    $checkEmmail = mysqli_query($conn,"SELECT * FROM users WHERE emails='$email'");
    if(mysqli_num_rows($checkEmmail)>0){
        $_SESSION['email']= $email;
        header('location:index.php');
    }
  
   
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<style>
    body { background-color: #9dd3ff; font-family: Arial, sans-serif; }
    form { background-color: #a3c486; padding: 20px; border-radius: 15px; margin: 20px auto; width: 350px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    input { width: 90%; padding: 8px; margin-top: 5px; border-radius: 9px; border: 1px solid #302929; }
    button { padding: 8px 17px; margin: 10px 0 0 0; border: none; border-radius: 8px; background-color: #487b5f; color: white; cursor: pointer; }
    button:hover { background-color: #040b12; }
    h2 { text-align: center; color: #201f1f; }
    .msg { text-align: center; font-weight: bold; }
</style>
</head>
<body>
<center>

<h2>Login</h2>

<?= $message ?>

<form method="post" autocomplete="off">
    <label>Email:</label><br>
    <input type="email" name="emails" required placeholder="Enter your email"><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required placeholder="Enter password"><br><br>

    <button type="submit" name="login">Login</button>
</form>

<p>No account? <a href="signup.php">Sign up</a></p>

</center>
</body>
</html>
