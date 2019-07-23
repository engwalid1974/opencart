<?php
// Error Reporting
error_reporting(E_ALL);

// Check Version
if (version_compare(phpversion(), '7.0.0', '<')) {
	header('Content-Type: application/json', true);

	$output = array(
		'API_Version' => API_VERSION,
		'error'       => 'PHP7+ Required'
	);

	echo json_encode($output);
	exit;
}

if (!ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

// Windows IIS Compatibility
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
	if (isset($_SERVER['SCRIPT_FILENAME'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
	if (isset($_SERVER['PATH_TRANSLATED'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['REQUEST_URI'])) {
	$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);

	if (isset($_SERVER['QUERY_STRING'])) {
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
	}
}

if (!isset($_SERVER['HTTP_HOST'])) {
	$_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}

// Check if SSL
if ((isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) || (isset($_SERVER['HTTPS']) && (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443))) {
	$_SERVER['HTTPS'] = true;
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
	$_SERVER['HTTPS'] = true;
} else {
	$_SERVER['HTTPS'] = false;
}

// Modification Override
function modification($filename) {
	if (substr($filename, 0, strlen(DIR_API_APPLICATION)) == DIR_API_APPLICATION) {
		if (defined('DIR_API_CATALOG')) {
			$file = DIR_MODIFICATION . 'api/admin/' .  substr($filename, strlen(DIR_API_APPLICATION));
		} else {
			$file = DIR_MODIFICATION . 'api/catalog/' . substr($filename, strlen(DIR_API_APPLICATION));
		}
	} elseif (substr($filename, 0, strlen(DIR_APPLICATION)) == DIR_APPLICATION) {
		if (defined('DIR_CATALOG')) {
			$file = DIR_MODIFICATION . 'admin/' .  substr($filename, strlen(DIR_APPLICATION));
		} else {
			$file = DIR_MODIFICATION . 'catalog/' . substr($filename, strlen(DIR_APPLICATION));
		}
	}

	if (substr($filename, 0, strlen(DIR_API_SYSTEM)) == DIR_API_SYSTEM) {
		$file = DIR_MODIFICATION . 'api/system/' . substr($filename, strlen(DIR_API_SYSTEM));
	} elseif (substr($filename, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
		$file = DIR_MODIFICATION . 'system/' . substr($filename, strlen(DIR_SYSTEM));
	}

	if (isset($file) && is_file($file)) {
		require($file);
		return $file;
	}

	return $filename;
}

// Autoloader
if (is_file(DIR_STORAGE . 'vendor/autoload.php')) {
	require_once(DIR_STORAGE . 'vendor/autoload.php');
}

function library($class) {
	$file = DIR_SYSTEM . 'library/' . str_replace('\\', '/', strtolower($class)) . '.php';

	if (is_file($file)) {
		include_once(modification($file));

		return true;
	} else {
		return false;
	}
}

spl_autoload_register('library');
spl_autoload_extensions('.php');

// Engine
require_once(modification(DIR_SYSTEM . 'engine/action.php'));
require_once(modification(DIR_SYSTEM . 'engine/controller.php'));
require_once(modification(DIR_SYSTEM . 'engine/event.php'));
require_once(modification(DIR_SYSTEM . 'engine/loader.php'));
require_once(modification(DIR_SYSTEM . 'engine/model.php'));
require_once(modification(DIR_SYSTEM . 'engine/registry.php'));
require_once(modification(DIR_SYSTEM . 'engine/proxy.php'));

require_once(modification(DIR_API_SYSTEM . 'engine/action.php'));
require_once(modification(DIR_API_SYSTEM . 'engine/event.php'));
require_once(modification(DIR_API_SYSTEM . 'engine/router.php'));
require_once(modification(DIR_API_SYSTEM . 'engine/loader.php'));
require_once(modification(DIR_API_SYSTEM . 'engine/registry.php'));

// Helper
require_once(DIR_SYSTEM . 'helper/general.php');
require_once(DIR_SYSTEM . 'helper/utf8.php');

function start($application_config) {
	require_once(DIR_API_SYSTEM . 'framework.php');
}
