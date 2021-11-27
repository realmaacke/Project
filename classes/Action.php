<?php

class Action
{

    public static function FollowAction($t_id, $userid,$action)
    {
        if($action)
        {
            DB::query('INSERT INTO followers VALUES (\'\', :user_id, :follower_id)', array(':user_id'=>$t_id, ':follower_id'=>$userid));
        }
        if(!$action)
        {
         DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid',array(':userid'=>$t_id, ':followerid'=>$userid));
        }
    }

    public static function LikeAction($postId, $likerId) {

        if (!DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId))) {
                DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid'=>$postId));
                DB::query('INSERT INTO post_likes VALUES (\'\', :postid, :userid)', array(':postid'=>$postId, ':userid'=>$likerId));
                // Notify::createNotify("", $postId);
        } else {
                DB::query('UPDATE posts SET likes=likes-1 WHERE id=:postid', array(':postid'=>$postId));
                DB::query('DELETE FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId));
        }
}

    public static function PostAction($userid)
    {

    }


    public static function DeleteComment($postid, $userid)
    {

    }

    public static function DeletePost($postid, $userid)
    {

    }

    public static function ReportUser($userid, $t_id)
    {

    }
}