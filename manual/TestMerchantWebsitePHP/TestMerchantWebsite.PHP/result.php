<?php
	session_start();

	require_once("CNPMerchantWebServiceClient.php");

	$rrn=$_SESSION["customerReference"];

	$errors="";

	$client=new CNPMerchantWebServiceClient();

	$param = new completeTransaction();
	$param->merchantId = "000000000000015";
  	$param->referenceNr = $rrn;
  	$param->overrideAmount = NULL;

	if (is_null($_REQUEST["btn_RequestPayment"]) === false) {
  		$param->transactionSuccess = true;
		if ($client->completeTransaction($param) == false) {
			$errors="Settlement request failed.";
		}
	}
	if (is_null($_REQUEST["btn_RequestReversal"]) === false) {
		$param->transactionSuccess = false;
		if ($client->completeTransaction($param) == false) {
			$errors="Reversal request failed.";
		}
	}
	$params = new getTransactionStatus();
	$params->merchantId = "000000000000015";
	$params->referenceNr = $rrn;
	$tranResult=$client->getTransactionStatus($params);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Trasaction Result</title>
</head>
<body>
    <h1>The status of the transaction was ...</h1>
	<?php if ($tranResult==null) {  ?>
        	<p>Transaction could not be located.</p>
    <?php } else {  ?>
        	<p><?php echo $tranResult->return->transactionStatus; ?></p>
    <?php }  ?>

    <?php if ($errors != "") { ?>
    	<p><font color='red'><b><?php echo $errors; ?></b></font></p>
    <?php } ?>

    &nbsp;<br />
	<a href="result.php?btn_RequestPayment=now">Request Payment</a><br />
	<a href="result.php?btn_RequestReversal=now">Request Cancellation</a><br />
</body>
</html>