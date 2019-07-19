<?php
// Session
$_['session_autostart']  = false;
$_['session_engine']     = 'db';
$_['session_name']       = 'OCSESSID';

// Actions
$_['action_default']     = 'common/home';

$_['admin_action_pre_action']  = array(
	'startup/startup',
	'startup/event'
);

$_['catalog_action_pre_action']  = array(
	'startup/startup',
	'startup/event'
);

// Action Events
$_['admin_action_event'] = array(
	'controller/*/before' => array(
		'event/language/before'
	),
	'controller/*/after' => array(
		'event/language/after'
	)
);

$_['catalog_action_event'] = array(
	'controller/*/before' => array(
		'event/language/before'
	),
	'controller/*/after' => array(
		'event/language/after'
	)
);
