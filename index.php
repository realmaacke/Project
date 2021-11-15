<?php
include('autoload.php');


if (Login::isLoggedIn()) {
  $userid = Login::isLoggedIn();
} else {
 Redirect::goto('login.php');
}

$name = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['username'];

$followingposts = DB::query('SELECT posts.id, posts.body, posts.likes, users.`username` FROM users, posts, followers
WHERE posts.user_id = followers.user_id
AND users.id = posts.user_id
AND follower_id = :userid
ORDER BY posts.id DESC;', array(':userid'=>$userid));

?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Visual/style.css">
    <script src="https://kit.fontawesome.com/6bfb37676a.js" crossorigin="anonymous"></script>
    <title>COMBINED - HOME</title>
</head>
<body>
    <div class="navigation">
        <ul>
            <a href="index.html"><h1>COMBINED</h1></a>
                <li> <a href="profile.html">Profile</a> </li>
                <li> <a href="dm.html">Messages</a> </li>
                <li> <a href="friends.html">Friends</a> </li>
                <li> <a href="search.html">Search</a> </li>
        </ul>
    </div>

    <div class="flow">
     <?php foreach($followingposts as $posts) {
       $hasImage = false;
       $img = DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$posts['username']))[0]['profileimg'];
       
       if(DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$posts['username']))[0]['profileimg'])
        {
         $hasImage = true;
        }
        else{
          $hasImage = false;
        }

        $comments = DB::query('SELECT * FROM comments WHERE post_id=:userid',array(':userid'=>$posts['id']));
        $ammountOfComments = 0;
        foreach($comments as $comment){
          $ammountOfComments++;
        }

       ?>
        <div class="post">
            <div class="left">
                <div class="top">
                  <?php 
                    if($hasImage){ ?> 
                    
                    <img src="<?php echo $img; ?>" alt="" style="margin: auto; margin-left: 5px;" width="80" height="80">
                <?php 
                }
                else {
                  ?> <img src="Visual/img/avatar.png" alt="" style="margin: auto; margin-left: 5px;" width="80" height="80">
                  <?php 
                }
                  ?>
                    
                    <h3><a href="profile.php?username=<?php echo $posts['username'] ?>"><?php echo "@". $posts['username']; ?></a></h3>
                </div>
            </div>
            <div class="right">
                <div id="post-top">
                    <?php 
                    echo Post::link_add($posts['body']);
                    ?>
                </div>
                <div id="post-bottom">
                    <label for="likes"><?php echo $posts['likes'];?> </label>
                    <button><i class="far fa-heart"></i></button>
                    <label for="comments"><?php echo $ammountOfComments; ?></label>
                    <button><i class="far fa-comments"></i></button>
                    <button style="float:right"><i class="fas fa-ellipsis-v"></i></button>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</body>
</html>