@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-md-12" style="margin-bottom:20px;">
		<form method="get" action="{{url('/reports/cash-flow')}}">
			<div class="col-md-3">
				<label class="col-md-4 text-right btn btn-default active"><strong> From  <i class="fa fa-arrow-right" aria-hidden="true"></i></strong></label>
				<div class="input-group">
					<input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="from" data-link-field="form_dtp_input" value="{{(isset($_GET['from']) && !empty($_GET['from']) ) ? $_GET['from']:date('Y-m-d') }}" required>
					<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
				
				</div>
			</div>

			<div class="col-md-3">
				<label class="col-md-4 text-right btn btn-default active"><strong> To  <i class="fa fa-arrow-right" aria-hidden="true"></i></strong></label>
				<div class="input-group">
					<input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="to" data-link-field="form_dtp_input" value="{{(isset($_GET['to']) && !empty($_GET['to']) ) ? $_GET['to']:date('Y-m-d') }}" required>
					<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
				
				</div>
			</div>
			<div class="col-md-4">
				<label class="col-md-4 text-right btn btn-default active"><strong>Cost Center</strong></label>
				<div class="input-group">
					<select class="form-control" name="cost_center_id" >
						<option value="">Select Cost Center</option>
						@if(isset($cost_centers) && (count($cost_centers)>0))
							@foreach($cost_centers as $key => $center)
							<option {{(isset($_GET['cost_center_id']) && !empty($_GET['cost_center_id']) && $_GET['cost_center_id']==$center->cost_center_id) ? 'selected':'' }} value="{{$center->cost_center_id}}">{{$center->cost_center_name}}</option>
							@endforeach
						@endif
					</select>
				</div>
			</div>

			<div class="col-md-2">
				<button class="btn btn-success" name="action"  value="view" type="Submit">View</button>
			</div>
		</form>
	</div><!--End  View Date-->

	<div class="col-md-12 invoice cashflow_Conatainer"><!--Start  Cashflow-->
		<div class="row invoice-logo">
			<div class="col-sm-6">
				<img alt="" src="{{(isset($company_info->company_logo) && !empty($company_info->company_logo)) ? asset($company_info->company_logo):''}}" title="{{(isset($company_info->company_name) && !empty($company_info->company_name)) ? $company_info->company_name:'Company Logo'}}" alt="{{(isset($company_info->company_name) && !empty($company_info->company_name)) ? $company_info->company_name:'Company Logo'}}">
			</div>
			<div class="col-sm-6">
				<p>
					<strong>{{(isset($company_info->company_title) && !empty($company_info->company_title)) ? $company_info->company_title:'Company Title'}}</strong><span> {{(isset($company_info->company_address) && !empty($company_info->company_address)) ? $company_info->company_address:'Company Address'}}</span>
				</p>
			</div>
			
		</div>
		<div class="col-md-12">
			<h2 class="text-center">Cash Flow</h2>
			<span class="text-center"><p>{{isset($from)&& !empty($from) ? date('d-M-Y',strtotime($from)):''}} {{isset($to)&& !empty($to) ? 'to '.date('d-M-Y',strtotime($to)):''}}</p></span>
		</div>
		
	
		<div class="row"><!--Start  Table-->
			<div class="table-responsive col-md-12 ">
				<table class="table table-hover table-bordered table-striped nopadding text-center">
					<thead>
						<tr>
							<th>Particular</th>
							@if(isset($cost_center_info) && !empty($cost_center_info))
							<th>Cost Center</th>
							@endif
							<th>InFlow Amount</th>
							<th>OutFlow Amount</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($all_cashflow_data) && count($all_cashflow_data)>0)
							<tr>
								@php($get_url = parse_url(\Request::fullUrl(), PHP_URL_QUERY))
								<td><a href="{{url('/reports/cash-flow/ledger?'.$get_url)}}">{{(isset($company_info->company_title) && !empty($company_info->company_title)) ? $company_info->company_title:''}}</a></td>
								@if(isset($cost_center_info) && !empty($cost_center_info))
								<th>{{(isset($cost_center_info->cost_center_name) && !empty($cost_center_info->cost_center_name)) ? $cost_center_info->cost_center_name:''}}</th>
								@endif
								<td>{{(isset($all_cashflow_data['total_inflow']) && !empty($all_cashflow_data['total_inflow'])) ? number_format($all_cashflow_data['total_inflow'],2,'.',''):'0.0'}}</td>
								<td>{{(isset($all_cashflow_data['total_outflow']) && !empty($all_cashflow_data['total_outflow'])) ? number_format($all_cashflow_data['total_outflow'],2,'.',''):'0.0'}}</td>
							</tr>
						@else
							<tr>
								<td colspan="3">No Data Available</td>
							</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div><!--End  Table-->
	
	</div><!--End  Cashflow-->

</div><!--End  row-->
@stop