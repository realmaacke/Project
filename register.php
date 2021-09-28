<?php
include('classes/DB.php');

if (isset($_POST['register'])) {  // Grabing post variables and defining them to $...
  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];

  if(!DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username)))
  {
      if(strlen($username) >= 4 && strlen($username) <= 33){

        if(preg_match('/[a-zA-Z0-9_]+/', $username)) {  // making non utf8 characters invalid

          if(strlen($password) >= 6 && strlen($password) <= 60){

          if(filter_var($email, FILTER_VALIDATE_EMAIL)){ // PHP EMAIL VERIFICATION FUNCTION

          if(!DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))){

            DB::query('INSERT INTO users VALUES (\'\', :username, :password, :email)', array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email));
            echo "Success!";
          } else{
            echo "Email already in use";
           }
          } else{
            echo "invalid email";
          }
        } else{
            echo "invalid username";
          }
        } else {
         echo "invalid username";
        }
      }else{
        echo "your usernema must be 4-33 characters";
      }
  } else{
      echo "user already exist";
}
}

?>

<h1>Register</h1>
<form action="register.php" method="post">
<input type="text" name="username" value="" placeholder="Username ..."><p />
<input type="password" name="password" value="" placeholder="Password ..."><p />
<input type="email" name="email" value="" placeholder="example@domain.com"><p />
<input type="submit" name="register" value="Register">
</form>
