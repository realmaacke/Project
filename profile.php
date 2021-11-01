<?php

include('autoload.php');

if (Login::isLoggedIn()) {
    $validLogin = Login::isLoggedIn();
} else {
   Redirect::goto('login.php');
}

//standard variables
$username = "";
$verified = False;
$isFollowing = False;
$hasImage = False;

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
                        if (DB::query('SELECT id FROM posts WHERE id=:postid AND user_id=:userid', array(':postid'=>$_GET['postid'], ':userid'=>$followerid))) {
                                DB::query('DELETE FROM posts WHERE id=:postid and user_id=:userid', array(':postid'=>$_GET['postid'], ':userid'=>$followerid));
                                DB::query('DELETE FROM post_likes WHERE post_id=:postid', array(':postid'=>$_GET['postid']));
                                echo 'Post deleted!';
                        }
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
                        Post::likePost($_GET['postid'], $followerid);
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
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Profile - COMBINED</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="GUI/index-style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
    <link rel="stylesheet" href="assets/fonts/simple-line-icons.min.css">
    <link rel="stylesheet" href="assets/css/vanilla-zoom.min.css">
    <link rel="stylesheet" href="GUI/profile.css">
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
<script src="assets/js/vanilla-zoom.js"></script>
<script src="assets/js/theme.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/6bfb37676a.js" crossorigin="anonymous"></script>
</head>


<body>
    <nav class="navbar navbar-light navbar-expand-lg fixed-top bg-white clean-navbar">
        <div class="container">
            <a class="navbar-brand logo" href="index.php">COMBINED</a>
            <button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1">
                <span class="visually-hidden">Toggle navigation</span>
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.html">SEARCH</a></li>
                    <li class="nav-item"><a class="nav-link" href="features.html">DISCOVER<br></a></li>
                    <li class="nav-item"><a class="nav-link active" href="pricing.html">MESSAGES</a></li>
                    <li class="nav-item"><a class="nav-link" href="new.php?username=<?php echo $username; ?>">PROFILE<br></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="page pricing-table-page" style="height: 100%;">
        <section style="height: 1041px;margin-top: -29px;">
            <div class="container" style="height: 765px;min-height: 15px;margin-top: 61px;width: 1343px;">
                <div class="row">
                    <div class="col-md-6" style="height: 872px;">
                        <div class="card"
                            style="height: 457px;width: 516px;margin-left: 48px;box-shadow: 5px 5px rgb(230,234,237);">
                            <div class="card-body">
                                <div style="height: 244px;">
                                    <div style="height: 177px;width: 203px;margin: auto;margin-top: 2px;">

                                    <?php
                                    if($hasImage)
                                    {
                                        ?> <img src="<?php echo $img; ?>"  style="width: 100%; height: 100%; filter: blur(0px); box-shadow: 0px 34px 57px -12px rgba(161,161,161,1);
                                        -webkit-box-shadow: 0px 34px 57px -12px rgba(161,161,161,1);
                                        -moz-box-shadow: 0px 34px 57px -12px rgba(161,161,161,1);" >  <?php 
                                    }else 
                                    {
                                        ?> <img src="assets/avatar.png"  style="width: 100%;height: 100%;filter: blur(0px); box-shadow: 0px 34px 57px -12px rgba(161,161,161,1);
                                        -webkit-box-shadow: 0px 34px 57px -12px rgba(161,161,161,1);
                                        -moz-box-shadow: 0px 34px 57px -12px rgba(161,161,161,1);"" >  <?php
                                    }
                                    
                                    ?>
                                    </div>
                                    <div style="height: 64px;">
                                    <!-- verified here -->
                                    <p style="float: right"></p>    
                                    <h1 style="margin-top: 9px;"><?php echo "@",$username; ?></h1>
                                        
                                    </div>
                                </div>
                                <div style="height: 192px;">
                                    <div id="stripe_white"></div>
                                    <div style="height: 29px;margin-top: 14px;">
                                        <p><?php echo $postvalue; ?> Posts <?php  echo $clientFollow;?> Following <?php echo $followervalue; ?> Followers</p>
                                    </div>
                                    <div style="height: 141px;">
                                        <div id="stripe_white"></div>
                                        <div style="height: 103px;margin: auto;width: 199px;">


                                    <form action="profile.php?username=<?php echo $username; ?>" method="post">
                                    <?php if ($userid != $followerid) 
                                    {
                                        ?>

                                        <?php
                                        if ($isFollowing) 
                                        {
                                                echo '<input class="btn btn-primary" style="margin-left: 45px;margin-top: 10px;" type="submit" name="unfollow" value="Unfollow">';
                                        } else {
                                            echo '<input class="btn btn-primary" style="margin-left: 55px;margin-top: 10px;" type="submit" name="follow" value="Follow">';
                                        }
                                    }
                                    ?>
                                    </form>
                                    <?php 
                                    if($userid != $followerid)
                                    { ?>
                                         <button class="btn btn-primary" type="button" style="margin-left: 46px;margin-top: 3px;">Message</button>
                                    <?php } ?>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card"
                            style="width: 516px;height: 275px;margin-left: 48px;margin-top: 25px;box-shadow: 5px 5px rgb(230,234,237);">
                            <div class="card-body" style="height: 316px;">
                                <div style="height: 68px;margin-top: 13px;">
                                    <h1 id="settings"><a href="my-account.php"> <i class="fas fa-sliders-h"></i> Settings</a></h1>
                                    <div id="stripe_white"></div>
                                </div>
                                <div style="height: 68px;margin-top: 13px;">
                                    <h1 id="settings"><a href="report.php"> <i class="fas fa-bug"></i> Report a bug</a></h1>
                                    <div id="stripe_white"></div>
                                </div>
                                <div style="height: 68px;margin-top: 13px;">
                                    <h1 id="settings"><a href="logout.php"> <i class="fas fa-sign-out-alt"></i> Sign out</a></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" style="height: 800px;">
                        <div class="card" style="height: 759px;box-shadow: 5px 5px rgb(230,234,237);">
                                            <!-- posts -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

</body>
</html>