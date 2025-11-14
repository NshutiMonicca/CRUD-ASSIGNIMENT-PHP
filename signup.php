<!doctype html>
<html><head>
<meta charset="utf-8"><title>Signup</title>

<style>
        body {
            background-color: #9dd3ffff;
            font-family: Arial, sans-serif;
        }
        form, table {
            background-color: #a3c486ff;
            padding: 20px;
            border-radius: 15px;
            margin: 20px auto;
            width: 350px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, select {
            width: 90%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 9px;
            border: 1px solid #302929ff;
        }
        button {
            padding: 8px 17px;
            margin: 10px 5px 0 0;
            border: none;
            border-radius: 8px;
            background-color: #487b5fff;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #040b12ff;
        }
        table {
            width: 90%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #302929ff;
            padding: 8px;
            text-align: center;
        }
        h1 {
            text-align: center;
            color: #201f1fff;
        }
    </style>
</head><body><center>
<h2>Signup</h2>
<?php //foreach ($errors as $err) echo '<div style="color:red;">' . e($err) . "</div>"; ?>
<form method="post">
  User Name:  <input name="username" required placeholder="username"><br><br><br>
 Password: <input type="text" name="password" required placeholder="password"><br><br><br>
 Confirm:  <input type="password" name="conf_password" required placeholder="conf_password"><br><br><br>
  Email <input type="text" name="emails" required placeholder="emails"><br><br><br>
  <button type="" name ="create">Sign up</button>
</form>
<p>Have an account? <a href="login.php">Login</a></p>




<?php
include 'connection.php';
// $conn = mysqli_connect('localhost','root','','juice_bar');
if(isset($_POST['create'])){
    $username=$_POST['username'];
    $password = $_POST['password'];
     $email = $_POST['emails'];
    $conf_password = $_POST['conf_password'];
    if($password === $conf_password){


    $create = mysqli_query($conn,"INSERT INTO users VALUES('$username','$password','$email')");
    if($create){
        echo "<p style='color:green'>User Created!</p>";
    }
    
}else{
    echo "<p style='color:red'>Password not match!</p>";

}
}


?>
</center></body></html>