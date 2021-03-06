<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <!-- Title here -->
    <title>Inventory Stock Summary Reports</title>
   
    
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
  max-height :auto;

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
          <div class="to"><strong> Inventory Stock Summary Report </strong></div>                  
       </div>
      
      <div class="invoice-list">
        <h3 align="center">From: {{(isset($search_from)?$search_from :'')}} - To: {{(isset($search_to)?$search_to :'')}}</h3>

          <ul class="voucher_head">
              <li  style="width:105px; font-weight: bold;">Particulars</li>
              <li  style="width:120px; font-weight: bold;">Opening Balance</li>
              <li  style="width:120px; font-weight: bold;">Inwards</li>
              <li  style="width:100px; font-weight: bold;">Return</li>
              <li  style="width:120px; font-weight: bold;">Outwards</li>
              <li  style="width:120px; font-weight: bold;">Closing Balance</li>
          </ul>
      
        <ul class="voucher_head">
            <li  style="width:105px; font-weight: bold;">Stock Item</li>
            <li  style="width:60px; font-weight: bold;">Qty</li>
            <li  style="width:57px; font-weight: bold;">Value</li>
            <li  style="width:60px; font-weight: bold;">Qty</li>
            <li  style="width:57px; font-weight: bold;">Value</li>
            <li  style="width:48px; font-weight: bold;">Qty</li>
            <li  style="width:50px; font-weight: bold;">Value</li>
            <li  style="width:60px; font-weight: bold;">Qty</li>
            <li  style="width:58px; font-weight: bold;">Value</li>
            <li  style="width:60px; font-weight: bold;">Qty</li>
            <li  style="width:57px; font-weight: bold;">Value</li>
        </ul>



      <?php

          $total_opening_cost=0;
          $total_closing_cost=0;
          $total_inwards_cost=0;
          $total_outwards_cost=0;
          $total_opening_qty=0;
          $total_closing_qty=0;
          $total_inwards_qty=0;
          $total_outwards_qty=0;
          $total_return_qty=0;
          $total_return_cost=0;

        if(!empty($stock_summary_data) && count($stock_summary_data)>0){
          foreach($stock_summary_data as $key => $list){
            $stock_summery_outwards_list_cost=0;
            $stock_summery_outwards_list_qty=0;

                $stock_item_info=\DB::table('ltech_inventory_stocks')
                        ->where('inventory_stock_id',$list['inventory_stock_id'])
                        ->first();

                  $stock_summery_opening_list=\DB::table('ltech_inventory_stocks_transactions')
                        ->where('ltech_inventory_stocks_transactions.inventory_stock_id',$list['inventory_stock_id'])
                        ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                        ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','!=','outwards')
                        ->where(function($query){
                            if(isset($cost_center) && ($cost_center!=0)){
                                $query->where(function ($q){
                                    $q->where('ltech_inventory_stocks_transactions.cost_center_id', $cost_center);
                                  });
                            }
                        })
                        ->orderBy('ltech_inventory_stocks_transactions.stocks_transactions_id','asc')
                        ->first();

                  $stock_summery_closing_list=\DB::table('ltech_inventory_stocks_transactions')
                        ->where('ltech_inventory_stocks_transactions.inventory_stock_id',$list['inventory_stock_id'])
                                  ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                        ->where(function($query){
                          if(isset($cost_center) && ($cost_center!=0)){
                              $query->where(function ($q){
                                  $q->where('ltech_inventory_stocks_transactions.cost_center_id', $cost_center);
                                });
                          }
                        })
                        ->orderBy('ltech_inventory_stocks_transactions.stocks_transactions_id','desc')
                        ->first();

                  $stock_summery_outwards_list=\DB::table('ltech_inventory_stocks_transactions')
                        ->where('ltech_inventory_stocks_transactions.inventory_stock_id',$list['inventory_stock_id'])
                                  ->where('ltech_inventory_stocks_transactions.stocks_transaction_date','<',$search_from)
                        ->where(function($query){
                                    if(isset($cost_center) && ($cost_center!=0)){
                                        $query->where(function ($q){
                                            $q->where('ltech_inventory_stocks_transactions.cost_center_id', $cost_center);
                                          });
                                    }
                                  })
                                  ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','=','outwards')
                        ->get();

                      foreach ($stock_summery_outwards_list as $key => $value) {
                                  $stock_summery_outwards_list_cost=$stock_summery_outwards_list_cost+$value->stocks_quantity_cost;
                                  $stock_summery_outwards_list_qty=$stock_summery_outwards_list_qty+$value->transaction_stocks_quantity;
                                }

              $open_cost=0;
              $open_qty=0;
              $stock_inwards_outwards_cost=0;
              $stock_inwards_outwards_qty=0;

            if(!empty($stock_summery_opening_list)){
                  if($stock_summery_opening_list->opening_transaction_stocks_quantity <=0 || $stock_summery_opening_list->opening_transaction_stocks_cost){
                  $stock_summery_opening_other_list=\DB::table('ltech_inventory_stocks_transactions')
                        ->where('ltech_inventory_stocks_transactions.inventory_stock_id',$list['inventory_stock_id'])
                                  ->where('ltech_inventory_stocks_transactions.stocks_transaction_date','<',$search_from)
                                  ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','!=','outwards')
                                  ->where(function($query){
                                    if(isset($cost_center) && ($cost_center!=0)){
                                        $query->where(function ($q){
                                            $q->where('ltech_inventory_stocks_transactions.cost_center_id', $cost_center);
                                          });
                                    }
                                  })
                        ->orderBy('ltech_inventory_stocks_transactions.stocks_transactions_id','desc')
                        ->first();
                  $stock_inwards_outwards_list=\DB::table('ltech_inventory_stocks_transactions')
                      ->where('ltech_inventory_stocks_transactions.inventory_stock_id',$list['inventory_stock_id'])
                                ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','=','outwards')
                                ->where('ltech_inventory_stocks_transactions.stocks_transaction_date','<',$search_from)
                                ->where(function($query){
                                  if(isset($cost_center) && ($cost_center!=0)){
                                      $query->where(function ($q){
                                          $q->where('ltech_inventory_stocks_transactions.cost_center_id', $cost_center);
                                        });
                                  }
                                })
                                ->get();
                                foreach ($stock_inwards_outwards_list as $key => $value) {
                                  $stock_inwards_outwards_cost=$stock_inwards_outwards_cost+$value->stocks_quantity_cost;
                                  $stock_inwards_outwards_qty=$stock_inwards_outwards_qty+$value->transaction_stocks_quantity;
                                }
                  $open_cost=(isset($stock_summery_opening_other_list->closing_transaction_stocks_cost)?($stock_summery_opening_other_list->closing_transaction_stocks_cost):0)-$stock_inwards_outwards_cost;
                  $open_qty=(isset($stock_summery_opening_other_list->closing_transaction_stocks_quantity)?($stock_summery_opening_other_list->closing_transaction_stocks_quantity):0)-$stock_inwards_outwards_qty;
                  }

                }

      ?>    
              <ul class="voucher_content">
                <li  style="width:105px; text-align:center;"><span>                               @if(!empty($stock_item_info))
                {{(isset($stock_item_info->item_name)? ($stock_item_info->item_name) : '')}}
                @endif</span><br/></li>
                  <li style="width:60px; font-weight: bold; text-align:center;"><span>{{(isset($stock_summery_opening_list->opening_transaction_stocks_quantity)? ($stock_summery_opening_list->opening_transaction_stocks_quantity)-$stock_summery_outwards_list_qty :$open_qty)}}</span><br/>
                  <li  style="width:60px; font-weight: bold; text-align:center;"><span>{{(isset($stock_summery_opening_list->opening_transaction_stocks_cost)? ($stock_summery_opening_list->opening_transaction_stocks_cost)-$stock_summery_outwards_list_cost :$open_cost)}}</span></li>
                  <li  style="width:60px; font-weight: bold; text-align:center;"><span><?php echo $list['inwards_qty'] ;?></span></li>
                  <li  style="width:60px; font-weight: bold; text-align:center;"><span><?php echo $list['inwards_cost'] ;?></span></li>
                  <li  style="width:50px; font-weight: bold; text-align:center;"><span><?php echo $list['return_qty'] ;?></span></li>
                  <li  style="width:50px; font-weight: bold; text-align:center;"><span><?php echo $list['return_cost'] ;?></span></li>
                  <li  style="width:60px; font-weight: bold; text-align:center;"><span>{{$list['outwards_qty']}}</span></li>
                  <li  style="width:60px; font-weight: bold; text-align:center;">{{$list['outwards_cost']}}<span></span></li>
                  <li  style="width:60px; font-weight: bold; text-align:center;"><span>{{(isset($stock_summery_opening_list->opening_transaction_stocks_quantity)? ($stock_summery_opening_list->opening_transaction_stocks_quantity)-$stock_summery_outwards_list_qty :$open_qty)-$list['return_qty']+$list['inwards_qty']-$list['outwards_qty']}}</span></li>
                  <li  style="width:60px; font-weight: bold; text-align:center;"><span>{{(isset($stock_summery_opening_list->opening_transaction_stocks_cost)? ($stock_summery_opening_list->opening_transaction_stocks_cost)-$stock_summery_outwards_list_cost :$open_cost)-$list['return_cost']+$list['inwards_cost']-$list['outwards_cost']}}</span></li>


              </ul>
      <?php

                  $total_return_qty=$total_return_qty+$list['return_qty'];
                  $total_return_cost=$total_return_cost+$list['return_cost'];

                  $total_opening_cost=$total_opening_cost+(isset($stock_summery_opening_list->opening_transaction_stocks_cost)? ($stock_summery_opening_list->opening_transaction_stocks_cost) :(isset($stock_summery_opening_other_list->closing_transaction_stocks_quantity)?($stock_summery_opening_other_list->closing_transaction_stocks_quantity):0));


                  $total_closing_cost=$total_closing_cost+(isset($stock_summery_opening_list->opening_transaction_stocks_cost)? ($stock_summery_opening_list->opening_transaction_stocks_cost) :$open_cost)-$list['return_cost']+$list['inwards_cost']-$list['outwards_cost'];
                  $total_inwards_cost=$total_inwards_cost+$list['inwards_cost'];
                  $total_outwards_cost=$total_outwards_cost+$list['outwards_cost'];


                  $total_opening_qty=$total_opening_qty+(isset($stock_summery_opening_list->opening_transaction_stocks_quantity)? ($stock_summery_opening_list->opening_transaction_stocks_quantity) :0);
                  

                  $total_closing_qty=$total_closing_qty+(isset($stock_summery_opening_list->opening_transaction_stocks_quantity)? ($stock_summery_opening_list->opening_transaction_stocks_quantity) :$open_qty)-$list['return_qty']+$list['inwards_qty']-$list['outwards_qty'];

                  $total_inwards_qty=$total_inwards_qty+$list['inwards_qty'];
                  $total_outwards_qty=$total_outwards_qty+$list['outwards_qty'];

        }
        }

      ?>

      </div>
      <div class="total_block">
        <ul>
          <li style="width:105px;text-align:center;font-weight:bold;">Total</li>
          <li  style="width:60px; font-weight: bold; text-align:center;">{{$total_opening_qty}}</li>
          <li  style="width:60px; font-weight: bold; text-align:center;">{{$total_opening_cost}}</li>
          <li  style="width:60px; font-weight: bold; text-align:center;">{{$total_inwards_qty}}</li>
          <li  style="width:60px; font-weight: bold; text-align:center;">{{$total_inwards_cost}}</li>
          <li  style="width:48px; font-weight: bold; text-align:center;">{{$total_return_qty}}</li>
          <li  style="width:50px; font-weight: bold; text-align:center;">{{$total_return_cost}}</li>
          <li  style="width:60px; font-weight: bold; text-align:center;">{{$total_outwards_qty}}</li>
          <li  style="width:60px; font-weight: bold; text-align:center;">{{$total_outwards_cost}}</li>
          <li  style="width:60px; font-weight: bold; text-align:center;">{{$total_closing_qty}}</li>
          <li  style="width:60px; font-weight: bold; text-align:center;">{{$total_closing_cost}}</li>
        </ul>
      </div>
      <div class="amounts_in_word">

        <p><strong>Amount In words:</strong>
        <?php
          if(is_numeric($total_closing_cost)){
            $amount_words = \App\System::ConvertNumberToWords($total_closing_cost);

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