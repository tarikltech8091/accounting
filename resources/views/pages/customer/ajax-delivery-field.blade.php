
@if(isset($ltech_sales_order_details) && count($ltech_sales_order_details))
	@foreach($ltech_sales_order_details as $key => $list)
		<tr>
			<td>{{$i}}</td>
			<td>
				<input data-id="{{$i}}" type="text" class="form-control delivery_quantity_name delivery_quantity_name_row_{{$i}}" name="delivery_quantity_name_{{$i}}" value="{{$list->order_item_name}}"  required />
			</td>

			<td><input data-id="{{$i}}" type="text" class="form-control delivery_quantity delivery_quantity_row_{{$i}}" name="delivery_quantity_{{$i}}" value="{{$list->order_item_quantity}}" required /> </td>

			<td><input data-id="{{$i}}" type="text" class="form-control delivery_quantity_rate delivery_quantity_rate_row_{{$i}}" name="delivery_quantity_rate_{{$i}}" value="{{$list->order_item_quantity_rate}}" required /> </td>

			<td><input data-id="{{$i}}" type="text" class="form-control delivery_amount delivery_amount_row_{{$i}}" name="delivery_amount_{{$i}}" value="{{$list->order_item_cost}}"  required /> </td>
			<input type="hidden" name="delivery_order_id_{{$i}}" value="{{$list->order_id}}">
			<input type="hidden" name="delivery_order_item_id_{{$i}}" value="{{$list->order_details_id}}">
		</tr>
		@php($i++)
	@endforeach
@endif