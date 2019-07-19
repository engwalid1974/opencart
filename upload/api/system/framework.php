<?php
// Registry
$registry = new Registry();

// Config
$config = new Config();

// Load the default config
$config->load('default');
$config->load($application_config);
$config->load('api');
$registry->set('config', $config);

// Log
$log = new Log($config->get('error_filename'));
$registry->set('log', $log);

date_default_timezone_set($config->get('date_timezone'));

set_error_handler(function($code, $message, $file, $line) use($log, $config, $registry) {
	// error suppressed with @
	if (error_reporting() === 0) {
		return false;
	}

	switch ($code) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}

	$msg = '(API) - PHP ' . $error . ':  ' . $message . ' in ' . $file . ' on line ' . $line;

	if ($config->get('error_log')) {
		$log->write($msg);
	}

	if ($config->get('error_display')) {
		$registry->get('api_response')->addErrorToOutput($msg);
	}

	return true;
});

set_exception_handler(function($e) use ($log, $config, $registry) {
	$msg = '(API) - ' . get_class($e) . ':  ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();

	if ($config->get('error_log')) {
		$log->write($msg);
	}

	if ($config->get('error_display')) {
		$registry->get('api_response')->addErrorToOutput($msg);

		$registry->get('api_response')->output();
	}
});

// Event
$event = new \Event($registry);
$registry->set('event', $event);

$api_event = new \ApiEvent($registry);
$registry->set('api_event', $api_event);

// Event Register
if ($config->has('action_event')) {
	foreach ($config->get('action_event') as $key => $value) {
		foreach ($value as $priority => $action) {
			$event->register($key, new Action($action), $priority);
		}
	}
}

if (defined('DIR_API_CATALOG')) {
	if ($config->has('admin_action_event')) {
		foreach ($config->get('admin_action_event') as $key => $value) {
			foreach ($value as $priority => $action) {
				$api_event->register($key, new ApiAction($action), $priority);
			}
		}
	}
} else {
	if ($config->has('catalog_action_event')) {
		foreach ($config->get('catalog_action_event') as $key => $value) {
			foreach ($value as $priority => $action) {
				$api_event->register($key, new ApiAction($action), $priority);
			}
		}
	}
}

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

$api_loader = new ApiLoader($registry);
$registry->set('api_load', $api_loader);

// Request
$registry->set('request', new Request());

// Response
$file = DIR_API_SYSTEM . 'library/response.php';
include_once(modification($file));

$api_response = new ApiResponse();
$api_response->setCompression($config->get('response_compression'));
$registry->set('api_response', $api_response);

// Database
if ($config->get('db_autostart')) {
	$db = new DB($config->get('db_engine'), $config->get('db_hostname'), $config->get('db_username'), $config->get('db_password'), $config->get('db_database'), $config->get('db_port'));
	$registry->set('db', $db);

	// Sync PHP and DB time zones
	$db->query("SET time_zone = '" . $db->escape(date('P')) . "'");
}

// Session
$session = new Session($config->get('session_engine'), $registry);
$registry->set('session', $session);

// Cache
$registry->set('cache', new Cache($config->get('cache_engine'), $config->get('cache_expire')));

// Url
$registry->set('url', new Url($config->get('site_url')));

// Document
$registry->set('document', new Document());

// Route
$api_route = new ApiRouter($registry);

// Pre Actions
if (defined('DIR_API_CATALOG')) {
	if ($config->has('admin_action_pre_action')) {
		foreach ($config->get('admin_action_pre_action') as $value) {
			$api_route->addPreAction(new ApiAction($value));
		}
	}
} else {
	if ($config->has('catalog_action_pre_action')) {
		foreach ($config->get('catalog_action_pre_action') as $value) {
			$api_route->addPreAction(new ApiAction($value));
		}
	}
}

// Dispatch
$api_route->dispatch(new ApiAction($config->get('action_router')), new ApiAction($config->get('action_error')));

// Output
$api_response->output();
