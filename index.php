<?php

//Require the autoload file
require_once ('vendor/autoload.php');
//Start the session
session_start();

//Turn on error reporting
ini_set('display-errors', 1);
error_reporting(E_ALL);

//Create an instance of the Base class
$f3 = Base::instance();
// set debug level
$f3->set('DEBUG', 3);

$_SESSION['user'] = "sykog";
$f3->set('user', $_SESSION['user']);

//Define a route to go to home page
$f3->route('GET /', function($f3, $params) {

    $template = new Template();
    echo $template->render('pages/navbar.html');
    echo $template->render('pages/home.html');
});

//Define a route using parameters to get to a user's profile
$f3->route('GET /profile/@user', function($f3, $params) {

    $f3->set('username', $params['username']);
    $template = new Template();
    echo $template->render('pages/navbar.html');
    echo $template->render('pages/profile.php');
});

//Run Fat-Free
$f3->run();