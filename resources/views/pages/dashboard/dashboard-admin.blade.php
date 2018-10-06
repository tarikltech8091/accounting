@extends('layout.master')
@section('content')
<div class="row">

<!-- 	<div class="col-md-12">

		<div class="col-sm-3">
			<button class="btn btn-icon btn-block">
				<i class="fa fa-group"></i>
				Today Transaction <span class="badge badge-primary"> {{isset($today_count) ? $today_count:0}} </span>
			</button>
		</div>

		<div class="col-sm-3">
			<button class="btn btn-icon btn-block">
				<i class="fa fa-group"></i>
				Weekly Transaction <span class="badge badge-primary"> {{isset($weekly_count)? $weekly_count:0}} </span>
			</button>
		</div>

		<div class="col-sm-3">
			<button class="btn btn-icon btn-block">
				<i class="fa fa-group"></i>
				Monthly Transaction <span class="badge badge-primary">{{isset($monthly_count)? $monthly_count:0}}  </span>
			</button>
		</div>

		<div class="col-sm-3">
			<button class="btn btn-icon btn-block">
				<i class="fa fa-group"></i>
				Yearly Transaction <span class="badge badge-primary">{{isset($yearly_count)? $yearly_count:0}}</span>
			</button>
		</div>

	</div> -->

	<div class="col-md-12 dashboard_btn">
		<div id="dashboard">
          <div class="to"> Manage</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/dashboard/admin/user/management')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-user-4 circle-icon circle-green"></i>
					<h2>Manage Users</h2>
				</div>
				
			</div>
		</div>
		<div class="col-md-3" onclick="location.href='{{url('/dashboard/cost-center')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-clip circle-icon circle-red"></i>
					<h2>Cost Center</h2>
				</div>
			</div>
		</div>
		<div class="col-md-3"  onclick="location.href='{{url('/customer/order')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-bell-o circle-icon circle-bricky"></i>
					<h2>Customer Order</h2>
				</div>
				
			</div>
		</div>
		

		<div class="col-md-3"  onclick="location.href='{{url('/journal/posting/type-general_journal')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-pencil circle-icon circle-teal"></i>
					<h2>Manage Posting</h2>
				</div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/customer/all/order-list')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-bars circle-icon circle-red" aria-hidden="true"></i>
					<h2>All Order</h2>
				</div>
			</div>
		</div>

		<div class="col-md-3" onclick="location.href='{{url('customer/list')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-user-4 circle-icon circle-green"></i>
					<h2> Customer</h2>
				</div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('supplier/list')}}';" >
			<div class="core-box">
				<div class="heading">
					<i class="clip-user-4 circle-icon circle-teal"></i>
					<h2> Supplier</h2>
				</div>
			</div>
		</div>
		


		<div class="col-md-3"  onclick="location.href='{{url('/customer/order/delivery')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-tags circle-icon circle-bricky"></i>
					<h2>Order Delivery</h2>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div id="dashboard">
	          <div class="to"> Transaction </div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/general/transaction-list')}}';" >
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-tasks  circle-icon circle-green"></i>
					<h2>General Transaction</h2>
				</div>
			</div>
		</div>
		<div class="col-md-3" onclick="location.href='{{url('/journal/transaction')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-list-alt circle-icon circle-teal"></i>
					<h2>Journal Transaction</h2>
				</div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('all/journal/ledger')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-database circle-icon circle-bricky"></i>
					<h2>Ledger Transaction</h2>
				</div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/inventory/stocks/trasansaction/list')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-tasks  circle-icon circle-red"></i>
					<h2>Manage Inventroy</h2>
				</div>
				
			</div>
		</div>
		


		<div class="col-md-12">
			<div id="dashboard">
	          <div class="to"> Payment and Receipt</div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/sales/balance/report')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-briefcase circle-icon circle-teal"></i>
					<h2>Sales Report</h2>
				</div>
				
			</div>
		</div>
		<div class="col-md-3"  onclick="location.href='{{url('/account-receivable/balance/report')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-money circle-icon circle-green"></i>
					<h2>A/C Receivable </h2>
				</div>
			</div>
		</div>
		



		<div class="col-md-3"  onclick="location.href='{{url('/purchase/balance/report')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-database circle-icon circle-red"></i>
					<h2>Purchase Report</h2>
				</div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/account-payable/balance/report')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-credit-card circle-icon circle-bricky"></i>
					<h2>A/C Payable</h2>
				</div>
			</div>
		</div>

		
		

	</div>
</div>


@stop