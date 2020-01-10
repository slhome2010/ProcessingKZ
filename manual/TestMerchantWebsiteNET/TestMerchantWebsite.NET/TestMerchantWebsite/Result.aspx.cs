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


public partial class Result : System.Web.UI.Page
{
    /// <summary>
    /// Checks the transaction status
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    protected void Page_Load(object sender, EventArgs e)
    {
        CNPMerchantWebService merchantWS = new CNPMerchantWebService();
        if (Session["customerReference"] == null)
        {
            lbl_Status.Text = "No transaction in flight...";
            return;
        }
        StoredTransactionStatus ts=merchantWS.getTransactionStatus("000000000000001", (string) Session["customerReference"]);
        if (ts != null)
            lbl_Status.Text = ts.transactionStatus;
        else
            lbl_Status.Text = "Error transaction not found...";

    }
    /// <summary>
    /// Attempts to request payment then checks the transaction status
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    protected void btn_RequestPayment_Click(object sender, EventArgs e)
    {
        CNPMerchantWebService merchantWS = new CNPMerchantWebService();
        bool retval;
        bool retvalSpecified;
        merchantWS.completeTransaction("000000000000001", (string) Session["customerReference"], true, true, null, out retval, out retvalSpecified);
        if (retvalSpecified && retval)
        {
            StoredTransactionStatus ts = merchantWS.getTransactionStatus("000000000000001", (string)Session["customerReference"]);
            if (ts != null)
                lbl_Status.Text = ts.transactionStatus;
        }
        else
        {
            lbl_Status.Text = "Failed to complete.";
        }
        
    }
    /// <summary>
    /// Attempts to request reversal then checks the transaction status
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    protected void btn_RequestReversal_Click(object sender, EventArgs e)
    {
        CNPMerchantWebService merchantWS = new CNPMerchantWebService();
        bool retval;
        bool retvalSpecified;
        merchantWS.completeTransaction("000000000000001", (string)Session["customerReference"], false, true, null, out retval, out retvalSpecified);
        if (retvalSpecified && retval)
        {
            StoredTransactionStatus ts = merchantWS.getTransactionStatus("000000000000001", (string)Session["customerReference"]);
            if (ts != null)
                lbl_Status.Text = ts.transactionStatus;
        } else {
            lbl_Status.Text = "Failed to reverse.";
        }
    }
}
