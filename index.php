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
$f3->route('GET|POST /login', function($f3, $params) {

    //Create the database
    $database = new Database();

    $template = new Template();
    echo $template->render('pages/navbar.html');
    echo $template->render('pages/signup.html');

    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm = $_POST['confirm'];
        $success = true;

        $user = new Member($username, $password);

        //$database->addMember($user->getUsername(), $user->getPassword(), 0, 0);
        if ($database->memberExists($user->getUsername()) ==1) $success = false;
        if ($password == $confirm && $success) {
            $database->addMember($user->getUsername(), $user->getPassword(), 0, 0);
        }
        if($password != $confirm){
            echo"Passwords do not match.";
        }
        if(!$success) {
            echo "Username already exists.";
        }

    }
});

//Run Fat-Free
$f3->run();