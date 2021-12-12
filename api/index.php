<?php
include('../autoload.php');

$db = new DB("127.0.0.1", "project", "root", "");

if ($_SERVER['REQUEST_METHOD'] == "GET") 
{

} 
else if ($_SERVER['REQUEST_METHOD'] == "POST")
{

        if($_GET['url'] == "likes")
        {
                $postId = $_GET['id'];
                $token = $_COOKIE['CMBNID'];
                $likerId = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];

                if (!$db->query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId))) {

                        $db->query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid'=>$postId));
                        $db->query('INSERT INTO post_likes VALUES (\'\', :postid, :userid)', array(':postid'=>$postId, ':userid'=>$likerId));
                        //Notify::createNotify("", $postId);
                } else {
                        $db->query('UPDATE posts SET likes=likes-1 WHERE id=:postid', array(':postid'=>$postId));
                        $db->query('DELETE FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId));
                }

                echo "{";
                echo '"Likes":';
                echo $db->query('SELECT likes FROM posts WHERE id=:postid', array(':postid'=>$postId))[0]['likes'];
                echo "}";
        }

        if($_GET['url'] == "follow")
        {
                $targetUser = $_GET['user'];
                $token = $_COOKIE['CMBNID'];
                $userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];

                if($db->query('SELECT follower_id FROM followers WHERE follower_id=:userid AND user_id=:targetid',array(':userid'=>$userid, ':targetid'=>$targetUser)))
                {
                        $db->query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid',array(':userid'=>$targetUser, ':followerid'=>$userid));
                        echo "Follow";
                } else {
                        $db->query('INSERT INTO followers VALUES (\'\', :user_id, :follower_id)', array(':user_id'=>$targetUser, ':follower_id'=>$userid));
                        echo "Unfollow";
                        Notify::isBeingFollowed($userid, $targetUser);
                }
        }

        if($_GET['url'] == "comment")
        {
                $token = $_COOKIE['CMBNID'];
                $userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];

                $text = $_POST['text'];
                $id = $_POST['postid'];

                
                if (strlen($text) < 160 || strlen($text) > 1) 
                {
                 $db->query('INSERT INTO comments VALUES (\'\', :comment, :userid, NOW(), :postid)', array(':comment'=>$text, ':userid'=>$userid, ':postid'=>$id));

                }  
        }

        if($_GET['url'] == "deleteComment")
        {
                $token = $_COOKIE['CMBNID'];
                $userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
                $id = $_POST['commentid'];

                $db->query('DELETE FROM comments WHERE id=:id',array(':id'=>$id));
                echo "deleted comment";
                echo $id;
        }

        if($_GET['url'] == "deletePost")
        {
                $token = $_COOKIE['CMBNID'];
                $userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
                $id = $_POST['postid'];

                $db->query('DELETE FROM posts WHERE id=:id',array(':id'=>$id));    
                echo "deleted post";
        }



} 
 else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
        if ($_GET['url'] == "auth") {
                if (isset($_GET['token'])) {
                        if ($db->query("SELECT token FROM login_tokens WHERE token=:token", array(':token'=>sha1($_GET['token'])))) {
                                $db->query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_GET['token'])));
                                echo '{ "Status": "Success" }';
                                http_response_code(200);
                        } else {
                                echo '{ "Error": "Invalid token" }';
                                http_response_code(400);
                        }
                } else {
                        echo '{ "Error": "Malformed request" }';
                        http_response_code(400);
                }
        }
}

?>