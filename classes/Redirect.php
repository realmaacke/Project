<?php

class Redirect{ // simple redirect class so header() method wont be used, (easy to use wrong)

    public static function goto($location = null)
    {
        if($location)
        {
            header('Location: ' . $location);
            exit();
        }
    }
}
