@extends('layout.master')
@section('content')

<!--error message*******************************************-->
<div class="row ">
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

<!-- start: PAGE CONTENT -->
<div class="row ">
	<div class="col-sm-12">
		<div class="tabbable">
			<ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4">
				<li class="{{isset($tab) && ($tab=='panel_overview') ? 'active' : ''}}">
					<a data-toggle="tab" href="#panel_overview">
						Overview
					</a>
				</li>

				<li class="{{isset($tab) && ($tab=='panel_edit_account') ? 'active' : ''}}">
					<a data-toggle="tab" href="#panel_edit_account">
						Update Account
					</a>
				</li>

				<li class="{{isset($tab) && ($tab=='change_password') ? 'active' : ''}}">
					<a data-toggle="tab" href="#change_password">
						Change Password
					</a>
				</li>
			</ul>
			<div class="tab-content">
				<div id="panel_overview" class="tab-pane in {{isset($tab) && ($tab=='panel_overview') ? 'active' : ''}}">

					<div class="row">
						<div class="col-md-6">
								<div class="center">
									<h4>{{isset($user_info->name) ? $user_info->name : ''}}</h4>

									@if(!empty($user_info->user_profile_image))
									<img src="{{asset($user_info->user_profile_image)}}" alt="User Profile Photo">
									@else
									<img src="{{asset('assets/images/profile.png')}}" alt="User Profile Photo">
									@endif

								<!-- <hr> -->
								<!-- <p>
									<a class="btn btn-twitter btn-sm btn-squared" href="{{isset($user_twitter_account) ? $user_twitter_account : '#'}}" target="blank">
										<i class="fa fa-twitter"></i>
									</a>
									<a class="btn btn-linkedin btn-sm btn-squared" href="{{isset($user_linkedin_account) ? $user_linkedin_account : '#'}}" target="blank">
										<i class="fa fa-linkedin"></i>
									</a>
									<a class="btn btn-google-plus btn-sm btn-squared" href="{{isset($user_google_plus_account) ? $user_google_plus_account : '#'}}" target="blank">
										<i class="fa fa-google-plus"></i>
									</a>
									<a class="btn btn-linkedin btn-sm btn-squared" href="{{isset($user_facebook_account) ? $user_facebook_account : '#'}}" target="blank">
										<i class="fa fa-facebook"></i>
									</a>
								</p> -->
								<!-- <hr> -->

							</div>
							<table class="table table-condensed table-hover">
								<thead>
									<tr>
										<th colspan="3">Contact Information</th>
									</tr>
								</thead>
								<tbody>

									<tr>
										<td>Name:</td>
										<td>
											<a href="">
												{{isset($user_info->name) ? strtoupper($user_info->name) : ''}}
											</a></td>
											<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
										</tr>

										<tr>
											<td>Email:</td>
											<td>
												<a href="">
													{{isset($user_info->email) ? $user_info->email : ''}}
												</a></td>
												<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
											</tr>
											<tr>
												<td>Mobile:</td>
												<td>{{isset($user_info->user_mobile) ? $user_info->	user_mobile : ''}}</td>
												<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
											</tr>

										</tbody>
									</table>
									<table class="table table-condensed table-hover">
										<thead>
											<tr>
												<th colspan="3">General information</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Position</td>
												<td>{{isset($user_info->user_type) ? ucfirst($user_info->user_type) : ''}}</td>
												<td><a href="" class="show-tab"></a></td>
											</tr>
										</tbody>
									</table>
									<hr>
							</div>
						
						</div>
					</div>



					<div id="panel_edit_account" class="tab-pane in {{isset($tab) && ($tab=='panel_edit_account') ? 'active' : ''}}">
						<form action="{{url('/user/profile/update')}}" method="post" enctype="multipart/form-data" role="form" id="form">
							<div class="row">
								<div class="col-md-12">
									<h3>Account Info</h3>
									<hr>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">
											Name
										</label>
										<input type="text" placeholder="Name" class="form-control" id="lastname" name="name" value="{{isset($user_info->name) ? $user_info->name : ''}}">
									</div>

									<div class="form-group">
										<label class="control-label">
											Email Address
										</label>
										<input type="email" placeholder="email@example.com" class="form-control" id="email" name="email" value="{{isset($user_info->email) ? $user_info->email : ''}}">
									</div>
									<div class="form-group">
										<label class="control-label">
											Mobile
										</label>
										<input type="text" placeholder="User Mobile" class="form-control" id="phone" name="user_mobile" value="{{isset($user_info->user_mobile) ? $user_info->user_mobile : ''}}">
									</div>

								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label>
											Image Upload
										</label>
										<div class="fileupload fileupload-new" data-provides="fileupload">

											<div class="fileupload-new thumbnail profile_img_size" >@if(!empty($user_info->user_profile_image))
												<img src="{{asset($user_info->user_profile_image)}}" alt="User Profile Photo">
												@else
												<img src="{{asset('images/USER/profile.png')}}" alt="User Profile Photo">
												@endif
											</div>
											<div class="fileupload-preview fileupload-exists thumbnail profile_img_size" style="line-height: 20px;"></div>

											<div class="user-edit-image-buttons">
												<span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture"></i> Change</span>
												<input type="file" name="image_url" value="" />

											</span>
											<a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
												<i class="fa fa-times"></i> Remove
											</a>
										</div>
									</div>
								</div>

							</div>
						</div>
						<hr>

						<div class="row">
							<div class="col-md-8">
								<p>
									By clicking UPDATE, you are agreeing to the Policy and Terms &amp; Conditions.
								</p>
							</div>
							<div class="col-md-4">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<button class="btn btn-teal btn-block" type="submit">
									Update <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</div>
					</form>
				</div>


				<div id="change_password" class="tab-pane in {{isset($tab) && ($tab=='change_password') ? 'active' : ''}}">
					<div class="row">
						<div class="col-md-6  change_password">
							<div class="col-md-4">
								<img alt="profile photo" src="{{asset($user_info->user_profile_image)}}" class="thumbnail profile_img_size" />
							</div>
							<div class="col-md-8 info">
								<h1><i class="fa fa-lock"></i> {{isset($user_info->name) ? $user_info->name : ''}}</h1>

								<form action="{{url('/user/change/password')}}" method="post" enctype="multipart/form-data">
									<input type="hidden" name="_token" value="{{csrf_token()}}" >
									<div class="row">
										<div class="col-md-6" style="padding-right:0">
											<span><i>New Password</i></span>
											<input type="password" name="new_password" placeholder="New Password" class="form-control" value="">
										</div>
										<div class="col-md-6">
											<span><i>Confirm Password</i></span>
											<input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control" value="">
										</div>
									</div>
									<div class="input-group" style="margin-top:7px">
										<input type="password" name="current_password" placeholder="Current Password" class="form-control" value="">
										<span class="input-group-btn">
											<button class="btn btn-blue" type="submit">
												<i class="fa fa-chevron-right"></i>
											</button>
										</span>
									</div>

								</form>
							</div>
						</div>

					</div>
					<br>
				</div>


			</div>
		</div>
	</div>
	<!-- end: PAGE CONTENT-->

	@stop