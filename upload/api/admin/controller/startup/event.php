<?php
class ApiControllerStartupEvent extends Controller {
	public function index() {
		// Add events from the DB
		$this->load->model('setting/event');
		
		$results = $this->model_setting_event->getEvents();
		
		foreach ($results as $result) {
			if ((substr($result['trigger'], 0, 10) == 'api/admin/') && $result['status']) {
				$this->event->register(substr($result['trigger'], 10), new ApiAction($result['action']), $result['sort_order']);
			}
		}
				
	}
}