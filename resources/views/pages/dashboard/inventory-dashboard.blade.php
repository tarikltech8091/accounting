@extends('layout.master')
@section('content')
<!--error message*******************************************-->
<div class="row">
	<div class="col-md-12">
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
				Editable Table
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"> </a>
					<a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal"> <i class="fa fa-wrench"></i> </a>
					<a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
				<!--<div class="col-md-12 space20">
						<button class="btn btn-green add-row">
							Add New <i class="fa fa-plus"></i>
						</button>
					</div> -->
					<div class="col-md-12">
						<a class="btn btn-success" href="#panel-title" data-toggle="modal"><i class="fa fa-plus"></i> Category</a>
				
						<a class="btn btn-success" href="#panel-item" data-toggle="modal"><i class="fa fa-plus"></i> Item</a>
					</div>
				</div>


				<div class="table-responsive">
					<table class="table table-striped table-hover" id="sample_2">
						<thead>
							<tr>
								<th>Category</th>
								<th>Item Details</th>	
								<th>Item Quantity</th>
								<th>Total Quantity</th>
								<th>Cost Center</th>
								<th>Edit</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
							<tr class="odd">
							<form method="post" action="{{url('/inventory/stock')}}">
<!-- 
								<td>
									<div class="row">
										<div class="multi_ssc_subject" >
										</div>
										<div class="col-md-12" style="margin-top:5px">
											<button type="button" class="btn btn-primary btn-sm add_ssc_subject" style="float:left"><i class="fa fa-plus" aria-hidden="true"></i> Add Subject</button>
											<input type="hidden" class="site_url" value="{{url('/')}}">
										</div>
										<input type="hidden" name="multi_ssc_subject_count" class="multi_ssc_subject_count" value="5" >
									</div>

								</td> -->

								<td class="">

									<?php
										$category_list=\DB::table('ltech_item_categories')->get();
									?>
									<select class="form-control" name="item_category_id">
										<option>Select Category</option>
										@if(!empty($category_list) && count($category_list)>0)
										@foreach($category_list as $key => $list)
											<option value="{{$list->item_category_id}}">{{$list->	item_category_name}}</option>
										@endforeach
										@endif
									</select>
								</td>
								<td class=" sorting_1">
									<?php
										$item_list=\DB::table('ltech_items_details')->get();
									?>
									<select class="form-control" name="item_details_id">
										<option>Select Category</option>
										@if(!empty($item_list) && count($item_list)>0)
										@foreach($item_list as $key => $list)
											<option value="{{$list->item_id}}">{{$list->item_name}}</option>
										@endforeach
										@endif
									</select>
								</td>
								<td class="">
									<input type="text" class="form-control" name="item_category_name" value="" disabled="">
								</td>

								<td class="">
									<input type="text" class="form-control" name="stocks_total_quantity" value="">
								</td>
								<td class="">
									<?php
										$cost_list=\DB::table('ltech_cost_centers')->get();
									?>
									<select class="form-control" name="cost_center_id">
										<option>Select Category</option>
										@if(!empty($cost_list) && count($cost_list)>0)
										@foreach($cost_list as $key => $list)
											<option value="{{$list->cost_center_id}}">{{$list->cost_center_name}}</option>
										@endforeach
										@endif
									</select>
								</td>
								<td class="">
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<input type="submit" name="submit" value="Submit">
								</td>
								<td class="">
								<a class="cancel-row" href="">Cancel</a>
								</td>
							</form>
							</tr>

								@if(!empty($stock_inventory_list) && count($stock_inventory_list)>0)
								@foreach($stock_inventory_list as $key => $list)
								<tr>
									<td>{{$list->item_category_name}}</td>
									<td>{{$list->item_name}}</td>
									<td>{{$list->item_quantity_unit}}</td>
									<td>{{$list->stocks_total_quantity}}</td>
									<td>{{$list->cost_center_name}}</td>
									<td><a href="#" class="edit-row"> Edit </a></td>
									<td><a href="#" class="delete-row"> Delete </a></td>
								</tr>									
								@endforeach
								@endif

						</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>
</div>



<div class="modal fade" id="panel-title" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">Category</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
					<form method="post" action="{{url('/category/insert')}}" role="form" class="form-horizontal">
						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Category Name
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="item_category_name" value="">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<input type="submit" class="btn btn-success pull-right" name="submit" value="Submit">
							</div>
						</div>
					</form>

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					Close
				</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="panel-item" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">Item</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
					<form method="post" action="{{url('/item-details/insert')}}" role="form" class="form-horizontal">
						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Item Name
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="item_name" value="">
							</div>
						</div>

						<?php
							$category_list=\DB::table('ltech_item_categories')->get();
						?>
						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Category
							</label>
							<div class="col-md-9">
								<select name="item_category_id" class="form-control">
									<option>Select Category</option>
								@if(!empty($category_list) && count($category_list)>0)
								@foreach($category_list as $key => $list)
									<option value="{{$list->item_category_id}}">{{$list->	item_category_name}}</option>
								@endforeach
								@endif
								</select>
							</div>
						</div>


						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Quantity Unit
							</label>
							<div class="col-md-9">
								<select name="item_quantity_unit" class="form-control">
									<option>Select Quantity Unit</option>
									<option value="piece">Piece</option>
									<option value="kg">KG</option>
								</select>
							</div>
						</div>


						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Description
							</label>
							<div class="col-md-9">
								<textarea name="item_description" class="form-control" cols="20" rows="6">
								</textarea>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<input type="submit" class="btn btn-success pull-right" name="submit" value="Submit">
							</div>
						</div>
					</form>

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					Close
				</button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="panel-QtyType" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">Category</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
					<form method="post" action="{{url('/category/insert')}}" role="form" class="form-horizontal">
						<div class="form-group">
							<label for="Debit_naration" class="col-md-3">
								Category Name
							</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="item_category_name" value="">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<input type="submit" class="btn btn-success pull-right" name="submit" value="Submit">
							</div>
						</div>
					</form>

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					Close
				</button>
			</div>
		</div>
	</div>
</div>


@stop