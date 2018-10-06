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
				Delivery Details
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"> </a>
					<a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group supplier_select">
							<select class="form-control customer_order_delivery_account_id" name="customer_order_delivery_account_id" required>
								<option value="">Choose a Customer</option>
								@if(isset($customer_list) && (count($customer_list)>0))
									@foreach($customer_list as $key => $customer)
									<option {{(isset($customer_info->customer_account_id) && $customer_info->customer_account_id==$customer->customer_account_id)? 'selected':''}}  data-companynameslug="{{isset($customer->customer_company_slug)? $customer->customer_company_slug:''}}" data-customerid="{{isset($customer->customer_id)? $customer->customer_id:''}}" value="{{isset($customer->customer_account_id)? $customer->customer_account_id:''}}">{{isset($customer->customer_company)? $customer->customer_company:''}}</option>
									@endforeach
								@endif
								
							</select>
						</div>
						
						<div class="form-group">
							<label>Customer Address</label>
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
											@if(isset($customer_order_info) && count($customer_order_info)>0)
												@foreach($customer_order_info as $key => $order_info)
													<thead>
														<tr style="background-color: #ffffcd;">
															<th>#</th>
															<th>Order Date</th>
															<th>Order Description</th>
															<th>Delivery Date</th>
															<th>Amount</th>
															<th>Status</th>
														</tr>
													</thead>
													<tbody>
														<td>{{$key+1}}</td>
														<td>{{$order_info->order_date}}</td>
														<td>{{$order_info->order_description}}</td>
														<td>{{$order_info->order_delivery_date}}</td>
														<td>{{$order_info->order_net_amount}}</td>
														<td>
															@if($order_info->order_status==1)
																@php($parse_url =parse_url(\Request::fullUrl(), PHP_URL_QUERY))
																<a href="{{url('/customer/order/delivery?customer_order_id='.$order_info->order_id.'&'.$parse_url)}}" class="btn btn-green"><i class="fa fa-plus"></i>Add Delivery</a>	
															@elseif($order_info->order_status==2)
																Delivered
															@else
																Processing
															@endif 
														</td>
														
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
															</tr>
														</thead>
														@foreach($ltech_sales_order_details as $key => $list)
															<tr>
																<td>{{($key+1)}}</td>
																<td>{{$list->order_item_name}}</td>
																<td>{{$list->order_item_quantity}}</td>
																<td>{{$list->order_item_quantity_rate}}</td>
																<td>{{$list->order_item_cost}}</td>
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
								</div>
							</div>
						</div>
					</div><!--End Supplier transaction-->
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="clip-users-2"></i>
								Delivery Confirm
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
									<form class="supplier_select_form" action="{{url('/customer/order/delivery?'.$parse_url)}}" method="post">
										<input type="hidden" name="_token" value="{{csrf_token()}}">

										<div class="col-md-3 pull-left" style="margin-bottom:10px;">
											<label class="text-right btn btn-default active"><strong>SL # </strong><?php echo isset($delivery_confirm_order_info[0]->order_id)? $delivery_confirm_order_info[0]->order_id:'';?></label>
										</div>
										<input type="hidden" name="delivery_order_id" value="{{ isset($delivery_confirm_order_info[0]->order_id)? $delivery_confirm_order_info[0]->order_id:''}}">
										<input type="hidden" name="delivery_cost_center_id" value="{{ isset($delivery_confirm_order_info[0]->cost_center_id)? $delivery_confirm_order_info[0]->cost_center_id:''}}">

										<div class="col-md-4 pull-left" style="margin-bottom:10px;">
											<label class="col-md-4 text-right btn btn-default active"><strong> Order Date </strong></label>
											<div class="input-group">
												<input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="delivery_confirm_date" data-link-field="form_dtp_input" value="<?php echo isset($delivery_confirm_order_info[0]->order_date)? $delivery_confirm_order_info[0]->order_date:'';?>">
												<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
											
											</div>
										</div>

										<div class="col-md-5 pull-right" style="margin-bottom:10px;">
											<label class="col-md-4 text-right btn btn-default active"><strong> Delivery Date </strong></label>
											<div class="input-group">
												<input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="delivery_confirm_date" data-link-field="form_dtp_input" value="<?php echo date('Y-m-d');?>">
												<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
											
											</div>
										</div>
										<div class="table-responsive"><!--end of Stockes table-->
											<table class="table delivery_confirm table-hover table-bordered table-striped nopadding" >
												<thead>
													<tr>
														<th>SL</th>	
														<th>Product</th>
														<th>Quantity</th>
														<th>Rate</th>
														<th>Amount</th>
													</tr>
												</thead>
												<tbody class="order_delivery_entry_body">

												@if(isset($delivery_confirm_order_info)&&count($delivery_confirm_order_info)>0)
													@php($i=1)
													@php($order_delivery_total=0)
													@foreach($delivery_confirm_order_info as $key => $list)
														<tr>
															<td>{{$i}}</td>
															<td>
																<input data-id="{{$i}}" type="text" class="form-control delivery_quantity_name delivery_quantity_name_row_{{$i}}" name="delivery_quantity_name_{{$i}}" value="{{$list->order_item_name}}"  required />
															</td>

															<td><input data-id="{{$i}}" type="text" class="form-control delivery_quantity delivery_quantity_row_{{$i}}" name="delivery_quantity_{{$i}}" value="{{$list->order_item_quantity}}" required /> </td>

															<td><input data-id="{{$i}}" type="text" class="form-control delivery_quantity_rate delivery_quantity_rate_row_{{$i}}" name="delivery_quantity_rate_{{$i}}" value="{{$list->order_item_quantity_rate}}" required /> </td>

															<td><input data-id="{{$i}}" type="text" class="form-control delivery_amount delivery_amount_row_{{$i}}" name="delivery_amount_{{$i}}" value="{{$list->order_item_cost}}"  required /> </td>
															
															<input type="hidden" name="delivery_order_item_id_{{$i}}" value="{{$list->order_details_id}}">
														</tr>
														@php($i++)
														@php($order_delivery_total=$order_delivery_total+$list->order_item_cost)
													@endforeach

												@else		
													@php($i=1)
														<tr class="order_delivery_entry_body_{{$i}}">
															<td>{{$i}}</td>
															<td>
																<input data-id="{{$i}}" type="text" class="form-control delivery_quantity_name delivery_quantity_name_row_{{$i}}" name="delivery_quantity_name_{{$i}}" value="" placeholder="Name" required />
															</td>

															<td><input data-id="{{$i}}" type="text" class="form-control delivery_quantity delivery_quantity_row_{{$i}}" name="delivery_quantity_{{$i}}" value="" placeholder="0" required /> </td>
															<td><input data-id="{{$i}}" type="text" class="form-control delivery_quantity_rate delivery_quantity_rate_row_{{$i}}" name="delivery_quantity_rate_{{$i}}" value="" placeholder="0" required /> </td>

															<td><input data-id="{{$i}}" type="text" class="form-control delivery_amount delivery_amount_row_{{$i}}" name="delivery_amounty_{{$i}}" value="" placeholder="0.0" required /> </td>
														</tr>
												@endif
												</tbody>
												<tfoot>
													<th colspan="4" class="text-right">TOTAL</th>
													<td><input class="form-control order_delivery_total" name="order_delivery_total" value="{{isset($order_delivery_total)? $order_delivery_total:0.0}}"></td>
												</tfoot>
											</table>
												<input type="hidden" class="delivery_confirm_entry_field" name="delivery_confirm_entry_field" value="{{($i-1)}}">
												<input type="hidden" class="delivery_customer_id" name="delivery_customer_id" value="{{isset($customer_info->customer_id) ? $customer_info->customer_id:''}}">
										</div><!--end of Stockes table-->
										<div class="form-group">
											<a href="{{url('/customer/order/delivery')}}" class="btn btn-default">Cancel</a>
											<input type="submit" name="Delivery Confirm" value="Delivery Confirm" class="btn btn-info">
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