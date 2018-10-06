@extends('layout.master')
@section('content')
<!--error message*******************************************-->
<div class="row">
	<div class="col-md-6">
		@if($errors->count() > 0 )

		<div class="alert alert-danger">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<h6>The following errors have occurred:</h6>
			<ul>
				@foreach( $errors->all() as $message )
				<li>{{ $message }}</li>
				@endforeach
			</ul>
		</div>
		@endif

		@if(Session::has('message'))
		<div class="alert alert-success" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('message') }}
		</div> 
		@endif

		@if(Session::has('errormessage'))
		<div class="alert alert-danger" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('errormessage') }}
		</div>
		@endif

	</div>
</div>
<!--end of error message*************************************-->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-external-link-square"></i>
				Receipt
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"> </a>
					<a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group supplier_select">
							<select class="form-control customer_account_id" name="customer_account_id" required>
								<option value="">Choose a Customer</option>
								@if(isset($customer_list) && (count($customer_list)>0))
									@foreach($customer_list as $key => $customer)
									<option {{(isset($customer_info->customer_account_id) && $customer_info->customer_account_id==$customer->customer_account_id)? 'selected':''}}  data-companynameslug="{{isset($customer->customer_company_slug)? $customer->customer_company_slug:''}}" data-customerid="{{isset($customer->customer_id)? $customer->customer_id:''}}" value="{{isset($customer->customer_account_id)? $customer->customer_account_id:''}}">{{isset($customer->customer_company)? $customer->customer_company:''}}</option>
									@endforeach
								@endif
								
							</select>
						</div>
						
						<div class="form-group">
							<label>Billing Address</label>
							<textarea class="form-control customer_address" name="customer_address" cols="7" rows="3">{{(isset($customer_info->customer_address) && !empty($customer_info->customer_address))? $customer_info->customer_address:''}}</textarea>
						</div>
					</div>

					<div class="col-md-8">
						<div class="panel panel-info">
							<div class="panel-heading">
								<i class="fa fa-external-link-square"></i>
								Order Details Info
								<div class="panel-tools">
									<a class="btn btn-xs btn-link panel-collapse collapses" href="#"> </a>
									<a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
								</div>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="supplier_credit">
										<table class="table table-hover table-bordered table-striped nopadding text-center">  
											<thead>
												<tr style="background-color: #d6e1d6;">
													<th>#</th>
													<th>Date</th>
													<th>Description</th>
													<th>Delivery Date</th>
													<th>Order Amount</th>
													<th>Order Balance</th>
													<th>Status</th>
												</tr>
											</thead>
											<tbody>
												@if(isset($customer_order_info) && count($customer_order_info)>0)
													@foreach($customer_order_info as $key => $order_info)
													</tr>
														<td>{{$key+1}}</td>
														<td>{{$order_info->order_date}}</td>
														<td>{{$order_info->order_description}}</td>
														<td>{{$order_info->order_delivery_date}}</td>
														<td>{{(isset($order_info->order_delivery_net_amount) && !empty($order_info->order_delivery_net_amount))? $order_info->order_delivery_net_amount:0.0}}</td>
														<td>{{(isset($order_info->order_delivery_balance_amount) && !empty($order_info->order_delivery_balance_amount))? $order_info->order_delivery_balance_amount:0.0}}
														</td>
														<td>
															@if((isset($order_info->order_delivery_balance_amount) && ($order_info->order_delivery_balance_amount !=0)))
																<button class="btn btn-green btn-xs customer_payment customer_payment_row_{{$order_info->order_id}}" data-id="{{$order_info->order_id}}" ><i class="fa fa-plus"></i> Add Payment</button>
															@else
																PAID
															@endif
														</td>
													</tr>
													@endforeach

												@else
													<tr>
														<th colspan="7">No data available</th>
													</tr>
												@endif
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div><!--End Supplier transaction-->
					<div class="col-md-12">
						<div class="panel panel-info">
							<div class="panel-heading">
								<i class="fa fa-external-link-square"></i>
								Order Credit Info
								<div class="panel-tools">
									<a class="btn btn-xs btn-link panel-collapse collapses" href="#"> </a>
									<a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
								</div>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="supplier_inventory">
										<table class="table table-hover table-bordered table-striped nopadding text-center">
											
											<thead>
												<tr>
													<th colspan="5">Customer Accounts Details</th>
												</tr>
												<tr>
													<th>Company</th>
													<th>Address</th>
													<th>Debit</th>
													<th>Credit</th>
													<th>Balance</th>
												</tr>
											</thead>
											<tbody>
												@if(isset($customer_info) && !empty($customer_info))
													<tr>
														<td>{{(isset($customer_info->customer_company) && !empty($customer_info->customer_company))? $customer_info->customer_company:''}}</td>
														<td>{{(isset($customer_info->customer_address) && !empty($customer_info->customer_address))? $customer_info->customer_address:''}}</td>
														<td>{{(isset($customer_info->customer_net_debit_amount) && !empty($customer_info->customer_net_debit_amount))? $customer_info->customer_net_debit_amount:0.0}}</td>
														<td>{{(isset($customer_info->customer_net_credit_amount) && !empty($customer_info->customer_net_credit_amount))? $customer_info->customer_net_credit_amount:0.0}}</td>

														<td>{{(isset($customer_info->customer_net_balance_amount) && !empty($customer_info->customer_net_balance_amount))? $customer_info->customer_net_balance_amount:0.0}}</td>
														
													</tr>
												@else
													<tr>
														<td colspan="5">No data available</td>
													</tr>
												@endif
											</tbody>
										</table>
										<table class="table table-hover table-bordered table-striped nopadding text-center">
											<thead>
												<tr>
													<th colspan="7">Order Transactions</th>
												</tr>
												<tr>
													<th>Date</th>
													<th>Company</th>
													<th>Order No.</th>
													<th>Amount</th>
													<th>Debit</th>
													<th>Credit</th>
													<th>Balance</th>
												</tr>
											</thead>
											<tbody>
												@if(isset($customer_credit_transactions) && count($customer_credit_transactions)>0)
													@foreach($customer_credit_transactions as $key => $credit_transactions)
														<tr>
															<td>{{(isset($credit_transactions->transaction_date) && !empty($credit_transactions->transaction_date)) ? $credit_transactions->transaction_date:''}}</td>

															<td>{{(isset($credit_transactions->customer_company) && !empty($credit_transactions->customer_company)) ? $credit_transactions->customer_company:''}}</td>
															<td>{{(isset($credit_transactions->order_id) && !empty($credit_transactions->order_id)) ? $credit_transactions->order_id:''}}</td>

															<td>{{(isset($credit_transactions->transaction_amount) && !empty($credit_transactions->transaction_amount)) ? $credit_transactions->transaction_amount:''}}</td>

															<td>{{(isset($credit_transactions->closing_customer_debit_amount) && !empty($credit_transactions->closing_customer_debit_amount)) ? $credit_transactions->closing_customer_debit_amount:0}}</td>

															<td>{{(isset($credit_transactions->closing_customer_credit_amount) && !empty($credit_transactions->closing_customer_credit_amount)) ? $credit_transactions->closing_customer_credit_amount:0}}</td>
															
															<td>{{(isset($credit_transactions->closing_customer_balance_amount) && !empty($credit_transactions->closing_customer_balance_amount)) ? $credit_transactions->closing_customer_balance_amount:0}}</td>
															
														</tr>
													@endforeach
														<tr>
															<th colspan="7" >Balance</th>
															<th >
																{{(isset($credit_transactions->customer_net_balance_amount) && !empty($credit_transactions->customer_net_balance_amount)) ? $credit_transactions->customer_net_balance_amount:''}}
															</th>
														</tr>

												@else
													<tr>
														<td colspan="6">No data available</td>
													</tr>
												@endif
											</tbody>
										</table>
									</div><!--End Supplier transaction-->
								</div><!--End Supplier credit panel body row-->
							</div><!--End Supplier credit panel body -->
						</div><!--End Supplier credit panel -->
					</div><!--End Supplier credit row-->
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="clip-users-2"></i>
								Payment Transactions
								<div class="panel-tools">
									<a class="btn btn-xs btn-link panel-collapse collapses" data-toggle="tooltip" data-placement="top" title="Show / Hide" href="#">
									</a>
									<a class="btn btn-xs btn-link panel-close red-tooltip" data-toggle="tooltip" data-placement="top" title="Close" href="#">
										<i class="fa fa-times"></i>
									</a>
								</div>
							</div>
							<div class="panel-body">
								<div class="row">
									<form class="supplier_select_form" action="{{url('/customer/payment')}}" method="post">
										<input type="hidden" name="_token" value="{{csrf_token()}}">
										<div class="col-md-3">
											<div class="form-group">
												<label>Payment Method</label>
												<select class="form-control customer_payment_method" name="customer_payment_method" required="">
													<option>Choose Payment Method</option>
													<option value="cash">Cash</option>
													<option value="bank">Bank</option>
												</select>
											</div>
											<div class="form-group">
												<label>Payment Account</label>
												<select class="form-control customer_paid_account" name="customer_paid_account" required="">
													<option>Choose Account</option>
												</select>
											</div>

											<div class="">
												<label class="">Payment Date</label>
												<div class="input-group">
													<input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="customer_payment_date" data-link-field="form_dtp_input" value="<?php echo date('Y-m-d');?>">
													<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
												</div>
											</div>

											<div class="form-group ">
												<label>Pay Note</label>
												<textarea class="form-control" name="customer_pay_note" cols="6" rows="3" required></textarea>
											</div>
											
										<input type="hidden" class="payment_entry_field" name="payment_entry_field" value="0">

										</div>
										<div class="col-md-9">
											<table class="table  table-hover table-bordered table-striped nopadding" >
												<thead>
													<tr>
														<th>Order ID</th>
														<th>Order Date</th>
														<th>Amount</th>	
														<th>Order Credit Amount</th>
														<th>Paid Amount</th>
														<th></th>
													</tr>
												</thead>
												<tbody class="payment_entry_body">
													<tr>
														<td colspan="6">No bills to pay</td>
													</tr>
												</tbody>
												<tfoot>
													<tr>
														<td  class="text-center" colspan="4">Total Amount</td>
														<td><input type="text" class="form-control total_oreder_paid_amount" name="total_oreder_paid_amount" value=""></td>
														<td></td>
													</tr>
												</tfoot>
											</table>
											<input type="hidden" name="customer_payment_account_id" value="{{isset($customer_info->customer_account_id)? $customer_info->customer_account_id.'-'.$customer_info->customer_company:''}}">
											
											<div class="pull-right">
												<a class="btn btn-default" href="{{url('/customer/payment')}}">Cancel Payment</a>
												<input type="submit" class="btn btn-info" value="Submit Payment"/>
											</div>
										</div>
											
										

									</form>
								</div>
							</div><!--End Payment Transaction panel body -->
						</div><!--End Payment Transaction panel-->
					</div><!--End Payment field row-->

				</div><!--End Payment row-->
			</div><!--End Panel Body-->
		</div><!--End Panel-->
	</div><!--End panel 12-->
</div><!--End row-->
@stop