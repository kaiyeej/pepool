<?php

require 'env.php';

// connection

// START THE SESSION
session_start();

// THIS WILL LOAD ONLY THE NEEDED CLASS
spl_autoload_register(function ($class) {

    include __DIR__ . '/autoloader.php';

    if (array_key_exists($class, $classes)) {
        require_once $classes[$class];
    }
});


$url_main = "trisakay";
