<?php 

class Messages{
    
    public static function DisplayFriends($userid)
    {
        $friends = DB::query('SELECT * FROM followers WHERE follower_id=:id',array(':id'=>$userid));

        foreach($friends as $f)
        {
        $user = DB::query('SELECT * FROM users WHERE id=:friendID',array(':friendID'=>$f['user_id']));

        $username = $user[0]['username'];
        $profilePicture = $user[0]['profileimg'];
        
        }
    }
}