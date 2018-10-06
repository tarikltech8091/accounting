<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <!-- Title here -->
  <title>Manufacturing Report</title>
  
  
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
<!-- <body onload="window.print();" onfocus="window.close()"> -->
<body>
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
      <div class="to"><strong> Manufaturing Account</strong></div>                  
    </div>
    
    <div class="invoice-list">
      <h3 align="center">From: {{(isset($search_from)?$search_from :'')}} - To: {{(isset($search_to)?$search_to :'')}}</h3>
      
      <ul class="voucher_head">
        <li  style="width:795px; font-weight: bold;">The Format Of a Manufaturing Account</li>
      </ul>



      <?php
      $total_balance1=0;
      ?>

      @if(isset($all_opening_amount) && count($all_opening_amount)>0)

      <ul class="voucher_content">
        <li  style="width:325px; text-align:center;"><span>Opening Stock Raw Materials</span></li>
        <li  style="width:200px; text-align:center;"><span>{{isset($all_opening_amount)? $all_opening_amount : 0}}</span></li>
        <li  style="width:170px; text-align:center;"><span></span></li>
        <?php $total_balance1=$total_balance1+$all_opening_amount; ?>

      </ul>
      @endif


      @if(isset($total_amount_of_raw_materials_purchase_data) && count($total_amount_of_raw_materials_purchase_data)>0)

      <ul class="voucher_content">
        <li  style="width:325px; text-align:center;"><span>Add Purchase Of raw materials</span></li>
        <li  style="width:200px; text-align:center;"><span>{{isset($total_amount_of_raw_materials_purchase_data)? $total_amount_of_raw_materials_purchase_data : 0}}</span></li>
        <li  style="width:170px; text-align:center;"><span></span></li>
        <?php $total_balance1=$total_balance1+$total_amount_of_raw_materials_purchase_data; ?>

      </ul>
      @endif


      @if(isset($total_carriage_data_info) && count($total_carriage_data_info)>0)
      @foreach($total_carriage_data_info as $key => $list)
      <ul class="voucher_content">
        <li  style="width:325px; text-align:center;"><span>{{isset($list['ledger_name'])? $list['ledger_name'] : 0}}</span></li>
        <li  style="width:200px; text-align:center;"><span>{{isset($list['debit'])? $list['debit'] : 0}}</span></li>
        <li  style="width:170px; text-align:center;"><span></span></li>
        <?php $total_balance1=$total_balance1+$list['debit']; ?>

      </ul>

      @endforeach
      @endif

      <ul class="voucher_content">
        <li  style="width:325px; text-align:center;"><span></span></li>
        <li  style="width:200px; text-align:center;"><span><strong>{{isset($total_balance1)? $total_balance1 : 0}}</strong></span></li>
        <li  style="width:170px; text-align:center;"><span></span></li>
      </ul>



      @if(isset($total_stock_outwards_amount) && count($total_stock_outwards_amount)>0)
      <ul class="voucher_content">
        <li  style="width:325px; text-align:center;"><span>Purchase Return Of Raw materials</span></li>
        <li  style="width:200px; text-align:center;"><span></span>{{isset($total_stock_outwards_amount)? $total_stock_outwards_amount : 0}}</span></li>
        <li  style="width:170px; text-align:center;"><span></span></li>
      </ul>
      <?php $total_balance1=$total_balance1-$total_stock_outwards_amount; ?>
      @endif

      <ul class="voucher_content">
        <li  style="width:325px; text-align:center;"><span></span></li>
        <li  style="width:200px; text-align:center;"><span><strong>{{isset($total_balance1)? $total_balance1 : 0}}</strong></span></li>
        <li  style="width:170px; text-align:center;"><span></span></li>
      </ul>

      @if(isset($all_closing_amount) && count($all_closing_amount)>0)
      <ul class="voucher_content">
        <li  style="width:325px; text-align:center;"><span>Closing Stocks Of Raw Materials</span></li>
        <li  style="width:200px; text-align:center;">{{isset($all_closing_amount)? $all_closing_amount : 0}}<span></span></li>
        <li  style="width:170px; text-align:center;"><span></span></li>
      </ul>
      <?php $total_balance1=$total_balance1-$all_closing_amount; ?>
      @endif


      <ul class="voucher_content">
        <li  style="width:325px; text-align:center;"><span>Cost Of Direct Materials</span></li>
        <li  style="width:200px; text-align:center;"><span></span></li>
        <li  style="width:170px; text-align:center;">{{isset($total_balance1)?$total_balance1 :0}}<span></span></li>
      </ul>

      @if(isset($total_direct_labor_data) && count($total_direct_labor_data)>0)
            
            <ul class="voucher_content">
              <li  style="width:325px; text-align:center;"><span>Add Direct Labor</span></li>
              <li  style="width:200px; text-align:center;"><span></span></li>
              <li  style="width:170px; text-align:center;"><span>{{$total_direct_labor_data}}</span></li>
            </ul>
           <?php $total_balance1=$total_balance1+$total_direct_labor_data; ?>

      @endif
      @if(isset($total_others_expences_data_info) && count($total_others_expences_data_info)>0)
            <ul class="voucher_content">
              <li  style="width:325px; text-align:center;"><span>Add Other Direct Expenses</span></li>
              <li  style="width:200px; text-align:center;"><span></span></li>
              <li  style="width:170px; text-align:center;"><span>{{$total_others_expences_data_info}}</span></li>
            </ul>
           <?php $total_balance1=$total_balance1+$total_others_expences_data_info; ?>

      @endif




      <ul class="voucher_content">
        <li  style="width:325px; text-align:center;"><span>Prime Cost</span></li>
        <li  style="width:200px; text-align:center;"><span></span></li>
        <li  style="width:170px; text-align:center;"><span>{{isset($total_balance1)? $total_balance1 : 0}}</span></li>
      </ul>

      <ul class="voucher_content">
        <strong><li  style="width:695px; text-align:center;"><span>Factory Overhead Expenses</span><br/></li></strong>
      </ul>


      <?php 
      $balance=0;
      $total_balance=0;
      if(isset($alll_overhead_info) && count($alll_overhead_info)>0){
      foreach($alll_overhead_info as $key => $list){
      if(count($list)>0){
      foreach($list as $key => $value){
      ?>
      <ul class="voucher_content">
        <li  style="width:325px; text-align:center;"><span><?php echo $value['journal_particular_name'] ;?></span></li>
        <?php
        if(($value['debit']-$value['credit']) < 0){
        $balance = (-1)*($value['debit']-$value['credit']);
      }else{
        $balance=($value['debit']-$value['credit']);
      }
      ?>
    <li  style="width:200px; text-align:center;"><span>{{$balance}}</span></li>
    <li  style="width:170px; text-align:center;"><span></span></li>
  </ul>
   <?php $total_balance=$total_balance+$balance; 
  
}}}}
?>


  <ul class="voucher_content">
    <li  style="width:325px; text-align:center;"><span>Total Factory Overhead Expenses</span></li>
    <li  style="width:200px; text-align:center;"><span></span></li>
    <li  style="width:170px; text-align:center;"><span>{{isset($total_balance)? $total_balance : 0}}</span></li>
  </ul>
  <?php $grand_total=$total_balance1+$total_balance; ?>


  <ul class="voucher_content">
    <li  style="width:325px; text-align:center;"><span>Cost Of Production</span></li>
    <li  style="width:200px; text-align:center;"><span></span></li>
    <li  style="width:170px; text-align:center;"><span></span></li>
  </ul>




</div>
<div class="total_block">
  <ul>
    <li style="width:525px;text-align:center;font-weight:bold;">Cost Of Production</li>
    <li style="width:170px;text-align:center;font-weight:bold;"> Tk <?php echo isset($grand_total) ? number_format($grand_total,2,'.',''):'';?></li>
  </ul>
</div>
<div class="amounts_in_word">

  <p><strong>Amount In words:</strong>
    <?php
    if(is_numeric($grand_total)){
    $amount_words = \App\System::ConvertNumberToWords($grand_total);

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