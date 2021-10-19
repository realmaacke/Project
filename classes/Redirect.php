<?php

class Redirect{

    public static function goto($location = null)
    {
        if($location)
        {
            header('Location: ' . $location);
            exit();
        }
    }
}
