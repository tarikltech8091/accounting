@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
		<?php $company_details=\DB::table('company_details')->latest()->first(); ?>
			<div class="row" align="center">
				<h2>
					{{isset($company_details->company_name)? $company_details->company_name :''}}
				</h2><br>
				{{isset($company_details->company_address)? $company_details->company_address :''}}
			</div><br>

			<div class="panel panel-heading" align="center">
				<strong> Inventory Stock Summery </strong>


				<a href="{{url('/stock/summery/pdf/from-'.(isset($search_from)? $search_from : '').'/to-'.(isset($search_to)? $search_to : '').'/ccid-'.(isset($cost_center)? $cost_center : 0))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="Stock Summery PDF" style="margin-left:10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>

				<a target="_blank" href="{{url('/stock/summery/print/from-'.(isset($search_from)? $search_from : '').'/to-'.(isset($search_to)? $search_to : '').'/ccid-'.(isset($cost_center)? $cost_center : 0))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="Stock Summery print" style="margin-left:10px;"><i class="fa fa-print" aria-hidden="true"></i></a>
			</div>

			<div class="panel-body" align="center">
				<div class="row">
				<form method="get" action="{{url('/stock/summery/list')}}">
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
			</div>


			<div class="panel-body">
				<div class="table-responsive cost_list posting_list">
				<?php
					$total_opening_cost=0;
					$total_closing_cost=0;
					$total_inwards_cost=0;
					$total_outwards_cost=0;
					$total_opening_qty=0;
					$total_closing_qty=0;
					$total_inwards_qty=0;
					$total_outwards_qty=0;
					$total_return_qty=0;
					$total_return_cost=0;
				?>
					<table class="table table-hover table-bordered table-striped nopadding">
						<thead>
				            <tr>
				             	<th colspan="3" rowspan="2">Stocks Item</th>
				             	<th colspan="2" class="text-center">Opening Balance</th>
				             	<th colspan="2" class="text-center">Inwards</th>
				             	<th colspan="2" class="text-center">Return</th>
				             	<th colspan="2" class="text-center">Outwards</th>
				             	<th colspan="2" class="text-center">Closing Balance</th>
				            </tr>

				            <tr>
				                <th>Qty</th>
				                <th>Value</th>
				                <th>Qty</th>
				                <th>Value</th>
				                <th>Qty</th>
				                <th>Value</th>
				                <th>Qty</th>
				                <th>Value</th>
				                <th>Qty</th>
				                <th>Value</th>
				            </tr>

						</thead>
						<tbody>
					@if(!empty($stock_data) && count($stock_data)>0)
					@foreach($stock_data as $key => $list)
							<tr>
					<?php
					$stock_summery_outwards_list_cost=0;
					$stock_summery_outwards_list_qty=0;
            		$stock_item_info=\DB::table('ltech_inventory_stocks')
            						->where('inventory_stock_id',$list['inventory_stock_id'])
            						->first();

            			$stock_summery_opening_list=\DB::table('ltech_inventory_stocks_transactions')
            						->where('ltech_inventory_stocks_transactions.inventory_stock_id',$list['inventory_stock_id'])
                                	->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                                	->where('ltech_inventory_stocks_transactions.stocks_transaction_type','!=','outwards')
            						->where(function($query){
		                                if(isset($cost_center) && ($cost_center!=0)){
		                                    $query->where(function ($q){
		                                        $q->where('ltech_inventory_stocks_transactions.cost_center_id', $cost_center);
		                                      });
		                                }
                                	})
            						->orderBy('ltech_inventory_stocks_transactions.stocks_transactions_id','asc')
            						->first();

            			$stock_summery_closing_list=\DB::table('ltech_inventory_stocks_transactions')
            						->where('ltech_inventory_stocks_transactions.inventory_stock_id',$list['inventory_stock_id'])
                                	->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
            						->where(function($query){
		                                if(isset($cost_center) && ($cost_center!=0)){
		                                    $query->where(function ($q){
		                                        $q->where('ltech_inventory_stocks_transactions.cost_center_id', $cost_center);
		                                      });
		                                }
                                	})
                                	->where('ltech_inventory_stocks_transactions.stocks_transaction_type','!=','outwards')
            						->orderBy('ltech_inventory_stocks_transactions.stocks_transactions_id','desc')
            						->first();

            			$stock_summery_outwards_list=\DB::table('ltech_inventory_stocks_transactions')
            						->where('ltech_inventory_stocks_transactions.inventory_stock_id',$list['inventory_stock_id'])
                                	->where('ltech_inventory_stocks_transactions.stocks_transaction_date','<',$search_from)
            						->where(function($query){
		                                if(isset($cost_center) && ($cost_center!=0)){
		                                    $query->where(function ($q){
		                                        $q->where('ltech_inventory_stocks_transactions.cost_center_id', $cost_center);
		                                      });
		                                }
                                	})
                                	->where('ltech_inventory_stocks_transactions.stocks_transaction_type','=','outwards')
            						->get();

            					foreach ($stock_summery_outwards_list as $key => $value) {
                                	$stock_summery_outwards_list_cost=$stock_summery_outwards_list_cost+$value->stocks_quantity_cost;
                                	$stock_summery_outwards_list_qty=$stock_summery_outwards_list_qty+$value->transaction_stocks_quantity;
                                }

        			$open_cost=0;
        			$open_qty=0;
        			$stock_inwards_outwards_cost=0;
        			$stock_inwards_outwards_qty=0;

            		if(empty($stock_summery_opening_list)){
            			$stock_summery_opening_other_list=\DB::table('ltech_inventory_stocks_transactions')
            						->where('ltech_inventory_stocks_transactions.inventory_stock_id',$list['inventory_stock_id'])
                                	->where('ltech_inventory_stocks_transactions.stocks_transaction_date','<',$search_from)
                                	->where('ltech_inventory_stocks_transactions.stocks_transaction_type','!=','outwards')
                                	->where(function($query){
		                                if(isset($cost_center) && ($cost_center!=0)){
		                                    $query->where(function ($q){
		                                        $q->where('ltech_inventory_stocks_transactions.cost_center_id', $cost_center);
		                                      });
		                                }
                                	})
            						->orderBy('ltech_inventory_stocks_transactions.stocks_transactions_id','desc')
            						->first();
            			$stock_inwards_outwards_list=\DB::table('ltech_inventory_stocks_transactions')
            					->where('ltech_inventory_stocks_transactions.inventory_stock_id',$list['inventory_stock_id'])
                                ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','=','outwards')
                                ->where('ltech_inventory_stocks_transactions.stocks_transaction_date','<',$search_from)
                                ->where(function($query){
	                                if(isset($cost_center) && ($cost_center!=0)){
	                                    $query->where(function ($q){
	                                        $q->where('ltech_inventory_stocks_transactions.cost_center_id', $cost_center);
	                                      });
	                                }
                                })
                                ->get();
                                foreach ($stock_inwards_outwards_list as $key => $value) {
                                	$stock_inwards_outwards_cost=$stock_inwards_outwards_cost+$value->stocks_quantity_cost;
                                	$stock_inwards_outwards_qty=$stock_inwards_outwards_qty+$value->transaction_stocks_quantity;
                                }
            			$open_cost=(isset($stock_summery_opening_other_list->closing_transaction_stocks_cost)?($stock_summery_opening_other_list->closing_transaction_stocks_cost):0)-$stock_inwards_outwards_cost;
            			$open_qty=(isset($stock_summery_opening_other_list->closing_transaction_stocks_quantity)?($stock_summery_opening_other_list->closing_transaction_stocks_quantity):0)-$stock_inwards_outwards_qty;

            		}

					?>
								<td colspan="3">
								
								@if(!empty($stock_item_info))
								{{(isset($stock_item_info->item_name)? ($stock_item_info->item_name) : '')}}
								@endif
								</td>

								<td>{{(isset($stock_summery_opening_list->opening_transaction_stocks_quantity)? ($stock_summery_opening_list->opening_transaction_stocks_quantity)-$stock_summery_outwards_list_qty :$open_qty)}}</td>
								<td>{{(isset($stock_summery_opening_list->opening_transaction_stocks_cost)? ($stock_summery_opening_list->opening_transaction_stocks_cost)-$stock_summery_outwards_list_cost :$open_cost)}}</td>

								<td>{{$list['inwards_qty']}}</td>
								<td>{{$list['inwards_cost']}}</td>

								<td>{{$list['return_qty']}}</td>
								<td>{{$list['return_cost']}}</td>
								<td>{{$list['outwards_qty']}}</td>
								<td>{{$list['outwards_cost']}}</td>



								<td>{{(isset($stock_summery_opening_list->opening_transaction_stocks_quantity)? ($stock_summery_opening_list->opening_transaction_stocks_quantity)-$stock_summery_outwards_list_qty :$open_qty)-$list['return_qty']+$list['inwards_qty']-$list['outwards_qty']}}</td>
								<!-- <td>{{(isset($stock_summery_closing_list->closing_transaction_stocks_quantity)? ($stock_summery_closing_list->closing_transaction_stocks_quantity) :0)-$list['outwards_qty']}}</td> -->
								<!-- <td>{{(isset($stock_summery_closing_list->closing_transaction_stocks_quantity)? ($stock_summery_closing_list->closing_transaction_stocks_quantity): 0)}}</td> -->
								<!-- <td>{{(isset($stock_summery_closing_list->closing_transaction_stocks_cost)? ($stock_summery_closing_list->closing_transaction_stocks_cost) :0)}}</td> -->

								<!-- <td>{{(isset($stock_summery_closing_list->closing_transaction_stocks_cost)? ($stock_summery_closing_list->closing_transaction_stocks_cost) :0)-$list['outwards_cost']}}</td> -->
								<td>{{(isset($stock_summery_opening_list->opening_transaction_stocks_cost)? ($stock_summery_opening_list->opening_transaction_stocks_cost)-$stock_summery_outwards_list_cost :$open_cost)-$list['return_cost']+$list['inwards_cost']-$list['outwards_cost']}}</td>
							</tr>
							<?php

            						$total_return_qty=$total_return_qty+$list['return_qty'];
            						$total_return_cost=$total_return_cost+$list['return_cost'];

									$total_opening_cost=$total_opening_cost+(isset($stock_summery_opening_list->opening_transaction_stocks_cost)? ($stock_summery_opening_list->opening_transaction_stocks_cost)-$stock_summery_outwards_list_cost :$open_cost);


									$total_closing_cost=$total_closing_cost+(isset($stock_summery_opening_list->opening_transaction_stocks_cost)? ($stock_summery_opening_list->opening_transaction_stocks_cost) :$open_cost)-$list['return_cost']+$list['inwards_cost']-$list['outwards_cost'];
									$total_inwards_cost=$total_inwards_cost+$list['inwards_cost'];
									$total_outwards_cost=$total_outwards_cost+$list['outwards_cost'];


									$total_opening_qty=$total_opening_qty+(isset($stock_summery_opening_list->opening_transaction_stocks_quantity)? ($stock_summery_opening_list->opening_transaction_stocks_quantity) :$open_qty);


									$total_closing_qty=$total_closing_qty+(isset($stock_summery_opening_list->opening_transaction_stocks_quantity)? ($stock_summery_opening_list->opening_transaction_stocks_quantity) :$open_qty)-$list['return_qty']+$list['inwards_qty']-$list['outwards_qty'];

									$total_inwards_qty=$total_inwards_qty+$list['inwards_qty'];
									$total_outwards_qty=$total_outwards_qty+$list['outwards_qty'];

							?>
						@endforeach

							<tr>
								<th colspan="3"><strong>Grand Total</strong></th>
				                <th>{{$total_opening_qty}}</th>
				                <th>{{$total_opening_cost}}</th>
				                <th>{{$total_inwards_qty}}</th>
				                <th>{{$total_inwards_cost}}</th>
				                <th>{{$total_return_qty}}</th>
				                <th>{{$total_return_cost}}</th>
				                <th>{{$total_outwards_qty}}</th>
				                <th>{{$total_outwards_cost}}</th>
				                <th>{{$total_closing_qty}}</th>
				                <th>{{$total_closing_cost}}</th>
							</tr>
						@else
							<tr>
								<td colspan="17" class="text-center"> No data Available</td>
							</tr>
						@endif

						</tbody>
					</table>
					{{isset($stock_summery_pagination)?$stock_summery_pagination:''}}
				</div>
			</div>

		</div>
	</div>
</div>
@stop