<!doctype html>
<html><head>
<meta charset="utf-8"><title>Login</title>

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



<?php
include 'connection.php';
// $conn = mysqli_connect('localhost','root','','juice_bar');
if(isset($_POST['login'])){
    $name = $_POST['username'];
    $password = $_POST['password'];
   $email = $_POST['emails'];


    $select = mysqli_query($conn,"SELECT * FROM users WHERE username = '$name' && password = '$password' && emails = '$email'");
$num = mysqli_num_rows($select);

    if($num > 0){
        session_start();
        $_SESSION['my_session'] = $_POST['emails'];
        
        header('location:index.php');
    }else{
        echo "<p style='color:red'>Invalid Credentials!</p>";
    }
    
}

?>

<h2>Login</h2>
<?php //foreach ($errors as $err)
     //echo '<div style="color:red;">' . e($err) . "</div>"; ?>
<form method="post">
  User Name  <input name="username" required placeholder="username"><br><br><br>
  Password   <input type="password" name="password" required placeholder="password"><br><br><br>
   Email     <input type="text" name="emails" required placeholder="emails"><br><br><br>
  <button type ="submit" name ="login">Login</button>
</form>
<p>No account? <a href="signup.php">Sign up</a></p>

</center></body></html>