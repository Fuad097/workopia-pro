<?php 



$router->get('/','Homecontroller@index');
$router->get('/listings','Listingscontroller@index');
$router->get('/listings/create','Listingscontroller@create',['auth']);
$router->get('listings/edit/{id}','Listingscontroller@edit',['auth']);
$router->get('/listings/search','Listingscontroller@search');
$router->get('/listings/{id}','Listingscontroller@show');




$router->post('/listings','Listingscontroller@store',['auth']);
$router->put('/listings/{id}','Listingscontroller@update',['auth']);
$router->delete('/listings/{id}','Listingscontroller@rmv',['auth']);

$router->get('/auth/login','Usercontroller@login',['guest']);
$router->get('/auth/register','Usercontroller@create',['guest']);



$router->post('/auth/register','Usercontroller@store',['guest']);
$router->post('/auth/logout','Usercontroller@logout',['auth']);
$router->post('/auth/login','Usercontroller@authenticate',['guest']);
