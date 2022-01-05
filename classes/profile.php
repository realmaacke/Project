<?php
class Profile
{

    public static function verify()
    {
        $verify = DB::query('SELECT * FROM users WHERE username=:username',array(':username'=>htmlspecialchars($_GET['username'])));
        
        if($verify[0]['username'] == 0)
        {
          return false;
        }
        else {
            return true;
        }
    }

    public static function PermisionBadges($t_id)
    {
        $returnValue = "";
        $isAdmin = false;
        if(DB::query('SELECT user_id FROM administrator WHERE user_id=:userid',array(':userid'=>$t_id)))
        {
            $isAdmin = true;
        }
        else{
            $isAdmin = false;
        }

        $verified = DB::query('SELECT verified FROM users WHERE id=:id',array(':id'=>$t_id))[0]['verified'];

        if($verified)
        {
           if($isAdmin)
           {
            $returnValue= "<img src='Visual/img/verified.png'>  <img src='Visual/img/Moderator.png' style='margin-left: 12px;'>";
           }
           else {
            $returnValue= "<img src='Visual/img/verified.png' style='margin-left: 12px;'>";
           }
        }
        else {
            if($isAdmin)
            {
             $returnValue= "<img src='Visual/img/Moderator.png' style='margin-left: 12px;'>";
            }
        }

        return $returnValue;
    }

    public static function CheckifFollowing($userid, $t_id)
    {
        if(DB::query('SELECT follower_id FROM followers WHERE user_id=:targetid AND follower_id=:userid', array(':targetid'=>$t_id,':userid'=>$userid)))
        {
            return true;
        }
        else{
            return false;
        }
    }

    public static function CheckifLiked($userid, $postid)
    {
        if (DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postid, ':userid'=>$userid))) 
        {
         return true;
        }
        else 
        {
         return false;
        }
    }

    public static function Ammount($postid, $type)
    {
        if($type)   // likes
        {
            $calculateLikes = DB::query('SELECT * FROM post_likes WHERE post_id=:targetID', array(':targetID'=>$postid));
            $ammountOfLikes = 0;

            foreach($calculateLikes as $like)
            {  
                $ammountOfLikes++;
            }
            return $ammountOfLikes;
        }

        if(!$type)  // comments
        {
            $calculateComments = DB::query('SELECT comment FROM comments WHERE post_id=:targetID',array(':targetID'=>$postid));
            $ammountOfComments = 0;

            foreach($calculateComments as $calculate)
            {
                $ammountOfComments++;
            }
            return $ammountOfComments;
        }
    }

    public static function Statistic($t_id, $t_username)
    {
        $FollowingAmmount = DB::query('SELECT * FROM followers WHERE follower_id=:userid',array(':userid'=>$t_id));
        $FollowersAmmount = DB::query('SELECT * FROM followers WHERE user_id=:userid',array(':userid'=>$t_id));
        $PostsAmmount = DB::query('SELECT * FROM posts WHERE user_id=:userid',array(':userid'=>$t_id));

        $followingCount = 0;
        $followersCount = 0;
        $postCount = 0;
        foreach($FollowingAmmount as $following){
          $followingCount++;
        }
        foreach($FollowersAmmount as $followers){
          $followersCount++;
        }
        foreach($PostsAmmount as $ammountOfPosts){
          $postCount++;
        }
        $returnValue = "
        
        <h2> ". ucfirst($t_username) ." </h2>
        <h7> @". $t_username." </h7>
        <p> ".$followersCount." Followers | ".$followingCount." Following | ".$postCount." Posts </p>
        ";

        return $returnValue;
    }

    public static function displayImage($t_username, $type)
    {
        if($type)
        {
            $hasImage = false;
            $returnValue = "";
            if(DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$t_username))[0]['profileimg']) 
            { 
                $hasImage = true; 
            }
            else
            { 
                $hasImage = false; 
            }
    
           $img = DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$t_username))[0]['profileimg'];
           
            if($hasImage)
            { 
                 $returnValue = "<img src='". $img."' id='profile-pic' width='100%' height='100%'>";
            }
            else 
            {
              $returnValue = "<img src='Visual/img/avatar.png' id='profile-pic' width='100%' height='100%'>"; 
            }
            return $returnValue;
        }

        if(!$type)
        {
            $hasImage = false;
            $returnValue = "";
            if(DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$t_username))[0]['profileimg']) 
            { 
                $hasImage = true; 
            }
            else
            { 
                $hasImage = false; 
            }
    
           $img = DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$t_username))[0]['profileimg'];
           
            if($hasImage)
            { 
                 $returnValue = "<img src='". $img."' style='margin: auto; margin-left: 5px;' width='80' height='80' >";
            }
            else 
            {
              $returnValue = "<img src='Visual/img/avatar.png' style='margin: auto; margin-left: 5px;' width='80' height='80'>"; 
            }
            return $returnValue;
        }


    }

    public static function messageImage($t_username)
    {
        $hasImage = false;
        $returnValue = "";
        if(DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$t_username))[0]['profileimg']) 
        { 
            $hasImage = true; 
        }
        else
        { 
            $hasImage = false; 
        }

       $img = DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$t_username))[0]['profileimg'];
       
        if($hasImage)
        { 
            $returnValue = "<img src='". $img."' class='msg-img' width='50' height='50' >";
        }
        else 
        {
          $returnValue = "<img src='../Visual/img/avatar.png' class='msg-img' width='40' height='40'>"; 
        }
        return $returnValue;
    }

    public static function EditProfileImg($username)
    {
            $hasImage = false;
            $returnValue = "";
            if(DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$username))[0]['profileimg']) 
            { 
                $hasImage = true; 
            }
            else
            { 
                $hasImage = false; 
            }
    
           $img = DB::query('SELECT profileimg FROM users WHERE username=:username', array(':username'=>$username))[0]['profileimg'];
           
            if($hasImage)
            { 
                 $returnValue = "<img src='". $img."' class='edit-img' id='OpenImgUpload' width='100%' height='100%' onclick='ChangePicture()' >";
            }
            else 
            {
              $returnValue = "<img src='Visual/img/avatar.png' class='edit-img' id='OpenImgUpload' width='100%' height='100%' onclick='ChangePicture()' >"; 
            }
            return $returnValue;
        
    }

    public static function ProfileBanner($targetedUser)
    {
        $colorMap[0] = '#5ed6a0'; // DEFAULT

        if($targetedUser[0]['colorbanner'])
        {
          $colorMap[4] = $targetedUser[0]['colorbanner'];
          return $colorMap[4];
        }
        else {
            return $colorMap[0];
        }
    }

    public static function changeProfileBanner($userid)
    {
        $color = $_POST['color'];
        if(DB::query('SELECT colorbanner FROM users WHERE id=:userid',array(':userid'=>$userid)))
        {
                DB::query('UPDATE users SET colorbanner=:cb WHERE id=:userid',array(':cb'=>$color, ':userid'=>$userid));
        }
        else {
                DB::query('INSERT INTO users VALUES (:color)', array(':color'=>$color));
        }
    }
}