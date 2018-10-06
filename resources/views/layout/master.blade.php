<!DOCTYPE html>
<!-- Template Name: Clip-One - Responsive Admin Template build with Twitter Bootstrap 3.x Version: 1.4 Author: ClipTheme -->
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
	<!-- start: HEAD -->

	<!-- Mirrored from www.cliptheme.com/preview/admin/clip-one/ by HTTrack Website Copier/3.x [XR&CO'2013], Sun, 12 Apr 2015 06:25:32 GMT -->
	<head>
		<title>{{isset($page_title) ? $page_title.' |' : ''}} DF Tex</title>
		<!-- start: META -->
		<meta charset="utf-8" />
		<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta content="" name="description" />
		<meta content="" name="author" />
		<!-- end: META -->
		<!-- start: MAIN CSS -->
		<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
		<link rel="stylesheet" href="{{asset('assets/plugins/font-awesome/css/font-awesome.min.css')}}">
		<link rel="stylesheet" href="{{asset('assets/fonts/style.css')}}">
		<link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
		<link rel="stylesheet" href="{{asset('assets/css/main-responsive.css')}}">
		<link rel="stylesheet" href="{{asset('assets/plugins/iCheck/skins/all.css')}}">
		<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css')}}">
		<link rel="stylesheet" href="{{asset('assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css')}}">
		<link rel="stylesheet" href="{{asset('assets/css/theme_dark.css')}}" type="text/css" id="skin_color">
		<link rel="stylesheet" href="{{asset('assets/css/print.css')}}" type="text/css" media="print"/>
		<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')}}">
		<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-social-buttons/social-buttons-3.css')}}">

		<!-- custom css -->
		<link rel="stylesheet" href="{{asset('assets/css/custom.css')}}" type="text/css"/>

		<!-- table view -->
		<link rel="stylesheet" href="{{asset('assets/plugins/DataTables/media/css/DT_bootstrap.css')}}" />

		<!-- switch nutton -->
		<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-switch/static/stylesheets/bootstrap-switch.css')}}">
		
		<link rel="stylesheet" href="{{asset('assets/plugins/dropzone/downloads/css/dropzone.css')}}">


		<!-- Datepiicker css -->
		<link rel="stylesheet" href="{{asset('assets/plugins/datepicker/css/datepicker.css')}}" type="text/css"/>

		<!-- Chart -->
		<link href="{{asset('assets/css/morris-0.4.3.min.css')}}" rel="stylesheet" type="text/css" />




		<!--[if IE 7]>
		<link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome-ie7.min.css">
		<![endif]-->
		<!-- end: MAIN CSS -->
		<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->

		<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
		<link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" />
		<!-- <link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}" /> -->

		<!-- start: Selectbox -->
		<link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.css')}}">
		<!-- end: Selectbox -->
	<!-- <link rel="stylesheet" href="{{asset('css/datetimepicker.css')}}" /> -->

	</head>
	<!-- end: HEAD -->
	<!-- start: BODY -->

	@if(isset($page_title) && ($page_title=='LogIn'))
		@yield('login-content')
	@else
	<body>
			<!-- start: HEADER -->
			<div class="navbar navbar-inverse navbar-fixed-top">
				<!-- start: HEADER-MENU -->
					@include('layout.header-menu')
				<!-- end: HEADER-MENU -->
			</div>
			<!-- end: HEADER -->
			<!-- start: MAIN CONTAINER -->
			<div class="main-container">
				<!-- start: Sidebar-MENU -->
					
					@include('layout.left-sidebar')
				<!-- start: Sidebar-MENU -->
			</div>
			<!-- end: MAIN CONTAINER -->
			<!-- start: PAGE -->
			<div class="main-content">
				<!-- start: PAGE Container -->
				<div class="container">
					<!-- start: PAGE HEADER -->
					<div class="row">
						<div class="col-sm-12">
							<!-- start: BREADCRUMB -->
							@include('layout.bradecrumb')
							<!-- end: BREADCRUMB -->
						</div>
					</div>
					<!-- end: PAGE HEADER -->
					<!-- start: PAGE CONTENT -->
						@yield('content')
					<!-- end: PAGE CONTENT-->
				</div>
				<!-- end: PAGE Container -->
			</div>
			<!-- end: PAGE -->
			<!-- start: FOOTER -->
			<div class="footer-fixed">
				<div class="footer clearfix">
					<div class="footer-inner">
						{{date('Y')}} &copy; Developed by Live Technologies Ltd.
					</div>
					<div class="footer-items">
						<span class="go-top"><i class="clip-chevron-up"></i></span>
					</div>
				</div>
			</div>
			<!-- start: RIGHT SIDEBAR -->
			<div id="page-sidebar">
				<a class="sidebar-toggler sb-toggle" href="#"><i class="fa fa-indent"></i></a>
				@include('layout.right-sidebar')
			</div>
			<!-- end: FOOTER -->
	@endif
			<input type="hidden" class="site_url" value="{{url('/')}}" >
			<input type="hidden" class="current_page_url" value="{{\Request::fullUrl()}}">

			<script src="{{asset('assets/plugins/jQuery-lib/2.0.3/jquery.min.js')}}"></script>
			
			<!--<![endif]-->
			<script src="{{asset('assets/plugins//nnnnn/jquery-ui/jquery-ui-1.10.2.custom.min.js')}}"></script>
			<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
			<script src="{{asset('assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')}}"></script>
			<script src="{{asset('assets/plugins/blockUI/jquery.blockUI.js')}}"></script>
			<script src="{{asset('assets/plugins/iCheck/jquery.icheck.min.js')}}"></script>
			<script src="{{asset('assets/plugins/perfect-scrollbar/src/jquery.mousewheel.js')}}"></script>
			<script src="{{asset('assets/plugins/perfect-scrollbar/src/perfect-scrollbar.js')}}"></script>
			<script src="{{asset('assets/plugins/less/less-1.5.0.min.js')}}"></script>
			<script src="{{asset('assets/plugins/jquery-cookie/jquery.cookie.js')}}"></script>
			<script src="{{asset('assets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js')}}"></script>
			<script src="{{asset('assets/js/main.js')}}"></script>
			<script src="{{asset('assets/js/custom.js')}}"></script>
			<!-- end: MAIN JAVASCRIPTS -->

			<!-- <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

  <script src="http://cdn.oesmith.co.uk/morris-0.4.1.min.js"></script> -->



			<!-- Image Upload Js-->
			<script src="{{asset('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js')}}"></script>
			<script src="{{asset('assets/js/pages-user-profile.js')}}"></script>

			<!-- Datetimepicekr Js-->
			<script src="{{asset('assets/plugins/bootstrap-daterangepicker/moment.min.js')}}"></script>
			<script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>

			

			<script>
				jQuery(document).ready(function() {
					Main.init();
				});
			</script>
			<script>
				jQuery(document).ready(function() {
		
					/*FormElements.init();
					
					$('.date-picker-2').datepicker({
							autoclose: true
						});*/
				});

				jQuery(document).ready(function(){
			        jQuery('[data-toggle="tooltip"]').tooltip();   
			    });

			    jQuery(document).ready(function(){
			        jQuery('[data-toggle1="tooltip"]').tooltip();   
			    });

			    jQuery(document).ready(function() {

				    jQuery('.date-picker').datepicker({
			            autoclose: true
			        });

				});
			</script>


			<!-- Chart Calling in DashboardPage Js-->
				<!-- Chart JavaScript -->
	 			<script src="{{asset('assets/js/raphael-min.js')}}"></script>
				<script src="{{asset('assets/js/morris-0.4.1.min.js')}}"></script>
				@if(isset($page_title) && ($page_title=='Dashboard'))
					<script type="text/javascript">
						jQuery(document).ready(function(){
							
							var request_url = '{{url('/dashboard/admin/today/all-report/summary')}}';
							jQuery.ajax({
								url: request_url, 
								dataType: 'JSON',
								type: 'GET',
								success: function(response) {
									Morris.Donut({
										element: 'today-chart',
										data: response,
										//formatter: function (x) { return x + "BDT"}
									});
								}
							});


							var request_url = '{{url('/dashboard/admin/line-graph/chart')}}';
							jQuery.ajax({
								url: request_url, 
								dataType: 'JSON',
								type: 'GET',
								success: function(response) {
									Morris.Donut({
										element: 'weekly-chart',
										data: response,
									});
								}
							});

							/*Morris.Line({
								  element: 'weekly-chart',
								  data: [
								    { y: '2006', a: 100, b: 90 },
								    { y: '2007', a: 75,  b: 65 },
								    { y: '2008', a: 50,  b: 40 },
								    { y: '2009', a: 75,  b: 65 },
								    { y: '2010', a: 50,  b: 40 },
								    { y: '2011', a: 75,  b: 65 },
								    { y: '2012', a: 100, b: 90 }
								  ],
								  xkey: 'y',
								  ykeys: ['a', 'b'],
								  labels: ['Series A', 'Series B']
								});*/
						});
					</script>
				@endif
			<!-- Datetimepicekr Js-->
			
			
	</body>
	<!-- end: BODY -->
</html>

	