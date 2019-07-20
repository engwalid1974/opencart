<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Response class
*/
class ApiResponse {
	protected $registry;

	private $headers = array();
	private $level = 0;
	private $output;
	private $error = array();

	/**
	 * 
 	*/
	public function __construct($registry) {
		$this->registry = $registry;
	}

	/**
	 * 
 	*/
	public function addHeader($header) {
		$this->headers[] = $header;
	}
	
	/**
	 * 
	 *
	 * @param	int		$level
 	*/
	public function setCompression($level) {
		$this->level = $level;
	}
	
	/**
	 * 
	 *
	 * @return	array
 	*/
	public function getOutput() {
		return $this->output;
	}

	/**
	 * 
	 *
	 * @param	string	$output
 	*/	
	public function setOutput($output) {
		$this->output = $output;
	}

	/**
	 * 
 	*/
	public function getError() {
		return $this->error;
	}

	/**
	 * 
 	*/	
	public function setError($error_message) {
		$this->error['errors'][] = $error_message;
	}

	/**
	 * 
 	*/	
	public function clearOutput() {
		$this->output = array();
	}

	/**
	 * 
	 *
	 * @param	string	$data
	 * @param	int		$level
	 * 
	 * @return	string
 	*/
	private function compress($data, $level = 0) {
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
			$encoding = 'gzip';
		}

		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
			$encoding = 'x-gzip';
		}

		if (!isset($encoding) || ($level < -1 || $level > 9)) {
			return $data;
		}

		if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
			return $data;
		}

		if (headers_sent()) {
			return $data;
		}

		if (connection_status()) {
			return $data;
		}

		$this->addHeader('Content-Encoding: ' . $encoding);

		return gzencode($data, (int)$level);
	}
	
	/**
	 * 
 	*/
	public function output() {
		if ($this->output) {
			if (is_array($this->output)) {
				$this->addHeader('Content-Type: application/json');
				$this->output = array_merge($this->error, $this->output);
				$this->error = array();
				$this->output = json_encode($this->output);
			}
		} else {
			$this->output = $this->registry->get('response')->getOutput();
			$this->headers = $this->registry->get('response')->getHeaders();
		}

		if (!empty($this->error['errors'])) {
			$error = '';

			foreach ($this->error['errors'] as $value) {
				$error = $error . $value . "<br>";
			}

			$this->output = $error . $this->output;
		}

		if ($this->output) {
			$output = $this->level ? $this->compress($this->output, $this->level) : $this->output;

			if (!headers_sent()) {
				foreach ($this->headers as $header) {
					header($header, true);
				}
			}

			echo $output;
		}
	}
}
