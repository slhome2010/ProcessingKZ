<?xml version="1.0" encoding="UTF-8" ?>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ taglib prefix="s" uri="/struts-tags" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<s:i18n name="kz/processing/cnp/consumer_website/package_%{tranDetails.languageCode}">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><s:text name="Transaction Results" /></title>
	</head>
	<body>
		<h1>Transaction Results</h1>
		<s:label label="Merchant ID" value="%{mid}" /><br />
		<s:label label="Customer Reference" value="%{customerReference}" /><br />
		<p><font color='red'><s:property value="%{errorMessage}" /></font></p>
		
		<h2>General</h2>
		<s:label label="Transaction Status" value="%{transactionStatus.transactionStatus}" /><br />
		<s:label label="Auth Code" value="%{transactionStatus.authCode}" /><br />
		<s:label label="Currency Code" value="%{transactionStatus.transactionCurrencyCode}" /><br />
		<s:label label="Amount Requested" value="%{transactionStatus.amountRequested}" /><br />
		<s:label label="Amount Authorised" value="%{transactionStatus.amountAuthorised}" /><br />
		<s:label label="Amount Settled" value="%{transactionStatus.amountSettled}" /><br />
		<s:label label="Amount Refunded" value="%{transactionStatus.amountRefunded}" /><br />
		<s:label label="Merchant Local Date Time" value="%{transactionStatus.merchantLocalDateTime}" /><br />
        <s:label label="Merchant Order Id" value="%{transactionStatus.orderId}" /><br />

        <h2>Purchaser</h2>
        <s:label label="Purchaser Name" value="%{transactionStatus.purchaserName}" /><br />
        <s:label label="Purchaser Email" value="%{transactionStatus.purchaserEmail}" /><br />
        <s:label label="Purchaser Phone" value="%{transactionStatus.purchaserPhone}" /><br />
		
		<h2>Goods</h2>
		<s:iterator value="transactionStatus.goods" status="rowstatus">
			<s:label label="Amount[%{#rowstatus.index}]"  value="%{amount}" /><br />
			<s:label label="Currency Code[%{#rowstatus.index}]" value="%{currencyCode}" /><br />
			<s:label label="Merchant Goods ID[%{#rowstatus.index}]" value="%{merchantsGoodsID}" /><br />
			<s:label label="Name of Goods[%{#rowstatus.index}]" value="%{nameOfGoods}" /><br />
		</s:iterator>
		<h2>Additional Information</h2>
		<s:iterator value="transactionStatus.additionalInformation" status="rowstatus">
		  <s:label label="Key[%{#rowstatus.index}]" value="%{key}" /><br />
		  <s:label label="Value[%{#rowstatus.index}]" value="%{value}" /><br />
		</s:iterator>
		<h2>Completion Operations</h2>
		<s:form>
			<s:hidden key="mid" />
			<s:hidden key="customerReference" />
			<s:textfield label="Override Amount" key="overrideAmount"/>
			<s:submit action="Settle" value="Settle" />
			<s:submit action="Reverse"  value="Reverse" />
		</s:form>
		<h2>Refund Operations</h2>
		<s:form>
			<s:hidden key="mid" />
			<s:hidden key="customerReference" />
			<s:textfield label="Amount" key="overrideAmount"/>
			<s:textfield label="Description" key="description"/>
			<s:textfield label="Password" key="returnURL"/>
			<s:submit action="Refund" value="Refund" />
		</s:form>
		<h1>Start Again</h1>
		<a href='CapturePaymentDetails'>Click Here to Start Again</a>
	</body>
	</html>
</s:i18n>