@extends('layout.master')
@section('content')
<div class="row" style="margin-bottom:10px;">
	<div class="col-md-12">
		<div class="panel panel-default">
		<?php $company_details=\DB::table('company_details')->latest()->first(); ?>
			<div class="row" align="center">
				<h2>
					{{isset($company_details->company_name)? $company_details->company_name :''}}
				</h2><br>
				{{isset($company_details->company_address)? $company_details->company_address :''}}
			</div><br>

			<div class="panel panel-heading" align="center">
				<strong> All Order Report </strong>
				<a target="_blank" href="{{url('/customer/order-list/download/from-'.(isset($search_from)? $search_from :'').'/to-'.(isset($search_to)? $search_to :'').'/cost-'.(isset($cost_center)? $cost_center :'').'/customer-'.(isset($customer)? $customer :''))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="PDF Download" style="margin-left:10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
				<a target="_blank" href="{{url('/customer/order-list/print/from-'.(isset($search_from)? $search_from :'').'/to-'.(isset($search_to)? $search_to :'').'/cost-'.(isset($cost_center)? $cost_center :'').'/customer-'.(isset($customer)? $customer :''))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="PDF Print"><i class="fa fa-print" aria-hidden="true"></i></a>
			</div>


			<div class="panel-body">

				<div class="row">
			        <form method="get" action="{{url('/customer/all/order-list')}}">
			        <input type="hidden" name="_token" value="{{csrf_token()}}">
			          	<div class="col-md-3">
			            <div class="form-group ">
			              <label for="form-field-23">
			                From<span class="symbol required"></span>
			              </label>
			              <div class="input-group">
			                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="search_from" value="{{(isset($_GET['search_from']) ? $_GET['search_from'] : date("Y-m-d"))}}" placeholder="">
			                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
			              </div>
			            </div>
			          </div>
			          <div class="col-md-3">
			            <div class="form-group ">
			              <label for="form-field-23">
			                To<span class="symbol required"></span>
			              </label>
			              <div class="input-group">
			                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="search_to" value="{{(isset($_GET['search_to']) ? $_GET['search_to'] : date("Y-m-d"))}}">
			                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
			              </div>
			            </div>
			          </div>

			          <?php
			            $all_cost=\DB::table('ltech_cost_centers')->get();
			          ?>

			          <div class="col-md-2">
			            <div class="form-group">
			              <label for="form-field-23">
			                Cost Center
			              </label>
			              <div class="input-group">
			                <select name="cost_center" class="form-control">
			                  <option value=""> Select Cost</option>
			                  @if(!empty($all_cost) && count($all_cost)>0)
			                  @foreach ($all_cost as $key => $list){

			                  <option {{(isset($_GET['cost_center']) && ($_GET['cost_center'] == $list->cost_center_id)) ? 'selected':''}} value="{{$list->cost_center_id}}">{{$list->cost_center_name}}</option>
			            
			                  @endforeach
			                  @endif
			                  
			                </select>
			              </div>
			            </div>
			          </div>

			        <?php
			            $all_customer=\DB::table('ltech_customers')->get();
			        ?>

			          <div class="col-md-2">
			            <div class="form-group">
			              <label for="form-field-23">
			                Customer
			              </label>
			              <div class="input-group">
			                <select name="customer" class="form-control">
			                  <option value=""> Select Customer</option>
			                  @if(!empty($all_customer) && count($all_customer)>0)
			                  @foreach ($all_customer as $key => $list){

			                  <option {{(isset($_GET['customer']) && ($_GET['customer'] == $list->customer_id)) ? 'selected':''}} value="{{$list->customer_id}}">{{$list->customer_company}}</option>
			            
			                  @endforeach
			                  @endif
			                  
			                </select>
			              </div>
			            </div>
			          </div>

			          <div class="col-md-2" style="margin-top:22px;">
			            <div class="form-group">
			              <input type="submit" class="btn btn-primary" data-toggle1="tooltip" title="Search Transaction" value="View" style="width:100px;">
			            </div>
			          </div>
			        </form>
			    </div>

				<div class="table-responsive" style="height: 690px; overflow: auto; padding: 5px">
					<table class="table table-hover table-bordered table-striped nopadding">
						<thead>
				            <tr>
				                <th>SL </th>
				                <th>Order Date </th>
				                <th>Descriptipn</th>
				                <th>Delivary Date </th>
				                <th>Customer</th>
				                <th>Cost Center</th>
				                <th>Amount </th>
				                <th>Order Status </th>
				            </tr>

						</thead>
						<tbody>

						<?php
						$total_amount=0;
						?>
						@if(!empty($all_order_list) && count($all_order_list)>0)
						@foreach ($all_order_list as $key => $list)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$list->order_date}}</td>
								<td><a href="{{url('/customer/order-details-list/'.$list->order_id)}}"><?php echo substr($list->order_description, 0, 15);?>...</a></td>
								<td>{{$list->order_delivery_date}}</td>
								<td>{{$list->customer_company}}</td>
								<td>{{$list->cost_center_name}}</td>
								<td>{{$list->order_net_amount}}</td>
								<td>
								@if($list->order_status == 0)
								<button class="btn btn-primary" style="width:90px;">Order</button>
								@elseif($list->order_status == 1)
								<button class="btn btn-success" style="width:90px;">Process</button>
								@elseif($list->order_status == 2)
								<button class="btn btn-teal" style="width:90px;">Delivered</button>
								@endif
								</td>
							</tr>
							<?php
								$total_amount=$total_amount+$list->order_net_amount;
							?>
						@endforeach
							<tr>
								<th colspan="6" class="text-center"> Grand Total </th>
								<th>{{isset($total_amount)? $total_amount:''}}</th>
							</tr>
						@else
							<tr>
								<td colspan="9" class="text-center">No data Available</td>
							</tr>
						@endif


						</tbody>
					</table>

				</div>
			</div>

		</div>
	</div>
</div>
@stop