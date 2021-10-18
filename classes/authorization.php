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

                                    DB::query('INSERT INTO users VALUES (\'\', :username, :password, :email, \'0\', \'\')', array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email));
                                    Mail::sendMail('Welcome to Combined!', 'Your Account has been created!', $email);
                                    Redirect::goto("index.php");
                            } else {
                                $_SESSION['error'] = "1";
                            }
                    } else {
                        $_SESSION['error'] = "2";
                            }
                    } else {
                        $_SESSION['error'] = "3";
                    }
                    } else {
                        $_SESSION['error'] = "4";
                    }
            } else {
                $_SESSION['error'] = "5";
            }

    } else {
        $_SESSION['error'] = "6";
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
}