@extends('layout.master')
@section('content')
<div class="row" style="margin-bottom:10px;">
	<div class="col-md-12">
		<div class="panel panel-default">
		<?php $company_details=\DB::table('company_details')->latest()->first(); ?>
			<div class="row" align="center">
				<h2>
					{{isset($company_details->company_name)? $company_details->company_name :''}}
				</h2><br>
				{{isset($company_details->company_address)? $company_details->company_address :''}}
			</div><br>

			<div class="panel-body">
			<input type="text" id="myInput" onkeyup="myFunctiontbl(1)" placeholder="Search for names.." title="Type in a name">

				<div class="table-responsive">
					<table class="table table-hover table-bordered table-striped nopadding">
						<thead>
				            <tr>
				                <th>SL </th>
				                <th>Supplier Company</th>
				                <th>Supplier Name</th>
				                <th>Supplier Mobile</th>
				                <th>Supplier Email</th>
				                <th>Tax Reg No </th>
				                <th>Supplier Address </th>
				                <th>Action</th>
				            </tr>
						</thead>

						<tbody id="myULtbl">

						<?php
						$total_amount=0;
						?>
						@if(!empty($supplier_lists) && count($supplier_lists)>0)
						@foreach ($supplier_lists as $key => $list)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$list->supplier_company}}</td>
								<td>{{$list->supplier_name}}</td>
								<td>{{$list->supplier_mobile}}</td>
								<td>{{$list->supplier_email}}</td>
								<td>{{$list->supplier_tax_reg_no}}</td>
								<td>{{$list->supplier_address}}</td>
								<td>
									<a class="btn btn-success" href="{{url('/edit/supplier/id-'.$list->supplier_id)}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Supplier Edit</a>
								</td>
							</tr>
						@endforeach
						@else
							<tr>
								<td colspan="8" class="text-center">No data Available</td>
							</tr>
						@endif


						</tbody>
					</table>
					{{isset($supplier_pagination)?$supplier_pagination:''}}

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