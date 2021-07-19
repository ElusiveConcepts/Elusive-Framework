<?php namespace elusive;

/**
 * Abstract Singleton Class - defines the base functionality of a singleton
 *
 * A singleton must be able to return a single, static instance of an object
 * regardless of where it is called. Generally this is used to create an
 * instance of an object that is accessible and shared across multiple classes.
 *
 * @copyright Copyright (C) 2021 Elusive Concepts, LLC.
 * @author Roger Soucy <roger.soucy@elusive-concepts.com>
 * @license https://www.gnu.org/licenses/gpl.html GNU General Public License, version 3
 * @version 1.00.000
 */
abstract class Singleton
{
	/** @var object|null holds the current instance of the object */
	protected static $_instance = null;

	/**
	 * The constructor is protected so that it may not be called externally. All
	 * instances of the singleton MUST be called through the get_instance method.
	 */
	protected function __construct()
	{

	}

	/**
	 * Return the current instance of the singleton, creating it if necessary.
	 *
	 * @return Singleton the current static instance of the singleton
	 */
	public static function get_instance() : Singleton
	{
		if(self::$_instance === null)
		{
			self::$_instance = new static();
		}

		return self::$_instance;
	}
}