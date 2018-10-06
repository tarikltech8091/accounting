@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="clip-users-2"></i>
				Posting
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" data-toggle="tooltip" data-placement="top" title="Show / Hide" href="#">
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

				<form action="{{url('/journal/posting/type-'.$posting_type)}}" method="POST">
			 		<input type="hidden" name="_token" value="{{csrf_token()}}">

			 		<div class="page-header text-center">{{isset($posting_type_name) ? $posting_type_name:''}}</div>

			 		<div class="row">

						<div class="col-md-4 pull-left">
							<div class="form-group">
								<label for="form-field-23">
									Voucher<span class="symbol required"></span>
								</label>
								<input class="form-control" type="text" name="voucher" value="{{isset($new_transactions_id)? $new_transactions_id :''}}" readonly="">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="form-field-6" class="">
									Cost Center<span class="symbol required"></span>
								</label>
								<div class="">
									<select  class="form-control" name="cost_center" >
										<option value="">Select</option>
										@if(isset($cost_centers) && count($cost_centers) > 0)
											@foreach($cost_centers as $key => $center)
												<option  value="{{$center->cost_center_id}}">{{$center->cost_center_name}}</option>
											@endforeach
										@endif
																	
									</select>
								</div>
							</div>
						</div>

						<div class="col-md-4 pull-rignt">
							<div class="form-group">
								<label for="form-field-23">
									Date<span class="symbol required"></span>
								</label>
								<div class="input-group">
									<input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="transactions_date" value="{{date("Y-m-d")}}">
									<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
								</div>
							</div>
						</div>

					</div>

			 		<div class="debit_body"><!--Start: Deebit body-->
				 		<div class="debit_group"><!--Start: Deebit group-->
				 			<div class="form-group col-md-6">
								<label for="form-field-6" class="">
									Debit
								</label>
								<div class="">
									<select  data-post="journal" class="debit_ledger form-control" name="debit_ledger[]" required>
										<option value="">Select</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif
																	
									</select>
								</div>
							</div>
							
							<div class="form-group col-md-5">
								<label for="Debit_naration" class="">
									Amount (Tk.)
								</label>
								<div class="">
									<span class="input-icon">
										<input placeholder=" 0.0" id="form-field-16" name="debit_transaction_amount[]" class="form-control" type="text" required>
										<i class="fa fa-money"></i> </span>
								</div>
							</div>
							<div class="col-md-1 debit_add_btn">
								<span class="btn btn-purple" data-toggle="tooltip" data-placement="top" title="More Debit"><i class="fa fa-plus fa fa-white"></i></span>
							</div>

							<div class="form-group col-md-11">
								<label for="Debit_naration">
									Naration
								</label>
								<div class="">
									<span class="input-icon">
										<input placeholder="Naration" name="debit_naration[]" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
				 		</div><!--End: Deebit group-->
				 		<div style="clear:both"></div>
					</div><!--End: Deebit body-->

					<div class="credit_body"><!--Start: Credit body-->
				 		<div class="credit_group"><!--Start: Credit group-->
				 			<div class="form-group col-md-6">
								<label for="form-field-6" class="">
									Credit
								</label>
								<div class="">
									<select  data-post="journal" class="credit_ledger form-control" name="credit_ledger[]" required>
										<option value="">Select</option>
										@if(isset($journal_posting_field) && count($journal_posting_field) > 0)
											@foreach($journal_posting_field as $key => $journal)
												<option data-depth="{{$journal->depth}}" value="{{$journal->ledger_id}}">{{$journal->ledger_name}}</option>
											@endforeach
										@endif						
									</select>
								</div>
								
							</div>
							<input type="hidden" name="credit_ledger_depth" id="credit_ledger_depth" value="">
							<div class="form-group col-md-5">
								<label for="Debit_naration" class="">
									Amount (Tk.)
								</label>
								<div class="">
									<span class="input-icon">
										<input placeholder=" 0.0" id="form-field-16" name="credit_transaction_amount[]" class="form-control" type="text" required>
										<i class="fa fa-money"></i> </span>
								</div>
							</div>
							<div class="col-md-1 credit_add_btn">
								<span class="btn btn-green" data-toggle="tooltip" data-placement="top" title="More Credit"><i class="fa fa-plus fa fa-white"></i></span>
							</div>

							<div class="form-group col-md-11">
								<label for="Credit_naration">
									Naration
								</label>
								<div class="">
									<span class="input-icon">
										<input placeholder="Naration" name="credit_naration[]" id="form-field-16" class="form-control" type="text">
										<i class="fa fa-hand-o-right"></i> </span>
								</div>
							</div>
				 		</div><!--End: Deebit group-->
					</div><!--End: Deebit body-->
					<div class="form-group">
						<label>Transaction Detail</label>
						<textarea name="transaction_details" class="form-control" cols="5" rows="5" placeholder="Naration.." required></textarea>
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
			</div>
		</div>
	</div>
	<div class="col-md-4">
		@include('layout.postingtype-menu')
	</div>
</div>
@stop