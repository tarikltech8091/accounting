<option>Choose Account</option>
@if(!empty($payment_account))
	@foreach($payment_account as $key => $account)
		<option value="{{$account->ledger_id.'.'.$account->depth.'.'.$account->ledger_name}}">{{$account->ledger_name}}</option>
	@endforeach	
@endif
