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
				Purchase Return
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"> </a>
					<a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group supplier_select">
							<select class="form-control return_supplier_account_id" name="supplier_account_id" required>
								<option value="">Choose a Supplier</option>
								@if(isset($supplier_list) && (count($supplier_list)>0))
									@foreach($supplier_list as $key => $supplier)
									<option {{(isset($supplier_info->supplier_account_id) && $supplier_info->supplier_account_id==$supplier->supplier_account_id)? 'selected':''}}  data-companynameslug="{{isset($supplier->supplier_company_slug)? $supplier->supplier_company_slug:''}}" data-supplierid="{{isset($supplier->supplier_id)? $supplier->supplier_id:''}}" value="{{isset($supplier->supplier_account_id)? $supplier->supplier_account_id:''}}">{{isset($supplier->supplier_company)? $supplier->supplier_company:''}}</option>
									@endforeach
								@endif
								
							</select>
						</div>
						
						<div class="form-group">
							<label>Billing Address</label>
							<textarea class="form-control supplier_address" name="supplier_address" cols="7" rows="3">{{(isset($supplier_info->supplier_address) && !empty($supplier_info->supplier_address))? $supplier_info->supplier_address:''}}</textarea>
						</div>
						
					</div>

					<div class="col-md-9">
						<div class="panel panel-info">
							<div class="panel-heading">
								<i class="fa fa-external-link-square"></i>
								Supplied Inventory
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
												
												<tr>
													<th>#</th>
													<th>Product</th>
													<th>Description</th>
													<th>Quantity</th>
													<th>Rate</th>
													<th>Amount (Tk.)</th>
													<th>Balance</th>
													<th>Payment</th>
												</tr>
											</thead>
											<tbody>
											
												@if(isset($supplier_inventory_transactions) && count($supplier_inventory_transactions) >0)
													@php ($total_stocks_amount = 0)
													@foreach($supplier_inventory_transactions as $key => $inventory_transactions)
													<tr>
														<td>{{($key+1)}}</td>
														<td>{{(isset($inventory_transactions->item_name) && !empty($inventory_transactions->item_name)) ? $inventory_transactions->item_name:''}}</td>
														<td>{{(isset($inventory_transactions->item_description) && !empty($inventory_transactions->item_description)) ? $inventory_transactions->item_description:''}}</td>
														<td>{{(isset($inventory_transactions->transaction_stocks_quantity) && !empty($inventory_transactions->transaction_stocks_quantity)) ? $inventory_transactions->transaction_stocks_quantity:''}}</td>
														<td>{{(isset($inventory_transactions->stocks_quantity_rate) && !empty($inventory_transactions->stocks_quantity_rate)) ? $inventory_transactions->stocks_quantity_rate:''}}</td>
														<td>{{(isset($inventory_transactions->stocks_quantity_cost) && !empty($inventory_transactions->stocks_quantity_cost)) ? $inventory_transactions->stocks_quantity_cost:''}}</td>
														
														<td >{{$inventory_transactions->stocks_supplier_balance_amount}}</td>
														<td>

														@php($parse_url =parse_url(\Request::fullUrl(), PHP_URL_QUERY))

														@if($inventory_transactions->stocks_supplier_balance_amount !=0)
																<a href="{{url('/supplier/purchase/return?stocks_transactions_id='.$inventory_transactions->stocks_transactions_id.'&'.$parse_url)}}" class="btn btn-purple btn-xs"><i class="fa fa-minus"></i> Purchase Return</a>

														@else
														PAID
														@endif

														</td>
													</tr>

														@php ($total_stocks_amount = $total_stocks_amount+$inventory_transactions->stocks_quantity_cost)
													@endforeach
													<!-- <tr>
														<th colspan="5" class="text-center">Total</th>
														<th>Tk. {{$total_stocks_amount}}</th>
													</tr> -->
												@else
													<tr>
														<td colspan="8" class="text-center">No data available</td>
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
								Supplied Credit Info
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
													<th colspan="5" class="text-center"><strong>Supplier Accounts Details</strong></th>
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
												@if(isset($supplier_info) && !empty($supplier_info))
													<tr>
														<td>{{(isset($supplier_info->supplier_company) && !empty($supplier_info->supplier_company))? $supplier_info->supplier_company:''}}</td>
														<td>{{(isset($supplier_info->supplier_address) && !empty($supplier_info->supplier_address))? $supplier_info->supplier_address:''}}</td>
														<td>{{(isset($supplier_info->supplier_net_debit_amount) && !empty($supplier_info->supplier_net_debit_amount))? $supplier_info->supplier_net_debit_amount:0.0}}</td>
														<td>{{(isset($supplier_info->supplier_net_credit_amount) && !empty($supplier_info->supplier_net_credit_amount))? $supplier_info->supplier_net_credit_amount:0.0}}</td>

														<td>{{(isset($supplier_info->supplier_net_balance_amount) && !empty($supplier_info->supplier_net_balance_amount))? $supplier_info->supplier_net_balance_amount:0.0}}</td>
														
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
													<th colspan="6" class="text-center"><strong>Supplier Transactions</strong></th>
												</tr>
												<tr>
													<th>Date</th>
													<th>Company</th>
													<th>Product</th>
													<th>Amount</th>
													<th>Debit</th>
													<th>Credit</th>
													<th>Balance</th>
												</tr>
											</thead>
											<tbody>
												@if(isset($supplier_credit_transactions) && count($supplier_credit_transactions)>0)
													@foreach($supplier_credit_transactions as $key => $credit_transactions)
														<tr>
															<td>{{(isset($credit_transactions->transaction_date) && !empty($credit_transactions->transaction_date)) ? $credit_transactions->transaction_date:''}}</td>

															<td>{{(isset($credit_transactions->supplier_company) && !empty($credit_transactions->supplier_company)) ? $credit_transactions->supplier_company:''}}</td>

															<td>{{(isset($credit_transactions->item_name) && !empty($credit_transactions->item_name)) ? $credit_transactions->item_name:''}}</td>

															<td>{{(isset($credit_transactions->transaction_amount) && !empty($credit_transactions->transaction_amount)) ? $credit_transactions->transaction_amount:''}}</td>

															<td>{{(isset($credit_transactions->closing_stocks_debit_amount) && !empty($credit_transactions->closing_stocks_debit_amount)) ? $credit_transactions->closing_stocks_debit_amount:0}}</td>

															<td>{{(isset($credit_transactions->closing_stocks_credit_amount) && !empty($credit_transactions->closing_stocks_credit_amount)) ? $credit_transactions->closing_stocks_credit_amount:0}}</td>
															
															<td>{{(isset($credit_transactions->closing_stocks_balance_amount) && !empty($credit_transactions->closing_stocks_balance_amount)) ? $credit_transactions->closing_stocks_balance_amount:0}}</td>
														</tr>
														@php($total_credit_balance=(isset($credit_transactions->closing_stocks_balance_amount) && !empty($credit_transactions->closing_stocks_balance_amount)) ? $credit_transactions->closing_stocks_balance_amount:0)
													@endforeach
														<tr>
															<th colspan="6" class="text-center">Balance</th>
															<th class="text-center">
																{{isset($total_credit_balance) ? $total_credit_balance:0}}
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
								Return Transactions 
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
									@php($parse_url =parse_url(\Request::fullUrl(), PHP_URL_QUERY))
									<form class="supplier_select_form" action="{{url('/supplier/purchase/return?'.$parse_url)}}" method="post">
										<input type="hidden" name="_token" value="{{csrf_token()}}">
										<div class="col-md-3">
											<div class="">
												<label class="">Return Date</label>
												<div class="input-group">
													<input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="supplier_return_date" data-link-field="form_dtp_input" value="<?php echo date('Y-m-d');?>">
													<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
												</div>
											</div>
											<div class="form-group ">
												<label>Return Note</label>
												<textarea class="form-control" name="supplier_return_note" cols="6" rows="3" required></textarea>
											</div>
										</div>


										<div class="col-md-9">
											<div class="table-responsive"><!--end of Stockes table-->
												<table class="table stocks_entry table-hover table-bordered table-striped nopadding" >
													<thead>
														<tr>
															<th>Tran ID #</th>	
															<th>Product</th>
															<th>Quantity</th>
															<th>Rate</th>
															<th>Amount</th>
														
														</tr>
													</thead>
													<tbody class="purchase_return_entry_body">
														@if(isset($inventory_transaction_info) && count($inventory_transaction_info)>0)
															<tr>
																<td>{{$inventory_transaction_info->stocks_transactions_id}}
																<input type="hidden" name="return_stocks_tran_id" value="{{$inventory_transaction_info->stocks_transactions_id}}" required>
																<input type="hidden" name="return_cost_center_id" value="{{$inventory_transaction_info->cost_center_id}}" required>
																<input type="hidden" name="return_stocks_account_id" value="{{$inventory_transaction_info->item_account_id}}" required>

																</td>
																<td>{{$inventory_transaction_info->item_name}}</td>
																<td>
																	<input type="text" class="form-control purchase_return_quantity purchase_return_quantity" name="return_product_quantity" value="{{$inventory_transaction_info->transaction_stocks_quantity}}" required>
																</td>
																<td>
																	<input type="text" class="form-control purchase_return_quantity_rate purchase_return_quantity" name="return_product_rate" value="{{$inventory_transaction_info->stocks_quantity_rate}}" required>
																</td>
																<td>
																	<input type="text" class="form-control purchase_return_quantity_cost" name="return_product_cost" value="{{$inventory_transaction_info->stocks_quantity_cost}}" required>
																</td>
															
															</tr>
														@endif
														<tr>
															<td colspan="5">No bills to pay. </td>
														</tr>
													</tbody>
												</table>
												
											</div>
											
											<input type="hidden" name="supplier_payment_account_id" value="{{isset($inventory_transaction_info->supplier_account_id)? $inventory_transaction_info->supplier_account_id.'.'.$inventory_transaction_info->supplier_id.'.'.$inventory_transaction_info->supplier_company:''}}">


											<div class="pull-right">
												<a class="btn btn-default" href="{{url('/supplier/purchase/return')}}">Cancel Payment</a>
												<input type="submit" class="btn btn-info" value="Return Payment"/>
											</div>
										</div>
									</form>
								</div>
							</div><!--End Payment Transaction panel body-->
						</div><!--End Payment Transaction panel-->
					</div><!--End Payment field row-->

				</div><!--End Payment row-->
			</div><!--End Panel Body-->
		</div><!--End Panel-->
	</div><!--End panel 12-->
</div><!--End row-->
@stop