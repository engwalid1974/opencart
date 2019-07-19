<?php
class ApiControllerCommonHome extends Controller {
	public function index() {
		$this->api_response->addOutput('home', 'API - Catalog - Home');

		$test = $this->api_language->get('text_test');
		$this->api_response->addOutput('test', $test);

		$filter_data = array(
			'filter_category_id'  => 28
		);

		$this->load->model('catalog/product');
		$products = $this->model_catalog_product->getProducts($filter_data);
		$this->api_response->addOutput('Category (28)', $products);
	}
}