<?php

use elusive\core\Application;
use elusive\debug\Debug;

/**
 * Start Benchmark
 */
require (__DIR__) . '/../elusive/debug/class.debug.php';
require (__DIR__) . '/../elusive/lib/class.timer.php';
Debug::start_benchmark('execution_time', 'Starting bootstrap...');

/**
 * Load the Bootstrap
 */
require (__DIR__) . '/../elusive/core/bootstrap.php';

/**
 * Run the Application
 */
Application::run(array('test' => "test"));

