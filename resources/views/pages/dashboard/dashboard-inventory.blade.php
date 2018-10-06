@extends('layout.master')
@section('content')
<div class="row">

	<div class="col-md-12">
		<div class="col-md-3">
			<button onclick="location.href='{{url('/journal/posting/type-general_journal')}}';" class="btn btn-icon btn-block">
				<i class="fa fa-upload"></i>
				Posting
			</button>
		</div>
		<div class="col-md-3">
			<button class="btn btn-icon btn-block " onclick="location.href='{{url('/general/transaction-list/by-user')}}';" ><i class="fa fa-tasks"></i>
				General Transaction
			</button>
		</div>
		<div class="col-md-3">
			<button  onclick="location.href='{{url('/journal/transaction/by-user')}}';" class="btn btn-icon btn-block"><i class="clip-grid-6"></i>
					Journal Transaction
				</button>
		</div>
		<div class="col-md-3">
			<button class="btn btn-icon btn-block" onclick="location.href='{{url('/finish-goods/list')}}';">
				<i class="clip-stack-2"></i>
				Finish-goods Entry
			</button>
			
		</div>
		<div class="col-md-3">
			<button class="btn btn-icon btn-block" onclick="location.href='{{url('/inventory/stocks/trasansaction/list')}}';">
				<i class="clip-transfer"></i>
				Stocks Transaction
			</button>
		</div>
	
		<div class="col-md-3">
			<button class="btn btn-icon btn-block" onclick="location.href='{{url('/inventory/purchase/invoice')}}';">
				<i class="clip-cube-2"></i>
				Inventory Invoice
			</button>
		</div>
		<div class="col-md-3">
			<button class="btn btn-icon btn-block" onclick="location.href='{{url('/supplier/payment')}}';">
				<i class="fa fa-money"></i>
				Supplier Payment
			</button>
		</div>
	</div>

</div>
@stop