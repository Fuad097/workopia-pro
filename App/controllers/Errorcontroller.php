<?php

namespace App\controllers;


class Errorcontroller{

    /**
     * 404 error
     */

    static function notFound($message = 'Not found 404'){
        http_response_code(404);
        loadView('error',['status'=> '404','message'=> $message]);
    }
    static function unAuthorized($message = 'Not  authorized to this page'){
        http_response_code(403);
        loadView('error',['status'=> '403','message'=> $message]);
    }
}