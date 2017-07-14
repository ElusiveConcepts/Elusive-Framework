<?php
/**
 * Elusive Framework Frontloader
 *
 * @copyright Copyright (C) 2011-2016 Elusive Concepts, LLC.
 * @author Roger Soucy <roger.soucy@elusive-concepts.com>
 * @license https://www.gnu.org/licenses/gpl.html GNU General Public License, version 3
 * @version 1.00.000
 *
 * @package Elusive\Frontloader
 */

use elusive\core\Application;
use elusive\debug\Debug;

/**
 * Start Benchmark
 */
require (__DIR__) . '/../elusive/debug/Debug.class.php';
require (__DIR__) . '/../elusive/lib/Timer.class.php';
Debug::start_benchmark('execution_time', 'Starting bootstrap...');

/**
 * Load the Bootstrap
 */
require (__DIR__) . '/../elusive/core/bootstrap.php';

/**
 * Run the Application
 */
Application::run(array('test' => "test"));

