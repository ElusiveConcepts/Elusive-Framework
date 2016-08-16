<?php namespace elusive\debug;

use \Exception;
use elusive\lib\Timer;
use elusive\lib\Events;
use elusive\debug\View;

class Debug
{
	private static $loaded = FALSE;

	private static $logs       = array();
	private static $events     = array();
	private static $errors     = array();
	private static $traces     = array();
	private static $benchmarks = array();


	/**
	 * Debugger initialization
	 *
	 * Registers the exception handler
	 *
	 * @return boolean
	 */
	public static function init()
	{
		// Ensure that the framework has not been loaded yet
		if(self::$loaded == true) { return FALSE; }

		// The variable has been loaded;
		self::$loaded = true;

		// Register the error handlers
		set_exception_handler(__NAMESPACE__ . '\Debug::exception');
		set_error_handler(__NAMESPACE__ . '\Debug::error');

		// Register a template event handler
		// Note: Dynamic loader probably doesn't exist yet
		require_once(PATH_LIB.'/class.events.php');
		Events::add_event_listener('TEMPLATE', 'BEFORE_HTML_END', __CLASS__, 'render_console');

		if(defined('SERVER_MODE') && SERVER_MODE == 'DEBUG')
		{
			error_reporting(E_ALL);
		}

		return TRUE;
	}


	/**
	 * Debug Console Rendering
	 */
	public static function render_console($modified_data, $data)
	{
		if(SERVER_MODE == 'DEBUG')
		{
			$view = new View();
			$modified_data .= $view->render('console', TRUE);
		}
		return $modified_data;
	}


	/**
	 * Primary Exception Handler
	 *
	 * @param Exception $exception
	 */
	public static function exception(Exception $ex)
	{
		self::set_stack_trace($ex->getTrace());

		$error = array();
		$error['type']      = 'Exception';
		$error['code']      = $ex->getCode();
		$error['msg']       = $ex->getMessage();
		$error['file']      = $ex->getFile();
		$error['line']      = $ex->getLine();
		$error['class']     = self::$traces['stack'][0]['class'];

		self::$errors['exception'] = $error;

		$view = new View();
		$view->render('exception');

		//Display::factory('Exception');
	}

	/**
	 * Primary Error Handler
	 *
	 * @param int $e_num contains the level of the error raised
	 * @param string $e_str contains the error message
	 * @param string $e_file optional contains the filename that the error was raised in
	 * @param int $e_line optional contains the line number the error was raised at
	 * @param array $e_context optional an array of every variable that existed in the scope the error was triggered in
	 *
	 * @return boolean
	 */
	public static function error($e_num, $e_str, $e_file, $e_line, $e_context)
	{
		// This error code is not included in error_reporting
		if (!(error_reporting() & $e_num)) { return; }

		if(!defined('E_STRICT'))            { define('E_STRICT', 2048); }
		if(!defined('E_RECOVERABLE_ERROR')) { define('E_RECOVERABLE_ERROR', 4096); }
		if(!defined('E_DEPRECATED'))        { define('E_STRICT', 8192); }
		if(!defined('E_USER_DEPRECATED'))   { define('E_RECOVERABLE_ERROR', 16384); }

		if($e_num == 0) return true;

		if(in_array($e_num, array(E_USER_ERROR, E_RECOVERABLE_ERROR)))
		{
			throw new \ErrorException($e_str, 0, $e_num, $e_file, $e_line);
		}

		$e_type = "Unknown error";

		switch ($e_num)
		{
			case E_ERROR:             $e_type = "Error";             break;
			case E_WARNING:           $e_type = "Warning";           break;
			case E_PARSE:             $e_type = "Parse Error";       break;
			case E_NOTICE:            $e_type = "Notice";            break;
			case E_CORE_ERROR:        $e_type = "Core Error";        break;
			case E_CORE_WARNING:      $e_type = "Core Warning";      break;
			case E_COMPILE_ERROR:     $e_type = "Compile Error";     break;
			case E_COMPILE_WARNING:   $e_type = "Compile Warning";   break;
			case E_USER_ERROR:        $e_type = "User Error";        break;
			case E_USER_WARNING:      $e_type = "User Warning";      break;
			case E_USER_NOTICE:       $e_type = "User Notice";       break;
			case E_STRICT:            $e_type = "Strict Notice";     break;
			case E_RECOVERABLE_ERROR: $e_type = "Recoverable Error"; break;
			case E_DEPRECATED:        $e_type = "Deprecated";        break;
			case E_USER_DEPRECATED:   $e_type = "Deprecated";        break;
		}

		self::$errors['general'][] = array(
			'type' => $e_type,
			'code' => $e_num,
			'msg'  => $e_str,
			'file' => $e_file,
			'line' => $e_line
		);

		/* Don't execute PHP internal error handler */
		return TRUE;
	}

	/**
	 * Getter
	 */
	public static function get($key)
	{
		return (isset(self::$$key)) ? self::$$key : FALSE;
	}

	/**
	 * Event Logger
	 */
	public static function log_event($type, $event, $listeners, $data)
	{
		self::$events[] = array(
			'type'      => $type,
			'name'      => $event,
			'listeners' => $listeners,
			'data'      => json_encode($data)
		);
	}

	/**
	 * Logger
	 */
	public static function log($data, $pre = FALSE)
	{
		self::$logs[] = array(
			'time'      => date('Y-m-d H:i:s'),
			'data'      => ($pre) ? '<pre>' . $data . '</pre>' : $data
		);
	}

	public static function start_benchmark($benchmark, $comment = '')
	{
		if(empty(self::$benchmarks[$benchmark]))
		{
			self::$benchmarks[$benchmark] = new Timer();
			self::$benchmarks[$benchmark]->start($comment);
		}
		else
		{
			return false;
		}
	}

	public static function mark_benchmark($benchmark, $mark, $comment = '')
	{
		if(empty(self::$benchmarks[$benchmark]))
		{
			self::$benchmarks[$benchmark] = new Timer();
			self::$benchmarks[$benchmark]->start($comment);
		}
		self::$benchmarks[$benchmark]->mark($mark, $comment);
	}

	public static function get_benchmark($benchmark, $comment = '', $format = 'ms')
	{
		if(!empty(self::$benchmarks[$benchmark]))
		{
			self::$benchmarks[$benchmark]->stop($comment);
			return self::$benchmarks[$benchmark]->get_marks($format);
		}
		else
		{
			return array();
		}
	}

	public static function get_benchmarks($format = 'ms')
	{
		$benchmarks = array();
		foreach(self::$benchmarks as $benchmark => $data)
		{
			$benchmarks[$benchmark] = $data->get_marks($format);
		}
		return $benchmarks;
	}


	/**
	 * Stack Trace
	 */
	private static function set_stack_trace($stack_trace = NULL)
	{
		$traces = array();

		foreach($stack_trace as $trace)
		{
			$data = array();

			$data['class']    = array_key_exists('class',    $trace) ? $trace['class']    : '';
			$data['type']     = array_key_exists('type',     $trace) ? $trace['type']     : '';
			$data['function'] = array_key_exists('function', $trace) ? $trace['function'] : '';
			$data['args']     = array_key_exists('args',     $trace) ? $trace['args']     : '';
			$data['file']     = array_key_exists('file',     $trace) ? $trace['file']     : '';
			$data['line']     = array_key_exists('line',     $trace) ? $trace['line']     : '';

			$traces[] = $data;
		}

		self::$traces['stack'] = $traces;
	}

	/**
	 * Backtrace
	 */
	private static function set_backtrace()
	{
		if(function_exists('debug_backtrace'))
		{
			// Prevent breakage pre PHP 5.3.6; while not the same behaviour,
			// it won't break backwards compatibility however args will still show
			if(!defined('DEBUG_BACKTRACE_PROVIDE_OBJECT')) define('DEBUG_BACKTRACE_PROVIDE_OBJECT', false);
			if(!defined('DEBUG_BACKTRACE_IGNORE_ARGS')) define('DEBUG_BACKTRACE_IGNORE_ARGS', false);

			$backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT & DEBUG_BACKTRACE_IGNORE_ARGS);
			array_shift($backtrace);
			array_shift($backtrace);

			$backtrace_data = array();

			foreach($backtrace as $data)
			{
				$trace = array();

				$trace['class']    = array_key_exists('class',    $data) ? $data['class']    : '';
				$trace['type']     = array_key_exists('type',     $data) ? $data['type']     : '';
				$trace['function'] = array_key_exists('function', $data) ? $data['function'] : '';
				$trace['file']     = array_key_exists('file',     $data) ? $data['file']     : '';
				$trace['line']     = array_key_exists('line',     $data) ? $data['line']     : '';

				$backtrace_data[] = $trace;
			}

			self::$back_trace = $backtrace_data;
		}

	}

}
