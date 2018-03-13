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

$f3->set('user', $_SESSION['user']);
if(isset($_SESSION['user'])){
    $f3->set("loggedIn", "true");
}

//Define a route to go to home page
$f3->route('GET /', function($f3, $params) {

    $template = new Template();
    echo $template->render('pages/navbar.html');
    echo $template->render('pages/home.html');

    if(isset($_SESSION['user'])){
        echo $_SESSION['user'];
    }
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

    //if register button is clicked
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = sha1($_POST['password']);
        $confirm = sha1($_POST['confirm']);
        $success = true;

        $user = new Member($username, $password, 0, 0);

        if ($database->memberExists($user->getUsername()) ==1) $success = false;
        if ($password == $confirm && $success) {
            $database->addMember($username, $password, 0, 0);
            $_SESSION['user'] = $username;
            $f3->set('user', $_SESSION['user']);
            echo $username;
        }
        if($password != $confirm){
            echo"Passwords do not match.";
            echo $confirm;
            echo $password;
        }
        if(!$success) {
            echo "Username already exists.";
        }
    }

    //if login button is clicked
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = sha1($_POST['password']);
        $success = true;

        if($database->getMember($username) == $username && ($database->getPassword($username) == $password)) {
            $success = true;
        }
        else $success = false;

        if($success) {
            $user = new Member($database->getMember($username), $database->getPassword($username), $database->getPremium($username), $database->getComments($username));
            $_SESSION['user'] = $username;
            $_SESSION['member'] = $user;
            $f3->set('user', $_SESSION['user']);

            $f3->reroute("/");
        }

        else {
            echo "Incorrect username or password.";
        }
    }
});

//Define a route to log out
$f3->route('GET /logout', function($f3, $params) {

    unset($_SESSION["user"]);

    $template = new Template();
    echo $template->render('pages/navbar.html');
    echo $template->render('pages/home.html');

    $f3->reroute("/");
});

//Run Fat-Free
$f3->run();