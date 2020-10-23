<?php

include 'lib/simplePHPRouter/src/Steampixel/Route.php';

require_once('__config.php');

use Steampixel\Route;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Define a global basepath
Route::add('/guide', function() {
  header("Location: https://mahisnghrwt.github.io/redshift-api");
});

Route::add('/graph/([a-zA-Z0-9]*)', function($id) {
  include 'graph.php';
});

Route::add('/error/([a-zA-Z0-9]*)', function($id) {
  include 'error.php';
});

Route::add('/', function() {
  $isGuest = false;
  include 'api.php';
  }, 'post');

Route::add('/guest', function() {
  include 'api-guest.php';
}, 'post');

Route::add('/methods', function() {
    include 'methods.php';
}, 'get');

Route::add('/system-load', function() {
  include 'system-load.php';
}, 'post');

Route::add('/status', function() {
  include 'status.php';
}, 'post');

Route::add('/result', function() {
  include 'result.php';
}, 'post');

Route::pathNotFound(function($path) {
    header('HTTP/1.0 404 Not Found');
    echo 'Error 404 :-(<br>';
    echo 'The requested path "'.$path.'" was not found!';
  });
  
  Route::methodNotAllowed(function($path, $method) {
    header('HTTP/1.0 405 Method Not Allowed');
    echo 'Error 405 :-(<br>';
    echo 'The requested path exists. But the request method "'.$method.'" is not allowed on this path!';
  });
  
  Route::run(BASEPATH);
?>