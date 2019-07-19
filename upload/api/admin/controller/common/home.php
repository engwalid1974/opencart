<?php
class ApiControllerCommonHome extends Controller {
	public function index() {
		// ToDo
		echo "API - Admin - Home <br>";

		/*
		//-> test-1
		$this->load->language('design/layout');
		$success = $this->language->get('text_success');
		echo $success . "<br>";
		//<- test-1
		*/

		/*
		//-> test-2
		$test = $this->api_language->get('text_test');
		echo $test . "<br>";
		//<- test-2
		*/

		/*
		//-> test-3
		$this->load->model('catalog/product');
		$products = $this->model_catalog_product->getProducts();
		echo "<pre>";
		print_r($products);
		//<- test-3
		*/

		/*
		//-> test-4
		$this->api_load->model('catalog/product');
		$products = $this->api_model_catalog_product->getProducts();
		echo "<pre>";
		print_r($products);
		//<- test-4
		*/
	}
}