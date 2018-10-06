@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="clip-users-2"></i>
				Account Create
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" data-toggle="tooltip" data-placement="top" title="Show / Hide" href="#">
					</a>
					<a class="btn btn-xs btn-link panel-close red-tooltip" data-toggle="tooltip" data-placement="top" title="Close" href="#">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>
			<div class="panel-body"> <!--Start Panel Body -->
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
		 	@if(isset($journalinfo) && count($journalinfo) > 0)
				<div class="tabbable"><!--Start Tab -->
					<ul id="myTab4" class="nav nav-tabs tab-bricky">
						<li class="{{(isset($tab)&& ($tab=='add_ledger')) ? 'active':''}}">
							<a href="#add_ledger" data-toggle="tab">
								Add Account
							</a>
						</li>
						<li class="{{(isset($tab)&& ($tab=='add_sub_ledger')) ? 'active':''}}">
							<a href="#add_sub_ledger" data-toggle="tab">
								Add Sub Account 
							</a>
						</li>
						
					</ul>
					<div class="tab-content">
						<div class="tab-pane in {{(isset($tab)&& ($tab=='add_ledger')) ? 'active':''}}" id="add_ledger">
					
							<form action="{{url('/journal/ledger-'.$journalinfo->ledger_id.'/depth-'.$journalinfo->depth)}}" method="POST">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<input type="hidden" name="add_type" value="add_ledger">
								<div class="form-group">
									<label> ledger Name</label>
									<input type="text" name="add_ledger_name" class="ledger_name form-control" value="{{old('add_ledger_name')}}" required>
								</div>

								<label for="form-field-select-1">
									Ledger Head
								</label>
								<select id="add_ledgere_parent_id" class="form-control" name="add_ledger_parent_id">
									<option value="">Select</option>
									@if(isset($journal_data_node) && count($journal_data_node) > 0)

										@foreach($journal_data_node as $key => $journal)
											<option value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
										@endforeach
									@endif
								</select>
								<div class="form-group col-md-6 ledger_amount">
									<label for="Debit_naration" class="">
										Debit
									</label>
									<div class="">
										<span class="input-icon">
											<input placeholder=" 0.0" id="form-field-16" name="ledger_debit" class="form-control" type="text" >
											<i class="fa fa-money"></i> </span>
									</div>
								</div>
								<div class="form-group col-md-6 ledger_amount">
									<label for="Debit_naration" class="">
										Credit
									</label>
									<div class="">
										<span class="input-icon">
											<input placeholder=" 0.0" id="form-field-16" name="ledger_credit" class="form-control" type="text" >
											<i class="fa fa-money"></i> </span>
									</div>
								</div>

								<div class="form-group">
									
										<a href="{{URL::previous()}}" class="btn btn-default">Back</a>
										<button class="btn btn-purple" type="submit">
												Add <i class="fa fa-arrow-circle-right"></i>
										</button>
									
								</div>

							</form>

						</div>
						<div class="tab-pane {{(isset($tab)&& ($tab=='add_sub_ledger')) ? 'active':''}}" id="add_sub_ledger">
							<form action="{{url('/journal/ledger-'.$journalinfo->ledger_id.'/depth-'.$journalinfo->depth)}}" method="post">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<input type="hidden" name="add_type" value="add_sub_ledger">
								<div class="form-group">
									<label> ledger Name</label>
									<input type="text" name="add_sub_ledger_name" class="ledger_name form-control" >
								</div>

								<div class="form-group">
									<label for="form-field-select-1">
										Current	Ledger Head
									</label>

									@if(isset($journalinfo) && count($journalinfo) > 0)
										
									<input type="text" name="parent_node" class="form-control" value="{{$journalinfo->ledger_name}}" disabled="">
									
									@endif
								</div>
								<div class="form-group col-md-6 ledger_amount">
									<label for="Debit_naration" class="">
										Debit
									</label>
									<div class="">
										<span class="input-icon">
											<input placeholder=" 0.0" id="form-field-16" name="ledger_debit" class="form-control" type="text" >
											<i class="fa fa-money"></i> </span>
									</div>
								</div>
								<div class="form-group col-md-6 ledger_amount">
									<label for="Debit_naration" class="">
										Credit
									</label>
									<div class="">
										<span class="input-icon">
											<input placeholder=" 0.0" id="form-field-16" name="ledger_credit" class="form-control" type="text" >
											<i class="fa fa-money"></i> </span>
									</div>
								</div>
								<div class="form-group">
									
										<a href="{{URL::previous()}}" class="btn btn-default">Back</a>
										<button class="btn btn-purple" type="submit">
												Add <i class="fa fa-arrow-circle-right"></i>
										</button>
									
								</div>
							</form>
						</div>
					</div>	
				</div><!--End Tab -->
			@endif
			</div><!--End Panel Body -->
		</div>
	</div>

	<div class="col-md-6">
		@include('layout.ledger-group')
	</div>
</div>
@stop