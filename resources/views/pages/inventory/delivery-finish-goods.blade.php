@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="clip-users-2"></i>
				Delivery Finish Goods List
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
			<div class="panel-body" ><!--Start panel Body-->
				<div class="table-responsive">
					<table class="table table-hover table-bordered table-striped nopadding">
						<thead>
							<tr>
								<th> # </th>
								<th> Goods Name </th>
								<th> Production Quantity </th>
								<th> Production Cost </th>
								<th> Sales Quantity </th>
								<th> Sales Cost </th>
								<th> Net Quantity </th>
								<th> Net Cost </th>
								<th> Waste Quantity </th>
								<th> Waste Cost </th>
								<th> Action </th>
							</tr>
						</thead>
						<tbody>
							@if(isset($delivery_finish_goods) && (count($delivery_finish_goods)>0))
								@foreach($delivery_finish_goods as $key => $goods)
									<tr>
										<td>{{($key+1)}}</td>
										<td>{{isset($goods->finish_goods_name) ? $goods->finish_goods_name:''}} </td>
										<td> {{isset($goods->finish_goods_net_production_quantity) ? $goods->finish_goods_net_production_quantity:''}} </td>
										<td> Tk {{isset($goods->finish_goods_net_production_cost) ? $goods->finish_goods_net_production_cost:''}} </td>
										<td> {{isset($goods->finish_goods_net_sales_quantity) ? $goods->finish_goods_net_sales_quantity:''}}</td>
										<td> Tk {{isset($goods->finish_goods_net_sales_cost) ? number_format($goods->finish_goods_net_sales_cost,2,'.','' ):''}}</td>
										<td>{{isset($goods->finish_goods_net_quantity) ? number_format($goods->finish_goods_net_quantity,2,'.','' ):''}} </td>
										<td> Tk {{isset($goods->finish_goods_net_cost) ? number_format($goods->finish_goods_net_cost,2,'.','' ):''}} </td>
										<td>{{isset($goods->finish_goods_waste_quantity) ? number_format($goods->finish_goods_waste_quantity,2,'.','' ):''}} </td>
										<td> Tk {{isset($goods->finish_goods_waste_cost) ? number_format($goods->finish_goods_waste_cost,2,'.','' ):''}} </td>
										<td>
											<a href="{{url('/waste/finish-goods/id-'.$goods->finish_goods_id)}}" class="btn btn-success tooltips" data-toggle1="tooltip" title="" data-original-title="Waste Goods Export"><i class="fa fa-download"></i> Confirm</a>
											
										</td>
									</tr>
								@endforeach
							@else
								<td class="text-center" colspan="11">No Transactions Available</td>
							@endif
						</tbody>
					</table>
					<?php echo isset($delivery_finish_goods_pagination)? $delivery_finish_goods_pagination :''; ?>
				</div>
			</div><!--End panel Body-->
		</div>
	</div>
</div>
@stop