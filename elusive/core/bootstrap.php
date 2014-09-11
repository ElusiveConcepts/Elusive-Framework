<?php namespace elusive\core;

use elusive\debug\Debug;

/**
 * Elusive Framework Bootstrap
 *
 * The bootstrap defines required constants and handles the initialization
 * of the framework. The bootstrap must be loaded (through a front loader
 * or via stand-alone script) to access features of the framework.
 */

/**
 * Define Directory Constants
 */
define('PATH_ROOT',    dirname(dirname(__DIR__)));
define('PATH_ELUSIVE', PATH_ROOT    . '/elusive');
define('PATH_CORE',    PATH_ELUSIVE . '/core');
define('PATH_DEBUG',   PATH_ELUSIVE . '/debug');
define('PATH_LIB',     PATH_ELUSIVE . '/lib');
define('PATH_TMP',     PATH_ROOT    . '/tmp');

/**
 * Load and initialize the Debugger
 */
require_once(PATH_DEBUG . '/class.debug.php');
if(!Debug::init()) { trigger_error('Could not initialize Elusive Debugger', E_ERROR); }

/**
 * Load and initialize the Framework Core Class
 */
Debug::log('Loading Framework...');
require_once(PATH_CORE . '/class.elusive.php');
if(!Elusive::init()) { trigger_error('Could not initialize Elusive Framework', E_ERROR); }

