<?php
class ApiModelCatalogProduct extends Model {
	public function getProduct($product_id) {
		$this->load->model('catalog/product');

		$product = $this->model_catalog_product->getProduct($product_id);
		return $product;
	}

	public function getProducts($data = array()) {
		$this->load->model('catalog/product');

		$products = $this->model_catalog_product->getProducts();

		return $products;
	}
}