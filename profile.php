<?php
include('autoload.php');
/////////////////  CHECKING FOR PERMISSION  ////////////////////////////////
if (Login::isLoggedIn())  
{ 
  $userid = Login::isLoggedIn(); 
}
else 
{
  Redirect::goto('login.php'); 
}

if(isset($_GET['username']))
{
  if(!Profile::verify())
  {
    Redirect::goto('index.php');
  }
  $name = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['username'];

  $targetedUser = DB::query('SELECT * FROM users WHERE username=:username',array(':username'=>htmlspecialchars($_GET['username'])));
  $t_id = $targetedUser[0]['id'];
  $t_username = $targetedUser[0]['username'];
  /////////////////  Admin Permissions  ////////////////////////////////
  $isAdmin = authorization::ValidateAdmin($userid);

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

  if(isset($_POST['follow']))
  {
    Profile::FollowAction($t_id, $userid, true);
  }

  if(isset($_POST['unfollow']))
  {
    Profile::FollowAction($t_id, $userid, false);
  }


  $alreadyLiked = false;
  $postIndex = 0;
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
                <div class="P_L_T" style="background-color: <?php echo Profile::ProfileBanner($targetedUser); ?>">
                        <div class="P_L_T_I">
                          <?php
                          echo Profile::displayImage($t_username);
                          ?>

                        </div>
                        <div class="P_L_T_S">
                          <div class="line"></div>
                        <hr>
                          <div class="P_text">
                            <?php
                            echo Profile::Statistic($t_id, $t_username);
                            echo Profile::PermisionBadges($t_id);
                            if($t_id == $userid)
                            {
                            ?> <button type="submit" id="interact-btn" name="button">Edit Profile</button> <?php
                            }
                            if($t_id != $userid)
                            { ?> 
                              <form style="float:right" action="profile.php?username=<?php echo $t_username; ?>" method="POST">
                                <?php 
                                if(Profile::CheckifFollowing($userid, $t_id))
                                {
                                  ?> <button type="submit" id="interact-btn" value="unfollow" name="unfollow">UnFollow</button> <?php
                                } else 
                                {
                                  ?> <button type="submit" id="interact-btn" value="follow" name="follow">Follow</button> <?php
                                } ?>
                                <button type="button" id="interact-btn" value="message" name="message">Message</button>
                              </form>
                                <?php 
                            }
                            ?>
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
