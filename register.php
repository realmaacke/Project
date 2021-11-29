<?php 
include('autoload.php');  // loading all classes using spl loader
$error = "";
if(isset($_POST['register'])){
 $usrnm = htmlspecialchars($_POST['username']);
 $pass =  htmlspecialchars($_POST['password']);
 $email = htmlspecialchars($_POST['email']);
 $error = authorization::register($usrnm, $pass, $email);
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

<div class="login">

    <div class="login_top">
      <h2>REGISTER</h2>
    </div>
    <div class="login_bottom">
      <div class="formController">
        <?php echo $error; ?>
      <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="E-mail" id="">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password" id="">
        <input type="submit" id="login-btn" name="register" value="Register">
      </form>
      </div>
    </div>


</div>


<div class="links">
  <a id="signup-a" href="login.php">Log in?</a>
  <br>
  <br>
  <a id="cantLogin-a" href="forgot-password.php">Can't log in?</a>
</div>


</body>
</html>

<script>
  setTimeout(function(){
  if ($('#error').length > 0) {
    $('#error').remove();
  }
}, 3000)
</script>