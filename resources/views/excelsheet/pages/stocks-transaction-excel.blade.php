@extends('excelsheet.layout.master-excel')
@section('content')

<table class="table table-hover table-bordered table-striped nopadding">
	<thead>
		<tr >
			<th class="text-center" colspan="7"><strong>D. F Tex</strong>13/2 West Panthpath,Dhaka 1207</th>
		</tr>
		<tr>
			<th colspan="2">
				<strong>Supplier: </strong>{{isset($inventory_stocks->supplier_name) ? $inventory_stocks->supplier_name:''}}
			</th>
			<td colspan="5" class="text-right" >
				{{isset($inventory_stocks->supplier_company) ? $inventory_stocks->supplier_company:''}}
				,
				{{isset($inventory_stocks->supplier_address) ? $inventory_stocks->supplier_address:''}}
				,<strong>Mobile:</strong>
				{{isset($inventory_stocks->supplier_mobile) ? $inventory_stocks->supplier_mobile:''}}
			</td>
		</tr>
		<tr>
			<td colspan="3"><strong>Date: </strong>
			{{isset($inventory_stocks->stocks_transaction_date) ? date('j M, Y',strtotime($inventory_stocks->stocks_transaction_date)):''}}</td>
			<td colspan="4"><strong>Bill No: </strong>
			{{isset($inventory_stocks->stocks_transaction_date) ? date('Ymd',strtotime($inventory_stocks->stocks_transaction_date)):''}}{{isset($inventory_stocks->stocks_transactions_id) ? $inventory_stocks->stocks_transactions_id:''}}</td>
		</tr>
		<tr>
		
			<th> Item </th>
			<th class="hidden-480"> Description </th>
			<th class="hidden-480"> Purpose </th>
			<th class="hidden-480"> Quantity ({{isset($inventory_stocks->item_quantity_unit) ? $inventory_stocks->item_quantity_unit:''}})</th>
			<th class="hidden-480"> Rate (Tk.)</th>
			<th> Total(Tk.) </th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>{{isset($inventory_stocks->item_name) ? $inventory_stocks->item_name:''}} </td>
			<td class="hidden-480"> {{isset($inventory_stocks->stocks_transaction_desc) ? $inventory_stocks->stocks_transaction_desc:''}} </td>
			<td class="hidden-480"> {{isset($inventory_stocks->cost_center_name) ? $inventory_stocks->cost_center_name:''}} </td>
			<td class="hidden-480"> {{isset($inventory_stocks->transaction_stocks_quantity) ? $inventory_stocks->transaction_stocks_quantity:''}}</td>
			<td class="hidden-480">{{isset($inventory_stocks->stocks_quantity_rate) ? number_format($inventory_stocks->stocks_quantity_rate,2,'.','' ):''}}</td>
			<td> {{isset($inventory_stocks->stocks_quantity_cost) ? number_format($inventory_stocks->stocks_quantity_cost,2,'.','' ):''}} </td>
		</tr>
	</tbody>
</table>
@stop