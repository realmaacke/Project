<?PHP 

class Send {
    public static function SendEmailError()
    {
        return "<br> <p id='errormsg' style='color:red'>Email aready in use!</p>";
    }

    public static function SendUsernameError()
    {
        return "<br> <p id='errormsg' style='color:red'>Username already in use!</p>";
    }

    public static function SendUsernameSpecialCharError()
    {
        return "<br> <p id='errormsg' style='color:red'>Username must be A-Z, 0-9,</p>";
    }

    public static function SendUsernameToLongError()
    {
        return "<br> <p id='errormsg' style='color:red'>Username is either to short or to long (3 > 32) </p>";
    }

    public static function SendPasswordError()
    {
        return "<br> <p id='errormsg' style='color:red'>Password is either to short or to long (3 > 32) </p>";
    }
}