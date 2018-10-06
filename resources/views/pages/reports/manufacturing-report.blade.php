@extends('layout.master')
@section('content')
<div class="row" style="margin-bottom:10px;">
  <div class="col-md-12">

      <div class="row">
        <?php $company_details=\DB::table('company_details')->latest()->first(); ?>

        <div class="col-md-6">
          <h2>
            {{isset($company_details->company_name)? $company_details->company_name :''}}
          </h2><br>
            {{isset($company_details->company_address)? $company_details->company_address :''}}
        </div>

        <div class="col-md-6 text-right">

          <a href="{{url('/manufacturing/report/pdf/from-'.(isset($search_from)? $search_from : '').'/to-'.(isset($search_to)? $search_to : '').'/ccid-'.(isset($cost_center)? $cost_center : 0))}}" class="btn btn-green" style="margin-left:10px;">Download<i class="fa fa-download"></i></a>

          <a href="{{url('/manufacturing/report/print/from-'.(isset($search_from)? $search_from : '').'/to-'.(isset($search_to)? $search_to : '').'/ccid-'.(isset($cost_center)? $cost_center : 0))}}" class="btn btn-purple tooltips pull-right" data-toggle1="tooltip" title="Manufacturaing Report Print" style="margin-left:10px;">Print<i class="fa fa-print" aria-hidden="true"></i></a>
          
        </div>
      </div><br>
    <div class="panel panel-default"><br>
      <div class="panel panel-heading" align="center">
        <strong> Manufacturing Report </strong>
      </div>

      <div class="panel-body">
        <div class="row">
          <form method="get" action="{{url('/manufacturing/report')}}">
          <!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->
          <div class="col-md-4">
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
          <div class="col-md-4">
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
                  <option value="0"> Select Cost</option>
                  @if(!empty($all_cost) && count($all_cost)>0)
                  @foreach ($all_cost as $key => $list){

                  <option {{(isset($_GET['cost_center']) && ($_GET['cost_center'] == $list->cost_center_id)) ? 'selected':''}} value="{{$list->cost_center_id}}">{{$list->cost_center_name}}</option>
            
                  @endforeach
                  @endif
                  
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-2" style="margin-top:22px;">
            <div class="form-group">
              <input type="submit" class="btn btn-primary" data-toggle1="tooltip" title="Search Transaction" value="View">
            </div>
          </div>
        </form>
        </div>

        <div class="table-responsive">
          <table class="table table-hover table-bordered table-striped nopadding">
            <thead>
                  <caption>The Format Of a Manufaturing Account</caption><br>
            </thead>
            <tbody>
            	<?php $total_balance1=0; ?>


                @if(isset($all_opening_amount) && count($all_opening_amount)>0)
			                <tr>
			                  <td class="text-left">Opening Stock Of Raw Materials</td>
			                  <td class="text-center">{{$all_opening_amount}}</td>
			                  <td></td>
			                </tr>
            	<?php $total_balance1=$total_balance1+$all_opening_amount; ?>

                @endif

                @if(isset($total_amount_of_raw_materials_purchase_data) && count($total_amount_of_raw_materials_purchase_data)>0)
		                <tr>
		                  <td class="text-left">Add Purchase Of raw materials</td>
		                  <td class="text-center">{{$total_amount_of_raw_materials_purchase_data}}</td>
		                  <td></td>
		                </tr>
            	   <?php 
                    $total_balance1=$total_balance1+$total_amount_of_raw_materials_purchase_data;
                 ?>

                @endif


            	@if(isset($total_carriage_data_info) && count($total_carriage_data_info)>0)
	               	@foreach($total_carriage_data_info as $key => $list)
			                <tr>
			                  <td class="text-left">{{$list['ledger_name']}}</td>
			                  <td class="text-center">{{$list['debit']}}</td>
			                  <td></td>
			                </tr>
            	<?php $total_balance1=$total_balance1+$list['debit']; ?>

	                @endforeach
                @endif

                <tr>
                	<td></td>
                	<th class="text-center">{{$total_balance1}}</th>
                	<td class="text-center"></td>
                </tr>




                @if(isset($total_stock_outwards_amount) && count($total_stock_outwards_amount)>0)
		                <tr>
		                  <td class="text-left">Purchase Return Of Raw materials</td>
		                  <td class="text-center">{{$total_stock_outwards_amount}}</td>
		                  <td></td>
		                </tr>
            	   <?php $total_balance1=$total_balance1-$total_stock_outwards_amount; ?>

                @endif


                @if(isset($all_closing_amount) && count($all_closing_amount)>0)
                      <tr>
                        <td class="text-left">Closing Stocks Of Raw Materials</td>
                        <td class="text-center">{{$all_closing_amount}}</td>
                        <td></td>
                      </tr>
                     <?php $total_balance1=$total_balance1-$all_closing_amount; ?>

                @endif

                <tr>
                	<td class="text-left" style="padding-left:30px;">Cost Of Direct Materials</td>
                	<td></td>
                	<td class="text-center">{{$total_balance1}}</td>
                </tr>



                @if(isset($total_direct_labor_data) && count($total_direct_labor_data)>0)
                      <tr>
                        <td class="text-left" style="padding-left:30px;">Add Direct Labor</td>
                        <td class="text-center"></td>
                        <td class="text-center">{{$total_direct_labor_data}}</td>
                      </tr>
                     <?php $total_balance1=$total_balance1+$total_direct_labor_data; ?>

                @endif
                @if(isset($total_others_expences_data_info) && count($total_others_expences_data_info)>0)
                      <tr>
                        <td class="text-left" style="padding-left:30px;">Add Other Direct Expenses</td>
                        <td class="text-center"></td>
                        <td class="text-center">{{$total_others_expences_data_info}}</td>
                      </tr>
                     <?php $total_balance1=$total_balance1+$total_others_expences_data_info; ?>

                @endif




                <tr>
                	<th class="text-left" style="padding-left:30px;">Prime Cost</th>
                	<td></td>
                	<th class="text-center">{{$total_balance1}}</th>
                </tr>

                <tr>
                	<th colspan="3" class="text-left">Factory Overhead Expenses</th>
                </tr>



            <?php 
            	$balance=0;
            	$total_balance=0;
             ?>
                @if(isset($alll_overhead_info) && count($alll_overhead_info)>0)
	               	@foreach($alll_overhead_info as $key => $list)
	               		@if(count($list)>0)
			               	@foreach($list as $key => $value)
			                <tr>
			                  <td class="text-left" style="padding-left:30px;">{{$value['journal_particular_name']}}</td>
			                  	<?php 
			                  		if(($value['debit']-$value['credit']) < 0){
				                  		$balance = (-1)*($value['debit']-$value['credit']);
				                  	}else{
				                  		$balance=($value['debit']-$value['credit']);
				                  	}
				                ?>
			                  <td class="text-center">{{$balance}}</td>
			                  <td></td>
			                </tr>
            				<?php $total_balance=$total_balance+$balance; ?>
			                @endforeach
		                @endif
	                @endforeach
                @endif

                <tr>
                	<th class="text-left">Total Factory Overhead Expenses</th>
                	<td></td>
                	<th class="text-center">{{(isset($total_balance)? $total_balance : 0)}}</th>
                </tr>

                <tr>
                	<th class="text-left"> Cost Of Production </th>
                	<td></td>
                	<th class="text-center">{{$total_balance1+$total_balance}}</th>
                </tr>



            </tbody>
          </table>

        </div>
      </div>

    </div>
  </div>
</div>
@stop