@extends('layout.master')
@section('content')
<!-- start: PAGE CONTENT -->
<div class="row">
	<!-- start: 404 -->
	<div class="col-sm-12 page-error">
		<div class="error-number teal">
			404
		</div>
		<div class="error-details col-sm-6 col-sm-offset-3">
			<h3>Oops! You are stuck at 404</h3>
			<p>
				Unfortunately the page you were looking for could not be found.
				<br>
				It may be temporarily unavailable, moved or no longer exist.
				<br>
				Check the URL you entered for any mistakes and try again.
				<br>
				<a href="{{(\Auth::check()) ? url('/dashboard/'.\Auth::user()->user_role.'/'.\Auth::user()->name_slug):'#'}}" class="btn btn-teal btn-return">
					Return home
				</a>
				
			</p>
		</div>
	</div>
	<!-- end: 404 -->
</div>
<!-- end: PAGE CONTENT-->
@stop