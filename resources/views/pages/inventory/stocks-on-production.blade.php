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

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-external-link-square"></i>
				Stocks On Production
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"> </a>
					<a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
				</div>
			</div>
			<div class="panel-body">
				<form method="post" action="{{url('/inventory/stocks/on-production')}}">
				<div class="row">
						<input type="hidden" name="_token" value="{{csrf_token()}}">

						<div class="col-md-3">
							<div class="form-group ">
								<select class="form-control" name="stocks_employee_id" required>
									<option value="">Choose an Employee</option>
								@if(isset($employee_list) && (count($employee_list)>0))
								@foreach($employee_list as $key => $employee)
									<option value="{{$employee->user_id}}">{{$employee->name}}</option>
								@endforeach
								@endif
								</select>
							</div>
						</div>

						<div class="col-md-2">
							<a class="btn btn-success" href="#panel-employee" data-toggle="modal"><i class="fa fa-plus"></i>New Employee</a>
						</div>

						<div class="col-md-4 pull-right">
							<label class="col-md-4 text-right btn btn-default active"><strong> Entry Date <i class="fa fa-arrow-right" aria-hidden="true"></i></strong></label>
							<div class="input-group">
								<input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="production_stocks_entry_date" data-link-field="form_dtp_input" value="<?php echo date('Y-m-d');?>">
								<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
							
							</div>
						</div>
				</div>
				
				<div class="row">
					<div class="col-md-3">
						<div class="form-group ">
							<select class="form-control" name="cost_center_id" required>
								<option value="">Choose a Cost Center</option>
								@if(isset($cost_centers) && (count($cost_centers)>0))
									@foreach($cost_centers as $key => $center)
									<option value="{{$center->cost_center_id}}">{{$center->cost_center_name}}</option>
									@endforeach
								@endif
							</select>
						</div>
					</div>
				</div>
				
				<div class="table-responsive"><!--end of Stockes table-->
					<table class="table stocks_entry table-hover table-bordered table-striped nopadding" >
						<thead>
							<tr>
								<th>#</th>	
								<th>Product</th>
								<th>Stocks on hand</th>
								<th>Quantity</th>
								<th>Description</th>
							</tr>
						</thead>
						<tbody class="production_stocks_entry_body">
							
							@for($i=1;$i<=1;$i++)
								<tr class="production_stocks_entry_group_{{$i}}">
									<td>{{$i}}</td>
									<td class="production_inventory_stocks_td" data-rowid="{{$i}}">
										<select data-rowid="{{$i}}" class="form-control production_inventory_stocks production_inventory_stocks_row_{{$i}}" name="production_inventory_stocks_id_{{$i}}" required>
											<option value="0">Choose a product</option>
											@if(isset($inventory_stocks_list) && (count($inventory_stocks_list) > 0))
												@foreach($inventory_stocks_list as $key => $stocks)
												<option value="{{$stocks->inventory_stock_id}}">{{$stocks->item_name}}</option>
												@endforeach
											@endif
										</select>
									</td>

									<td><input data-rowid="{{$i}}" type="text" class="form-control production_stocks_onhand production_stocks_onhand_row_{{$i}}" name="production_stocks_onhand_{{$i}}" value="" disabled="" /> </td>

									<td><input data-rowid="{{$i}}" type="text" class="form-control production_transaction_stocks_quantity production_transaction_stocks_quantity_row_{{$i}}" name="production_transaction_stocks_quantity_{{$i}}" value="" required /> </td>

									<td><input data-rowid="{{$i}}" class="form-control production_stocks_transaction_desc production_stocks_transaction_desc_row_{{$i}}"  name="production_stocks_transaction_desc_{{$i}}" required></td>

								</tr>

							@endfor
						</tbody>
					</table>
					<input type="hidden" class="production_stocks_entry_field" name="production_stocks_entry_field" value="1">
				</div><!--end of Stockes table-->
				<div class="row">
					<div class="col-md-12 form-group">
						<button class="btn btn-default production_add_line_stocks">Add line</button>
						
					</div>
					<div class="col-md-12 form-group">	
						<input  type="submit" class="btn btn-info pull-right" name="production_stocks_entry" value="Save">
					</div>
					
				</div>
				</form>
			</div>
		</div>

	</div>
</div>



<!--Start: Supplier Modal-->
<div class="modal fade" id="panel-employee" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">Employee Registration</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
					<form method="post" action="{{url('/dashboard/employee/registration')}}" role="form" class="form-horizontal" enctype="">
						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Employee Name
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="name" value="" required>
							</div>
						</div>

						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Employee Email
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="email" value="">
							</div>
						</div>

						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Employee Mobile
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="user_mobile" value="" required>
							</div>
						</div>
<!-- 
						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Email
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="supplier_email" value="" required>
							</div>
						</div> -->

						<input type="hidden" name="back_page" value="{{\Request::fullUrl()}}">

						<div class="form-group">
							<div class="col-md-12">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<button type="button" class="btn btn-default" data-dismiss="modal">
									Close
								</button>
								<input type="submit" class="btn btn-success pull-right" name="submit" value="Submit">
							</div>
						</div>
					</form>

					</div>
				</div>
				<!-- <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">
						Close
					</button>
				</div> -->
			</div>
			
		</div>
	</div>
</div>



@stop