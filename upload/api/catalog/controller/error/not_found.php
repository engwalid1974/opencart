<?php
class ApiControllerErrorNotFound extends Controller {
	public function index() {
		$data = array();

		$data['API_Version'] = API_VERSION;
		$data['route'] = 'API-CATALOG, error/not_found';

		// ToDo
		$this->api->response->setError('Bad request...');

		$this->api->response->setOutput($data);
	}
}