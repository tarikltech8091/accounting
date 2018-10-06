<tr class="customert_payment_entry_row_{{$i}}">
	<td>
		{{$customer_order_info->order_id}}
		<input type="hidden" class="form-control" name="payment_order_id_{{$i}}" value="{{$customer_order_info->order_id}}" />
	</td>
	<td>{{$customer_order_info->order_date}}</td>
	<td>{{$customer_order_info->order_delivery_net_amount}}</td>
	<td>{{$customer_order_info->order_delivery_balance_amount}}</td>
	<td>
		<input type="text" class="form-control customer_paid customer_paid_amount_row_{{$i}}" name="customer_paid_amount_{{$i}}" value="{{$customer_order_info->order_delivery_balance_amount}}" />
	</td>
	<td>
		<a data-rowid="{{$i}}" class="btn btn-xs btn-bricky customert_payment_entry_remove_btn" data-toggle1="tooltip" title="Delete line" data-original-title="Delete line"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
	</td>
</tr>


