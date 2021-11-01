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

?> 

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Home - COMBINED</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="GUI/index-style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
    <link rel="stylesheet" href="assets/fonts/simple-line-icons.min.css">
    <link rel="stylesheet" href="assets/css/vanilla-zoom.min.css">
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
<script src="assets/js/vanilla-zoom.js"></script>
<script src="assets/js/theme.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/6bfb37676a.js" crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar navbar-light navbar-expand-lg fixed-top bg-white clean-navbar">
        <div class="container"><a class="navbar-brand logo" href="#">COMBINED<br></a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.html" style="filter: blur(0px);">SEARCH&nbsp;</a></li>
                    <li class="nav-item"><a class="nav-link" href="features.html">DISCOVER</a></li>
                    <li class="nav-item"><a class="nav-link" href="pricing.html">MESSAGES<br></a></li>
                    <li class="nav-item"><a class="nav-link" href="about-us.html">MORE</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php?username=<?php echo $name ?>">PROFILE</a></li>
                </ul>
            </div>
        </div>
    </nav>

<div class="Main">


<?php 
      foreach($followingposts as $posts)
      { ?>
      
        <div class="P_header">
            <img id="avatar" src="assets/avatar.png" width="65" height="65" alt="">
           <h3 id="username"><a href="profile.php?username=<?php echo $posts['username']?>">@<?php echo $posts['username']?></a></h3>
        </div>
        <hr />
        <!-- making the content clickable for a montal  -->
        <a href=""> 
            <div class="P_main">
               <p> <?php echo $posts['body']; ?> </p>
            </div>
        </a>
        <div class="P_interact">
                <button onclick="send()" value="like" id="like">Like</button>
                <?php 
                echo $state;
                ?>
        <hr />
        </div>
<?php } ?>
</div>

</body>
</html>

</div>
  </div>
</div>
</body>
</html>



<script>
      function likeButton() {
          var parentEl = this.parentElement;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'interact.php', true);
        // form data is sent appropriately as a POST request
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {asf
          if(xhr.readyState == 4 && xhr.status == 200) {
            var result = xhr.responseText;
            console.log('Result: ' + result);
            if(result == "true"){
                parentEl.classList.add('liked');
            }
          }
        };
        xhr.send("id=" + parentEl.id);
      }

      var buttons = document.getElementsByClassName("like-button");
      for(i=0; i < buttons.length; i++) {
        buttons.item(i).addEventListener("click", likeButton);
      }

      function unlikeButton() {
          var parentEl = this.parentElement;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'interact.php', true);
        // form data is sent appropriately as a POST request
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
          if(xhr.readyState == 4 && xhr.status == 200) {
            var result = xhr.responseText;
            console.log('Result: ' + result);
            if(result == "true"){
                parentEl.classList.remove('liked');
            }
          }
        }; 
        xhr.send("id=" + parentEl.id);
      }

      var buttons = document.getElementsByClassName("unlike-button");
      for(i=0; i < buttons.length; i++) {
        buttons.item(i).addEventListener("click", unlikeButton);
      }

    </script>