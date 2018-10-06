<?php 
 $inventory_stocks_list = \DB::table('ltech_inventory_stocks')
                                                ->leftjoin('ltech_item_categories','ltech_inventory_stocks.item_category_id','=','ltech_item_categories.item_category_id')
                                                ->select('ltech_inventory_stocks.*','ltech_item_categories.*')
                                                ->OrderBy('ltech_inventory_stocks.inventory_stock_id','desc')
                                                ->get();
$inventory_stocks_account = \App\Journal::GetLedgerAllChild('Stock-in-hand',3);


?>

<tr class="stocks_entry_group_{{$i}}">
    <td>{{$i}}</td>
    <td class="inventory_stocks_td" data-rowid="{{$i}}">
      <select data-rowid="{{$i}}" class="form-control inventory_stocks inventory_stocks_row_{{$i}}" name="inventory_stocks_id_{{$i}}" required>
        <option value="0">Choose a product</option>
        @if(isset($inventory_stocks_list) && (count($inventory_stocks_list) > 0))
          @foreach($inventory_stocks_list as $key => $stocks)
            <option value="{{$stocks->inventory_stock_id}}">{{$stocks->item_name}}</option>
          @endforeach
        @else
            <option>Add Product in Stock</option>
        @endif
      </select>
    </td>
    <!-- <td class="stocks_account_td" data-rowid="{{$i}}">
      <select data-rowid="{{$i}}" class="form-control stocks_account stocks_account_row_{{$i}}" name="stocks_account_id_{{$i}}" required>
        <option value="">Choose a Stock Account</option>
        @if(isset($inventory_stocks_account) && (count($inventory_stocks_account)>0))
          @foreach($inventory_stocks_account as $key => $stocks_account)
          <option data-depth="{{isset($stocks_account->depth)? $stocks_account->depth:''}}" 
          data-parent="{{isset($stocks_account->ledger_group_parent_id)? $stocks_account->ledger_group_parent_id:''}}" data-slug="{{isset($stocks_account->ledger_name_slug)? $stocks_account->ledger_name_slug:''}}" value="{{isset($stocks_account->ledger_id)? $stocks_account->ledger_id.'.'.$stocks_account->depth.'.'.$stocks_account->ledger_name_slug.'.'.$stocks_account->ledger_name :''}}">{{isset($stocks_account->ledger_name)? $stocks_account->ledger_name:''}}</option>
          @endforeach
        @else
          <option>Create Stock Account</option>
        @endif
      </select>
    </td> -->
    <td><input data-rowid="{{$i}}" type="text" class="form-control transaction_stocks_quantity transaction_stocks_quantity_row_{{$i}}" name="transaction_stocks_quantity_{{$i}}" value="" required/> </td>
 
    <td><input data-rowid="{{$i}}" type="text" class="form-control stocks_quantity_rate stocks_quantity_rate_row_{{$i}}" name="stocks_quantity_rate_{{$i}}" value="" required/> </td>
    
    <td><input data-rowid="{{$i}}" type="text" class="form-control stocks_quantity_cost stocks_quantity_cost_row_{{$i}}" name="stocks_quantity_cost_{{$i}}" value="" required /></td>
    <td>
      <a data-rowid="{{$i}}" class="btn btn-xs btn-bricky stocks_entry_remove_btn" data-toggle1="tooltip" title="Delete line" data-original-title="Delete line"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
      <!-- <a data-rowid="{{$i}}"  class="btn btn-xs btn-purple tooltips stocks_clear stocks_clear_row_{{$i}}" data-toggle1="tooltip" title="Clear Data" data-original-title="Clear Data"><i class="fa fa-times" aria-hidden="true"></i></a> -->
    </td>
</tr>