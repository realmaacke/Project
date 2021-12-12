<?php
class Comment {

        public static function displayComments($postId) {


                $comments = DB::query('SELECT comments.comment, users.username FROM comments, users WHERE post_id = :postid AND comments.user_id = users.id', array(':postid'=>$postId));
                foreach($comments as $comment) 
                {       
                }
        }
}