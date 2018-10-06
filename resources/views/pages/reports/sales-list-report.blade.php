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
				<strong> Sales Report </strong>
				<a target="_blank" href="{{url('/reports/total-sales-list/pdf/from-'.(isset($search_from)? $search_from : '').'/to-'.(isset($search_to)? $search_to :'').'/cid-'.(isset($cost_center)? $cost_center :''))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="Sales PDF Download" style="margin-left:10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>

				<a target="_blank" href="{{url('/reports/total-sales-list/print/from-'.(isset($search_from)? $search_from : '').'/to-'.(isset($search_to)? $search_to :'').'/cid-'.(isset($cost_center)? $cost_center :''))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="Sales PDF Print" ><i class="fa fa-print" aria-hidden="true"></i></a>
			</div>


			<div class="panel-body">
				<div class="row">
					<form method="get" action="{{url('/reports/sales-list')}}">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
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
				                <th class="text-center">Perticuler Name</th>
				                <th>Amount </th>
				            </tr>

						</thead>
						<tbody>


						@if(!empty($total_receviable_debit_amount) || !empty($total_bank_debit_amount) || !empty($total_cash_debit_amount))
							<tr>
								<td><a href="{{url('/reports/sales-list/from-'.$search_from.'/to-'.$search_to.'/cid-'.$cost_center.'/'.'Account Receivable')}}"> Account Receivable </a></td>
								<td>{{$total_receviable_debit_amount}}</td>
							</tr>

							<tr>
								<td><a href="{{url('/reports/sales-list/from-'.$search_from.'/to-'.$search_to.'/cid-'.$cost_center.'/'.'Bank Accounts')}}">Bank Accounts </a></td>
								<td>{{$total_bank_debit_amount}}</td>
							</tr>

							<tr>
								<td><a href="{{url('/reports/sales-list/from-'.$search_from.'/to-'.$search_to.'/cid-'.$cost_center.'/'.'Cash-in-Hand')}}">Cash-in-Hand</a></td>
								<td>{{$total_cash_debit_amount}}</td>
							</tr>

							<tr>
				                <th class="text-center">Grand Total</th>
				                <th>{{isset($total_debit_amount)? $total_debit_amount : 0}}</th>
				            </tr>
						@else
							<tr>
								<td colspan="2" class="text-center">No data Available</td>
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