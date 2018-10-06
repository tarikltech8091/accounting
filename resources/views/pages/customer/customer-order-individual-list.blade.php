@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-md-12" style="margin-bottom:40px;">
		<div class="panel panel-default">
			<div class="row">
			<div class="col-md-6">
				<img src="{{asset('assets/images/dfblack.png')}}" alt="" style="margin:50px;">
			</div>
			<div class="col-md-6 pull-right">
				<div class="pull-right" style="margin-right:10px;">
					<h3> D. F Tex </h3>13/2, West Panthapath, Dhaka-1207
				</div>
			</div>
			</div><br>

			<div class="panel panel-heading" align="center">
				<strong> Customer Order Invoice </strong>
			</div>

			<div class="panel-body">
				<div class="col-md-9 pull-left">
					<h4>Customer:</h4>
					<strong>{{isset($order_customer_details->customer_name)?($order_customer_details->customer_name):''}}</strong><br>
					<strong>{{isset($order_customer_details->customer_company)?($order_customer_details->customer_company):''}}</strong><br>
					{{isset($order_customer_details->customer_address)?($order_customer_details->	customer_address):''}}
				</div>

				<div class="col-md-3 pull-right">
					<div class="row">
						<p class="pull-right">
							<a href="{{url('/customer/order-pdf/oid-'.$ltech_sales_orders->order_id)}}" class="btn btn-success tooltips" data-toggle1="tooltip" title="Stock Summery PDF">Download<i class="fa fa-print" aria-hidden="true"></i></a>
							<a href="{{url('/customer/order-pdf-print/oid-'.$ltech_sales_orders->order_id)}}" target="_blank" class="btn btn-success hidden-print">Print <i class="fa fa-print"></i></a>
						</p>
					</div>

					<div class="row">
						<p class="pull-right">
						<strong> Order Date:</strong>{{isset($ltech_sales_orders->order_date)?($ltech_sales_orders->order_date):''}}<br>
						<strong> Deleviry Date:</strong>{{isset($ltech_sales_orders->order_delivery_date)?($ltech_sales_orders->order_delivery_date):''}}<br>
						<strong>Order No:</strong>{{isset($ltech_sales_orders->order_id)?($ltech_sales_orders->order_id):' 0 '}}
						</p>
					</div>

				</div><br>
			</div>

			<div class="panel-body">

				<div class="table-responsive">
					<table class="table table-hover table-bordered table-striped nopadding">
						<thead>
				              <tr>
				                <th>Name of Item</th>
				                <th>Quantity</th>
				                <th>Rate Per Unit (Tk)</th>
				                <th>Amount (Tk)</th>
				              </tr>
						</thead>
						<tbody>
						@if(!empty($ltech_sales_order_details) && count($ltech_sales_order_details)>0)
						@foreach($ltech_sales_order_details as $key => $list)
							<tr>
								<td>{{$list->item_name}}</td>
								<td>{{$list->order_item_quantity}}</td>
								<td>{{$list->order_item_quantity_rate}}</td>
								<td>{{$list->order_item_cost}}</td>
							</tr>
						@endforeach
						@else
							<tr>
								<td colspan="4" class="text-center"> No data Available</td>
							</tr>
						@endif
							<tr>
								<td colspan="3" class="text-center"><strong>Grand Total</strong></td>
				                <td><strong>{{isset($ltech_sales_orders->order_amount)?($ltech_sales_orders->order_amount):' 00.00 '}} Tk</strong></td>
							</tr>

						</tbody>
					</table>

					<div class="col-md-4 pull-right">
						<strong>Discount Rate :</strong>{{isset($ltech_sales_orders->order_discount_rate)?($ltech_sales_orders->order_discount_rate):' 00.00 '}}%<br>
						<strong>Discount Amount :</strong>{{isset($ltech_sales_orders->order_discount_amount)?($ltech_sales_orders->order_discount_amount):' 00.00 '}}<br>
						<strong>Grand Total :</strong>{{isset($ltech_sales_orders->order_net_amount)?($ltech_sales_orders->order_net_amount ):' 00.00 '}} Tk<br>
					</div>

				</div>
			</div>

		</div>
	</div>
</div>
@stop