<?php namespace elusive\core;

class Request
{
	private static $_instance = NULL;

	public $app  = array();
	public $vars = array();
	public $data = array();
	public $raw  = array();

	public $response = "HTML";

	private function __construct()
	{
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
		// note: post comes last because we trust it more
		$this->vars = array_merge($this->data['GET'], $this->data['POST']);

		// Security Measure
		$this->raw['ENV']['PATH']  = 'REMOVED';
		$this->data['ENV']['PATH'] = 'REMOVED';

		// Parse the request
		$uri       = $this->data['ENV']['REQUEST_URI'];
		$uri       = trim(preg_replace('/\?.*/', '', $uri), '/');
		$this->app = explode('/', $uri);

		// Response Type
		$this->response = isset($this->data['GET']['response'])  ? $this->data['GET']['response'] : $this->response;
		$this->response = isset($this->data['POST']['response']) ? $this->data['GET']['response'] : $this->response;
	}


	public static function get_instance()
	{
		if(self::$_instance == NULL)
		{
			self::$_instance = new Request();
		}
		return self::$_instance;
	}

	public function dump()
	{
		echo "<pre><strong>Request->app</strong>\n"  . print_r($this->app,1)  . "</pre>";
		echo "<pre><strong>Request->raw</strong>\n"  . print_r($this->raw,1)  . "</pre>";
		echo "<pre><strong>Request->data</strong>\n" . print_r($this->data,1) . "</pre>";
	}

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
				//$value = mb_convert_encoding((string) $value, 'UTF-8', 'UTF-8');

				if($allow_html)
				{
					$value = filter_var($value, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_HIGH);
				}
				else
				{
					$value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_HIGH);
				}

				$value = trim($value);

				$data[$key] = $value;
			}
		}

		return $data;
	}

}