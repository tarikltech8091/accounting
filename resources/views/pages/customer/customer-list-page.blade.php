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
				                <th>Customer Company</th>
				                <th>Customer Name</th>
				                <th>Customer Mobile</th>
				                <th>Customer Email</th>
				                <th>Tax Reg No </th>
				                <th>Customer Address </th>
				                <th>Action</th>
				            </tr>
						</thead>

						<tbody id="myULtbl">

						<?php
						$total_amount=0;
						?>
						@if(!empty($customer_lists) && count($customer_lists)>0)
						@foreach ($customer_lists as $key => $list)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$list->customer_company}}</td>
								<td>{{$list->customer_name}}</td>
								<td>{{$list->customer_mobile}}</td>
								<td>{{$list->customer_email}}</td>
								<td>{{$list->customer_tax_reg_no}}</td>
								<td>{{$list->customer_address}}</td>
								<td>
									<a class="btn btn-success" href="{{url('/edit/customer/id-'.$list->customer_id)}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Customer Edit</a>
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
					{{isset($customer_pagination)?$customer_pagination:''}}

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