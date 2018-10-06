@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-md-12">
		@if($errors->count() > 0 )

		<div class="alert alert-danger">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<h6>The following errors have occurred:</h6>
			<ul>
				@foreach( $errors->all() as $message )
				<li>{{ $message }}</li>
				@endforeach
			</ul>
		</div>
		@endif

		@if(Session::has('message'))
		<div class="alert alert-success" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('message') }}
		</div> 
		@endif

		@if(Session::has('errormessage'))
		<div class="alert alert-danger" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('errormessage') }}
		</div>
		@endif

	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="clip-users-2"></i>
				All Transaction
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


			<div class="panel-body">
				<form method="get" action="{{url('/general/transaction-list')}}">
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

			<!-- <div class="panel-body panel-scroll" style="height:450px, margin-bottom:20px"> -->
			<div class="panel-body">
				<div class="table-responsive cost_list posting_list">
					<table class="table table-hover table-bordered table-striped nopadding" id="sample-table-1">
					<caption>You can not delete or update all type data !!!!</caption>
						<thead>
							<tr>
								<th>SL</th>
								<th>Transaction Date</th>
								<th>Transactions Naration</th>
								<th>Transaction Amount</th>
								<th>Cost Center</th>
								<th>Posting Type</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody class="cost_list">
							@if(!empty($all_transaction) && count($all_transaction) > 0)
							@foreach($all_transaction as $key => $list)
							<tr >
								<td>{{$key+1}}</td>
								<td>{{$list->transactions_date}}</td>
								<td>{{$list->transactions_naration}}</td>
								<td>{{$list->transaction_amount}}</td>
								<td>{{$list->cost_center_name}}</td>
								<td>{{$list->posting_type}}</td>

								<td>
									<a href="{{url('/general/transaction-list/edit/id-'.$list->transactions_id.'/type-'.$list->posting_type_slug)}}" class="btn btn-xs btn-green tooltips" data-toggle1="tooltip" title="General Transaction Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
									<a class="btn btn-xs btn-bricky tooltips transactions_delete"  data-id="{{$list->transactions_id}}" data-type="{{$list->posting_type_slug}}" data-toggle1="tooltip" title="General Transaction Delete"><i class="fa  fa-trash-o"></i></a>
									<a href="{{url('/journal/posting/print/'.$list->transactions_id)}}" class="btn btn-xs btn-green tooltips" data-toggle1="tooltip" title="General Transaction PDF"><i class="fa fa-print" aria-hidden="true"></i></a>
									<a href="#" class="btn btn-xs btn-bricky tooltips" data-toggle1="tooltip" title="General Transaction Share"><i class="fa fa-share"></i></a>
								</td>	
							</tr>
							@endforeach
							@else
							<tr class="text-center">
								<td colspan="7">No Data available</td>
							</tr>
							@endif
						</tbody>
					</table>
					{{isset($transaction_pagination)?$transaction_pagination:''}}
				</div>

			</div>
		</div>
	</div>
</div>

@stop