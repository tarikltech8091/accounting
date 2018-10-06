
<?php 
 $journal_data= \App\Journal::GetJournalData(1);
 $journal_posting_field = \App\Journal::GetJournalEntryList();
 $journal_level =1;


?>
<div class="panel panel-default">
	<div class="panel-heading">
		<i class="clip-list-5"></i>
		List of Groups
		<div class="panel-tools">
			<a class="btn btn-xs btn-link panel-collapse collapses" data-toggle="tooltip" data-placement="top" title="Show / Hide" href="#">
			</a>
			
			<a class="btn btn-xs btn-link panel-close red-tooltip" data-toggle="tooltip" data-placement="top" title="Close" href="#">
				<i class="fa fa-times"></i>
			</a>
		</div>
	</div>
	<div class="panel-body">
		<div class="loading"></div>
		
		<div class="dd ledger_group_list" id="nestable2444444444444">
			
			<ol class="dd-list">

				@if(isset($journal_data) && count($journal_data) > 0)
					@foreach($journal_data as $key => $journal)
						<li class="dd-item">
							<div class="dd-handle ledger_head" data-id="{{$journal->ledger_id}}" data-group="{{$journal->ledger_id}}" data-depth="{{$journal->depth}}" data-child="{{$journal->ledger_group_have_child}}" >
							@if($journal->ledger_group_have_child>0)
								<span class="treebtn expand_{{$journal->ledger_id}}" data-action="expand" data-group="{{$journal->ledger_id}}"  data-depth="{{$journal->depth}}" style="display: inline-block;" data-toggle="tooltip" data-placement="right" title="Click to Expand"><i class="fa fa-plus"></i></span>
								<span class="treebtn collapse_{{$journal->ledger_id}}" data-action="collapse" data-group="{{$journal->ledger_id}}"  data-depth="{{$journal->depth}}"  style="display: none;" data-toggle="tooltip" data-placement="right" title="Click to Collapse"><i  class="fa fa-minus"></i></span>	
								
							@endif
							<!-- <a data-toggle="tooltip" data-placement="right" title="Doubble Click for Add"> -->
								{{$journal->ledger_name}}
							<!-- </a> -->
							</div>
							@if($journal->ledger_group_have_child>0)
							 <div class="item_{{$journal->ledger_id}}"></div>
							@endif
						</li>
					@endforeach
				@endif
			
			</ol>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<i class="clip-users-2"></i>
		Posting Type
		<div class="panel-tools">
			<a class="btn btn-xs btn-link panel-collapse collapses" data-toggle="tooltip" data-placement="top" title="Show / Hide" href="#">
			</a>
			
			<a class="btn btn-xs btn-link panel-close red-tooltip" data-toggle="tooltip" data-placement="top" title="Close" href="#">
				<i class="fa fa-times"></i>
			</a>
		</div>
	</div>
	<div class="panel-body posting_sidebar">
		<ul id="" class="nav">
			<li class="{{(isset($posting_type)&& ($posting_type=='journal')) ? 'active':''}}">
				<a href="{{url('/journal/posting/type-journal')}}" >
					<i class="pink fa fa-file-code-o"></i> Journal
				</a>
			</li>
			<li class="{{(isset($posting_type)&& ($posting_type=='receipt')) ? 'active':''}}">
				<a href="{{url('/journal/posting/type-receipt')}}">
					<i class="blue fa fa-ticket"></i> Receipt
				</a>
			</li>
			<li class="{{(isset($posting_type)&& ($posting_type=='payment')) ? 'active':''}}">
				<a href="{{url('/journal/posting/type-payment')}}" >
					<i class="fa fa-credit-card"></i> Payment
				</a>
			</li>
			<li class="{{(isset($posting_type)&& ($posting_type=='sales')) ? 'active':''}}">
				<a href="{{url('/journal/posting/type-sales')}}" >
					<i class="fa fa-money"></i> Sales
				</a>
			</li>
			<li class="{{(isset($posting_type)&& ($posting_type=='purchase')) ? 'active':''}}">
				<a href="{{url('/journal/posting/type-purchase')}}" >
					<i class="fa fa-dollar"></i> Purchase
				</a>
			</li>
			<li class="{{(isset($posting_type)&& ($posting_type=='sales-return')) ? 'active':''}}">
				<a href="{{url('/journal/posting/type-sales_return')}}" >
					<i class="fa fa-strikethrough"></i> Sales Return
				</a>
			</li>
			<li class="{{(isset($posting_type)&& ($posting_type=='purchase-return')) ? 'active':''}}">
				<a href="{{url('/journal/posting/type-purchase_return')}}" >
					<i class="fa fa-caret-square-o-left"></i> Purchase Return
				</a>
			</li>
			
		</ul>
	</div>
</div>
</div>