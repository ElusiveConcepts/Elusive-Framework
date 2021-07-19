<?php
/**
 * Elusive Framework Core Class File
 *
 * @copyright Copyright (C) 2011-2016 Elusive Concepts, LLC.
 * @author Roger Soucy <roger.soucy@elusive-concepts.com>
 * @license https://www.gnu.org/licenses/gpl.html GNU General Public License, version 3
 * @version 1.00.000
 *
 * @package Elusive\Core
 */

namespace elusive\core;

use elusive\debug\Debug;

/**
 * Elusive Framework Core Object
 *
 * Singleton class for containing, loading, and initializing the 
 * Elusive framework.
 *
 * Must be called with Elusive::get_instance().
 */
final class Elusive
{
	/** @var object|null Instance of the framework singleton */
	private static $instance = NULL;

	/** @var boolean Framework has been instantiated */
	private static $loaded = FALSE;

	/**
	 * Constructor
	 *
	 * Cannot be called externally. Use Elusive::get_instance() to retreive
	 * an instance of the class.
	 */
	private function __construct()
	{

	}

	/**
	 * Get an instance of the framework object
	 *
	 * Creates a new instance of the framework if not created and returns
	 * the current instance.
	 *
	 * @return object
	 */
	public static function get_instance()
	{
		if(self::$instance == NULL)
		{
			self::$instance = new Elusive();
		}

		return self::$instance;
	}

	/**
	 * Framework initialization
	 *
	 * Registers the autoloader and creates the Request object.
	 *
	 * @return boolean
	 */
	public static function init()
	{
		// Ensure that the framework has not been loaded yet
		if(self::$loaded == true) { return FALSE; }

		// The variable has been loaded;
		self::$loaded = true;

		// Register the internal autload function for SQL autoloading
		spl_autoload_register(__NAMESPACE__ . '\Elusive::loader');

		// Populate the Request Object
		Request::get_instance();

		return TRUE;
	}

	/**
	 * Autoloader for non-instanciated classes
	 *
	 * @param string $class
	 *
	 * @return boolean
	 */
	public static function loader($class)
	{
		// If the class already exists, prevent reloading it
		if(class_exists($class)) { return true; }

		// Separate the namespace from the class name
		$name_space = explode('\\', $class);
		$class_name = implode('', array_splice($name_space, -1));
		$name_space = implode('/', $name_space);

		// Determine the path to the requested class
		$path = self::find_file(PATH_ROOT . "/{$name_space}/{$class_name}.class.php");
		$path = ($path === FALSE) ? self::find_file(PATH_ROOT . "/{$name_space}/class.{$class_name}.php") : $path;
		$path = ($path === FALSE) ? self::find_file(PATH_ROOT . "/{$name_space}/{$class_name}.php") : $path;

		// If the file exists, require the file and return true
		if($path !== FALSE)
		{
			require_once($path);

			// initialize static classes
			if (method_exists($class, '__static_construct'))
			{
            	$class::__static_construct();
        	}

			return true;
		}
		else
		{
			// Do not throw an error here as it can cause issues with
			// other libraries that may register autoloaders after this one.
			return false;
		}
	}


	/**
	 * Case insensitive file search
	 *
	 * To accomodate case insensitive URL requests which call class files that
	 * that may be case-sensitive, we poll the directory if the case-sensitive
	 * file can't be found and return any case-insensitive match.
	 *
	 * @param string $file Full file path and file name
	 *
	 * @return mixed original file, case insensitive matching file, or FALSE
	 */
	private static function find_file($file)
	{
		if(file_exists($file)) { return $file; }

		$directory = dirname($file);
		$fileArray = glob($directory . '/*', GLOB_NOSORT);
		$fileLower = strtolower($file);

		foreach($fileArray as $f)
		{
			if(strtolower($f) == $fileLower)
			{
				return $f;
			}
		}
		return FALSE;
	}
}
