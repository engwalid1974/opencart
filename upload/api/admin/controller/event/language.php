<?php
class ApiControllerEventLanguage extends Controller {
	// 1. Before controller load store all current loaded language data
	public function before(&$route, &$output) {
		$this->api_language->set('backup', $this->api_language->all());
	}
	
	// 2. After controller load restore old language data
	public function after(&$route, &$args, &$output) {
		$data = $this->api_language->get('backup');
		
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$this->api_language->set($key, $value);
			}
		}
	}
}