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
			
			<div class="table-responsive col-md-6 ">
			
				
				<table class="table  table-bordered  nopadding text-center">
					<thead>
						<tr>
							<th colspan="3">Cash Inflow</th>
						</tr>
						<tr>
							<th colspan="2">Particulars</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($cash_accounts_ledger) && count($cash_accounts_ledger)>0)
								@php($node=0)
								@foreach($cash_accounts_ledger as $key => $accounts_ledger)
									<tr>
										@if($node==0)
										<td rowspan="{{count($cash_accounts_ledger)}}">Cash</td>
										@endif
										@php($node++)
										@php($total_cash_inflow=0)
										
										<td>{{$accounts_ledger[0]->ledger_name}}</td>
									
										@foreach($accounts_ledger as $key => $ledger)
											@if($ledger->journal_particular_amount_type=='debit')
												@php($total_cash_inflow=$total_cash_inflow + $ledger->journal_particular_amount)
											@endif
										@endforeach
										<td>{{number_format($total_cash_inflow,2,'.','')}}</td>
									</tr>
								@endforeach
						@endif
						@if(isset($bank_accounts_ledger) && count($bank_accounts_ledger)>0)
								@php($node=0)
								@foreach($bank_accounts_ledger as $key => $accounts_ledger)
									<tr>
										@if($node==0)
										<td rowspan="{{count($bank_accounts_ledger)}}">Bank Accounts</td>
										@endif
										@php($node++)
										@php($total_bank_inflow=0)
										
										<td>{{$accounts_ledger[0]->ledger_name}}</td>
									
										@foreach($accounts_ledger as $key => $ledger)
											@if($ledger->journal_particular_amount_type=='debit')
												@php($total_bank_inflow=$total_bank_inflow + $ledger->journal_particular_amount)
											@endif
										@endforeach
										<td>{{number_format($total_bank_inflow,2,'.','')}}</td>
									</tr>
								@endforeach
						@endif
						@if(isset($cash_accounts_ledger) && count($cash_accounts_ledger)==0 && isset($bank_accounts_ledger) && count($bank_accounts_ledger)==0)
							<tr>
								<td colspan="3">No data available</td>
							</tr>
						@endif
					</tbody>
				</table>
			</div>
			<div class="table-responsive col-md-6 ">	
				<table class="table  table-bordered  nopadding text-center">
					<thead>
						<tr>
							<th colspan="3">Cash Outflow</th>
						</tr>
						<tr>
							<th colspan="2">Particulars</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($cash_accounts_ledger) && count($cash_accounts_ledger)>0)
								@php($node=0)
								@foreach($cash_accounts_ledger as $key => $accounts_ledger)
									<tr>
										@if($node==0)
										<td rowspan="{{count($cash_accounts_ledger)}}">Cash</td>
										@endif
										@php($node++)
										@php($total_cash_outflow=0)
										
										<td>{{$accounts_ledger[0]->ledger_name}}</td>
									
										@foreach($accounts_ledger as $key => $ledger)
											@if($ledger->journal_particular_amount_type=='credit')
												@php($total_cash_outflow=$total_cash_outflow + $ledger->journal_particular_amount)
											@endif
										@endforeach
										<td>{{number_format($total_cash_outflow,2,'.','')}}</td>
									</tr>
								@endforeach
						@endif
						@if(isset($bank_accounts_ledger) && count($bank_accounts_ledger)>0)
								@php($node=0)
								@foreach($bank_accounts_ledger as $key => $accounts_ledger)
									<tr>
										@if($node==0)
										<td rowspan="{{count($bank_accounts_ledger)}}">Bank Accounts</td>
										@endif
										@php($node++)
										@php($total_bank_outflow=0)
										
										<td>{{$accounts_ledger[0]->ledger_name}}</td>
									
										@foreach($accounts_ledger as $key => $ledger)
											@if($ledger->journal_particular_amount_type=='credit')
												@php($total_bank_outflow=$total_bank_outflow + $ledger->journal_particular_amount)
											@endif
										@endforeach
										<td>{{number_format($total_bank_outflow,2,'.','')}}</td>
									</tr>
								@endforeach
						@endif
						@if(isset($cash_accounts_ledger) && count($cash_accounts_ledger)==0 && isset($bank_accounts_ledger) && count($bank_accounts_ledger)==0)
							<tr>
								<td colspan="3">No data available</td>
							</tr>
						@endif
					</tbody>
				</table>
			</div>
			@php($get_url = parse_url(\Request::fullUrl(), PHP_URL_QUERY))
			@php($get_url = !empty($get_url) ? '?'.$get_url:'')
			<div class="col-md-3">

			<a href="{{url('/reports/cash-flow/'.$get_url)}}" class="btn btn-info">Back</a>	
			</div>
		</div><!--End  Table-->
	</div><!--End  Cashflow-->

</div><!--End  row-->
@stop