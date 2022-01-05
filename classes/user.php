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
}