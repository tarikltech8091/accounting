<?php 
 $inventory_stocks_list = \DB::table('ltech_inventory_stocks')
                                                ->leftjoin('ltech_item_categories','ltech_inventory_stocks.item_category_id','=','ltech_item_categories.item_category_id')
                                                ->select('ltech_inventory_stocks.*','ltech_item_categories.*')
                                                ->OrderBy('ltech_inventory_stocks.inventory_stock_id','desc')
                                                ->get();
$inventory_stocks_account = \App\Journal::GetLedgerAllChild('Stock-in-hand',3);

$journal_posting_field = \App\Journal::GetJournalEntryList();



?>


<tr class="journal_entry_group_{{$i}}">
  <td>{{$i}}</td>
<!--   <td>
    <input data-rowid="{{$i}}" type="text" class="form-control" name="journal_particular_name_{{$i}}" value="Live Ent" />
  </td> -->

  <td>
    @if(!empty($journal_posting_field) && count($journal_posting_field)>0)
      <select data-rowid="{{$i}}" class="form-control" name="journal_particular_name_{{$i}}" required>
    @foreach($journal_posting_field as $key => $list)
        <option value="{{($list->ledger_id).'-'.($list->ledger_name).'-'.($list->depth)}}">{{$list->ledger_name}}</option>
    @endforeach
      </select>

    @endif
  </td>

  <td>
    <select data-rowid="{{$i}}" class="form-control" name="journal_particular_amount_type_{{$i}}" required>
      <option value="">Choose Amount Type</option>
      <option value="debit">Debit</option>
      <option  value="credit">Credit</option>
    </select>
  </td>
  <td>
    <input data-rowid="{{$i}}" type="text" class="form-control" name="journal_particular_amount_{{$i}}" value="" required />
  </td>

  <td>
    <textarea data-rowid="{{$i}}" class="form-control" name="journal_particular_naration_{{$i}}" rows="3" cols="5" required></textarea>
  </td>

    <input data-rowid="{{$i}}" type="hidden" class="form-control" name="journal_id_{{$i}}" value="" />

</tr>