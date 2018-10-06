<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <!-- Title here -->
    <title>Bill</title>
   
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo asset('assets/images/favicon.png');?>">
  <style type="text/css">

.clearfix:after {
  content: "";
  display: table;
  clear: both;
}

a {
  color: #0087C3;
  text-decoration: none;
}

body {
  position: relative;
  width: 710px;  
  margin: 0 auto; 
  color: #555555;
  background: #FFFFFF; 
  font-family: "Times New Roman", Times, serif;
  font-size: 14px; 
  
}

header {
 border-bottom: 1px solid #aaaaaa;
margin-bottom: 0;
padding-bottom: 5px;
padding-top: 5px;
}

#logo {
  float: left;
  margin-top: 8px;
}

#logo img {
  height: 70px;
}


#details {
  margin-bottom: 10px;
}

#client {
background: #eeeeee none repeat scroll 0 0;
border-left: 4px solid #0087c3;
font-size: 24px;
height: 27px;
margin-bottom: 15px;
text-align: center;
width: 99%;
}

#client .to {
  color: #777777;
}

h2.name {
  font-size: 1.4em;
  font-weight: normal;
  margin: 0;
}

#invoice {
  float: right;
  text-align: right;
}

#invoice h4 {
  color: #0087C3;
  font-size: 21px;
  line-height: 1em;
  font-weight: normal;
  margin: 0  0 10px 0;
}

#invoice .date {
  font-size: 11px;
  color: #777777;
}

.invoice-list {
  font-family: "Times New Roman", Times, serif;
  font-size: 13px; 
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  height: 490px;

}

.invoice-list .voucher_head{
  border: 1px solid;
}
.invoice-list ul{
  margin: 0;
  padding: 0;
}

.invoice-list ul li{
  display: inline-block;
  margin-right: -2px;
  color: #000000; 
  

}

.invoice-list .voucher_head li{
  border-right: 1px solid #000;
     
  text-align: center;
}


.invoice-list .voucher_head  li:last-child {
  border-right: none;
}


.profit-list ul{
  text-align: right;
}
.profit-list ul li{
    
     display: inline;
    font-size: 15px;
    font-weight: bold;
    padding-left: 27px;
    padding-right: 25px;
     
}

.header_left{
  width: 400px; 
  display: inline-block;
  text-align: left; 
}

.header_right {
    display: inline-block;
    text-align: right;
    vertical-align: top;
    width: 295px;
}

.content_left{
  width: 300px; 
  display: inline-block;
  text-align: left;
}

.content_left h1{

}

.content_left p{
margin-top: 10px;
}

.content_right {
    display: inline-block;
padding: 20px 0 0;
text-align: right;
vertical-align: top;
width: 406px;
}
.content_right p {
    line-height: 18px;
    margin: 0;
}

.content_left p span {
    display: inline-block;
    font-weight: bold;
    margin-bottom: 5px;
}

.thanks_text{
  text-align: right; 
  padding-right: 150px; 
}

.office_address {
    margin-left: 370px;
}
.invoice_footer {
  margin-top: 40px;
  margin-bottom: 20px;
}
.amounts_in_word {
  height: 50px;
margin-bottom: 10px;
margin-top: 10px;
padding: 0;
}
.total_block ul {
  margin: 0;
  padding: 0;
}
.total_block ul li{
  border: 1px solid;
color: #000000;
display: inline-block;
list-style: outside none none;
margin-right: -5px; 
}

.content_right span {
  font-weight: bold;
}
.voucher_content span {
  font-size: 10px;
  font-weight: normal;
  padding-left: 15px;
}



  </style>
  <!-- <body onload="window.print();" onfocus="window.close()"> -->
  <body>
    <header class="clearfix">
      <div class="header_left header_content">
        <img src="<?php echo asset('assets/images/dfblack.png');?>" alt="Logo">
      </div>
      <div class="header_right header_content">
          <p><strong>D. F Tex</strong> <br/>13/2 West Panthpath,Dhaka 1207</p>
      </div>
    </header>
    <main>
      <div id="details" class="clearfix">
        <div class="content_left">
          
          <p><span>Supplier Detail</span>
          <?php echo isset($inventory_stocks->supplier_name) ? '<br/>'.$inventory_stocks->supplier_name:'';?>
          <?php echo isset($inventory_stocks->supplier_company) ? '<br/>'.$inventory_stocks->supplier_company:'';?>
          <?php echo isset($inventory_stocks->supplier_address) ? '<br/>'.$inventory_stocks->supplier_address:'';?>
          <?php echo isset($inventory_stocks->supplier_mobile) ? '<br><abbr title="Phone">Mb:</abbr>'.$inventory_stocks->supplier_mobile:'';?>
          </p>
        </div>

        <div class="content_right">
          <p><span>Date: <?php echo isset($inventory_stocks->stocks_transaction_date) ? date('j M, Y',strtotime($inventory_stocks->stocks_transaction_date)):'';?></span></p> 
          <p><span>Bill No: <?php echo isset($inventory_stocks->stocks_transaction_date) ? date('Ymd',strtotime($inventory_stocks->stocks_transaction_date)):'';?><?php echo isset($inventory_stocks->stocks_transactions_id) ? $inventory_stocks->stocks_transactions_id:'';?></span></p>
        </div>
      </div>
      <div id="client">
          <div class="to">Bill</div>                  
       </div>
      
      <div class="invoice-list">
      
        <ul class="voucher_head">
           <li  style="width:50px; font-weight: bold;">SL</li>
           <li  style="width:325px; font-weight: bold;">Name of Item</li>
           <li  style="width:70px; font-weight: bold;">Quantity</li>
           <li  style="width:115px; font-weight: bold;">Rate Per Unit (Tk)</li>
           <li  style="width:135px; font-weight: bold;">Amount (Tk)</li>
        </ul>

        <ul class="voucher_content">
          <li  style="width:50px; text-align:center;font-weight:bold;">1</li>
          <li  style="width:325px;padding-left:10px;"><strong><?php echo isset($inventory_stocks->item_name) ? $inventory_stocks->item_name:'';?></strong><br><span>(Purpose : <?php echo isset($inventory_stocks->cost_center_name) ? $inventory_stocks->cost_center_name:'';?>)</span></li>
          <li  style="width:70px; text-align:center;font-weight:bold;"><?php echo isset($inventory_stocks->transaction_stocks_quantity) ? $inventory_stocks->transaction_stocks_quantity:'';?><?php echo isset($inventory_stocks->item_quantity_unit) ? $inventory_stocks->item_quantity_unit:'';?></li>
          <li  style="width:115px;text-align:center;font-weight:bold;">Tk <?php echo isset($inventory_stocks->stocks_quantity_rate) ? number_format($inventory_stocks->stocks_quantity_rate,2,'.','' ):'';?></li>
          <li  style="width:135px; text-align:center;font-weight:bold;">Tk <?php echo isset($inventory_stocks->stocks_quantity_cost) ? number_format($inventory_stocks->stocks_quantity_cost,2,'.','' ):'';?></li>
        </ul>
           
      </div>
      <div class="total_block">
        <ul>
          <li style="width:560px;text-align:center;font-weight:bold;">Total</li>
          <li style="width:135px;text-align:center;font-weight:bold;">Tk <?php echo isset($inventory_stocks->stocks_quantity_cost) ? number_format($inventory_stocks->stocks_quantity_cost,2,'.','' ):'';?> </li>
        </ul>
      </div>
      <div class="amounts_in_word">
        <p><strong>Amount In words:</strong>
        <?php
          if(is_numeric($inventory_stocks->stocks_quantity_cost)){
            $amount_words = \App\System::ConvertNumberToWords($inventory_stocks->stocks_quantity_cost);

            if(!empty($amount_words))
              echo ucwords("taka ".$amount_words.' only');
          }


         ?>
      </p>
      </div>
      <div class="invoice_footer">
        <table  style="width:100%;">
      <tr>
        <th>
          <hr style="width:100px;">Receiver Signature
        </th>
        <th>
          <hr style="width:100px;">Account Signature
        </th>
        <th style="margin-right:20px;">
          <hr style="width:100px;">Authorize Signature
        </th>
      </tr>
    </table>
      </div>   
    </main>
    
  </body>
</html>