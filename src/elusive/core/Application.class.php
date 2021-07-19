<?php
/**
 * Elusive Framework Application Class File
 *
 * @copyright Copyright (C) 2011-2016 Elusive Concepts, LLC.
 * @author Roger Soucy <roger.soucy@elusive-concepts.com>
 * @license https://www.gnu.org/licenses/gpl.html GNU General Public License, version 3
 * @version 1.00.000
 *
 * @package Elusive\Core
 */

namespace elusive\core;

use elusive\lib\Events;

/**
 * Application Object
 *
 * Loads the application as defined in the elusive config file.
 */
final class Application
{
	/** @var object|null Application controller instance */
	public static $app     = NULL;

	/** @var object|null Application request instance [singleton] */
	public static $request = NULL;

	/**
	 * Run the Application Controller
	 *
	 * @return void
	 */
	public static function run()
	{
		Events::dispatch('APPLICATION', 'RUN');

		self::$request = Request::get_instance();

		// Load the application settings
		self::load_application_settings();

		Events::dispatch('APPLICATION', 'LOADED');

		Events::dispatch('APPLICATION', 'HANDOFF');

		self::$request->app['path']            = APP_PATH;
		self::$request->app['main_controller'] = APP_CONTROLLER;

		// Try to load the Application Controller
		$appclass = APP_CONTROLLER;

		// Check if we're using a factory, singleton, or standard class
		if(class_exists($appclass, true))
		{
			if(is_callable(array($appclass, 'create')))
			{
				self::$app = $appclass::create(); // factory method
			}
			else if(is_callable(array($appclass, 'get_instance')))
			{
				self::$app = $appclass::get_instance(); // singleton method
			}
			else
			{
				self::$app = new $appclass(); // constructor method
			}
		}
		else
		{
			throw new \Exception("Application Controller Unavailable");
		}

		Events::dispatch('APPLICATION', 'COMPLETE');
	}

	/**
	 * Load Application Settings
	 *
	 * The configuration file that is loaded is based off the file
	 * name of the frontloader. in most cases, the configuration
	 * file will be named /config.php. However, if the application
	 * frontloader is /myapp_index.php, the configuration file would
	 * be /myapp_config.php.
	 *
	 * @return void
	 */
	 public static function load_application_settings()
	 {
		$index = self::$request->data['ENV']['PHP_SELF'];

		$app_config_prefix  = substr(preg_replace('/index\.php.*/', '', $index), strrpos($index, '/')+1);

		include_once(PATH_ROOT . "/{$app_config_prefix}config.php");

		if(!defined('APP_PATH') || !defined('APP_CONTROLLER'))
		{
			// Application Installer Here
			throw new \Exception("Application Configuration Error");
		}
	 }
}
