<?php 
include('classes/authorization.php');
include('classes/DB.php');
include('classes/Redirect.php');
if(isset($_POST['register'])){
 $usrnm = $_POST['username'];
 $pass = $_POST['password'];
 $mail = $_POST['email'];
  authorization::register($usrnm, $pass, $mail);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="GUI/login.css">
  <link rel="stylesheet" href="GUI/universal/style.css">
  <title>COMBINED</title>
</head>
<body>

<div class="wrapper fadeInDown">

  <h1>COMBINED</h1>

  <div id="formContent">
    <!-- Tabs Titles -->
    <h2 class="inactive underlineHover"><a href="login.php">Sign in</a></h2>
    <h2 class="active"> Sign up </h2>
    <!-- Login Form -->
    <br>  <!-- break line  -->
    <p id="errorMSG" style="color:red; display: none;">Invalid Username or Password</p> <!-- Error MSG  -->
    <form action="register.php" method="POST">
      <input type="text" id="username" class="fadeIn second" name="username" placeholder="login">
      <input type="password" id="password" class="fadeIn third" name="password" placeholder="password">
      <input type="email" id="email" name="email" class="fadeIn third" placeholder="someone@somesite.com"><p />
    <!-- Remind Passowrd -->
      <div id="formFooter">
       <input class="btn btn-primary fadeIn fourth" name="register" value="REGISTER" type="submit">
      </div>
    </form>
  </div>
</div>
</body>
</html>
