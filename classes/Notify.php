<?php
class Notify {

        // type: 1 = Message, 2 = user -> @, 3 = Followed

        public static function DisplayNotifications($from, $userid, $type)
        {

        }

        public static function NavbarNotification($id)
        {
                if(DB::query('SELECT id FROM notifications WHERE receiver=:receiver',array(':receiver'=>$id)))
                {
                        return true;
                }
                else {
                        return false;
                }
        }

        public static function atNotifications($text = "", $postid = 0)
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
                        DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender, :seen)', array(':type'=>2, ':receiver'=>$r, ':sender'=>$s, ':seen'=>0));
                }

                return $notify;
        }

        public static function MessageNotification($from, $to)
        {//1
                if(!DB::query('SELECT seen FROM notifications WHERE receiver=:receiver AND sender=:sender', array(':receiver'=>$to, ':sender'=>$from)))
                {
                        DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender, :seen)', array(':type'=>1, ':receiver'=>$to, ':sender'=>$from, ':seen'=>0));
                }
                else{
                        return;
                }
        }

        public static function isBeingFollowed($from, $to)
        {//3
                // if notification has been seen dont send
                if(!DB::query('SELECT seen FROM notifications WHERE receiver=:receiver AND sender=:sender', array(':receiver'=>$to, ':sender'=>$from)))
                {
                        DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender, :seen)', array(':type'=>3, ':receiver'=>$to, ':sender'=>$from, ':seen'=>0));
                }
                else{
                        return;
                }
        }
}
?>
