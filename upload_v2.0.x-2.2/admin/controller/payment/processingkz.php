<?php 
define('MODULE_VERSION', 'v2.1.2');
class ControllerPaymentProcessingkz extends Controller {
	private $error = array(); 
	private $token;
	protected $data = array();

	public function index() {

		$extension = version_compare(VERSION, '2.3.0', '>=') ? "extension/" : "";
        $edit = version_compare(VERSION, '2.0.0', '>=') ? "edit" : "update";
        $link = version_compare(VERSION, '2.3.0', '>=') ? "extension/extension" : "extension/module";

        if (version_compare(VERSION, '3.0.0', '>=')) {
            $link = "marketplace/extension";
        }

        if (version_compare(VERSION, '2.2.0', '>=')) {
            $this->load->language($extension . 'payment/processingkz');
            $ssl = true;
        } else {
            $this->language->load('payment/processingkz');
            $ssl = 'SSL';
        }

        if (isset($this->session->data['user_token'])) {
            $this->token = $this->session->data['user_token'];
            $token_name = 'user_token';
        }
        if (isset($this->session->data['token'])) {
            $this->token = $this->session->data['token'];
            $token_name = 'token';
        }

        $this->data['user_token'] = $this->data['token'] = $this->token;
        $this->data['extension'] = $extension;
        $this->data['edit'] = $edit;

        $this->data['heading_title'] = strip_tags($this->language->get('heading_title')) . ' ' . MODULE_VERSION;
		$this->document->setTitle(strip_tags($this->language->get('heading_title')) . ' ' . MODULE_VERSION);
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {			
			$this->model_setting_setting->editSetting('processingkz', $this->request->post);
			$this->model_setting_setting->editSetting('payment_processingkz', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			if (version_compare(VERSION, '2.0.1', '>=')) { // иначе вылетает из админки
                $this->response->redirect($this->url->link($link, $token_name . '=' . $this->token . '&type=payment', $ssl));
            } else {
                $this->redirect($this->url->link($link, $token_name . '=' . $this->token, $ssl));
            }			
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['help_total'] = $this->language->get('help_total');
		
		$this->data['entry_shop_id'] = $this->language->get('entry_shop_id');
		$this->data['text_edit'] = $this->language->get('text_edit');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');

		$this->data['entry_order_status'] = $this->language->get('entry_order_status');	
		$this->data['entry_check_status'] = $this->language->get('entry_check_status');
		$this->data['entry_declined_status'] = $this->language->get('entry_declined_status');
		$this->data['entry_canceled_status'] = $this->language->get('entry_canceled_status');			
		$this->data['entry_comission_status'] = $this->language->get('entry_comission_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_apikey'] = $this->language->get('entry_apikey');
		$this->data['entry_visa_big_total'] = $this->language->get('entry_visa_big_total');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['shop_id'])) {
			$this->data['error_shop_id'] = $this->error['shop_id'];
		} else {
			$this->data['error_shop_id'] = '';
		}
		
		$this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', $token_name . '=' . $this->token, $ssl),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link($link, $token_name . '=' . $this->token . '&type=payment', $ssl),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link($extension . 'payment/processingkz', $token_name . '=' . $this->token, $ssl),
            'separator' => ' :: '
		);	
		
		$this->data['action'] = $this->url->link($extension . 'payment/processingkz', $token_name . '=' . $this->token, $ssl);
        $this->data['cancel'] = $this->url->link($link, $token_name . '=' . $this->token . '&type=payment', $ssl);

		$this->load->model('localisation/language');
		
		// Нижняя граница
		if (isset($this->request->post['cod_total'])) {
			$this->data['processingkz_total'] = $this->request->post['processingkz_total'];
		} else {
			$this->data['processingkz_total'] = $this->config->get('processingkz_total');
		}
		
		// Номер магазина присвоенный по договору с processing.kz
		if (isset($this->request->post['processingkz_shop_id'])) {
			$this->data['processingkz_shop_id'] = $this->request->post['processingkz_shop_id'];
		} else {
			$this->data['processingkz_shop_id'] = $this->config->get('processingkz_shop_id');
		}
		
		// Статус который надо установить после подтверждения заказа
		if (isset($this->request->post['processingkz_check_status_id'])) {
			$this->data['processingkz_check_status_id'] = $this->request->post['processingkz_check_status_id'];
		} else {
			$this->data['processingkz_check_status_id'] = $this->config->get('processingkz_check_status_id'); 
		} 
		// Статус который надо установить после авторизации банком-эмитентом
		if (isset($this->request->post['processingkz_canceled_status_id'])) {
			$this->data['processingkz_canceled_status_id'] = $this->request->post['processingkz_canceled_status_id'];
		} else {
			$this->data['processingkz_canceled_status_id'] = $this->config->get('processingkz_canceled_status_id'); 
		} 
		// Статус который надо установить после отказа банка принимать карту к оплате
		if (isset($this->request->post['processingkz_declined_status_id'])) {
			$this->data['processingkz_declined_status_id'] = $this->request->post['processingkz_declined_status_id'];
		} else {
			$this->data['processingkz_declined_status_id'] = $this->config->get('processingkz_declined_status_id'); 
		} 		
		// Статус который надо установить после подтверждения оплаты
		if (isset($this->request->post['processingkz_order_status_id'])) {
			$this->data['processingkz_order_status_id'] = $this->request->post['processingkz_order_status_id'];
		} else {
			$this->data['processingkz_order_status_id'] = $this->config->get('processingkz_order_status_id'); 
		} 				

		$this->load->model('localisation/order_status');
		
		$this->data['check_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$this->data['declined_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$this->data['canceled_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		// вкл-выкл комиссию
		if (isset($this->request->post['processingkz_comission_status_id'])) {
			$this->data['processingkz_comission_status_id'] = $this->request->post['processingkz_comission_status_id'];
		} else {
			$this->data['processingkz_comission_status_id'] = $this->config->get('processingkz_comission_status_id'); 
		} 
		$this->data['comission_statuses'][] = array(
			'comission_status_id' => 'false',
			'name' => 'Нет'
		);
		$this->data['comission_statuses'][] = array(
			'comission_status_id' => 'true',
			'name' => 'Да'
		);
		
		if (isset($this->request->post['processingkz_geo_zone_id'])) {
			$this->data['processingkz_geo_zone_id'] = $this->request->post['processingkz_geo_zone_id'];
		} else {
			$this->data['processingkz_geo_zone_id'] = $this->config->get('processingkz_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['processingkz_status'])) {
			$this->data['processingkz_status'] = $this->request->post['processingkz_status'];
		} else {
			$this->data['processingkz_status'] = $this->config->get('processingkz_status');
		}

		if (isset($this->request->post['payment_processingkz_status'])) {
			$this->data['payment_processingkz_status'] = $this->request->post['payment_processingkz_status'];
		} else {
			$this->data['payment_processingkz_status'] = $this->config->get('payment_processingkz_status');
		}
		
		if (isset($this->request->post['processingkz_sort_order'])) {
			$this->data['processingkz_sort_order'] = $this->request->post['processingkz_sort_order'];
		} else {
			$this->data['processingkz_sort_order'] = $this->config->get('processingkz_sort_order');
		}
		
		// Настройки параметров Visa
		// Сумма, которую вы считаете большой
		 if (isset($this->request->post['processingkz_visa_big_total'])) {
			$this->data['processingkz_visa_big_total'] = $this->request->post['processingkz_visa_big_total'];
		} else {
			$this->data['processingkz_visa_big_total'] = $this->config->get('processingkz_visa_big_total');
		} 
				
		// Ключ для GeoIP в сервисе http://ipinfodb.com/
		if (isset($this->request->post['processingkz_apikey'])) {
			$this->data['processingkz_apikey'] = $this->request->post['processingkz_apikey'];
		} else {
			$this->data['processingkz_apikey'] = $this->config->get('processingkz_apikey');
		}	
		
		if (version_compare(VERSION, '2.0.1', '>=')) {
            $this->data['header'] = $this->load->controller('common/header');
            $this->data['column_left'] = $this->load->controller('common/column_left');
            $this->data['footer'] = $this->load->controller('common/footer');

            $tpl = version_compare(VERSION, '2.2.0', '>=') ? "" : ".tpl";
            $this->response->setOutput($this->load->view($extension . 'payment/processingkz' . $tpl, $this->data));
        } else {
            $this->template = 'payment/processingkz.tpl';
            $this->children = array(
                'common/header',
                'common/footer'
            );
            $this->response->setOutput($this->render());
        }   
		
	}

	private function validate() {
		$extension = version_compare(VERSION, '2.3.0', '>=') ? "extension/" : "";

		if (!$this->user->hasPermission('modify', $extension . 'payment/processingkz')) {
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

		$this->data['processingkz_shop_id'] = '000000000000015';
        $this->data['processingkz_comission_status_id'] = 'false';
        $this->data['processingkz_visa_big_total'] = '50000';
		$this->data['processingkz_geo_zone_id'] = '0';
		$this->data['processingkz_status'] = '1';
		$this->data['payment_processingkz_status'] = '1';

		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('processingkz', $this->data);       
	}

	public function uninstall() {
		$this->load->model('sale/visa');
		$this->model_sale_visa->dropDatabaseTables();
	}
}
class ControllerExtensionPaymentProcessingkz extends ControllerPaymentProcessingkz
{ }