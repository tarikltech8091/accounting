<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <!-- Title here -->
    <title>Sales Report</title>
   
    
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
  font-size: 15px; 
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;

  height: 520px;

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
    width: 200px;
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
padding: 9px 0 0;
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


  </style>
  <body onload="window.print();" onfocus="window.close()">
  <!-- <body > -->
    <header class="clearfix">
      <div class="header_left header_content">
        <img src="<?php echo asset('assets/images/dfblack.png');?>" alt="Logo">
      </div>
    </header>
    <main>
    <?php $company_details=\DB::table('company_details')->latest()->first(); ?>
      <div id="details" class="clearfix">
        <div class="content_left">
          <p><span>{{isset($company_details->company_name)? $company_details->company_name :''}}</span> <br/>{{isset($company_details->company_address)? $company_details->company_address :''}}</p>
        </div>

        <div class="content_right">
          <p><span>Date: </span><?php echo date("Y-m-d");?></p> 
        </div>
      </div>
      <div id="client">
          <div class="to"><strong> Sales Report </strong></div>                  
       </div>
      
      <div class="invoice-list">
        <h3 align="center">From: {{(isset($search_from)?$search_from :'')}} - To: {{(isset($search_to)?$search_to :'')}}</h3>
      
        <ul class="voucher_head">
            <li  style="width:425px; font-weight: bold;">Particulars</li>
            <li  style="width:135px; font-weight: bold;">Quantity</li>
            <li  style="width:135px; font-weight: bold;">Amount</li>
        </ul>



      <?php
        $total_amount=0;
        $total_quantity=0;
        $grand_total_amount=0; 
        $grand_total_quantity=0;

        if(!empty($sales_goods_data) && count($sales_goods_data)>0){
          foreach($sales_goods_data as $key => $list){
      ?>
              <ul class="voucher_content">
                <li  style="width:425px; text-align:center;"><span>{{isset($list['finish_goods_name'])? $list['finish_goods_name'] :'' }}</span>
                  <?php
                      $total_amount=$list['outwards_amount']-$list['return_amount'];
                      $total_quantity=$list['outwards_quantity']-$list['return_quantity'];
                      $total_amount=\App\Report::MakePositiveData($total_amount);
                      $total_quantity=\App\Report::MakePositiveData($total_quantity);
                  ?>
                <li  style="width:135px; text-align:center;"><span>{{isset($total_quantity)? $total_quantity : 0}}</span>
                <li  style="width:135px; text-align:center;"><span>{{isset($total_amount)? $total_amount : 0}}</span>

              </ul>
      <?php
          
          $grand_total_amount=$grand_total_amount+$total_amount;
          $grand_total_quantity=$grand_total_quantity+$total_quantity;

        }
        }

      ?>

      </div>
      <div class="total_block">
        <ul>
          <li style="width:425px;text-align:center;font-weight:bold;">Total</li>
          <li style="width:135px;text-align:center;font-weight:bold;"> Tk <?php echo isset($grand_total_quantity) ? number_format($grand_total_quantity,2,'.',''):'';?>
          </li>
          <li style="width:135px;text-align:center;font-weight:bold;"> Tk <?php echo isset($grand_total_amount) ? number_format($grand_total_amount,2,'.',''):'';?>
          </li>
        </ul>
      </div>
      <div class="amounts_in_word">

        <p><strong>Amount In words:</strong>
        <?php
          if(is_numeric($grand_total_amount)){
            $amount_words = \App\System::ConvertNumberToWords($grand_total_amount);

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
          <hr style="width:100px;">Prepared By
        </th>
        <th>
          <hr style="width:100px;">Accountant
        </th>
        <th style="margin-right:20px;">
          <hr style="width:100px;">Approved By
        </th>
      </tr>
    </table>
      </div>   
    </main>
    
  </body>
</html>