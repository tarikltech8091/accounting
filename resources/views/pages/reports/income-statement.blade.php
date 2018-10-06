@extends('layout.master')
@section('content')
<div class="row" style="margin-bottom:10px;">
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
				<strong> Income Statement </strong>
				<a href="{{url('/income-statement/pdf/from-'.(isset($search_from)? $search_from : '').'/to-'.(isset($search_to)? $search_to :'').'/ccid-'.(isset($cost_center)? $cost_center :''))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="Income Statement Download" style="margin-left:10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>

				<a target="_blank" href="{{url('/income-statement/print/from-'.(isset($search_from)? $search_from : '').'/to-'.(isset($search_to)? $search_to :'').'/ccid-'.(isset($cost_center)? $cost_center :''))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="Income Statement Print" ><i class="fa fa-print" aria-hidden="true"></i></a>
			</div>


			<div class="panel-body">
				<div class="row">
					<form method="get" action="{{url('/income-statement/report')}}">
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
							<caption>Income Statement</caption>
						</thead>
						<tbody>
						<tr><th colspan="4">Revenues</th></tr>
						<?php
							$Grand_total_finish_goods=0;
						?>
						@if(!empty($total_finish_goods) && count($total_finish_goods)>0)
								<tr>
									<td colspan="2" style="padding-left:50px;">Merchandise Sales</td>
									
									<?php 
        								$finish_goods_total_balance = \App\Report::MakePositiveData($total_finish_goods);
									?>

									<td  align="left" class="text-center">{{$finish_goods_total_balance}}</td>
									<td class="text-center"></td>
									
								</tr>
						@endif

						<?php
							$Grand_total_other_incomes=0;
						?>

						@if(!empty($total_other_incomes) && count($total_other_incomes)>0)
							@foreach($total_other_incomes as $key => $list)
								<tr>
									<td colspan="2" style="padding-left:50px;">{{$list['ledger_name']}}</td>
									
									<?php 
        								$other_incomes_balance = \App\Report::SummationOfDebitAndCreditData($list['debit'],$list['credit']);
        								$other_incomes_total_balance = \App\Report::MakePositiveData($other_incomes_balance);
									?>

									<td  align="left" class="text-center">{{$other_incomes_total_balance}}</td>
									<td class="text-center"></td>
									
								</tr>
								<?php $Grand_total_other_incomes=$Grand_total_other_incomes+$other_incomes_total_balance; ?>
							@endforeach
						@endif

							<?php
								$total_revenues=0;
								$grand_total_revenues=0;
							?>

							<tr>
								<td colspan="2" style="padding-left:50px;"></td>
								<?php
									$total_revenues=(isset($total_finish_goods)?$total_finish_goods:'')+$Grand_total_other_incomes;
    								$grand_total_revenues = \App\Report::MakePositiveData($total_revenues);
								?>
								<td  align="left" class="text-center"></td>
								<td class="text-center">{{$grand_total_revenues}}</td>
								
							</tr>

						<tr><th colspan="4">Expenses</th></tr>

						<?php
							$total_cost_of_goods_sold=0;
							$grand_cost_of_goods_sold=0;
						?>

							<tr>
								<td colspan="2" style="padding-left:50px;">Cost Of Goods Sold</td>
								<?php
									$total_cost_of_goods_sold=(isset($all_finish_goods_opening_balance)?$all_finish_goods_opening_balance : 0)+(isset($cost_production)?$cost_production : 0)-(isset($all_finish_goods_closing_balance)?$all_finish_goods_closing_balance : 0);

    								$grand_cost_of_goods_sold = \App\Report::MakePositiveData($total_cost_of_goods_sold);
								?>
								<td  align="left" class="text-center">{{$grand_cost_of_goods_sold}}</td>
								<td class="text-center"></td>
								
							</tr>


						<?php
							$Grand_total_indirect_expenses=0;
						?>

						@if(!empty($total_indirect_expenses) && count($total_indirect_expenses)>0)
							@foreach($total_indirect_expenses as $key => $list)
								<tr>
									<td colspan="2" style="padding-left:50px;">{{$list['ledger_name']}}</td>

									<?php 
        								$indirect_expenses_balance = \App\Report::SummationOfDebitAndCreditData($list['debit'],$list['credit']);
        								$indirect_expenses_total_balance = \App\Report::MakePositiveData($indirect_expenses_balance);
									?>


									<td  align="left" class="text-center">{{$indirect_expenses_total_balance}}</td>
									<td class="text-center"></td>
									
								</tr>
								<?php $Grand_total_indirect_expenses=$Grand_total_indirect_expenses+$indirect_expenses_balance; ?>
							@endforeach
						@endif


								<tr>
									<th colspan="2" style="padding-left:50px;"></th>
									<?php
										$total_expenses=0;
										$total_expenses=$total_cost_of_goods_sold+$Grand_total_indirect_expenses;
                						$show_total_expenses= \App\Report::MakePositiveData($total_expenses);
	    								
									?>
									<td  align="left" class="text-center"></td>
									<td class="text-center">{{$show_total_expenses}}</td>
									
								</tr>

							<?php
								$total_incomes=0;
								$grand_total_incomes=0;
								if($total_expenses<0){
									$total_expenses=(-1)*$total_expenses;
								}

							?>

								<tr>
									<th colspan="3">Net Income</th>
									<?php
									if($total_revenues<0){
										$total_revenues=$total_revenues*(-1);
									}
									if($total_expenses<0){
										$total_expenses=$total_expenses*(-1);
									}
										$total_incomes=$total_revenues-$total_expenses;
	    								$grand_total_incomes = \App\Report::MakePositiveData($total_incomes);

									?>
									<th class="text-center">{{$grand_total_incomes}}</th>
									
								</tr>

						</tbody>
					</table>

				</div>
			</div>

		</div>
	</div>
</div>
@stop