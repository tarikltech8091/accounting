
<div class="row">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Edit Supplier: <span style="color:#5bc0de;"></span></h4>
	</div><br>
	<div class="col-md-12">
		<form action="{{url('/supplier/update',$supplier_edit_data->supplier_id)}}" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label>Supplier Name</label>
				<input type="text" name="supplier_name" class="form-control" value="{{($supplier_edit_data->supplier_name)? ($supplier_edit_data->supplier_name) :''}}" />	
			</div>
			<div class="form-group">
				<label>Supplier Company</label>
				<input type="text" name="supplier_company" class="form-control" value="{{($supplier_edit_data->supplier_company)? ($supplier_edit_data->supplier_company) :''}}" />	
			</div>

			<div class="form-group">
				<label>Supplier Mobile</label>
				<input type="text" name="supplier_mobile" class="form-control" value="{{($supplier_edit_data->supplier_mobile)? ($supplier_edit_data->supplier_mobile) :''}}" />	
			</div>

			<div class="form-group">
				<label>Supplier Email</label>
				<input type="text" name="supplier_email" class="form-control" value="{{($supplier_edit_data->supplier_email)? ($supplier_edit_data->supplier_email) :''}}" />	
			</div>


			<div class="form-group">
				<label>Supplier Tax No</label>
				<input type="text" name="supplier_tax_reg_no" class="form-control" value="{{($supplier_edit_data->supplier_tax_reg_no)? ($supplier_edit_data->supplier_tax_reg_no) :''}}" />	
			</div>


			<div class="form-group">
				<label>Supplier Address</label>
				<textarea name="supplier_address" class="form-control" cols="20" rows="4">
					{{($supplier_edit_data->supplier_address)? ($supplier_edit_data->supplier_address) :''}}
				</textarea>	
			</div>



			<div class="form-group pull-right">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn btn-primary" value="Update">
			</div>
		</form>
	</div>
</div>
