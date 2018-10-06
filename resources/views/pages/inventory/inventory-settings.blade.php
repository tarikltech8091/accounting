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
				Stocks Entry
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"> </a>
					<a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
				</div>
			</div>
			<div class="panel-body">
				<form method="post" action="{{url('/inventory/item/settings')}}">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="table-responsive">
						<table class="table table-hover table-bordered table-striped nopadding">
							<thead>
								<tr><th>#</th>
									<th>Category</th>
									<th>Item Name</th>	
									<th>Item Quantity Type</th>
									<th>Item Description</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody class="stock_entry_body">
								@if(!empty($stock_inventory_list) && count($stock_inventory_list)>0)
								@foreach($stock_inventory_list as $key => $list)
								<?php $row=$key+1; ?>
								<tr class="{{$key}}" >
										<td>{{$row}}</td>
										<td>
											<?php
											$category_list=\DB::table('ltech_item_categories')->get();
											?>
											<select  disabled="disabled" class="item_category_id form-control category_list" name="item_category_id" id="item_category_id_{{$key}}" data-id="{{$key}}">
												<option value="">Select Category</option>
												@if(!empty($category_list) && count($category_list)>0)
												@foreach($category_list as $key1 => $cat_list)
												<option {{($list->item_category_id == $cat_list->item_category_id)? 'selected':''}} value="{{$cat_list->item_category_id}}">{{$cat_list->item_category_name}}</option>
												@endforeach
												@endif
											</select>
										</td>
										<td>
											<input type="text" class="form-control item_name" name="item_name" value="{{isset($list->item_name)? $list->item_name:'' }}" id="item_name_{{$key}}" data-id="{{$key}}" disabled="disabled">
										</td>
										<td>
											<input type="text" class="form-control qty" name="item_quantity_unit" id="item_quantity_unit_{{$key}}" value="{{$list->item_quantity_unit}}" readonly="">
										</td>
										<td>
											<textarea name="item_description" class="form-control item_description" cols="20" rows="4" id="item_description_{{$key}}" data-id="{{$key}}" disabled="disabled">{{$list->item_description}}</textarea>
										</td>

										<td class="">

											<a id="edit_{{$key}}" class="edit btn btn-teal tooltips" name="edit" data-toggle1="tooltip" title="Edit Data" data-original-title="Edit Data" value="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>

											<a data-id="{{$list->inventory_stock_id}}" data-rid="{{$key}}" id="update_{{$key}}" class="stock_update btn btn-success tooltips hidden" data-toggle1="tooltip" title="Update Data" data-original-title="Update Data"><i class="fa fa-check" aria-hidden="true"></i></a>

											<a data-id="{{$list->inventory_stock_id}}" class="stock_delete btn btn-danger tooltips" data-toggle1="tooltip" title="Clear Data" data-original-title="Clear Data"><i class="fa fa-times" aria-hidden="true"></i></a>
										</td>

									</form>
								</tr>
								@endforeach
								@else
								<tr>
									<td colspan="6" class="text-center">No Data Available</td>
								</tr>
								@endif 


							</tbody>
						</table>
					</div><!--end of Category table-->

					<input type="hidden" class="stock_entry_field" name="stock_entry_field" value="1">

					<input  type="submit" class="btn btn-info pull-right hidden" name="entry" value="Save" id="inventory_save">


					<div class="row">
						<div class="col-md-6 form-group pull-left">
							<button class="btn btn-default add_line_stock">Add New</button>	
						</div>

					</div>
					{{($inventory_pagination)? $inventory_pagination:''}}
				</form>
			</div>
		</div>

	</div>
</div>


@stop