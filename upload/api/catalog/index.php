<?php
// Version
define('VERSION', '3.1.0.0_b');
define('API_VERSION', '1.0.0.0_a1');

// Configuration
if (is_file('../../config.php')) {
	require_once('../../config.php');
}

// Check Installed
if (!defined('DIR_APPLICATION')) {
	exit('You are not authorized to view this page!');
}

define('DIR_API_APPLICATION', DIR_API . 'catalog/');
define('DIR_API_SYSTEM', DIR_API . 'system/');
define('DIR_API_LANGUAGE', DIR_API_APPLICATION . 'language/');

// Startup
require_once(DIR_API_SYSTEM . 'startup.php');

start('catalog');