<?php

class authorization {
    public static function register($username, $password, $email)
    {

        if (!DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {

            if (strlen($username) >= 3 && strlen($username) <= 32) {

                    if (preg_match('/[a-zA-Z0-9_]+/', $username)) {

                            if (strlen($password) >= 6 && strlen($password) <= 60) {

                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                            if (!DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))) {

                                    DB::query('INSERT INTO users VALUES (\'\', :username, :password, :email, \'0\', \'\', \'\')', array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email));
                                    Redirect::goto("index.php");
                            } else {
                               Redirect::goto('register.php?emailError');
                            }
                    } else {
                        Redirect::goto('register.php?emailError');
                            }
                    } else {
                        Redirect::goto('register.php?SendUsernameSpecialCharError');
                    }
                    } else {
                        Redirect::goto('register.php?emailError');
                    }
            } else {
                Redirect::goto('register.php?usernameErrorToLong');
            }

    } else {
        Redirect::goto('register.php?usernameError');
    }
    }

    public static function login($username, $password)
    {
        if(DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username)))
        {
           // Grabing password from the username to compare if it is correct
          if(password_verify($password, DB::query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password']))
          {
            $cstrong = true;
            $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
            $user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];
            DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', array('token'=>sha1($token), ':user_id'=>$user_id));
           setcookie("CMBNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
           Redirect::goto('index.php');
          }
          else
          {
            Redirect::goto('login.php?error');
          }
        } else
        {
            Redirect::goto('login.php?error');
        }
    }

    public static function ValidateAdmin($id)
    {
        if(DB::query('SELECT user_id FROM administrator WHERE user_id=:userid', array(':userid'=>$id)))
        { 
           return true;
        }
        else
        { 
            return false;
        }
    }

    public static function CommentDelete($id)
    {
        DB::query('DELETE FROM comments WHERE id=:cmtID',array(':cmtID'=>$id));  // deleting comments associated with the post
    }

    public static function AdminDeleteComment($id)
    {
        DB::query('DELETE FROM comments WHERE id=:cmtID',array(':cmtID'=>$id));  // deleting comments associated with the post
    }

    public static function AdminDeletePost($id)
    {
        if(DB::query('SELECT id FROM posts WHERE id=:postid', array(':postid'=>$id))) // selecting the post
        {
          DB::query('DELETE FROM comments WHERE post_id=:postid',array(':postid'=>$id));  // deleting comments associated with the post
          DB::query('DELETE FROM posts WHERE id=:A_POSTID',array(':A_POSTID'=>$id));  // deleting the post
          DB::query('DELETE FROM post_likes WHERE post_id=:A_POSTID',array('A_POSTID'=>$id)); //deleting the post likes
        }
    }
}
