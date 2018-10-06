<ol class="dd-list" >
	@if(isset($journal_data_child) && count($journal_data_child) > 0)
		@foreach($journal_data_child as $key => $journal)
			<li class="dd-item">
			<div class="dd-handle ledger_head" data-id="{{$journal->ledger_id}}" data-group="{{$journal->ledger_id}}" data-depth="{{$journal->depth}}" data-child="{{$journal->ledger_group_have_child}}" >
			@if($journal->ledger_group_have_child>0)
				<span class="treebtn expand_{{$journal->ledger_id}}" data-action="expand" data-group="{{$journal->ledger_id}}"  data-depth="{{$journal->depth}}" style="display: inline-block;" data-toggle="tooltip" data-placement="right" title="Click to Expand"><i class="fa fa-plus"></i></span>
				<span class="treebtn collapse_{{$journal->ledger_id}}" data-action="collapse" data-group="{{$journal->ledger_id}}"  data-depth="{{$journal->depth}}"  style="display: none;" data-toggle="tooltip" data-placement="right" title="Click to Collapse"><i  class="fa fa-minus"></i></span>	
				
			@endif
			<a href="{{url('/journal/ledger-'.$journal->ledger_id.'/depth-'.$journal->depth)}}" data-toggle="tooltip" data-placement="right" title="Doubble Click for Add">
				{{$journal->ledger_name}}
			</a>
			</div>
			@if($journal->ledger_group_have_child>0)
			 <div class="item_{{$journal->ledger_id}}"></div>
			@endif
		</li>
	@endforeach
	@endif
</ol>