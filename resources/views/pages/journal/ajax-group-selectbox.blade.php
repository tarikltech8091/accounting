
<div class="form-group add_node_parent_select" style="display:none;">
	<label for="form-field-select-1">
		Ledger Head
	</label>
	<select id="add_node_parent_id" class="form-control">
		<option value="">&nbsp;</option>
		@if(isset($journal_data_node) && count($journal_data_node) > 0)
			@foreach($journal_data_node as $key => $journal)
				<option value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
			@endforeach
		@endif
	</select>
</div>


<div class="form-group add_sub_node_parent_select" style="display:none;">
	<label for="form-field-select-1">
		Ledger Head
	</label>

		@if(isset($journal_data_child) && count($journal_data_child) > 0)
			<input type="text" name="parent_node" value="{{$journal_data_child->ledger_name}}" disabled="">
			<input type="hidden" id="add_sub_node_parent_id" name="add_sub_node_parent_id" value="{{$journal_data_child->ledger_id}}" >
			
		@endif
	</select>
</div>
