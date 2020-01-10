<?xml version="1.0" encoding="UTF-8" ?>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ taglib prefix="s" uri="/struts-tags" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<s:head />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Capture Payment Details</title>
</head>
<body>
	<h1>Capture Payment Details</h1>
	<p>This page represents a shopping basket of a merchant. It will allow you to do purchases in any way
	you deem necessary to test the system.</p>
	<p><font color='red'><s:property value="%{errorMessage}" /></font></p>
	<s:form>
		<tr><td colspan='2'><h2>General Information</h2></td></tr>
		<s:textfield label="Merchant ID" key="mid"/>
		<s:textfield label="Terminal ID" key="terminalId"/>
		<s:textfield label="Customer Reference" key="customerReference"/>
	    <s:textfield label="Description" key="description"/> 
	    <s:textfield label="Currency Code" key="currencyCode"/>
	    <s:textfield label="Amount" key="totalAmount"/>
	    <s:textfield label="Order Number" key="orderNum"/>
	    <s:textfield label="Return URL" key="returnURL"/>
	    <s:textfield label="Language Code" key="languageCode"/>
	    <s:textfield label="Purchaser Name" key="purchaserName"/>
        <s:textfield label="Purchaser Email" key="purchaserEmail"/>
        <s:textfield label="Purchaser Phone" key="purchaserPhone"/>
	    <s:textfield label="Merchant Date" key="merchantLocalDateTime"/>

        <tr>
        <td>
            <s:submit action="SubmitPaymentDetails"/>
        </td>
        </tr>
        			    
	   <tr><td colspan='2'><h2>Registration Information</h2></td></tr>
        <s:textfield label="Card ID" key="cardId"/>
        			    
		<tr><td colspan='2'><h2>Billing Adress</h2></td></tr>
		<s:textfield label="Address Line1" key="billingAddress.addressLine1"/>
		<s:textfield label="Address Line2" key="billingAddress.addressLine2"/>
		<s:textfield label="City" key="billingAddress.city"/>
		<s:textfield label="County" key="billingAddress.county"/>
		<s:textfield label="Country" key="billingAddress.country"/>
		<s:textfield label="Zip" key="billingAddress.zip"/>
		
		<tr><td colspan='2'><h2>Goods</h2></td></tr>
		<s:iterator value="goodsList" status="rowstatus">
			<s:textfield label="Amount[%{#rowstatus.index}]" name="goodsList[%{#rowstatus.index}].amount"  value="%{amount}" />
			<s:textfield label="Currency Code[%{#rowstatus.index}]" name="goodsList[%{#rowstatus.index}].currencyCode" value="%{currencyCode}" />
			<s:textfield label="Merchant Goods ID[%{#rowstatus.index}]" name="goodsList[%{#rowstatus.index}].merchantsGoodsID" value="%{merchantsGoodsID}" />
			<s:textfield label="Name of Goods[%{#rowstatus.index}]" name="goodsList[%{#rowstatus.index}].nameOfGoods" value="%{nameOfGoods}" />
			<s:submit action="RemoveGoods" value="Remove Goods %{#rowstatus.index}" />
		</s:iterator>
		
		<s:submit action="AddGoods" value="Add Goods" /> 
		
		<tr><td colspan='2'><h2>Additional Information</h2></td></tr>
		<s:iterator value="additionalInformation" status="rowstatus">
		  <s:textfield label="Key[%{#rowstatus.index}]" name="additionalInformation[%{#rowstatus.index}].key"  value="%{key}" />
		  <s:textfield label="Value[%{#rowstatus.index}]" name="additionalInformation[%{#rowstatus.index}].value" value="%{value}" />
		  <s:submit action="RemoveAdditionalInfo" value="Remove Additional %{#rowstatus.index}" />
		</s:iterator>
		
		<s:submit action="AddAdditionalInfo" value="Add Additional Info" />
		
	    <s:submit action="SubmitPaymentDetails"/>
	</s:form>
</body>
</html>
