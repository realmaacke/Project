<?php
include('./classes/DB.php');
include('./classes/Login.php');
include('./classes/Post.php');
include('./classes/Image.php');
include('./classes/Notify.php');

$username = "";
$verified = False;
$isFollowing = False;
if (isset($_GET['username'])) {
        if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))) {

                

                $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
                $userid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
                $verified = DB::query('SELECT verified FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['verified'];
                $img = DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['profileimg'];
                $followerid = Login::isLoggedIn();

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


        } else {
                die('User not found!');
        }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="GUI/forms.css">
    <link rel="stylesheet" href="GUI/profile.css">
    <link rel="stylesheet" href="GUI/universal/style.css">
    <script src="https://kit.fontawesome.com/6bfb37676a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
 
    <title>Combined Profile</title>
</head>
<body>
<div class="container">
    <div class="main-body">

          <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <img src="<?php echo $img; ?>" alt="Admin" class="rounded-circle" width="150" height="150">
                    <div class="mt-3">
                      <h4><?php echo "@",$username; ?></h4>
                      <p class="text-secondary mb-1"> <span style="color: #08ffbd;">Verified </span></p>

                      <form action="profile.php?username=<?php echo $username; ?>" method="post">
                                <?php
                                if ($userid != $followerid) {
                                        if ($isFollowing) {
                                                echo '<input class="btn btn-primary" type="submit" name="unfollow" value="Unfollow">';
                                        } else {
                                                echo '<input class="btn btn-primary" type="submit" name="follow" value="Follow">';
                                        }
                                }
                                ?>
                        </form>


                      <button class="btn btn-outline-primary">Message</button>
                    </div>
                  </div>
                </div>
              </div>
              <?php
                if ($userid == $followerid)
                {
                
                        ?>
                        <div class="card mt-3">
                        <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                 <h6 class="mb-0"> <a href="my-account.php">Settings <i class="fas fa-cog"></i></a></h6>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                 <h6 class="mb-0"> <a href="">Report a bug <i class="fas fa-user-shield"></i></a></h6>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <h6 class="mb-0"> <a href="logout.php">Logout <i class="fas fa-sign-out-alt"></i></a></h6>
                                </li>
                        </ul>
                        </div>
                        <?php
                        
                }
                 ?>
            </div>      
            <div class="col-md-8">
              <div class="card mb-3">
                <div class="card-body">
                        <form action="profile.php?username=<?php echo $username; ?>" id="post" method="post" enctype="multipart/form-data">
                                <textarea name="postbody" rows="8" cols="70"></textarea>
                                <br />Upload an image:
                                <input type="file" name="postimg">
                                <input type="submit" name="post" value="Post">
                        </form>
                        <hr />

                        <?php echo $posts; ?>
            </div>
          </div>

        </div>
    </div>
</body>
</html>  