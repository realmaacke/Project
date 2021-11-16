<?php
//Including Database configuration file.
include("autoload.php");
//Getting value of "search" variable from "script.js".
if (isset($_POST['search'])) 
{
//Search box value assigning to $Name variable.
   $Name = $_POST['search'];
//Search query.
   $Query = DB::query("SELECT * FROM users WHERE username LIKE '%$Name%' LIMIT 5");
   echo '
<div>
   ';
   //Fetching result from database.
  foreach ($Query as $q) {
    $img = DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$q['username']))[0]['profileimg'];
      $hasImage = false;
    if(DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$q['username']))[0]['profileimg'])
    {
     $hasImage = true;
    }
    else{
      $hasImage = false;
    }
    
?>
<div id="item">
            <div id="left">
                <?php 
                if($hasImage){ ?>
                    <img src="<?php echo $img; ?>" alt="" style="margin: auto; margin-left: 5px;" width="80" height="80">
               <?php }else{ ?>
                <img src="Visual/img/avatar.png" alt="" style="margin: auto; margin-left: 5px;" width="80" height="80">
                <?php } ?>
            </div>
            <div id="s_info">
                <h2><?php echo $q['username'];?></h2>
                <div id="s_info_btns">
                    <a href="profile.php?username=<?php echo $q['username']; ?>">Go To Profile  <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
       
       
       
<?php }}
?>
</div>


<!--  <li onclick="fill(" echo $q['username'] ?> ")"> -->