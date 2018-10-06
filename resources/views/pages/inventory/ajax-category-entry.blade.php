<tr>
	<td>*</td>
    <td><input class="form-control"  name="item_category_name" value="" required /></td>
	<td>
		<select name="item_quantity_unit" class="form-control">
			<option value="">Select Quantity Unit</option>
			<option value="piece">Piece</option>
			<option value="kg">KG</option>
		</select>
	</td>
	<td>
	<input  type="submit" class="btn btn-info pull-left" name="category_entry" value="Save">
	<a href="{{url('/inventory/category/settings')}}" class="btn btn-danger" style="margin-left:5px;"><i class="fa fa-times" aria-hidden="true"></i> Cancel</a>
    </td>
</tr>

