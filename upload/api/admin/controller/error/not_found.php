<?php
class ApiControllerErrorNotFound extends Controller {
	public function index() {
		$data = array();

		$data['API_Version'] = API_VERSION;
		$data['route'] = 'API-ADMIN, error/not_found';

		// ToDo
		$this->api_response->setError('Bad request...');

		$this->api_response->setOutput($data);
	}
}