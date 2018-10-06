@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-md-12" style="margin-bottom:40px;">
		<div class="panel panel-default">
			<div class="row">
			<div class="col-md-6">
				<img alt="" src="{{(isset($company_info->company_logo) && !empty($company_info->company_logo)) ? asset($company_info->company_logo):''}}" title="{{(isset($company_info->company_name) && !empty($company_info->company_name)) ? $company_info->company_name:'Company Logo'}}" alt="{{(isset($company_info->company_name) && !empty($company_info->company_name)) ? $company_info->company_name:'Company Logo'}}" style="margin:15px;">
			</div>
			<div class="col-md-6 pull-right">
				<div class="pull-right" style="margin-right:10px;">
					<h3>{{(isset($company_info->company_title) && !empty($company_info->company_title)) ? $company_info->company_title:'Company Title'}}</h3>{{(isset($company_info->company_address) && !empty($company_info->company_address)) ? $company_info->company_address:'Company Address'}}
				</div>
			</div>
			</div><br>

			<div class="panel panel-heading" align="center">
				<strong>Sales Return Invoice </strong>
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
							<a href="{{url('/customer/sales/return/invoice/download/pdf')}}" class="btn btn-success tooltips" data-toggle1="tooltip" title="Order Detail PDF">Download<i class="fa fa-print" aria-hidden="true"></i></a>
							<a href="{{url('/customer/sales/return/invoice/print')}}" target="_blank" class="btn btn-success hidden-print">Print <i class="fa fa-print"></i></a>
						</p>
					</div>

					<div class="row">
						<p class="pull-right">
						<strong>Order No:</strong>{{ date('Ymd',strtotime($ltech_sales_orders->order_date))}}{{isset($ltech_sales_orders->order_date)?($ltech_sales_orders->order_id):' 0 '}}
						<br>
						<strong> Order Date:</strong>{{isset($ltech_sales_orders->order_date)?($ltech_sales_orders->order_date):''}}<br>
						<strong> Delivered Date:</strong>{{isset($ltech_sales_orders->order_delivered_customer_date)?($ltech_sales_orders->order_delivered_customer_date):''}}<br>
						
						</p>
					</div>

				</div><br>
			</div>

			<div class="panel-body">

				<div class="table-responsive">
					<table class="table table-hover table-bordered table-striped nopadding">
						<thead>
				              <tr>
				              	<th>SL</th>
				                <th>Name of Item</th>
				                <th>Quantity</th>
				                <th>Rate Per Unit (Tk)</th>
				                <th>Amount (Tk)</th>
				              </tr>
						</thead>
						<tbody>
						@if(isset($sales_return_all_data['return_confirm_entry_field']) && $sales_return_all_data['return_confirm_entry_field'] !=0)
						@php($total=0)
						@for($i=1;$i<=$sales_return_all_data['return_confirm_entry_field'];$i++)
						
							<tr>
								<td>{{$i}}</td>
								<td>{{$sales_return_all_data['return_quantity_name_'.$i]}}</td>
								<td>{{$sales_return_all_data['return_quantity_'.$i]}}</td>
								<td>{{$sales_return_all_data['return_quantity_rate_'.$i]}}</td>
								<td>{{$sales_return_all_data['return_quantity_'.$i]*$sales_return_all_data['return_quantity_rate_'.$i]}}</td>
							</tr>
							@php($total=$total+($sales_return_all_data['return_quantity_'.$i]*$sales_return_all_data['return_quantity_rate_'.$i]))
						@endfor
						@else
							<tr>
								<td colspan="4" class="text-center"> No data Available</td>
							</tr>
						@endif
							<tr>
								<td colspan="4" class="text-center"><strong>Grand Total</strong></td>
				                <td><strong>{{isset($total)?($total):' 00.00 '}} Tk</strong></td>
							</tr>

						</tbody>
					</table>

				</div>
			</div>

		</div>
	</div>
</div>
@stop