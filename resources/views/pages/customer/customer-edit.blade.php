
			<div class="row">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Edit Customer: <span style="color:#5bc0de;"></span></h4>
				</div><br>
				<div class="col-md-12">
					<form action="{{url('/customer/update',$customer_edit_data->customer_id)}}" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label>Customer Name</label>
							<input type="text" name="customer_name" class="form-control" value="{{($customer_edit_data->customer_name)? ($customer_edit_data->customer_name) :''}}" />	
						</div>
						<div class="form-group">
							<label>Customer Company</label>
							<input type="text" name="customer_company" class="form-control" value="{{($customer_edit_data->customer_company)? ($customer_edit_data->customer_company) :''}}" />	
						</div>

						<div class="form-group">
							<label>Customer Mobile</label>
							<input type="text" name="customer_mobile" class="form-control" value="{{($customer_edit_data->customer_mobile)? ($customer_edit_data->customer_mobile) :''}}" />	
						</div>

						<div class="form-group">
							<label>Customer Email</label>
							<input type="text" name="customer_email" class="form-control" value="{{($customer_edit_data->customer_email)? ($customer_edit_data->customer_email) :''}}" />	
						</div>


						<div class="form-group">
							<label>Customer Tax No</label>
							<input type="text" name="customer_tax_reg_no" class="form-control" value="{{($customer_edit_data->customer_tax_reg_no)? ($customer_edit_data->customer_tax_reg_no) :''}}" />	
						</div>


						<div class="form-group">
							<label>Customer Address</label>
							<textarea name="customer_address" class="form-control" cols="20" rows="4">
								{{($customer_edit_data->customer_address)? ($customer_edit_data->customer_address) :''}}
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
