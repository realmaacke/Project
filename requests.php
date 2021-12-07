<?php 
include('autoload.php');
/////////////////  CHECKING FOR PERMISSION  ////////////////////////////////
if (Login::isLoggedIn())  
{ 
  $userid = Login::isLoggedIn(); 
}
else 
{
  Redirect::goto('login.php'); 
}

if(isset($_GET['unfollow']))
{
    $user = $_GET['user'];
    DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid',array(':userid'=>$user, ':followerid'=>$userid));
}