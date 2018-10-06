@extends('layout.master')
@section('content')
@include('layout.bradecrumb')

<div class="row page_row">
	<div class="col-md-12">
		<div class="panel panel-info">
			<div class="panel-heading">Student List</div>
			<div class="panel-body">

				@if(!empty($users))
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>SL</th>
							<th>Name</th>
							<th>User ID</th>
							<th>User Type</th>
							<th>Login Status</th>
							<th>User Status</th>
						</tr>
					</thead>
					<tbody>
						
						@foreach($users as $key => $list)
						<tr>
							<td>{{$key+1}}</td>
							<td>{{$list->name}}</td>
							<td>{{$list->user_id}}</td>
							<td>{{$list->user_type}}</td>
							<td>{{(isset($list->login_status) && ($list->login_status=='1')) ? 'LoggedIn' : 'Not LoggedIn'}}</td>
							<td>{{(isset($list->status) && ($list->status=='1')) ? 'Active' : 'Inactive'}}</td>
						</tr>
						@endforeach
						
					</tbody>
				</table>
				@else
				<!-- empty message -->
				<div class="alert alert-success">
					<center><h3 style="font-style:italic">No Data Available !</h3></center>
				</div>
				@endif
			</div>
		</div>
	</div>
</div>

@stop