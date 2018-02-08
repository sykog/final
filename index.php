<?php

//Require the autoload file
require_once ('vendor/autoload.php');

//Turn on error reporting
ini_set('display-errors', 1);
error_reporting(E_ALL);

//Create an instance of the Base class
$f3 = Base::instance();

//Define a route using parameters
$f3->route('GET /@username', function($f3, $params) {

    $f3->set('username', $params['username']);
    $template = new Template();
    echo $template->render('pages/profile.php');
}
);

//Run Fat-Free
$f3->run();