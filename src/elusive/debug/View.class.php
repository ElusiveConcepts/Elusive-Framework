<?php
/**
 * Elusive Framework View Class File
 *
 * @copyright Copyright (C) 2011-2016 Elusive Concepts, LLC.
 * @author Roger Soucy <roger.soucy@elusive-concepts.com>
 * @license https://www.gnu.org/licenses/gpl.html GNU General Public License, version 3
 * @version 1.00.000
 *
 * @package Elusive\Debug
 */

namespace elusive\debug;

use elusive\debug\Debug;
use elusive\core\Request;

/**
 * Debugging View Renderer
 *
 * Render output display for the Debugger
 */
class View
{

	const NL = "\n";
	const BR = "<br>\n";

	/** @var boolean Application debug mode active */
	protected $debug = FALSE;

	/** @var object|null Request object instance */
	protected $request = NULL;

	/** @var array Debugging traces */
	protected $traces = array();

	/** @var array Debugging errors */
	protected $errors = array();

	/** @var array Debugging logs*/
	protected $logs = array();

	/** @var array Debugging events */
	protected $events = array();

	/** @var array */
	protected $page = array();

	/**
	 * Constructor
	 *
	 * Loads debugging data from the static Debug object
	 *
	 * @return void
	 */
	public function __construct()
	{
		// Safety Net
		if(!defined('APP_MODE')) { define('APP_MODE', 'PRODUCTION'); }

		if(APP_MODE == 'DEBUG') { $this->debug = TRUE; }

		$this->request = Request::get_instance();
		$this->traces  = Debug::get('traces');
		$this->errors  = Debug::get('errors');
		$this->logs    = Debug::get('logs');
		$this->events  = Debug::get('events');
	}

	/**
	 * Render debug data output
	 *
	 * @param string $template Output template name
	 * @param boolean $return_html Return output as string (default: FALSE)
	 *
	 * @return void|string
	 */
	public function render($template, $return_html = FALSE)
	{
		if($return_html) { ob_start(); }

		if(defined(SERVER_ERROR_DOC) && SERVER_MODE == 'PRODUCTION')
		{
			require(SERVER_ERROR_DOC);
		}
		else
		{
			require(PATH_DEBUG . '/templates/' . $template . '.' . strtolower(SERVER_MODE) . '.tpl.php');
		}

		if($return_html)
		{
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}
	}

	/**
	 * Format and output errors
	 *
	 * @param array $e Error data
	 * @param string $format Output formatting (default: str)
	 *                       Valid Options:
	 *                           str = String format
	 *                           dl  = Definition list item format
	 *                           li  = Ordered/Unordered list item format
	 *                           tr  = Table row format
	 * @param boolean $ret Return output as a string (default: FALSE)
	 *
	 * @return void|string
	 */
	private function format_error($e, $format = 'str', $ret = FALSE)
	{
		$icon = 'icon ';
		$html = '';

		switch($e['type'])
		{
			case "Exception":         $icon .= 'error';   break;
			case "Error":             $icon .= 'error';   break;
			case "Parse Error":       $icon .= 'error';   break;
			case "Core Error":        $icon .= 'error';   break;
			case "Compile Error":     $icon .= 'error';   break;
			case "User Error":        $icon .= 'error';   break;
			case "Recoverable Error": $icon .= 'error';   break;
			case "Warning":           $icon .= 'warning'; break;
			case "Core Warning":      $icon .= 'warning'; break;
			case "Compile Warning":   $icon .= 'warning'; break;
			case "User Warning":      $icon .= 'warning'; break;
			case "User Notice":       $icon .= 'notice';  break;
			case "Strict Notice":     $icon .= 'notice';  break;
			case "Notice":            $icon .= 'notice';  break;
			case "Deprecated":        $icon .= 'notice';  break;
			case "Deprecated":        $icon .= 'notice';  break;
		}

		switch($format)
		{
			case 'str':
				$html = "{$e['type']} ({$e['code']}): {$e['msg']} in {$e['file']} at line {$e['line']}";
				break;

			case 'dl':
				$html = array(
					"<dt><h4 class='{$icon}'>{$e['type']} ({$e['code']})</h4></dt>",
					"<dd>",
					"	<p class='err_msg'>{$e['msg']}</p>",
					$this->create_snippet($e['file'], $e['line']),
					"	<p class='fileinfo'><strong>File:</strong> {$e['file']} | <strong>Line:</strong> {$e['line']}</p>",
					"</dd>"
				);
				$html = implode(self::NL, $html);
				break;

			case 'li':
				$html = array(
					"<li>",
					"	<h4 class='{$icon}'>{$e['type']} ({$e['code']})</h4>",
					"	<p class='err_msg'>{$e['msg']}</p>",
					$this->create_snippet($e['file'], $e['line']),
					"	<p class='fileinfo'><strong>File:</strong> {$e['file']} | <strong>Line:</strong> {$e['line']}</p>",
					"</li>"
				);
				$html = implode(self::NL, $html);
				break;

			case 'tr':
				$html = array(
					"<tr>",
					"	<td class='{$icon}'>{$e['type']} ({$e['code']})</td>",
					"	<td class='err_msg'>{$e['msg']}</td>",
					"	<td>{$e['file']}</td>",
					"	<td>L: {$e['line']}</td>",
					"</tr>"
				);
				$html = implode(self::NL, $html);
				break;
		}

		if($ret) { return $html; }
		else     { echo   $html; }
	}

	/**
	 * Output debug data as HTML table
	 *
	 * @param array $data Debug data
	 * @param array $headers Table column headers (optional)
	 * @param string $caption Table caption (optional)
	 * @param boolean $ret Return output as string (default: FALSE)
	 *
	 * @param void|string
	 */
	private function create_debug_table($data, $headers = array(), $caption = '', $ret = FALSE)
	{
		$html  = "<table class='debug_table'>" . self::NL;

		if($caption != '') { $html .= "	<caption>{$caption}</caption>" . self::NL; }

		if(is_array($headers) && count($headers) > 0)
		{
			$html .= "	<tr>" . self::NL;
			foreach($headers as $header)
			{
				$html .= "		<th>{$header}</th>" . self::NL;
			}
			$html .= "	</tr>" . self::NL;
		}

		if(!is_array($data) || count($data) < 1)
		{
			$html .= "	<tr><td colspan='2'>No Data</td></tr>" . self::NL;
		}
		else
		{
			foreach($data as $k => $v)
			{
				if(is_array($v)) { $v = $this->create_debug_table($v); }
				else             { $html .= "	<tr><td>{$k}&nbsp;</td><td>{$v}&nbsp;</td></tr>" . self::NL; }
			}
		}
		$html .= "</table>" . self::NL;

		if($ret) { return $html; }
		else     { echo   $html; }
	}

	/**
	 * Format and output code snippet
	 *
	 * @param string $file Source filename
	 * @param int $line Source line number
	 * @param boolean $ret Return output as string (default: FALSE)
	 *
	 * @return mixed
	 */
	private function create_snippet($file, $line, $ret = FALSE)
	{
		$line--;
		$lines = ($file != '') ? file($file) : FALSE;
		if(is_array($lines) && count($lines) >= $line)
		{
			$start = max($line-5, 0);
			$stop  = min($line+5, count($lines));

			$lines = array_splice($lines, $start, $stop - $start);

			$lines[$line-$start] = "###_HIGHLIGHT_START_###" . $lines[$line-$start] . "###_HIGHLIGHT_STOP_###";
			$lines = htmlentities(implode('', $lines));
			$lines = preg_replace('/###_HIGHLIGHT_START_###/', '<span class="actvln">', $lines);
			$lines = preg_replace('/###_HIGHLIGHT_STOP_###/', '</span>', $lines);

			$token = md5(uniqid());

			$html = array(
				'<div class="button code_toggle" onclick="Elusive.toggle_code(\'' . $token . '\')">Toggle Code Snippet</div>',
				'<pre id="' . $token . '" class="ec_source lang-php linenums:' . ($start+1) . '" style="display:none;">',
				$lines,
				'</pre>'
			);

			if($ret) { return implode(self::NL, $html); }
			else     { echo   implode(self::NL, $html); }
		}
		else
		{
			if($ret) { return FALSE; }
		}
	}
}
