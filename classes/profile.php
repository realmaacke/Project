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
            $returnValue = "";
        }
        return $returnValue;
    }

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
        <h7> ". $t_username." </h7>
        <p> ".$followersCount." Followers | ".$followingCount." Following | ".$postCount." Posts </p>
        ";

        return $returnValue;
    }

    public static function displayImage($t_username)
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
             $returnValue = "<img src='". $img."'  width='100%' height='100%'>";
        }
        else 
        {
          $returnValue = "<img src='Visual/img/avatar.png' width='100%' height='100%'>"; 
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

    public static function changeProfileBanner()
    {
        $colorMap[0] = '#5ed6a0'; // DEFAULT
        $colorMap[1] = '#0000FF'; //blue
        $colorMap[2] = '#FF0000'; //red
        $colorMap[3] = '#330000'; //dark red
    }

    public static function ReportUser()
    {

    }

}