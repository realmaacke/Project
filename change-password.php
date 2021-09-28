<?php
include('classes/DB.php');
include('classes/login.php');

if (!login::isLoggedIn()) {
    header("Location: login.php");
}

if(isset($_POST['changepassword']))
{   // POST VARIABLES
    $oldpassword = $_POST['oldpassword'];
    $newpassword = $_POST['newpassword'];
    $newpasswordrepeat = $_POST['newpasswordrepeat'];

    // DECLARING USER ID FROM ISLOGGEDIN FUNCTION
    $userid = login::isLoggedIn();

    if(password_verify($oldpassword, DB::query('SELECT password FROM users WHERE id=:userid',  array(':userid'=>$userid))[0]['password'])) 
    {   
        if($newpassword == $newpasswordrepeat)
        {
            if(strlen($newpassword) >= 6 && strlen($newpassword) <= 60)
            {
                DB::query('UPDATE users SET password=:newpassword WHERE id=:userid', array(':newpassword'=>password_hash($newpassword, PASSWORD_BCRYPT), ':userid'=>$userid));
                echo "password changed Succesfully";
            } 
            else
            {
                echo "Password must be 6-60 characters";
            }
        } 
        else
        {
            echo "Passwords not match";
        }


    } else {
        echo "incorrect old password";
    }

}



?>

<form action="change-password.php" method="POST">
    <input type="password" name="oldpassword" value="" placeholder="Current password"> </p>
    <input type="password" name="newpassword" value="" placeholder="New password"> </p>
    <input type="password" name="newpasswordrepeat" value="" placeholder="Repeat new password"> </p>
    <input type="submit" name="changepassword" value="Change password">
</form>
