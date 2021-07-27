<?php namespace elusive\core;

use \elusive\Singleton;

/**
 * Request Object
 *
 * Singleton class which holds the request paramaters and cleans them for use.
 * Must be called with Request::get_instance().
 *
 * @copyright Copyright (C) 2011-2016 Elusive Concepts, LLC.
 * @author Roger Soucy <roger.soucy@elusive-concepts.com>
 * @license https://www.gnu.org/licenses/gpl.html GNU General Public License, version 3
 * @version 1.00.000
 *
 * @package Elusive\Core
 */
class Request extends Singleton
{
	/** @var array Raw (pre-processed) request data */
	public $raw  = array();

	/** @var array Cleaned (post-processed) request data */
	public $data = array();

	/** @var array Cleaned GET/POST request parameters */
	public $vars = array();

	/** @var array URI request path elements */
	public $app  = array();

	/** @var string Response output type */
	public $response = "HTML";

	/**
	 * Constructor
	 *
	 * Populates and cleans the request properties, performs some security
	 * clean-up, and parses the request URI. Automatically called by the
	 * static get_instance method defined in Singleton
	 */
	protected function __construct()
	{
		// Add raw request data
		$this->raw['ENV']     = $_SERVER;
		$this->raw['GET']     = $_GET;
		$this->raw['POST']    = $_POST;
		$this->raw['FILES']   = $_FILES;
		$this->raw['COOKIE']  = $_COOKIE;

		// Add data after cleaning
		// (note: allow HTML on POST data for possible WYSIWIG forms)
		$this->data['ENV']     = $this->clean($_SERVER);
		$this->data['GET']     = $this->clean($_GET);
		$this->data['POST']    = $this->clean($_POST, true);
		$this->data['FILES']   = $this->clean($_FILES);
		$this->data['COOKIE']  = $this->clean($_COOKIE);

		// Drop all get and post data into an easy to access array
		// note: post comes last because we trust it more but we do strip tags
		$this->vars = array_merge($this->data['GET'], $this->clean($_POST));

		// Security Measure
		$this->raw['ENV']['PATH']  = 'REMOVED';
		$this->data['ENV']['PATH'] = 'REMOVED';

		// Parse the request
		$uri       = $this->data['ENV']['REQUEST_URI'];
		$uri       = trim(preg_replace('/\?.*/', '', $uri), '/');
		$uri       = trim(preg_replace('/(\%20|[\s-])/', '_', $uri), '/');
		$this->app = explode('/', $uri);

		// Response Type
		$this->response = isset($this->data['GET']['response'])  ? $this->data['GET']['response'] : $this->response;
		$this->response = isset($this->data['POST']['response']) ? $this->data['POST']['response'] : $this->response;
	}


	/**
	 * Limit the debugging info to specific properties
	 *
	 * @return array
	 */
	public function __debugInfo()
	{
		return [
			'app'      => $this->app,
			'vars'     => $this->vars,
			'response' => $this->response
		];
	}

	/**
	 * Clean request data
	 *
	 * Filters request data using built-in filter functions
	 * @param array $data Associative array of request parameters
	 * @param boolean $allow_html Allow HTML tags in request data
	 *
	 * @return array
	 */
	private function clean($data, $allow_html = false)
	{
		if(!is_array($data)) { $data = array($data); }

		foreach($data as $key => $value)
		{
			if(is_array($value))
			{
				$value = $this->clean($value);
			}
			else
			{
				// preserve newlines
				$value = trim(str_replace("\r\n", '~!NL!~', $value));

				if($allow_html)
				{
					$value = filter_var($value, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_HIGH);
				}
				else
				{
					$value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_HIGH);
				}

				$value = str_replace('~!NL!~', "\n", $value);
				$value = trim($value);

				$data[$key] = $value;
			}
		}

		return $data;
	}

}
