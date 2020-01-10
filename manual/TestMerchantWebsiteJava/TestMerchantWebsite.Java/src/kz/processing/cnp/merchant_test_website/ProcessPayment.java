package kz.processing.cnp.merchant_test_website;

import java.io.IOException;
import java.io.InputStream;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.Properties;

import org.apache.axis2.transport.http.HTTPConstants;
import org.apache.log4j.Logger;
import org.apache.struts2.ServletActionContext;

import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.AdditionalInformation;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.Address;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.CompleteRegistration;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.CompleteTransaction;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.GetRegistrationStatus;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.GetTransactionStatus;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.GoodsItem;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.RefundTransaction;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.RegistrationDetails;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.StartRegistration;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.StartRegistrationResponse;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.StartTransaction;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.StartTransactionResponse;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.StoredRegistrationStatus;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.StoredTransactionStatus;
import kz.processing.cnp.merchant_web_services_client.MerchantWebServiceStub.TransactionDetails;

import com.opensymphony.xwork2.ActionSupport;

/**
 * This requests
 */
public class ProcessPayment extends ActionSupport {
    /** Generated GUID */
	private static final long serialVersionUID = 290016339072091593L;
	/** The key in the configuration file for finding the merchant web services url */
	private static final String MERCHANT_WEB_SERVICES_URL_KEY="CNP_URL";
	/** Logger in use */
	private static final Logger log = Logger.getLogger(ProcessPayment.class);
	/** The web services in use */
	private MerchantWebServiceStub webServices;
	
	/** Any error message to display */
	private String errorMessage;
	/** The billing address */
	private Address billingAddress;
	/** The currency code */
	private Integer currencyCode;
	/** The Retrieval Reference Number that is provided to the customer */
	private String customerReference;
	/** Any sort of description to give the system */
	private String description;
	/** The list of goods to be associated */
	private ArrayList<GoodsItem> goodsList=new ArrayList<GoodsItem>();
	/** Any additional information to submit with the transaction */
	private ArrayList<AdditionalInformation> additionalInformation=new ArrayList<AdditionalInformation>();
	/** The test merchant ID */
	private String mid;
	/** The test response URL */
	private String returnURL;
	/** The test amount */
	private String totalAmount;
	/** The status of the transaction */
	private StoredTransactionStatus transactionStatus;
	/** Language Code */
	private String languageCode;
	/** The terminal ID to send through */
	private String terminalId;
	/** The amount to override complete with */
	private String overrideAmount;
	
    /** The Purchaser name  **/
	private String purchaserName;
	/** The merchant date and time **/
	private String merchantLocalDateTime;
	
	private String purchaserEmail;
	private String purchaserPhone;	
	private String orderNum;
	
	private String merchantId;
	private String keyStore;
	private String keyStorePassword;
	private String trustKeyStore;
	private String trustKeyStorePassword;
	
	private String cardId;
	
	private StoredRegistrationStatus registrationStatus;
	
	/**
	 * Instantiate the payments linked to the test merchant web services 
	 */
	public ProcessPayment() throws IOException {
		/* Load the config file */
		Properties props=new Properties();
		InputStream propsInputStream=ProcessPayment.class.getResourceAsStream("ProcessPayment.properties");
		props.load(propsInputStream);
		propsInputStream.close();
		
		
		/* Now load the web services */
		webServices=new MerchantWebServiceStub(props.getProperty(MERCHANT_WEB_SERVICES_URL_KEY));
		
		webServices._getServiceClient().getOptions().setProperty(HTTPConstants.CHUNKED, false);
		
		merchantId = props.getProperty("MerchantId");
		keyStore = props.getProperty("keyStore");
		keyStorePassword = props.getProperty("keyStorePassword");
		trustKeyStore = props.getProperty("trustKeyStore");
		trustKeyStorePassword = props.getProperty("trustKeyStorePassword");
		
	}
	
	@Override
	public String execute() throws Exception {
		/* Start Demo Code To Pre-Populate a Request */
		errorMessage="";
		languageCode="ru";
		billingAddress=new Address();
		billingAddress.setAddressLine1("Line1");
		billingAddress.setAddressLine2("Line2");
		billingAddress.setCity("City");
		billingAddress.setCountry("Country");
		billingAddress.setCounty("County");
		billingAddress.setZip("Zip");
		currencyCode=398;
		customerReference=String.valueOf(new Date().getTime());
		if (customerReference.length()>12) customerReference=customerReference.substring(0,12);
		while (customerReference.length()<12) customerReference="0"+customerReference;
		description="A Transaction Description";
		goodsList=new ArrayList<MerchantWebServiceStub.GoodsItem>(2);
		GoodsItem goodsListItem=new GoodsItem();
		goodsListItem.setAmount("1000");
		goodsListItem.setCurrencyCode(398);
		goodsListItem.setMerchantsGoodsID("VW_Sierra");
		goodsListItem.setNameOfGoods("Ford Sierra 1.2 TDI Pink");
		goodsList.add(goodsListItem);
		goodsListItem=new GoodsItem();
		goodsListItem.setAmount("2000");
		goodsListItem.setCurrencyCode(398);
		goodsListItem.setMerchantsGoodsID("CCPringle");
		goodsListItem.setNameOfGoods("Cheese and Chive Pringles");
		goodsList.add(goodsListItem);
		additionalInformation=new ArrayList<AdditionalInformation>(3);
		AdditionalInformation additionalInformationItem=new AdditionalInformation();
		additionalInformationItem.setKey("MyKey1");
		additionalInformationItem.setValue("MyVal1");
		additionalInformation.add(additionalInformationItem);
		additionalInformationItem=new AdditionalInformation();
		additionalInformationItem.setKey("MyKey2");
		additionalInformationItem.setValue("MyVal2");
		additionalInformation.add(additionalInformationItem);
		additionalInformationItem=new AdditionalInformation();
		additionalInformationItem.setKey("MyKey3");
		additionalInformationItem.setValue("MyVal3");
		additionalInformation.add(additionalInformationItem);
		
		mid= merchantId;
		terminalId="<NULL>";
		totalAmount="3000";
		returnURL=ServletActionContext.getRequest().getRequestURL().toString();
					
		
		int pos=returnURL.lastIndexOf("/");
		
		returnURL=returnURL.substring(0, pos+1)+"ShowResult"+ "?mid="+mid+"&customerReference="+customerReference;
					
		purchaserName = "IVANOV IVAN";
		purchaserEmail = "purchaser@processing.kz";
		purchaserPhone = "+7 (727) 244 04 77";
		merchantLocalDateTime = new SimpleDateFormat("dd.MM.yyyy HH:mm:ss").format(new Date());
		
		orderNum = customerReference.substring(6, 12);
		
		cardId = "<NULL>";
		
		/* End demo code */
        return SUCCESS;
    }
	
	/**
	 * Part of the demo code used to setup the basket
	 */
	public String addGoods() {
		/* Start demo code */
		GoodsItem goodsListItem=new GoodsItem();
		goodsListItem.setAmount("1000");
		goodsListItem.setCurrencyCode(826);
		goodsListItem.setMerchantsGoodsID("TBD");
		goodsListItem.setNameOfGoods("TBD");
		goodsList.add(goodsListItem);
		return SUCCESS;
		/* End demo code */
	}
	/**
	 * Part of the demo code used to setup the basket
	 */
	public String addAdditionalInfo() {
		/* Start demo code */
		AdditionalInformation additionalInformationItem=new AdditionalInformation();
		additionalInformationItem.setKey("NewKey");
		additionalInformationItem.setValue("NewValue");
		additionalInformation.add(additionalInformationItem);
		return SUCCESS;
		/* End demo code */
	}
	/**
	 * Part of the demo code used to setup the basket
	 */
	public String removeGoods() {
		/* Start demo code */
		goodsList.remove(Integer.parseInt(ServletActionContext.getRequest().getParameter("action:RemoveGoods").substring(13))); 
		return SUCCESS;
		/* End demo code */
	}
	/**
	 * Part of the demo code used to setup the basket
	 */
	public String removeAdditionalInfo() {
		/* Start demo code */
		additionalInformation.remove(Integer.parseInt(ServletActionContext.getRequest().getParameter("action:RemoveAdditionalInfo").substring(18))); 
		return SUCCESS;
		/* End demo code */
	}
	
	private void SetSSL(){
		
		if(keyStore != null && keyStore != ""){

			System.getProperties().remove("javax.net.ssl.keyStore");
			System.getProperties().remove("javax.net.ssl.keyStorePassword");			
			System.getProperties().remove("javax.net.ssl.trustStore");
			System.getProperties().remove("javax.net.ssl.trustStorePassword");	

			System.setProperty("javax.net.ssl.keyStore", keyStore);
			System.setProperty("javax.net.ssl.keyStorePassword", keyStorePassword);			
			System.setProperty("javax.net.ssl.trustStore", trustKeyStore);
			System.setProperty("javax.net.ssl.trustStorePassword", trustKeyStorePassword);
			
		}
	}
	
	/**
	 * This method will submit a payment to the remote system 
	 * @return The return is not in fact used, rather we do a server side redirect
	 */
	StartTransaction st=new StartTransaction();
	public String requestPayment() {
		TransactionDetails td=new TransactionDetails();
		td.setTerminalID(null);
		if (terminalId!=null) {
			if (!terminalId.equals("<NULL>")) {
				td.setTerminalID(terminalId);
			}
		}
		td.setBillingAddress(billingAddress);
		td.setCurrencyCode(currencyCode);
		td.setCustomerReference(customerReference); 
		td.setDescription(description);
		td.setGoodsList(goodsList.toArray(new GoodsItem[0]));
		td.setMerchantAdditionalInformationList(additionalInformation.toArray(new AdditionalInformation[0]));
		td.setMerchantId(mid); 
		td.setTotalAmount(totalAmount);
		td.setReturnURL(returnURL);
		td.setLanguageCode(languageCode);
		
		td.setPurchaserName(purchaserName);
		td.setMerchantLocalDateTime(merchantLocalDateTime);
		
		td.setPurchaserEmail(purchaserEmail);
		td.setPurchaserPhone(purchaserPhone);
		
		td.setOrderId(orderNum);
		
		if (cardId!=null) {
			if (!cardId.equals("<NULL>")) {
				td.setCardId(cardId);
			}
		}
		
		st.setTransaction(td);		
		
		
		try {
			
			// use client ssl certificate
			SetSSL();
			
			StartTransactionResponse str = webServices.startTransaction(st);
			if (str.get_return().getSuccess()) {
				ServletActionContext.getResponse().sendRedirect(str.get_return().getRedirectURL());
			} else {
				setErrorMessage("Unsuccessful trying to start transaction: "+str.get_return().getErrorDescription());
			}
		} catch (Exception e) {
			setErrorMessage(e.getMessage());
			log.error(e.getMessage(), e);
		}
		
		return SUCCESS;
	}

	/**
	 * Shows the payment result
	 * @return Success
	 */
	public String viewPaymentResult() {
		if (customerReference!=null&&mid!=null) {
			try {
				SetSSL();
				
				GetTransactionStatus gts=new GetTransactionStatus();
				gts.setMerchantId(mid);
				gts.setReferenceNr(customerReference);
				transactionStatus=webServices.getTransactionStatus(gts).get_return();
				if (transactionStatus==null){
					setErrorMessage("Couldn't find transaction for this MID and customer reference");
				}
				overrideAmount="<NULL>";
				description="<NULL>";
				returnURL="<NULL>";
			} catch (Exception e) {
				setErrorMessage(e.getMessage());
				log.error(e.getMessage(), e);
			}
		} else { 
			setErrorMessage("Must supply both customer reference and MID");
		}
		return SUCCESS;
	}	
	
	/**
	 * Send the transaciton for payment
	 */
	public String settle() {
		return complete(true);
	}
	
	/**
	 * Complete the transaction
	 * @param b was it successful
	 * @return SUCCESS
	 */
	private String complete(boolean b) {

		SetSSL();
		
		if (customerReference!=null&&mid!=null) {
			try {

				CompleteTransaction completeTransaction=new CompleteTransaction();
				completeTransaction.setMerchantId(mid);
				if (overrideAmount!=null) {
					if (!overrideAmount.equals("<NULL>")) {
						completeTransaction.setOverrideAmount(overrideAmount);
					}
				}
				completeTransaction.setReferenceNr(customerReference);
				completeTransaction.setTransactionSuccess(b);
				
				if (!webServices.completeTransaction(completeTransaction).get_return()) {
					setErrorMessage("Unable to complete this transaction");
				}
				GetTransactionStatus gts=new GetTransactionStatus();
				gts.setMerchantId(mid);
				gts.setReferenceNr(customerReference);
				transactionStatus=webServices.getTransactionStatus(gts).get_return();
				if (transactionStatus==null){
					setErrorMessage("Couldn't find transaction for this MID and customer reference");
				}
			} catch (Exception e) {
				setErrorMessage(e.getMessage());
				log.error(e.getMessage(), e);
			}
		} else { 
			setErrorMessage("Must supply both customer reference and MID");
		}
		return SUCCESS;
	}

	/**
	 * Reverse the transaction
	 */
	public String reverse() {
		return complete(false);
	}
	
	/**
	 * Refund the transaction
	 */
	public String refund() {
		SetSSL();
		
		if (customerReference!=null&&mid!=null) {
			try {
				RefundTransaction refundTransaction=new RefundTransaction();
				refundTransaction.setMerchantId(mid);
				if (overrideAmount!=null) {
					if (!overrideAmount.equals("<NULL>")) {
						refundTransaction.setRefundAmount(overrideAmount);
					}
				}
				refundTransaction.setDescription(description);
				if (returnURL!=null) {
					if (!returnURL.equals("<NULL>")) {
						refundTransaction.setPassword(returnURL);
					}
				}
				refundTransaction.setReferenceNr(customerReference);
				if (!webServices.refundTransaction(refundTransaction).get_return()) {
					setErrorMessage("Unable to refund this transaction");
				}
				GetTransactionStatus gts=new GetTransactionStatus();
				gts.setMerchantId(mid);
				gts.setReferenceNr(customerReference);
				transactionStatus=webServices.getTransactionStatus(gts).get_return();
				if (transactionStatus==null){
					setErrorMessage("Couldn't find transaction for this MID and customer reference");
				}
			} catch (Exception e) {
				setErrorMessage(e.getMessage());
				log.error(e.getMessage(), e);
			}
		} else { 
			setErrorMessage("Must supply both customer reference and MID");
		}
		return SUCCESS;
	}
	
	
	public String requestRegistration(){
		
		RegistrationDetails td=new RegistrationDetails();
		
		td.setTerminalID(null);
		if (terminalId!=null) {
			if (!terminalId.equals("<NULL>")) {
				td.setTerminalID(terminalId);
			}
		}

		td.setCurrencyCode(currencyCode);
		td.setCustomerReference(customerReference); 
		td.setMerchantId(mid); 
		td.setTotalAmount(totalAmount);
		td.setReturnURL(returnURL);
		
		td.setLanguageCode(languageCode);
		
		td.setPurchaserName(purchaserName);
		td.setPurchaserEmail(purchaserEmail);
		td.setPurchaserPhone(purchaserPhone);
		td.setMerchantLocalDateTime(merchantLocalDateTime);

		StartRegistration sr = new StartRegistration();
		
		sr.setRegistration(td);
		
		try {
			SetSSL();
			
			StartRegistrationResponse str = webServices.startRegistration(sr);
			if (str.get_return().getSuccess()) {
				ServletActionContext.getResponse().sendRedirect(str.get_return().getRedirectURL());
			} else {
				setErrorMessage("Unsuccessful trying to register: "+str.get_return().getErrorDescription());
			}
		} catch (Exception e) {
			setErrorMessage(e.getMessage());
			log.error(e.getMessage(), e);
		}
		
		return SUCCESS;
	}
	
	
	public String showRegistrationResult() {
		if (customerReference!=null&&mid!=null) {
			try {
				SetSSL();
				
				GetRegistrationStatus gts=new GetRegistrationStatus();
				gts.setMerchantId(mid);
				gts.setReferenceNr(customerReference);
				registrationStatus=webServices.getRegistrationStatus(gts).get_return();
				if (registrationStatus==null){
					setErrorMessage("Couldn't find transaction for this MID and customer reference");
				}
				overrideAmount="<NULL>";
				description="<NULL>";
				returnURL="<NULL>";
			} catch (Exception e) {
				setErrorMessage(e.getMessage());
				log.error(e.getMessage(), e);
			}
		} else { 
			setErrorMessage("Must supply both customer reference and MID");
		}
		return SUCCESS;
	}

	public String registerSuccess(){
		return completeRegistration(true);
	}
	public String registerFail(){
		return completeRegistration(false);
	}
	
	private String completeRegistration(boolean b) {

		SetSSL();
		
		if (customerReference!=null&&mid!=null) {
			try {
				
				CompleteRegistration completeRegistration=new CompleteRegistration();
				completeRegistration.setMerchantId(mid);
				completeRegistration.setReferenceNr(customerReference);
				completeRegistration.setRegistrationSuccess(b);
				
				if (!webServices.completeRegistration(completeRegistration).get_return()) {
					setErrorMessage("Unable to complete this transaction");
				}
				
				GetRegistrationStatus gts=new GetRegistrationStatus();
				gts.setMerchantId(mid);
				gts.setReferenceNr(customerReference);
				registrationStatus=webServices.getRegistrationStatus(gts).get_return();
				if (registrationStatus==null){
					setErrorMessage("Couldn't find transaction for this MID and customer reference");
				}
				overrideAmount="<NULL>";
				description="<NULL>";
				returnURL="<NULL>";

			} catch (Exception e) {
				setErrorMessage(e.getMessage());
				log.error(e.getMessage(), e);
			}
		} else { 
			setErrorMessage("Must supply both customer reference and MID");
		}
		return SUCCESS;
	}

	
	/**
	 * @param errorMessage the errorMessage to set
	 */
	public void setErrorMessage(String errorMessage) {
		this.errorMessage = errorMessage;
	}

	/**
	 * @return the errorMessage
	 */
	public String getErrorMessage() {
		return errorMessage;
	}

	/**
	 * @return the billingAddress
	 */
	public Address getBillingAddress() {
		return billingAddress;
	}

	/**
	 * @param billingAddress the billingAddress to set
	 */
	public void setBillingAddress(Address billingAddress) {
		this.billingAddress = billingAddress;
	}

	/**
	 * @return the currencyCode
	 */
	public Integer getCurrencyCode() {
		return currencyCode;
	}

	/**
	 * @param currencyCode the currencyCode to set
	 */
	public void setCurrencyCode(Integer currencyCode) {
		this.currencyCode = currencyCode;
	}

	/**
	 * @return the customerReference
	 */
	public String getCustomerReference() {
		return customerReference;
	}

	/**
	 * @param customerReference the customerReference to set
	 */
	public void setCustomerReference(String customerReference) {
		this.customerReference = customerReference;
	}

	/**
	 * @return the description
	 */
	public String getDescription() {
		return description;
	}

	/**
	 * @param description the description to set
	 */
	public void setDescription(String description) {
		this.description = description;
	}


	/**
	 * @return the returnURL
	 */
	public String getReturnURL() {
		return returnURL;
	}

	/**
	 * @param returnURL the returnURL to set
	 */
	public void setReturnURL(String returnURL) {
		this.returnURL = returnURL;
	}

	/**
	 * @return the totalAmount
	 */
	public String getTotalAmount() {
		return totalAmount;
	}

	/**
	 * @param totalAmount the totalAmount to set
	 */
	public void setTotalAmount(String totalAmount) {
		this.totalAmount = totalAmount;
	}

	/**
	 * @return the mid
	 */
	public String getMid() {
		return mid;
	}

	/**
	 * @param mid the mid to set
	 */
	public void setMid(String mid) {
		this.mid = mid;
	}

	/**
	 * @return the transactionStatus
	 */
	public StoredTransactionStatus getTransactionStatus() {
		return transactionStatus;
	}

	/**
	 * @param transactionStatus the transactionStatus to set
	 */
	public void setTransactionStatus(StoredTransactionStatus transactionStatus) {
		this.transactionStatus = transactionStatus;
	}

	/**
	 * @return the goodsList
	 */
	public ArrayList<GoodsItem> getGoodsList() {
		return goodsList;
	}

	/**
	 * @param goodsList the goodsList to set
	 */
	public void setGoodsList(ArrayList<GoodsItem> goodsList) {
		this.goodsList = goodsList;
	}

	/**
	 * @return the additionalInformation
	 */
	public ArrayList<AdditionalInformation> getAdditionalInformation() {
		return additionalInformation;
	}

	/**
	 * @param additionalInformation the additionalInformation to set
	 */
	public void setAdditionalInformation(
			ArrayList<AdditionalInformation> additionalInformation) {
		this.additionalInformation = additionalInformation;
	}
	
	/**
	 * @return the language code
	 */
	public String getLanguageCode() {
		return languageCode;
	}

	/**
	 * @param languageCode the languageCode to set
	 */
	public void setLanguageCode(String languageCode) {
		this.languageCode = languageCode;
	}

	/**
	 * @param terminalId the terminalId to set
	 */
	public void setTerminalId(String terminalId) {
		this.terminalId = terminalId;
	}

	/**
	 * @return the terminalId
	 */
	public String getTerminalId() {
		return terminalId;
	}

	/**
	 * @return the overrideAmount
	 */
	public final String getOverrideAmount() {
		return overrideAmount;
	}

	/**
	 * @param overrideAmount the overrideAmount to set
	 */
	public final void setOverrideAmount(String overrideAmount) {
		this.overrideAmount = overrideAmount;
	}

	/**
	 * @return the purchaserName
	 */
	public String getPurchaserName() {
		return purchaserName;
	}

	/**
	 * @param purchaserName the purchaserName to set
	 */
	public void setPurchaserName(String purchaserName) {
		this.purchaserName = purchaserName;
	}

	/**
	 * @return the merchantLocalDateTime
	 */
	public String getMerchantLocalDateTime() {
		return merchantLocalDateTime;
	}

	/**
	 * @param merchantLocalDateTime the merchantLocalDateTime to set
	 */
	public void setMerchantLocalDateTime(String merchantLocalDateTime) {
		this.merchantLocalDateTime = merchantLocalDateTime;
	}

	/**
	 * @return the purchaserEmail
	 */
	public String getPurchaserEmail() {
		return purchaserEmail;
	}

	/**
	 * @param purchaserEmail the purchaserEmail to set
	 */
	public void setPurchaserEmail(String purchaserEmail) {
		this.purchaserEmail = purchaserEmail;
	}

	/**
	 * @return the purchaserPhone
	 */
	public String getPurchaserPhone() {
		return purchaserPhone;
	}

	/**
	 * @param purchaserPhone the purchaserPhone to set
	 */
	public void setPurchaserPhone(String purchaserPhone) {
		this.purchaserPhone = purchaserPhone;
	}

	/**
	 * @return the orderNum
	 */
	public String getOrderNum() {
		return orderNum;
	}

	/**
	 * @param orderNum the orderNum to set
	 */
	public void setOrderNum(String orderNum) {
		this.orderNum = orderNum;
	}

	/**
	 * @return the registrationStatus
	 */
	public StoredRegistrationStatus getRegistrationStatus() {
		return registrationStatus;
	}

	/**
	 * @param registrationStatus the registrationStatus to set
	 */
	public void setRegistrationStatus(StoredRegistrationStatus registrationStatus) {
		this.registrationStatus = registrationStatus;
	}

	/**
	 * @return the cardId
	 */
	public String getCardId() {
		return cardId;
	}

	/**
	 * @param cardId the cardId to set
	 */
	public void setCardId(String cardId) {
		this.cardId = cardId;
	}
}
