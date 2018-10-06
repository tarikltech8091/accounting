<?php 
 $inventory_stocks_list= \DB::table('ltech_inventory_stocks')
  ->get();
?>

<tr class="sales_order_entry_group_{{$i}}">

    <td>{{$i}}</td>
	<td>
		<input data-rowid="{{$i}}" type="text" class="form-control order_quantity_name order_quantity_name_row_{{$i}}" name="order_quantity_name_{{$i}}" value="" placeholder="Name" required />
	</td>

	<td><input data-rowid="{{$i}}" type="text" class="form-control sales_order_quantity sales_order_quantity_row_{{$i}}" name="sales_order_quantity_{{$i}}" value="" placeholder="0" required /> </td>

	<td><input data-rowid="{{$i}}" type="text" class="form-control sales_order_quantity_type sales_order_quantity_type_row_{{$i}}" name="sales_order_quantity_type_{{$i}}" value="" placeholder="Kg/Pice" required /> </td>

	<td><input data-rowid="{{$i}}" type="text" class="form-control sales_order_quantity sales_order_rate_row_{{$i}}" name="sales_order_rate_{{$i}}" value="" placeholder="0" required /> </td>

	<td><input data-rowid="{{$i}}" type="text" class="form-control sales_order_amount order_quantity_cost_row_{{$i}}" name="sales_order_amount_{{$i}}" value="" placeholder="0.0" required /> 
	</td>
	<td><a data-rowid="{{$i}}" class="btn btn-xs btn-bricky sales_order_entry_remove_btn" data-toggle1="tooltip" title="Delete line" data-original-title="Delete line"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>

</tr>
