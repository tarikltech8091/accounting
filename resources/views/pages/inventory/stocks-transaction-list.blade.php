@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="clip-users-2"></i>
				Stocks Trasansactions List
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
				<div class="row">
			        <form method="get" action="{{url('/inventory/stocks/trasansaction/list')}}">
			          <!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->
			          <div class="col-md-4">
			            <div class="form-group ">
			              <label for="form-field-23">
			                From<span class="symbol required"></span>
			              </label>
			              <div class="input-group">
			                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="search_from" value="{{(isset($_GET['search_from']) ? $_GET['search_from'] : date("Y-m-d"))}}" placeholder="">
			                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
			              </div>
			            </div>
			          </div>
			          <div class="col-md-4">
			            <div class="form-group ">
			              <label for="form-field-23">
			                To<span class="symbol required"></span>
			              </label>
			              <div class="input-group">
			                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="search_to" value="{{(isset($_GET['search_to']) ? $_GET['search_to'] : date("Y-m-d"))}}">
			                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
			              </div>
			            </div>
			          </div>

			          <?php
			            $all_cost=\DB::table('ltech_cost_centers')->get();
			          ?>

			          <div class="col-md-2">
			            <div class="form-group">
			              <label for="form-field-23">
			                Cost Center
			              </label>
			              <div class="input-group">
			                <select name="cost_center" class="form-control">
			                  <option value="0"> Select Cost</option>
			                  @if(!empty($all_cost) && count($all_cost)>0)
			                  @foreach ($all_cost as $key => $list){

			                  <option {{(isset($_GET['cost_center']) && ($_GET['cost_center'] == $list->cost_center_id)) ? 'selected':''}} value="{{$list->cost_center_id}}">{{$list->cost_center_name}}</option>
			            
			                  @endforeach
			                  @endif
			                  
			                </select>
			              </div>
			            </div>
			          </div>

			          <div class="col-md-2" style="margin-top:22px;">
			            <div class="form-group">
			              <input type="submit" class="btn btn-primary" data-toggle1="tooltip" title="Search Transaction" value="View">
			            </div>
			          </div>
			        </form>
			    </div>

				<div class="table-responsive cost_list posting_list">
					<table class="table table-hover table-bordered table-striped nopadding">
						<thead>
							<tr>
								<th> # </th>
								<th> Item </th>
								<th class="hidden-480"> Description </th>
								<th class="hidden-480"> Purpose </th>
								<th class="hidden-480"> Quantity </th>
								<th class="hidden-480"> Rate </th>
								<th> Total </th>
								<th> Action </th>
							</tr>
						</thead>
						<tbody>
							@if(isset($inventory_stocks_list) && (count($inventory_stocks_list)>0))
								@foreach($inventory_stocks_list as $key => $inventory_stocks)
									<tr>
										<td>{{($key+1)}}</td>
										<td>{{isset($inventory_stocks->item_name) ? $inventory_stocks->item_name:''}} </td>
										<td class="hidden-480"> {{isset($inventory_stocks->stocks_transaction_desc) ? $inventory_stocks->stocks_transaction_desc:''}} </td>
										<td class="hidden-480"> {{isset($inventory_stocks->cost_center_name) ? $inventory_stocks->cost_center_name:''}} </td>
										<td class="hidden-480"> {{isset($inventory_stocks->transaction_stocks_quantity) ? $inventory_stocks->transaction_stocks_quantity:''}} {{isset($inventory_stocks->item_quantity_unit) ? $inventory_stocks->item_quantity_unit:''}}</td>
										<td class="hidden-480"> Tk {{isset($inventory_stocks->stocks_quantity_rate) ? number_format($inventory_stocks->stocks_quantity_rate,2,'.','' ):''}}</td>
										<td> Tk {{isset($inventory_stocks->stocks_quantity_cost) ? number_format($inventory_stocks->stocks_quantity_cost,2,'.','' ):''}} </td>
										<td>
											<a target="_blank"  href="{{url('/inventory/stocks/trasansaction/'.$inventory_stocks->stocks_transactions_id.'/view')}}" class="btn btn-xs btn-green tooltips" data-toggle1="tooltip" title="" data-original-title="Transaction View"><i class="fa fa-eye" aria-hidden="true"></i></a>
											<a href="{{url('/inventory/stocks/trasansaction/'.$inventory_stocks->stocks_transactions_id.'/download')}}" class="btn btn-xs btn-bricky tooltips" data-toggle1="tooltip" title="" data-original-title="Transaction Download"><i class="fa fa-download"></i></a>
											
											<a target="_blank" href="{{url('/inventory/stocks/trasansaction/'.$inventory_stocks->stocks_transactions_id.'/print')}}" class="btn btn-xs btn-green tooltips" data-toggle1="tooltip" title="" data-original-title="Transaction Print"><i class="fa fa-print" aria-hidden="true"></i></a>
											<a href="{{url('/inventory/stocks/trasansaction/'.$inventory_stocks->stocks_transactions_id.'/excel')}}" class="btn btn-xs btn-bricky tooltips" data-toggle1="tooltip" title="" data-original-title="Transaction Export"><i class="fa fa-download"></i></a>
											<a href="#" class="btn btn-xs btn-bricky tooltips" data-toggle1="tooltip" title="" data-original-title="Stock Transaction Delete"><i class="fa  fa-trash-o"></i></a>
											
										</td>
									</tr>
								@endforeach
							@else
								<td colspan="7" class="text-center">No Transactions Available</td>
							@endif
						</tbody>
					</table>
					<?php echo isset($pagination)? $pagination :''; ?>
				</div>
			</div><!--End panel Body-->
		</div>
	</div>
</div>
@stop