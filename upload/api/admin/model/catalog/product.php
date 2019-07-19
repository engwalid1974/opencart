<?php
class ApiModelCatalogProduct extends Model {
	public function getProducts($data = array()) {
		$this->load->model('catalog/product');

		$products = $this->model_catalog_product->getProducts();

		return $products;
	}
}