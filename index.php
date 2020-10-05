<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'lib/simplePHPRouter/src/Steampixel/Route.php';

use Steampixel\Route;

// Define a global basepath
define('BASEPATH','/php7www/redshift-api/');

Route::add('/', function() {
  $isGuest = false;
  include 'api.php';
  }, 'post');

Route::add('/guest', function() {
  $isGuest = true;
  include 'api.php';
}, 'post');

Route::add('/methods', function() {
    include 'methods.php';
}, 'get');

Route::add('/status', function() {
  include 'status.php';
}, 'post');

Route::add('/status/([0-9]*)', function($calculation_id) {
  include 'status_single.php';
}, 'get');

  
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