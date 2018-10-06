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
				<li class="{{($tab=='create_user') ? 'active' : ''}}">
					<a data-toggle="tab" href="#create_user">
						Create User
					</a>
				</li>
				<li class="{{($tab=='blocked_user') ? 'active' : ''}}">
					<a data-toggle="tab" href="#blocked_user">
						Blocked Users
					</a>
				</li>
				<li class="{{$tab=='admins' ? 'active':''}}">
					<a data-toggle="tab" href="#admins">
						Admins
					</a>
				</li>

				<li class="{{$tab=='inventory' ? 'active':''}}">
					<a data-toggle="tab" href="#inventory">
						Inventory
					</a>
				</li>

				<li class="{{$tab=='account' ? 'active':''}}">
					<a data-toggle="tab" href="#account">
						Account
					</a>
				</li>

			</ul>


			<div class="tab-content">
				<div id="create_user" class="tab-pane {{$tab=='create_user' ? 'active':''}}">
					<div class="row">
						<div class="col-md-12">
							<form action="{{url('/dashboard/admin/user/registration')}}" method="post" enctype="multipart/form-data" role="form" id="form">

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
											<input type="text" placeholder="Name" class="form-control" id="name" name="name" value="{{old('name')}}">
										</div>

										<div class="form-group">
											<label class="control-label">
												Mobile
											</label>
											<input type="text" placeholder="User Mobile" class="form-control" id="phone" name="user_mobile" value="{{old('user_mobile')}}">
										</div>

										<div class="form-group">
											<label class="control-label">
												Email Address
											</label>
											<input type="email" placeholder="email@example.com" class="form-control" id="email" name="email" value="{{old('email')}}">
										</div>

										<div class="form-group">
											<label class="control-label">
												Password
											</label>
											<input type="password" name="password" placeholder="********" class="form-control" value="" />
										</div>

										<div class="form-group">
											<label class="control-label">
												Confirm Password
											</label>
											<input type="password" class="form-control" name="confirm_password" placeholder="********" value="" />
										</div>

									</div>



									<div class="col-md-6">

										<div class="form-group">
											<label class="control-label">
												Position
											</label>
											<select class="form-control" name="user_type">
												<option value="admin">Admin</option>
												<option value="account">Accounts</option>
												<option value="inventory">Inventory</option>
											</select>
										</div>
										<div class="form-group">
											<label class="control-label">
												User Role
											</label>
											<select class="form-control" name="user_role">
												<option value="admin">Administrator</option>
												<option value="account">Accounts</option>
												<option value="inventory">Inventory</option>
											</select>
										</div>

										<div class="fileupload fileupload-new" data-provides="fileupload">
											<div class="fileupload-new thumbnail" style="width: 150px; height: 150px;"><img src="{{asset('assets/images/profile.png')}}" alt="">
											</div>
											<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 150px; max-height: 150px; line-height: 20px;"></div>
											<div class="user-edit-image-buttons">
												<span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture"></i> Change</span>
													<input type="file" name="user_profile_image">
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
											By clicking Register, you are agreeing to the Policy and Terms &amp; Conditions.
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


				<div id="blocked_user" class="tab-pane {{$tab=='blocked_user' ? 'active':''}}">
					<div class="row">
						<div class="col-md-12">
							<!-- start: DYNAMIC TABLE PANEL -->
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="sample-table-1">
									<thead>
										<tr>
											<th>SL</th>
											<th>Name</th>
											<th>Email</th>
											<th>Mobile</th>
											<th>Login Status</th>
											<th>User Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@if(!empty($user_info))
										@foreach($user_info as $key => $blocked_user_list)
										@if($blocked_user_list->user_status == '-1')
										<tr>
											<td>{{$key+1}}</td>
											<td>{{$blocked_user_list->name}}</td>
											<td>{{$blocked_user_list->email}}</td>
											<td>{{$blocked_user_list->user_mobile}}</td>
											<td>{{isset($blocked_user_list->login_status) && ($blocked_user_list->login_status=='1') ? 'Logged In' : 'Logged Out'}}</td>
											<td>{{isset($blocked_user_list->user_status) && ($blocked_user_list->user_status=='1') ? 'Active' : 'Inactive'}}</td>
											<td>
												@if($blocked_user_list->user_status==1)
												<button class="btn btn-primary btn-xs status col-md-12" data-id="{{$blocked_user_list->user_id}}" data-tab="blocked_user" data-action="-1">Block</button>
												@else
												<button class="btn btn-danger btn-xs status col-md-12" data-id="{{$blocked_user_list->user_id}}" data-tab="blocked_user" data-action="1">Unblock</button>
												@endif
											</td>
										</tr>
										@endif
										@endforeach
										@else
										<tr>
											<td colspan="9">
												<div class="alert alert-success" role="alert">
													<center><h4>No Data Available !</h4></center>
												</div> 
											</td>
										</tr>
										@endif

									</tbody>
								</table>
							</div>
							<!-- end: DYNAMIC TABLE PANEL -->

						</div>
					</div>
				</div>


				<div id="admins" class="tab-pane {{$tab=='admins' ? 'active':''}}">
					<div class="row">
						<div class="col-md-12">

							<!-- start: DYNAMIC TABLE PANEL -->
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="sample-table-1">
									<thead>
										<tr>
											<th>SL</th>
											<th>Name</th>
											<th>Photo</th>
											<th>Email</th>
											<th>Mobile</th>
											<th>Login Status</th>
											<th>Last Login</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@if(!empty($user_info))
										@foreach($user_info as $key => $admin_user_list)
										@if(($admin_user_list->user_type == 'admin'))
										<tr>
											<td>{{$key+1}}</td>
											<td><a href="{{url('/user/profile/view/id-'.$admin_user_list->user_id)}}">{{$admin_user_list->name}}</a></td>
											
											<td class="center" >
												<img src="{{!empty($admin_user_list->user_profile_image) ? asset($admin_user_list->user_profile_image) :  asset('assets/images/profile.png') }}" title="{{$admin_user_list->name}}" style="height:20px;">
											</td>
											<td>{{$admin_user_list->email}}</td>
											<td>{{$admin_user_list->user_mobile}}</td>
											<td>{{isset($admin_user_list->login_status) && ($admin_user_list->login_status=='1') ? 'Logged In' : 'Logged Out'}}</td>
											<td>{{$admin_user_list->last_login}}</td>
											<td>{{isset($admin_user_list->user_status) && ($admin_user_list->user_status=='1') ? 'Active' : 'Inactive'}}</td>

											<td>
												@if($admin_user_list->user_status==1)
												<button type="button" class="btn btn-danger status btn-xs" data-id="{{$admin_user_list->user_id}}" data-tab="blocked_user" data-action="-1" style="padding:0; width:70px;" data-toggle1="tooltip" title="Order Confirm">Block</button>

												@else
												<button type="button" class="btn btn-primary status btn-xs" data-id="{{$admin_user_list->user_id}}" data-tab="admins" data-action="1" style="padding:0; width:70px;">Unblock</button>
												@endif
												<button class="btn btn-xs btn-bricky tooltips" title="" data-toggle1="tooltip" onclick="UserDelete({{$admin_user_list->user_id}})" data-original-title="Delete User"><i class="fa  fa-trash-o"></i></button>
											</td>
										</tr>
										@endif
										@endforeach
										@else
										<tr>
											<td colspan="9">
												<div class="alert alert-success" role="alert">
													<center><h4>No Data Available !</h4></center>
												</div> 
											</td>
										</tr>
										@endif

									</tbody>
								</table>
							</div>
							<!-- end: DYNAMIC TABLE PANEL -->

						</div>
					</div>
				</div>

				<div id="inventory" class="tab-pane {{$tab=='inventory' ? 'active':''}}">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="sample-table-1">
									<thead>
										<tr>
											<th>SL</th>
											<th>Name</th>
											<th>Photo</th>
											<th>Role</th>
											<th>Email</th>
											<th>Mobile</th>
											<th>Login Status</th>
											<th>Last Login</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@if(!empty($user_info))
										@foreach($user_info as $key1 => $inventory_user_list)
										@if(($inventory_user_list->user_type == 'inventory'))
										<tr>
											<td>{{$key1+1}}</td>
											<td><a href="{{url('/user/profile/view/id-'.$inventory_user_list->user_id)}}">{{$inventory_user_list->name}}</a></td>
											<td class="center" >
												<img src="{{!empty($inventory_user_list->user_profile_image) ? asset($inventory_user_list->user_profile_image) :  asset('assets/images/profile.png') }}" title="{{$inventory_user_list->name}}" style="height:20px;">
											</td>
											<td>{{strtoupper($inventory_user_list->user_role)}}</td>
											<td>{{$inventory_user_list->email}}</td>
											<td>{{$inventory_user_list->user_mobile}}</td>
											<td>{{isset($inventory_user_list->login_status) && ($inventory_user_list->login_status=='1') ? 'Logged In' : 'Logged Out'}}</td>
											<td>{{$inventory_user_list->last_login}}</td>
											<td>{{isset($inventory_user_list->user_status) && ($inventory_user_list->user_status=='1') ? 'Active' : 'Inactive'}}</td>
											<td>
												@if($inventory_user_list->user_status == 1)
												<button type="button" class="btn btn-danger status" data-id="{{$inventory_user_list->user_id}}" data-action="-1" data-tab="blocked_user" style="padding:0; width:70px;" data-toggle1="tooltip" title="Order Confirm">Block</button>

												@else
												<button type="button"  class="btn btn-primary status" data-id="{{$inventory_user_list->user_id}}" data-action="1" data-tab="inventory" style="padding:0; width:70px;">Unblock</button>
												
												@endif
												<button class="btn btn-xs btn-bricky tooltips" title="" data-toggle1="tooltip" onclick="UserDelete({{$inventory_user_list->user_id}})" data-original-title="Delete User"><i class="fa  fa-trash-o"></i></button>
											</td>
										</tr>
										@endif
										@endforeach
										@else
										<tr>
											<td colspan="9">
												<div class="alert alert-success" role="alert">
													<center><h4>No Data Available !</h4></center>
												</div> 
											</td>
										</tr>
										@endif

									</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>

				<div id="account" class="tab-pane {{$tab=='account' ? 'active':''}}">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="sample-table-1">
									<thead>
										<tr>
											<th>SL</th>
											<th>Name</th>
											<th>Photo</th>
											<th>Role</th>
											<th>Email</th>
											<th>Mobile</th>
											<th>Login Status</th>
											<th>Last Login</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@if(!empty($user_info))
										@foreach($user_info as $key1 => $account_user_list)
										@if(($account_user_list->user_role == 'account'))
										<tr>
											<td>{{$key1+1}}</td>
											<td><a href="{{url('/user/profile/view/id-'.$account_user_list->user_id)}}">{{$account_user_list->name}}</a></td>
											<td class="center" >
												<img src="{{!empty($account_user_list->user_profile_image) ? asset($account_user_list->user_profile_image) :  asset('assets/images/profile.png') }}" title="{{$account_user_list->name}}" style="height:20px;">
											</td>
											<td>{{strtoupper($account_user_list->user_role)}}</td>
											<td>{{$account_user_list->email}}</td>
											<td>{{$account_user_list->user_mobile}}</td>
											<td>{{isset($account_user_list->login_status) && ($account_user_list->login_status=='1') ? 'Logged In' : 'Logged Out'}}</td>
											<td>{{$account_user_list->last_login}}</td>
											<td>{{isset($account_user_list->user_status) && ($account_user_list->user_status=='1') ? 'Active' : 'Inactive'}}</td>
											<td>
												@if($account_user_list->user_status==1)
												<button type="button" class="btn btn-danger status" data-id="{{$account_user_list->user_id}}" data-tab="blocked_user" data-action="-1" style="padding:0; width:70px;" data-toggle1="tooltip" title="Order Confirm">Block</button>

												@else
												<button type="button"  class="btn btn-primary status" data-id="{{$account_user_list->user_id}}" data-tab="account" data-action="1" style="padding:0; width:70px;">Unblock</button>
												
												@endif
												<button class="btn btn-xs btn-bricky tooltips" title="" data-toggle1="tooltip" onclick="UserDelete({{$account_user_list->user_id}})" data-original-title="Delete User"><i class="fa  fa-trash-o"></i></button>
											</td>
										</tr>
										@endif
										@endforeach
										@else
										<tr>
											<td colspan="9">
												<div class="alert alert-success" role="alert">
													<center><h4>No Data Available !</h4></center>
												</div> 
											</td>
										</tr>
										@endif

									</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>




			</div>


		</div>
	</div>

	@stop

	<script type="text/javascript">
		function UserDelete(user_id) {
     
        var r = confirm("Are you want to Delete?");
	        if (r == true) {
	           window.location ="{{url('/user/profile/delete/')}}/"+user_id;
	        } else {
	            return false;
	        }  
	    }
	</script>