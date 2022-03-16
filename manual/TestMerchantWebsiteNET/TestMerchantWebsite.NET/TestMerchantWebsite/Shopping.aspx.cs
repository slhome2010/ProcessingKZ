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
using System.Collections.Generic;

using TestMerchantWebsite.CNPMerchantWebService;
using TestMerchantWebsite;

public partial class Shopping : System.Web.UI.Page
{
    /// <summary>
    /// Populate the items list
    /// </summary>
    protected void Page_Load(object sender, EventArgs e)
    {
        updateAmount();
    }

    private void updateAmount()
    {
        int total = Basket.getTotalAmount();
        lbl_TotalAmount.Text = total.ToString();
        btn_Checkout.Enabled = total > 0;
        btn_Checkout.Visible = total > 0;

        
    }


    /// <summary>
    /// Verify the currency code is valid
    /// </summary>
    protected void CustomValidator1_ServerValidate(object source, ServerValidateEventArgs args)
    {
        int val;
        if (int.TryParse(args.Value, out val))
        {
            args.IsValid = (args.Value.Length == 3);
        }
        else
        {
            args.IsValid = false;
        }
        
    }
    
    /// <summary>
    /// Adds the item of goods to the table
    /// </summary>
    protected void btn_AddItem_Click(object sender, EventArgs e)
    {
        List<GoodsItem> basket = Basket.getBasket();
        GoodsItem newItem = new GoodsItem();
        basket.Add(newItem);
        newItem.amount = txt_ItemAmount.Text;
        newItem.currencyCode = int.Parse(txt_ItemCurrency.Text);
        newItem.currencyCodeSpecified = true;
        newItem.nameOfGoods = txt_ItemName.Text;
        newItem.merchantsGoodsID = Guid.NewGuid().ToString();
        ObjectDataSource1.Update();
        updateAmount();
    }

    protected void ObjectDataSource1_Selecting(object sender, ObjectDataSourceSelectingEventArgs e)
    {

    }
    protected void GridView1_RowDeleted(object sender, GridViewDeletedEventArgs e)
    {
        updateAmount();
    }
    protected void btn_Checkout_Click(object sender, EventArgs e)
    {
        Response.Redirect("Checkout.aspx");
    }
}
