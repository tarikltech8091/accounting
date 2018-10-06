<!DOCTYPE html>
<html>
<head>
  <title></title>
  <style type="text/css">
    .row div{
      width: 595px;
      height: 742px;
      font-size: 18px;
      display: inline-block;
      align-content: center;
      margin-left: 35px;
    }

    .middleside{
      width: 595px;
      align-content: center;
      margin: 0;
      height: 642px;

    }

    table {
      background-color: transparent;
      border-collapse: collapse;
      border-spacing: 0;
      color: #000000;
      direction: ltr;
      font-family: "Open+Sans",sans-serif;
      font-size: 15px;
      height: 550px;

    }

    table.table {
      clear: both;
      margin-bottom: 6px !important;
      max-width: none !important;

    }

    .table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
      vertical-align: middle;
      border: 1px solid;

    }

    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
      border-top: 1px solid #ddd;
      line-height: 1;
      padding: 2px;
      vertical-align: top;
    }

    .common td{
      height: 10px;
      width: 110px;
    }

    .mtr td{
      height: 200px;
    }

     .tr th{
      height: 10px;
      width: 110px;
    }






  </style>
</head>
<body>
  <div class="row" align="center">

    <div class="col-md-12">

    	<?php $company_details=\DB::table('company_details')->latest()->first(); ?>
    
        <p>
        	<strong>{{isset($company_details->company_name)? $company_details->company_name :''}}</strong><br>
            <span>{{isset($company_details->company_address)? $company_details->company_address :''}}
              <br>.........................................................</span>
        </p>
        <p><strong>Journal Details</strong>
        	<!-- <br> 01-jan-2016 to 7-Dec-2016 -->
        </p>

        <div class="middleside">
          <table class="table table-striped">
            <thead>
            <!-- mtr -->
              <tr class="tr">
                <th>Date</th>
                <th>Name</th>
                <th>Description</th>
                <th>Debit</th>
                <th>credit</th>
              </tr>
            </thead>

            <tbody>

			@if(!empty($journal_details_info) && count($journal_details_info)>0)
			@foreach($journal_details_info as $key => $list)
              <tr class="common">
                <td>{{$list->journal_date}}</td>
                <td>{{$list->journal_particular_name}}</td>
                <td>{{$list->journal_particular_naration}}</td>
				@if($list->journal_particular_amount_type == 'debit')
				<td>{{$list->journal_particular_amount}}</td>
				@else
				<td>-</td>
				@endif

				@if($list->journal_particular_amount_type == 'credit')
				<td>{{$list->journal_particular_amount}}</td>
				@else
				<td>-</td>
				@endif
              </tr>
            @endforeach
            @endif





            <!-- For blank -->
            <tr class="mtr">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
			</tr>
            <!-- For blank -->



              <tr class="tr">
                <th colspan="3" align="center"><strong>Grand Total</strong></th>
                <th>{{$all_debit_amount}}</th>
                <th>{{$all_credit_amount}}</th>
              </tr>

            </tbody>

          </table>
        </div>
      </div>
    </div>
  </body>
  </html>