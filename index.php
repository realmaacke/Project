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

if(isset($_GET['like']))
{
  $postid = $_POST['id'];

  if (!DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postid, ':userid'=>$userid))) 
  {
    DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid'=>$postid));
    DB::query('INSERT INTO post_likes VALUES (\'\', :postid, :userid)', array(':postid'=>$postid, ':userid'=>$userid));
  }
}

if(isset($_GET['unlike']))
{
  $postid = $_POST['id'];
  POST::likePost($postid, $userid);
}


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
                <li> <a href="profile.php?username=<?php echo $name;?>">Profile</a> </li>
                <li> <a href="dm.html">Messages</a> </li>
                <li> <a href="friends.html">Friends</a> </li>
                <li> <a href="search.php">Search</a> </li>
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
        
        $comments = DB::query('SELECT * FROM comments WHERE post_id=:targetID',array(':targetID'=>$posts['id']));
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
                <button type='submit' name='unlike' class='btn btn-primary'><?php echo $posts['likes']; ?><i class='far fa-heart'></i></button>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

</body>
</html>

