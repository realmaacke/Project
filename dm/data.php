<?php

class messages{
    public static function display($from, $to)
    {
        $data = DB::query("SELECT * FROM `messages` WHERE (`from`=:u_from AND `to` = :u_to) OR (`from` =:u_to AND `to` =:u_from)", array(':u_from'=>$from, ':u_to'=>$to));

        return $data;
    }
}