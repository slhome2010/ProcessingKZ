<?php
require_once(DIR_SYSTEM . 'library/processingkz/ip2locationlite.class.php');

class ControllerSaleVisa extends Controller {

    private $token;

    public function index() {
        
		$this->load->language('sale/visa');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_latest_10_orders'] = $this->language->get('text_latest_10_orders');
        $data['text_total_order'] = $this->language->get('text_total_order');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['column_visa'] = $this->language->get('column_visa');
        $data['column_order'] = $this->language->get('column_order');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_firstname'] = $this->language->get('column_firstname');
        $data['column_lastname'] = $this->language->get('column_lastname');
        $data['column_action'] = $this->language->get('column_action');
        $data['button_refresh'] = $this->language->get('button_refresh');

        if (isset($this->session->data['user_token'])) {
            $this->token = $this->session->data['user_token'];
            $token_name = 'user_token';
        }
        if (isset($this->session->data['token'])) {
            $this->token = $this->session->data['token'];
            $token_name = 'token';
        }

        $data['user_token'] = $data['token'] = $this->token;
		
        $data['breadcrumbs'] = array();
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', $token_name . '=' . $this->token, 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/visa', $token_name . '=' . $this->token, 'SSL')      
        );	

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
        $this->load->model('sale/visa');

        $data['orders'] = array();

        $param = array(
            'sort' => 'o.date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => 20
        );

        $results = $this->model_sale_visa->getOrders($param);

        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_refresh'),
                'text1' => $this->language->get('text_confirm'),
                'text2' => $this->language->get('text_cancel'),
                'text3' => $this->language->get('text_verified'),
                'href' => $this->url->link('sale/visa/info', 'token=' . $this->session->data['token'] .
                        '&order_id=' . $result['order_id'] .
                        '&customer_reference=' . $result['customer_reference'] .
                        '&transaction_status=' . $result['transaction_status'], 'SSL')
            );

            $data['orders'][] = array(
                'visa_id' => $result['visa_id'],
                'order_id' => $result['order_id'],
                'customer' => $result['customer'],
                'status' => $result['status'],
                'customer_reference' => $result['customer_reference'],
                'transaction_status' => $result['transaction_status'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'action' => $action
            );
        }
      
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $tpl = version_compare(VERSION, '2.2.0', '>=') ? "" : ".tpl";
        $this->response->setOutput($this->load->view('sale/visa' . $tpl, $data));
      
    }

    public function refresh() {

        $this->load->model('sale/visa');
        $visaID = (isset($this->request->get['visa_id'])) ? (int) $this->request->get['visa_id'] : NULL;

        $data['orders'] = array();

        $set = array(
            'sort' => 'o.date_added',
            'order' => 'DESC',
            'filter_visa_id' => $visaID,
            'start' => 0,
            'limit' => 20
        );
        $results = $this->model_sale_visa->getOrders($set);
        foreach ($results as $result) {
            $v = array(
                'order_id' => $result['order_id'],
                'customer_reference' => $result['customer_reference'],
                'transaction_status' => $result['transaction_status']
            );
        }
        $status = $this->model_sale_visa->editVisa($v);

        $this->language->load('sale/visa');
        echo json_encode(array('warning' => ($this->error) ? $this->error['warning'] : FALSE, 'success' => (!$this->error) ? $this->language->get('text_success') : FALSE, 'status' => $status, 'reference' => $result['customer_reference']));
    }

    public function confirm() {

        $this->load->model('sale/visa');
        $visaID = (isset($this->request->get['visa_id'])) ? (int) $this->request->get['visa_id'] : NULL;

        $data['orders'] = array();

        $set = array(
            'sort' => 'o.date_added',
            'order' => 'DESC',
            'filter_visa_id' => $visaID,
            'start' => 0,
            'limit' => 20
        );
        $results = $this->model_sale_visa->getOrders($set);
        foreach ($results as $result) {
            $v = array(
                'order_id' => $result['order_id'],
                'customer_reference' => $result['customer_reference'],
                'transaction_status' => $result['transaction_status']
            );
        }
        $status = $this->model_sale_visa->confirmVisa($v);
        if ($status != false) {
            $history = array(
                'notify' => '0',
                'order_status_id' => $this->config->get('processingkz_order_status_id'),
            );
            $this->model_sale_visa->addOrderHistory($result['order_id'], $history);
			
            $this->language->load('sale/visa');
            echo json_encode(array('warning' => ($this->error) ? $this->error['warning'] : FALSE, 'success' => (!$this->error) ? $this->language->get('text_confirm_success') : FALSE,
                'status' => $status, 'reference' => $result['customer_reference']));
        } else {
            echo json_encode(array('warning' => ($this->error) ? $this->error['warning'] : FALSE, 'success' => (!$this->error) ? $this->language->get('text_error') : FALSE, 'status' => $status, 'reference' => $result['customer_reference']));
        }
    }

    public function cancel() {

        $this->load->model('sale/visa');
        $visaID = (isset($this->request->get['visa_id'])) ? (int) $this->request->get['visa_id'] : NULL;
        $statusElem = (isset($this->request->get['statusElem'])) ? (int) $this->request->get['statusElem'] : NULL;

        $data['orders'] = array();

        $param = array(
            'sort' => 'o.date_added',
            'order' => 'DESC',
            'filter_visa_id' => $visaID,
            'start' => 0,
            'limit' => 20
        );
        $results = $this->model_sale_visa->getOrders($param);
        foreach ($results as $result) {
            $v = array(
                'order_id' => $result['order_id'],
                'customer_reference' => $result['customer_reference'],
                'total' => $result['total'],
                'transaction_status' => $result['transaction_status']
            );
        }

        $status = $this->model_sale_visa->cancelVisa($v);
        if ($status != false) {
            $history = array(
                'notify' => '0',
                'order_status_id' => $this->config->get('processingkz_canceled_status_id'),
            );
            $this->model_sale_visa->addOrderHistory($result['order_id'], $history);

            $this->language->load('sale/visa');
            echo json_encode(array('warning' => ($this->error) ? $this->error['warning'] : FALSE, 'success' => (!$this->error) ? $this->language->get('text_cancel_success') : FALSE,
                'status' => $status, 'reference' => $result['customer_reference']));
        } else {
            echo json_encode(array('warning' => ($this->error) ? $this->error['warning'] : FALSE, 'success' => (!$this->error) ? $this->language->get('text_error') : FALSE,
                'status' => $status, 'reference' => $result['customer_reference']));
        }
    }

    public function info() {
        $this->load->language('sale/visa');
		
        $this->document->setTitle($this->language->get('heading_title2'));

        $data['heading_title'] = $this->language->get('heading_title2');
        $data['button_back'] = $this->language->get('button_back');
		$data['text_verified'] = $this->language->get('text_verified');
		
        $data['text_latest_10_orders'] = $this->language->get('text_latest_10_orders');
        $data['text_total_order'] = $this->language->get('text_total_order');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_visa_big_total'] = $this->language->get('text_visa_big_total');       

        $data['column_visa'] = $this->language->get('column_visa');
        $data['column_order'] = $this->language->get('column_order');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_firstname'] = $this->language->get('column_firstname');
        $data['column_lastname'] = $this->language->get('column_lastname');
        $data['column_action'] = $this->language->get('column_action');

        $data['column_country'] = $this->language->get('column_country');
        $data['column_number'] = $this->language->get('column_number');
        $data['column_verified'] = $this->language->get('column_verified');
        $data['column_ip'] = $this->language->get('column_ip');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/visa', 'token=' . $this->session->data['token'], 'SSL'),            
        );

        //$visaID = (isset ($this->request->get['visa_id'])) ? (int) $this->request->get['visa_id'] : NULL;
        $orderID = (isset($this->request->get['order_id'])) ? $this->request->get['order_id'] : NULL;
        $reference = (isset($this->request->get['customer_reference'])) ? $this->request->get['customer_reference'] : NULL;
        $status = (isset($this->request->get['transaction_status'])) ? $this->request->get['transaction_status'] : NULL;

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title2'),
            'href' => $this->url->link('sale/visa/info', 'token=' . $this->session->data['token'] .
                    '&order_id=' . $orderID .
                    '&customer_reference=' . $reference .
                    '&transaction_status=' . $status, 'SSL')            
        );

        /* $data['token'] = $this->session->data['token'];
        $data['user_token'] = $this->session->data['user_token']; */
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
       
	   $data['cancel'] = $this->url->link('sale/visa', 'token=' . $this->session->data['token'], 'SSL');

        $this->load->model('sale/visa');

        $data['orders'] = array();

        $set = array(
            'sort' => 'o.date_added',
            'order' => 'DESC',
            'filter_order_id' => (int) $orderID,
            'order_id' => $orderID,
            'customer_reference' => $reference,
            'start' => 0,
            'limit' => 20
        );

        if ($status == 'AUTHORISED' or $status == 'PAID' or $status == 'DECLINED' or $status == 'REVERSED') {
            $results = $this->model_sale_visa->getExtended($set);
        }

        $results = $this->model_sale_visa->getOrders($set);

        foreach ($results as $result) {            
            if ($result['ip_address']) {
                $geoip = new ip2location_lite;
                $geoip->setKey($this->config->get('processingkz_apikey'));
                $locations = $geoip->getCity($result['ip_address']);
                $errors = $geoip->getError();
                if (!empty($errors)) {
                    $data['text_geoip'] = 'errors'.'<pre>'.print_r($errors, true).'</pre>';
                } else {
                    $data['text_geoip'] = $locations['countryName'] . ', ' . $locations['cityName'];
                }
            } else {
                $data['text_geoip'] = '';
            }          

            $data['orders'][] = array(
                'visa_id' => $result['visa_id'],
                'order_id' => $result['order_id'],
                'customer' => $result['customer'],
                'status' => $result['status'],
                'customer_reference' => $result['customer_reference'],
                'transaction_status' => $result['transaction_status'],
                'date_added' => date($this->language->get('date_format_short') . ' в ' . $this->language->get('time_format'), strtotime($result['date_added'])),
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'card_country' => $result['card_country'],
                'card_number' => $result['card_number'],
                'verified3d' => $result['verified3d'],
                'visa_big_total' => $this->config->get('processingkz_visa_big_total'),
                'geoip' => $data['text_geoip'],
                'ip_address' => $result['ip_address']
            );
        }
     
        $data['header'] = $this->load->controller('common/header');	
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sale/visa_info', $data));       
    }
}
