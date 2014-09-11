<?php
/**
 * Safety Catch
 */
if(!defined('PATH_ROOT')) { exit; }

/**
 * Application Branching
 *
 * Load different applications based off of subdomain
 *
 * Application Settings:
 *     Debug Mode
 *         Valid options are:
 * 		       PRODUCTION  = Show "Polite" Error Message
 *		       MAINTENANCE = Displays the maintenance page
 *		       DEBUG       = Show full debug error tracing / and console
 *
 *     Application Folder
 *
 *     Application Controller
 */
switch(array_shift(explode(".", $_SERVER['HTTP_HOST'])))
{
	// Default Application
	default:
		define('SERVER_MODE', 'DEBUG');
		//define('SERVER_MODE', 'PRODUCTION');
		define('APP_PATH', PATH_ROOT . '/app');
		define('APP_CONTROLLER', 'app\controllers\Primary');
		break;
}
