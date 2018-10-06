@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-external-link-square"></i>
				Editable Table
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"> </a>
					<a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal"> <i class="fa fa-wrench"></i> </a>
					<a class="btn btn-xs btn-link panel-refresh" href="#"> <i class="fa fa-refresh"></i> </a>
					<a class="btn btn-xs btn-link panel-expand" href="#"> <i class="fa fa-resize-full"></i> </a>
					<a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12 space20">
						<button class="btn btn-green add-row">
							Add New <i class="fa fa-plus"></i>
						</button>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="sample_5">
						<thead>
							<tr>
								<th>Full Name</th>
								<th>Role</th>
								<th>Phone</th>
								<th>Edit</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Peter Clark</td>
								<td>UI Designer</td>
								<td>(641)-734-4763</td>
								<td><a href="#" class="edit-row"> Edit </a></td>
								<td><a href="#" class="delete-row"> Delete </a></td>
							</tr>
							<tr>
								<td>Nicole Bell</td>
								<td>Content Designer</td>
								<td>(741)-034-4573</td>
								<td><a href="#" class="edit-row"> Edit </a></td>
								<td><a href="#" class="delete-row"> Delete </a></td>
							</tr>
							<tr>
								<td>Steven Thompson</td>
								<td>Visual Designer</td>
								<td>(471)-543-4073</td>
								<td><a href="#" class="edit-row"> Edit </a></td>
								<td><a href="#" class="delete-row"> Delete </a></td>
							</tr>
							<tr>
								<td>Ella Patterson</td>
								<td>Web Editor</td>
								<td>(799)-994-9999</td>
								<td><a href="#" class="edit-row"> Edit </a></td>
								<td><a href="#" class="delete-row"> Delete </a></td>
							</tr>
							<tr>
								<td>Kenneth Ross</td>
								<td>Senior Designer</td>
								<td>(111)-114-1173</td>
								<td><a href="#" class="edit-row"> Edit </a></td>
								<td><a href="#" class="delete-row"> Delete </a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>
</div>
@stop