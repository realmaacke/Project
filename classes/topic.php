<?php

class Topic // similar class to post.php
{
    public static function DisplayTopics($topic, $userid, $isAdmin) 
    {

            $posts = DB::query("SELECT * FROM posts WHERE FIND_IN_SET(:topic, topics)", array(':topic'=>htmlspecialchars($_GET['topic'])));
    ?>
        <div class="topic_Header">
                <div class="topic_header_text">
                        <h3>#<?php echo $topic; ?></h3>
                </div>
        </div>
        <?php 
        $postIndex = 0;
        $img ="";
        foreach($posts as $post) 
        { 
            $user = DB::query('SELECT * FROM users WHERE id=:postid', array(':postid'=>$post['user_id']));
            // defining bools to prevent error
            $postIndex++;
            // querrying for profile img, if $hasimage bool is true
            $img = DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$user[0]['username']))[0]['profileimg'];
    
             //  Check if post user got any profile image, if not use the default one in Visual/img/..
            if(DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$user[0]['username']))[0]['profileimg']) 
            {
              $hasImage = true; 
            }
            else
            {
              $hasImage = false;  
            }
    
            ?>

        <div class="post">
                <div class="left">
                        <div class="top">
                            <?php 
                            if($hasImage)
                            { 
                              ?> <img src="<?php echo $img; ?>" alt="" style="margin: auto; margin-left: 5px;" width="80" height="80"> <?php
                            }
                            else 
                            {
                              ?> <img src="Visual/img/avatar.png" alt="" style="margin: auto; margin-left: 5px;" width="80" height="80"> <?php
                            }
                              ?> <h3><a href="profile.php?username=<?php echo $user[0]['username'] ?>"><?php echo "@". $user[0]['username'] ; ?></a></h3>
                        </div>
                </div>

                <div class="right">
                        <div id="post-top">
                        <?php
                            echo Post::link_add($post['body']);  // link_add is an function that separates characters that start with an @ as a userlink.
                        ?>
                        </div>
                           
                        <div id="post-bottom">
                        <form action="topics.php?topic=<?php echo $topic; ?>" method="POST" style="width:50%; float:left">
                            <input type="hidden" name="postid" value="<?php echo $post['id']; ?>">
                              <?php 
                              if(Profile::CheckifLiked($userid, $post['id']))
                              {
                                ?> <button type='submit'  name='unlike' value="unlike" class='btn btn-primary'> <?php echo Profile::Ammount($post['id'], true); ?> <i class='fas fa-heart'></i></button> <?php
                              }
                              else {
                                ?> <button type='submit'  name='like' value="like" class='btn btn-primary'> <?php echo Profile::Ammount($post['id'], true); ?>  <i class='far fa-heart'></i></button> <?php
                              }
                              ?>
                              <button type="button"  value="<?php echo $postIndex; ?>" id="CommentBTN" class='btn btn-primary'><?php echo Profile::Ammount($post['id'], false); ?>  <i class="far fa-comments"></i></button>
                              </form>
                              <?php
                                if($isAdmin)
                                {
                                  ?>
                                  <form style="float:right" action="profile.php?username=<?php echo $user[0]['username'];?>" method="POST">
                                      <input type="hidden" name="postid" value="<?php echo $post['id']; ?>">
                                      <button type='submit' style='color:red;' name='deletePost' class='btn btn-primary'><i class="far fa-trash-alt"></i></button>
                                  </form>
                                <?php
                                }
                                ?>
                        </div>
                </div>
        </div>
        <div class="comments" id="<?php echo $postIndex; ?>">
                <div class="PostComment">
                        <form action="topics.php?topic=<?php echo $topic;?>" method="POST">
                            <input type="hidden" name="postid" value="<?php echo $post['id']; ?>">
                            <textarea name="text" value="text" placeholder="Comment Something!" class="textAreaComment" id="" cols="80" rows="2"></textarea>
                            <button id='commentPost' name="Comment" class='btn btn'>Send <i class="fas fa-arrow-right"></i></button>
                        </form>
                </div>
        <?php
            $commentIndex = 0;
            $comment = DB::query('SELECT * FROM comments WHERE post_id=:postid', array(':postid'=>$post['id']));
            $commentOwner = false;
            foreach($comment as $cmt)
            {
              $commentIndex++;
              $cmtName = DB::query('SELECT username FROM users WHERE id=:userid',array(':userid'=>$cmt['user_id']))[0]['username'];
              if($cmt['user_id'] == $userid)
              {
                $commentOwner = true;
              
              }
              ?>
                <div class="C_item">
                <?php
              if($commentOwner || $isAdmin)
                 { ?>
                   <form style="float:right; padding-right:50px;" action="index.php?comment=<?php echo $cmt['id'];?>" method="POST">
                          <button type='submit' style="color:red; float:right" name='self_DeleteComment' class='btn btn'><i class="far fa-trash-alt"></i></button>
                      </form>
                   <?php
                 } ?>
                <h2 ><a href="profile.php?username=<?php echo $cmtName;?>"> <?php echo $cmtName ?></a> -</h2>
                <p ><?php echo Post::link_add($cmt['comment']); ?>
                        <div class="cmtLine"></div>
                        </div>
                        <?php } ?>
                </div>
        </div>
        <?php
            }
        }

}