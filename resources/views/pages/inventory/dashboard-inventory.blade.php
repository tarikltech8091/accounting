@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-md-12">

		<div class="col-sm-3">
			<button class="btn btn-icon btn-block">
				<i class="fa fa-group"></i>
				Today Stock Transaction <span class="badge badge-primary"> {{isset($today_count) ? $today_count:0}} </span>
			</button>
		</div>

		<div class="col-sm-3">
			<button class="btn btn-icon btn-block">
				<i class="fa fa-group"></i>
				Weekly Stock Transaction <span class="badge badge-primary"> {{isset($weekly_count)? $weekly_count:0}} </span>
			</button>
		</div>

		<div class="col-sm-3">
			<button class="btn btn-icon btn-block">
				<i class="fa fa-group"></i>
				Monthly Stock Transaction <span class="badge badge-primary">{{isset($monthly_count)? $monthly_count:0}}  </span>
			</button>
		</div>

		<div class="col-sm-3">
			<button class="btn btn-icon btn-block">
				<i class="fa fa-group"></i>
				Yearly Stock Transaction <span class="badge badge-primary">{{isset($yearly_count)? $yearly_count:0}}</span>
			</button>
		</div>

	</div>

	<div class="col-md-12">

		<div class="col-md-12" align="center">
			<div class="core-box">
				<div class="heading">
					<i class="clip-database circle-icon circle-bricky"></i>
					<h2>Manage Inventory</h2>
				</div>
				<div class="content">
					Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
				</div>
				<a class="view-more" href="{{url('/inventory/dashboard')}}">
					<button class="btn btn-success">More <i class="clip-arrow-right-2"></i></button>
				</a>
			</div>
		</div>

	</div>
</div>
@stop