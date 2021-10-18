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

    public static function redirectData($data = null)
    {
        $value = $data;
        return $value;
    }
}
