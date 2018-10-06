<!DOCTYPE HTML>
<html>
<head>
	<title>{{ isset($page_title) ? $page_title: 'Sheet'}}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


	 <!-- Bootstrap Core CSS -->
	<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
	<!-- Custom CSS -->
	<link href="{{asset('assets/css/excel.css')}}" rel='stylesheet' type='text/css' />
	<!-- jQuery -->
	<!-- lined-icons -->
	<link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" />


<!-- Placed js at the end of the document so the pages load faster -->
</head> 
<body>


	<div class="container">
		@yield('content')
	</div>
		

<!-- Bootstrap Core JavaScript -->
<script src="{{asset('assets/plugins/jQuery-lib/2.0.3/jquery.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
</body>
</html>
