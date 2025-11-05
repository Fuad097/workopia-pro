<?php

namespace Framework;


class Session{
    

    /**
     * Session start
     * 
     * @return void
     */
    public static function start(){
        if(session_status() !== PHP_SESSION_ACTIVE){
            session_start();
        }
    }

    /**
     * Set session key/value pairs
     * @param string $key
     * @param mixed $value
     * @return void
     */

    public static function set($key ,$value){
        $_SESSION[$key] = $value;
    }

    /**
     * get session key
     * @param string $key
     * @param mixed $default
     * @return void
     */

    public static function get($key ,$default=null){
       return isset($_SESSION[$key]) ?  $_SESSION[$key] : $default;
    }

    /**
     * check session status
     * @param string $key
     * @return bool
     */

    public static function has($key){
        return isset($_SESSION[$key]);
    }


    /**
     * clear session 
     * @param string $key
     * @return void
     */

    public static function clear($key){
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
    }


    /**
     * clear session 
     * 
     * @return void
     */

    public static function clearall(){
        session_unset();
        session_destroy();
    }


    /**
     * @param string $key
     * @param string $message
     * @return void
     */

    public static function setFlashmessage($key,$message){
        self::set('flash_' . $key,$message);
    }

    /**
     * @param string $key
     * @param string $default
     * @return mixed
     */

    public static function getFlashmessage($key,$default=[]){
        $message = self::get('flash_' . $key,$default);
        self::clear('flash_' . $key);
        return $message;
    }


    
}