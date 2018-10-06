@php($stock_transaction_info=\App\Inventory::StockTransactionInfo($stocks_transactions_id))

@if(!empty($stock_transaction_info))
<tr>
	<td>{{$i}}</td>
	<td>
		{{(isset($stock_transaction_info->item_name) && !empty($stock_transaction_info->item_name)) ? $stock_transaction_info->item_name:''}}
		<input type="hidden" name="stocks_trasnsaction_id_{{$i}}" class="stocks_trasnsaction_id_{{$i}}" value="{{$stocks_transactions_id}}">
		<input type="hidden" name="cost_center_id_{{$i}}" class="cost_center_id_{{$i}}" value="{{(isset($stock_transaction_info->cost_center_id) && !empty($stock_transaction_info->cost_center_id)) ? $stock_transaction_info->cost_center_id:''}}">
	</td>
	<td>
		{{(isset($stock_transaction_info->stocks_quantity_cost) && !empty($stock_transaction_info->stocks_quantity_cost)) ? $stock_transaction_info->stocks_quantity_cost:0.0}}
	</td>
	<td>
		{{(isset($stock_transaction_info->stocks_supplier_balance_amount) && !empty($stock_transaction_info->stocks_supplier_balance_amount)) ? $stock_transaction_info->stocks_supplier_balance_amount:0}}
	</td>
	<td>
		<input type="text" name="stocks_payment_amount_{{$i}}"  class="form-control stocks_payment_amount_row stocks_payment_amount_{{$i}}" value="{{(isset($stock_transaction_info->stocks_supplier_balance_amount) && !empty($stock_transaction_info->stocks_supplier_balance_amount)) ? $stock_transaction_info->stocks_supplier_balance_amount:0}}" required>
	</td>

</tr>
@endif