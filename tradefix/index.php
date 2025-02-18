<?php
ob_start();
/*****************************************************
  Simple jQuery Mobile MVC Framework in PHP
  =========================================
  Read more at:
  http://devgrow.com/jquery-mobile-php-mvc-framework/

  This is meant to serve as a simple basis for
  more complex jQuery Mobile applications that
  can benefit from an MVC architecture.
 *****************************************************/
/*ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
// server should keep session data for AT LEAST 10 hours
ini_set('session.gc_maxlifetime', 36000);

// each client should remember their session id for EXACTLY 10 hours
session_set_cookie_params(36000);


require_once('lib/config.php');

$app = new App;


ob_end_flush();
/*$str = '[{"id": 31428684,"siteId": 674384,"keyword": "אוכל ליום הולדת","engine": {"engine": "google.co.il","isMobile": false}}]';

$res = json_decode($str);

print_r($res);*/
