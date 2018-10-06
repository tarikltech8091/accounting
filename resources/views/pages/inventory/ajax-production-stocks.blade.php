<?php 
 $inventory_stocks_list= \DB::table('ltech_inventory_stocks')
  ->get();
  //echo('r');

?>

<tr class="production_stocks_entry_group_{{$i}}">
    <td>{{$i}}</td>

    <td class="production_inventory_stocks_td" data-rowid="{{$i}}">
      <select data-rowid="{{$i}}" class="form-control production_inventory_stocks production_inventory_stocks_row_{{$i}}" name="production_inventory_stocks_id_{{$i}}" required>
        <option value="0">Choose a product</option>
        @if(isset($inventory_stocks_list) && (count($inventory_stocks_list) > 0))
          @foreach($inventory_stocks_list as $key => $stocks)
          <option value="{{$stocks->inventory_stock_id}}">{{$stocks->item_name}}</option>
          @endforeach
        @endif
      </select>
    </td>

    <td><input data-rowid="{{$i}}" type="text" class="form-control production_stocks_onhand production_stocks_onhand_row_{{$i}}" name="production_stocks_onhand_{{$i}}" disabled="" /> </td>

    <td><input data-rowid="{{$i}}" type="text" class="form-control production_transaction_stocks_quantity production_transaction_stocks_quantity_row_{{$i}}" name="production_transaction_stocks_quantity_{{$i}}" value="" required /> </td>

    <td><input data-rowid="{{$i}}" class="form-control production_stocks_transaction_desc production_stocks_transaction_desc_row_{{$i}}"  name="production_stocks_transaction_desc_{{$i}}" required></td>

</tr>
