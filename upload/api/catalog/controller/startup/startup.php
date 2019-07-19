<?php
class ApiControllerStartupStartup extends Controller {
	public function index() {
		// Pre Actions
		$action_pre_action = array(
			'startup/startup',
			'startup/event'
		);

		foreach ($action_pre_action as $value) {
			$action = new Action($value);
			$action->execute($this->registry);
		}

		// Language
		$file = DIR_API_SYSTEM . 'library/language.php';

		include_once(modification($file));
			
		$code = $this->language->directory;

		$api_language = new ApiLanguage($code);
		$api_language->load($code);
		$this->registry->set('api_language', $api_language);
	}
}
