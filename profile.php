<?php
include('autoload.php');
/////////////////  CHECKING FOR PERMISSION  ////////////////////////////////
if (Login::isLoggedIn())  { $userid = Login::isLoggedIn(); }
else {  Redirect::goto('login.php'); }


if(isset($_GET['username']))
{
  $verify = DB::query('SELECT * FROM users WHERE username=:username',array(':username'=>htmlspecialchars($_GET['username'])));

  if($verify[0]['username'] == 0){
    Redirect::goto('index.php');
  }

        /////////////////  Users  ////////////////////////////////
        // local user
        $name = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['username'];
        /////////////////  Targeted User  ////////////////////////////////
        $targetedUser = DB::query('SELECT * FROM users WHERE username=:username',array(':username'=>htmlspecialchars($_GET['username'])));
        $t_id = $targetedUser[0]['id'];
        $t_username = $targetedUser[0]['username'];
        $t_profilePic = $targetedUser[0]['profileimg'];
        $t_verified = $targetedUser[0]['verified'];
        $hasImage = false;

        /////////////////  Admin Permissions  ////////////////////////////////
        $isAdmin = false;
        if(DB::query('SELECT user_id FROM administrator WHERE user_id=:userid', array(':userid'=>$t_id)))
        { $isAdmin = true; }
        else
        { $isAdmin = false; }
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
                }
                 else
                {
                         Redirect::goto('index.php');
                }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if(DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$t_username))[0]['profileimg']) { $hasImage = true; }
         else{ $hasImage = false;  }
        $img = DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$t_username))[0]['profileimg'];

        /////////////////  Calculating ammount of POSTS/FOLLOWERS/FOLLOWING  ////////////////////////////////
        $FollowingAmmount = DB::query('SELECT * FROM followers WHERE user_id=:userid',array(':userid'=>$t_id));
        $FollowersAmmount = DB::query('SELECT * FROM followers WHERE follower_id=:userid',array(':userid'=>$t_id));
        $PostsAmmount = DB::query('SELECT * FROM posts WHERE user_id=:userid',array(':userid'=>$t_id));


        $alreadyLiked = false;
        $isPostOwner = false;
        $postIndex = 0;


        $followingCount = 0;
        $followersCount = 0;
        $postCount = 0;
        foreach($FollowingAmmount as $following){
          $followingCount++;
        }
        foreach($FollowersAmmount as $followers){
          $followersCount++;
        }
        foreach($PostsAmmount as $ammountOfPosts){
          $postCount++;
        }


        /////////////// Background Banner Hash Map and INSERT INTO DB IF OWN IS PICKED
        // template colors
        $colorMap[0] = '#5ed6a0'; // DEFAULT
        $colorMap[1] = '#0000FF'; //blue
        $colorMap[2] = '#FF0000'; //red
        $colorMap[3] = '#330000'; //dark red
        // 4 is always the users made
        $userBanner = false;
        if($targetedUser[0]['colorbanner']){
          $userBanner = true;
          $colorMap[4] = $targetedUser[0]['colorbanner'];
        }


}
else
{
        Redirect::goto('index.php');
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
    <title>COMBINED PROFILE - <?php echo $t_username?> </title>
</head>
<body>

<div class="navigation">
        <ul>
            <a href="index.php"><h1>COMBINED </h1></a>
                <li> <a href="profile.php?username=<?php echo $name;?>">Profile</a> </li>
                <li> <a href="dm.html">Messages</a> </li>
                <li> <a href="friends.html">Friends</a> </li>
                <li> <a href="search.php">Search</a> </li>
        </ul>
</div>

<div class="P_Main">
        <div class="P_left">
                                                    <!-- checking if user got own banner else use default -->
                <div class="P_L_T" style="background-color: <?php if($userBanner){ echo $colorMap[4];} else { echo $colorMap[0];} ?>">

                  <?php
                  if($t_id == $userid){
                    ?> <button id="edit-btn"> EDIT PROFILE</button> <?php
                  }
                  ?>

                        <div class="P_L_T_I">
                          <?php
                          if($hasImage)
                          { ?>
                            <img src="<?php echo $img; ?>" alt="" width="100%" height="100%">
                          <?php }
                          else {
                            ?> <img src="Visual/img/avatar.png" alt="" width="100%" height="100%"> <?php
                          }
                          ?>

                        </div>
                        <div class="P_L_T_S">
                          <div class="line"></div>
                        <hr>
                          <div class="P_text">
                            <h2><?php echo ucfirst($t_username); ?></h2>
                            <h7>@<?php echo $t_username; ?></h7>
                            <p><?php echo $followersCount;?> followers | <?php echo $followingCount; ?> Following | <?php echo  $postCount; ?> Posts</p>
                            <?php
                            if($t_verified)
                            {
                              ?> <img src="Visual/img/verified.png" alt="">  <?php
                            }
                            if($isAdmin)
                            {
                              ?> <img src="Visual/img/Moderator.png" style="margin-left: 12px;" alt="">  <?php
                            }
                            ?>
                            <button type="button" id="interact-btn" name="button">Follow</button>
                            <button type="button" id="interact-btn" name="button">Messages</button>
                          </div>
                        </div>
                </div>
                <?php
                if($t_id == $userid)
                {
                  ?>
                  <div class="P_L_B">
                  </div>
                  <?php
                }
                ?>
        </div>

        <div class="flow">
           <?php
           $posts = DB::query('SELECT * FROM posts WHERE user_id=:targetID', array(':targetID'=>$t_id));
           foreach($posts as $p)
           {
             $postUsername = DB::query('SELECT username from users WHERE id=:targetID',array(':targetID'=>$p['id']))[0]['username'];
              $postIndex++;
              echo $postUsername;
              $img = DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$postUsername))[0]['profileimg'];

               //  Check if post user got any profile image, if not use the default one in Visual/img/..
              if(DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$postUsername))[0]['profileimg']) { $hasImage = true; }
               else{ $hasImage = false;  }

               // same here as the one before
               if (DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$userid))) { $alreadyLiked = true;}
               else {  $alreadyLiked = false; }

               // querrying the likes based on what post it is
               $calculateLikes = DB::query('SELECT * FROM post_likes WHERE post_id=:targetID', array(':targetID'=>$p['id']));
               $ammountOfLikes = 0;  // standard value
               foreach($calculateLikes as $like){  // for each like ++
                 $ammountOfLikes++;
               }

               // calculate how many comments the post have
               $calculateComments = DB::query('SELECT comment FROM comments WHERE post_id=:targetID',array(':targetID'=>$p['id']));
               $ammountOfComments = 0;
               foreach($calculateComments as $calculate){
                 $ammountOfComments++;
               }
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

                          <h3><a href="profile.php?username=<?php echo $postUsername; ?>"><?php echo "@" . $postUsername;?></a></h3>
                      </div>
                  </div>
                  <div class="right">
                      <div id="post-top">
                          <?php
                          echo Post::link_add($p['body']);  // link_add is an function that separates characters that start with an @ as a userlink.
                          ?>
                      </div>
                      <div id="post-bottom">
                      <form action="index.php?post=" method="POST" style="width:50%; float:left">
                      <?php
                      if(!$alreadyLiked){
                        ?> <button type='submit'  name='LikeAction' value="LikeAction" class='btn btn-primary'><?php ?> <i class='far fa-heart'></i></button> <?php
                      } else {
                        ?> <button type='submit'  name='LikeAction' value="LikeAction" class='btn btn-primary'><?php ?> <i class='fas fa-heart'></i></button> <?php
                      }
                      ?>
                        <button type="button"  value="<?php echo $postIndex; ?>" id="CommentBTN" class='btn btn-primary'><?php  ?>  <i class="far fa-comments"></i></button>
                      <?php
                      if($isAdmin)
                      {
                        ?>
                        <form action="index.php?postid=" method="POST">
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
              <form action="index.php?post=" method="POST">
                  <textarea name="text" value="text" placeholder="Comment Something!" class="textAreaComment" id="" cols="80" rows="2"></textarea>
                  <button id='commentPost' name="commentPost" class='btn btn'>Send <i class="fas fa-arrow-right"></i></button>
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
             <?php } ?>
            </div>

</div>

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

</body>
</html>
