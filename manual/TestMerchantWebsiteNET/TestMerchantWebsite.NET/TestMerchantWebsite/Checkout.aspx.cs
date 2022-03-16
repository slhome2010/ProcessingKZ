using System;
using System.Data;
using System.Configuration;
using System.Collections;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;

using TestMerchantWebsite.CNPMerchantWebService;
using TestMerchantWebsite;

public partial class Checkout : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {

    }

    /// <summary>
    /// Example starting the transaction with the demo basket
    /// </summary>
    protected void btn_StartCardPayment_Click(object sender, EventArgs e)
    {
        CNPMerchantWebService merchantWS = new CNPMerchantWebService();
        TransactionDetails td=new TransactionDetails();
        td.currencyCode = Basket.getBasket()[0].currencyCode;
        td.description = "My first transaction";
        td.goodsList = Basket.getBasket().ToArray();
        td.merchantId = "000000000000001";
        td.terminalID = "TEST TID";
        td.returnURL = Request.Url.OriginalString.Replace("Checkout.aspx", "Result.aspx");
        td.totalAmount = Basket.getTotalAmount().ToString();
        td.currencyCodeSpecified = true;
        td.languageCode = "en";
        td.orderId = (new Random()).Next(10000).ToString();
        td.merchantLocalDateTime = String.Format("{0:dd.MM.yyyy HH:mm:ss}", DateTime.Now);
        td.purchaserName = "IVANOV IVAN";
        td.purchaserEmail = "purchaser@mail.com";        

        StartTransactionResult result=merchantWS.startTransaction(td);
        if (result.success)
        {
            Session["customerReference"] = result.customerReference;
            Response.Redirect(result.redirectURL);
        }
        else
        {
            lbl_Error.Text = result.errorDescription;
        }
    }


}
