<tr>
	<td>*</td>
	<td>
		<?php
		$category_list=\DB::table('ltech_item_categories')->get();
		?>
		<select class="form-control category_list" name="item_category_id" data-id="6">
			<option value="">Select Category</option>
			@if(!empty($category_list) && count($category_list)>0)
			@foreach($category_list as $key => $clist)
			<option value="{{$clist->item_category_id}}">{{$clist->item_category_name}}</option>
			@endforeach
			@endif
		</select>
	</td>

	<td>
		<input type="text" class="form-control item_name" name="item_name">
	</td>

	<td colspan="2">
		<textarea name="item_description" class="form-control item_description" cols="20" rows="4"></textarea>
	</td>

	<td>
		<input  type="submit" class="btn btn-info pull-left" name="category_entry" value="Save" id="inventory_save">
		<a href="{{url('/inventory/item/settings')}}" class="btn btn-danger" style="margin-left:5px;"><i class="fa fa-times" aria-hidden="true"></i> Cancel</a>
	</td>

</tr>
