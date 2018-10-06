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


<div class="row" style="margin-bottom:30px;">
	<div class="col-sm-12">
		<div class="tabbable">
			<ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4">
				<li class="{{($tab=='company_update') ? 'active' : ''}}">
					<a data-toggle="tab" href="#company_overview">
						Company Overview
					</a>
				</li>

				<li class="{{($tab=='create_new') ? 'active' : ''}}">
					<a data-toggle="tab" href="#create_new">
						Insert and Update
					</a>
				</li>

			</ul>

			<div class="tab-content">

				<div id="company_overview" class="tab-pane in {{$tab=='company_overview' ? 'active':''}}">

					<div class="row">
						<!-- <h3 colspan="3">Company Information</h3> -->
						<div class="col-md-4">
							<br/>
							<table class="table table-condensed table-hover">
								<thead>
									<tr>
										<th colspan="3">Contact Information</th>
									</tr>
								</thead>
								<tbody>

									<tr>
										<td>Company Name   :</td>
										<td>
											{{isset($company_info->company_name) ? strtoupper($company_info->company_name) : ''}}
										</td>
									</tr>

									<tr>
										<td>Company Email  :</td>
										<td>
											{{isset($company_info->company_email) ? $company_info->company_email : ''}}
										</td>
									</tr>
									<tr>
										<td>Company Contact :</td>
										<td>{{isset($company_info->company_contact) ? $company_info->company_contact : ''}}</td>
									</tr>

									<tr>
										<td>Company Title:</td>
										<td>{{isset($company_info->company_title) ? $company_info->company_title : ''}}</td>
									</tr>


								</tbody>
							</table>

						</div>

						<div class="col-md-4">
							<br/>
							<table class="table table-condensed table-hover">
							<thead>
								<tr>
									<th colspan="3">Details Information</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Company Moto:</td>
									<td>{{isset($company_info->company_moto) ? $company_info->company_moto : ''}}</td>
								</tr>


								<tr>
									<td>Company Tax No:</td>
									<td>{{isset($company_info->company_tax_no) ? $company_info->company_tax_no : ''}}</td>
								</tr>


								<tr>
									<td>Foundation Date:</td>
									<td>{{isset($company_info->foundation_date) ? $company_info->foundation_date : ''}}</td>
								</tr>


								<tr>
									<td>Company Address :</td>
									<td>{{isset($company_info->company_address) ? $company_info->company_address : ''}}</td>
								</tr>

							</tbody>
							</table>
						</div>

						<div class="col-md-4">
							<br/>
							<table class="table table-condensed table-hover">
								<thead>
									<tr>
										<th colspan="3">Logo Information</th>
									</tr>
								</thead>
							<tbody>
							<tr>
								<td>
									@if(!empty($company_info->company_logo))
									<img src="{{asset($company_info->company_logo)}}" alt="User Profile Photo">
									@else
									<img src="{{asset('assets/images/profile.png')}}" alt="">
									@endif
								</td>
							</tr>
							</tbody>
							</table>
						</div>
					</div>

				</div>

				<div id="create_new" class="tab-pane in {{$tab =='create_new' ? 'active':''}}">
					<div class="row">
						<div class="col-md-12">
							<form action="{{url('/dashboard/company/info')}}" method="post" enctype="multipart/form-data" role="form" id="form">

								<div class="row">
									<div class="col-md-12">
										<h3>Company Info</h3>
										<hr>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">
												Company Name
											</label>
											<input type="text" placeholder="Company Name" class="form-control" id="name" name="company_name" value="{{isset($company_info->company_name) ? $company_info->company_name : ''}}" required>
										</div>

										<div class="form-group">
											<label class="control-label">
												Contact
											</label>
											<input type="text" placeholder="Contact" class="form-control" name="company_contact" value="{{isset($company_info->company_contact) ? $company_info->company_contact : ''}}" >
										</div>

										<div class="form-group">
											<label class="control-label">
												Email Address
											</label>
											<input type="email" placeholder="email@example.com" class="form-control" name="company_email" value="{{isset($company_info->company_email) ? $company_info->company_email : ''}}" required>
										</div>


										<div class="form-group">
											<label class="control-label">
												Company Title
											</label>
											<input type="text" class="form-control" name="company_title" placeholder="Title" value="{{isset($company_info->company_title) ? $company_info->company_title : ''}}" required>
										</div>


										<div class="form-group">
											<label class="control-label">
												Company Moto
											</label>
											<input type="text" placeholder="Moto" class="form-control" name="company_moto" value="{{isset($company_info->company_moto) ? $company_info->company_moto : ''}}">
										</div>

										<div class="form-group">
											<label class="control-label">
												Company Tax No
											</label>
											<input type="text" class="form-control" placeholder="Tax No" name="company_tax_no" value="{{isset($company_info->company_tax_no) ? $company_info->company_tax_no : ''}}">
										</div>

									</div>



									<div class="col-md-6">

										<div class="form-group">
											<label class="control-label"> Foundation Date </label>
											<div class="input-group">
												<input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="foundation_date" data-link-field="form_dtp_input" value="{{isset($company_info->foundation_date) ? $company_info->foundation_date : ''}}">
												<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
											
											</div>
										</div>

										<div class="form-group">
											<label class="control-label">
												Company Address
											</label>
											<textarea class="form-control" name="company_address" cols="20" rows="3" required>{{isset($company_info->company_address) ? $company_info->company_address : ''}}</textarea>
										</div>
											<input type="hidden" name="company_id" value="{{isset($company_info->company_id) ? $company_info->company_id : ''}}">

										<div class="fileupload fileupload-new" data-provides="fileupload">

											<div class="fileupload-new thumbnail" style="width: 200px; height: 70px;">
												@if(!empty($company_info->company_logo))
												<img src="{{asset($company_info->company_logo)}}" alt="User Profile Photo">
												@else
												<img src="{{asset('assets/images/profile.png')}}" alt="">
												@endif
											</div>
											<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 70px; line-height: 20px;"></div>

											<div class="user-edit-image-buttons">
												<span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture"></i> Change</span>
												<input type="file" name="company_logo">
											</span>
											<a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
												<i class="fa fa-times"></i> Remove
											</a>
										</div>
									</div>

								</div>
							</div>

							<div class="row">
								<div class="col-md-8">
									<p>

									</p>
								</div>
								<div class="col-md-4">
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<button class="btn btn-teal btn-block" type="submit">
										Register <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>



		</div>


	</div>
</div>

@stop