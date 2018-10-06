@extends('layout.master')
@section('content')
<div class="invoice purchase_invoce">
	<div class="row invoice-logo">
		<?php $company_info = \DB::table('company_details')->where('company_name','D. F Tex')->first();?>
		<div class="col-sm-6">
			<img alt="" src="{{(isset($company_info->company_logo) && !empty($company_info->company_logo)) ? asset($company_info->company_logo):''}}" title="{{(isset($company_info->company_name) && !empty($company_info->company_name)) ? $company_info->company_name:'Company Logo'}}" alt="{{(isset($company_info->company_name) && !empty($company_info->company_name)) ? $company_info->company_name:'Company Logo'}}">
		</div>
		<div class="col-sm-6">
			<p>
				<strong>{{(isset($company_info->company_title) && !empty($company_info->company_title)) ? $company_info->company_title:'Company Title'}}</strong><span> {{(isset($company_info->company_address) && !empty($company_info->company_address)) ? $company_info->company_address:'Company Address'}}</span>
			</p>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-4">
			<h4>Supplier:</h4>
			<div class="well">
				<address>
					<strong>{{(isset($supplier_info->supplier_company) && !empty($supplier_info->supplier_company)) ? $supplier_info->supplier_company:'Supplier Company'}}</strong>
					<br>
					{{(isset($supplier_info->supplier_name) && !empty($supplier_info->supplier_name)) ? $supplier_info->supplier_name:'Supplier Name'}}
					<br>
					{{(isset($supplier_info->supplier_address) && !empty($supplier_info->supplier_address)) ? $supplier_info->supplier_address:'Supplier Address'}}
					<br>
					<abbr title="Phone">M: </abbr>{{(isset($supplier_info->supplier_mobile) && !empty($supplier_info->supplier_mobile)) ? $supplier_info->supplier_mobile:'Supplier Mobile'}}
					<br>
					<strong>E-mail</strong>
					<br>
					<a href="mailto:{{(isset($supplier_info->supplier_email) && !empty($supplier_info->supplier_email)) ? $supplier_info->supplier_email:'#'}}">
						{{(isset($supplier_info->supplier_email) && !empty($supplier_info->supplier_email)) ? $supplier_info->supplier_email:'Supplier Email'}}
					</a>
				</address>
			</div>
		</div>
		<div class="col-md-6 pull-right">
			<div class="col-md-12 text-right">
				<ul class="list-unstyled invoice-details ">
					<li>
						<strong>Date :</strong> {{date('d-M-Y')}}
					</li>
					<li>
						<strong>Invoice #:</strong> <?php echo isset($all_stocks_transaction->stocks_transaction_date) ? date('Ymd',strtotime($all_stocks_transaction->stocks_transaction_date)):'';?><?php echo isset($all_stocks_transaction->stocks_transactions_id) ? $all_stocks_transaction->stocks_transactions_id:'';?>
					</li>
					
				</ul>
				
				<div class="btn-group">
					<a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
						Download <span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li role="presentation">
							<a  tabindex="-1" role="menuitem" href="{{url('/supplier/purchase/return/invoice/download/pdf/stocks-tran-'.$all_stocks_transaction->stocks_transactions_id)}}" class=" ">
								PDF
							</a>
						</li>
						<li role="presentation">
							<a tabindex="-1" role="menuitem" href="{{url('/supplier/purchase/return/invoice/print/stocks-tran-'.$all_stocks_transaction->stocks_transactions_id)}}" class=" ">
								Excelsheet
							</a>
						</li>
						
					</ul>
				</div>
				<a target="_blank" href="{{url('/supplier/purchase/return/invoice/print/stocks-tran-'.$all_stocks_transaction->stocks_transactions_id)}}" class="btn  btn-purple">
					Print <i class="fa fa-print"></i>
				</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 purchase_invoice_tbl">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>SL</th>
						<th>Particulars</th>
						<th>Quantity</th>
						<th>Rate Per Unit (Tk)</th>
						<th>Amount (Tk)</th>
					</tr>
				</thead>
				<tbody class="">
					@if(isset($all_stocks_transaction) && !empty($all_stocks_transaction))
			
							<tr>
								<td>1</td>
								<td>{{$all_stocks_transaction->item_name}}</td>
								<td>{{$all_stocks_transaction->transaction_stocks_quantity}}</td>
								<td>{{number_format($all_stocks_transaction->stocks_quantity_rate,2,'.','')}}</td>
								<td>{{number_format($all_stocks_transaction->stocks_quantity_cost,2,'.','')}}</td>
							</tr>
						
						<tr>
							<th colspan="4">Grand Total</th>
							<th class="text-center">{{number_format($all_stocks_transaction->stocks_quantity_cost,2,'.','')}}</th>
						</tr>
					@else
						<tr>
							<td colspan="5" class="text-center">No Data Available</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
</div><!--End Invoice-->


@stop