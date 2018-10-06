@extends('layout.master')
@section('content')
<!-- start: LOGIN BOX -->
			<div class="box-login" style="display:block;">
				<h3>Sign in to your account</h3>
				<p>
					Please enter your name and password to log in.
				</p>
				<form class="form-login" action="{{url('/form')}}" method="POST">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
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

					<fieldset>
						<div class="form-group">
							<span class="input-icon">
								<input type="text" class="form-control" name="email" placeholder="email">
								<i class="fa fa-envelope"></i> </span>
							<!-- To mark the incorrectly filled input, you must add the class "error" to the input -->
							<!-- example: <input type="text" class="login error" name="login" value="Username" /> -->
						</div>
						<div class="form-group form-actions">
							<span class="input-icon">
								<input type="password" class="form-control password" name="password" placeholder="Password">
								<i class="fa fa-lock"></i>
								
						</div>
						<div class="form-actions">
							<label for="remember" class="checkbox-inline">
								<input type="checkbox" class="grey remember" id="remember" name="remember">
								Keep me signed in
							</label>
							<a  class="btn btn-bricky pull-right loginbtn">
								Login <i class="fa fa-arrow-circle-right"></i>
							</a>
						</div>
						
					</fieldset>
				</form>
			</div>
			<!-- end: LOGIN BOX -->
@stop