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
        <strong> Trail Balance Report </strong>
        <a href="{{url('/trail/balance/report/pdf/from-'.(isset($search_from)? $search_from : '').'/to-'.(isset($search_to)? $search_to :'').'/ccid-'.(isset($cost_center_id)? $cost_center_id : 0))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="Trail Balnce PDF Download" style="margin-left:10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>

        <a target="_blank" href="{{url('/trail/balance/report/print/from-'.(isset($search_from)? $search_from : '').'/to-'.(isset($search_to)? $search_to :'').'/ccid-'.(isset($cost_center_id)? $cost_center_id : 0))}}" class="btn btn-xs btn-green tooltips pull-right" data-toggle1="tooltip" title="Trail Balance PDF Print" ><i class="fa fa-print" aria-hidden="true"></i></a>
      </div>


      <div class="panel-body">
        <div class="row">
          <form method="get" action="{{url('/trail/balance/report')}}">
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
          $cost_centers=\DB::table('ltech_cost_centers')->get();
          ?>


          	<div class="col-md-2">
				<label><strong>Cost Center</strong></label>
				<div class="input-group">
					<select class="form-control" name="cost_center_id" >
						<option value="0">Select Cost Center</option>
						@if(isset($cost_centers) && (count($cost_centers)>0))
							@foreach($cost_centers as $key => $center)
							<option {{(isset($_GET['cost_center_id']) && !empty($_GET['cost_center_id']) && $_GET['cost_center_id']==$center->cost_center_id) ? 'selected':'' }} value="{{$center->cost_center_id}}">{{$center->cost_center_name}}</option>
							@endforeach
						@endif
					</select>
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
        <?php
            $assets_balance=0;
            $liabilities_balance=0;
            $direct_incomes_balance=0;
            $indirect_incomes_balance=0;
            $direct_expenses_balance=0;
            $indirect_expenses_balance=0;
        ?>
          <table class="table table-hover table-bordered table-striped nopadding">
            <thead>
                  <tr>
                      <th class="text-center">Perticuler Name</th>
                      <th class="text-center">Debit </th>
                      <th class="text-center">Credit </th>
                  </tr>
            </thead>
            <tbody>


            
               @if(isset($all_assets) && count($all_assets)>0)
               @foreach($all_assets as $key => $list)

                <tr>
                  <td class="text-center">{{$list['particular_name']}}</td>
                  <?php
                  $assets_balance=$assets_balance+$list['paritcular_total'];
                  $all_assets_total=\App\Report::MakePositiveData($list['paritcular_total'])
                  ?>
                  <td class="text-center">{{isset($all_assets_total)? $all_assets_total : '0'}}</td>
                  <td class="text-center"></td>
                </tr>
                @endforeach
                @endif

              @if(isset($all_liabilities_and_capital) && count($all_liabilities_and_capital)>0)
                @foreach($all_liabilities_and_capital as $key => $list)
                <tr>
                  <td class="text-center">{{$list['particular_name']}}</td>
                  <?php
                    $liabilities_balance=$liabilities_balance+$list['paritcular_total'];
                    $all_liabilities_total=\App\Report::MakePositiveData($list['paritcular_total'])
                  ?>
                  <td class="text-center"></td>
                  <td class="text-center">{{isset($all_liabilities_total)? $all_liabilities_total : '0'}}</td>
                </tr>
                @endforeach
              @endif


              @if(isset($all_direct_incomes) && count($all_direct_incomes)>0)
                @foreach($all_direct_incomes as $key => $list)
                <tr>
                  <td class="text-center">{{$list['particular_name']}}</td>
                  <?php
                    $direct_incomes_balance=$direct_incomes_balance+$list['paritcular_total'];
                    $all_direct_incomes_total=\App\Report::MakePositiveData($list['paritcular_total'])
                  ?>
                  <td class="text-center"></td>
                  <td class="text-center">{{isset($all_direct_incomes_total)? $all_direct_incomes_total : '0'}}</td>
                </tr>
                @endforeach
              @endif


              @if(isset($all_indirect_incomes) && count($all_indirect_incomes)>0)
                @foreach($all_indirect_incomes as $key => $list)
                <tr>
                  <td class="text-center">{{$list['particular_name']}}</td>
                  <?php
                    $indirect_incomes_balance=$indirect_incomes_balance+$list['paritcular_total'];
                    $all_indirect_incomes_total=\App\Report::MakePositiveData($list['paritcular_total'])
                  ?>
                  <td class="text-center"></td>
                  <td class="text-center">{{isset($all_indirect_incomes_total)? $all_indirect_incomes_total : '0'}}</td>
                </tr>
                @endforeach
              @endif

              @if(isset($all_direct_expenses) && count($all_direct_expenses)>0)
                @foreach($all_direct_expenses as $key => $list)
                <tr>
                  <td class="text-center">{{$list['particular_name']}}</td>
                  <?php
                    $direct_expenses_balance=$direct_expenses_balance+$list['paritcular_total'];
                    $all_direct_expenses_total=\App\Report::MakePositiveData($list['paritcular_total'])
                  ?>
                  <td class="text-center">{{isset($all_direct_expenses_total)? $all_direct_expenses_total : '0'}}</td>
                  <td class="text-center"></td>
                </tr>
                @endforeach
              @endif

              @if(isset($all_indirect_expenses) && count($all_indirect_expenses)>0)
                @foreach($all_indirect_expenses as $key => $list)
                <tr>
                  <td class="text-center">{{$list['particular_name']}}</td>
                  <?php
                    $indirect_expenses_balance=$indirect_expenses_balance+$list['paritcular_total'];
                    $all_indirect_expenses_total=\App\Report::MakePositiveData($list['paritcular_total'])
                  ?>
                  <td class="text-center">{{isset($all_indirect_expenses_total)? $all_indirect_expenses_total : '0'}}</td>
                  <td class="text-center"></td>
                </tr>
                @endforeach
              @endif


                <tr>
                  	<th class="text-center">Total</th>
                    <?php
                      $grand_total_debit=0;
                      $grand_total_credit=0;
                      $grand_total_debit=(isset($assets_balance)? $assets_balance : 0) + (isset($indirect_expenses_balance)? $indirect_expenses_balance : 0) + (isset($direct_expenses_balance)? $direct_expenses_balance : 0);
                      $grand_total_credit=(isset($liabilities_balance)? $liabilities_balance : 0) + (isset($$direct_incomes_balance)? $direct_incomes_balance : 0)+ (isset($indirect_incomes_balance)? $indirect_incomes_balance : 0);
                      $grand_total_debit=\App\Report::MakePositiveData($grand_total_debit);
                      $grand_total_credit=\App\Report::MakePositiveData($grand_total_credit);

                    ?>
                	<th class="text-center">{{$grand_total_debit}}</th>
                	<th class="text-center">{{$grand_total_credit}}</th>
                </tr>

            </tbody>
          </table>

        </div>
      </div>

    </div>
  </div>
</div>
@stop