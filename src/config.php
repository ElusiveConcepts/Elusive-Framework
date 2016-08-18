<?php
/**
 * Elusive Framework Server/Application Configuration
 *
 * @copyright Copyright (C) 2011-2016 Elusive Concepts, LLC.
 * @author Roger Soucy <roger.soucy@elusive-concepts.com>
 * @license https://www.gnu.org/licenses/gpl.html GNU General Public License, version 3
 * @version 1.00.000
 *
 * @package Elusive/Config
 */

/**
 * Safety Catch
 */
if(!defined('PATH_ROOT')) { exit; }

/** 
 * Define Server Environment
 * 
 * Define various server environments for branching and conditionals
 *
 *     Valid Options:
 *         DEVELOPMENT = Development Server (unstable development build)
 *         STAGING     = Staging Server (stable development build)
 *         PRODUCTION  = Production Server (stable release build)
 */
define('SERVER_ENV', 'DEVELOPMENT');
//define('SERVER_ENV', 'STAGING');
//define('SERVER_ENV', 'PRODUCTION');

/**
 * Application Branching
 *
 * Load different applications based off of subdomain and define 
 * application modes for each
 *
 * Application Settings:
 *     APP_MODE = Application Mode
 *         Define the application mode to run in
 *         Valid options are:
 * 		       PRODUCTION  = Show "Polite" Error Messages
 *		       MAINTENANCE = Displays a maintenance page
 *		       DEBUG       = Show full debug error tracing / and console
 *
 *     APP_PATH = Application Folder
 *         Define the base folder path for the application code
 *
 *     APP_CONTROLLER = Main Application Controller
 *         Define Class and namespace for the main application controller
 */
switch(array_shift(explode(".", $_SERVER['HTTP_HOST'])))
{
	/* sample.example.com
	// Sample Subdomain Application
	case 'sample':
		// Define App Mode
		define('APP_MODE', 'DEBUG');
		// define('APP_MODE', 'MAINTENANCE');
		// define('APP_MODE', 'PRODUCTION');

		// Define Application
		define('APP_PATH', PATH_ROOT . '/sample');
		define('APP_CONTROLLER', 'sample\controllers\Primary');
		break;
	*/

	// Default Application
	default:
		// Show/Hide Debug Mode
		define('APP_MODE', 'DEBUG');
		//define('APP_MODE', 'MAINTENANCE');
		//define('APP_MODE', 'PRODUCTION');

		define('APP_PATH', PATH_ROOT . '/app');
		define('APP_CONTROLLER', 'app\controllers\Primary');
		break;
}
