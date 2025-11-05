<?php 


namespace Framework;

use Framework\Session;



class Authorization{


    /**
 * @param int $resourceId
 * @return bool
 */






    public static function isOwn($resourceId){
    $sessionuser = Session::get('user');

    if(!$sessionuser || !isset($sessionuser['id']) ){
        return false;
    }
    
     return $sessionuser = (int) $sessionuser['id'] === (int) $resourceId;
    }

}