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
				Stocks Purcahse
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"> </a>
					<a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
				</div>
			</div>
			<div class="panel-body">
			<form method="post" action="{{url('/inventory/purchase/invoice')}}">
				<div class="row">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="col-md-3">
						<div class="form-group supplier_select">
							<select class="form-control" name="supplier_id" required>
								<option value="">Choose a supplier</option>
								@if(isset($supplier_list) && (count($supplier_list)>0))
									@foreach($supplier_list as $key => $supplier)
									<option data-depth="{{isset($supplier->depth)? $supplier->depth:''}}" 
									data-parent="{{isset($supplier->ledger_group_parent_id)? $supplier->ledger_group_parent_id:''}}" data-slug="{{isset($supplier->ledger_name_slug)? $supplier->ledger_name_slug:''}}" value="{{isset($supplier->ledger_id)? $supplier->ledger_id.'.'.$supplier->depth.'.'.$supplier->ledger_name_slug.'.'.$supplier->ledger_name:''}}">{{isset($supplier->ledger_name)? $supplier->ledger_name:''}}</option>
									@endforeach
								@endif
							</select>
						</div>
					</div>
					@if((\Auth::user()->user_role)== 'admin')
					<div class="col-md-2">
						<a class="btn btn-success" href="#panel-supplier" data-toggle="modal"><i class="fa fa-plus"></i>New Supplier</a>
					</div>
					@endif
					<div class="col-md-4 pull-right">
						<label class="col-md-6 text-right">Entry Date</label>
						<div class="input-group">
							<input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="stocks_purchase_date" data-link-field="form_dtp_input" value="<?php echo date('Y-m-d');?>">
							<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
							<input type="hidden" id="form_dtp_input" value="" />
						</div>
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
				</div>
		

				<div class="table-responsive"><!--end of Stockes table-->
					<table class="table stocks_entry table-hover table-bordered table-striped nopadding" >
						<thead>
							<tr>
								<th>#</th>	
								<th>Product</th>
								<!-- <th>Stock Account</th> -->
								<th>Quantity</th>
								<th>Rate</th>
								<th>Amount</th>
								<th></th>
							</tr>
						</thead>
						<tbody class="stocks_entry_body">
							
							@for($i=1;$i<=1;$i++)
								<tr class="stocks_entry_group_{{$i}}">
									<td>{{$i}}</td>
									<td class="inventory_stocks_td" data-rowid="{{$i}}">
										<select data-rowid="{{$i}}" class="form-control inventory_stocks inventory_stocks_row_{{$i}}" name="inventory_stocks_id_{{$i}}" required>
											<option value="0">Choose a product</option>
											@if(isset($inventory_stocks_list) && (count($inventory_stocks_list) > 0))
												@foreach($inventory_stocks_list as $key => $stocks)
												<option value="{{$stocks->inventory_stock_id}}">{{$stocks->item_name}}</option>
												@endforeach
											@else
												<option>Add Product in Stock</option>
											@endif
										</select>
									</td>

									<!-- <td class="stocks_account_td" data-rowid="{{$i}}">
										<select data-rowid="{{$i}}" class="form-control stocks_account stocks_account_row_{{$i}}" name="stocks_account_id_{{$i}}" required>
											<option value="">Choose a Stock Account</option>
											@if(isset($inventory_stocks_account) && (count($inventory_stocks_account)>0))
												@foreach($inventory_stocks_account as $key => $stocks_account)
												<option data-depth="{{isset($stocks_account->depth)? $stocks_account->depth:''}}" 
												data-parent="{{isset($stocks_account->ledger_group_parent_id)? $stocks_account->ledger_group_parent_id:''}}" data-slug="{{isset($stocks_account->ledger_name_slug)? $stocks_account->ledger_name_slug:''}}" value="{{isset($stocks_account->ledger_id)? $stocks_account->ledger_id.'.'.$stocks_account->depth.'.'.$stocks_account->ledger_name_slug.'.'.$stocks_account->ledger_name :''}}">{{isset($stocks_account->ledger_name)? $stocks_account->ledger_name:''}}</option>
												@endforeach
											@else
												<option>Create Stock Account</option>
											@endif
										</select>
									</td> -->

									
									<td><input data-rowid="{{$i}}" type="text" class="form-control transaction_stocks_quantity transaction_stocks_quantity_row_{{$i}}" name="transaction_stocks_quantity_{{$i}}" value="" required /> </td>
									
									<td><input data-rowid="{{$i}}" type="text" class="form-control stocks_quantity_rate stocks_quantity_rate_row_{{$i}}" name="stocks_quantity_rate_{{$i}}" value="" required /> </td>
									
									<td><input type="text" data-rowid="{{$i}}" class="form-control stocks_quantity_cost stocks_quantity_cost_row_{{$i}}" name="stocks_quantity_cost_{{$i}}" value="" required /></td>
									

									<td>
										<!-- <a data-rowid="{{$i}}"  class="btn btn-xs btn-purple tooltips stocks_clear stocks_clear_row_{{$i}}" data-toggle1="tooltip" title="Clear Data" data-original-title="Clear Data"><i class="fa fa-times" aria-hidden="true"></i></a> -->
									</td>
								</tr>
							@endfor
						</tbody>
					</table>
					<input type="hidden" class="stocks_entry_field" name="stocks_entry_field" value="1">
				</div><!--end of Stockes table-->
				<div class="row">
					<div class="col-md-12 form-group">
						<button class="btn btn-default add_line_stocks">Add line</button>
						
					</div>
					<div class="col-md-6 form-group">
						<label>Transaction Description</label>
						<textarea class="form-control" name="purchase_desc" rows="3" cols="5" required></textarea>
					</div>
					<div class="col-md-12">
						<a href="{{\Request::fullUrl()}}" class="btn btn-default pull-right">Cancel</a>	
						<input  type="submit" class="btn btn-info pull-right" name="stocks_entry" value="Save">
					</div>
					
				</div>
				
			</div>
			</form>
		</div>

	</div>
</div>








<!--Start: Supplier Modal-->
<div class="modal fade" id="panel-supplier" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">Supplier Registration</h4>
			</div>
			<div class="modal-body supplier_modal">
				<div class="row">
					<div class="col-md-12">
					<form method="post" action="{{url('/supplier/add')}}" role="form" class="form-horizontal">
						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Account Group
							</label>
							<div class="col-md-9">
								<select class="supplier_account_group form-control" name="supplier_account_group" required>
									<option>select an account</option>
									@if(isset($account_payable) && count($account_payable)>0)
										@foreach($account_payable as $key => $accounts)
											<option data-depth="{{isset($accounts->depth)? $accounts->depth:''}}" 
									data-parent="{{isset($accounts->ledger_group_parent_id)? $accounts->ledger_group_parent_id:''}}" data-slug="{{isset($accounts->ledger_name_slug)? $accounts->ledger_name_slug:''}}" value="{{isset($accounts->ledger_id)? $accounts->ledger_id:''}}">{{isset($accounts->ledger_name)? $accounts->ledger_name:''}}</option>
										@endforeach
									@else
										<option>Create Account Payable Group</option>
									@endif
								</select>
							</div>
							<input type="hidden" name="supplier_account_group_depth" class="supplier_account_group_depth" value="">
						</div>
						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Company
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="supplier_company" value="" required>
							</div>
						</div>
						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								<!-- Supplier Name -->
								Contact Person
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="supplier_name" value="" required>
							</div>
						</div>



						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Mobile
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="supplier_mobile" value="" required>
							</div>
						</div>

						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Email
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="supplier_email" value="" required>
							</div>
						</div>

						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Tax Reg No
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="supplier_tax_reg_no" value="">
							</div>
						</div>


						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Address
							</label>
							<div class="col-md-9">
								<textarea name="supplier_address" class="form-control" cols="20" rows="6" required></textarea>
							</div>
						</div>
						<input type="hidden" name="back_page" value="{{\Request::fullUrl()}}">

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
				<!-- <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">
						Close
					</button>
				</div> -->
			</div>
			
		</div>
	</div>
</div>
<!--End: Supplier Modal-->

<!--Start: Supplier Modal-->
<div class="modal fade" id="panel-stock-account" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">Create Stock Account</h4>
			</div>
			<div class="modal-body stock_account_modal">
				<div class="row">
					<div class="col-md-12">
					<form method="post" action="{{url('/inventory/ledger/new-account')}}" role="form" class="form-horizontal">
						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Account Group
							</label>
							<div class="col-md-9">
								<select class="stock_in_hand_group form-control" name="stock_in_hand_group" required>
									<option>select an account</option>
									@if(isset($account_stock_in_hand) && count($account_stock_in_hand)>0)
										@foreach($account_stock_in_hand as $key => $accounts)
											<option data-depth="{{isset($accounts->depth)? $accounts->depth:''}}" 
									data-parent="{{isset($accounts->ledger_group_parent_id)? $accounts->ledger_group_parent_id:''}}" data-slug="{{isset($accounts->ledger_name_slug)? $accounts->ledger_name_slug:''}}" value="{{isset($accounts->ledger_id)? $accounts->ledger_id:''}}">{{isset($accounts->ledger_name)? $accounts->ledger_name:''}}</option>
										@endforeach
									@else
										<option>Create Account Stock-in-hand Group</option>
									@endif
								</select>
							</div>
							<input type="hidden" name="account_stock_in_hand_group_depth" class="account_stock_in_hand_group_depth" value="">
						</div>
						<div class="form-group">
							<label for="stock_account_name" class="col-md-3">
								Stocks Name
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="stock_account_name" value="" required>
							</div>
						</div>
						<div class="form-group">
							<label for="stock_debit_amount" class="col-md-3">
								Debit Amount
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control"  placeholder="0.0" name="stock_debit_amount" value="" >
							</div>
						</div>
						<div class="form-group">
							<label for="stock_credit_amount" class="col-md-3">
								Credit Amount
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" placeholder="0.0" name="stock_credit_amount" value="" >
							</div>
						</div>
						<input type="hidden" name="back_page" value="{{\Request::fullUrl()}}">

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
				<!-- <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">
						Close
					</button>
				</div> -->
			</div>
			
		</div>
	</div>
</div>
<!--End: Supplier Modal-->

@stop