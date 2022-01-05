
<?php
include('../autoload.php');  // autoloader
include('data.php');

if (Login::isLoggedIn())   // validating user
{ 
  $userid = Login::isLoggedIn(); 
}
else 
{
  Redirect::goto('login.php'); 
}

if(isset($_GET['id']))
{
    $target = User::getUserByID(escape($_GET['id']));
}
else {
    Redirect::goto('../index.php');
}

 $data = messages::display($userid, $target[0]['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <link rel="stylesheet" href="chatstyle.css">
    <script src="https://kit.fontawesome.com/6bfb37676a.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="../Visual\img\favicon.ico">
    <title>COMBINED - MESSAGE</title>
</head>
<body>
<div class="content">

    <div class="chatbox">

        <div class="boxheader">

            <button class="chat-Return"><i class="fas fa-arrow-left"></i>Return to profile</button>
            <?php echo Profile::messageImage($target[0]['username']); ?>
            <h2 class="user-name"> <?php echo $target[0]['username']; ?></h2>

        </div>

        <div class="chat-display">

        </div>

        <div class="chat-controls">
            <textarea class="chat-text-area" placeholder="Message something..."></textarea>
            <button type="submit" class="chat-send-btn"><i class="fas fa-angle-right"></i></button>
        </div>

    </div>

</div>
</body>
</html>