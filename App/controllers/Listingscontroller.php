<?php

namespace App\controllers;

use Framework\Database;
use Framework\Session;
use Framework\Validation;
use Framework\Authorization;

class Listingscontroller
{
    protected $db;
    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }


    public function index()
    {

        $listings = $this->db->query('SELECT * FROM listings ORDER BY created_at DESC LIMIT 10')->fetchAll();



        loadView('listings/index', [
            'listings' => $listings,
        ]);
    }


    public function create()
    {
        loadView('listings/create');
    }


    public function show($params)
    {
        $id = $params['id'] ?? "";

        $params = [
            "id" => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id=:id ", $params)->fetch();

        if (!$listing) {
            Errorcontroller::notFound();
            return;
        }
        loadView('listings/show', ['listing' => $listing]);
    }


    /**
     * Store data
     * 
     * @return void
     */

    public function store()
    {
        $allowedfields = ['title', 'description', 'salary', 'requirements', 'benefits', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email'];


        $newlistingsdata = array_map('sanitize', array_intersect_key($_POST, array_flip($allowedfields)));

        $requiredfields = ['title', 'description', 'salary', 'company', 'city', 'email'];

        $errors = [];


        foreach ($requiredfields as $field) {
            if (empty($newlistingsdata[$field]) || !Validation::string($newlistingsdata[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }
        if (!empty($errors)) {
            loadView('listings/create', [
                'errors' => $errors,
                'listing' => $newlistingsdata
            ]);
            return;
        }


        $newlistingsdata['user_id'] = Session::get('user')['id'];



        $fields = [];

        foreach ($newlistingsdata as $field => $value) {
            $fields[] = $field;
        }

        $fields = implode(", ", $fields);

        $values = [];

        foreach ($newlistingsdata as $field => $value) {
            if ($value === "") {
                $newlistingsdata[$field] = null;
            }
            $values[] = ":" . $field;
        }

        $values = implode(", ", $values);

        $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

        $this->db->query($query, $newlistingsdata);

        Session::setFlashmessage('success_message', 'New post added successfully');
        redirect('/listings');
    }

    public function rmv($params)
    {
        $id = $params['id'];

        $bind = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id=:id', $bind)->fetch();

        if (!$listing) {
            Errorcontroller::notFound('Listing not found');
            return;
        }

        //Authorization
        if (!Authorization::isOwn($listing['user_id'])) {
            Session::setFlashmessage('error_message', 'You are not authorized to delete listing');
            redirect('/listings/' . $listing['id']);
            exit;
        }

        $this->db->query("DELETE FROM listings WHERE id=:id", $bind);

        Session::setFlashmessage('success_message', "Listing deleted successfully");


        redirect('/listings');
        exit;
    }


    public function edit($params)
    {
        $id = $params['id'] ?? "";

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id=:id", $params)->fetch();

        if (!$listing) {
            Errorcontroller::notFound();
            return;
        }

        loadView('listings/edit', [
            'listing' => $listing
        ]);
    }



    public function update($params)
    {
        $id = $params['id'] ??  "";

        $qparams = [
            'id' => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id=:id", $qparams)->fetch();

        if (empty($listing)) {
            Errorcontroller::notFound();
            return;
        }


        if (!Authorization::isOwn($listing['user_id'])) {
            Session::setFlashmessage('error_message', 'you are not authoried to update');
            redirect('/listings/' . $listing['id']);
            exit;
        }





        $allowedfields = ['title', 'description', 'salary', 'requirements', 'benefits', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email'];

        $updatevalues = [];
        $updatevalues = array_intersect_key($_POST, array_flip($allowedfields));
        $updatevalues = array_map('sanitize', $updatevalues);

        $requiredfields = ['title', 'description', 'salary', 'company', 'city', 'email'];

        $errors = [];

        foreach ($requiredfields as $field) {
            if (empty($updatevalues[$field]) || !Validation::string($updatevalues[$field])) {
                $errors[] = ucfirst($field) . ' is required';
            }
        }
        if (!empty($errors)) {
            loadView('listings/edit', [
                'listing' => $listing,
                'errors' => $errors
            ]);
            exit;
        } else {


            $updatedfields = [];

            foreach (array_keys($updatevalues) as $field) {

                $updatedfields[] = "{$field} = :{$field}";
            }




            $updatedfields = implode(', ', $updatedfields);

            $updatequery = "UPDATE listings SET $updatedfields WHERE id = :id";

            $updatevalues['id'] = $id;


            $this->db->query($updatequery, $updatevalues);


            Session::setFlashmessage('success_message', 'Listings updated');

            redirect('/listings/');
        }
    }


    /**
     * set up keywords search
     * 
     * @return void
     */
    public function search()
    {
        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : "";
        $location = isset($_GET['location']) ? trim($_GET['location']) : "";


        $query = 'SELECT * FROM listings WHERE (title LIKE :keywords
                 OR description LIKE :keywords
                 OR salary LIKE :keywords
                 OR tags LIKE :keywords)
            AND (city LIKE :location OR state LIKE :location)';
        $params = [
            'keywords' => "%{$keywords}%",
            'location' => "%{$location}%"
        ];

        $listings = $this->db->query($query, $params)->fetchAll();

        loadView('/listings/index', [
            'listings' => $listings,
            'keywords' => $keywords,
            'location' => $location
        ]);
    }
}
