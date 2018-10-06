@if(!empty($item_list))
	@foreach($item_list as $key => $list)
		<option value="{{$list->item_id}}">{{$list->item_name}}</option>
	@endforeach
@else
	<option value="0">Select Item</option>
@endif
