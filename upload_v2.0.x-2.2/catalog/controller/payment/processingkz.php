<?php
require_once(DIR_SYSTEM . 'library/processingkz/CNPMerchantWebServiceClient.php');

class ControllerPaymentProcessingkz extends Controller
{

    public function index()
    {

        $extension = version_compare(VERSION, '2.3.0', '>=') ? "extension/" : "";

        if (version_compare(VERSION, '2.2.0', '>=')) {
            $this->load->language($extension . 'payment/processingkz');
            $ssl = true;
        } else {
            $this->load->language('payment/processingkz');
            $ssl = 'SSL';
        }

        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['button_back'] = $this->language->get('button_back');

        $data['action'] = HTTPS_SERVER . 'index.php?route=' . $extension . 'payment/processingkz/processing';

        $data['text_note'] = $this->language->get('text_note');

        $id = 'payment';

        if (version_compare(VERSION, '2.2.0', '>=')) {
            return $this->load->view($extension . 'payment/processingkz', $data);
        } else {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/processingkz.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/payment/processingkz.tpl', $data);
            } else {
                return $this->load->view('default/template/payment/processingkz.tpl', $data);
            }
        }
    }

    public function processing()
    {

        $extension = version_compare(VERSION, '2.3.0', '>=') ? "extension/" : "";
        $m_extension = version_compare(VERSION, '2.3.0', '>=') ? "extension_" : "";

        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $description = $this->config->get('config_meta_description');
        $mrId = $this->config->get('processingkz_shop_id');

        $basket = array();
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $goodsItem = new GoodsItem();
            $goodsItem->merchantsGoodsID = $product['product_id'];
            $goodsItem->nameOfGoods = $product['name'];
            $goodsItem->currencyCode = "398";
            $kzt_item = $this->currency->convert($product['price'], $order_info['currency_code'], 'KZT');
            $goodsItem->amount = $this->currency->format($kzt_item, $order_info['currency_code'], $order_info['currency_value'], false) * 100;
            $basket[] = $goodsItem;
        }

        // добавим доставкуk
        if (isset($this->session->data['shipping_method'])) {
            $goodsItem = new GoodsItem();
            $goodsItem->nameOfGoods = 'Доставка: ' . $this->session->data['shipping_method']['title'];
            $goodsItem->currencyCode = "398";
            $kzt_shipping = $this->currency->convert($this->session->data['shipping_method']['cost'], $order_info['currency_code'], 'KZT');
            $goodsItem->amount = $this->currency->format($kzt_shipping, $order_info['currency_code'], $order_info['currency_value'], false) * 100;
            $basket[] = $goodsItem;
        }
        // если надо добавить комиссию
        if ($this->config->get('processingkz_comission_status_id') == 'true') {
            // соберем в кучу все скидки и наценки
            $total_data = array();
            $total = 0;
            $taxes = $this->cart->getTaxes();

            $this->load->model('extension/extension');

            $sort_order = array();
            $results = $this->model_extension_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);


            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('total/' . $result['code']);
                    if ('model_total_' . $result['code'] != 'model_total_comission') {
                        $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                    }
                }
            }

            $sort_order = array();

            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $total_data);

            // добавим комиссию
            $comission = $total;
            $this->load->model($extension . 'total/comission');
            $model = 'model_' . $m_extension . 'total_comission';
            $this->$model->getTotal($total_data, $total, $taxes);

            $goodsItem = new GoodsItem();
            $goodsItem->nameOfGoods = 'Комиссия платежной системы:';
            $goodsItem->currencyCode = "398";
            $kzt_comission = $this->currency->convert($total - $comission, $order_info['currency_code'], 'KZT');
            $goodsItem->amount = $this->currency->format($kzt_comission, $order_info['currency_code'], $order_info['currency_value'], false) * 100;
            $basket[] = $goodsItem;
        }

        $client = new CNPMerchantWebServiceClient();
        $transactionDetails = new TransactionDetails();
        $transactionDetails->merchantId = $mrId;
        /* $transactionDetails->terminalId = $this->config->get('processingkz_terminal_id'); */
        $kzt_order_total = $this->currency->convert($order_info['total'], $order_info['currency_code'], 'KZT');
        $transactionDetails->totalAmount = $this->currency->format($kzt_order_total, $order_info['currency_code'], $order_info['currency_value'], FALSE) * 100;
        $transactionDetails->currencyCode = "398";
        $transactionDetails->description = $description;
        $transactionDetails->returnURL = HTTPS_SERVER . 'index.php?route=' . $extension . 'payment/processingkz/success&orderID=' . $this->session->data['order_id'] . '&sessionID=';
        $transactionDetails->goodsList = $basket;
        $transactionDetails->languageCode = "ru";
        $transactionDetails->merchantLocalDateTime = date("d.m.Y H:i:s");
        $transactionDetails->orderId = $this->session->data['order_id'];
        /* $transactionDetails->purchaserName=$order_info['firstname']; */
        $transactionDetails->purchaserEmail = $order_info['email'];
        $transactionDetails->purchaserPhone = $order_info['telephone'];

        $st = new startTransaction();
        $st->transaction = $transactionDetails;
        $startTransactionResult = $client->startTransaction($st);
        $rrn = $startTransactionResult->return->customerReference;

        if ($startTransactionResult->return->success == true) {
            $params = new getTransactionStatus();
            $params->merchantId = $mrId;
            $params->referenceNr = $rrn;
            $tranResult = $client->getTransactionStatus($params);
            $ts = $tranResult->return->transactionStatus;

            $set = array(
                'order_id' => $this->session->data['order_id'],
                'customer_reference' => $rrn,
                'transaction_status' => $ts,
                'total' => $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], FALSE)
            );

            $this->load->model($extension . 'payment/processingkz');
            $model = 'model_' . $m_extension . 'payment_processingkz';
            $vs = $this->$model->addVisa($set);
            $vso = $this->$model->addVisaToOrder($vs, $this->session->data['order_id']);

            if ($ts == "PENDING_CUSTOMER_INPUT") {
                $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('processingkz_check_status_id'));
                $this->cart->clear();
            }
            header("Location: " . $startTransactionResult->return->redirectURL);
        } else {
            $this->response->redirect(HTTP_SERVER . 'index.php?route=error/not_operation&error=' . $startTransactionResult->return->errorDescription);
        }
    }

    public function success()
    {

        $extension = version_compare(VERSION, '2.3.0', '>=') ? "extension/" : "";
        $m_extension = version_compare(VERSION, '2.3.0', '>=') ? "extension_" : "";

        $mrId = $this->config->get('processingkz_shop_id');
        $rrn = $_GET["sessionID"];
        $orderId = $_GET["orderID"];
        $client = new CNPMerchantWebServiceClient();

        $params = new getTransactionStatus();
        $params->merchantId = $mrId;
        $params->referenceNr = $rrn;
        $tranResult = $client->getTransactionStatus($params);
        $ts = $tranResult->return->transactionStatus;

        $set = array(
            'order_id' => $orderId,
            'customer_reference' => $rrn,
            'transaction_status' => $ts,
        );

        $this->load->model($extension . 'payment/processingkz');
        $model = 'model_' . $m_extension . 'payment_processingkz';
        $this->$model->editVisa($set);
        $this->load->model('checkout/order');

        if ($ts == "AUTHORISED" || $ts == "PAID") {
            if ($ts == "AUTHORISED") {
                //     $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('processingkz_authorised_status_id'));
            } else {
                //     $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('processingkz_paid_status_id'));
            }
            $this->response->redirect(HTTPS_SERVER . 'index.php?route=checkout/success');
        } else {
            // $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('processingkz_declined_status_id'));
            $this->response->redirect(HTTP_SERVER . 'index.php?route=error/not_operation&error=' . ($ts == 'DECLINED' ? 'Банк отклонил карту. Свяжитесь с банком.' : $ts));
        }
    }
}

class ControllerExtensionPaymentProcessingkz extends ControllerPaymentProcessingkz
{ }
