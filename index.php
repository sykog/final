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
$f3->set('message', $_SESSION['message']);

//Define a route to go to home page
$f3->route('GET /', function($f3, $params) {

    $database = new Database();
    // grab the post from the database
    $postid = 2;
    $post = $database->getPost($postid);
    $f3->set("post", $post);

    $template = new Template();
    echo $template->render('pages/navbar.html');
    echo $template->render('pages/home.html');
});

//Define a route to get to a user's profile
$f3->route('GET /profile/@username', function($f3, $params) {

    // access the database
    $database = new Database();
    $username = $params['username'];
    $f3->set('username', $params['username']);
    $posts = $database->getMemberPosts($username);
    $comment = $database->getMemberComments($username);
    $f3->set('memberPosts', $posts);
    $f3->set('memberComments', $comment);

    $template = new Template();
    echo $template->render('pages/navbar.html');
    echo $template->render('pages/profile.html');
});

//Define a route to the main blog
$f3->route('GET|POST /blog', function($f3, $params) {

    // access the database
    $database = new Database();
    // grab every post from the database
    $posts = $database->getPosts();
    $member = $database->getMember($_SESSION['user']);
    // create member from database
    $user = new Member($member[0]['username'], $member[0]['password'], $member[0]['premium'], $member[0]['numPosts']);

    if ($user->getPremium() == 1) {
        $f3->set("premium", "true");
        if(isset($_POST['submit'])) {
            $comment = $_POST['blogPost'];
            $title = $_POST['title'];
            if ($comment != "" || $title != "") {
                $user->blogPost($title, $comment);
                $f3->reroute("/328/final/blog/");
            }
        }
    }

    $f3->set("allPosts", $posts);

    $template = new Template();
    echo $template->render('pages/navbar.html');
    echo $template->render('pages/posts.html');
});

//Define a new route for a specific post
$f3->route('GET|POST /blog/@postid', function($f3, $params) {

    $database = new Database();
    // grab the post from the database
    $postid = $params['postid'];
    $post = $database->getPost($postid);
    $f3->set("post", $post);

    // grab every comment from the database
    $comments = $database->getComments($postid);
    $f3->set("allComments", $comments);

    $member = $database->getMember($_SESSION['user']);
    // create member from database
    $user = new Member($member[0]['username'], $member[0]['password'], $member[0]['premium'], $member[0]['numPosts']);

    if(isset($_POST['submit'])) {
        $comment = $_POST['blogComment'];
        $commentid = $_POST['commentid'] + 1;
        $commentEdit = $_POST['editComment'];

        if ($commentEdit != "") {
            // comment id - 1 wont change the indent
            $database->editComment($commentid - 1, $user->getUsername(), $commentEdit);
            $f3->reroute("/blog/".$params['postid']);
        }
        if ($comment != "") {
            $user->comment($postid, $commentid, $comment);
            $database->updateCommentCount($postid, $post['commentCount'] + 1);
            $f3->reroute("/blog/".$params['postid']);
        }
    }

    $template = new Template();
    echo $template->render('pages/navbar.html');
    echo $template->render('pages/comments.html');

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
            $_SESSION["message"] = "";
            $f3->reroute("/");
        }

        if($password != $confirm){
            $_SESSION["message"] = "Passwords do not match";
            $f3->reroute("/login");
        }
        if(!$success) {
            $_SESSION["message"] = "Username already exists";
            $f3->reroute("/login");
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

            $_SESSION["message"] = "";
            $f3->reroute("/");
        }

        else {
            $_SESSION["message"] = "Incorrect username or password";
            $f3->reroute("/login");
        }
    }
});

//Define a route to the members page
$f3->route('GET /members', function($f3, $params) {

    // access the database
    $database = new Database();
    //Get all the users
    $users = $database->selectMembers();

    $f3->set("allUsers", $users);

    $template = new Template();
    echo $template->render('pages/navbar.html');
    echo $template->render('pages/members.html');

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