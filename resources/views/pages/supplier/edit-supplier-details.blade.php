@extends('layout.master')
@section('content')
<!--error message*******************************************-->
<div class="row">
	<div class="col-md-6">
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

			<div class="panel-body">
				<div class="col-md-12">
				<form method="post" action="{{url('/update/supplier/id-'.$selected_supplier_list->supplier_id)}}" role="form" class="form-horizontal">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="form-group">
						<label for="Debit_naration" class="col-md-2">
							Company
						</label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="supplier_company" value="{{$selected_supplier_list->supplier_company}}" readonly="">
						</div>
					</div>

					<div class="form-group">
						<label for="Debit_naration" class="col-md-2">
							Customer Name
						</label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="supplier_name" value="{{$selected_supplier_list->supplier_name }}" required>
						</div>
					</div>

					<div class="form-group">
						<label for="Debit_naration" class="col-md-2">
							Mobile
						</label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="supplier_mobile" value="{{$selected_supplier_list->supplier_mobile }}" required>
						</div>
					</div>

					<div class="form-group">
						<label for="Debit_naration" class="col-md-2">
							Email
						</label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="supplier_email" value="{{$selected_supplier_list->supplier_email }}" required>
						</div>
					</div>

					<div class="form-group">
						<label for="Debit_naration" class="col-md-2">
							Tax Reg No
						</label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="supplier_tax_reg_no" value="{{$selected_supplier_list->supplier_tax_reg_no }}">
						</div>
					</div>


					<div class="form-group">
						<label for="Debit_naration" class="col-md-2">
							Address
						</label>
						<div class="col-md-10">
							<textarea name="supplier_address" class="form-control" cols="20" rows="6" required>{{$selected_supplier_list->supplier_address}}</textarea>
						</div>
					</div>

					<div class="form-group">
						<input type="submit" class="btn btn-success pull-right" name="save" value="Update">
						<a href="{{url('/supplier/list')}}" class="btn btn-danger pull-right">Cancel</a>
					</div>
				</form>
				</div>
			</div>

		</div>
	</div>
</div>



@stop
