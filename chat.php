<?php 
  session_start();
  include('autoload.php');  // loading all classes using spl loader
  include_once "php/config.php";
?>
<?php include_once "header.php"; ?>
<body>
<div class="navigation">
        <ul>
            <a href="index.php"><h1>COMBINED </h1></a>
                <li> <a href="profile.php?username=<?php echo $name;?>">Profile</a> </li>
                <li> <a href="dm.php">Messages</a> </li>
                <li> <a href="search.php">Search</a> </li>
        </ul>
</div>

    <section class="chat-area">
      <header>
        <?php 
          $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
          $sql = mysqli_query($conn, "SELECT * FROM users WHERE id = {$user_id}");
          if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
          }else{
            header("location: users.php");
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
          <p>Active</p>
        </div>
      </header>
      <div class="chat-box">

      </div>
      <form action="#" class="typing-area">
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
        <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
        <button><i class="fab fa-telegram-plane"></i></button>
      </form>
    </section>
  <script src="javascript/chat.js"></script>
</body>
</html>
