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
				<strong> Purchase Details Report </strong>
				<a target="_blank" href="{{url('/reports/purchase-list/pdf/from-'.(isset($search_from)? $search_from :'').'/to-'.(isset($search_to)? $search_to :'').'/cid-'.(isset($cost_center_id)? $cost_center_id :'').'/type-'.(isset($type)? $type :''))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="PDF Download" style="margin-left:10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
				<a target="_blank" href="{{url('/reports/purchase-list/print/from-'.(isset($search_from)? $search_from :'').'/to-'.(isset($search_to)? $search_to :'').'/cid-'.(isset($cost_center_id)? $cost_center_id :'').'/type-'.(isset($type)? $type :''))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="PDF Print"><i class="fa fa-print" aria-hidden="true"></i></a>
			</div>


			<div class="panel-body">
				<div class="table-responsive cost_list posting_list">
					<table class="table table-hover table-bordered table-striped nopadding">
						<thead>
				            <tr>
				                <th>SL </th>
				                <th class="text-center">Perticuler Name</th>
				                <th>Amount </th>
				            </tr>

						</thead>
						<tbody>


						@if(!empty($purchase_details_list) && count($purchase_details_list)>0)
						@foreach ($purchase_details_list as $key => $list)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$list->journal_particular_name}}</td>
								<td>{{$list->journal_particular_amount}}</td>
							</tr>
						@endforeach
							<tr>
								<th colspan="2" class="text-center"> Grand Total </th>
								<th>{{isset($total_purchase_amount)? $total_purchase_amount:''}}</th>
							</tr>
						@else
							<tr>
								<td colspan="3" class="text-center">No data Available</td>
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