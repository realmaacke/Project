<?php 
$Notify = "";
if(Notify::NavbarNotification($userid))
{
  $Notify = "<i class='fas fa-comment-dots' style='color:yellow;'></i>";
}
else {
  $Notify = "<i class='fas fa-comment-dots'></i>";
}
$name = DB::query('SELECT username FROM users WHERE id=:userid',array(':userid'=>$userid))[0]['username'];

?>
<div class="navigation">
        <ul>
            <a href="index.php"><h1>COMBINED</h1></a>
                <li> <a href="profile.php?username=<?php echo $name;?>">Profile <i class="far fa-user"></i></a> </li>
                <li> <a href="dm.php">Messages <?php echo $Notify; ?></a> </li>
                <li> <a href="search.php">Search <i class="fas fa-search"></i></a> </li>
        </ul>
    </div>