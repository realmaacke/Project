<?php

class Action
{

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