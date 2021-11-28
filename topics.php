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


$name = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['username'];

if (isset($_GET['topic'])) // specific topic
{

        if(isset($_POST['like']))
        {
          $postid = $_POST['postid'];
          Action::LikeAction($postid, $userid);
        }
      
        if(isset($_POST['unlike']))
        {
          $postid = $_POST['postid'];
          Action::LikeAction($postid, $userid);
        }
      
        if(isset($_POST['deleteComment']))
        {
      
        }
      
        if(isset($_POST['deletePost']))
        {
          $postid = $_POST['postid'];
          POST::DeletePost($postid);
        }
      
        if(isset($_POST['Comment']))
        {
          $body = $_POST['text'];
          $postid = $_POST['postid'];
          Comment::createComment($body, $postid, $userid);
        }

}
else {  // browsing through topics
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
    <title>COMBINED  #Discover</title>
</head>
<body>
<div class="navigation">
        <ul>
                <a href="index.php"><h1>COMBINED</h1></a>
                <li> <a href="profile.php?username=<?php echo $name;?>">Profile</a> </li>
                <li> <a href="dm.html">Messages</a> </li>
                <li> <a href="friends.html">Discover</a> </li>
                <li> <a href="search.php">Search</a> </li>
        </ul>
</div>



<div class="flow">
<?php 
 Topic::DisplayTopics(htmlspecialchars($_GET['topic']), $userid, $isAdmin);
?>
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