<?php 
include('classes/authorization.php');
include('classes/DB.php');
include('classes/Redirect.php');
if(isset($_POST['login'])){
 $usrnm = htmlspecialchars($_POST['username']);
 $pass =  htmlspecialchars($_POST['password']);
  authorization::login($usrnm, $pass);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Visual/style.css">
       <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
       <link rel="icon" type="image/x-icon" href="Visual\img\favicon.ico">
    <script src="https://kit.fontawesome.com/6bfb37676a.js" crossorigin="anonymous"></script>
  <title>COMBINED - LOGIN </title>
</head>
<body>

<div class="wrapper fadeInDown">
  <h1>COMBINED</h1>
  <div id="formContent">
    <!-- Tabs Titles -->
    <h2 class="active"> Sign In </h2>
    <h2 class="inactive underlineHover"><a href="register.php">Sign up</a></h2>
    <!-- Login Form -->
    <br>  <!-- break line  -->
    <p id="errorMSG" style="color:red; display: none;">Invalid Username or Password</p> <!-- Error MSG  -->
    <form  method="POST" action="login.php">
      <input type="text" id="username" class="fadeIn second" id="username" name="username" placeholder="login">
      <input type="password" id="password" class="fadeIn third" id="password" name="password" placeholder="password">
      <input class="btn btn-primary fadeIn fourth" name="login" id="login" type="submit">
    </form>
    <!-- Remind Passowrd -->
    <div id="formFooter">
      <a class="underlineHover" href="#">Forgot Password?</a>
    </div>
  </div>
</div>

</body>
</html>