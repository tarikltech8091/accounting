<tr>
	<td>*</td>
	<td>
		<input type="text" class="form-control" name="item_name" value="">
	</td>
	<?php
		$cat_list= \DB::table('ltech_item_categories')->get();
	?>
	<td>
		<select name="item_category_id" class="form-control">
			<option>Select Category</option>
			@if(!empty($cat_list) && count($cat_list)>0)
			@foreach($cat_list as $key => $list)
			<option value="{{$list->item_category_id}}">{{$list->item_category_name}}</option>
			@endforeach
			@endif
		</select>
	</td>

	<td>
		<select name="item_quantity_unit" class="form-control">
			<option>Select Quantity Unit</option>
			<option value="piece">Piece</option>
			<option value="kg">KG</option>
		</select>
	</td>
	<td>
		<textarea name="item_description" class="form-control" cols="20" rows="6"></textarea>
	</td>
	<td>
		<input  type="submit" class="btn btn-info pull-left" name="item_entry" value="Save" id="item_save">
	</td>
</tr>