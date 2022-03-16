<%@ Page Language="C#" AutoEventWireup="true" Inherits="Result" Codebehind="Result.aspx.cs" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
    <title>Trasaction Result</title>
</head>
<body>
    <form id="form1" runat="server">
    <div>
        <h1>The status of the transaction was ...</h1>
        <asp:Label ID="lbl_Status" runat="server" Text=""></asp:Label>
        <br />
        <asp:Button ID="btn_RequestPayment" runat="server" Text="Request Payment" OnClick="btn_RequestPayment_Click" />
        <asp:Button ID="btn_RequestReversal" runat="server" Text="Request Cancellation" OnClick="btn_RequestReversal_Click" /></div>
    </form>
</body>
</html>
