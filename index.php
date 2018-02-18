<?php

//Require the autoload file
require_once ('vendor/autoload.php');

//Turn on error reporting
ini_set('display-errors', 1);
error_reporting(E_ALL);

//Create an instance of the Base class
$f3 = Base::instance();

//Define a route to go to home page
$f3->route('GET /', function() {

    $template = new Template();
    echo $template->render('includes/navbar.html');
    echo $template->render('pages/home.php');
});

//Define a route using parameters to get to a user's profile
$f3->route('GET /@username', function($f3, $params) {

    $f3->set('username', $params['username']);
    $template = new Template();
    echo $template->render('includes/navbar.html');
    echo $template->render('pages/profile.php');
});

//Run Fat-Free
$f3->run();