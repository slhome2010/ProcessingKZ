<%@ Page Language="C#" AutoEventWireup="true" Inherits="Checkout" Codebehind="Checkout.aspx.cs" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
    <title>Checkout</title>
</head>
<body>
    <form id="form1" runat="server">
    <div>
        <h1>Review Your Purchase</h1>
        <asp:ObjectDataSource id="ObjectDataSource1" runat="server" 
            InsertMethod="addBasketContents" DeleteMethod="deleteBasketContents" 
            SelectMethod="selectBasketContents" TypeName="TestMerchantWebsite.Basket" 
            DataObjectTypeName="TestMerchantWebsite.CNPMerchantWebService.GoodsItem" 
            UpdateMethod="updateBasketContents">
        </asp:ObjectDataSource>
        <asp:GridView ID="GridView1" runat="server" AutoGenerateColumns="False"
            DataSourceID="ObjectDataSource1">
            <Columns>
                <asp:BoundField DataField="amount" HeaderText="amount" SortExpression="amount" />
                <asp:BoundField DataField="currencyCode" HeaderText="currencyCode" SortExpression="currencyCode" />
                <asp:BoundField DataField="nameOfGoods" HeaderText="nameOfGoods" 
                    SortExpression="nameOfGoods" />
            </Columns>
        </asp:GridView>
        <br />
    </div>
        <asp:Label ID="lbl_Error" runat="server"></asp:Label><br />
        <br />
        <asp:Button ID="btn_StartCardPayment" runat="server" OnClick="btn_StartCardPayment_Click"
            Text="Start Card Payment" />
    </form>
</body>
</html>
