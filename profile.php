<?php
include('autoload.php');  // autoloader

if (Login::isLoggedIn())   // validating user
{ 
  $userid = Login::isLoggedIn(); 
}
else 
{
  Redirect::goto('login.php'); 
}

$imageToAdd = "No Image Selected";

if(isset($_GET['username']))
{
  if(!Profile::verify())  // verifiyng so there exists a user to display the profile
  {
    Redirect::goto('index.php');
  }
  $localuser = User::getUserByID($userid);
  $target = User::getUserByName(escape($_GET['username']));
  $isAdmin = authorization::ValidateAdmin($userid);

  if(isset($_POST['PostBtn']))
  {
      if ($_FILES['postimg']['size'] == 0) 
      {
        Post::createPost($_POST['postbody'], Login::isLoggedIn(), $userid);
      } 
      else 
      {
        $postid = Post::createImgPost($_POST['postbody'], Login::isLoggedIn(), $userid);
        Image::uploadImage('postimg', "UPDATE posts SET postimg=:postimg WHERE id=:postid", array(':postid'=>$postid));
      }
  }
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
    <title>COMBINED PROFILE - <?php echo $target[0]['username']?> </title>
</head>
<body>

<?php include 'navigation.php';  ?>

<div class="P_Main">
        <div class="P_left">
                <div class="P_L_T" style="background-color: <?php echo Profile::ProfileBanner($target); ?>">
                        <div class="P_L_T_I">
                          <?php
                          echo Profile::displayImage($target[0]['username'], true);
                          ?>

                        </div>
                        <div class="P_L_T_S">
                          <div class="line"></div>
                        <hr>
                          <div class="P_text">
                            <?php
                            echo Profile::Statistic($target[0]['id'], $target[0]['username']);
                            echo Profile::PermisionBadges($target[0]['id']); ?>

                            </div>
                            <div class="P_text_right"> <?php
                            if($target[0]['id'] == $userid)
                            {
                            ?>
                            <a href="logout.php" style="color: red;" id="interact-btn">Sign out <i class="fas fa-sign-out-alt"></i></a>
                            <a href="my-account.php?username=<?php echo $localuser[0]['username'];?>" style="color: #83e2b2;" id="interact-btn" name="button">Settings <i class="fas fa-cog"></i></a> <?php
                            }
                            if($target[0]['id'] != $userid)
                            { ?> 
                              <form style="float:right" method="POST">
                                <?php 
                                if(Profile::CheckifFollowing($userid, $target[0]['id']))
                                {
                                  ?> <button type="button" id="follow" value="<?php echo $target[0]['id']; ?>"  name="unfollow">Unfollow</button> <?php
                                } else 
                                {
                                  ?> <button type="button" id="follow" value="<?php echo $target[0]['id']; ?>"  name="follow">Follow</button> <?php
                                } ?>
                                <a href="dm/chat.php?id=<?php echo $target[0]['id']; ?>" id="interact-btn">Message</a>
                              </form>
                                <?php 
                            }
                            ?>
                          </div>
                          
                          
                      </div>
                </div>
                <?php if($target[0]['id'] == $userid)
                { ?> 
                  <div class="P_L_B">
                    <div class="PostBox">
                        <form action="profile.php?username=<?php echo $target[0]['username']; ?>" method="POST" enctype="multipart/form-data">
                          <div class="post_top" id="form">
                            <textarea data-emojiable="true" name="postbody" value="text" placeholder="Comment Something!" class="textAreaPost" id="emoji" cols="80" rows="2"></textarea>
                          </div>
                        <div class="post_bottom">
                              <input id="extrabtns-post" type="file" name="postimg" hidden>
                              <label id="labelPic" for="extrabtns-post" class="btn btn"><i class="far fa-images"></i></label>
                              <button name="PostBtn" value="PostBtn" type="submit" id="postbtn-profile" class='btn btn'>Post <i class="fas fa-arrow-right"></i></button>
                        </div>
                        </form>
                    </div>
                  </div>
                  <?php } ?>
        </div>
 
          <div class="flow">
            <?php Post::Posts($userid, $target[0]['username'], $target[0]['id'], $isAdmin, false); ?>
          </div>
      </div>
  </div>
</div>
<script src="main.js" type="text/javascript">
</script>

</body>
</html>
