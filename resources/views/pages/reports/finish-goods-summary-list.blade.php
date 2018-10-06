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
				<strong> Finish Goods Summery </strong>

				<a href="{{url('/finish-goods/pdf/from-'.(isset($search_from)? $search_from : '').'/to-'.(isset($search_to)? $search_to : '').'/ccid-'.(isset($cost_center)? $cost_center : 0))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="Finish Goods PDF" style="margin-left:10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>

				<a href="{{url('/finish-goods/print/from-'.(isset($search_from)? $search_from : '').'/to-'.(isset($search_to)? $search_to : '').'/ccid-'.(isset($cost_center)? $cost_center : 0))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="Finish Goods print" style="margin-left:10px;"><i class="fa fa-print" aria-hidden="true"></i></a>
			</div>

			<div class="panel-body" align="center">
				<div class="row">
				<form method="get" action="{{url('/finish-goods/summery/list')}}">
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
				             	<th colspan="3" rowspan="2">Finish Goods</th>
				             	<th colspan="2" class="text-center">Opening Balance</th>
				             	<th colspan="2" class="text-center">Inwards</th>
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
				            </tr>

						</thead>
						<tbody>
						@if(!empty($finish_goods_data) && count($finish_goods_data)>0)
						@foreach($finish_goods_data as $key => $list)
							<tr>
					<?php
						// $total_return_qty=0;
						// $total_return_cost=0;
						$finish_goods_info=\DB::table('ltech_finish_goods_stocks')
            						->where('finish_goods_id',$list['finish_goods_id'])
            						->first();

            		if($cost_center!=0){
            			$finish_goods_opening_list=\DB::table('ltech_finish_goods_transactions')
            						->where('ltech_finish_goods_transactions.finish_goods_id',$list['finish_goods_id'])
                                	->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
            						->where('ltech_finish_goods_transactions.cost_center_id',$cost_center)
            						->orderBy('ltech_finish_goods_transactions.ltech_finish_goods_transactions_id','asc')
            						->latest()->first();

            			$finish_goods_closing_list=\DB::table('ltech_finish_goods_transactions')
            						->where('ltech_finish_goods_transactions.finish_goods_id',$list['finish_goods_id'])
                                	->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
            						->where('ltech_finish_goods_transactions.cost_center_id',$cost_center)
            						->orderBy('ltech_finish_goods_transactions.ltech_finish_goods_transactions_id','desc')
            						->latest()->first();
            		}else{
            			$finish_goods_opening_list=\DB::table('ltech_finish_goods_transactions')
            						->where('ltech_finish_goods_transactions.finish_goods_id',$list['finish_goods_id'])
                                	->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
            						->orderBy('ltech_finish_goods_transactions.ltech_finish_goods_transactions_id','asc')
            						->latest()->first();

            			$finish_goods_closing_list=\DB::table('ltech_finish_goods_transactions')
            						->where('ltech_finish_goods_transactions.finish_goods_id',$list['finish_goods_id'])
                                	->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
            						->orderBy('ltech_finish_goods_transactions.ltech_finish_goods_transactions_id','desc')
            						->latest()->first();
            		}

						$total_return_qty=$total_return_qty+$list['return_qty'];
						$total_return_cost=$total_return_cost+$list['return_cost'];
					?>

								<td colspan="3">
								@if(!empty($finish_goods_info))
								{{(isset($finish_goods_info->finish_goods_name)? ($finish_goods_info->finish_goods_name) : '')}}
								@endif
								</td>

								<td>{{(isset($finish_goods_opening_list->opening_transaction_finish_goods_quantity)? ($finish_goods_opening_list->opening_transaction_finish_goods_quantity) :0)}}</td>
								<td>{{(isset($finish_goods_opening_list->opening_transaction_finish_goods_cost)? ($finish_goods_opening_list->opening_transaction_finish_goods_cost) :0)}}</td>

								<td>{{$list['inwards_qty']}}</td>
								<td>{{$list['inwards_cost']}}</td>

								<td><?php echo ($list['outwards_qty']) - ($list['return_qty']) ;?></td>
								<td><?php echo ($list['outwards_cost'])- ($list['return_cost']) ;?></td>

								<!-- <td>{{(isset($finish_goods_closing_list->closing_transaction_finish_goods_quantity)? ($finish_goods_closing_list->closing_transaction_finish_goods_quantity): 0)}}</td>
								<td>{{(isset($finish_goods_closing_list->closing_transaction_finish_goods_cost)? ($finish_goods_closing_list->closing_transaction_finish_goods_cost) :0)}}</td> -->

								<td>
								{{$list['inwards_qty']-($list['outwards_qty']-$list['return_qty'])}}</td>
								<td>{{$list['inwards_cost']-(($list['outwards_qty']*$list['inwards_rate'])-($list['return_qty']*$list['inwards_rate']))}}</td>
							</tr>

							<?php


									$total_opening_cost=$total_opening_cost+(isset($finish_goods_opening_list->opening_transaction_finish_goods_cost)? ($finish_goods_opening_list->opening_transaction_finish_goods_cost) :0);

									
									$total_closing_cost=$total_closing_cost+$list['inwards_cost']-(($list['outwards_qty']*$list['inwards_rate'])-($list['return_qty']*$list['inwards_rate']));
									$total_inwards_cost=$total_inwards_cost+$list['inwards_cost'];
									$total_outwards_cost=$total_outwards_cost+$list['outwards_cost']-$list['return_cost'];

									$total_opening_qty=$total_opening_qty+(isset($finish_goods_opening_list->opening_transaction_finish_goods_quantity)? ($finish_goods_opening_list->opening_transaction_finish_goods_quantity) :0);
									
									$total_closing_qty=$total_closing_qty+$list['inwards_qty']-($list['outwards_qty']-$list['return_qty']);
									$total_inwards_qty=$total_inwards_qty+$list['inwards_qty'];
									$total_outwards_qty=$total_outwards_qty+$list['outwards_qty']-$list['return_qty'];

							?>
							@endforeach
								<tr>
									<th colspan="3"><strong>Grand Total</strong></th>
					                <th>{{$total_opening_qty}}</th>
					                <th>{{$total_opening_cost}}</th>
					                <th>{{$total_inwards_qty}}</th>
					                <th>{{$total_inwards_cost}}</th>
					                <th>{{$total_outwards_qty}}</th>
					                <th>{{$total_outwards_cost}}</th>
					                <th>{{$total_closing_qty}}</th>
					                <th>{{$total_closing_cost}}</th>
								</tr>
							@else
								<tr>
									<td colspan="15" class="text-center"> No data Available</td>
								</tr>
							@endif

						</tbody>
					</table>
				</div>
			</div>

		</div>
	</div>
</div>
@stop