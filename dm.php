<?php 
  session_start();
  include_once "php/config.php";
  include('autoload.php');  // loading all classes using spl loader

/////////////////  CHECKING FOR PERMISSION  ////////////////////////////////
if (Login::isLoggedIn())  { $userid = Login::isLoggedIn(); }
else {  Redirect::goto('login.php'); }
$_SESSION['unique_id'] = $userid;
$username = DB::query('SELECT username FROM users WHERE id=:id',array(':id'=>$userid))[0]['username'];

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
    <section class="users">
      <header>
        <div class="content">
          <?php 

            $sql = mysqli_query($conn, "SELECT * FROM users WHERE id = {$userid}");
            if(mysqli_num_rows($sql) > 0){
              $row = mysqli_fetch_assoc($sql);
            }

            echo Profile::displayImage($username, false);
          ?>
          <div class="details">
            <span ><?php echo $row['username']; ?></span>
            <p>Active</p>
          </div>
        </div>
      </header>
      <div class="search">
        <span class="text">Select an user to start chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>
      </div>
      <div class="users-list">
  
      </div>
    </section>

  <script src="javascript/users.js"></script>

</body>
</html>
