<?php 
class ModelPaymentProcessingkz extends Model {
  	public function getMethod($address, $total) {
		$this->load->language('payment/processingkz');
		
    	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('processingkz_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
		if ($this->config->get('processingkz_total') > 0 && $this->config->get('processingkz_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('processingkz_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'         => 'processingkz',
        		'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('processingkz_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
	
	public function addVisa($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "visa` SET transaction_status = '" . $this->db->escape($data['transaction_status']) . "',
		order_id = '" . (int)$data['order_id'] . "',
		customer_reference = '" . $this->db->escape($data['customer_reference']) . "',
		total = '" . (float)$data['total'] . "',
		date_added = NOW()");

		$visa_id = $this->db->getLastId();

		return $visa_id;
	}
	
	public function addVisaToOrder($visa_id,$order_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET visa_id = '" . (int)$visa_id . "' WHERE order_id = '" . (int)$order_id . "'");
		
		return true;
	}
	public function editVisa($data) {
		$this->db->query("UPDATE`" . DB_PREFIX . "visa` SET transaction_status = '" . $this->db->escape($data['transaction_status']) . "',
		customer_reference = '" . $this->db->escape($data['customer_reference']) . "',
		date_added = NOW() WHERE order_id = '" . (int)$data['order_id'] . "'");

		$visa_id = $this->db->getLastId();

		return $visa_id;
	}
	
}
?>