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
				<strong>Invoice </strong>
			</div>

			<div class="panel-body">

				<div class="col-md-9">
					<div class="row">
						<h2 class="text-center">
						All Inventory Stocks List
						</h2>
					</div>
				</div><br>

				<div class="col-md-3 pull-right">
					<div class="row">
						<p class="pull-right">
							<a href="{{url('/inventory/stock-item/download')}}" class="btn btn-success tooltips" data-toggle1="tooltip" title="Stocks List PDF">Download<i class="fa fa-print" aria-hidden="true"></i></a>
							<a href="{{url('/inventory/stock-item/print')}}" target="_blank" class="btn btn-success hidden-print" data-toggle1="tooltip" title="Stocks List Print">Print <i class="fa fa-print"></i></a>
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
				                <th>Stocks Type</th>
				                <th>Description</th>
				                <th>Onhand</th>
				                <th>Onproduction</th>
				                <th>Stocks Total Quantity</th>
				                <th>Total Cost</th>
				              </tr>
						</thead>
						<tbody>
						@if(isset($inventory_stock_item_list) && count($inventory_stock_item_list)>0)
						@foreach($inventory_stock_item_list as $key => $list)
							<tr>
								<td>{{($key+1)}}</td>
								<td>{{$list->item_name}}</td>
								<td>{{$list->stocks_type}}</td>
								<td>{{$list->item_description}}</td>
								<td>{{$list->stocks_onhand}}</td>
								<td>{{$list->stocks_onproduction}}</td>
								<td>{{$list->stocks_total_quantity}}</td>
								<td>{{$list->stocks_total_cost}}</td>

							</tr>
						@endforeach
						@else
							<tr>
								<td colspan="8" class="text-center"> No data Available</td>
							</tr>
						@endif
							<tr>
								<td colspan="7" class="text-center"><strong>Grand Total</strong></td>
				                <td><strong>{{isset($inventory_stock_total_item_cost)?($inventory_stock_total_item_cost):' 00.00 '}} Tk</strong></td>
							</tr>

						</tbody>
					</table>
					{{(isset($stock_list_pagination)? $stock_list_pagination :'')}}

				</div>
			</div>

		</div>
	</div>
</div>
@stop