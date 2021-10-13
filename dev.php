<?php
$comments = DB::query('SELECT comments.comment, users.username, users.profileimg FROM comments, users WHERE post_id = :postid AND comments.user_id = users.id', array(':postid'=>$postId));
foreach($followingposts as $post) 
{ ?>
    <div class="post">

    </div> 
    
<?php

    foreach($comments as $comment) 
    {  
    ?>

    <div class="comment">
        <a href="profile.php?username=<? $comment['username'] ?>"><? $comment['username'] ?></a>
        <p><? echo $comment['comment']; ?></p>
    </div>


<?php
    }
}

?>
<div class="comment">
<h4></h4>
<p></p>
<div class="make-a-comment">
</form>
  <form action='index.php?postid="<?php $post['id']; ?>" 'method='post'>
  <textarea name='commentbody' rows='2' cols='50'></textarea>
  <button style='margin-bottom: 20px' type='submit'class="btn btn-primary" name='comment'><i class="far fa-comment"></i></button>
  </form>
</div>
</div>