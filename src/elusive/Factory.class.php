<?php namespace elusive;

/**
 * Abstract Factory Class - defines the base functionality of a factory
 *
 * A factory must be able to use an identifier to create a specific instance of
 * a class based on that identifier. Generally this is used to create an
 * instance of a specific subclass when using inheritance. Note: this should be
 * an interface, but interfaces in PHP don't allow static methods to be defined.
 *
 * @copyright Copyright (C) 2021 Elusive Concepts, LLC.
 * @author Roger Soucy <roger.soucy@elusive-concepts.com>
 * @license https://www.gnu.org/licenses/gpl.html GNU General Public License, version 3
 * @version 1.00.000
 */
abstract class Factory
{
	/**
	 * The create method of a factory is required to return an object. The type
	 * of object returned is not defined, though any factory implementation
	 * should limit returned objects to a specific base type.
	 *
	 * @param  string|null $name a unique identifier for the object to be created
	 *
	 * @return object            the newly created object instance
	 */
	abstract public static function create(?string $name = null) : object;
}