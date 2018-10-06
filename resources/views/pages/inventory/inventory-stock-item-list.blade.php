@extends('layout.master')
@section('content')

<div class="row">
	<div class="col-md-12" style="margin-bottom:40px;">
		<div class="panel panel-default">
			<div class="row">
			<div class="col-md-6">
				<img alt="" src="{{(isset($company_info->company_logo) && !empty($company_info->company_logo)) ? asset($company_info->company_logo):''}}" title="{{(isset($company_info->company_name) && !empty($company_info->company_name)) ? $company_info->company_name:'Company Logo'}}" alt="{{(isset($company_info->company_name) && !empty($company_info->company_name)) ? $company_info->company_name:'Company Logo'}}" style="margin:15px;">
			</div>
			<div class="col-md-6 pull-right">
				<div class="pull-right" style="margin-right:10px;">
					<h3>{{(isset($company_info->company_title) && !empty($company_info->company_title)) ? $company_info->company_title:'Company Title'}}</h3>{{(isset($company_info->company_address) && !empty($company_info->company_address)) ? $company_info->company_address:'Company Address'}}
				</div>
			</div>
			</div><br>

			<div class="panel panel-heading" align="center">
				<strong> Inventory Stocks Item  </strong>
			</div>

			<div class="panel-body">
			

				<div class="col-md-12">
					<div class="row">
						<h2 class="text-center">
						All Inventory Stocks List
						</h2>
					</div>
				</div><br>

			</div>
				
			<div class="panel-body">

				<div class="table-responsive">
				<div class="row">
				<div class="col-md-4 pull-left">
					<input type="text" id="myInput" onkeyup="myFunctiontbl(1)" placeholder="Search for names.." title="Type in a name">
				</div>
				</div>

					<table class="table table-hover table-bordered table-striped nopadding">
					
						<thead>
				              <tr>
				              	<th>SL</th>
				                <th>Name of Item</th>
				                <th>Stocks Type</th>
				                <th>Description</th>
				                <th>Onhand</th>
				                <th>Onproduction</th>
				                <th>Stocks Total Quantity</th>
				              </tr>
						</thead>
						<tbody id="myULtbl">
						@if(isset($inventory_stock_item_list) && count($inventory_stock_item_list)>0)
						@foreach($inventory_stock_item_list as $key => $list)
							<tr>
								<td>{{($key+1)}}</td>
								<td>{{$list->item_name}}</td>
								<td>{{$list->stocks_type}}</td>
								<td>{{str_limit($list->item_description,10)}}</td>
								<td>{{$list->stocks_onhand}}</td>
								<td>{{$list->stocks_onproduction}}</td>
								<td>{{$list->stocks_total_quantity}}</td>

							</tr>
						@endforeach
						@else
							<tr>
								<td colspan="7" class="text-center"> No data Available</td>
							</tr>
						@endif
						</tbody>
					</table>
					{{(isset($stock_list_pagination)? $stock_list_pagination :'')}}

				</div>
			</div>

		</div>
	</div>
</div>

<script>

function myFunctiontbl(column) {
    var input, filter, tbody, tr, td, i,j,row;

    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();

    row = document.getElementById('myULtbl').rows.length;

    //alert(row);
    for (i = 0; i < row; i++) {

      tr = document.getElementById('myULtbl').rows[i];

      for(j=0; j< document.getElementById('myULtbl').rows[i].cells.length; j=j+parseInt(tr.getElementsByTagName("td").length)){
        
        td = tr.getElementsByTagName("td")[column];
        // data = td.getElementsByTagName("a")[0];

        if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
            tr.style.display = "";
        } else {
            tr.style.display = "none";

        }
      }
    }
}
</script>
@stop