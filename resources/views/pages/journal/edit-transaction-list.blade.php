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
				Transaction Edit
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"> </a>
					<a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
				</div>
			</div>
			<div class="panel-body">
			<form method="post" action="{{url('/general/transaction-list/update')}}">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<div class="row">

					<div class="col-md-9">
						<div class="row">
						<div class="col-md-4">
							<label>Posting Type</label>
							<div class="form-group">
								<input type="text" class="form-control" name="" value=" {{$edit_ltech_transactions->posting_type}}" readonly="">
							</div>
						</div>


							<div class="col-md-4">
								<label>Amount</label>
								<div class="form-group">
									<input type="text" class="form-control" name="transaction_amount" value="{{$edit_ltech_transactions->transaction_amount}}">
								</div>
							</div>
							
							<div class="col-md-4">
								<label>Cost Center</label>
								<div class="form-group ">
									<select class="form-control" name="cost_center_id" required>
										<option value="">Choose a Cost Center</option>
										@if(isset($cost_centers) && (count($cost_centers)>0))
											@foreach($cost_centers as $key => $list)
												<option {{($edit_ltech_transactions->cost_center_id == $list->cost_center_id) ? "selected" :''}} value="{{$list->cost_center_id}}">{{$list->cost_center_name}}</option>
											@endforeach
										@endif
									</select>
								</div>
							</div>
						</div>

						@if($edit_ltech_transactions->posting_type == 'purchase')
						<div style="background-color: #777777;" class="text-center"><strong> Purchase Stocks Details </strong></div>
						<?php $purchase_info=\DB::table('ltech_inventory_stocks_transactions')->where('referrence', $edit_ltech_transactions->transactions_id)->get(); 
						foreach ($purchase_info as $key => $value) {
						 $i=$key+1; 
						?>
						<div class="row">
							<div class="col-md-4">
								<label>Stocks Quantity</label>
								<div class="form-group">
									<input type="hidden" class="form-control" name="stocks_tran_id_{{$i}}" value="{{$value->stocks_transactions_id}}">
									<input type="text" class="form-control" name="quantity_{{$i}}" value="{{$value->transaction_stocks_quantity}}">
								</div>
							</div>

							<div class="col-md-4">
								<label>Stocks Rate</label>
								<div class="form-group">
									<input type="text" class="form-control" name="quantity_rate_{{$i}}" value="{{$value->stocks_quantity_rate}}">
								</div>
							</div>

							<div class="col-md-4">
								<label>Stocks Cost</label>
								<div class="form-group">
									<input type="text" class="form-control" name="quantity_cost_{{$i}}" value="{{$value->stocks_quantity_cost}}">
								</div>
							</div>
						</div>
						<?php } ?>
						@endif
						@if($edit_ltech_transactions->posting_type == 'purchase_return')
						<div style="background-color: #777777;" class="text-center"><strong> Purchase Return Stocks </strong></div>
						<?php $purchase_info=\DB::table('ltech_inventory_stocks_transactions')->where('referrence', $edit_ltech_transactions->transactions_id)->first();
						?>
						<div class="row">
							<div class="col-md-4">
								<label>Stocks Quantity</label>
								<div class="form-group">
									<input type="hidden" class="form-control" name="stocks_tran_id" value="{{$purchase_info->stocks_transactions_id}}">
									<input type="text" class="form-control" name="quantity" value="{{$purchase_info->transaction_stocks_quantity}}">
								</div>
							</div>

							<div class="col-md-4">
								<label>Stocks Rate</label>
								<div class="form-group">
									<input type="text" class="form-control" name="quantity_rate" value="{{$purchase_info->stocks_quantity_rate}}">
								</div>
							</div>

							<div class="col-md-4">
								<label>Stocks Cost</label>
								<div class="form-group">
									<input type="text" class="form-control" name="quantity_cost" value="{{$purchase_info->stocks_quantity_cost}}">
								</div>
							</div>
						</div>

						@endif


						@if($edit_ltech_transactions->posting_type == 'sales' || $edit_ltech_transactions->posting_type == 'sales_return')
						<?php
							$journal_info=\DB::table('ltech_finish_goods_transactions')->where('referrence', $edit_ltech_transactions->transactions_id)->get();
						if(!empty($journal_info) && count($journal_info)>0){
						?>
						
          				<div style="background-color: #777777;" class="text-center"><strong> Finish Goods Transactions </strong></div>
          				
						<?php
							foreach ($journal_info as $key => $value) {
						 $i=$key+1; 
						?>
							
							<div class="row">
								<div class="col-md-4">
									<label> Transaction Quantity</label>
									<div class="form-group">
										<input type="text" class="form-control" name="transaction_item_quantity_{{$i}}" value="{{$value->transaction_finish_goods_quantity}}" required="">
										<input type="hidden" class="form-control" name="finish_goods_tran_id_{{$i}}" value="{{$value->ltech_finish_goods_transactions_id}}">

									</div>
								</div>

								<div class="col-md-4">
									<label>Transaction Item Rate</label>
									<div class="form-group">
										<input type="text" class="form-control" name="transaction_quantity_rate_{{$i}}" value="{{$value->finish_goods_quantity_rate}}" required="">
									</div>
								</div>


								<div class="col-md-4">
									<label>Transaction Item Cost</label>
									<div class="form-group">
										<input type="text" class="form-control" name="transaction_quantity_cost_{{$i}}" value="{{$value->finish_goods_quantity_cost}}" placeholder="" required="">
									</div>
								</div>
							</div>

						<?php
								}

							}

                            if(!empty($edit_ltech_transactions->posting_type == 'sales')){

								$sales_orders_info=\DB::table('ltech_sales_orders')
                                    ->where('sales_referrence',$edit_ltech_transactions->transactions_id)->first();
                            }

                            if(!empty($edit_ltech_transactions->posting_type == 'sales_return')){

                            	$sales_orders_meta=\DB::table('ltech_transaction_meta')
                                    ->where('transaction_id',$edit_ltech_transactions->transactions_id)
                                    ->where('field_name','ltech_sales_orders')
                                    ->first();

								$sales_orders_info=\DB::table('ltech_sales_orders')
                                    ->where('order_id',$sales_orders_meta->field_value)->first();
                                // $sales_orders_info=\DB::table('ltech_sales_orders')
                                //     ->where('sales_return_referrence',$edit_ltech_transactions->transactions_id)->first();

                            }

                    		$sales_order_details_info=\DB::table('ltech_sales_order_details')
                                    ->where('order_id',$sales_orders_info->order_id)->get();
						?>

						@if(!empty($sales_order_details_info) && count($sales_order_details_info)>0)
						<div style="background-color: #777777;" class="text-center"><strong> Sales Order Details </strong></div>
						@foreach($sales_order_details_info as $key => $list)
						<?php $i=$key+1; ?>

						<div class="row">
							<div class="to"><strong>  </strong></div>  
							<div class="col-md-4">
								<label>Sales Order Item </label>
								<div class="form-group">
									<input type="text" class="form-control" name="order_item_name_{{$i}}" value="{{$list->order_item_name}}">
									<input type="hidden" class="form-control" name="order_details_item_{{$i}}" value="{{$list->order_details_id}}">
								</div>
							</div>

							<div class="col-md-2">
								<label> Order Quantity</label>
								<div class="form-group">
									<input type="text" class="form-control" name="order_item_quantity_{{$i}}" value="{{$list->order_item_deliverd_quantity}}" required="">
								</div>
							</div>

							<div class="col-md-2">
								<label>Order Item Rate</label>
								<div class="form-group">
									<input type="text" class="form-control" name="order_quantity_rate_{{$i}}" value="{{$list->order_item_quantity_rate}}" required="">
								</div>
							</div>


							<div class="col-md-4">
								<label>Sales Order Item Cost</label>
								<div class="form-group">
									<input type="text" class="form-control" name="order_quantity_cost_{{$i}}" value="{{$list->order_item_deliverd_cost}}" placeholder="" required="">
								</div>
							</div>
						</div>
						
						@endforeach
						@endif
						@endif


						<!-- #############  Journal ####################### -->

						@if($edit_ltech_transactions->posting_type == 'journal' )
						<div style="background-color: #777777;" class="text-center"><strong>  Finish Goods Transactions </strong></div>
						<?php 
							$journal_info=\DB::table('ltech_finish_goods_transactions')->where('referrence', $edit_ltech_transactions->transactions_id)->first();
							$inventory_stocks_data=\DB::table('ltech_inventory_stocks')->get();

							$finish_goods_inventory=unserialize($journal_info->finish_goods_inventory);
						?>
							<div class="row">
								<div class="col-md-4">
									<label>Finish Goods Quantity</label>
									<div class="form-group">
										<input type="text" class="form-control" name="quantity" value="{{$journal_info->transaction_finish_goods_quantity}}">
									</div>
								</div>

								<div class="col-md-4">
									<label>Finish Goods Rate</label>
									<div class="form-group">
										<input type="text" class="form-control" name="quantity_rate" value="{{$journal_info->finish_goods_quantity_rate}}">
									</div>
								</div>

								<div class="col-md-4">
									<label>Finish Goods Quantity Cost</label>
									<div class="form-group">
										<input type="text" class="form-control" name="quantity_cost" value="{{$journal_info->finish_goods_quantity_cost}}">
									</div>
								</div>

							</div>
							@if(!empty($finish_goods_inventory))
							<div style="background-color: #777777;" class="text-center"><strong>  Inventory Stocks Transactions </strong></div>

							@foreach ($finish_goods_inventory as $key => $list)
							<?php $i=$key+1; ?>
							<div class="row">
								<input type="hidden" name="count_row" value="{{$i}}">
								<div class="col-md-4">
									<label> Inventory Stocks</label>
									<div class="form-group">

									<select class="form-control" name="stocks_id_{{$i}}" required>
										<option value="">Choose Inventory Stocks </option>
										@if(isset($inventory_stocks_data) && (count($inventory_stocks_data)>0))
											@foreach($inventory_stocks_data as $key => $value)
												<option {{(($list['finishgoods_inventory_stocks_id']) == $value->inventory_stock_id) ? "selected" :''}} value="{{$value->inventory_stock_id}}">{{$value->item_name}}</option>
											@endforeach
										@endif
									</select>



									</div>
								</div>

								<div class="col-md-4">
									<label>Inventory Stocks Quantity</label>
									<div class="form-group">
										<input type="text" class="form-control" name="stocks_quantity_{{$i}}" value="{{$list['finishgoods_transaction_stocks_quantity']}}" required>
									</div>
								</div>

								<div class="col-md-4">
									<label>Inventory Stocks Cost</label>
									<div class="form-group">
										<input type="text" class="form-control" name="stocks_cost_{{$i}}" value="{{$list['finishgoods_stocks_transaction_amount']}}" required>
									</div>
								</div>
							</div>
							@endforeach
							@endif
						@endif

					</div>
					<div class="col-md-3">


						<div class="col-md-12 form-group">
							<label>Transaction Description</label>
							<textarea class="form-control" name="transactions_naration" rows="3" cols="5" required>{{$edit_ltech_transactions->transactions_naration}} </textarea>
						</div>

					</div>

						<input type="hidden" class="form-control" name="transactions_id" value="{{$edit_ltech_transactions->transactions_id}}">
						<input type="hidden" class="form-control" name="transactions_date" value="{{$edit_ltech_transactions->transactions_date}}">

				</div>
		

				<div class="table-responsive"><!--end of Stockes table-->
					<table class="table stocks_entry table-hover table-bordered table-striped nopadding" >
						<thead>
							<tr>
								<th>#</th>
								<th>Particuler Name</th>
								<th>Amount Type</th>
								<th>Amount</th>
								<th>Narration</th>
								<th></th>
							</tr>
						</thead>
						<tbody class="journal_entry_body">
						@if(!empty($edit_journal_info) && count($edit_journal_info)>0)
						@foreach ($edit_journal_info as $key => $list)
						@php($i=$key+1)
							<tr>
								<td>{{$i}}</td>

								<td>

									@if(!empty($journal_posting_field) && count($journal_posting_field)>0)
									<select data-rowid="{{$i}}" class="form-control" name="journal_particular_name_{{$i}}" required>
									@foreach($journal_posting_field as $key => $value)
									    <option {{($list->journal_particular_name == $value->ledger_name) ? "selected" :''}} value="{{($value->ledger_id).'.'.($value->ledger_name).'.'.($value->depth)}}">{{$value->ledger_name}}</option>
									@endforeach
									</select>
									@endif

								</td>
								<td>
									<select data-rowid="{{$i}}" class="form-control" name="journal_particular_amount_type_{{$i}}" required>
										<option value="">Choose Amount Type</option>
										<option {{($list->journal_particular_amount_type == 'debit') ? "selected" :''}} value="debit">Debit</option>
										<option {{($list->journal_particular_amount_type == 'credit') ? "selected" :''}} value="credit">Credit</option>
									</select>
								</td>
								<td>
									<input data-rowid="{{$i}}" type="text" class="form-control" name="journal_particular_amount_{{$i}}" value="{{$list->journal_particular_amount}}" required />
								</td>

								<td>
									<textarea data-rowid="{{$i}}" class="form-control" name="journal_particular_naration_{{$i}}" rows="3" cols="5" required>{{$list->journal_particular_naration}}</textarea>
								</td>

									<input data-rowid="{{$i}}" type="hidden" class="form-control" name="journal_id_{{$i}}" value="{{$list->journal_id}}" />

							</tr>
					
						@endforeach


						</tbody>
					</table>
					<input type="hidden" class="journal_entry_field" name="journal_entry_field" value="{{count($edit_journal_info)}}">
				@endif

				</div><!--end of Stockes table-->
				<div class="row">
					<div class="col-md-12 form-group">
						<button class="btn btn-default add_line_journal">Add line</button>
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
								Supplier Name
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