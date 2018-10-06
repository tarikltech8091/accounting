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
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="clip-users-2"></i>
				Cost Center
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
				<form action="{{url('/dashboard/cost-center/edit/'.$edit_cost->cost_center_id)}}" method="POST">
			 		<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="form-group">
						<label for="Debit_naration" class="col-md-3">
							Cost Name
						</label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="cost_center_name" value="{{isset($edit_cost->cost_center_name)? $edit_cost->cost_center_name :''}}">
						</div>
					</div>

					<div class="form-group pull-right" style="margin-top:10px;">  
						<a class="btn btn-default" href="{{url('/dashboard/cost-center')}}">
						Cancel 
						</a>
						<button class="btn btn-purple" type="submit">
						Update <i class="fa fa-arrow-circle-right"></i>
						</button>
					</div>
				</form>

			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="clip-users-2"></i>
				All Cost
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

				<div class="table-responsive">
					<table class="table table-hover table-bordered table-striped nopadding" id="sample-table-1">
						<thead>
							<tr>
								<th>SL</th>
								<th>Cost Name</th>
								<th>Date</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(!empty($all_cost) && count($all_cost) > 0)
							@foreach($all_cost as $key => $list)
							<tr >
								<td>{{$key+1}}</td>
								<td>{{$list->cost_center_name}}</td>
								<td>{{$list->updated_at}}</td>
								<td>
									<a href="{{url('/dashboard/cost-center/edit',$list->cost_center_id)}}" class="btn btn-xs btn-green tooltips" data-toggle1="tooltip" title="Cost Center Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
									<a href="{{url('/dashboard/cost-center/delete',$list->cost_center_id)}}" class="btn btn-xs btn-bricky tooltips" data-toggle1="tooltip" title="Cost Center Delete"><i class="fa  fa-trash-o"></i></a>
									</a>
								</td>
							</tr>
							@endforeach
							@else
							<tr class="text-center">
								<td colspan="4">No Data available</td>
							</tr>
							@endif
						</tbody>
					</table>
					{{isset($cost_pagination) ? $cost_pagination:""}}
				</div>

			</div>
		</div>
	</div>
</div>
@stop