<?php 
 $inventory_stocks_list= \DB::table('ltech_inventory_stocks')
  ->get();
  //echo('r');

?>

<tr class="finishgoods_stocks_entry_group_{{$i}}">
  <td>{{$i}}</td>
  <td class="finishgoods_inventory_stocks_td" data-rowid="{{$i}}">
    <select data-rowid="{{$i}}" class="form-control finishgoods_inventory_stocks finishgoods_inventory_stocks_row_{{$i}}" name="finishgoods_inventory_stocks_id_{{$i}}" required>
      <option value="0">Choose a product</option>
      @if(isset($inventory_stocks_list) && (count($inventory_stocks_list) > 0))
        @foreach($inventory_stocks_list as $key => $stocks)
        <option value="{{$stocks->inventory_stock_id}}">{{$stocks->item_name}}</option>
        @endforeach
      @endif
    </select>
  </td>

  <td><input data-rowid="{{$i}}" type="text" class="form-control finishgoods_stocks_onhand finishgoods_stocks_onhand_row_{{$i}}" name="finishgoods_stocks_onhand_{{$i}}" value="" disabled="" /><input type="hidden" class="finishgoods_stocks_onhand_quantity_row_{{$i}}" name="finishgoods_stocks_onhand_quantity_{{$i}}" value=""></td>

  <td><input data-rowid="{{$i}}" type="text" class="form-control finishgoods_transaction_stocks_quantity finishgoods_transaction_stocks_quantity_row_{{$i}}" name="finishgoods_transaction_stocks_quantity_{{$i}}" value="" required /> </td>

  <td><input data-rowid="{{$i}}" class="form-control finishgoods_stocks_transaction_amount finishgoods_stocks_transaction_amount_row_{{$i}}"  name="finishgoods_stocks_transaction_amount_{{$i}}" required></td>
  <td><a data-rowid="{{$i}}" class="btn btn-xs btn-bricky finishgoods_entry_remove_btn" data-toggle1="tooltip" title="Delete line" data-original-title="Delete line"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
  </td>

</tr>