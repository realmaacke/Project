<?php
include('autoload.php');  // loading all classes using spl loader

/////////////////  CHECKING FOR PERMISSION  ////////////////////////////////
if (Login::isLoggedIn())  { $userid = Login::isLoggedIn(); }
else {  Redirect::goto('login.php'); }

$isAdmin = false;
if(DB::query('SELECT user_id FROM administrator WHERE user_id=:userid', array(':userid'=>$userid)))
{ $isAdmin = true; }
else
{ $isAdmin = false; }

/////////////////  Admin Permissions  ////////////////////////////////

if(isset($_POST['A_DeleteComment']))
{
  if($isAdmin)
  {
    authorization::AdminDeleteComment(htmlspecialchars($_GET['comment']));
    Redirect::goto('index.php');
  } else
  {
    Redirect::goto('index.php');
  }
}
if(isset($_POST['A_DeletePost']))
{
 if($isAdmin)
 {
    authorization::AdminDeletePost(htmlspecialchars($_GET['post']));
    Redirect::goto('index.php');
 } else
 {
  Redirect::goto('index.php');
 }
}

/////////////////  COMMENT OWNER Permissions  ///////////////////


if(isset($_POST['self_DeleteComment']))
{
  authorization::CommentDelete(htmlspecialchars($_GET['comment']));
}
///////////////// Website Functions  ////////////////////////////////

// defining Variables
$name = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['username'];
$hasImage = false;
$alreadyLiked = false;
$isPostOwner = false;
$postIndex = 0;


// querrying sql for people the user is following and grabing post related itmes(posts.id, posts.body, posts.likes)
$followingposts = DB::query('SELECT posts.id, posts.body, posts.likes, users.`username` FROM users, posts, followers
WHERE posts.user_id = followers.user_id
AND users.id = posts.user_id
AND follower_id = :userid
ORDER BY posts.id DESC;', array(':userid'=>$userid));


// handling form submition
if(isset($_POST['LikeAction']))
{
  POST::likePost(htmlspecialchars($_GET['post']), $userid);
}
if(isset($_POST['commentPost']))
{
  Comment::createComment(htmlspecialchars($_POST['text']),htmlspecialchars($_GET['post']), $userid);
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
       <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
       <link rel="icon" type="image/x-icon" href="Visual\img\favicon.ico">
    <script src="https://kit.fontawesome.com/6bfb37676a.js" crossorigin="anonymous"></script>
    <title>COMBINED - HOME</title>
</head>
<body>
    <div class="navigation">
        <ul>
            <a href="index.php"><h1>COMBINED</h1></a>
                <li> <a href="profile.php?username=<?php echo $name;?>">Profile</a> </li>
                <li> <a href="dm.html">Messages</a> </li>
                <li> <a href="friends.html">Friends</a> </li>
                <li> <a href="search.php">Search</a> </li>
        </ul>
    </div>
    <div class="flow">
     <?php
     foreach($followingposts as $posts) { // foreach post as $post =>key
       // defining bools to prevent error
        $postIndex++;
       // querrying for profile img, if $hasimage bool is true
       $img = DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$posts['username']))[0]['profileimg'];

        //  Check if post user got any profile image, if not use the default one in Visual/img/..
       if(DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$posts['username']))[0]['profileimg']) { $hasImage = true; }
        else{ $hasImage = false;  }

        // same here as the one before
        if (DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$posts['id'], ':userid'=>$userid))) { $alreadyLiked = true;}
        else {  $alreadyLiked = false; }

        // querrying the likes based on what post it is
        $calculateLikes = DB::query('SELECT * FROM post_likes WHERE post_id=:targetID', array(':targetID'=>$posts['id']));
        $ammountOfLikes = 0;  // standard value
        foreach($calculateLikes as $like){  // for each like ++
          $ammountOfLikes++;
        }

        // calculate how many comments the post have
        $calculateComments = DB::query('SELECT comment FROM comments WHERE post_id=:targetID',array(':targetID'=>$posts['id']));
        $ammountOfComments = 0;
        foreach($calculateComments as $calculate){
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
                    echo Post::link_add($posts['body']);  // link_add is an function that separates characters that start with an @ as a userlink.
                    ?>
                </div>
                <div id="post-bottom">
                <form action="index.php?post=<?php echo $posts['id'];?>" method="POST" style="width:50%; float:left">
                <?php
                if(!$alreadyLiked){
                  ?> <button type='submit'  name='LikeAction' value="LikeAction" class='btn btn-primary'><?php echo $ammountOfLikes;?> <i class='far fa-heart'></i></button> <?php
                } else {
                  ?> <button type='submit'  name='LikeAction' value="LikeAction" class='btn btn-primary'><?php echo $ammountOfLikes;?> <i class='fas fa-heart'></i></button> <?php
                }
                ?>
                  <button type="button"  value="<?php echo $postIndex; ?>" id="CommentBTN" class='btn btn-primary'><?php echo $ammountOfComments; ?>  <i class="far fa-comments"></i></button>
                <?php
                if($isAdmin)
                {
                  ?>
                  <form action="index.php?postid=<?php $posts['id'];?>" method="POST">
                      <button type='submit' style='color:red;' name='A_DeletePost' class='btn btn-primary'><i class="far fa-trash-alt"></i></button>
                  </form>
                <?php
                }
                ?>
                </form>
                </div>
            </div>
        </div>
      <div class="comments" id="<?php echo $postIndex; ?>">
      <div class="PostComment">
        <form action="index.php?post=<?php echo $posts['id'];?>" method="POST">
            <textarea name="text" value="text" placeholder="Comment Something!" class="textAreaComment" id="" cols="80" rows="2"></textarea>
            <button id='commentPost' name="commentPost" class='btn btn'>Send <i class="fas fa-arrow-right"></i></button>
            <hr>
          </form>
      </div>
        <?php
        $commentIndex = 0;
        $comment = DB::query('SELECT * FROM comments WHERE post_id=:postid', array(':postid'=>$posts['id']));
        $commentOwner = false;
        foreach($comment as $cmt)
        {
          $commentIndex++;
          $cmtName = DB::query('SELECT username FROM users WHERE id=:userid',array(':userid'=>$cmt['user_id']))[0]['username'];
          if($cmt['user_id'] == $userid){
            $commentOwner = true;
          }
          ?>
        <div class="C_item">
          <?php
          if($commentOwner && !$isAdmin)
             { ?>
               <form style="float:right; padding-right:50px;" action="index.php?comment=<?php echo $cmt['id'];?>" method="POST">
                      <button type='submit' style="color:red; float:right" name='self_DeleteComment' class='btn btn'><i class="far fa-trash-alt"></i></button>
                  </form>
               <?php
             } ?>
            <h2 ><a href="profile.php?username=<?php echo $cmtName;?>"> <?php echo $cmtName ?></a> -</h2>
            <p ><?php echo Post::link_add($cmt['comment']); ?>
             <?php
             if($isAdmin)
                {?>
                 <form style="float:right; padding-right:50px;" action="index.php?comment=<?php echo $cmt['id'];?>" method="POST">
                      <button type='submit' style="color:red;" name='A_DeleteComment' class='btn btn'><i class="far fa-trash-alt"></i></button>
                  </form>
                 <?php
                } ?> </p>
            <?php
            $commentUser = $cmt['user_id'];
            ?>
            <div class="cmtLine"></div>
            <hr>
        </div>
       <?php }
        ?>
      </div>
      <?php
      ?>
 <?php }

if($postIndex < 1)
{
  ?>

  <div class="startBox">
    <h2>This is your Timeline where you can se the activity of those you follow.</h2>
    <h3>to follow people use our <a href="search.php"> Search function</a> to look after Friends</h3>
  </div>

  <?php
}

?>


    </div>
</script>
</body>
</html>

<script>
  // script that opens the comment section
$("button").click(function() {  // grabing the button type.
    var commentValue = $(this).val(); // grabing the value of the button, (set to the postindex)
    row = $('#' + commentValue);

    //switching the css when true
    if(row.css('display') === 'none')
    {
      row.css('display', 'inline-block');
    }
    else if(row.css('display') === 'inline-block')
    {
      row.css('display', 'none');
    }
    else{
      return;
    }
});
</script>

<script>
  $("delete").click(function()
{
    $.ajax({
    type: "POST",
    url: url,
    data: "DELETE",
    success: success,
    dataType: dataType
  });
});
</script>
