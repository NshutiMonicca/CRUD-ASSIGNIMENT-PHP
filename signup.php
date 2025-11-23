<?php
// signup.php
session_start();
include 'connection.php'; // must create $conn (mysqli)


$message = "";

if (isset($_POST['create'])) {
    // normalize and validate inputs
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $conf     = $_POST['conf_password'];
    $email    = trim($_POST['emails']); // NOTE: use "emails" to match other pages

   $sql= mysqli_query($conn,"SELECT * FROM users WHERE emails='$email'");
    if(mysqli_num_rows($sql)>0){
        $message= "Email have been used";
    }else{
      $insertQuery= mysqli_query($conn,"INSERT INTO users(username,password,emails) VALUES('$username','$password','$email')");
      if($insertQuery){
        $message= "Iserted well";
        header('location:login.php');
      }
    }

    
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Signup</title>
<style>
    body { background-color: #9dd3ff; font-family: Arial, sans-serif; }
    form { background-color: #a3c486; padding: 20px; border-radius: 15px; margin: 20px auto; width: 350px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    input { width: 90%; padding: 8px; margin-top: 5px; border-radius: 9px; border: 1px solid #302929; }
    button { padding: 8px 17px; margin: 10px 5px 0 0; border: none; border-radius: 8px; background-color: #487b5f; color: white; cursor: pointer; }
    button:hover { background-color: #040b12; }
    h2 { text-align: center; color: #201f1f; }
    .msg { text-align: center; font-weight: bold; }
</style>
</head>
<body>

<h2>Signup</h2>

<?= $message ?>

<form method="post" autocomplete="off">
    <label>User Name:</label><br>
    <input name="username" required placeholder="username"><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required placeholder="password"><br><br>

    <label>Confirm Password:</label><br>
    <input type="password" name="conf_password" required placeholder="confirm"><br><br>

    <label>Email:</label><br>
    <!-- NOTE: name="emails" to match other pages and DB column -->
    <input type="email" name="emails" required placeholder="email"><br><br>

    <button type="submit" name="create">Sign up</button>
</form>

<p style="text-align:center;">Have an account? <a href="login.php">Login</a></p>

</body>
</html>
