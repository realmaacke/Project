<?php


class User{
    public static function getUserByID($id)
    {
       return DB::query('SELECT * FROM users WHERE id=:userid', array(':userid'=>$id));
    }

    public static function getUserByName($name)
    {
        return DB::query('SELECT * FROM users WHERE username=:username', array(':username'=>$name));
    }

    public static function displayFriendslist($userid)
    {
        $data = DB::query('SELECT * FROM followers WHERE user_id=:userid',array(':userid'=>$userid));

        foreach($data as $d)
        {
            $user = DB::query('SELECT * FROM users WHERE id=:dataid',array(':dataid'=>$d['follower_id']));
            ?>
            <div class="friends-item">

                <div class="image-div">

                    <?php echo Profile::displayImage($user[0]['username'], false) ?>
                    
                </div>

                <div class="contact-div">

                    <div class="contact-name">

                        <h1><a href='profile.php?username=<?php echo $user[0]['username']; ?>'>@<?php echo $user[0]['username'];?></a></h1>
                        <p><?php echo strtoupper($user[0]['username']); ?></p>

                    </div>

                </div>

            </div>
            <?php
        }
    }
}