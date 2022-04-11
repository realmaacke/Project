<?php
class Notify {
 
        // type: 1 = Message, 2 = user -> @, 3 = Followed

        public static function NavbarNotification($id)  // top page notification, (change color to yellow and change icon)
        {
                if(DB::query('SELECT id FROM notifications WHERE receiver=:receiver',array(':receiver'=>$id)))
                {
                        return true;
                }
                else {
                        return false;
                }
        }

        public static function atNotifications($text = "", $postid = 0)  // if user @ someone this method will be executed
        {//2
                $text = explode(" ", $text);
                $notify = array();

                foreach ($text as $word) 
                {
                        if (substr($word, 0, 1) == "@") 
                        {
                                $notify[substr($word, 1)] = array("type"=>2);
                        }
                }

                if (count($text) == 1 && $postid != 0) {
                        $temp = DB::query('SELECT posts.user_id AS receiver, post_likes.user_id AS sender FROM posts, post_likes WHERE posts.id = post_likes.post_id AND posts.id=:postid', array(':postid'=>$postid));
                        $r = $temp[0]["receiver"];
                        $s = $temp[0]["sender"];
                        DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender, :seen)', array(':type'=>2, ':receiver'=>$r, ':sender'=>$s));
                }

                return $notify;
        }

        public static function MessageNotification($from, $to)  // deprecated
        {//1
                if(!DB::query('SELECT seen FROM notifications WHERE receiver=:receiver AND sender=:sender', array(':receiver'=>$to, ':sender'=>$from)))
                {
                        DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender)', array(':type'=>1, ':receiver'=>$to, ':sender'=>$from));
                }
                else{
                        return;
                }
        }

        public static function isBeingFollowed($from, $to)      // if someone follows and hasnt followed before
        {//3
                // if notification has been seen dont send
                if(!DB::query('SELECT receiver FROM notifications WHERE receiver=:receiver AND sender=:sender', array(':receiver'=>$to, ':sender'=>$from)))
                {
                        DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender)', array(':type'=>3, ':receiver'=>$to, ':sender'=>$from));
                }
                else{
                        return;
                }
        }

        public static function DeleteNotification($id)  // deletes notifications when seen method
        {
                DB::query('DELETE FROM notifications WHERE id=:id',array(':id'=>$id));
        }
}
?>
