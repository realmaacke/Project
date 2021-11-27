<?php

class Post {

        public static function createPost($postbody, $loggedInUserId, $profileUserId) {

                if (strlen($postbody) > 160 || strlen($postbody) < 1) {
                        die('Incorrect length!');
                }

                $topics = self::getTopics($postbody);

                if ($loggedInUserId == $profileUserId) {

                        if (count(Notify::createNotify($postbody)) != 0) {
                                foreach (Notify::createNotify($postbody) as $key => $n) {
                                                $s = $loggedInUserId;
                                                $r = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$key))[0]['id'];
                                                if ($r != 0) {
                                                        DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender, :extra)', array(':type'=>$n["type"], ':receiver'=>$r, ':sender'=>$s, ':extra'=>$n["extra"]));
                                                }
                                        }
                                }

                        DB::query('INSERT INTO posts VALUES (\'\', :postbody, NOW(), :userid, 0, \'\', :topics)', array(':postbody'=>$postbody, ':userid'=>$profileUserId, ':topics'=>$topics));

                } else {
                        die('Incorrect user!');
                }
        }

        public static function createImgPost($postbody, $loggedInUserId, $profileUserId) {

                if (strlen($postbody) > 160) {
                        die('Incorrect length!');
                }

                $topics = self::getTopics($postbody);

                if ($loggedInUserId == $profileUserId) {

                        if (count(Notify::createNotify($postbody)) != 0) {
                                foreach (Notify::createNotify($postbody) as $key => $n) {
                                                $s = $loggedInUserId;
                                                $r = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$key))[0]['id'];
                                                if ($r != 0) {
                                                        DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender, :extra)', array(':type'=>$n["type"], ':receiver'=>$r, ':sender'=>$s, ':extra'=>$n["extra"]));
                                                }
                                        }
                                }

                        DB::query('INSERT INTO posts VALUES (\'\', :postbody, NOW(), :userid, 0, \'\', :topics)', array(':postbody'=>$postbody, ':userid'=>$profileUserId, ':topics'=>$topics));
                        $postid = DB::query('SELECT id FROM posts WHERE user_id=:userid ORDER BY ID DESC LIMIT 1;', array(':userid'=>$loggedInUserId))[0]['id'];
                        return $postid;
                } else {
                        die('Incorrect user!');
                }
        }


        public static function getTopics($text) {

                $text = explode(" ", $text);

                $topics = "";

                foreach ($text as $word) {
                        if (substr($word, 0, 1) == "#") {
                                $topics .= substr($word, 1).",";
                        }
                }

                return $topics;
        }

        public static function DeletePost($id)
        {
                if(DB::query('SELECT id FROM posts WHERE id=:postid', array(':postid'=>$id))) // selecting the post
                {
                  DB::query('DELETE FROM comments WHERE post_id=:postid',array(':postid'=>$id));  // deleting comments associated with the post
                  DB::query('DELETE FROM posts WHERE id=:A_POSTID',array(':A_POSTID'=>$id));  // deleting the post
                  DB::query('DELETE FROM post_likes WHERE post_id=:A_POSTID',array('A_POSTID'=>$id)); //deleting the post likes
                }
        }



        public static function link_add($text) {

                $text = explode(" ", $text);
                $newstring = "";

                foreach ($text as $word) {
                        if (substr($word, 0, 1) == "@") {
                                $newstring .= "<a href='profile.php?username=".substr($word, 1)."'>".htmlspecialchars($word)."</a> ";
                        } else if (substr($word, 0, 1) == "#") {
                                $newstring .= "<a href='topics.php?topic=".substr($word, 1)."'>".htmlspecialchars($word)."</a> ";
                        } else {
                                $newstring .= htmlspecialchars($word)." ";
                        }
                }
                return $newstring;
        }


        public static function ProfilePosts($userid, $t_name, $t_id, $isAdmin) 
        {
                $postIndex = 0;
                $posts = DB::query('SELECT * FROM posts WHERE user_id=:targetid',array(':targetid'=>$t_id));
                foreach($posts as $p)
                { 
                  $postIndex++;
                  ?>
                              <div class="post">
                    <div class="left">
                              <div class="top">
                                <?php 
                                echo Profile::displayImage($t_name, false);
                                ?>
                              <h3><a href="profile.php?username=<?php echo $t_name ?>"><?php echo "@". $t_name ?></a></h3>
                              </div>
                    </div>
                          <!-- Right Side -->
                    <div class="right">
                          <div id="post-top">
                          <?php
                        echo Post::link_add($p['body']);  // link_add is an function that separates characters that start with an @ as a userlink.
                        ?>
                          </div>
                            <div id="post-bottom">
                            <form action="profile.php?username=<?php echo $t_name; ?>" method="POST" style="width:50%; float:left">
                            <input type="hidden" name="postid" value="<?php echo $p['id']; ?>">
                              <?php 
                              if(Profile::CheckifLiked($userid, $p['id']))
                              {
                                ?> <button type='submit'  name='unlike' value="unlike" class='btn btn-primary'> <?php echo Profile::Ammount($p['id'], true); ?> <i class='fas fa-heart'></i></button> <?php
                              }
                              else {
                                ?> <button type='submit'  name='like' value="like" class='btn btn-primary'> <?php echo Profile::Ammount($p['id'], true); ?>  <i class='far fa-heart'></i></button> <?php
                              }
                              ?>
                              <button type="button"  value="<?php echo $postIndex; ?>" id="CommentBTN" class='btn btn-primary'><?php echo Profile::Ammount($p['id'], false); ?>  <i class="far fa-comments"></i></button>
                              </form>
                              <?php
                                if($isAdmin)
                                {
                                  ?>
                                  <form style="float:right" action="profile.php?username=<?php echo $t_name;?>" method="POST">
                                      <input type="hidden" name="postid" value="<?php echo $p['id']; ?>">
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
                          <form action="profile.php?username=<?php echo $t_name; ?>" method="POST">
                              <input type="hidden" name="postid" value="<?php echo $p['id']; ?>">
                              <textarea name="text" value="text" placeholder="Comment Something!" class="textAreaComment" id="" cols="80" rows="2"></textarea>
                              <button id='commentPost' name="Comment" class='btn btn'>Send <i class="fas fa-arrow-right"></i></button>
                           </form>
                        </div>
                        <?php
                         $commentIndex = 0;
                         $comment = DB::query('SELECT * FROM comments WHERE post_id=:postid', array(':postid'=>$p['id']));
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
                <?php
                }
        }
}
