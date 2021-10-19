<?php
include('autoload.php');
$showTimeline = False;
if (Login::isLoggedIn()) {
        $userid = Login::isLoggedIn();
        $showTimeline = True;
} else {
        Redirect::goto("login.php");
}
 
if (isset($_GET['postid'])) {
        Post::likePost($_GET['postid'], $userid);
}
if (isset($_POST['comment'])) {
        Comment::createComment($_POST['commentbody'], $_GET['postid'], $userid);
}

if (isset($_POST['searchbox'])) 
{
        $tosearch = explode(" ", $_POST['searchbox']);
        if (count($tosearch) == 1)
        {
                $tosearch = str_split($tosearch[0], 2); 
        }
        $whereclause = "";
        $paramsarray = array(':username'=>'%'.$_POST['searchbox'].'%');

        for ($i = 0; $i < count($tosearch); $i++) 
        {     // looping through results array
                $whereclause .= " OR username LIKE :u$i ";
                $paramsarray[":u$i"] = $tosearch[$i];
        }
        // users search
        $users = DB::query('SELECT users.username FROM users WHERE users.username LIKE :username '.$whereclause.'', $paramsarray); // <-- Searchin both array and string
        
        foreach($users as $user)
        { ?>

         <a href="profile.php?username=<?php echo $user['username']?>"><?php echo $user['username'] ?></a>

  <?php }

        $whereclause = "";
        $paramsarray = array(':body'=>'%'.$_POST['searchbox'].'%');
        for ($i = 0; $i < count($tosearch); $i++) {
                if ($i % 2) {   // searchin for every 2nd word
                $whereclause .= " OR body LIKE :p$i ";
                $paramsarray[":p$i"] = $tosearch[$i];
                }
        }
        // post search
        $posts = DB::query('SELECT posts.body FROM posts WHERE posts.body LIKE :body '.$whereclause.'', $paramsarray);
}

$followingposts = DB::query('SELECT posts.id, posts.body, posts.likes, users.`username` FROM users, posts, followers
WHERE posts.user_id = followers.user_id
AND users.id = posts.user_id
AND follower_id = :userid
ORDER BY posts.id DESC;', array(':userid'=>$userid));

?> 

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Home - COMBINED</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/index-style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
    <link rel="stylesheet" href="assets/fonts/simple-line-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css">
    <link rel="stylesheet" href="assets/css/vanilla-zoom.min.css">
</head>
<body>
<nav class="navbar navbar-light navbar-expand-lg fixed-top bg-white clean-navbar">
        <div class="container"><a class="navbar-brand logo" href="#">COMBINED<br></a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.html" style="filter: blur(0px);">SEARCH&nbsp;</a></li>
                    <li class="nav-item"><a class="nav-link" href="features.html">DISCOVER</a></li>
                    <li class="nav-item"><a class="nav-link" href="pricing.html">MESSAGES<br></a></li>
                    <li class="nav-item"><a class="nav-link" href="about-us.html">MORE</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact-us.html">PROFILE</a></li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="Main">


    <?php foreach($followingposts as $post) { ?>


        <!-- Post starts -->
        <div class="Post">
            <div class="PostHeader">
                <img src="assets/avatar.png" id="avatar" width="76" height="76" alt="">
                 <h3 id="username"><a href="profile.php?username=<?php echo $post['username']; ?>">@<?php echo $post['username'] ?></a></h3>
                 <a href="" id="report"><i class="fas fa-ellipsis-h"></i></a>
            </div>
            <hr />

            <div class="PostContent">
                <p><?php echo $post['body'] ?></p>
            </div>
            <hr />
            <div class="interactions">
            <form action='index.php?postid="<?php $post['id']?> "' method='post'>
                <?php if (!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$post['id'], ':userid'=>$userid))) 
                { ?>
                <input type='submit' name='like' value='Like' hidden/>
                <label for="like"><i class="far fa-heart"></i> <span><?php echo $post['likes'] ?></span></label>

          <?php } ?>
                <a href=""><i class="far fa-comment"></i> <span>123124</span></a>
                <a href=""><i class="far fa-save"></i></a>
            </div>
            <hr>
        </div>
        <!-- Post Ends -->
    </div>

    <?php

        if (!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$post['id'], ':userid'=>$userid))) {

        echo "<input type='submit' name='like' value='Like'>";
        } else {
        echo "<input type='submit' name='unlike' value='Unlike'>";
        }
        echo "<span>".$post['likes']." likes</span>
        </form>
        <form action='index.php?postid=".$post['id']."' method='post'>
        <textarea name='commentbody' rows='3' cols='50'></textarea>
        <input type='submit' name='comment' value='Comment'>
        </form>
        ";
        Comment::displayComments($post['id']);
        echo "
        <hr /></br />";


}?>


<script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
    <script src="assets/js/vanilla-zoom.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="https://kit.fontawesome.com/6bfb37676a.js" crossorigin="anonymous"></script>
        
</body>
</html>