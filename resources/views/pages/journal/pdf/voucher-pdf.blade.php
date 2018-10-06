<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <!-- Title here -->
    <title><?php echo isset($journalinfo[0]->posting_type)? $journalinfo[0]->posting_type:'Journal';?></title>
   
    
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
        <!--<p>Goedkopebeltegoed.nl</p>-->
        <img src="<?php echo asset('assets/images/dfblack.png');?>" alt="Logo">
      </div>
    </header>
    <main>
      <div id="details" class="clearfix">
        <div class="content_left">
          <p><span>D. F Tex</span> <br/>13/2 West Panthpath, <br/>Dhaka 1207</p>
        </div>

        <div class="content_right">
          <p><span>Date: </span><?php echo isset($journalinfo[0]->journal_date)? $journalinfo[0]->journal_date:'';?></p> 
          <p><span>Voucher No: </span><?php echo isset($journalinfo[0]->journal_date)? date('Ymd',strtotime($journalinfo[0]->journal_date)):'';?><?php echo isset($journalinfo[0]->journal_id)? $journalinfo[0]->journal_id:'';?></p>
        </div>
      </div>
      <div id="client">
          <div class="to"><?php echo isset($journalinfo[0]->posting_type)? $journalinfo[0]->posting_type:'Journal';?></div>                  
       </div>
      
      <div class="invoice-list">
      
        <ul class="voucher_head">
           <li  style="width:100px; font-weight: bold;">Date</li>
           <li  style="width:325px; font-weight: bold;">Particulars</li>
           <li  style="width:135px; font-weight: bold;">Debit</li>
           <li  style="width:135px; font-weight: bold;">Credit</li>
         </ul>

       <?php
       		if(count($journalinfo)>0){
       			$total_debit=0;
       			$total_credit=0;
       			foreach ($journalinfo as $key => $journalinfo) {
       				if($journalinfo->journal_particular_amount_type=='debit'){ 

       					$total_debit=$total_debit+$journalinfo->journal_particular_amount;
       					?>
       					<ul class="voucher_content">
           					<li  style="width:100px; "><?php echo ($key==0)? date('j M, Y',strtotime($journalinfo->journal_date)):'';?></li>
           					<li  style="width:325px;"><span><?php echo isset($journalinfo->journal_particular_name)? $journalinfo->journal_particular_name:'';?></span><br/>
								 	<i style="margin-left:10px; font-size:12px"><?php echo !empty($journalinfo->journal_particular_naration)? '('.$journalinfo->journal_particular_naration.')':'';?></i></li>
				        	<li  style="width:135px;text-align:center;font-weight:bold;"><?php echo isset($journalinfo->journal_particular_amount)? number_format($journalinfo->journal_particular_amount,2,'.',''):'';?></li>
				        	<li  style="width:135px;"></li>
				        </ul>
       			<?php	}

       				if($journalinfo->journal_particular_amount_type=='credit'){ 

       						$total_credit=$total_credit+$journalinfo->journal_particular_amount;
       					?>

       					<ul class="voucher_content">
           					<li  style="width:100px; "><?php echo ($key==0)? date('j M, Y',strtotime($journalinfo->journal_date)):'';?></li>
           					<li  style="width:325px; text-align:center;"><span><?php echo isset($journalinfo->journal_particular_name)? $journalinfo->journal_particular_name:'';?></span><br/>
								 	<i style="margin-left:10px; font-size:12px"><?php echo !empty($journalinfo->journal_particular_naration)? '('.$journalinfo->journal_particular_naration.')':'';?></i></li>
				        	<li  style="width:135px;"></li>
				        	<li  style="width:135px;text-align:center;font-weight:bold;"><?php echo isset($journalinfo->journal_particular_amount)? number_format($journalinfo->journal_particular_amount,2,'.',''):'';?></li>
				        </ul>
       			<?php	}

       			}
       				
       		}

       	?>            
      </div>
      <div class="total_block">
      	<ul>
      		<li style="width:425px;text-align:center;font-weight:bold;">Total</li>
      		<li style="width:135px;text-align:center;font-weight:bold;">Tk <?php echo isset($total_debit) ? number_format($total_debit,2,'.',''):'';?></li>
      		<li style="width:135px;text-align:center;font-weight:bold;">Tk <?php echo isset($total_credit) ? number_format($total_credit,2,'.',''):'';?></li>
      	</ul>
      </div>
      <div class="amounts_in_word">
      	<p><strong>Amount In words:</strong>
      	<?php
      		if(is_numeric($total_debit)){
      			$amount_words = \App\System::ConvertNumberToWords($total_debit);

      			if(!empty($amount_words))
      				echo ucwords("taka ".$amount_words.' only');
      		}


      	 ?></p>
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