<?php 
include('autoload.php');
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
        }
         else 
        {
                 Redirect::goto('index.php'); 
        }
}

if(isset($_GET['username'])) 
{
        /////////////////  Users  ////////////////////////////////
        // local user
        $name = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['username'];
        /////////////////  Targeted User  ////////////////////////////////
        $targetedUser = DB::query('SELECT * FROM users WHERE username=:username',array(':username'=>htmlspecialchars($_GET['username'])));

        $t_username = $targetedUser[0]['username'];
        $t_id = $targetedUser[0]['id'];
        $t_profilePic = $targetedUser[0]['profileimg'];

        $hasImage = false;
        if($t_profilePic != null)
        {
         $hasImage = true;
        }
        
        /////////////////  Calculating ammount of POSTS/FOLLOWERS/FOLLOWING  ////////////////////////////////
        $FollowingAmmount = DB::query('SELECT * FROM followers WHERE user_id=:userid',array(':userid'=>$t_id));
        $FollowersAmmount = DB::query('SELECT * FROM followers WHERE follower_id=:userid',array(':userid'=>$t_id));
        $PostsAmmount = DB::query('SELECT * FROM posts WHERE user_id=:userid',array(':userid'=>$t_id));
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
                <div class="P_L_T">
                <button id="edit-btn"> EDIT PROFILE</button>
                        <div class="P_L_T_I">
                        <img src="Visual/img/avatar.png" alt="" width="100%" height="100%">
                        </div>
                
                        <div class="P_L_T_S">
                        <hr>
                        <div class="P_text">
                        <h2>NickName</h2>
                        <h5>@Username</h5>
                        <p>123 followers | 123 Following | 123 Posts</p>
                        </div>
                        </div>
                </div>
                <div class="P_L_B">
                </div>
        </div>

        <div class="P_right">
                
        </div>
</div>


</body>
</html>