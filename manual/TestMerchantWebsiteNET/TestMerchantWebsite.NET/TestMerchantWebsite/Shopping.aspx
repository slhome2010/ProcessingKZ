<%@ Page Language="C#" AutoEventWireup="true" Inherits="Shopping" Codebehind="Shopping.aspx.cs" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
    <title>Shopping</title>
</head>
<body>
    <form id="form1" runat="server">
    <div>
        <h1>Shopping</h1>
        <p>This demo web page allows you to fill your shopping cart with goods ready for submission to the services.</p>
        <h2>Current Basket Contents</h2>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <asp:GridView ID="GridView1" runat="server" AutoGenerateColumns="False" DataSourceID="ObjectDataSource1" DataKeyNames="merchantsGoodsID" OnRowDeleted="GridView1_RowDeleted">
            <Columns>
                <asp:CommandField ShowDeleteButton="True" />
                <asp:BoundField DataField="amount" HeaderText="amount" SortExpression="amount" />
                <asp:BoundField DataField="currencyCode" HeaderText="currencyCode" SortExpression="currencyCode" />
                <asp:BoundField DataField="nameOfGoods" HeaderText="nameOfGoods" SortExpression="nameOfGoods" />
            </Columns>
        </asp:GridView>
        <asp:ObjectDataSource ID="ObjectDataSource1" runat="server" DataObjectTypeName="TestMerchantWebsite.CNPMerchantWebService.GoodsItem" DeleteMethod="deleteBasketContents" InsertMethod="addBasketContents" SelectMethod="selectBasketContents" TypeName="TestMerchantWebsite.Basket" UpdateMethod="updateBasketContents" OnSelecting="ObjectDataSource1_Selecting">
        </asp:ObjectDataSource>
        <h3>Total Amount: <asp:Label ID="lbl_TotalAmount" runat="server" Text="Label"></asp:Label> <asp:Button ID="btn_Checkout" runat="server" Text="Checkout" OnClick="btn_Checkout_Click" /></h3>
        <h2>Add Item To Basket</h2>
        <table>
            <tr><td align="right">Name</td><td><asp:TextBox ID="txt_ItemName" runat="server">MyItem</asp:TextBox></td><td><asp:RequiredFieldValidator ID="name_RequiredFieldValidator" runat="server" ErrorMessage="Must be given" ControlToValidate="txt_ItemName"></asp:RequiredFieldValidator></td></tr>
            <tr><td align="right">Currency</td><td><asp:TextBox ID="txt_ItemCurrency" runat="server">398</asp:TextBox></td><td>
                <asp:CustomValidator ID="CustomValidator1" runat="server" ControlToValidate="txt_ItemCurrency"
                    ErrorMessage="Must be in the range 000 to 999." OnServerValidate="CustomValidator1_ServerValidate"></asp:CustomValidator></td></tr>
            <tr><td align="right">Amount</td><td><asp:TextBox ID="txt_ItemAmount" runat="server">1000</asp:TextBox></td><td><asp:RegularExpressionValidator ID="RegularExpressionValidator1" runat="server"
                    ErrorMessage="Must be a number between 00000000000 and 999999999999" ControlToValidate="txt_ItemAmount" ValidationExpression="^[0-9]{1,12}$"></asp:RegularExpressionValidator></td></tr>
            <tr><td align="right" style="height: 26px">&nbsp;</td><td style="height: 26px"><asp:Button ID="btn_AddItem" runat="server" Text="Add" OnClick="btn_AddItem_Click" /></td><td style="height: 26px">&nbsp;</td></tr>
        </table>
    </div>
    </form>
</body>
</html>
