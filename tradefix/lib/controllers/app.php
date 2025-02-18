<?php

/**
 * App Controller
 *
 * This is the main app controller, used for connecting to the database (via PHP PDO),
 * creating a new instance of both the Template and User model, and for routing the
 * current request to the correct action.
 *
 * @package    jQuery Mobile PHP MVC Micro Framework
 * @author     Monji Dolon <md@devgrow.com>
 * @copyright  2011-2012 Monji Dolon
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL) v3
 * @link       http://devgrow.com/jquery-mobile-php-mvc-framework/
 */

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

class App
{
	
	/**
	 * Main constructor function, used to initialize the models, connect to the DB and
	 * route the user to where they need to go.
	 */
	function __construct()
	{
		global $template, $db, $car, $user;

		// Start the user session, used for storing cookies.
		session_start();
		$config = require __DIR__ . '../../../config/config.php';
		$environment = detectEnvironment();
		$envConfig = $config[$environment];
		$this->host = $envConfig['host'];
		$this->username = $envConfig['username'];
		$this->password = $envConfig['password'];
		$this->database = $envConfig['database'];
		
		// Connect to the database.
		try {
			// If your application uses MySQL, use the following line instead:

			$connectionInfo = array("Database"=> $this->database, "UID"=> $this->username, "PWD"=> $this->password);
			
			$host = $this->host;
			$dbname = $this->database;
			$user = $this->username;
			$pass = $this->password;           			

			$dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4";
            $db = new PDO($dsn, $this->username, $this->password, [
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // הפעלת טיפול בשגיאות
              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // הבאת נתונים כאסוציאטיביים
            ]);
			
		} catch (Exception $e) {
			die($e);
		}

		// Check to see if the 'user' table exists and if not, create it.
		$this->check_db();

		// Create new Template and User models.
		$template = new TemplateModel;
		$car = new carModel;
		$user = new userModel;
		
		// Figure out which controller the user is requesting and perform the
		// correct actions.
		$this->router();
	}

	/**
	 * Figure out where the user is trying to get to and route them to the
	 * appropriate controller/action.
	 */
	function router()
	{

		// Create a new Router instance.
		$r = new Router();

		// Configure the routes, where the user should go when they access the
		// specified URL structures. Default controller and action is set in the
		// config.php file.
		$r->map('/', array('controller' => ROUTER_DEFAULT_CONTROLLER, 'action' => ROUTER_DEFAULT_ACTION));
		$r->map('/user', array('controller' => 'user', 'action' => 'index'));
		//$r->map('/login', array('controller' => 'user', 'action' => 'login'));
		//$r->map('/logout', array('controller' => 'user', 'action' => 'logout'));
		//$r->map('/signup', array('controller' => 'user', 'action' => 'register'));
		//$r->map('/seller', array('controller' => 'user', 'action' => 'seller'));
		$r->map('/users/:id', array('controller' => 'users'), array('id' => '[\d]{1,8}'));

		// Load instructions for basic routing and send the user on their way!
		$r->default_routes();
		$r->execute();

		// Extracting info about where the user is headed, in order to match the
		// URL with the correct controllers/actions.
		$controller = $r->controller;
		$model = $r->controller_name;
		$action = $r->action;
		$id = $r->id;
		$params = $r->params; // Returns an array(...)
		$matched = $r->route_found; // Bool, where True is if a route was found.

		if ($matched) { //echo $controller;die();
			// If going to a site page, treat it in special manner, otherwise load
			// the appropriate controller/action and pass in the variables and
			// parameters specified by the URL.
			if ($controller == "site") {
				$site = new Site;
				$site->load_page($action);
			} elseif (file_exists(LIB_DIR . '/controllers/' . $controller . '.php')) {
				$$controller = new $model;
				if (method_exists($$controller, $action)) $$controller->$action($id, $params[0] ?? null, $params[1] ?? null);
				else Site::load_page('error');
			} else Site::load_page('error');
		} else Site::load_page('home');
	}

	/**
	 * Check to see if the proper tables exist in the database and if not,
	 * create them.
	 */
	function check_db()
	{
		global $db;

		$sql = 'CREATE TABLE IF NOT EXISTS users(
		       id INTEGER PRIMARY KEY,
		       name TEXT,
		       email TEXT,
		       password TEXT,
		       create_ip TEXT,
		       create_date TEXT,
		       status INTEGER
		    )';
		$query = $db->prepare($sql);
		$query->execute();
	}
}
