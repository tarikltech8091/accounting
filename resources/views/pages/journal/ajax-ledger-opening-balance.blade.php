<div class="panel panel-info">
 <p style="margin:20px">
  <strong>Ledger Name :</strong> {{isset($select_ledger_info->ledger_name)? $select_ledger_info->ledger_name :''}}
 </p>
 <div class="panel-body">

  <table border="1" class="table table-hover table-bordered table-striped">
    <thead>
  		<th>Debit</th>
  		<th>Credit</th>
    </thead>
  	<tbody>  
		  <tr>
	  		<td>
          <input type="hidden" class="form-control" name="ledger_id" value="{{isset($select_ledger_info->ledger_id)? $select_ledger_info->ledger_id :0}}" />
          <input type="hidden" class="form-control" name="depth" value="{{isset($select_ledger_info->depth)? $select_ledger_info->depth :0}}"/>
          <input type="text" class="form-control" name="ledger_debit" value="{{isset($select_ledger_info->ledger_debit)? $select_ledger_info->ledger_debit :0}}"/>
        </td>
	  		<td>
          <input type="text" class="form-control" name="ledger_credit" value="{{isset($select_ledger_info->ledger_credit)? $select_ledger_info->ledger_credit :0}}"/>   
        </td>    	
	    </tr>
  	</tbody>
  </table>
 </div>
</div>