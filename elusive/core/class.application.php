<?php namespace elusive\core;

use elusive\lib\Events;

final class Application
{
	public static $app     = NULL;
	public static $request = NULL;

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
		self::$app = new $appclass;

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
	 */
	 public static function load_application_settings()
	 {
		$index = self::$request->data['ENV']['PHP_SELF'];

		$app_config_prefix  = substr(preg_replace('/index\.php.*/', '', $index), strrpos($index, '/')+1);
		//$app_config_prefix .= ($app_config_prefix != '') ? '_' : '';

		include_once(PATH_ROOT . "/{$app_config_prefix}config.php");

		if(!defined('APP_PATH') || !defined('APP_CONTROLLER'))
		{
			// Application Installer Here
			throw new \Exception("Application Configuration Error");
		}
	 }
}