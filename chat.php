<?php 
  session_start();
  include('autoload.php');  // loading all classes using spl loader

  if (Login::isLoggedIn())  
  { 
    $userid = Login::isLoggedIn(); 
  }
  else 
  {
    Redirect::goto('login.php'); 
  }

  $target = DB::query('SELECT username FROM users WHERE id=:user_id', array(':user_id'=>$_GET['user_id']))[0]['username'];
  $user_id = $_GET['user_id'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="Visual/style.css">
  <link rel="stylesheet" href="Visual/dm.css">
  <title>Document</title>
</head>
<body>
<div class="navigation">
        <ul>
            <a href="index.php"><h1>COMBINED </h1></a>
                <li> <a href="profile.php?username=<?php echo $local;?>">Profile</a> </li>
                <li> <a href="dm.php">Messages</a> </li>
                <li> <a href="search.php">Search</a> </li>
        </ul>
</div>
  
<section class="chat-area">
      <header>
        <?php 
          $sql = DB::query("SELECT * FROM users WHERE id = {$user_id}");
          foreach($sql as $row){
            
          }
          $img = DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$row['username']))[0]['profileimg'];
          $hasimage = false;
          if($img)
          {
            $hasimage = true;
          }
        ?>
        <a href="dm.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <?php 
        if($hasimage){
          ?> <img src="<?php echo $img; ?>" alt=""> <?php
        } else{
         ?> <img src="Visual/img/avatar.png" alt=""> <?php
        }
        ?>
        
        <div class="details">
          <a href="profile.php?username=<?php echo $row['username'];?>"><span><?php echo $row['username']?></span></a>
        </div>
      </header>
      <div class="chat-box"> </div>
      
      <form action="#" class="typing-area">
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
        <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
        <button><i class="fab fa-telegram-plane"></i></button>
      </form>
    </section>
  <script src="javascript/chat.js"></script>
</body>
</html>