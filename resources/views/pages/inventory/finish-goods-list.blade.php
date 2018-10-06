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

		<!-- start: PANLEL TABS -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-reorder"></i>
				Finish-goods
			</div>
			<div class="panel-body">
				
				<div class="col-md-12">
					<table class="table table-hover table-bordered table-striped nopadding text-center">  
						@if(isset($customer_order_info) && count($customer_order_info)>0)
							@foreach($customer_order_info as $key => $order_info)
								<thead>
									<tr style="background-color: #ffffcd;">
										<th>#</th>
										<th>Order Date</th>
										<th>Order Description</th>
										<th>Customer</th>
										<th>Cost Center</th>
										<th>Delivery Date</th>
										<th>Amount</th>
									</tr>
								</thead>
								<tbody>
									<td>{{$key+1}}</td>
									<td>{{$order_info->order_date}}</td>
									<td>{{$order_info->order_description}}</td>
									<td>{{$order_info->customer_company}}</td>
									<td>{{$order_info->cost_center_name}}</td>
									<td>{{$order_info->order_delivery_date}}</td>
									<td>{{$order_info->order_net_amount}}</td>
								</tbody>

								@php($ltech_sales_order_details = \DB::table('ltech_sales_order_details')->where('order_id',$order_info->order_id)->get())

								@if(isset($ltech_sales_order_details) && count($ltech_sales_order_details) >0)
									<thead>
										<tr >
											<th>SL</th>
											<th>Product</th>
											<th>Quantity</th>
											<th>Rate</th>
											<th>Amount (Tk.)</th>
											<th colspan="2">Action</th>
										</tr>
									</thead>
									@foreach($ltech_sales_order_details as $key => $list)
										<tr>
											<td>{{($key+1)}}</td>
											<td>{{$list->order_item_name}}</td>
											<td>{{$list->order_item_quantity}}</td>
											<td>{{$list->order_item_quantity_rate}}</td>
											<td>{{$list->order_item_cost}}</td>
											<td colspan="2">
											@if($list->order_item_process_status==0)
											 	@if(!empty($_GET['order_id']) && ($_GET['order_id'] == ($order_info->order_id)))
											 		<a class="btn btn-green  btn-xs" disabled><i class="fa fa-plus"></i>Add Finish Goods</a>
											 	@else	
													<a href="{{url('/finish-goods/list?order_id='.$order_info->order_id.'&item_id='.$list->order_details_id)}}" class="btn btn-green  btn-xs" data-id="{{$order_info->order_id}}"><i class="fa fa-plus"></i>Add Finish Goods</a>
												@endif
											@else
												Finish-goods
											@endif
											</td>
										</tr>
									@endforeach

								@endif
							@endforeach
						@else
							<thead>
								<tr>
									<th>#</th>
									<th>Order Date</th>
									<th>Order Description</th>
									<th>Delivery Date</th>
									<th>Amount</th>
									<th>Deleivery</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="6">No order available</td>
								</tr>
							</tbody>

						@endif
						
					</table>
				</div>
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="clip-users-2"></i>
							Finish Goods Posting
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
							  <form class="supplier_select_form" action="{{url('/finish-goods/list')}}" method="post">
									<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="col-md-4">
									
									<div class="form-group">
										<label class="">Submit Date</label>
										<div class="input-group">
											<input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="finish_add_date" data-link-field="form_dtp_input" value="<?php echo date('Y-m-d');?>">
											<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
										</div>
									</div>
									<div class="form-group ">
										<select class="form-control" name="cost_center_id" required>
											<option value="">Choose a Cost Center</option>
											@if(isset($cost_centers) && (count($cost_centers)>0))
												@foreach($cost_centers as $key => $center)
												<option {{isset($product_info->cost_center_id) && ($product_info->cost_center_id==$center->cost_center_id) ? 'selected':''}} value="{{$center->cost_center_id}}">{{$center->cost_center_name}}</option>
												@endforeach
											@endif
										</select>
									</div>
									<div class="form-group ">
										<label class="">Finish Goods Name</label>
										<input name="finish_goods_name" class="form-control" value="{{isset($product_info->order_item_name) && !empty($product_info->order_item_name) ? $product_info->order_item_name:''}}" required>
										<input type="hidden" name="finish_goods_item_id" value="{{isset($product_info->order_details_id) && !empty($product_info->order_details_id) ? $product_info->order_details_id:0}}">
									</div>
									<div class="form-group ">
										<label class="">Goods Quantity</label>
										<input name="finish_goods_quantity" class="form-control finish_goods_change finish_goods_quantity" value="{{isset($product_info->order_item_quantity) && !empty($product_info->order_item_quantity) ? $product_info->order_item_quantity:''}}" required>
										
									</div>
									<div class="form-group ">
										<label class="">Rate</label>
										<input name="finish_goods_rate" class="form-control finish_goods_change finish_goods_rate" value="{{isset($product_info->order_item_quantity_rate) && !empty($product_info->order_item_quantity_rate) ? $product_info->order_item_quantity_rate:''}}" required>
										
									</div>
									<div class="form-group ">
										<label class="">Goods Cost</label>
										<input name="finish_goods_cost" class="form-control finish_goods_cost" value="{{isset($product_info->order_item_quantity_rate) && !empty($product_info->order_item_quantity_rate) ? $product_info->order_item_quantity_rate * $product_info->order_item_quantity :0}}" required>
										
									</div>
									
								</div>

								<div class="col-md-8">
									<div class="table-responsive"><!--end of Stockes table-->
										<table class="table stocks_entry table-hover table-bordered table-striped nopadding" >
											<thead>
												<tr>
													<th colspan="5" class="text-center">Inventory Items For Finish Goods</th>
												</tr>
												<tr>
													<th>#</th>	
													<th>Product</th>
													<th>Stocks on hand</th>
													<th>Quantity</th>
													<th>Amount</th>
												</tr>
											</thead>
											<tbody class="finishgoods_entry_body">
												
												@for($i=1;$i<=1;$i++)
													<tr class="finishgoods_stocks_entry_group_{{$i}}">
														<td>{{$i}}</td>
														<td class="finishgoods_inventory_stocks_td" data-rowid="{{$i}}">
															<select data-rowid="{{$i}}" class="form-control finishgoods_inventory_stocks finishgoods_inventory_stocks_row_{{$i}}" name="finishgoods_inventory_stocks_id_{{$i}}" required>
																<option value="0">Choose a product</option>
																@if(isset($inventory_stocks_list) && (count($inventory_stocks_list) > 0))
																	@foreach($inventory_stocks_list as $key => $stocks)
																	<option value="{{$stocks->inventory_stock_id}}">{{$stocks->item_name}}</option>
																	@endforeach
																@endif
															</select>
														</td>

														<td><input data-rowid="{{$i}}" type="text" class="form-control finishgoods_stocks_onhand finishgoods_stocks_onhand_row_{{$i}}" name="finishgoods_stocks_onhand_{{$i}}" value="" disabled="" />
														<input type="hidden" class="finishgoods_stocks_onhand_quantity_row_{{$i}}" name="finishgoods_stocks_onhand_quantity_{{$i}}" value="">
														</td>

														<td><input data-rowid="{{$i}}" type="text" class="form-control finishgoods_transaction_stocks_quantity finishgoods_transaction_stocks_quantity_row_{{$i}}" name="finishgoods_transaction_stocks_quantity_{{$i}}" value="" required /> </td>

														<td><input data-rowid="{{$i}}" class="form-control finishgoods_stocks_transaction_amount finishgoods_stocks_transaction_amount_row_{{$i}}"  name="finishgoods_stocks_transaction_amount_{{$i}}" required></td>

													</tr>

												@endfor
											</tbody>
											<tfoot>
												<tr>
													<th colspan="4">Total</th>
													<td><input data-rowid="{{$i}}" class="form-control finishgoods_stocks_transaction_total"  name="finishgoods_stocks_transaction_total" required></td>
													</tr>

											</tfoot>
										</table>
										<input type="hidden" class="finishgoods_stocks_entry_field" name="finishgoods_stocks_entry_field" value="1">

										<div class="col-md-6 form-group">
											<button class="btn btn-default finishgoods_add_line_stocks">Add line</button>
										
										</div>
										<div class="col-md-6 form-group">	
											<input  type="submit" class="btn btn-info pull-right" name="finishgoods_stocks_entry" value="Posting">
										</div>

									</div><!--end of Stockes table-->
								</div>
								
										
								
								 </form>
								</div>
							</div>
						</div>
					</div>
						
			</div>
		</div>
		<!-- end: PANLEL TABS -->
</div>

@stop