<?php
/**
 * Elusive Framework Timer Class File
 *
 * @copyright Copyright (C) 2011-2016 Elusive Concepts, LLC.
 * @author Roger Soucy <roger.soucy@elusive-concepts.com>
 * @license https://www.gnu.org/licenses/gpl.html GNU General Public License, version 3
 * @version 1.00.000
 *
 * @package Elusive\Lib
 */

namespace elusive\lib;

/**
 * Timer Class
 *
 * Encapsulates timer functionality with the ability to add named time 
 * stops and comments.
 */
class Timer
{
	/** @var array Timer tick mark array */
	private $marks        = array();

	/** @var int Current mark index */
	private $current_mark = 0;


	/**
	 * Constructor
	 */
	public function __construct()
	{

	}


	/**
	 * Start the Timer
	 *
	 * Starts the timer with the mark TIMER_START and stores an 
	 * optional comment.
	 *
	 * @param string $comment Timer start comment (optional)
	 *
	 * @return void
	 */
	public function start($comment = "")
	{
		if(empty($this->marks['TIMER_START']))
		{
			$this->mark('TIMER_START', $comment);
		}
	}

	
	/**
	 * Add a Timer Mark
	 *
	 * Creates a timer mark with the specified name and optional comment.
	 *
	 * @param string $id Timer mark name (defaults to MARK_n if not specified)
	 * @param string $comment Timer mark comment (optional)
	 */
	public function mark($id, $comment = "")
	{
		$id = isset($id) ? $id : 'MARK_' . $this->current_mark++;

		$this->marks[$id] = array(
			'TIME'    => $this->get_microtime(),
			'COMMENT' => $comment
		);
	}


	/**
	 * Stop the Timer
	 *
	 * Stops the timer with the mark TIMER_STOP and stores an 
	 * optional comment.
	 *
	 * @param string $comment Timer stop comment (optional)
	 *
	 * @return void
	 */
	public function stop($comment = "")
	{
		$this->mark('TIMER_STOP', $comment);
	}


	/**
	 * Get a named timer mark
	 *
	 * Retreive a named timer mark elapsed time and comment.
	 *
	 * @param string $id Timer mark name
	 * @param string $format Time format (default: ms)
	 *                       Valid Options:
	 *                           ms = milliseconds
	 *                           s  = seconds
	 *                           m  = minutes
	 *
	 * @return array
	 */
	public function get_mark($id, $format = "")
	{
		return array(
			'TIME'    => $this->get_time($format, 'TIMER_START', $id),
			'COMMENT' => $this->marks[$id]['COMMENT']
		);
	}

	/**
	 * Get all timer marks
	 *
	 * Retreive the elapsed time and comments of all timer marks.
	 *
	 * @param string $format Time format (default: ms)
	 *                       Valid Options:
	 *                           ms = milliseconds
	 *                           s  = seconds
	 *                           m  = minutes
	 *
	 * @return array
	 */
	public function get_marks($format = "")
	{
		$marks = array();
		foreach($this->marks as $id => $mark)
		{
			$marks[$id] = array(
				'TIME'    => $this->get_time($format, 'TIMER_START', $id),
				'COMMENT' => $mark['COMMENT']
			);
		}
		return $marks;
	}


	/**
	 * Get elapsed time between two marks
	 *
	 * Retreive the elapsed time between two timer marks.
	 *
	 * @param string $format Time format (default: ms)
	 *                       Valid Options:
	 *                           ms = milliseconds
	 *                           s  = seconds
	 *                           m  = minutes
	 * @param string $mark_1 Name of start mark for calulation (default: TIMER_START)
	 * @param string $mark_2 Name of end mark for calulation (default: TIMER_STOP)
	 *
	 * @return array
	 */
	public function get_time($format = "", $mark_1 = 'TIMER_START', $mark_2 = 'TIMER_STOP')
	{
		$t1 = isset($this->marks[$mark_1]) ? $this->marks[$mark_1]['TIME'] : $this->get_microtime();
		$t2 = isset($this->marks[$mark_2]) ? $this->marks[$mark_2]['TIME'] : $this->get_microtime();
		$t = $t2 - $t1;

		switch($format)
		{
			case 'ms':
				$t = ($t * 1000);
				return number_format($t, 3, '.', '') . 'ms';

			case 's':
				return number_format($t, 3, '.', '') . 's';

			case 'm':
				$t = ($t / 60);
				return number_format($t, 3, '.', '') . 'm';

			default:
				return $t;
		}
	}


	/**
	 * Get the Current Microtime
	 *
	 * Returns the current microseconds since the unix epoch (1970-1-1 00:00:00)
	 *
	 * @return int
	 */
	private function get_microtime()
	{
	    list($u, $s) = explode(' ',microtime());
	    return $u + $s;
	}
}
