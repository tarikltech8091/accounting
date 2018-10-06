
<!-- start: TOP NAVIGATION CONTAINER -->
<div class="container">
	<div class="navbar-header">
		<!-- start: RESPONSIVE MENU TOGGLER -->
		<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
			<span class="clip-list-2"></span>
		</button>
		<!-- end: RESPONSIVE MENU TOGGLER -->
		<!-- start: LOGO -->
		<a class="navbar-brand" href="{{url('/dashboard/'.\Auth::user()->user_role.'/'.\Auth::user()->name_slug)}}">
			<!-- CLIP<i class="clip-clip"></i>ONE -->
			<img src="{{asset('assets/images/dfpro.png')}}" alt="DF TEX">
		</a>
		<!-- end: LOGO -->
	</div>

	<div class="navbar-tools">
	
		<!-- start: TOP NAVIGATION MENU -->
		<ul class="nav navbar-right">

			<!-- start: USER DROPDOWN -->
			<li class="dropdown current-user">
				<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
					<!-- <img src="assets/images/avatar-1-small.jpg" class="circle-img" alt=""> -->

					<span class="username">{{(\Auth::check()) ? \Auth::user()->name:''}}</span>
					<i class="clip-chevron-down"></i>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="{{url('/user/profile')}}">
							<i class="clip-user-2"></i>
							&nbsp;My Profile
						</a>
					</li>
					
					@if(\Auth::check())
					<li>

						<a href="{{url('/logout/'.\Auth::user()->name_slug)}}">
							<i class="clip-exit"></i>
							&nbsp;Log Out
						</a>
					</li>
					@endif
				</ul>
			</li>
			<!-- end: USER DROPDOWN -->
			<!-- start: PAGE SIDEBAR TOGGLE -->
			<li>
				<a class="sb-toggle" data-toggle="tooltip" data-placement="top" title="Right-Sidebar" href="#"><i class="fa fa-outdent"></i></a>
			</li>
			<!-- end: PAGE SIDEBAR TOGGLE -->
		</ul>
		<!-- end: TOP NAVIGATION MENU -->
	</div>
</div>
<!-- end: TOP NAVIGATION CONTAINER -->
