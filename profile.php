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
  if(!Profile::verify())
  {
    Redirect::goto('index.php');
  }
  $name = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['username'];

  $targetedUser = DB::query('SELECT * FROM users WHERE username=:username',array(':username'=>htmlspecialchars($_GET['username'])));
  $t_id = $targetedUser[0]['id'];
  $t_username = $targetedUser[0]['username'];
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
    <title>COMBINED PROFILE - <?php echo $t_username?> </title>
</head>
<body>

<div class="navigation"> 
        <ul>
            <a href="index.php"><h1>COMBINED </h1></a>
                <li> <a href="profile.php?username=<?php echo $name;?>">Profile</a> </li>
                <li> <a href="dm.php">Messages</a> </li>
                <li> <a href="search.php">Search</a> </li>
        </ul>
</div>

<div class="P_Main">
        <div class="P_left">
                <div class="P_L_T" style="background-color: <?php echo Profile::ProfileBanner($targetedUser); ?>">
                        <div class="P_L_T_I">
                          <?php
                          echo Profile::displayImage($t_username, true);
                          ?>

                        </div>
                        <div class="P_L_T_S">
                          <div class="line"></div>
                        <hr>
                          <div class="P_text">
                            <?php
                            echo Profile::Statistic($t_id, $t_username);
                            echo Profile::PermisionBadges($t_id); ?>

                            </div>
                            <div class="P_text_right"> <?php
                            if($t_id == $userid)
                            {
                            ?>
                            <a href="logout.php" style="color: red;" id="interact-btn">Sign out</a>
                            <a href="my-account.php?username=<?php echo $name;?>" style="color: #83e2b2;" id="interact-btn" name="button">Settings</a> <?php
                            }
                            if($t_id != $userid)
                            { ?> 
                              <form style="float:right" method="POST">
                                <?php 
                                if(Profile::CheckifFollowing($userid, $t_id))
                                {
                                  ?> <button type="button" id="follow" value="<?php echo $t_id; ?>"  name="unfollow">Unfollow</button> <?php
                                } else 
                                {
                                  ?> <button type="button" id="follow" value="<?php echo $t_id; ?>"  name="follow">Follow</button> <?php
                                } ?>
                                <a href="chat.php?user_id=<?php echo $t_id; ?>" id="interact-btn">Message</a>
                              </form>
                                <?php 
                            }
                            ?>
                          </div>
                          
                          
                      </div>
                </div>
                <?php if($t_id == $userid)
                { ?> 
                  <div class="P_L_B">
                    <div class="PostBox">
                        <form action="profile.php?username=<?php echo $t_username; ?>" method="POST" enctype="multipart/form-data">
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
            <?php Post::ProfilePosts($userid, $t_username, $t_id, $isAdmin); ?>
          </div>
      </div>
  </div>
</div>
<script src="main.js" type="text/javascript">
  $(document).ready(function () 
{
  $('[data-id]').click(function() 
  {
    var buttonid = $(this).attr('data-id');

      $.ajax({

            type: "POST",
            url: "api/likes?id=" + $(this).attr('data-id'),
            processData: false,
            contentType: "application/json",
            data: '',
            success: function(r) 
            {
            var res = JSON.parse(r);
            $("[data-id='"+buttonid+"']").html(' '+res.Likes+' <i class="far fa-heart" data-aos="flip-right"></i><span></span>')
            },

            error: function(r) 
            {
                console.log(r)
            }
      
      });

  })

  $('#follow').click(function() 
  {
      $.ajax({

            type: "POST",
            url: "api/follow?user=" + $(this).val(),
            processData: false,
            contentType: "application/json",
            data: '',
            success: function(r) 
            {
              $('#follow').html(r);
            },

            error: function(r) 
            {
              console.log(r)
            }
      });
  })

  // this is the id of the form
  $("#commentForm").submit(function(e) 
    {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);

            $.ajax
            ({
                    type: "POST",
                    url: "api/comment",
                    data: form.serialize(), // serializes the form's elements.
                    success: function(data)
                    {
                    location.reload(); 
                    }
            });
    });

      // this is the id of the form
  $("#deletePost").submit(function(e) 
  {

  e.preventDefault(); // avoid to execute the actual submit of the form.

  var form = $(this);

          $.ajax
          ({
                  type: "POST",
                  url: "api/deletePost",
                  data: form.serialize(), // serializes the form's elements.
                  success: function(data)
                  {
                    console.log(data)
                  location.reload(); 
                  }
          });
  });

  $("#deleteComment").submit(function(e) 
  {

  e.preventDefault(); // avoid to execute the actual submit of the form.

  var form = $(this);

          $.ajax
          ({
                  type: "POST",
                  url: "api/deleteComment",
                  data: form.serialize(), // serializes the form's elements.
                  success: function(data)
                  {
                    console.log(data)
                  location.reload(); 
                  }
          });
  });

  $("button").click(function() {
      var commentValue = $(this).val(); 
    
      if(commentValue == "like")
      {
        return;
      } 
      else if(commentValue == "unlike")
      {
        return;
      }
      else 
      {
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
      else
      {
        return;
      }

    }
  });
});
</script>

</body>
</html>
