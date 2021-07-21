<?php

/**
 ** Import Vendor Library -----------------------------------------------------
 * Imports vendor autoload file for use in project.
 *  - Declare required classes
 */

require_once __DIR__ . '/../../vendor/autoload.php';

// Declare libraries
use Utilities\DB;
use Http\Session;
use Http\Request;
use Controllers\Router;
use Controllers\Link;
use ScssPhp\ScssPhp\Compiler;

/**
 ** DB Usage ----------------------------------------------------------------
 * If you intend to connect to a db within your project
 * add the table names below in the TABLE definition.
 * - Use the array key to reference the table
 * - Ex: TABLE[0] = 'demo'
 *
 * The framework is built on the
 * thingengineer/mysqli-database-class library.
 * Learn more at https://github.com/ThingEngineer/PHP-MySQLi-Database-Class
 */

/**
 * DB Table
 * @var \Array
 * Enter table names as array for easy changes
 *
 * 0 - users
 *
 * 1 - templates
 */
define('TABLE', ["demo", "demo2"]);

/**
 ** Site Info ----------------------------------------------------------------
 * Set site variables here.
 * - TITLE refers to the meta title name
 * - PROD_URL is the production url common name
 * - VERSION of the current build. Also appends version to js and css files
 *    to force caching updates
 */

// Set site title
define('TITLE', 'PHP Starter');

// Set production url
define('PROD_URL', 'www.phpstarter.com');

// Set current version
define('VERSION', '1.0');

/**
 ** Structure ----------------------------------------------------------------
 * Set project structure variables here.
 * - HOME_DIR primary view to load as index
 * - VIEWS where allowable pages are stored
 * - COMPONENT_DIR where all components for views are stored
 *      (this directory will be excluded from routing)
 * - SCRIPTS sets allowed ajax script calls - preventing calls from
 *      accessing backend scripts
 */

// Default home directory
define('HOME_DIR', 'home');

// Set views directory
define('VIEWS', 'templates/');

// Default component directory
define('COMPONENT_DIR', '_components');

// Set ajax allowed directory
define('SCRIPTS', 'src/');

/**
 ** System ---------------------------------------------------------------------
 * Set system variables here.
 * - DBDATA include database connection information
 * - TIMEOUT set session timeout for protected pages
 * - PROC_TIMER set process timer to return execution time
 * - Set default timezone for date/time references
 * - LOCAL checks for local ip and sets env
 * - STAGE compares current url host with PROD_URL var to detect stage env
 */

// Set DB connection info
define('DBDATA', include 'db.php');

// Set Session timeout
define('TIMEOUT', 1800); // 30 mins 1800

// Start Process Timer
define('PROC_TIMER', microtime(true));

// Set the default timezone
date_default_timezone_set('America/Chicago');

// Set local Env
if ($_SERVER["REMOTE_ADDR"] === '127.0.0.1') {
  define('LOCAL', true);
} else {
  define('LOCAL', false);
}

// Set stage Env
if (Request::getHost() !== PROD_URL) {
  define('STAGE', true);
} else {
  define('STAGE', false);
}

/**
 ** Error Handling ----------------------------------------------------------------
 * Sets error display based environment
 * - Allows php warnings in prod
 */

if (!STAGE) {
  error_reporting(E_ALL ^ E_WARNING);
} else {
  error_reporting(E_ALL);
  ini_set("display_errors", "On");
}

/**
 ** Initialize Classes -------------------------------------------------------------
 * Initialize commonly used classes for the project.
 *  - $session allows for access to current session data
 *  - $router used to route traffic to correct content
 *  - $link provides quick access to elements throughout the project
 */

$session = new Session();
$router = new Router();
$link = new Link();

/**
 ** Start DB -------------------------------------------------------------
 * Enable access to the database from any element in the project.
 *  - Classes must initiate the DB instance or bring pass the $db var
 *
 * Enabled by default
 */

$useDB = true; // Disable if not using db
$db = $useDB ? (new DB())->start() : null;

/**
 ** Compile CSCC -------------------------------------------------------------
 * While in the local env the index scss sheet is compiled on every load.
 * - By default the scss file compiles to css/index.css
 */
$useSCSS = true; // Disable for performance testing
if (LOCAL && $useSCSS) {
  $scssIn = file_get_contents(__DIR__ . '/../../public/scss/index.scss');
  $cssOut = (new Compiler())->compileString($scssIn)->getCss();
  file_put_contents(__DIR__ . '/../../public/css/index.css', $cssOut);
}