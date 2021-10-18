<?php
session_start();
include('classes/DB.php');
include('classes/Mail.php');
include('classes/Redirect.php');
include('classes/authorization.php');

$error = "";
if (isset($_POST['createaccount'])) 
{
 $username = $_POST['username'];
 $password = $_POST['password'];
 $email = $_POST['email'];
 authorization::register($username,$password,$email);
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Combined Register</title>
  <link rel="stylesheet" href="GUI/login.css">
  <link rel="stylesheet" href="GUI/universal/style.css">
</head>
<body>

<div class="wrapper fadeInDown">

  <h1>Combined Register</h1>

  <div id="formContent">
    <!-- Tabs Titles -->
    <h2 class="inactive underlineHover"><a href="login.php">Sign in</a></h2>
    <h2 class="active"> Sign up </h2>
    <?php 
      echo $error;
      ?>
    <!-- Login Form -->
    <form action="register.php" method="POST">
      <input type="text" id="login" class="fadeIn second" name="username" placeholder="login">
      <input type="password" id="password" class="fadeIn third" name="password" placeholder="password">
      <input type="email" id="email" name="email" class="fadeIn third" placeholder="someone@somesite.com"><p />
      <input type="submit" class="fadeIn fourth" name="createaccount" value="Register">
    </form>

    <!-- Remind Passowrd -->
    <div id="formFooter">
      <a class="underlineHover" href="forgot-password.phpc">Forgot Password?</a>
    </div>

  </div>
</div>
</body>
</html>

<script>
function hideMessage() 
{
    document.getElementById("errormsg").style.display = "none";
};
setTimeout(hideMessage, 3000);
</script>