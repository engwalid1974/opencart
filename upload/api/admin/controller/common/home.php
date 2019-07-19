<?php
class ApiControllerCommonHome extends Controller {
	public function index() {
		$this->api_response->addOutput('home', 'API - Admin - Home');

		$this->load->model('catalog/product');
		$product = $this->model_catalog_product->getProduct(40);
		$this->api_response->addOutput('product (40)', $product);

		$this->api_load->model('catalog/product');
		$product = $this->api_model_catalog_product->getProduct(42);
		$this->api_response->addOutput('product (42)', $product);
	}
}