@extends('layout.master')
@section('content')

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
				<strong> All Ledger </strong>
			</div>

			<div class="panel-body">
				<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">
				<div class="table-responsive " style="height: 400px; overflow: auto; padding: 5px">

					

					<ul id="myUL">
						@if(!empty($journal_data_node) && count($journal_data_node)>0)
						@foreach($journal_data_node as $key => $list)

							<li ><a target="_blank" href="{{url('/journal/debit-cerdit/details/id-'.$list->ledger_id)}}">{{trim($list->ledger_name)}}</a></li>
						@endforeach
						@else
						
							<li colspan="1" class="text-center"> No data Available</li>
							
						@endif
						</ul>

						<script>
							function myFunction() {
							    var input, filter, ul, li, a, i;
							    input = document.getElementById("myInput");
							    //alert(input);
							    filter = input.value.toUpperCase();
							    ul = document.getElementById("myUL");
							    li = ul.getElementsByTagName("li");
							    for (i = 0; i < li.length; i++) {
							        a = li[i].getElementsByTagName("a")[0];
							        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
							            li[i].style.display = "";
							        } else {
							            li[i].style.display = "none";

							        }
							    }
							}
						</script>

				</div>
			</div>

		</div>
	</div>
</div>


@stop