<?php namespace elusive\lib;

class Timer
{

	private $marks        = array();
	private $current_mark = 0;

	public function __construct()
	{

	}

	public function start($comment = "")
	{
		if(empty($this->marks['TIMER_START']))
		{
			$this->marks['TIMER_START'] = array(
				'TIME'    => $this->get_microtime(),
				'COMMENT' => $comment
			);
		}
	}

	public function mark($id, $comment = "")
	{
		$id = isset($id) ? $id : 'mark_' . $this->current_mark++;

		$this->marks[$id] = array(
			'TIME'    => $this->get_microtime(),
			'COMMENT' => $comment
		);
	}

	public function stop($comment = "")
	{
		$this->marks['TIMER_STOP'] = array(
			'TIME'    => $this->get_microtime(),
			'COMMENT' => $comment
		);
	}

	public function get_mark($id, $format = "")
	{
		return array(
			'TIME'    => $this->get_time($format, 'TIMER_START', $id),
			'COMMENT' => $this->marks[$id]['COMMENT']
		);
	}

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

	public function get_time($format = "", $mark_1 = 'TIMER_START', $mark_2 = 'TIMER_STOP')
	{
		$t = $this->marks[$mark_2]['TIME'] - $this->marks[$mark_1]['TIME'];

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

	private function get_microtime()
	{
	    list($u, $s) = explode(' ',microtime());
	    return $u + $s;
	}
}