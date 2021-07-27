<?php namespace app\controllers;

use elusive\core\Request;
use elusive\debug\Debug;
use elusive\lib\Timer;

class Primary
{
	const DEFAULT_NAMESPACE  = 'app\\controllers';

	private $request = NULL;

	public function __construct()
	{
		$this->request = Request::get_instance();

		include_once(APP_PATH . "/app.php");

		$this->route();
	}

	public function route($ctrl = '', $func = '', $nmsp = '')
	{
	}
}
