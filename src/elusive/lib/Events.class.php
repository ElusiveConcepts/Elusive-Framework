<?php
/**
 * Elusive Framework Events Class File
 *
 * @copyright Copyright (C) 2011-2016 Elusive Concepts, LLC.
 * @author Roger Soucy <roger.soucy@elusive-concepts.com>
 * @license https://www.gnu.org/licenses/gpl.html GNU General Public License, version 3
 * @version 1.00.000
 *
 * @package Elusive\Lib
 */

namespace elusive\lib;

use elusive\debug\Debug;

/**
 * Event Class
 *
 * Provides a static event object that can be used to subscribe to and fire events.
 */
class Events
{
	/** @var array event manager storage array */
	static private $manager = array();

	/**
	 * Constructor
	 *
	 * Cannot be called externally
	 */
	private function __construct()
	{

	}


	/**
	 * Add Event Listener
	 *
	 * @param string $type event type descriptor
	 * @param string $event event name descriptor
	 * @param string $subscriber classname of the event subscriber
	 * @param string $callback callback function to be called on event
	 * @param boolean $bubble optional allow events to propogate
	 *
	 * @return void
	 */
	static public function add_event_listener($type, $event, $subscriber, $callback, $bubble = TRUE)
	{
		$type  = strtoupper($type);
		$event = strtoupper($event);

		if(!isset(self::$manager[$type]) || !is_array(self::$manager[$type]))
		{
			self::$manager[$type] = array();
		}

		if(!isset(self::$manager[$type][$event]))
		{
			self::$manager[$type][$event] = array();
		}

		self::$manager[$type][$event][$subscriber] = array(
			'bubble'   => $bubble,
			'callback' => $callback
		);
	}

	/**
	 * Add Event Listener
	 *
	 * @param string type event type descriptor
	 * @param string $event event name descriptor
	 * @param string $subscriber classname of the event subscriber
	 *
	 * @return void
	 */
	static public function remove_event_listener($type, $event, $subscriber)
	{
		$type  = strtoupper($type);
		$event = strtoupper($event);

		if(isset(self::$manager[$type][$event][$subscriber]))
		{
			unset(self::$manager[$type][$event][$subscriber]);
		}
	}


	/**
	 * Dispatch Event
	 *
	 * @param string type event type descriptor
	 * @param string $event event name descriptor
	 * @param mixed $data OPTIONAL data to be passed to event listeners
	 *
	 * @return mixed
	 */
	static public function dispatch($type, $event, $data = NULL)
	{
		$type  = strtoupper($type);
		$event = strtoupper($event);

		$listener_count = isset(self::$manager[$type][$event]) ? count(self::$manager[$type][$event]) : '0';
		Debug::log_event($type, $event, $listener_count, $data);

		$modified_data = $data;
		if(isset(self::$manager[$type][$event]))
		{
			foreach(self::$manager[$type][$event] as $subscriber => $call)
			{
				$modified_data = call_user_func(array($subscriber, $call['callback']), $modified_data, $data);
				if(!$call['bubble']) { return $modified_data; }
			}
		}
		return $modified_data;
	}
}
