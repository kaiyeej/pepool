<?php

$request = $_SERVER['REQUEST_URI'];

/** SET ROUTES HERE */
// insert routes alphabetically
$routes = array(
    "homepage" => array(
        'class_name' => 'Homepage',
        'has_detail' => 0
    ),
    "users" => array(
        'class_name' => 'Users',
        'has_detail' => 0
    ),
    "job-types" => array(
        'class_name' => 'JobTypes',
        'has_detail' => 0
    ),
    "job-posting" => array(
        'class_name' => 'JobPosting',
        'has_detail' => 0
    ),
    "profile" => array(
        'class_name' => 'Users',
        'has_detail' => 0
    ),
);
/** END SET ROUTES */


$base_folder = "pages/";
$page = str_replace(APP_FOLDER."/", "", $request);
// $page = str_replace("/".APP_FOLDER."/", "", $request);

// chec if has parameters
if (substr_count($page, "?") > 0) {
    $url_params = explode("?", $page);
    $dir = $base_folder . $url_params[0] . '/index.php';
    //$param = $url_params[1];
    $page = $url_params[0];
} else {

    if ($page == "" || $page == null) {
        $page = "homepage";
    }
    $dir = $base_folder . $page . '/index.php';
}

if (array_key_exists($page, $routes)) {
    require_once $dir;
    $route_settings = json_encode($routes[$page]);
} else {
    require_once "index.php";//'error-404.html';
    $route_settings = json_encode([]);
}

