@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="clip-users-2"></i>
				Users
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" data-toggle="tooltip" data-placement="top" title="Show / Hide" href="#">
					</a>
					<a class="btn btn-xs btn-link panel-config" data-toggle="tooltip" data-placement="top" title="Add Account" href="#">
						<i class="clip-folder-plus"></i>
					</a>
					<a class="btn btn-xs btn-link panel-close red-tooltip" data-toggle="tooltip" data-placement="top" title="Close" href="#">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>
			<div class="panel-body panel-scroll" style="height:300px">
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="clip-users-2"></i>
				Users
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" data-toggle="tooltip" data-placement="top" title="Show / Hide" href="#">
					</a>
					<a class="btn btn-xs btn-link panel-config" data-toggle="tooltip" data-placement="top" title="Add Account" href="#">
						<i class="clip-folder-plus"></i>
					</a>
					<a class="btn btn-xs btn-link panel-close red-tooltip" data-toggle="tooltip" data-placement="top" title="Close" href="#">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive cost_list posting_list">
					<table class="table table-hover table-bordered table-striped nopadding">
						<thead>
							<tr>
								<th>SL</th>
								<th>Transaction Date</th>
								<th>Transactions Naration</th>
								<th>Transaction Amount</th>
								<th>Cost Center Id</th>
								<th>Posting Type</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>2016-12-12</td>
								<td>Assd</td>
								<td>1000</td>
								<td>Jacquard Elastic</td>
								<td>Journal</td>
								<td>
									<a href="#" class="btn btn-xs btn-green tooltips" data-toggle1="tooltip" title="" data-original-title="General Transaction Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
									<a href="#" class="btn btn-xs btn-bricky tooltips" data-toggle1="tooltip" title="" data-original-title="General Transaction Delete"><i class="fa  fa-trash-o"></i></a>
									<a href="#" class="btn btn-xs btn-green tooltips" data-toggle1="tooltip" title="" data-original-title="General Transaction PDF"><i class="fa fa-print" aria-hidden="true"></i></a>
									<a href="#" class="btn btn-xs btn-bricky tooltips" data-toggle1="tooltip" title="" data-original-title="General Transaction Share"><i class="fa fa-share"></i></a>
								</td>	
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@stop