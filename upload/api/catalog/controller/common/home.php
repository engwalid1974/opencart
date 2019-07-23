<?php
class ApiControllerCommonHome extends Controller {
	public function index() {
//-> Test-1
		//$this->load->controller('common/home');
		//$this->load->controller('api/cart/add');
		//return;
//<- Test-1

		$data = array();

		$data['API_Version'] = API_VERSION;
		$data['route'] = 'API-CATALOG, common/home';

//-> Test-2
		$data['test'] = $this->api->language->get('text_test');

		$filter_data = array(
			'filter_category_id'  => 28
		);

		$this->load->model('catalog/product');
		$data['products'] = $this->model_catalog_product->getProducts($filter_data);;
//<- Test-2

		$this->api->response->setOutput($data);
	}
}
