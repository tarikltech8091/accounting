@extends('layout.master')
@section('content')

<!--error message*******************************************-->
<div class="row">
	<div class="col-md-6">
		@if($errors->count() > 0 )

		<div class="alert alert-danger">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<h6>The following errors have occurred:</h6>
			<ul>
				@foreach( $errors->all() as $message )
				<li>{{ $message }}</li>
				@endforeach
			</ul>
		</div>
		@endif

		@if(Session::has('message'))
		<div class="alert alert-success" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('message') }}
		</div> 
		@endif

		@if(Session::has('errormessage'))
		<div class="alert alert-danger" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('errormessage') }}
		</div>
		@endif

	</div>
</div>
<!--end of error message*************************************-->

<div class="row" style="margin-bottom:20px;">
	<div class="col-md-12">
		<div class="panel panel-default">
		<?php $company_details=\DB::table('company_details')->latest()->first(); ?>
			<div class="row" align="center">
				<h2>
					{{isset($company_details->company_name)? $company_details->company_name :''}}
				</h2><br>
				{{isset($company_details->company_address)? $company_details->company_address :''}}
			</div><br>

			<div class="panel panel-heading" align="center">
				<strong> All Ledger Opening Balance </strong>
				<!-- <a href="" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="All Journal PDF"><i class="fa fa-print" aria-hidden="true"></i></a> -->
			</div>




			<div class="panel-body">
				<input type="text" id="myInput" onkeyup="myFunctionOpentbl(1)" placeholder="Search for names.." title="Type in a name">

				<div class="table-responsive cost_list posting_list" style="height: 400px; overflow: auto; padding: 5px">
					<table class="table table-hover table-bordered table-striped nopadding">
						<thead>
				            <tr>
				                <th>Sl</th>
				                <th class="text-center">Ledger Name</th>
				                <th>Debit</th>
				                <th>Credit</th>
				                <th>Action</th>
				            </tr>

						</thead>
						<tbody id="myULtbl">


						@if(!empty($journal_data_node) && count($journal_data_node)>0)
						@foreach($journal_data_node as $key => $list)

							<?php 
								$total_debit_amount=0;
								$total_credit_amount=0;

                                $all_journal_info = \DB::table('ltech_general_journal')
                                ->where('ltech_general_journal.journal_particular_id', '=', $list->ledger_id)
                                ->get();

                                if(!empty($all_journal_info)){

	                        		foreach($all_journal_info as $key1 => $j_amount){
                                		if(($j_amount->journal_particular_amount_type) == 'credit'){

		                            	$total_credit_amount=$total_credit_amount+($j_amount->journal_particular_amount);
		                        		}
		                        		elseif(($j_amount->journal_particular_amount_type) == 'debit'){
		                        		$total_debit_amount=$total_debit_amount+($j_amount->journal_particular_amount);

		                        		}
	                        		}

	                        	}
	                        	else{
	                        		$total_credit_amount=0;
	                        		$total_debit_amount=0;
	                        	}

							?>

							<tr>
								<td>{{$key+1}}</td>
								<td>{{$list->ledger_name}}</td>
								<td>{{$list->ledger_debit}}</td>
								<td>{{$list->ledger_credit}}</td>
								<td><a data-toggle="modal" data-target="#OpeningBalanceModel" data-id="{{$list->ledger_id}}" data-depth="{{$list->depth}}" class="text_none btn btn-success ledger_data_show"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
								</td>
							</tr>


						@endforeach
						@else
							<tr>
								<td colspan="4" class="text-center"> No data Available</td>
							</tr>
						@endif
						</tbody>
					</table>
					{{isset($journal_pagination)? $journal_pagination :''}}
				</div>
			</div>

		</div>
	</div>
</div>


<!-- Modal -->
<div id="OpeningBalanceModel" class="modal fade " rtabindex="-1" role="dialog">
	<div class="modal-dialog ">
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Ledger Opening Balance</h4>
			</div>
			<div class="modal-body">
	  			<form method="post" action="{{url('/ledger/opening/balance-confirm')}}">
	  			<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="ledger_data_details">


					</div>
					<div class="">
	          			<input type="submit" name="submit" class="btn btn-success" value="save">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default"  data-dismiss="modal">OK</button>
			</div>
		</div>
	</div>
</div>

<script>

function myFunctionOpentbl(column) {
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