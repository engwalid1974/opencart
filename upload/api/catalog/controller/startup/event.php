<?php
class ApiControllerStartupEvent extends Controller {
	public function index() {
		// Add events from the DB
		$this->api->load->model('setting/event');
		
		$results = $this->api->model_setting_event->getEvents();

		foreach ($results as $result) {
			$this->api->event->register(substr($result['trigger'], strlen('api/catalog/')), new ApiAction($result['action']), $result['sort_order']);
		}
	}
}