<?php
	session_start();
	require_once("CNPMerchantWebServiceClient.php");

	$basket=$_SESSION["session_basket"];
	if (is_null($basket)) {
		$basket=array();
	} else {
		$basket=unserialize($basket);
	}

	if (is_null($_REQUEST["deleteId"]) === false) {
		foreach ($basket as $i => $item) {
			if ($item->merchantsGoodsID == $_REQUEST["deleteId"]) {
				unset($basket[$i]);
				break;
			}
		}
	}

	$errors="";

	if (is_null($_REQUEST["btn_addItem"]) === false) {
		$goodsItem=new GoodsItem();
		$goodsItem->amount=$_REQUEST["txt_ItemAmount"] * 100;
		$goodsItem->currencyCode=$_REQUEST["txt_ItemCurrency"];
		$goodsItem->merchantsGoodsID=guid();
		$goodsItem->nameOfGoods=$_REQUEST["txt_ItemName"];
		$basket[]=$goodsItem;
	}

	$total = 0;
	if(is_array($basket)&&count($basket)>0)
	foreach ($basket as $i => $item) {
		$total+=$item->amount;
	}

	$_SESSION["session_basket"]=serialize($basket);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Shopping</title>
</head>
<body>
	<h1>Shopping</h1>
    <p>This demo web page allows you to fill your shopping cart with goods ready for submission to the services.</p>
    <?php if ($errors != "") { ?>
    	<p><font color='red'><b><?php echo $errors; ?></b></font></p>
    <?php } ?>
    <h2>Current Basket Contents</h2>
    <table border="1">
	    <tr><th>amount</th><th>currencyCode</th><th>nameOfGoods</th><th>&nbsp;</th></tr>
	    <?php 
		if(is_array($basket)&&count($basket)>0)foreach ($basket as $i => $item) {  ?>
			<tr>
				<td><?php echo $item->amount; ?></td>
				<td><?php echo $item->currencyCode; ?></td>
				<td><?php echo $item->nameOfGoods; ?></td>
				<td><a href="shopping.php?deleteId=<?php echo $item->merchantsGoodsID; ?>">Delete</a></td>
			</tr>
		<?php 
		} ?>
    </table>
    <h3>
    	Total Amount: <?php  echo $total; ?>
    	<?php if ($total>0) { ?>
    		<a href="checkout.php">Checkout</a>
    	<?php } ?>
    </h3>
    <h2>Add Item To Basket</h2>
    <form method="post">
	    <table>
		    <tr><td align="right">Name</td><td><input type='text' name="txt_ItemName" value="MyItem"></input></td></tr>
		    <tr><td align="right">Currency</td><td><input type='text' name="txt_ItemCurrency" value="398"></input></td></tr>
		    <tr><td align="right">Amount</td><td><input type='text' name="txt_ItemAmount" value="1000"></input></td></tr>
		    <tr><td align="right">&nbsp;</td><td><input type='submit' name="btn_addItem" value="Add"/></td></tr>
   	</table>
   	</form>
</body>
</html>