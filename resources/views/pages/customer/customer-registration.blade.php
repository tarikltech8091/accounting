@extends('layout.master')
@section('content')

<!--error message*******************************************-->
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
<!--end of error message*************************************-->



<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-external-link-square"></i>
				Customer Table
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"> </a>
					<a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12 space20">
						<a class="btn btn-green panel-config pull-right" href="#panel-config" data-toggle="modal"> Add New Customer <i class="fa fa-plus" aria-hidden="true"></i> </a>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_2">
						<thead>
							<tr>
								<th>SL</th>
								<th>Name</th>
								<th>Company</th>
								<th>Mobile</th>
								<th>Email</th>
								<th>Tax No</th>
								<th>Address</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(!empty($customer_data))
							@foreach($customer_data as $key => $user)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$user->customer_name}}</td>
								<td>{{$user->customer_company}}</td>
								<td>{{$user->customer_mobile}}</td>
								<td>{{$user->customer_email}}</td>
								<td>{{$user->customer_tax_reg_no}}</td>
								<td>{{str_limit($user->customer_address, 20)}}</td>
								<td>
									<button data-toggle="modal" data-target="#editModal" class="btn btn-teal edit_customer_settings" data-id="{{$user->customer_id}}" data-type="degree"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

									<a href="{{url('/customer/delete',$user->customer_id)}}" class="btn btn-danger"><i class="fa  fa-trash-o"></i></a>
								</td>

							</tr>
							@endforeach
							@else
							<tr>
								<td colspan="8"> No Data Available</td>
							</tr>
							@endif
						</tbody>
					</table>
					{{$customer_pagination? $customer_pagination :''}}

				</div>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="panel-config" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">Customer Registration</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
					<form method="post" action="{{url('/customer/registration')}}" role="form" class="form-horizontal">
						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Customer Name
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="customer_name" value="">
							</div>
						</div>

						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Company
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="customer_company" value="">
							</div>
						</div>

						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Mobile
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="customer_mobile" value="">
							</div>
						</div>

						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Email
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="customer_email" value="">
							</div>
						</div>

						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Tax Reg No
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="customer_tax_reg_no" value="">
							</div>
						</div>


						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Address
							</label>
							<div class="col-md-9">
								<textarea name="customer_address" class="form-control" cols="20" rows="6">
									
								</textarea>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<input type="submit" class="btn btn-success pull-right" name="submit" value="Submit">
							</div>
						</div>
					</form>

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					Close
				</button>
			</div>
		</div>
	</div>
</div>


<div id="editModal" class="modal fade" rtabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-body edit_view">
				<div class="ajax_loader loading_icon"></div>
			</div>
<!-- 
			<div class="modal-footer">
				<button type="button" class="btn btn-default"  data-dismiss="modal">OK</button>
			</div> -->
		</div>
	</div>
</div>


@stop