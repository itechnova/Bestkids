<?php

namespace App\Libraries;

class Hash{

    public static function encrypt($password){
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function valid($password, $password_hash){
        if(password_verify($password, $password_hash)){
            return true;
        }else{
            return false;
        }
    }
}