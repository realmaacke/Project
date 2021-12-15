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

$Notify = "";
if(Notify::NavbarNotification($userid))
{
  $Notify = "<i class='fas fa-comment-dots' style='color:yellow;'></i>";
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
                <li> <a href="dm.php">Messages <?php echo $Notify; ?></a> </li>
                <li> <a href="search.php">Search</a> </li>
        </ul>
    </div>
    <div class="flow">
      <?php Post::Posts($userid, "", "", $isAdmin, true) ?>
   </div>


<script src="main.js" type="text/javascript">
  $(document).ready(function () 
{
  $('[data-id]').click(function() 
  {
    var buttonid = $(this).attr('data-id');

      $.ajax({
// Change color instead of icon
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
    console.log("submitted");
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
                  error: function(data){
                    console.log(data);
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