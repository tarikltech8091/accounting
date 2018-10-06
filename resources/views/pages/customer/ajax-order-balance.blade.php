@if(!empty($customer_order_info))
	<label>Order Balance</label>	
	<input type="text" class="form-control" name="customer_credit_balance" value="{{$customer_order_info->order_balance_amount}}" disabled="">
@endif
