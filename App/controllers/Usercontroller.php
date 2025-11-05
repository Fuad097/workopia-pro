<?php

namespace App\controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;


class Usercontroller
{
    protected $db;

    function __construct()
    {
        $config = require basePath('/config/db.php');
        $this->db = new Database($config);
    }


    /** user login 
     * @return void
     */
    public function login()
    {
        loadView('user/login');
    }

    /** register user in database
     * @return void
     */
    public function create()
    {
        loadView('user/register');
    }

    /** Store user in database
     * @return void
     */
    public function store()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $password = $_POST['password'];
        $password_confirmation = $_POST['password_confirmation'];

        $errors = [];


        if (!Validation::email($email)) {
            $errors['email'] = "Please enter valid email";
        }

        if (!Validation::string($name, 2, 50)) {
            $errors['name'] = "Name must be between 2 and 50 chcarcters";
        }


        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = "Password must be at least 6 characters";
        }


        if (!Validation::match($password, $password_confirmation)) {
            $errors['password_confrim'] = 'Password is not matched';
        }





        if (!empty($errors)) {
            loadView('user/register', [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state
                ]
            ]);
            exit;
        }

        $params = [
            'email' => $email
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email= :email', $params)->fetch();


        //Check user exist
        if ($user) {
            $errors['email'] = "This email already exist";
            loadView('user/register', [
                'errors' => $errors
            ]);
            exit;
        }



        $params1 = [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        $this->db->query('INSERT INTO users (name,email,city,state,password) VALUES (:name,:email,:city,:state,:password)', $params1);

        $userID = $this->db->conn->lastInsertId();

        Session::set('user', [
            'id' => $userID,
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state
        ]);





        redirect('/');
    }

    /**
     * Logout and clear session
     * 
     * @return void
     */

    public function logout()
    {
        Session::clearall();

        $params = session_get_cookie_params();

        setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain']);
        redirect('/');
    }


    /**
     * Authontication user
     * 
     * @return void
     */

    public function authenticate()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $params = [
            'email' => $email,
        ];

        $errors = [];



        // Validate email
        if (!Validation::email($email)) {
            $errors['email'] = "Please valid email";
        };
        //Validate password
        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = "Password must be at least 6 characters";
        };

        if (!empty($errors)) {
            loadView('user/login', [
                'errors' => $errors
            ]);
            exit;
        };


        $user = $this->db->query("SELECT * FROM users WHERE email = :email", $params)->fetch();

        if (!$user) {
            $errors['email'] = "Incorrect credentials";
            loadView('user/login', [
                'errors' => $errors
            ]);
            exit;
        }

        if (!password_verify($password, $user['password'])) {
            $errors['password'] = "Incorrect credentials";
            loadView('user/login', [
                'errors' => $errors
            ]);
            exit;
        }


        // Set user session
        Session::set('user', [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'city' => $user['city'],
            'state' => $user['state']
        ]);


        redirect('/');
    }
}
