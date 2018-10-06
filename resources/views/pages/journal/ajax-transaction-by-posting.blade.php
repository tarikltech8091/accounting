<table class="table table-hover table-bordered table-striped nopadding" id="sample-table-1">
	<thead>
		<tr>
			<th>SL</th>
			<th>Transaction Date</th>
			<th>Transactions Naration</th>
			<th>Transaction Amount</th>
			<th>Cost Center</th>
			<th>Posting Type</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@if(!empty($transaction_by_posting) && count($transaction_by_posting) > 0)
		@foreach($transaction_by_posting as $key => $list)
		<tr >
			<td>{{$key+1}}</td>
			<td>{{$list->transactions_date}}</td>
			<td>{{$list->transactions_naration}}</td>
			<td>{{$list->transaction_amount}}</td>
			<td>{{$list->cost_center_name}}</td>
			<td>{{$list->posting_type}}</td>
			<td>
				<a href="#" class="btn btn-xs btn-green tooltips" data-toggle1="tooltip" title="General Transaction Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
				<a href="#" class="btn btn-xs btn-bricky tooltips" data-toggle1="tooltip" title="General Transaction Delete"><i class="fa  fa-trash-o"></i></a>
				<a href="#" class="btn btn-xs btn-green tooltips" data-toggle1="tooltip" title="General Transaction PDF"><i class="fa fa-print" aria-hidden="true"></i></a>
				<a href="#" class="btn btn-xs btn-bricky tooltips" data-toggle1="tooltip" title="General Transaction Share"><i class="fa fa-share"></i></a>
			</td>	
		</tr>
		@endforeach
		@else
		<tr class="text-center">
			<td colspan="7">No Data available</td>
		</tr>
		@endif
	</tbody>
</table>
