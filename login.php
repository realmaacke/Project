<?php
include('classes/DB.php');
include('classes/Redirect.php');
include('classes/authorization.php');

$error = "";
if(isset($_POST['login'])){
  $username = $_POST['username'];
  $password = $_POST['password'];
  authorization::login($username, $password);
} 

if(isset($_GET['error']))
{
  $error = "<br> <p id='errormsg' style='color:red'>Invalid Credentials!</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="GUI/login.css">
  <link rel="stylesheet" href="GUI/universal/style.css">
  <title>Combined</title>
</head>
<body>

<div class="wrapper fadeInDown">

  <h1>Combined Login</h1>

  <div id="formContent">
    <!-- Tabs Titles -->
    <h2 class="active"> Sign In </h2>
    <h2 class="inactive underlineHover"><a href="register.php">Sign up</a></h2>
    <?php 
      echo $error;
      ?>
    <!-- Login Form -->
    <form action="login.php" method="POST">
      <input type="text" id="username" class="fadeIn second" name="username" placeholder="login">
      <input type="password" id="password" class="fadeIn third" name="password" placeholder="password">
      <input type="submit" id="login" class="fadeIn fourth" name="login" value="Log In">
    </form>

    <!-- Remind Passowrd -->
    <div id="formFooter">
      <a class="underlineHover" href="#">Forgot Password?</a>
    </div>

  </div>
</div>

</body>
</html>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>

<script>  // error msg function
function hideMessage() 
{
    document.getElementById("errormsg").style.display = "none";
};
setTimeout(hideMessage, 3000);
</script>