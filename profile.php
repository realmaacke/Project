<?php
include('autoload.php');

if (Login::isLoggedIn()) {
    $validLogin = Login::isLoggedIn();
} else {
   Redirect::goto('login.php');
}

//standard variables
$localUsername = "";
$username = "";
$verified = False;
$isFollowing = False;
$hasImage = False;

$localUsername = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$validLogin))[0]['username'];

if (isset($_POST['uploadprofileimg'])) 
{
    Image::uploadImage('profileimg', "UPDATE users SET profileimg = :profileimg WHERE id=:userid", array(':userid'=>$userid));
}


if (isset($_GET['username'])) 
{
        if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))) 
        {    
                // Declaring variables based on querys
                $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
                $userid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
                $verified = DB::query('SELECT verified FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['verified'];
                $img = DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['profileimg'];
                $followerid = Login::isLoggedIn();


                // ammount of posts u made
                $postAmmount = DB::query('SELECT * FROM POSTS WHERE user_id=:userid', array(':userid'=>$userid));
                $postvalue = 0;

                foreach($postAmmount as $postAmmounts)
                {
                        $postvalue++;
                }

                // Ammount that follows u 
                $ammountofFollowers = DB::query('SELECT * FROM followers WHERE user_id =:userid', array(':userid'=>$userid));
                $followervalue = 0;     // follower value == null
                foreach($ammountofFollowers as $ammount) // for each follower ++;
                {
                 $followervalue++;
                }
                // ammount that u follow
                $selfFollow = DB::query('SELECT * FROM followers WHERE follower_id =:userid', array(':userid'=>$userid));
                $clientFollow = 0;     // follower value == null
                foreach($selfFollow as $ammount) // for each follower ++;
                {
                 $clientFollow++;
                }

                if(DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['profileimg'])
                {
                        $hasImage = true;
                }
                else{
                        $hasImage = false;
                }

                if (isset($_POST['follow'])) {

                        if ($userid != $followerid) {

                                if (!DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
                                        if ($followerid == 6) {
                                                DB::query('UPDATE users SET verified=1 WHERE id=:userid', array(':userid'=>$userid));
                                        }
                                        DB::query('INSERT INTO followers VALUES (\'\', :userid, :followerid)', array(':userid'=>$userid, ':followerid'=>$followerid));
                                } else {
                                        echo 'Already following!';
                                }
                                $isFollowing = True;
                        }
                }
                if (isset($_POST['unfollow'])) {

                        if ($userid != $followerid) {

                                if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
                                        if ($followerid == 6) {
                                                DB::query('UPDATE users SET verified=0 WHERE id=:userid', array(':userid'=>$userid));
                                        }
                                        DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid));
                                }
                                $isFollowing = False;
                        }
                }
                if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
                        //echo 'Already following!';
                        $isFollowing = True;
                }

                if (isset($_POST['deletepost'])) {
                        authorization::AdminDeletePost($_GET['postid']);
                }


                if (isset($_POST['post'])) {
                        if ($_FILES['postimg']['size'] == 0) {
                                Post::createPost($_POST['postbody'], Login::isLoggedIn(), $userid);
                        } else {
                                $postid = Post::createImgPost($_POST['postbody'], Login::isLoggedIn(), $userid);
                                Image::uploadImage('postimg', "UPDATE posts SET postimg=:postimg WHERE id=:postid", array(':postid'=>$postid));
                        }
                }

                if (isset($_GET['postid']) && !isset($_POST['deletepost'])) {
                        Post::likePost($_GET['postid'], $followerid, "profile.php'<?php echo $name; ?>'");
                }

                $posts = Post::displayPosts($userid, $username, $followerid);


        } else 
        {
                die('User not found!');
        }
} else {
    Redirect::goto('index.php');
} ?>


<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Visual/style.css">
       <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
       <link rel="icon" type="image/x-icon" href="Visual\img\favicon.ico">
    <script src="https://kit.fontawesome.com/6bfb37676a.js" crossorigin="anonymous"></script>
    <title>COMBINED PROFILE - <?php echo $localUsername?> </title>
</head>
<body>
<div class="navigation">
        <ul>
            <a href="index.php"><h1>COMBINED </h1></a>
                <li> <a href="profile.php?username=<?php echo $localUsername;?>">Profile</a> </li>
                <li> <a href="dm.html">Messages</a> </li>
                <li> <a href="friends.html">Friends</a> </li>
                <li> <a href="search.php">Search</a> </li>
        </ul>
    </div>
</body>
</html>