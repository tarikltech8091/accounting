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
			<h4>Customer:</h4>
			<div class="well">
				<address>
					<strong>{{(isset($customer_info->customer_company) && !empty($customer_info->customer_company)) ? $customer_info->customer_company:'Customer Company'}}</strong>
					<br>
					{{(isset($customer_info->customer_name) && !empty($customer_info->customer_name)) ? $customer_info->customer_name:'Customer Name'}}
					<br>
					{{(isset($customer_info->customer_address) && !empty($customer_info->customer_address)) ? $customer_info->customer_address:'Customer Address'}}
					<br>
					<abbr title="Phone">M: </abbr>{{(isset($customer_info->	customer_mobile) && !empty($customer_info->	customer_mobile)) ? $customer_info->	customer_mobile:'Customer Mobile'}}
					<br>
					<strong>E-mail</strong>
					<br>
					<a href="mailto:{{(isset($customer_info->customer_email) && !empty($customer_info->customer_email)) ? $customer_info->customer_email:'#'}}">
						{{(isset($customer_info->customer_email) && !empty($customer_info->customer_email)) ? $customer_info->customer_email:'Customer Email'}}
					</a>
				</address>
			</div>
		</div>
		<div class="col-md-6 pull-right">
			
			<div class="col-md-12 text-right">
			<h4>Payment Details:</h4>
				<ul class="list-unstyled invoice-details">

					<li>
						<strong>Date :</strong>{{date('d-M-Y',strtotime($customer_payment_date))}}
					</li>
					<li>

					</li>
					
				</ul>
				
				<div class="btn-group">
					<a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
						Download <span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li role="presentation">
							<a target="_blank" tabindex="-1" role="menuitem" href="{{url('/customer/payment/voucher/pdf')}}" class=" ">
								PDF
							</a>
						</li>
						<li role="presentation">
							<a tabindex="-1" role="menuitem" href="#" class=" ">
								Excelsheet
							</a>
						</li>
						
					</ul>
				</div>
				<a target="_blank" href="{{url('/customer/payment/voucher/print')}}" class="btn  btn-purple">
					Print <i class="fa fa-print"></i>				
				</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 purchase_invoice_tbl">
			<table class="table table-hover table-borderd table-striped">
				<thead>
					<tr>
						<th>Particulars</th>
						<th class="text-right">Amount (Tk)</th>
					</tr>
				</thead>
				<tbody>
					@if(isset($payment_account_info) && !empty($payment_account_info))
						
						<tr>
							<td><strong>Account:</strong><br>
								<span>{{ (isset($payment_account_info) && !empty($payment_account_info)) ? $payment_account_info->ledger_name :''}}</span>
							</td>
							<td class="text-center">{{$total_debit_amount}}</td>
						</tr>
						
					@else
						<tr>
							<td colspan="2" class="text-center">No Data Available</td>
						</tr>
					@endif
				</tbody>
				<tfoot>
					@if(isset($payment_account_info) && !empty($payment_account_info))
					
						<tr>
							<td><strong>Through:</strong><br>
								<span>{{ (isset($payment_account_info) && !empty($payment_account_info)) ? $payment_account_info->ledger_name :''}}</span>
							</td>
							<td></td>
						</tr>
						<tr>
							<td><strong>On Account of:</strong><br>
								<span>{{ (isset($customer_pay_note) && !empty($customer_pay_note)) ? $customer_pay_note:''}}</span>
							</td>
							<td></td>
						</tr>
						<tr>
							<th>Total</th>
							<th class="text-right">{{isset($total_debit_amount) ? $total_debit_amount :''}}</th>
						</tr>
					@endif
				</tfoot>

			</table>


		</div>
		
	</div>
</div><!--End Invoice-->

@stop