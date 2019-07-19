<?php
class ApiControllerCommonHome extends Controller {
	public function index() {
		// ToDo
		echo "API - Catalog - Home <br>";

		/*
		//-> test-1
		$this->load->model('catalog/product');
		$products = $this->model_catalog_product->getProducts();
		echo "<pre>";
		print_r($products);
		//<- test-1
		*/

		/*
		//-> test-2
		$test = $this->api_language->get('text_test');
		echo $test . "<br>";
		//<- test-2
		*/
	}
}