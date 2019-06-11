<?php
class ModelAccountAffiliate extends Model {
	public function addAffiliate($customer_id, $data) {
		$affiliate_custom_field = array();

		if (!empty($data['custom_field']['affiliate'])) {
			if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $data['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}

			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields(array('filter_customer_group_id' => $customer_group_id));

			foreach ($custom_fields as $custom_field) {
				$custom_field_id = $custom_field['custom_field_id'];

				if ($custom_field['location'] == 'affiliate' && !empty($data['custom_field']['affiliate'][$custom_field_id])) {
					$affiliate_custom_field[$custom_field_id] = $data['custom_field']['affiliate'][$custom_field_id];
				}
			}
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_affiliate SET `customer_id` = '" . (int)$customer_id . "', `company` = '" . $this->db->escape((string)$data['company']) . "', `website` = '" . $this->db->escape((string)$data['website']) . "', `tracking` = '" . $this->db->escape(token(10)) . "', `commission` = '" . (float)$this->config->get('config_affiliate_commission') . "', `tax` = '" . $this->db->escape((string)$data['tax']) . "', `payment` = '" . $this->db->escape((string)$data['payment']) . "', `cheque` = '" . $this->db->escape((string)$data['cheque']) . "', `paypal` = '" . $this->db->escape((string)$data['paypal']) . "', `bank_name` = '" . $this->db->escape((string)$data['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape((string)$data['bank_branch_number']) . "', `bank_swift_code` = '" . $this->db->escape((string)$data['bank_swift_code']) . "', `bank_account_name` = '" . $this->db->escape((string)$data['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape((string)$data['bank_account_number']) . "', custom_field = '" . $this->db->escape(json_encode($affiliate_custom_field)) . "', `status` = '" . (int)!$this->config->get('config_affiliate_approval') . "'");

		if ($this->config->get('config_affiliate_approval')) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET customer_id = '" . (int)$customer_id . "', type = 'affiliate', date_added = NOW()");
		}
	}

	public function editAffiliate($customer_id, $data) {
		$affiliate_custom_field = array();

		if (!empty($data['custom_field']['affiliate'])) {
			if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $data['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}

			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields(array('filter_customer_group_id' => $customer_group_id));

			foreach ($custom_fields as $custom_field) {
				$custom_field_id = $custom_field['custom_field_id'];

				if ($custom_field['location'] == 'affiliate' && !empty($data['custom_field']['affiliate'][$custom_field_id])) {
					$affiliate_custom_field[$custom_field_id] = $data['custom_field']['affiliate'][$custom_field_id];
				}
			}
		}

		$this->db->query("UPDATE " . DB_PREFIX . "customer_affiliate SET `company` = '" . $this->db->escape((string)$data['company']) . "', `website` = '" . $this->db->escape((string)$data['website']) . "', `commission` = '" . (float)$this->config->get('config_affiliate_commission') . "', `tax` = '" . $this->db->escape((string)$data['tax']) . "', `payment` = '" . $this->db->escape((string)$data['payment']) . "', `cheque` = '" . $this->db->escape((string)$data['cheque']) . "', `paypal` = '" . $this->db->escape((string)$data['paypal']) . "', `bank_name` = '" . $this->db->escape((string)$data['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape((string)$data['bank_branch_number']) . "', `bank_swift_code` = '" . $this->db->escape((string)$data['bank_swift_code']) . "', `bank_account_name` = '" . $this->db->escape((string)$data['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape((string)$data['bank_account_number']) . "', custom_field = '" . $this->db->escape(json_encode($affiliate_custom_field)) . "' WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	public function getAffiliate($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getAffiliateByTracking($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `tracking` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function addAffiliateReport($customer_id, $ip, $country = '') {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_affiliate_report` SET customer_id = '" . (int)$customer_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', ip = '" . $this->db->escape($ip) . "', country = '" . $this->db->escape($country) . "', date_added = NOW()");
	}
}