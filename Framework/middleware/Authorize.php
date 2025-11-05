<?php 


namespace Framework\middleware;

use Framework\Session;


class Authorize{
    /**
     * check user session
     * 
     * @return bool
     */

    public function isAuthorized(){
        return Session::has('user');
    }


    /**
     * Handle user request
     * 
     * @param string $role
     * @return bool
     */

    public function handle($role){
        if($role === 'guest' && $this->isAuthorized()){
            redirect('/');
        }elseif($role === 'auth' && !$this->isAuthorized()){
            redirect('/auth/login');
        }
    }
}