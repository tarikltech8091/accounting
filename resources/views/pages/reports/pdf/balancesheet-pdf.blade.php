<!DOCTYPE html>
<html>
<head>
	<title><?php echo isset($page_title) ? $page_title:''; ?></title>
	<style type="text/css">
		.row div{

			display: inline-block;
		}
		.leftside{
			width: 49%;
		
		}
		.rightside{
			width: 49%;

		}
		.row{
			width: 100%;
			clear: both;
			/*height: 842px;*/
		}
		table {
		  	background-color: transparent;
		  	border-collapse: collapse;
		  	border-spacing: 0;
			color: #000000;
			direction: ltr;
			font-family: "Open+Sans",sans-serif;
			font-size: 13px;
		}
		.table {
		  margin-bottom: 20px;
		  max-width: 100%;
		  width: 100%;
		}

		table.table {
		  clear: both;
		  margin-bottom: 6px !important;
		  max-width: none !important;
		}

		.table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th{
			  background-color: #e1e1e1;
			}
		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		  vertical-align: middle;
		}

		.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
		  border-top: 1px solid #ddd;
		  line-height: 1.42857;
		  padding: 8px;
		  vertical-align: top;
		}

	</style>
</head>
<body>
<!-- <body onload="window.print();" onfocus="window.close()"> -->



<div class="row">

<div class="col-md-12" style="margin-bottom:50px;">
    <h2>{{isset($company_details->company_name)? $company_details->company_name :''}}</h2><br>
    <p>{{isset($company_details->company_address)? $company_details->company_address :''}}</p>
	<hr>
	<h2>Balance Sheet</h2>
	<p align="center">From: {{(isset($search_from)?$search_from :'')}} - To: {{(isset($search_to)?$search_to :'')}}</p>
	<p align="right"><strong> D. F Tex</strong>
		<br>
		<?php echo date("Y-m-d");?>
	</p>
	<hr>

	<div class="leftside">
		<table class="table table-striped">
			<thead>
				<tr>
					<th colspan="7">Liabilities</th>
				</tr>
			</thead>

			
			<tbody>
			<?php 
				$total_capital=0;
				$total_capital_balance=0;
				$Grand_total_capital=0;
			 ?>

			 	<tr>
					<th>Capital Account</th>
					<td align="left"></td>
					<td colspan="2" align="right"></td>
					<?php 
						$Grand_total_capital=(isset($grand_total_capital_balance)? $grand_total_capital_balance:0);
						$Grand_total_capital=\App\Report::MakePositiveData($Grand_total_capital);
					  ?>
					<th align="right">{{$Grand_total_capital}}</th>
				</tr>


				@if(!empty($capital_accounts) && count($capital_accounts)>0)
					@foreach($capital_accounts as $key => $accounts)
						<tr>
							<td colspan="2" style="padding-left:50px;">{{$accounts['ledger_name']}}</td>
							<?php 
								$total_capital_balance=$accounts['debit']-$accounts['credit'];
								$total_capital_balance=\App\Report::MakePositiveData($total_capital_balance);
							?>
							<td colspan="2" align="left">{{$total_capital_balance}}</td>
							
						</tr>
						<?php 
							$Grand_total_capital=$Grand_total_capital+$total_capital_balance;
							$Grand_total_capital=\App\Report::MakePositiveData($Grand_total_capital);
						 ?>
					@endforeach
				@endif


				
				<!--End Capital Account-->
				<!--Start Loan Account-->
				<?php
					$total_loans =0;
					$grand_total_loans_balance=0;
					$total_loans_balance=0;

				?>

				<tr>
					<th>Loans(Liability)</th>
					<td align="left"></td>
					<td colspan="2" align="right"></td>
					<?php
						$grand_total_loans_balance=(isset($grand_total_loan_liability)? $grand_total_loan_liability:0);
						$grand_total_loans_balance=\App\Report::MakePositiveData($grand_total_loans_balance);  ?>
					<th align="left">{{$grand_total_loans_balance}}</th>
				</tr>

				@if(!empty($loan_liability) && count($loan_liability)>0)
					@foreach($loan_liability as $key => $account)
						<tr>
							<td colspan="2" style="padding-left:50px;">{{isset($account['particular_name'])?$account['particular_name']:''}}</td>
							<?php 
								// $total_loans_balance=$account['debit']-$account['credit'];
								// $total_loans_balance=\App\Report::MakePositiveData($total_loans_balance);
								$total_loans_balance=\App\Report::MakePositiveData(isset($account['paritcular_total'])?$account['paritcular_total']:0);							
							?>
							<td colspan="2" align="left">{{$total_loans_balance}}</td>
							
						</tr>
					@endforeach
				@endif

				<!--End loan Account-->

				<!--Start Current Liabilities Account-->
				<?php 

					$grand_total_balance = 0;
					$total_current_liability_debit = 0;
					$total_current_liability_credit = 0;

				?>


				<tr>
					<th >Current Liabilities</th>
					<td align="left"></td>
					<td colspan="2" align="right"></td>
					<?php
					$total_current_liability=0;
					$total_current_liability=(isset($total_current_liabilities)? $total_current_liabilities :0);
					$total_current_liability=\App\Report::MakePositiveData($total_current_liability);  ?>
					<th colspan="2" align="left">{{$total_current_liability}}</th>
				</tr>
				

				@if(!empty($account_payable_total) && count($account_payable_total)>0)
						<tr>
							<td colspan="2" style="padding-left:50px;">Accounts Payable</td>
							<?php 
								$account_payable_total=\App\Report::MakePositiveData($account_payable_total);
							?>
							<td colspan="2" align="left">{{$account_payable_total}}</td>
						</tr>
				@endif

				@if(!empty($current_account_with_sister_concern_total) && count($current_account_with_sister_concern_total)>0)
						<tr>
							<td colspan="2" style="padding-left:50px;">Current Account with Sister Concern</td>
							<?php 
								$current_account_with_sister_concern_total=\App\Report::MakePositiveData($current_account_with_sister_concern_total);
							?>
							<td colspan="2" align="left">{{$current_account_with_sister_concern_total}}</td>
						</tr>
				@endif

				@if(!empty($duties_taxes_total) && count($duties_taxes_total)>0)
						<tr>
							<td colspan="2" style="padding-left:50px;">Duties & Taxes</td>
							<?php 
								$duties_taxes_total=\App\Report::MakePositiveData($duties_taxes_total);
							?>
							<td colspan="2" align="left">{{$duties_taxes_total}}</td>
						</tr>
				@endif

				@if(!empty($others_accounts_payable_total) && count($others_accounts_payable_total)>0)
						<tr>
							<td colspan="2" style="padding-left:50px;">Others Accounts Payable</td>
							<?php 
								$others_accounts_payable_total=\App\Report::MakePositiveData($others_accounts_payable_total);
							?>
							<td colspan="2" align="left">{{$others_accounts_payable_total}}</td>
						</tr>
				@endif

				@if(!empty($sundry_creditors_total) && count($sundry_creditors_total)>0)
						<tr>
							<td colspan="2" style="padding-left:50px;">Sundry Creditors</td>
							<?php 
								$sundry_creditors_total=\App\Report::MakePositiveData($sundry_creditors_total);
							?>
							<td colspan="2" align="left">{{$sundry_creditors_total}}</td>
						</tr>
				@endif

				@if(!empty($provisions_for_expenses_total) && count($provisions_for_expenses_total)>0)
						<tr>
							<td colspan="2" style="padding-left:50px;">Provisions for Expenses</td>
							<?php 
								$provisions_for_expenses_total=\App\Report::MakePositiveData($provisions_for_expenses_total);
							?>
							<td colspan="2" align="left">{{$provisions_for_expenses_total}}</td>
						</tr>
				@endif
				
				<!-- End Current Liabilities Account -->

				<tr>
					<th>Profit & Loss A/C</th>
					<td align="left"></td>
					<td colspan="2" align="right"></td>
					<?php
						$total_profit=0;
						$total_profit=(isset($total_profit_and_loss)? $total_profit_and_loss :0);
						$total_profit=\App\Report::MakePositiveData($total_profit);

					?>
					<th align="left">{{isset($total_profit)? $total_profit :0.00}}</th>
				</tr>

					<?php
						$opening_balance=0;
						$current_profits=0;
						$opening_balance=\App\Report::MakePositiveData($opening_balance);
						$current_profits=\App\Report::MakePositiveData($current_profit);
					?>
				
				<tr>
					<td colspan="2" style="padding-left:50px;">Opening Balance</td>
					<td colspan="2" align="left">{{isset($opening_balance)? $opening_balance :0.00}}</td>
				</tr>
				<tr>
					<td colspan="2" style="padding-left:50px;">Current Period</td>
					<td colspan="2" align="left">{{isset($current_profits)? $current_profits :0.00}}</td>
				</tr>
				<tr>
				<?php 
					$total_liabilities_balance=0;
					$total_liabilities_balance=(isset($grand_total_current_liabilities)?$grand_total_current_liabilities:0);

					$total_liabilities_balance=\App\Report::MakePositiveData($total_liabilities_balance);
				 ?>
					<th><strong>Total</strong></th>
					<td align="left"></td>
					<td colspan="2" align="right"></td>
					<th align="left">{{$total_liabilities_balance}}</th>
				</tr>
			</tbody>
		</table>
	</div>


	<div class="rightside">
		
	<table class="table table-striped">

		<thead>
			<tr>
				<th colspan="5">Assets</th>
			</tr>
		</thead>
		<tbody>
				

				<!--Start Fixed Assets Account-->
				<?php 
					$fixed_assets_balance = 0;
					$grand_total_fixed_assets_balance = 0;
				?>


				@if(!empty($grand_total_fixed_assets) && count($grand_total_fixed_assets)>0)
				<tr>
					<th>Fixed Assets</th>
					<td align="left"></td>
					<td colspan="2" align="right"></td>
					<?php $total_fixed_assets=\App\Report::MakePositiveData($grand_total_fixed_assets);  ?>
					<th align="left">{{$total_fixed_assets}}</th>
				</tr>
				@endif

				@if(!empty($fixed_assets) && count($fixed_assets)>0)
					@foreach($fixed_assets as $key => $accounts)
							<tr>
							<td colspan="2" style="padding-left:50px;">{{isset($accounts['particular_name'])?$accounts['particular_name']:''}}</td>
							<?php 
								// $fixed_assets_balance=$accounts['debit']-$accounts['credit'];
								// $fixed_assets_balance=\App\Report::MakePositiveData($fixed_assets_balance);
								$fixed_assets_balance=\App\Report::MakePositiveData(isset($accounts['paritcular_total'])?$accounts['paritcular_total']:0);

							?>
							<td colspan="2" align="left">{{$fixed_assets_balance}}</td>
						</tr>
						<?php $grand_total_fixed_assets_balance=$grand_total_fixed_assets_balance+$fixed_assets_balance; ?>
					@endforeach
				@endif

				
				<!--End Fixed Assets Account-->

				<!--Start Investments Account-->
				<?php
					$investment_balance = 0;
					$grand_total_investment_balance = 0;
				?>
				

				<tr>
					<th>Investments</th>
					<td align="left"></td>
					<td colspan="2" align="right"></td>
					<?php
						$grand_total_investment_balance=(isset($grand_total_investment_accounts)? $grand_total_investment_accounts:0);
						$grand_total_investment_balance=\App\Report::MakePositiveData($grand_total_investment_balance);  
					?>
					<th align="left">{{$grand_total_investment_balance}}</th>
				</tr>

				@if(count($investment_accounts)>0)
					@foreach($investment_accounts as $key => $accounts)
							<tr>
							<td colspan="2" style="padding-left:50px;">{{isset($accounts['particular_name'])?$accounts['particular_name']:''}}</td>
							<?php 
								$investment_balance=\App\Report::MakePositiveData(isset($accounts['paritcular_total'])?$accounts['paritcular_total']:0);

							?>
							<td colspan="2" align="left">{{$investment_balance}}</td>
						</tr>
						<?php $grand_total_investment_balance=$grand_total_investment_balance+$investment_balance; ?>
					@endforeach
				@endif

				
				<!--End Investments Account-->

				<!--Start Current Assests Account-->



				<?php ;
					$current_assets_balance = 0;
					$grand_total_current_assets = 0;
				?>

				@if(!empty($total_current_assets) && count($total_current_assets)>0)
				<tr>
					<th >Current Assests</th>
					<td align="left"></td>
					<td colspan="2" align="right"></td>
					<?php 
						$total_current_assets=\App\Report::MakePositiveData($total_current_assets);  ?>
					<th align="left">{{$total_current_assets}}</th>
				</tr>
				@else
				<tr>
					<th >Current Assests</th>
					<td align="left"></td>
					<td colspan="2" align="right"></td>
					<th align="left">{{$total_current_assets}}</th>
				</tr>
				@endif

				@if(!empty($account_receivable_total) && count($account_receivable_total)>0)
				<tr>
				<?php 
					$account_receivable_total=\App\Report::MakePositiveData($account_receivable_total);
				?>
					<td colspan="2" style="padding-left:50px;">Account Receivable</td>
					<td colspan="2" align="left">{{$account_receivable_total}}</td>
	
				</tr>
				@endif

				@if(!empty($bank_accounts_total) && count($bank_accounts_total)>0)
				<tr>
				<?php 
					$bank_accounts_total=\App\Report::MakePositiveData($bank_accounts_total);
				?>
					<td colspan="2" style="padding-left:50px;">Bank Accounts</td>
					<td colspan="2" align="left">{{$bank_accounts_total}}</td>
	
				</tr>
				@endif

				@if(!empty($cash_in_hand_total) && count($cash_in_hand_total)>0)
				<tr>
				<?php 
					$cash_in_hand_total=\App\Report::MakePositiveData($cash_in_hand_total);
				?>
					<td colspan="2" style="padding-left:50px;">Cash In Hand</td>
					<td colspan="2" align="left">{{$cash_in_hand_total}}</td>
	
				</tr>
				@endif

				
				@if(!empty($stock_in_hand_raw_materials_total) && count($stock_in_hand_raw_materials_total)>0)
				<tr>
				<?php 
					$stock_in_hand_raw_materials_total=\App\Report::MakePositiveData($stock_in_hand_raw_materials_total);
				?>
					<td colspan="2" style="padding-left:50px;">Stock-in-hand (Raw Materials)</td>
					<td colspan="2" align="left">{{$stock_in_hand_raw_materials_total}}</td>
	
				</tr>
				@endif


				@if(!empty($stock_in_hand_finish_goods_total) && count($stock_in_hand_finish_goods_total)>0)
				<tr>
				<?php 
					$stock_in_hand_finish_goods_total=\App\Report::MakePositiveData($stock_in_hand_finish_goods_total);
				?>
					<td colspan="2" style="padding-left:50px;">Stock-in-hand (Finish Goods)</td>
					<td colspan="2" align="left">{{$stock_in_hand_finish_goods_total}}</td>
	
				</tr>
				@endif

				@if(!empty($others_receivable_total) && count($others_receivable_total)>0)
				<tr>
				<?php 
					$others_receivable_total=\App\Report::MakePositiveData($others_receivable_total);
				?>
					<td colspan="2" style="padding-left:50px;">Others Receivable</td>
					<td colspan="2" align="left">{{$others_receivable_total}}</td>
	
				</tr>
				@endif

				@if(!empty($loans_and_advances_total) && count($loans_and_advances_total)>0)
				<tr>
				<?php 
					$loans_and_advances_total=\App\Report::MakePositiveData($loans_and_advances_total);
				?>
					<td colspan="2" style="padding-left:50px;">Loans & Advances</td>
					<td colspan="2" align="left">{{$loans_and_advances_total}}</td>
	
				</tr>
				@endif
				@if(!empty($sundry_debtors_total) && count($sundry_debtors_total)>0)
				<tr>
				<?php 
					$sundry_debtors_total=\App\Report::MakePositiveData($sundry_debtors_total);
				?>
					<td colspan="2" style="padding-left:50px;">Sundry Debtors</td>
					<td colspan="2" align="left">{{$sundry_debtors_total}}</td>
	
				</tr>
				@endif


				
				<!--End Current Assests Account-->
				
				<tr>
				<?php 
					$total_assets_balance=0;
					$total_assets_balance=(isset($grand_total_assets)? $grand_total_assets:0);
					$total_assets_balance=\App\Report::MakePositiveData($total_assets_balance);

				?>
					<th><strong>Total</strong></th>
					<td align="left"></td>
					<td colspan="2" align="right"></td>
					<th align="left">{{$total_assets_balance}}</th>
				</tr>
			</tbody>
			
	</table>
	</div>
</div>
</div>
</body>
</html>