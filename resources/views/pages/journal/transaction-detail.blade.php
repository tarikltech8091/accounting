@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="clip-users-2"></i>
				Journal Transaction
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" data-toggle="tooltip" data-placement="top" title="Show / Hide" href="#">
					</a>
					<a class="btn btn-xs btn-link panel-close red-tooltip" data-toggle="tooltip" data-placement="top" title="Close" href="#">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>


			<div class="panel-body">
				<form method="get" action="{{url('/journal/transaction')}}">
					<!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->
					<div class="row">
						<div class="col-md-2">
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
						<div class="col-md-2">
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
							<div class="form-group ">
								<label for="form-field-23">
									Cost Center<span class="symbol required"></span>
								</label>
								<div class="input-group">
									<select name="cost_center" class="form-control">
										<option value=""> Select Cost</option>
										@if(!empty($all_cost) && count($all_cost)>0)
										@foreach ($all_cost as $key => $list){
										<option {{(isset($_GET['cost_center']) && ($_GET['cost_center'] == $list->cost_center_id)) ? 'selected':''}} value="{{$list->cost_center_id}}">{{$list->cost_center_name}}</option>
							
										@endforeach
										@endif
										
									</select>

								</div>
							</div>
						</div>


						<?php
							$all_posting_type=\DB::table('ltech_posting_types')->get();
						?>

						<div class="col-md-2">
							<div class="form-group ">
								<label for="form-field-23">
									Posting Type<span class="symbol required"></span>
								</label>
								<div class="input-group">
									<select name="post_type" class="form-control">
										<option value=""> Select Post Type</option>
										@if(!empty($all_posting_type) && count($all_posting_type)>0)
										@foreach ($all_posting_type as $key => $list){
										
										<option {{(isset($_GET['post_type']) && ($_GET['post_type'] == $list->posting_type_slug)) ? 'selected':''}} value="{{$list->posting_type_slug}}">{{$list->posting_type}}</option>

										@endforeach
										@endif
									</select>

 								</div>
							</div>
						</div>


						<?php
							$all_users=\DB::table('users')->get();
						?>

						<div class="col-md-2">
							<div class="form-group ">
								<label for="form-field-23">
									User<span class="symbol required"></span>
								</label>
								<div class="input-group">
									<select name="user_name" class="form-control">
										<option value=""> Select User</option>
										@if(!empty($all_users) && count($all_users)>0)
										@foreach ($all_users as $key => $list){

										<option {{(isset($_GET['user_name']) && ($_GET['user_name'] == $list->user_id)) ? 'selected':''}} value="{{$list->user_id}}">{{$list->name}}</option>
							
										@endforeach
										@endif
										
									</select>

								</div>
							</div>
						</div>
					
						<div class="col-md-2" style="margin-top:22px;">
							<div class="form-group">
								<input type="submit" class="btn btn-primary" data-toggle1="tooltip" title="Search Transaction" value="Search">
							</div>
						</div>
					</div>
				</form>	
			</div>


			<div class="panel-body">
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>Date</th>
							<th>Particulars</th>
							<th>Ref</th>
							<th>Debit(Tk.)</th>
							<th>Credit(Tk.)</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($journal_transaction) && count($journal_transaction) > 0)
							@foreach($journal_transaction as $key => $transaction_detail)
								
								 @foreach($transaction_detail as $keynode => $transaction)
								 	<tr>
								 	 @if($keynode==0)
									 <td rowspan="{{count($transaction_detail)}}">{{date('j F, Y',strtotime($transaction_detail[0]->journal_date))}}</td>
									 @endif

									 <td>{{$transaction->journal_particular_name}}</td>
									 <td>{{$transaction->transaction_id}}</td>
									 @if($transaction->journal_particular_amount_type=='debit')
										<td>{{number_format($transaction->journal_particular_amount, 2, '.', '')}}</td>
										<td></td>
									 @else
										<td></td>
										<td>{{number_format($transaction->journal_particular_amount, 2, '.', '')}}</td>
									 @endif
									 @if($keynode==0)
									 <td rowspan="{{count($transaction_detail)}}" class="center"><div class="visible-md visible-lg hidden-sm hidden-xs">
									 		<a target="_blank" href="{{url('/journal/posting/print/'.$transaction->transaction_id)}}" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="Print"><i class="fa fa-print"></i></a>
											<a href="#" class="btn btn-xs btn-teal tooltips" data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></a>
											<a href="#" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="Share"><i class="fa fa-share"></i></a>
											<a href="#" class="btn btn-xs btn-bricky tooltips" data-placement="top" data-original-title="Remove"><i class="fa fa-times fa fa-white"></i></a>
										</div>
										</td>
									 @endif
										 
									 </tr>
								 @endforeach
							@endforeach

						@else
							<tr>
								<td colspan="6" class="text-center">No Data Available</td>
							</tr>
						@endif
												
					</tbody>
				</table>
				{{isset($journal_pagination)?$journal_pagination:''}}
			</div>
		</div>
	</div>
</div>

@stop