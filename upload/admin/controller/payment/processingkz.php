<?php 
class ControllerPaymentProcessingkz extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/processingkz');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {			
			$this->model_setting_setting->editSetting('processingkz', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['entry_total'] = $this->language->get('entry_total');
		$data['help_total'] = $this->language->get('help_total');
		
		$data['entry_shop_id'] = $this->language->get('entry_shop_id');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['entry_order_status'] = $this->language->get('entry_order_status');	
		$data['entry_check_status'] = $this->language->get('entry_check_status');
		$data['entry_declined_status'] = $this->language->get('entry_declined_status');
		$data['entry_canceled_status'] = $this->language->get('entry_canceled_status');			
		$data['entry_comission_status'] = $this->language->get('entry_comission_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_apikey'] = $this->language->get('entry_apikey');
		$data['entry_visa_big_total'] = $this->language->get('entry_visa_big_total');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
 		if (isset($this->error['shop_id'])) {
			$data['error_shop_id'] = $this->error['shop_id'];
		} else {
			$data['error_shop_id'] = '';
		}
		
		$data['breadcrumbs'] = array();		
		
        $data['token'] = $this->session->data['token'];

   		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		
   		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_payment'),      		
   		);

   		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('payment/processingkz', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title'),      		
   		);
					
		$data['action'] = $this->url->link('payment/processingkz', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		// Нижняя граница
		if (isset($this->request->post['cod_total'])) {
			$data['processingkz_total'] = $this->request->post['processingkz_total'];
		} else {
			$data['processingkz_total'] = $this->config->get('processingkz_total');
		}
		
		// Номер магазина присвоенный по договору с processing.kz
		if (isset($this->request->post['processingkz_shop_id'])) {
			$data['processingkz_shop_id'] = $this->request->post['processingkz_shop_id'];
		} else {
			$data['processingkz_shop_id'] = $this->config->get('processingkz_shop_id');
		}
		
		// Статус который надо установить после подтверждения заказа
		if (isset($this->request->post['processingkz_check_status_id'])) {
			$data['processingkz_check_status_id'] = $this->request->post['processingkz_check_status_id'];
		} else {
			$data['processingkz_check_status_id'] = $this->config->get('processingkz_check_status_id'); 
		} 
		// Статус который надо установить после авторизации банком-эмитентом
		if (isset($this->request->post['processingkz_canceled_status_id'])) {
			$data['processingkz_canceled_status_id'] = $this->request->post['processingkz_canceled_status_id'];
		} else {
			$data['processingkz_canceled_status_id'] = $this->config->get('processingkz_canceled_status_id'); 
		} 
		// Статус который надо установить после отказа банка принимать карту к оплате
		if (isset($this->request->post['processingkz_declined_status_id'])) {
			$data['processingkz_declined_status_id'] = $this->request->post['processingkz_declined_status_id'];
		} else {
			$data['processingkz_declined_status_id'] = $this->config->get('processingkz_declined_status_id'); 
		} 		
		// Статус который надо установить после подтверждения оплаты
		if (isset($this->request->post['processingkz_order_status_id'])) {
			$data['processingkz_order_status_id'] = $this->request->post['processingkz_order_status_id'];
		} else {
			$data['processingkz_order_status_id'] = $this->config->get('processingkz_order_status_id'); 
		} 				

		$this->load->model('localisation/order_status');
		
		$data['check_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['declined_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['canceled_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		// вкл-выкл комиссию
		if (isset($this->request->post['processingkz_comission_status_id'])) {
			$data['processingkz_comission_status_id'] = $this->request->post['processingkz_comission_status_id'];
		} else {
			$data['processingkz_comission_status_id'] = $this->config->get('processingkz_comission_status_id'); 
		} 
		$data['comission_statuses'][] = array(
			'comission_status_id' => 'false',
			'name' => 'Нет'
		);
		$data['comission_statuses'][] = array(
			'comission_status_id' => 'true',
			'name' => 'Да'
		);
		
		if (isset($this->request->post['processingkz_geo_zone_id'])) {
			$data['processingkz_geo_zone_id'] = $this->request->post['processingkz_geo_zone_id'];
		} else {
			$data['processingkz_geo_zone_id'] = $this->config->get('processingkz_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['processingkz_status'])) {
			$data['processingkz_status'] = $this->request->post['processingkz_status'];
		} else {
			$data['processingkz_status'] = $this->config->get('processingkz_status');
		}
		
		if (isset($this->request->post['processingkz_sort_order'])) {
			$data['processingkz_sort_order'] = $this->request->post['processingkz_sort_order'];
		} else {
			$data['processingkz_sort_order'] = $this->config->get('processingkz_sort_order');
		}
		
		// Настройки параметров Visa
		// Сумма, которую вы считаете большой
		 if (isset($this->request->post['processingkz_visa_big_total'])) {
			$data['processingkz_visa_big_total'] = $this->request->post['processingkz_visa_big_total'];
		} else {
			$data['processingkz_visa_big_total'] = $this->config->get('processingkz_visa_big_total');
		} 
				
		// Ключ для GeoIP в сервисе http://ipinfodb.com/
		if (isset($this->request->post['processingkz_apikey'])) {
			$data['processingkz_apikey'] = $this->request->post['processingkz_apikey'];
		} else {
			$data['processingkz_apikey'] = $this->config->get('processingkz_apikey');
		}			
		
		$data['header'] = $this->load->controller('common/header');	
		$data['column_left'] = $this->load->controller('common/column_left');	
		$data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/processingkz.tpl', $data));       
		
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/processingkz')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['processingkz_shop_id']) {			
			$this->error['shop_id'] = $this->language->get('error_shop_id');
		}
		
		/* if (!(int)$this->request->post['processingkz_terminal_id']) {			
			$this->error['terminal_id'] = $this->language->get('error_terminal_id');
		} */
		
		/* if (!$this->request->post['processingkz_merchant_key']) {
			$this->error['merchant_key'] = $this->language->get('error_merchant_key');
		} */
		
		return !$this->error;
	}
	
	public function install() {
		$this->load->model('sale/visa');
		$this->model_sale_visa->createDatabaseTables();
	}

	public function uninstall() {

		$this->load->model('sale/visa');
		$this->model_sale_visa->dropDatabaseTables();
	}
}
?>