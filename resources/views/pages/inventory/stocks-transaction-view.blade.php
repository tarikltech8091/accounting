@extends('layout.master')
@section('content')
<div class="invoice"><!--Start Invoice page-->
	<div class="row invoice-logo"><!--Start Invocie Header-->
		<div class="col-md-6">
			<img alt="" src="{{asset('assets/images/dfblack.png')}}">
		</div>
		<div class="col-md-6">
			
			<p><strong>D. F Tex</strong></br>13/2 West Panthpath,Dhaka 1207</p>
			
		</div>
	</div><!--End Invocie Header-->
	<hr>

	<div class="row"><!--Start Subppler Header-->
		<div class="col-sm-4">
			<h4><strong>Supplier Details</strong></h4>
			<!-- <div class="well"> -->
				<address>
					<strong>{{isset($inventory_stocks->supplier_name) ? $inventory_stocks->supplier_name:''}}</strong>
					<br>
					{{isset($inventory_stocks->supplier_company) ? $inventory_stocks->supplier_company:''}}
					<br>
					{{isset($inventory_stocks->supplier_address) ? $inventory_stocks->supplier_address:''}}
					<br><abbr title="Phone">Mb:</abbr>
					{{isset($inventory_stocks->supplier_mobile) ? $inventory_stocks->supplier_mobile:''}}
				</address>
			<!-- </div> -->
		</div>
		<div class="col-sm-4 pull-right stck_transaction_no">
			
			<div class="">
				<address>
					<p><span>Date: {{isset($inventory_stocks->stocks_transaction_date) ? date('j M, Y',strtotime($inventory_stocks->stocks_transaction_date)):''}}</span></p> 
          			<p><span>Bill No: {{isset($inventory_stocks->stocks_transaction_date) ? date('Ymd',strtotime($inventory_stocks->stocks_transaction_date)):''}}{{isset($inventory_stocks->stocks_transactions_id) ? $inventory_stocks->stocks_transactions_id:''}}</span></p>
				</address>
			</div>
		</div>
	</div><!--End Subppler Header-->
<hr>
	<div class="row"><!--Start Item Deatails-->
		<div class="col-sm-12">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th> # </th>
						<th> Item </th>
						<th class="hidden-480"> Description </th>
						<th class="hidden-480"> Purpose </th>
						<th class="hidden-480"> Quantity </th>
						<th class="hidden-480"> Rate </th>
						<th> Total </th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td> 1 </td>
						<td>{{isset($inventory_stocks->item_name) ? $inventory_stocks->item_name:''}} </td>
						<td class="hidden-480"> {{isset($inventory_stocks->stocks_transaction_desc) ? $inventory_stocks->stocks_transaction_desc:''}} </td>
						<td class="hidden-480"> {{isset($inventory_stocks->cost_center_name) ? $inventory_stocks->cost_center_name:''}} </td>
						<td class="hidden-480"> {{isset($inventory_stocks->transaction_stocks_quantity) ? $inventory_stocks->transaction_stocks_quantity:''}} {{isset($inventory_stocks->item_quantity_unit) ? $inventory_stocks->item_quantity_unit:''}}</td>
						<td class="hidden-480"> Tk {{isset($inventory_stocks->stocks_quantity_rate) ? number_format($inventory_stocks->stocks_quantity_rate,2,'.','' ):''}}</td>
						<td> Tk {{isset($inventory_stocks->stocks_quantity_cost) ? number_format($inventory_stocks->stocks_quantity_cost,2,'.','' ):''}} </td>
					</tr>
				</tbody>
			</table>
		</div>
	</div><!--End Item Deatails-->

	<div class="row"><!--Start Item Deatails Footer-->
		<div class="col-sm-12 invoice-block">
			<ul class="list-unstyled amounts">
				
				<li>
					<strong>Total: Tk {{isset($inventory_stocks->stocks_quantity_cost) ? number_format($inventory_stocks->stocks_quantity_cost,2,'.','' ):''}} </strong>
				</li>
			</ul>
			<br>
			<a target="_blank" href="{{url('/inventory/stocks/trasansaction/'.$inventory_stocks->stocks_transactions_id.'/print')}}" class="btn btn-sm btn-teal hidden-print">
				Print <i class="fa fa-print"></i>
			</a>
			<a href="{{url('/inventory/stocks/trasansaction/'.$inventory_stocks->stocks_transactions_id.'/download')}}" class="btn btn-sm btn-green hidden-print">
				Download <i class="fa fa-download"></i>
			</a>
		</div>
	</div><!--End Item Deatails Footer-->

</div><!--End Invoice page-->
@stop