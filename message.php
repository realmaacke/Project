<?php
// CSRF PROTECTION
session_start();
$cstrong = True;
$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));

if(!isset($_SESSION['token']))  // if session token dosent exists, create a token
{
    $_SESSION['token'] = $token;
}
//

include('autoload.php');
if (Login::isLoggedIn()) {
        $userid = Login::isLoggedIn();
} else {
       die('not logged in');
}

if(isset($_POST['send'])){

    if(!isset($_POST['security']))
    {
        die("invalid");
    }


    if($_POST['security'] != $_SESSION['token'])
    {   // tokens dosent match
        // alternativly header to login.php
    die("Invalid Token");
    }

    if(DB::query('SELECT id FROM users WHERE id=:receiver', array(':receiver'=>$_GET['receiver'])))
    {
        DB::query("INSERT INTO messages VALUES ('', :body, :sender, :receiver, 0)", array(':body'=>$_POST['body'], ':sender'=>$userid, 'receiver'=>htmlspecialchars($_GET['receiver'])));
        echo "Message Sent";
    } else {
        die("invalid user");
    }
    session_destroy(); // destroying the token after button has been submited

}

?>

<h1>Send Message</h1>

<form action="message.php?receiver=<?php echo htmlspecialchars($_GET['receiver']); ?>" method="post">
    <textarea name="body"cols="80" rows="8"></textarea>
    <input type="hidden" name="security" value="<?php echo $_SESSION['token']; ?>">
    <input type="submit" name="send" value="Send Message">
</form>