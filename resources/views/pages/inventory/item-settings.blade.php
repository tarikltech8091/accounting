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
				Item Entry
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"> </a>
					<a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
				</div>
			</div>
			<div class="panel-body">
				<form method="post" action="{{url('/inventory/item/settings')}}">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="table-responsive"><!--end of Stockes table-->
						<table class="table stocks_entry table-hover table-bordered table-striped nopadding" >
							<thead>
								<tr>
									<th>#</th>	
									<th>Item Name</th>
									<th>Category</th>
									<th>Quantity</th>
									<th>Description</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody class="item_entry_body">

								@if(!empty($item_list) && count($item_list)>0)
								@foreach($item_list as $key => $list)

								<tr>
									<td>{{$key+1}}</td>
									<td>

										<input type="text" class="form-control item_name" name="item_name" value="{{isset($list->item_name)? $list->item_name:'' }}" id="item_name_{{$key}}" data-id="{{$key}}" disabled="disabled">
									</td>
									<td>

										<select name="item_category_id" class="form-control item_category_id" id="item_category_id_{{$key}}" data-id="{{$key}}" disabled="disabled">
											@if(!empty($category_list) && count($category_list)>0)
											@foreach($category_list as $key2 => $cat_list)
											<option {{($list->item_category_id == $cat_list->item_category_id)? 'selected' :''}} value="{{$cat_list->item_category_id}}">{{$cat_list->item_category_name}}</option>
											@endforeach
											@endif
										</select>
									</td>

									<td>

										<select name="item_quantity_unit" class="form-control item_quantity_unit" id="item_quantity_unit_{{$key}}" data-id="{{$key}}" disabled="disabled">
											<!-- <option>Select Quantity Unit</option> -->
											<option {{($list->item_quantity_unit == 'piece')? 'selected' :''}} value="piece">Piece</option>
											<option {{($list->item_quantity_unit == 'kg')? 'selected' :''}} value="kg">KG</option>
										</select>
									</td>
									<td>

										<textarea name="item_description" class="form-control item_description" cols="20" rows="4" id="item_description_{{$key}}" data-id="{{$key}}" disabled="disabled">{{$list->item_description}}</textarea>
									</td>
									<td>

										<a id="item_edit_{{$key}}" class="item_edit btn btn-teal tooltips" name="edit" data-toggle1="tooltip" title="Edit Data" data-original-title="Edit Data" value="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>

										<a data-id="{{$list->item_id}}" data-rid="{{$key}}" class="item_update btn btn-success tooltips hidden" data-toggle1="tooltip" title="Update Data" data-original-title="Update Data" id="item_update_{{$key}}"><i class="fa fa-check" aria-hidden="true"></i></a>

										<a class="btn btn-danger tooltips item_delete" data-toggle1="tooltip" title="Clear Data" data-original-title="Clear Data" data-iid="{{$list->item_id}}"><i class="fa fa-times" aria-hidden="true"></i></a>

									</td>
								</tr>
								@endforeach
								@else
									<tr>
										<td class="text-center" colspan="6">No Data Available</td>
									</tr>
								@endif
							</tbody>
						</table>
					</div><!--end of Item table-->

					<input type="hidden" class="item_entry_field" name="item_entry_field" value="1">


					<div class="row">
						<div class="col-md-6 form-group pull-left">
							<button class="btn btn-default add_line_item">Add New</button>
						</div>
					</div>
					{{$item_pagination? $item_pagination :''}}

				</form>
			</div>
		</div>

	</div>
</div>


@stop