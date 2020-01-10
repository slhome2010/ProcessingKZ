<?php
	session_start();

	require_once("CNPMerchantWebServiceClient.php");

	$basket=unserialize($_SESSION["session_basket"]);

	$errors="";
	$total = 0;

	if(is_array($basket)&&count($basket)>0){
		foreach ($basket as $i => $item) {
			$total+=$item->amount;
		}
	}

	if (is_null($_REQUEST["btn_StartCardPayment"]) === false) {
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}

		$client=new CNPMerchantWebServiceClient();
		$transactionDetails=new TransactionDetails();
		$transactionDetails->merchantId="000000000000015";
		$transactionDetails->terminalId="TEST TID";
		$transactionDetails->totalAmount=$total;//*100;
		$transactionDetails->currencyCode=$basket[0]->currencyCode;
		$transactionDetails->description="My first transaction";
		$transactionDetails->returnURL=str_replace("checkout.php", "result.php", $pageURL);
		$transactionDetails->goodsList=$basket;
		$transactionDetails->languageCode="ru";
		$transactionDetails->merchantLocalDateTime=date("d.m.Y H:i:s");
		$transactionDetails->orderId= rand(1,10000);		
		$transactionDetails->purchaserName="IVANOV IVAN";		
		$transactionDetails->purchaserEmail="purchaser@processing.kz";		

		$st = new startTransaction();
		$st->transaction = $transactionDetails;
		$startTransactionResult=$client->startTransaction($st);

		if ($startTransactionResult->return->success == true) {
			$_SESSION["customerReference"]=$startTransactionResult->return->customerReference;
			header("Location: " . $startTransactionResult->return->redirectURL);
		} else {
			$errors='Error: ' . $startTransactionResult->return->errorDescription;
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Checkout</title>
</head>
<body>
	<h1>Review Your Purchase</h1>
    <?php if ($errors != "") { ?>
    	<p><font color='red'><b><?php echo $errors; ?></b></font></p>
    <?php } ?>

    <h3>
    	Total Amount: <?php  echo $total; ?>
    	<?php if ($total>0) { ?>
    		<a href="checkout.php?btn_StartCardPayment=now">Start Card Payment</a>
    	<?php } ?>
    </h3>
</body>
</html>