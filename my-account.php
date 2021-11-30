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
$imageToAdd = "No Image Selected";

if(isset($_GET['username']))
{
  if(!Profile::verify())        // verrifying the username
  {
    Redirect::goto('index.php');
  }

  $user = DB::query('SELECT * FROM users WHERE id=:userid',array(':userid'=>$userid));
  if($_GET['username'] != $user[0]['username']) // controlling so no one else can login to the edit page
  {
        Redirect::goto('index.php');
  }

  if(isset($_POST['Change']))
  {
          Profile::changeProfileBanner($userid);
  }

  if(isset($_POST['uploadprofileimg'])){
        Image::uploadImage('profileimg', "UPDATE users SET profileimg = :profileimg WHERE id=:userid", array(':userid'=>$userid));
  }

}
else {
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
    <title>COMBINED Edit Profile</title>
</head>
<body>

<div class="navigation"> 
        <ul>
            <a href="index.php"><h1>COMBINED </h1></a>
                <li> <a href="profile.php?username=<?php echo $user[0]['username'];?>">Profile</a> </li>
                <li> <a href="dm.php">Messages</a> </li>
                <li> <a href="search.php">Search</a> </li>
        </ul>
</div>

<div class="P_Main">
        <div class="P_left">
                <div class="P_L_T" id="banner" style="background-color: <?php echo Profile::ProfileBanner($user); ?>">
                <div class="bannerSettings">

                </div>
                        <div class="P_L_T_I">
                          <?php
                          echo Profile::EditProfileImg($user[0]['username']);
                          ?>
                        </div>
                        <div class="P_L_T_S">
                          <div class="line"></div>
                        <hr>
                          <div class="P_text">
                            <?php
                            echo Profile::PermisionBadges($userid);
                            ?>
                          </div>
                          <form action="my-account.php?username=<?php echo $user[0]['username']; ?>" method="POST">
                                <input type="color" name="color" id="bannerColor">
                                <input type="submit" name="Change" value="Change Color">
                          </form>
                      </div>
                </div>
        </div>

      </div>
  </div>
</div>

<form action="my-account.php?username=<?php echo $user[0]['username']; ?>" method="post" enctype="multipart/form-data">
<input type="file" id="imgupload" name="profileimg" hidden />
<input type="submit" id="submitPicture" name="uploadprofileimg" value="Upload Image" hidden />
</form>

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

$('#OpenImgUpload').click(function(){   // triggers file "explorer"
 $('#imgupload').trigger('click'); });


document.getElementById("imgupload").onchange = function() {    // send profile pic post form when file != null
        $('#submitPicture').trigger('click');
};

</script>
</body>
</html>
