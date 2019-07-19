<?php
class ApiControllerStartupEvent extends Controller {
	public function index() {
		// Add events from the DB
		$this->api_load->model('setting/event');
		
		$results = $this->api_model_setting_event->getEvents();

		foreach ($results as $result) {
			$this->api_event->register(substr($result['trigger'], strlen('api/catalog/')), new ApiAction($result['action']), $result['sort_order']);
		}
	}
}