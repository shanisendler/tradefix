<?php

/**
 * Configuration file
 *
 * This file specifies all of the base values used throughout the app.
 *
 * @package    jQuery Mobile PHP MVC Micro Framework
 * @author     Monji Dolon <md@devgrow.com>
 * @copyright  2011-2012 Monji Dolon
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL) v3
 * @link       http://devgrow.com/jquery-mobile-php-mvc-framework/
 */
//ob_start();
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
// Define global Variables.
define("APP_NAME", "TradeFix");
define("APP_DESCRIPTION", "");
define("APP_KEYWORDS", "");
define("PASSWORD_SALT", "justjquerying");
define("CACHE_ENABLE", true);
define("BASE_DIR", dirname(dirname(__FILE__)));
define("LIB_DIR", dirname(__FILE__));
define("COOKIE_DOMAIN", "");

//If site disposed not in root path
define("BASE_URL", '/tradefix');

// Set the default controller hte user is directed to (aka homepage).
define('ROUTER_DEFAULT_CONTROLLER', 'site');
define('ROUTER_DEFAULT_ACTION', 'home');


// The following controllers/actions will not be cached:
$do_not_cache = array("user", "");


// Load helper functions and the model classes.
require_once(LIB_DIR . "/helpers.php");
require_once(LIB_DIR . "/models/car.php");
require_once(LIB_DIR . "/models/user.php");
require_once(LIB_DIR . "/models/template.php");
//require_once(LIB_DIR . "/models/reports.php");
