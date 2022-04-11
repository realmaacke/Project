<?php

class Post {

        public static function createPost($postbody, $loggedInUserId, $profileUserId) { // create post method

                if (strlen($postbody) > 160 || strlen($postbody) < 1) { // rules for the post 
                        die('Incorrect length!');
                }

                // add post to specified topics
                $topics = self::getTopics($postbody);
                self::UploadTopics($topics);

                if ($loggedInUserId == $profileUserId) {        // if someone @ u this will execute notify method

                        if (count(Notify::atNotifications($postbody)) != 0) {
                                foreach (Notify::atNotifications($postbody) as $key => $n) {
                                                $s = $loggedInUserId;
                                                $r = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$key))[0]['id'];
                                                if ($r != 0) {
                                                        DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender)', array(':type'=>$n["type"], ':receiver'=>$r, ':sender'=>$s));
                                                }
                                        }
                                }

                        DB::query('INSERT INTO posts VALUES (\'\', :postbody, NOW(), :userid, 0, \'\', :topics)', array(':postbody'=>$postbody, ':userid'=>$profileUserId, ':topics'=>$topics));

                } else {
                        die('Incorrect user!');
                }
        }

        public static function createImgPost($postbody, $loggedInUserId, $profileUserId) {      // same method as above except u can insert picture (uses imgur as api)

                if (strlen($postbody) > 160) {
                        die('Incorrect length!');
                }

                $topics = self::getTopics($postbody);

                if ($loggedInUserId == $profileUserId) {

                        if (count(Notify::atNotifications($postbody)) != 0) {
                                foreach (Notify::atNotifications($postbody) as $key => $n) {
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


        public static function getTopics($text) {       // if # is in $text adding to topics db

                $text = explode(" ", $text);

                $topics = "";

                foreach ($text as $word) {
                        if (substr($word, 0, 1) == "#") {
                                $topics .= substr($word, 1).",";
                        }
                }

                return $topics;
        }

        public static function UploadTopics($text)      // uploadtopics method
        {
                DB::query('INSERT INTO topics VALUES (\'\', \'\', :topics)', array(':topics'=>$text));
        }

        public static function link_add($text) {        // if someone @ it will make a link to their profile pages.

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


        public static function Posts($userid, $t_name, $t_id, $isAdmin, $type) // timeline post method
        {
                if($type)
                {
                        // querry for people user is following.
                        $posts = DB::query('SELECT posts.id, posts.body, posts.likes, users.`username` FROM users, posts, followers
                        WHERE posts.user_id = followers.user_id
                        AND users.id = posts.user_id
                        AND follower_id = :userid
                        ORDER BY posts.id DESC;', array(':userid'=>$userid));


                        // local username
                        $name = DB::query('SELECT username FROM users WHERE id=:userid',array(':userid'=>$userid))[0]['username'];

                        $postIndex = 0; // int that increments depending on what post it is (help with the js to open the right comment to the right post)
                        
                        foreach($posts as $p)
                        { 
                        $postIndex++;
                        ?>
                        <div class="post">     
                        <div class="left">
                                <div class="top">
                                        <?php  echo Profile::displayImage($p['username'], false); ?>
                                        <h3><a href="profile.php?username=<?php echo $p['username'] ?>"><?php echo "@". $p['username'] ?></a></h3>
                                </div>
                        </div>
                                <!-- Right Side -->
                                <div class="right">
                                        <div id="post-top">
                                                <?php echo Post::link_add($p['body']);  // link_add is an function that separates characters that start with an @ as a userlink. ?>
                                        </div>

                                                <div id="post-bottom">
                                                        <form  method="POST" style="width:50%; float:left">
                                                                <button type="button" id='like' data-id="<?php echo $p['id']; ?>" name='like' class='btn btn-primary'> <?php echo Profile::Ammount($p['id'], true); ?> <i class='far fa-heart'></i></button>
                                                                <button type="button" value="<?php echo $postIndex; ?>" id="CommentBTN" class='btn btn-primary'><?php echo Profile::Ammount($p['id'], false); ?>  <i class="far fa-comments"></i></button>
                                                        </form>
                                                <?php if($isAdmin) { ?>
                                                        <form style="float:right" id="deletePost" method="POST">
                                                                <input type="hidden" name="postid" value="<?php echo $p['id']; ?>">
                                                                <button type='submit' style='color:red;' name='deletePost' class='btn btn-primary'><i class="far fa-trash-alt"></i></button>
                                                        </form>
                                                        <?php } ?>
                                                </div>
                                        </div>
                                </div>

                        <div class="comments" id="<?php echo $postIndex; ?>">
                                <div class="PostComment">
                                <form id="commentForm" method="POST">
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

                                if($cmt['user_id'] == $userid){
                                        $commentOwner = true;
                                
                                } ?>

                        <div class="C_item"> 
                        <?php if($commentOwner || $isAdmin) { ?>
                        <form style="float:right; padding-right:50px;" id="deleteComment" method="POST">
                                <input type="hidden" name="commentid" value="<?php echo $cmt['id']; ?>">
                                <button type='submit' style="color:red; float:right" name="deleteComment" class='btn btn'><i class="far fa-trash-alt"></i></button>
                        </form>

                        <?php } ?>
                        <h2 ><a href="profile.php?username=<?php echo $cmtName;?>"> <?php echo ucfirst($cmtName); ?></a> -</h2>
                        <p ><?php echo Post::link_add($cmt['comment']); ?>
                                <div class="cmtLine"></div>
                                </div>
                                <?php } ?>
                        </div>
                        <?php
                        }
                        // if user got 0 posts at homepage, display basic welcome message
                        if($postIndex < 1)
                        { ?>      
                                <div class="post">
                                        <div class="left">
                                                <div class="top">
                                                        <img src='Visual/img/favicon.ico' style='margin: auto; margin-left: 5px;' width='80' height='80'>
                                                        <h3><a href="" style="color: green;">COMBINED</a></h3>
                                                </div>
                                        </div>
                                                <!-- Right Side -->
                                        <div class="right">
                                                <div id="post-top">
                                                <span style="color:green;">Welcome</span>, It seems like you're not following anyone. To locate users navigate to the <a style="color: green;" href="search.php">Search</a> tab and search for their usernames.
                                                To customize your user navigate to <a style="color: green;" href="profile.php?username=<?php echo $name; ?>">Profile</a>, This is where you will be able to Post and watch your old posts.
                                                </div>
                                        </div>
                                </div>
                                <?php
                        }
                }
                if(!$type)      // if false method is for displaying profile posts
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
                                        <?php  echo Profile::displayImage($t_name, false); ?>
                                        <h3><a href="profile.php?username=<?php echo $t_name ?>"><?php echo "@". $t_name ?></a></h3>
                                </div>
                            </div>
                                <!-- Right Side -->
                                <div class="right">
                                        <div id="post-top">
                                                <?php echo Post::link_add($p['body']);  // link_add is an function that separates characters that start with an @ as a userlink. ?>
                                        </div>

                                                <div id="post-bottom">
                                                        <form  method="POST" style="width:50%; float:left">
                                                                <button type="button" id='like' data-id="<?php echo $p['id']; ?>" name='like' class='btn btn-primary'> <?php echo Profile::Ammount($p['id'], true); ?> <i class='far fa-heart'></i></button>
                                                                <button type="button" value="<?php echo $postIndex; ?>" id="CommentBTN" class='btn btn-primary'><?php echo Profile::Ammount($p['id'], false); ?>  <i class="far fa-comments"></i></button>
                                                        </form>
                                                <?php if($isAdmin) { ?>
                                                        <form style="float:right" id="deletePost" method="POST">
                                                                <input type="hidden" name="postid" value="<?php echo $p['id']; ?>">
                                                                <button type='submit' style='color:red;' name='deletePost' class='btn btn-primary'><i class="far fa-trash-alt"></i></button>
                                                        </form>
                                                        <?php } ?>
                                                </div>
                                        </div>
                                </div>

                        <div class="comments" id="<?php echo $postIndex; ?>">
                                <div class="PostComment">
                                  <form id="commentForm" method="POST">
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

                                if($cmt['user_id'] == $userid){
                                        $commentOwner = true;
                                
                                } ?>

                      <div class="C_item"> 
                        <?php if($commentOwner || $isAdmin) { ?>
                        <form style="float:right; padding-right:50px;" id="deleteComment" method="POST">
                                <input type="hidden" name="commentid" value="<?php echo $cmt['id']; ?>">
                                <button type='submit' style="color:red; float:right" name="deleteComment" class='btn btn'><i class="far fa-trash-alt"></i></button>
                        </form>
                
                        <?php } ?>
        
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
}
