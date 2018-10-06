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
        <strong> Purchase Report </strong>
        <a href="{{url('/purchase/report/pdf/from-'.(isset($search_from)? $search_from : '').'/to-'.(isset($search_to)? $search_to :'').'/ccid-'.(isset($cost_center_id)? $cost_center_id :''))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="Purchase PDF Download" style="margin-left:10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>

        <a target="_blank" href="{{url('/purchase/report/print/from-'.(isset($search_from)? $search_from : '').'/to-'.(isset($search_to)? $search_to :'').'/ccid-'.(isset($cost_center_id)? $cost_center_id :''))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="Purchase PDF Print" ><i class="fa fa-print" aria-hidden="true"></i></a>
      </div>


      <div class="panel-body">
        <div class="row">
          <form method="get" action="{{url('/purchase/balance/report')}}">
          <input type="hidden" name="_token" value="{{csrf_token()}}">
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

        <div class="table-responsive cost_list posting_list">
          <table class="table table-hover table-bordered table-striped nopadding">
            <thead>
                  <tr>
                      <th colspan="2"  class="text-center">Perticuler Name</th>
                      <th class="text-center">Quantity </th>
                      <th class="text-center">Amount </th>
                  </tr>
            </thead>
            <tbody>
		        <?php
              $total_amount=0;
              $total_quantity=0;
              $grand_total_amount=0; 
              $grand_total_quantity=0; 
            ?>

               @if(isset($purchase_item_data) && count($purchase_item_data)>0)
               @foreach($purchase_item_data as $key => $list)
                <tr>
                  <td colspan="2" class="text-center">{{$list['item_name']}}</td>
                  <?php
                    $total_amount=$list['inwards_amount']-$list['return_amount'];
                    $total_quantity=$list['inwards_quantity']-$list['return_quantity'];
                    $total_amount=\App\Report::MakePositiveData($total_amount);
                    $total_quantity=\App\Report::MakePositiveData($total_quantity);

                   ?>
                  <td class="text-center">{{$total_quantity}}</td>
                  <td class="text-center">{{$total_amount}}</td>
                </tr>
                <?php
                  $grand_total_amount=$grand_total_amount+$total_amount;
                  $grand_total_quantity=$grand_total_quantity+$total_quantity;
                ?>
                @endforeach

                <tr>
                	<th colspan="2" class="text-center">Grand Total</th>
                  <th class="text-center">{{isset($grand_total_quantity)?$grand_total_quantity : 0}}</th>
                	<th class="text-center">{{isset($grand_total_amount)?$grand_total_amount : 0}}</th>
                </tr>

                @else
                <tr>
                  <td colspan="4" class="text-center"> No Data Available</td>
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