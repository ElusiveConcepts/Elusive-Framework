<?php namespace elusive\debug;

use elusive\debug\Debug;
use elusive\core\Request;

class View
{

	const NL = "\n";
	const BR = "<br>\n";

	protected $debug   = FALSE;
	protected $request = NULL;
	protected $traces  = array();
	protected $errors  = array();
	protected $logs    = array();
	protected $events  = array();

	protected $page   = array();


	public function __construct()
	{
		// Safety Net
		if(!defined('SERVER_MODE')) { define('SERVER_MODE', 'PRODUCTION'); }

		if(SERVER_MODE == 'DEBUG') { $this->debug = TRUE; }

		$this->request = Request::get_instance();
		$this->traces  = Debug::get('traces');
		$this->errors  = Debug::get('errors');
		$this->logs    = Debug::get('logs');
		$this->events  = Debug::get('events');
	}


	public function render($template, $return_html = FALSE)
	{
		if($return_html) { ob_start(); }

		require(PATH_DEBUG . '/templates/' . $template . '.' . strtolower(SERVER_MODE) . '.tpl.php');

		if($return_html)
		{
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}
	}


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
