@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-md-12 dashboard_btn">
		<div class="col-md-3"  onclick="location.href='{{url('/journal/posting/type-general_journal')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-pencil circle-icon circle-bricky"></i>
					<h2>Manage Posting</h2>
				</div>
			</div>
		</div>
		<div class="col-md-3"  onclick="location.href='{{url('/inventory/stocks/trasansaction/list')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-user-4 circle-icon circle-green"></i>
					<h2>Manage Inventroy</h2>
				</div>
				
			</div>
		</div>
		<div class="col-md-3" onclick="location.href='{{url('/journal/transaction/by-user')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-clip circle-icon circle-teal"></i>
					<h2>Journal</h2>
				</div>
			</div>
		</div>
		<div class="col-md-3"  onclick="location.href='{{url('/general/transaction-list/by-user')}}';" >
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-tasks  circle-icon circle-green"></i>
					<h2>Transaction</h2>
				</div>
			</div>
		</div>
		<div class="col-md-3"  onclick="location.href='{{url('/customer/order')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-bell-o circle-icon circle-green"></i>
					<h2>Customer Order</h2>
				</div>
				
			</div>
		</div>
		<div class="col-md-3"  onclick="location.href='{{url('/customer/order/delivery')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-tags circle-icon circle-teal"></i>
					<h2>Order Delivery</h2>
				</div>
			</div>
		</div>
		<div class="col-md-3"  onclick="location.href='{{url('/customer/all/order-list')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-database circle-icon circle-bricky"></i>
					<h2>All Order</h2>
				</div>
			</div>
		</div>
		<div class="col-md-3"  onclick="location.href='{{url('/customer/payment')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-money circle-icon circle-green"></i>
					<h2>Sales Receipt</h2>
				</div>
			</div>
		</div>
	</div>
</div>
@stop