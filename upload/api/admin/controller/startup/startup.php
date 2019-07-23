<?php
class ApiControllerStartupStartup extends Controller {
	public function index() {
		// Pre Actions
		$action_pre_action = array(
			'startup/startup',
			'startup/event'
		);

		foreach ($action_pre_action as $value) {
			$pre_action = new Action($value);

			$action = $pre_action->execute($this->registry);

			if ($action instanceof Action) {
				while ($action instanceof Action) {
					$action = $action->execute($this->registry);
				}

				break;
			}
		}

		// Language
		$file = DIR_API_SYSTEM . 'library/language.php';

		include_once(modification($file));

		$code = $this->language->directory;

		$language = new ApiLanguage($code);
		$language->load($code);
		$this->api->set('language', $language);
	}
}
