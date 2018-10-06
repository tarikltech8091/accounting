@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-md-7">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="clip-users-2"></i>
				Posting
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" data-toggle="tooltip" data-placement="top" title="Show / Hide" href="#">
					</a>
					<a class="btn btn-xs btn-link panel-config" data-toggle="tooltip" data-placement="top" title="Add Account" href="#">
						<i class="clip-folder-plus"></i>
					</a>
					<a class="btn btn-xs btn-link panel-close red-tooltip" data-toggle="tooltip" data-placement="top" title="Close" href="#">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>
			<div class="panel-body journal_posting">
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

			<div class="tabbable tabs-left">
				<ul id="myTab3" class="nav nav-tabs tab-green">
					<li class="{{(isset($post_tab)&& ($post_tab=='panel_journal')) ? 'active':''}}">
						<a href="#panel_journal" data-toggle="tab">
							<i class="pink fa fa-file-code-o"></i> Journal
						</a>
					</li>
					<li class="{{(isset($post_tab)&& ($post_tab=='panel_receipt')) ? 'active':''}}">
						<a href="#panel_receipt" data-toggle="tab">
							<i class="blue fa fa-ticket"></i> Receipt
						</a>
					</li>
					<li class="{{(isset($post_tab)&& ($post_tab=='panel_payment')) ? 'active':''}}">
						<a href="#panel_payment" data-toggle="tab">
							<i class="fa fa-credit-card"></i> Payment
						</a>
					</li>
					<li class="{{(isset($post_tab)&& ($post_tab=='panel_sales')) ? 'active':''}}">
						<a href="#panel_sales" data-toggle="tab">
							<i class="fa fa-money"></i> Sales
						</a>
					</li>
					<li class="{{(isset($post_tab)&& ($post_tab=='panel_purchase')) ? 'active':''}}">
						<a href="#panel_purchase" data-toggle="tab">
							<i class="fa fa-dollar"></i> Purchase
						</a>
					</li>
					<li class="{{(isset($post_tab)&& ($post_tab=='panel_sales_return')) ? 'active':''}}">
						<a href="#panel_sales_return" data-toggle="tab">
							<i class="fa fa-strikethrough"></i> Sales Return
						</a>
					</li>
					<li class="{{(isset($post_tab)&& ($post_tab=='panel_purchase_return')) ? 'active':''}}">
						<a href="#panel_purchase_return" data-toggle="tab">
							<i class="fa fa-caret-square-o-left"></i> Purchase Return
						</a>
					</li>
					
				</ul>
				<div class="tab-content">


					<div class="tab-pane {{(isset($post_tab)&& ($post_tab=='panel_journal')) ? 'active':''}}" id="panel_journal"> <!-- Start: Journal Posting-->
						<form action="{{url('/journal')}}" method="POST">
					 		<input type="hidden" name="_token" value="{{csrf_token()}}">
					 		<input type="hidden" name="posting_type" value="general_journal">
					 		<div class="page-header text-center">Journal</div>



		
							<div class="form-group">
								<label for="Debit_naration" class="col-md-3">
									Amount (Tk.)
								</label>
								<div class="col-md-5">
									<span class="input-icon">
										<input placeholder=" 0.0" id="form-field-16" name="transaction_amount" class="form-control" type="text" required>
										<i class="fa fa-money"></i> </span>
								</div>
							</div>



							<div class="form-group">
								<label for="form-field-6" class="col-md-1">
									Debit
								</label>
								<div class="col-md-11">
									<select  data-post="journal" class="debit_ledger form-control" name="debit_ledger" required>
										<option value="">Select</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif
																	
									</select>
								</div>
								<input type="hidden" name="debit_ledger_depth" id="debit_ledger_depth_journal" value="">
							</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-2">
									Naration
								</label>
								<div class="col-md-10">
									<span class="input-icon">
										<input placeholder="Naration" name="debit_naration" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
							<div class="form-group">
								<label for="form-field-6" class="col-md-1">
									Credit
								</label>
								<div class="col-md-11">
									<select  data-post="journal" class="credit_ledger form-control" name="credit_ledger" required>
										<option value="">Select</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif
									</select>
								</div>
								<input type="hidden" name="credit_ledger_depth" id="credit_ledger_depth_journal" value="">
							</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-2">
									Naration
								</label>
								<div class="col-md-10">
									<span class="input-icon">
										<input placeholder="Naration" name="credit_naration" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
							
							<div class="form-group">
								<label for="Debit_naration" class="col-md-3">
									Transaction Detail
								</label>
								<div class="col-md-9">
									<textarea class="form-control" name="transaction_details" required></textarea>
									
								</div>
							</div>
							<div class="form-group pull-right">  
								<a class="btn btn-default" href="{{\Request::fullUrl()}}">
								Cancel 
								</a>
								<button class="btn btn-purple" type="submit">
								Posting <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</form>
					</div> <!-- END: Journal Posting-->
					<div class="tab-pane {{(isset($post_tab)&& ($post_tab=='panel_receipt')) ? 'active':''}}" id="panel_receipt"> <!-- Start: Journal receipt-->
						<form action="{{url('/journal')}}" method="POST">
					 		<input type="hidden" name="_token" value="{{csrf_token()}}">
					 		<input type="hidden" name="posting_type" value="general_receipt">
					 		<div class="page-header text-center">General Receipt</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-3">
									Amount (Tk.)
								</label>
								<div class="col-md-5">
									<span class="input-icon">
										<input placeholder=" 0.0" id="form-field-16" name="transaction_amount" class="form-control" type="text" required>
										<i class="fa fa-money"></i> </span>
								</div>
							</div>


							<div class="form-group">
								<label for="form-field-6" class="col-md-1">
									Debit
								</label>
								<div class="col-md-11">
									<select  data-post="receipt" class="debit_ledger form-control" name="debit_ledger" required>
										<option value="">&nbsp;</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif
																	
									</select>
								</div>
								<input type="hidden" name="debit_ledger_depth" id="debit_ledger_depth_receipt" value="">
							</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-2">
									Naration
								</label>
								<div class="col-md-10">
									<span class="input-icon">
										<input placeholder="Naration" name="debit_naration" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
							<div class="form-group">
								<label for="form-field-6" class="col-md-1">
									Credit
								</label>
								<div class="col-md-11">
									<select data-post="receipt" class="credit_ledger form-control" name="credit_ledger" required>
										<option value="">&nbsp;</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif
									</select>
								</div>
								<input type="hidden" name="credit_ledger_depth" id="credit_ledger_depth_receipt" value="">
							</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-2">
									Naration
								</label>
								<div class="col-md-10">
									<span class="input-icon">
										<input placeholder="Naration" name="credit_naration" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
							
							<div class="form-group">
								<label for="Debit_naration" class="col-md-3">
									Transaction Detail
								</label>
								<div class="col-md-9">
									<textarea class="form-control" name="transaction_details" required></textarea>
									
								</div>
							</div>
							<div class="form-group pull-right">  
								<a class="btn btn-default" href="{{\Request::fullUrl()}}">
								Cancel 
								</a>
								<button class="btn btn-purple" type="submit">
								Posting <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</form>
					</div> <!-- END: Journal receipt-->

					<div class="tab-pane {{(isset($post_tab)&& ($post_tab=='panel_payment')) ? 'active':''}}" id="panel_payment"> <!-- Start: Journal payment-->
						<form action="{{url('/journal')}}" method="POST">
					 		<input type="hidden" name="_token" value="{{csrf_token()}}">
					 		<input type="hidden" name="posting_type" value="general_payment">
					 		<div class="page-header text-center">General Payment</div>

							<div class="form-group">
								<label for="Debit_naration" class="col-md-3">
									Amount (Tk.)
								</label>
								<div class="col-md-5">
									<span class="input-icon">
										<input placeholder=" 0.0" id="form-field-16" name="transaction_amount" class="form-control" type="text" required>
										<i class="fa fa-money"></i> </span>
								</div>
							</div>


							<div class="form-group">
								<label for="form-field-6" class="col-md-1">
									Debit
								</label>
								<div class="col-md-11">
									<select data-post="payment" class="debit_ledger form-control" name="debit_ledger" required>
										<option value="">&nbsp;</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif
																	
									</select>
								</div>
								<input type="hidden" name="debit_ledger_depth" id="debit_ledger_depth_payment" value="">
							</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-2">
									Naration
								</label>
								<div class="col-md-10">
									<span class="input-icon">
										<input placeholder="Naration" name="debit_naration" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
							<div class="form-group">
								<label for="form-field-6" class="col-md-1">
									Credit
								</label>
								<div class="col-md-11">
									<select data-post="payment" class="credit_ledger form-control" name="credit_ledger" required>
										<option value="">&nbsp;</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif
									</select>
								</div>
								<input type="hidden" name="credit_ledger_depth" id="credit_ledger_depth_payment" value="">
							</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-2">
									Naration
								</label>
								<div class="col-md-10">
									<span class="input-icon">
										<input placeholder="Naration" name="credit_naration" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
							
							<div class="form-group">
								<label for="Debit_naration" class="col-md-3">
									Transaction Detail
								</label>
								<div class="col-md-9">
									<textarea class="form-control" name="transaction_details" required></textarea>
									
								</div>
							</div>
							<div class="form-group pull-right">  
								<a class="btn btn-default" href="{{\Request::fullUrl()}}">
								Cancel 
								</a>
								<button class="btn btn-purple" type="submit">
								Posting <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</form>
					</div> <!-- END: Journal payment-->
					<div class="tab-pane {{(isset($post_tab)&& ($post_tab=='panel_sales')) ? 'active':''}}" id="panel_sales"> <!-- Start: Journal sales-->
						<form action="{{url('/journal')}}" method="POST">
					 		<input type="hidden" name="_token" value="{{csrf_token()}}">
					 		<input type="hidden" name="posting_type" value="general_sales">
					 		<div class="page-header text-center">General Sales</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-3">
									Amount (Tk.)
								</label>
								<div class="col-md-5">
									<span class="input-icon">
										<input placeholder=" 0.0" id="form-field-16" name="transaction_amount" class="form-control" type="text" required>
										<i class="fa fa-money"></i> </span>
								</div>
							</div>


							<div class="form-group">
								<label for="form-field-6" class="col-md-1">
									Debit
								</label>
								<div class="col-md-11">
									<select data-post="sales" class="debit_ledger form-control" name="debit_ledger" required>
										<option value="">&nbsp;</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif
																	
									</select>
								</div>
								<input type="hidden" name="debit_ledger_depth" id="debit_ledger_depth_sales" value="">
							</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-2">
									Naration
								</label>
								<div class="col-md-10">
									<span class="input-icon">
										<input placeholder="Naration" name="debit_naration" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
							<div class="form-group">
								<label for="form-field-6" class="col-md-1">
									Credit
								</label>
								<div class="col-md-11">
									<select data-post="sales" class="credit_ledger form-control" name="credit_ledger" required>
										<option value="">&nbsp;</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif
									</select>
								</div>
								<input type="hidden" name="credit_ledger_depth" id="credit_ledger_depth_sales" value="">
							</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-2">
									Naration
								</label>
								<div class="col-md-10">
									<span class="input-icon">
										<input placeholder="Naration" name="credit_naration" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
							
							<div class="form-group">
								<label for="Debit_naration" class="col-md-3">
									Transaction Detail
								</label>
								<div class="col-md-9">
									<textarea class="form-control" name="transaction_details" required></textarea>
									
								</div>
							</div>
							<div class="form-group pull-right">  
								<a class="btn btn-default" href="{{\Request::fullUrl()}}">
								Cancel 
								</a>
								<button class="btn btn-purple" type="submit">
								Posting <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</form>
					</div> <!-- END: Journal sales-->
					<div class="tab-pane {{(isset($post_tab)&& ($post_tab=='panel_purchase')) ? 'active':''}}" id="panel_purchase"> <!-- Start: Journal purchase-->
						<form action="{{url('/journal')}}" method="POST">
					 		<input type="hidden" name="_token" value="{{csrf_token()}}">
					 		<input type="hidden" name="posting_type" value="general_purchase">
					 		<div class="page-header text-center">General Purchase</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-3">
									Amount (Tk.)
								</label>
								<div class="col-md-5">
									<span class="input-icon">
										<input placeholder=" 0.0" id="form-field-16" name="transaction_amount" class="form-control" type="text" required>
										<i class="fa fa-money"></i> </span>
								</div>
							</div>


							<div class="form-group">
								<label for="form-field-6" class="col-md-1">
									Debit
								</label>
								<div class="col-md-11">
									<select name="debit_ledger" data-post="purchase" class="debit_ledger form-control"  required>
										<option value="">&nbsp;</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif
																	
									</select>
								</div>
								<input type="hidden" name="debit_ledger_depth" id="debit_ledger_depth_purchase" value="">
							</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-2">
									Naration
								</label>
								<div class="col-md-10">
									<span class="input-icon">
										<input placeholder="Naration" name="debit_naration" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
							<div class="form-group">
								<label for="form-field-6" class="col-md-1">
									Credit
								</label>
								<div class="col-md-11">
									<select data-post="purchase" class="credit_ledger form-control" name="credit_ledger" required>
										<option value="">&nbsp;</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif
									</select>
								</div>
								<input type="hidden" name="credit_ledger_depth" id="credit_ledger_depth_purchase" value="">
							</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-2">
									Naration
								</label>
								<div class="col-md-10">
									<span class="input-icon">
										<input placeholder="Naration" name="credit_naration" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
							
							<div class="form-group">
								<label for="Debit_naration" class="col-md-3">
									Transaction Detail
								</label>
								<div class="col-md-9">
									<textarea class="form-control" name="transaction_details" required></textarea>
									
								</div>
							</div>
							<div class="form-group pull-right">  
								<a class="btn btn-default" href="{{\Request::fullUrl()}}">
								Cancel 
								</a>
								<button class="btn btn-purple" type="submit">
								Posting <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</form>
					</div> <!-- END: Journal purchase-->

					<div class="tab-pane {{(isset($post_tab)&& ($post_tab=='panel_sales_return')) ? 'active':''}}" id="panel_sales_return"> <!-- Start: Journal sales_return-->
						<form action="{{url('/journal')}}" method="POST">
					 		<input type="hidden" name="_token" value="{{csrf_token()}}">
					 		<input type="hidden" name="posting_type" value="general_sales_return">
					 		<div class="page-header text-center">General Sales Return</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-3">
									Amount (Tk.)
								</label>
								<div class="col-md-5">
									<span class="input-icon">
										<input placeholder=" 0.0" id="form-field-16" name="transaction_amount" class="form-control" type="text" required>
										<i class="fa fa-money"></i> </span>
								</div>
							</div>


							<div class="form-group">
								<label for="form-field-6" class="col-md-1">
									Debit
								</label>
								<div class="col-md-11">
									<select data-post="sales_return" class="debit_ledger form-control" name="debit_ledger" required>
										<option value="">&nbsp;</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif
																	
									</select>
								</div>
								<input type="hidden" name="debit_ledger_depth" id="debit_ledger_depth_sales_return" value="">
							</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-2">
									Naration
								</label>
								<div class="col-md-10">
									<span class="input-icon">
										<input placeholder="Naration" name="debit_naration" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
							<div class="form-group">
								<label for="form-field-6" class="col-md-1">
									Credit
								</label>
								<div class="col-md-11">
									<select data-post="sales_return" class="credit_ledger form-control" name="credit_ledger" required>
										<option value="">&nbsp;</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif
									</select>
								</div>
								<input type="hidden" name="credit_ledger_depth" id="credit_ledger_depth_sales_return" value="">
							</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-2">
									Naration
								</label>
								<div class="col-md-10">
									<span class="input-icon">
										<input placeholder="Naration" name="credit_naration" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
							
							<div class="form-group">
								<label for="Debit_naration" class="col-md-3">
									Transaction Detail
								</label>
								<div class="col-md-9">
									<textarea class="form-control" name="transaction_details" required></textarea>
									
								</div>
							</div>
							<div class="form-group pull-right">  
								<a class="btn btn-default" href="{{\Request::fullUrl()}}">
								Cancel 
								</a>
								<button class="btn btn-purple" type="submit">
								Posting <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</form>
					</div> <!-- END: Journal sales_return-->
					<div class="tab-pane {{(isset($post_tab)&& ($post_tab=='panel_purchase_return')) ? 'active':''}}" id="panel_purchase_return"> <!-- Start: Journal purchase_return-->
						<form action="{{url('/journal')}}" method="POST">
					 		<input type="hidden" name="_token" value="{{csrf_token()}}"> 
					 		<input type="hidden" name="posting_type" value="general_purchase_return">
					 		<div class="page-header text-center">General Purchase Return</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-3">
									Amount (Tk.)
								</label>
								<div class="col-md-5">
									<span class="input-icon">
										<input placeholder=" 0.0" id="form-field-16" name="transaction_amount" class="form-control" type="text" required>
										<i class="fa fa-money"></i> </span>
								</div>
							</div>


							<div class="form-group">
								<label for="form-field-6" class="col-md-1">
									Debit
								</label>
								<div class="col-md-11">
									<select data-post="purchase_return" class="debit_ledger form-control" name="debit_ledger" required>
										<option value="">&nbsp;</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif
																	
									</select>
								</div>
								<input type="hidden" name="debit_ledger_depth" id="debit_ledger_depth_purchase_return" value="">
							</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-2">
									Naration
								</label>
								<div class="col-md-10">
									<span class="input-icon">
										<input placeholder="Naration" name="debit_naration" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
							<div class="form-group">
								<label for="form-field-6" class="col-md-1">
									Credit
								</label>
								<div class="col-md-11">
									<select data-post="purchase_return" class="credit_ledger form-control" name="credit_ledger" required>
										<option value="">&nbsp;</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif
									</select>
								</div>
								<input type="hidden" name="credit_ledger_depth" id="credit_ledger_depth_purchase_return" value="">
							</div>
							<div class="form-group">
								<label for="Debit_naration" class="col-md-2">
									Naration
								</label>
								<div class="col-md-10">
									<span class="input-icon">
										<input placeholder="Naration" name="credit_naration" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
							
							<div class="form-group">
								<label for="Debit_naration" class="col-md-3">
									Transaction Detail
								</label>
								<div class="col-md-9">
									<textarea class="form-control" name="transaction_details" required></textarea>
									
								</div>
							</div>
							<div class="form-group pull-right">  
								<a class="btn btn-default" href="{{\Request::fullUrl()}}">
								Cancel 
								</a>
								<button class="btn btn-purple" type="submit">
								Posting <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</form>
					</div> <!-- END: Journal purchase_return-->
				</div>
			</div>
			</div>
		</div>
	</div>

	<div class="col-md-5">
		@include('layout.ledger-group')
	</div>
</div>


<!-- Node modal-content -->
<div class="modal fade" id="nodeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Ledger Group</h4>
      </div>
      <div class="modal-body">

      		<div class="modal-loading"></div>
	        <label class="radio-inline">
				<input type="radio" class="add_ledger" value="add_ledger" name="ledgerentry">
					Add ledger					
			</label>
			<label class="radio-inline">
				<input type="radio" class="add_sub_ledger" value="add_sub_ledger" name="ledgerentry">
				 Add Sub ledger
			</label>
			<div class="parent_node_select">
				
			</div>
			<div class="form-group">
				<label> ledger Name</label>
				<input type="text" name="ledger_name" class="ledger_name form-control" >
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary save">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /.Node modal-content -->
@stop

