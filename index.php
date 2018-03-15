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
$f3->route('GET|POST /blog', function($f3, $params) {

    // access the database
    $database = new Database();
    $posts = $database->getPosts();
    $member = $database->getMember($_SESSION['user']);
    $user = new Member($member[0]['username'], $member[0]['password'], $member[0]['premium'], $member[0]['numPosts']);
    if ($user->getPremium() == 1) {
        $f3->set("premium", "true");
        if(isset($_POST['submit'])) {
            $comment = $_POST['blogPost'];
            $user->blogPost($comment);
        }
    }

    $f3->set("allPosts", $posts);

    $template = new Template();
    echo $template->render('pages/navbar.html');
    echo $template->render('pages/posts.html');


    /*$username = "sykog";
    $member = $database->getMember($username);
    $comment = "My personal favorite is peanut butter ice cream. The peanut butter ice cream at Coldstone is delicious, but I never see it at Kent Station anymore. I sometimes see it at other locations, and get it almost ever time. As far as toppings go, I really like Reeses or graham crackers. Actually, why not both?";
    $user = new Member($member[0]['username'], $member[0]['password'], $member[0]['premium'], $member[0]['numPosts']);
    $user->blogPost($comment); */
});

//Define a route to log in
$f3->route('GET|POST /login', function($f3, $params) {

    // access the database
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
            $f3->reroute("/");
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
        $member = $database->getMember($username);

        // have to use [0] since the array is in an array
        if($member[0]['username'] == $username && ($member[0]['password'] == $password)) {
            $success = true;
        }
        else $success = false;

        if($success) {
            $user = new Member($username, $password, $member[0]['premium'], $member[0]['numPosts']);
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