<div class="credit_group"><!--Start: Credit group-->
	<div class="form-group col-md-6">
		<label for="form-field-6" class="">
			Credit
		</label>
		<div class="">
			<select  data-post="journal" class="credit_ledger form-control" name="credit_ledger[]" required>
				<option value="">Select</option>
				@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
					@foreach($journal_posting_field as $key => $journal)
						<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
					@endforeach
				@endif
											
			</select>
		</div>
		
	</div>
	<div class="form-group col-md-5">
		<label for="Debit_naration" class="">
			Amount (Tk.)
		</label>
		<div class="">
			<span class="input-icon">
				<input placeholder=" 0.0" id="form-field-16" name="credit_transaction_amount[]" class="form-control" type="text" required>
				<i class="fa fa-money"></i> </span>
		</div>
	</div>

	<div class="form-group col-md-11">
		<label for="Debit_naration">
			Naration
		</label>
		<div class="">
			<span class="input-icon">
				<input placeholder="Naration" name="credit_naration[]" id="form-field-16" class="form-control" type="text">
				<i class="fa fa-hand-o-right"></i> </span>
		</div>
	</div>
	<div class="col-md-1 credit_remove_btn">
		<span class="btn btn-bricky" data-toggle="tooltip" data-placement="top" title="remove credit"><i class="fa fa-times fa fa-white"></i></span>
	</div>
	<div style="clear:both"></div>
</div><!--End: Deebit group-->