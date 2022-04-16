<?php
include('autoload.php');
if (!Login::isLoggedIn()) {
        Redirect::goto('login.php');
}

if (isset($_POST['confirm'])) { 

        if (isset($_POST['alldevices'])) { // if all devices is true remove coockies

                DB::query('DELETE FROM login_tokens WHERE user_id=:userid', array(':userid'=>Login::isLoggedIn()));
                Redirect::goto('login.php');

        } else {
                if (isset($_COOKIE['CMBNID'])) {
                        DB::query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['CMBNID'])));
                }
                setcookie('CMBNID', '1', time()-3600);
                setcookie('CMBNID_', '1', time()-3600);
                Redirect::goto('login.php');
        }

}

?>



<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="Visual/style.css">
        <link rel="icon" type="image/x-icon" href="Visual\img\favicon.ico">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/6bfb37676a.js" crossorigin="anonymous"></script>
        <title>COMBINED - SIGN OUT</title>
</head>
<body>

<div class="login">

        <div class="signout_top">
                <h2>SIGN OUT <i class="fas fa-sign-out-alt"></i></h2>
        </div>

        <div class="signout_bottom">
                <div class="formController-signout">
                        <form action="logout.php" method="post">
                                <h2 id="SIGNOUT-TXT"> Logout of all devices?</h2>
                                <input type="checkbox" id="checkbox" name="alldevices" value="alldevices">
                                <input type="submit" id="signout-btn" name="confirm" value="SIGN OUT">
                        </form>
                        <button id="return-btn"> <i class="fas fa-arrow-left"></i> RETURN</button>
                </div>
        </div>
</div>

<!-- return to index.php if user where to press the reutrn button -->
<script type="text/javascript">
  $(document).ready(function () {

  $('#return-btn').click(function() 
  {
        window.location = "index.php";
  })
});
</script>
</body>
</html>