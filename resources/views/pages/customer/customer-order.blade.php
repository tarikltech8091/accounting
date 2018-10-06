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
				<i class="clip-users-2"></i>
				Customer Orders
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" data-toggle="tooltip" data-placement="top" title="Show / Hide" href="#">
					</a>
					<a class="btn btn-xs btn-link panel-config" data-toggle="tooltip" data-placement="top" title="Add Account" href="#">
						<i class="clip-folder-plus"></i>
					</a>
					<a class="btn btn-xs btn-link panel-close red-tooltip" data-toggle="tooltip" data-placement="top" title="Close" href="#">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>
			<div class="panel-body">

						<form method="post" action="{{url('/customer/order')}}">
						<input type="hidden" name="_token" value="{{csrf_token()}}">

							<div class="row">
								<div class="col-md-3">

									<div class="form-group customer_select">
										<select class="form-control" name="order_customer_id" required>
											<option value="">Choose a Customer</option>
											@if(isset($customer_list) && (count($customer_list)>0))
												@foreach($customer_list as $key => $customer)
												<option data-depth="{{isset($customer->depth)? $customer->depth:''}}" 
												data-parent="{{isset($customer->ledger_group_parent_id)? $customer->ledger_group_parent_id:''}}" data-slug="{{isset($customer->ledger_name_slug)? $customer->ledger_name_slug:''}}" value="{{isset($customer->ledger_id)? $customer->ledger_id.'.'.$customer->depth :''}}">{{isset($customer->ledger_name)? $customer->ledger_name:''}}</option>
												@endforeach
											@endif
										</select>
									</div>


								</div>

								<div class="col-md-2">
									<a class="btn btn-success" href="#panel-customer" data-toggle="modal"><i class="fa fa-plus"></i>New Customer</a>
								</div>

								<div class="col-md-3">
									<div class="form-group ">
										<select class="form-control" name="cost_center_id" required>
											<option value="">Choose a Cost Center</option>
											@if(isset($cost_centers) && (count($cost_centers)>0))
												@foreach($cost_centers as $key => $center)
												<option value="{{$center->cost_center_id}}">{{$center->cost_center_name}}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>


								<div class="col-md-4 pull-right">
									<label class="col-md-4 text-right btn btn-default active"><strong> Order Date </strong></label>
									<div class="input-group">
										<input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="order_date" data-link-field="form_dtp_input" value="<?php echo date('Y-m-d');?>">
										<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
									
									</div>
								</div>
							</div>

							<div class="row">

								<div class="form-group col-md-3">
									<label class="text-right btn btn-default active">Order Description </label>
									<textarea class="form-control" name="order_description" cols="6" rows="3" required></textarea>
								</div>

								<div class="col-md-4 pull-right">
									<label class="col-md-4 text-right btn btn-default active"><strong> Deleviry Date </strong></label>
									<div class="input-group">
										<input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="order_delivery_date" data-link-field="form_dtp_input" value="<?php echo date('Y-m-d', strtotime('+1 month'));?>">
										<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
									</div>
								</div>
							</div><br>


							<div class="table-responsive"><!--end of Stockes table-->
							<table class="table stocks_entry table-hover table-bordered table-striped nopadding" >
								<thead>
									<tr>
										<th>SL</th>	
										<th>Product</th>
										<th>Quantity</th>
										<th>Quantity Type</th>
										<th>Rate</th>
										<th>Amount</th>
									</tr>
								</thead>
								<tbody class="sales_order_entry_body">
										
									@for($i=1;$i<=1;$i++)
										<tr class="sales_order_entry_group_{{$i}}">
											<td>{{$i}}</td>
											<td>
												<input data-rowid="{{$i}}" type="text" class="form-control order_quantity_name order_quantity_name_row_{{$i}}" name="order_quantity_name_{{$i}}" value="" placeholder="Name" required />
											</td>

											<td><input data-rowid="{{$i}}" type="text" class="form-control sales_order_quantity sales_order_quantity_row_{{$i}}" name="sales_order_quantity_{{$i}}" value="" placeholder="0" required /> </td>

											<td><input data-rowid="{{$i}}" type="text" class="form-control sales_order_quantity_type sales_order_quantity_type_row_{{$i}}" name="sales_order_quantity_type_{{$i}}" value="" placeholder="Kg/Pice" required /> </td>

											<td><input data-rowid="{{$i}}" type="text" class="form-control sales_order_quantity sales_order_rate_row_{{$i}}" name="sales_order_rate_{{$i}}" value="" placeholder="0" required /> </td>

											<td><input data-rowid="{{$i}}" type="text" class="form-control sales_order_amount order_quantity_cost_row_{{$i}}" name="sales_order_amount_{{$i}}" value="" placeholder="0.0" required /> </td>

										</tr>

									@endfor
								</tbody>
							</table>
								<input type="hidden" class="sales_order_entry_field" name="sales_order_entry_field" value="1">
							</div><!--end of Stockes table-->

							<div class="row">
								<div class="col-md-8 form-group">
									<button class="btn btn-default sales_add_line_orders pull-left">Add line</button>
									
								</div>

								<!-- <div class="col-md-4 pull-right">
									<label class="col-md-4 text-right btn btn-default active"><strong> Discount <i class="fa fa-arrow-right" aria-hidden="true"></i></strong></label>
									<div class="col-md-8 input-group pull-right">
										<input type="text" class="form-control text-center" placeholder="00.00" name="order_discount_rate" value="" />
									</div>
								</div> -->

								<div class="col-md-12 form-group">	
									<input  type="submit" class="btn btn-info pull-right" name="sales_order_entry" value="Save">
								</div>
								
							</div>
						</form>

					<div >
					
				</div>
			</div>
		</div>
	</div>
</div>





<div class="modal fade" id="panel-customer" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">Customer Registration</h4>
			</div>

			<div class="modal-body customer_model">
				<div class="row">
					<div class="col-md-12">
					<form method="post" action="{{url('/customer/registration')}}" role="form" class="form-horizontal">

						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Account Group
							</label>
							<div class="col-md-9">
								<select class="form-control customer_account_group" name="customer_account_group" required>
									<option>select an account</option>
									@if(isset($account_receivable) && count($account_receivable)>0)
										@foreach($account_receivable as $key => $accounts)
									<option data-depth="{{isset($accounts->depth)? $accounts->depth:''}}" 
									data-parent="{{isset($accounts->ledger_group_parent_id)? $accounts->ledger_group_parent_id:''}}" data-slug="{{isset($accounts->ledger_name_slug)? $accounts->ledger_name_slug:''}}" value="{{isset($accounts->ledger_id)? $accounts->ledger_id:''}}">{{isset($accounts->ledger_name)? $accounts->ledger_name:''}}</option>
										@endforeach
									@else
									<option>Create Account Receivable Group</option>
									@endif
								</select>
							</div>
							<input type="hidden" name="customer_account_group_depth" class="customer_account_group_depth" value="4">
						</div>



						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Company
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="customer_company" value="" required>
							</div>
						</div>

						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Customer Name
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="customer_name" value="" required>
							</div>
						</div>

						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Mobile
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="customer_mobile" value="" required>
							</div>
						</div>

						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Email
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="customer_email" value="" required>
							</div>
						</div>

						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Tax Reg No
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="customer_tax_reg_no" value="">
							</div>
						</div>


						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Address
							</label>
							<div class="col-md-9">
								<textarea name="customer_address" class="form-control" cols="20" rows="6" required></textarea>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<button type="button" class="btn btn-default" data-dismiss="modal">
									Close
								</button>
								<input type="submit" class="btn btn-success pull-right" name="submit" value="Submit">
							</div>
						</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@stop