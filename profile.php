<?php
include('classes/DB.php');
include('classes/login.php');

$username = "";
$isFollowing = false;

if(isset($_GET['username'])){
    if(DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username'])))
    {

        $username = (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username'])))[0]['username'];
        $userid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
        $followerid = login::isLoggedIn();


        if(isset($_POST['follow'])){

        if($userid != $followerid)
            {
            
                if(!DB::query('SELECT follower_id FROM followers WHERE user_id=:userid', array(':userid'=>$userid)))
                {
                    DB::query('INSERT INTO followers VALUES (\'\', :userid, :followerid)', array(':userid'=>$userid, ':followerid'=>$followerid));
                
                } else 
                {
                    echo "ALREADY FOLLOWING";
                }
                $isFollowing = true;
            }
        }

        if(isset($_POST['unfollow'])){

            if($userid != $followerid){

                if(DB::query('SELECT follower_id FROM followers WHERE user_id=:userid', array(':userid'=>$userid)))
                {
                    DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid));
                }
                $isFollowing = false;
            }
        }


        if(DB::query('SELECT follower_id FROM followers WHERE user_id=:userid', array(':userid'=>$userid)))
        {
           // echo "ALREADY FOLLOWING";
            $isFollowing = true;
        }
        
    } else {
        die("user not found");
    }
}
?>

<h1><?php echo $username?> sProfile</h1>


<form action="profile.php?username=<?php echo $username;?>" method="POST">
    <?php 
    if($userid != $followerid)
    {
        if($isFollowing){
            echo '<input type="submit" name="unfollow" value="Unfollow">';
        } else {
            echo '<input type="submit" name="follow" value="Follow">';
        }
    }
    ?>
</form>