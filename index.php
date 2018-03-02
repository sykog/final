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

//Define a route to get to a user's profile
$f3->route('GET /profile-@user', function($f3, $params) {

    $f3->set('username', $params['username']);
    $template = new Template();
    echo $template->render('pages/navbar.html');
    echo $template->render('pages/profile.html');
});

//Define a route to the main blog
$f3->route('GET /blog', function($f3, $params) {

    $template = new Template();
    echo $template->render('pages/navbar.html');
    echo $template->render('pages/posts.html');
});

//Define a route to log in
$f3->route('GET /login', function($f3, $params) {

    $template = new Template();
    echo $template->render('pages/navbar.html');
    echo $template->render('pages/signup.html');

    $username = "sykog";
    $password = "chocobo586";
    $user = new Admin($username, $password);
    $database = new Database();
    $database->addMember($user, $password, 1, $user->commentCount());
});

//Run Fat-Free
$f3->run();