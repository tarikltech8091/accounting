@extends('excelsheet.layout.master-excel')
@section('content')
<!-- <div class="row invoice-logo">
		<div class="col-md-6">
			<img alt="" src="{{asset('assets/images/dfblack.png')}}">
		</div>
		<div class="col-md-6">
			
			<p><strong>D. F Tex</strong></br>13/2 West Panthpath,Dhaka 1207</p>
			
		</div>
	</div> -->
<table class="table table-hover table-bordered table-striped nopadding">
	<thead>
			<tr>
				<th> # </th>
				<th> Item </th>
				<th class="hidden-480"> Description </th>
				<th class="hidden-480"> Quantity </th>
				<th class="hidden-480"> Unit Cost </th>
				<th> Total </th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td> 1 </td>
				<td> Lorem </td>
				<td class="hidden-480"> Drem psum dolor </td>
				<td class="hidden-480"> 12 </td>
				<td class="hidden-480"> $35 </td>
				<td> $1152 </td>
			</tr>
			<tr>
				<td> 2 </td>
				<td> Ipsum </td>
				<td class="hidden-480"> Consectetuer adipiscing elit </td>
				<td class="hidden-480"> 21 </td>
				<td class="hidden-480"> $469 </td>
				<td> $6159 </td>
			</tr>
			<tr>
				<td> 3 </td>
				<td> Dolor </td>
				<td class="hidden-480"> Olor sit amet adipiscing eli </td>
				<td class="hidden-480"> 24 </td>
				<td class="hidden-480"> $144 </td>
				<td> $8270 </td>
			</tr>
			<tr>
				<td> 4 </td>
				<td> Sit </td>
				<td class="hidden-480"> Laoreet dolore magna </td>
				<td class="hidden-480"> 194 </td>
				<td class="hidden-480"> $317 </td>
				<td> $966 </td>
			</tr>
		</tbody>
</table>

@stop