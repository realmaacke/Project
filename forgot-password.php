<?php
include('classes/DB.php');
include('classes/Mail.php');

if (isset($_POST['resetpassword'])) {

    $cstrong = True;
    $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
    $email = $_POST['email'];
    $user_id = DB::query('SELECT id FROM users WHERE email=:email', array(':email'=>$email))[0]['id'];
    DB::query('INSERT INTO password_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
    Mail::sendMail('Forgot Password', "<a href='http://combined.live/project/change-password.php?token=$token'>Click to reset your password!</a>", $email);
}

?>
<h1>Forgot Password</h1>
<form action="forgot-password.php" method="post">
    <input type="text" name="email" value="" placeholder="Email ..."><p />
    <input type="submit" name="resetpassword" value="Reset Password">
</form>