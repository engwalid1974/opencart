<?php
class ApiControllerCommonHome extends Controller {
	public function index() {
		$data = array();

		$data['API_Version'] = API_VERSION;
		$data['route'] = 'API-ADMIN, common/home';

//-> Test-1
		$this->load->model('catalog/product');
		$data['product (40)'] = $this->model_catalog_product->getProduct(40);

		$this->api_load->model('catalog/product');
		$data['product (42)'] = $this->api_model_catalog_product->getProduct(42);
//<- Test-1

		$this->api_response->setOutput($data);
	}
}
