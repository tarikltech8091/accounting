<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*******************************
#
## Report Controller
#
*******************************/

class ReportController extends Controller
{
    public function __construct(){
       
        $this->page_title = \Request::route()->getName();
        $description = \Request::route()->getAction();
        $this->page_desc = isset($description['desc']) ?  $description['desc']:'';
        \App\System::AccessLogWrite();
    }



    /********************************************
    ## ReportBalanceSheetPage
    *********************************************/
    public function ReportBalanceSheetPage(){

        if(isset($_GET['search_from'])  && isset($_GET['search_to'])  ||  isset($_GET['cost_center'])){

            $search_from = $_GET['search_from'];
            $search_to = $_GET['search_to'];
            if($_GET['cost_center'] != 0){
                $cost_center =$_GET['cost_center'];
            }else $cost_center=0;

        }else{
            $now=date('Y-m-d');
            $search_from=$now;
            $search_to=$now; 
            $cost_center=0; 
        }

        $data['search_from'] = $search_from;
        $data['search_to'] = $search_to;
        $data['cost_center'] = $cost_center;
        $grand_total_capital_balance=0;
        $total_profit_and_loss=0;

        $company_info=\DB::table('company_details')->first();
        $previous_search_from=$company_info->foundation_date;
        $previous_search_to=date('Y-m-d',strtotime('-1 day', strtotime($search_from)));

        $opening_balance = \App\Report::ProfitandLossReport($previous_search_from,$previous_search_to,$cost_center);
        $current_profit = \App\Report::ProfitandLossReport($search_from,$search_to,$cost_center);
        $data['opening_balance'] = $opening_balance;
        $data['current_profit'] = $current_profit;
        $total_profit_and_loss=(isset($opening_balance)?$opening_balance:0)+(isset($current_profit)?$current_profit:0);
        $data['total_profit_and_loss'] = $total_profit_and_loss;


        $capital_accounts_info = \App\Report::BalancesheetByDateAndCostCenter('Capital Account','2',$search_from, $search_to, $cost_center);
        $capital_accounts = \App\Report::GroupByData($capital_accounts_info);
        $grand_total_capital_balance=\App\Report::GetLedgerWithOpeningTotal('Capital Account','2',$capital_accounts_info);
        if(isset($grand_total_capital_balance)){
            $grand_total_capital_balance=$grand_total_capital_balance*(-1);
        }
        $data['grand_total_capital_balance'] = $grand_total_capital_balance;
        $data['capital_accounts'] = $capital_accounts;


        // $loan_liability_accounts = \App\Report::BalancesheetByDateAndCostCenter('Loans(Liability)','2',$search_from, $search_to, $cost_center);
        // $loan_liability = \App\Report::GroupByData($loan_liability_accounts);
        // $grand_total_loan_liability = \App\Report::GetLedgerTotal($loan_liability_accounts);

        $grand_total_loan_liability=0;
        $loan_liability = \App\Report::AllLedgerGetWithOpening('Loans(Liability)','2',$search_from, $search_to, $cost_center);
        foreach ($loan_liability as $key => $value) {
            $grand_total_loan_liability=$grand_total_loan_liability+$value['paritcular_total'];
        }
        
        if(isset($grand_total_loan_liability)){
            $grand_total_loan_liability=$grand_total_loan_liability*(-1);
        }
        $data['grand_total_loan_liability'] = $grand_total_loan_liability;
        $data['loan_liability'] = $loan_liability;


        $account_payable_info = \App\Report::GetLadgerDetailsByDateWithCost('Accounts Payable','3',$search_from, $search_to, $cost_center);
        $account_payable_total = \App\Report::GetLedgerWithOpeningTotal('Accounts Payable','3',$account_payable_info);
        if(isset($account_payable_total)){
            $account_payable_total=$account_payable_total*(-1);
        }

        $data['account_payable_total'] = $account_payable_total;

        $current_account_with_sister_concern_info = \App\Report::GetLadgerDetailsByDateWithCost('Current Account with Sister Concern','3',$search_from, $search_to, $cost_center);

        $current_account_with_sister_concern_total = \App\Report::GetLedgerWithOpeningTotal('Current Account with Sister Concern','3',$current_account_with_sister_concern_info);
            if(isset($current_account_with_sister_concern_total)){
                $current_account_with_sister_concern_total=$current_account_with_sister_concern_total*(-1);
            }

        $data['current_account_with_sister_concern_total'] = $current_account_with_sister_concern_total;

        $duties_taxes_info = \App\Report::GetLadgerDetailsByDateWithCost('Duties & Taxes','3',$search_from, $search_to, $cost_center);
        $duties_taxes_total = \App\Report::GetLedgerWithOpeningTotal('Duties & Taxes','3',$duties_taxes_info);
        if(isset($duties_taxes_total)){
            $duties_taxes_total=$duties_taxes_total*(-1);
        }
        $data['duties_taxes_total'] = $duties_taxes_total;


        $others_accounts_payable_info = \App\Report::GetLadgerDetailsByDateWithCost('Others Accounts Payable','3',$search_from, $search_to, $cost_center);
        $others_accounts_payable_total = \App\Report::GetLedgerWithOpeningTotal('Others Accounts Payable','3',$others_accounts_payable_info);
        if(isset($others_accounts_payable_total)){
            $others_accounts_payable_total=$others_accounts_payable_total*(-1);
        }
        $data['others_accounts_payable_total'] = $others_accounts_payable_total;


        $sundry_creditors_info = \App\Report::GetLadgerDetailsByDateWithCost('Sundry Creditors','3',$search_from, $search_to, $cost_center);
        $sundry_creditors_total = \App\Report::GetLedgerWithOpeningTotal('Sundry Creditors','3',$sundry_creditors_info);
        if(isset($sundry_creditors_total)){
            $sundry_creditors_total=$sundry_creditors_total*(-1);
        }
        $data['sundry_creditors_total'] = $sundry_creditors_total;

        $provisions_for_expenses_info = \App\Report::GetLadgerDetailsByDateWithCost('Provisions for Expenses','3',$search_from, $search_to, $cost_center);
        $provisions_for_expenses_total = \App\Report::GetLedgerWithOpeningTotal('Provisions for Expenses','3',$provisions_for_expenses_info);
        if(isset($provisions_for_expenses_total)){
            $provisions_for_expenses_total=$provisions_for_expenses_total*(-1);
        }
        $data['provisions_for_expenses_total'] = $provisions_for_expenses_total;


        $total_current_liabilities=0;
        $total_current_liabilities=$account_payable_total+$current_account_with_sister_concern_total+$duties_taxes_total+$others_accounts_payable_total+$sundry_creditors_total+$provisions_for_expenses_total;
        $data['total_current_liabilities'] = $total_current_liabilities;

        $grand_total_current_liabilities=0;
        $grand_total_current_liabilities=$total_current_liabilities+$grand_total_loan_liability+$grand_total_capital_balance+$total_profit_and_loss;
        $data['grand_total_current_liabilities'] = $grand_total_current_liabilities;


##################### End Liabilities ####################


###################### Start Assets ##################

        $account_receivable_info = \App\Report::GetLadgerDetailsByDateWithCost('Account Receivable','3',$search_from, $search_to, $cost_center);
        $account_receivable_total = \App\Report::GetLedgerWithOpeningTotal('Account Receivable','3',$account_receivable_info);
        $data['account_receivable_total'] = $account_receivable_total;


        $bank_accounts_info = \App\Report::GetLadgerDetailsByDateWithCost('Bank Accounts','3',$search_from, $search_to, $cost_center);
        $bank_accounts_total = \App\Report::GetLedgerWithOpeningTotal('Bank Accounts','3',$bank_accounts_info);
        $data['bank_accounts_total'] = $bank_accounts_total;

        $cash_in_hand_info = \App\Report::GetLadgerDetailsByDateWithCost('Cash-in-hand','3',$search_from, $search_to, $cost_center);
        
            $cash_in_hand_total = \App\Report::GetLedgerWithOpeningTotal('Cash-in-hand','3',$cash_in_hand_info);
            $data['cash_in_hand_total'] = $cash_in_hand_total;


        $raw_material_info = \App\Report::GetLadgerDetailsByDateWithCost('Stocks-in raw material','4',$search_from, $search_to, $cost_center);
        $stock_in_hand_raw_materials_total = \App\Report::GetLedgerWithOpeningTotal('Stocks-in raw material','4',$raw_material_info);
        $data['stock_in_hand_raw_materials_total'] = $stock_in_hand_raw_materials_total;


        $stock_in_hand_finish_goods_total = \App\Report::FinishGoodsInwardsOpeningData($search_from, $search_to, $cost_center);
        $data['stock_in_hand_finish_goods_total'] = $stock_in_hand_finish_goods_total;


        $others_receivable_info = \App\Report::GetLadgerDetailsByDateWithCost('Others Receivable','3',$search_from, $search_to, $cost_center);
        $others_receivable_total = \App\Report::GetLedgerWithOpeningTotal('Others Receivable','3',$others_receivable_info);
        $data['others_receivable_total'] = $others_receivable_total;


        $loans_and_advances_info = \App\Report::GetLadgerDetailsByDateWithCost('Loans & Advances','3',$search_from, $search_to, $cost_center);
        $loans_and_advances_total = \App\Report::GetLedgerWithOpeningTotal('Loans & Advances','3',$loans_and_advances_info);
        $data['loans_and_advances_total'] = $loans_and_advances_total;

        $sundry_debtors_info = \App\Report::GetLadgerDetailsByDateWithCost('Sundry Debtors','3',$search_from, $search_to, $cost_center);
        $sundry_debtors_total = \App\Report::GetLedgerWithOpeningTotal('Sundry Debtors','3',$sundry_debtors_info);
        $data['sundry_debtors_total'] = $sundry_debtors_total;

        $total_current_assets=0;
        $total_current_assets=$account_receivable_total+$bank_accounts_total+$cash_in_hand_total+(isset($stock_in_hand_raw_materials_total)?$stock_in_hand_raw_materials_total: 0)+(isset($stock_in_hand_finish_goods_total)?$stock_in_hand_finish_goods_total: 0)+$others_receivable_total+$loans_and_advances_total+$sundry_debtors_total;
        $data['total_current_assets'] = $total_current_assets;

    #########################################
        $grand_total_fixed_assets=0;
        $fixed_assets = \App\Report::AllLedgerGetWithOpening('Fixed Assets','2',$search_from, $search_to, $cost_center);
        foreach ($fixed_assets as $key => $value) {
            $grand_total_fixed_assets=$grand_total_fixed_assets+$value['paritcular_total'];
        }
        // $fixed_assets_accounts = \App\Report::GetLadgerDetailsByDateWithCost('Fixed Assets','2',$search_from, $search_to, $cost_center);
        // $fixed_assets = \App\Report::GroupByData($fixed_assets_accounts);
        // $grand_total_fixed_assets = \App\Report::GetLedgerTotal($fixed_assets_accounts);

        $data['fixed_assets'] = $fixed_assets;
        $data['grand_total_fixed_assets'] = $grand_total_fixed_assets;


            $grand_total_investment_accounts=0;
            $investment_accounts = \App\Report::AllLedgerGetWithOpening('Investments','2',$search_from, $search_to, $cost_center);
            foreach ($investment_accounts as $key => $value) {
                $grand_total_investment_accounts=$grand_total_investment_accounts+$value['paritcular_total'];
            }

        $data['investment_accounts'] = $investment_accounts;
        $data['grand_total_investment_accounts'] = $grand_total_investment_accounts;

        $grand_total_assets=$total_current_assets+$grand_total_fixed_assets+$grand_total_investment_accounts;
        $data['grand_total_assets'] = $grand_total_assets;
    
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.reports.balancesheet',$data);
    }

    /********************************************
    ## BalanceSheetPdf
    *********************************************/
    public function BalanceSheetPdf($search_from, $search_to, $cost_center){


        if(!empty($search_from)  &&  !empty($search_to) ||  !empty($cost_center)){

        $data['search_from'] = $search_from;
        $data['search_to'] = $search_to;

            if(!empty($cost_center)){
                $data['cost_center'] = $cost_center;
            }else{ 
                $data['cost_center'] = 0;
            }

        $data['search_from'] = $search_from;
        $data['search_to'] = $search_to;
        $data['cost_center'] = $cost_center;

        $company_info=\DB::table('company_details')->first();
        $previous_search_from=$company_info->foundation_date;
        $previous_search_to=date('Y-m-d',strtotime('-1 day', strtotime($search_from)));

                        
        $opening_balance = \App\Report::ProfitandLossReport($previous_search_from,$previous_search_to,$cost_center);
        $current_profit = \App\Report::ProfitandLossReport($search_from,$search_to,$cost_center);
        $data['opening_balance'] = $opening_balance;
        $data['current_profit'] = $current_profit;
        $total_profit_and_loss=$opening_balance+$current_profit;
        $data['total_profit_and_loss'] = $total_profit_and_loss;


        $capital_accounts_info = \App\Report::BalancesheetByDateAndCostCenter('Capital Account','2',$search_from, $search_to, $cost_center);
        $capital_accounts = \App\Report::GroupByData($capital_accounts_info);
        $grand_total_capital_balance=\App\Report::GetLedgerWithOpeningTotal('Capital Account','2',$capital_accounts_info);
        if(isset($grand_total_capital_balance)){
            $grand_total_capital_balance=$grand_total_capital_balance*(-1);
        }
        $data['grand_total_capital_balance'] = $grand_total_capital_balance;
        $data['capital_accounts'] = $capital_accounts;

        $grand_total_loan_liability=0;
        $loan_liability = \App\Report::AllLedgerGetWithOpening('Loans(Liability)','2',$search_from, $search_to, $cost_center);
        foreach ($loan_liability as $key => $value) {
            $grand_total_loan_liability=$grand_total_loan_liability+$value['paritcular_total'];
        }
        
        if(isset($grand_total_loan_liability)){
            $grand_total_loan_liability=$grand_total_loan_liability*(-1);
        }
        $data['loan_liability'] = $loan_liability;
        $data['grand_total_loan_liability'] = $grand_total_loan_liability;



        $account_payable_info = \App\Report::GetLadgerDetailsByDateWithCost('Accounts Payable','3',$search_from, $search_to, $cost_center);
        $account_payable_total = \App\Report::GetLedgerWithOpeningTotal('Accounts Payable','3',$account_payable_info);
        if(isset($account_payable_total)){
            $account_payable_total=$account_payable_total*(-1);
        }
        $data['account_payable_total'] = $account_payable_total;

        $current_account_with_sister_concern_info = \App\Report::GetLadgerDetailsByDateWithCost('Current Account with Sister Concern','3',$search_from, $search_to, $cost_center);
        $current_account_with_sister_concern_total = \App\Report::GetLedgerWithOpeningTotal('Current Account with Sister Concern','3',$current_account_with_sister_concern_info);
        if(isset($current_account_with_sister_concern_total)){
            $current_account_with_sister_concern_total=$current_account_with_sister_concern_total*(-1);
        }
        $data['current_account_with_sister_concern_total'] = $current_account_with_sister_concern_total;

        $duties_taxes_info = \App\Report::GetLadgerDetailsByDateWithCost('Duties & Taxes','3',$search_from, $search_to, $cost_center);
        $duties_taxes_total = \App\Report::GetLedgerWithOpeningTotal('Duties & Taxes','3',$duties_taxes_info);
        if(isset($duties_taxes_total)){
            $duties_taxes_total=$duties_taxes_total*(-1);
        }
        $data['duties_taxes_total'] = $duties_taxes_total;


        $others_accounts_payable_info = \App\Report::GetLadgerDetailsByDateWithCost('Others Accounts Payable','3',$search_from, $search_to, $cost_center);
        $others_accounts_payable_total = \App\Report::GetLedgerWithOpeningTotal('Others Accounts Payable','3',$others_accounts_payable_info);
        if(isset($others_accounts_payable_total)){
            $others_accounts_payable_total=$others_accounts_payable_total*(-1);
        }
        $data['others_accounts_payable_total'] = $others_accounts_payable_total;


        $sundry_creditors_info = \App\Report::GetLadgerDetailsByDateWithCost('Sundry Creditors','3',$search_from, $search_to, $cost_center);
        $sundry_creditors_total = \App\Report::GetLedgerWithOpeningTotal('Sundry Creditors','3',$sundry_creditors_info);
        if(isset($sundry_creditors_total)){
            $sundry_creditors_total=$sundry_creditors_total*(-1);
        }
        $data['sundry_creditors_total'] = $sundry_creditors_total;

        $provisions_for_expenses_info = \App\Report::GetLadgerDetailsByDateWithCost('Provisions for Expenses','3',$search_from, $search_to, $cost_center);
        $provisions_for_expenses_total = \App\Report::GetLedgerWithOpeningTotal('Provisions for Expenses','3',$provisions_for_expenses_info);
        if(isset($provisions_for_expenses_total)){
            $provisions_for_expenses_total=$provisions_for_expenses_total*(-1);
        }
        $data['provisions_for_expenses_total'] = $provisions_for_expenses_total;


        $total_current_liabilities=0;
        $total_current_liabilities=$account_payable_total+$current_account_with_sister_concern_total+$duties_taxes_total+$others_accounts_payable_total+$sundry_creditors_total+$provisions_for_expenses_total;
        $data['total_current_liabilities'] = $total_current_liabilities;

        $grand_total_current_liabilities=0;
        $grand_total_current_liabilities=$total_current_liabilities+$grand_total_loan_liability+$grand_total_capital_balance+$total_profit_and_loss;
        $data['grand_total_current_liabilities'] = $grand_total_current_liabilities;



##################### End Liabilities ####################


###################### Start Assets ##################

        $account_receivable_info = \App\Report::GetLadgerDetailsByDateWithCost('Account Receivable','3',$search_from, $search_to, $cost_center);
        $account_receivable_total = \App\Report::GetLedgerWithOpeningTotal('Account Receivable','3',$account_receivable_info);
        $data['account_receivable_total'] = $account_receivable_total;

        $bank_accounts_info = \App\Report::GetLadgerDetailsByDateWithCost('Bank Accounts','3',$search_from, $search_to, $cost_center);
        $bank_accounts_total = \App\Report::GetLedgerWithOpeningTotal('Bank Accounts','3',$bank_accounts_info);
        $data['bank_accounts_total'] = $bank_accounts_total;

        $cash_in_hand_info = \App\Report::GetLadgerDetailsByDateWithCost('Cash-in-hand','3',$search_from, $search_to, $cost_center);
        $cash_in_hand_total = \App\Report::GetLedgerWithOpeningTotal('Cash-in-hand','3',$cash_in_hand_info);
        $data['cash_in_hand_total'] = $cash_in_hand_total;




        $raw_material_info = \App\Report::GetLadgerDetailsByDateWithCost('Stocks-in raw material','4',$search_from, $search_to, $cost_center);
        $stock_in_hand_raw_materials_total = \App\Report::GetLedgerWithOpeningTotal('Stocks-in raw material','4',$raw_material_info);
        $data['stock_in_hand_raw_materials_total'] = $stock_in_hand_raw_materials_total;

        $stock_in_hand_finish_goods_total = \App\Report::FinishGoodsInwardsOpeningData($search_from, $search_to, $cost_center);
        $data['stock_in_hand_finish_goods_total'] = $stock_in_hand_finish_goods_total;


        $others_receivable_info = \App\Report::GetLadgerDetailsByDateWithCost('Others Receivable','3',$search_from, $search_to, $cost_center);
        $others_receivable_total = \App\Report::GetLedgerWithOpeningTotal('Others Receivable','3',$others_receivable_info);
        $data['others_receivable_total'] = $others_receivable_total;

        $loans_and_advances_info = \App\Report::GetLadgerDetailsByDateWithCost('Loans & Advances','3',$search_from, $search_to, $cost_center);
        $loans_and_advances_total = \App\Report::GetLedgerWithOpeningTotal('Loans & Advances','3',$loans_and_advances_info);
        $data['loans_and_advances_total'] = $loans_and_advances_total;

        $sundry_debtors_info = \App\Report::GetLadgerDetailsByDateWithCost('Sundry Debtors','3',$search_from, $search_to, $cost_center);
        $sundry_debtors_total = \App\Report::GetLedgerWithOpeningTotal('Sundry Debtors','3',$sundry_debtors_info);
        $data['sundry_debtors_total'] = $sundry_debtors_total;

        $total_current_assets=0;
        $total_current_assets=$account_receivable_total+$bank_accounts_total+$cash_in_hand_total+(isset($stock_in_hand_raw_materials_total)?$stock_in_hand_raw_materials_total: 0)+(isset($stock_in_hand_finish_goods_total)?$stock_in_hand_finish_goods_total: 0)+$others_receivable_total+$loans_and_advances_total+$sundry_debtors_total;
        $data['total_current_assets'] = $total_current_assets;


        $grand_total_fixed_assets=0;
        $fixed_assets = \App\Report::AllLedgerGetWithOpening('Fixed Assets','2',$search_from, $search_to, $cost_center);
        foreach ($fixed_assets as $key => $value) {
            $grand_total_fixed_assets=$grand_total_fixed_assets+$value['paritcular_total'];
        }
        $data['fixed_assets'] = $fixed_assets;
        $data['grand_total_fixed_assets'] = $grand_total_fixed_assets;

        $grand_total_investment_accounts=0;
            $investment_accounts = \App\Report::AllLedgerGetWithOpening('Investments','2',$search_from, $search_to, $cost_center);
            foreach ($investment_accounts as $key => $value) {
                $grand_total_investment_accounts=$grand_total_investment_accounts+$value['paritcular_total'];
            }
        $data['investment_accounts'] = $investment_accounts;
        $data['grand_total_investment_accounts'] = $grand_total_investment_accounts;


        $grand_total_assets=$total_current_assets+$grand_total_fixed_assets+$grand_total_investment_accounts;
        $data['grand_total_assets'] = $grand_total_assets;


        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        $pdf = \PDF::loadView('pages.reports.pdf.balancesheet-pdf', $data);
        $pdfname = time().'_balancesheet.pdf';
        return $pdf->download($pdfname); 
       // return \View::make('pages.reports.pdf.balancesheet-pdf', $data);

    }else return \Redirect::to('/reports/balance-sheet');

     
    }


    /********************************************
    ## BalanceSheetPrint
    *********************************************/
    public function BalanceSheetPrint($search_from,$search_to,$cost_center){

        if(!empty($search_from)  &&  !empty($search_to) ||  !empty($cost_center)){

        $data['search_from'] = $search_from;
        $data['search_to'] = $search_to;

            if(!empty($cost_center)){
                $data['cost_center'] = $cost_center;
            }else{ 
                $data['cost_center'] = 0;
            }

        $data['search_from'] = $search_from;
        $data['search_to'] = $search_to;
        $data['cost_center'] = $cost_center;

        $company_info=\DB::table('company_details')->first();
        $previous_search_from=$company_info->foundation_date;
        $previous_search_to=date('Y-m-d',strtotime('-1 day', strtotime($search_from)));

                        
        $opening_balance = \App\Report::ProfitandLossReport($previous_search_from,$previous_search_to,$cost_center);
        $current_profit = \App\Report::ProfitandLossReport($search_from,$search_to,$cost_center);
        $data['opening_balance'] = $opening_balance;
        $data['current_profit'] = $current_profit;
        $total_profit_and_loss=$opening_balance+$current_profit;
        $data['total_profit_and_loss'] = $total_profit_and_loss;


        $capital_accounts_info = \App\Report::BalancesheetByDateAndCostCenter('Capital Account','2',$search_from, $search_to, $cost_center);
        $capital_accounts = \App\Report::GroupByData($capital_accounts_info);
        $grand_total_capital_balance=\App\Report::GetLedgerWithOpeningTotal('Capital Account','2',$capital_accounts_info);
        if(isset($grand_total_capital_balance)){
            $grand_total_capital_balance=$grand_total_capital_balance*(-1);
        }
        $data['grand_total_capital_balance'] = $grand_total_capital_balance;
        $data['capital_accounts'] = $capital_accounts;

        $grand_total_loan_liability=0;
        $loan_liability = \App\Report::AllLedgerGetWithOpening('Loans(Liability)','2',$search_from, $search_to, $cost_center);
        foreach ($loan_liability as $key => $value) {
            $grand_total_loan_liability=$grand_total_loan_liability+$value['paritcular_total'];
        }
        
        if(isset($grand_total_loan_liability)){
            $grand_total_loan_liability=$grand_total_loan_liability*(-1);
        }
        $data['loan_liability'] = $loan_liability;
        $data['grand_total_loan_liability'] = $grand_total_loan_liability;



        $account_payable_info = \App\Report::GetLadgerDetailsByDateWithCost('Accounts Payable','3',$search_from, $search_to, $cost_center);
        $account_payable_total = \App\Report::GetLedgerWithOpeningTotal('Accounts Payable','3',$account_payable_info);
        if(isset($account_payable_total)){
            $account_payable_total=$account_payable_total*(-1);
        }
        $data['account_payable_total'] = $account_payable_total;

        $current_account_with_sister_concern_info = \App\Report::GetLadgerDetailsByDateWithCost('Current Account with Sister Concern','3',$search_from, $search_to, $cost_center);
        $current_account_with_sister_concern_total = \App\Report::GetLedgerWithOpeningTotal('Current Account with Sister Concern','3',$current_account_with_sister_concern_info);
        if(isset($current_account_with_sister_concern_total)){
            $current_account_with_sister_concern_total=$current_account_with_sister_concern_total*(-1);
        }
        $data['current_account_with_sister_concern_total'] = $current_account_with_sister_concern_total;

        $duties_taxes_info = \App\Report::GetLadgerDetailsByDateWithCost('Duties & Taxes','3',$search_from, $search_to, $cost_center);
        $duties_taxes_total = \App\Report::GetLedgerWithOpeningTotal('Duties & Taxes','3',$duties_taxes_info);
        if(isset($duties_taxes_total)){
            $duties_taxes_total=$duties_taxes_total*(-1);
        }
        $data['duties_taxes_total'] = $duties_taxes_total;


        $others_accounts_payable_info = \App\Report::GetLadgerDetailsByDateWithCost('Others Accounts Payable','3',$search_from, $search_to, $cost_center);
        $others_accounts_payable_total = \App\Report::GetLedgerWithOpeningTotal('Others Accounts Payable','3',$others_accounts_payable_info);
        if(isset($others_accounts_payable_total)){
            $others_accounts_payable_total=$others_accounts_payable_total*(-1);
        }
        $data['others_accounts_payable_total'] = $others_accounts_payable_total;


        $sundry_creditors_info = \App\Report::GetLadgerDetailsByDateWithCost('Sundry Creditors','3',$search_from, $search_to, $cost_center);
        $sundry_creditors_total = \App\Report::GetLedgerWithOpeningTotal('Sundry Creditors','3',$sundry_creditors_info);
        if(isset($sundry_creditors_total)){
            $sundry_creditors_total=$sundry_creditors_total*(-1);
        }
        $data['sundry_creditors_total'] = $sundry_creditors_total;

        $provisions_for_expenses_info = \App\Report::GetLadgerDetailsByDateWithCost('Provisions for Expenses','3',$search_from, $search_to, $cost_center);
        $provisions_for_expenses_total = \App\Report::GetLedgerWithOpeningTotal('Provisions for Expenses','3',$provisions_for_expenses_info);
        if(isset($provisions_for_expenses_total)){
            $provisions_for_expenses_total=$provisions_for_expenses_total*(-1);
        }
        $data['provisions_for_expenses_total'] = $provisions_for_expenses_total;


        $total_current_liabilities=0;
        $total_current_liabilities=$account_payable_total+$current_account_with_sister_concern_total+$duties_taxes_total+$others_accounts_payable_total+$sundry_creditors_total+$provisions_for_expenses_total;
        $data['total_current_liabilities'] = $total_current_liabilities;

        $grand_total_current_liabilities=0;
        $grand_total_current_liabilities=$total_current_liabilities+$grand_total_loan_liability+$grand_total_capital_balance+$total_profit_and_loss;
        $data['grand_total_current_liabilities'] = $grand_total_current_liabilities;



##################### End Liabilities ####################


###################### Start Assets ##################

        $account_receivable_info = \App\Report::GetLadgerDetailsByDateWithCost('Account Receivable','3',$search_from, $search_to, $cost_center);
        $account_receivable_total = \App\Report::GetLedgerWithOpeningTotal('Account Receivable','3',$account_receivable_info);
        $data['account_receivable_total'] = $account_receivable_total;

        $bank_accounts_info = \App\Report::GetLadgerDetailsByDateWithCost('Bank Accounts','3',$search_from, $search_to, $cost_center);
        $bank_accounts_total = \App\Report::GetLedgerWithOpeningTotal('Bank Accounts','3',$bank_accounts_info);
        $data['bank_accounts_total'] = $bank_accounts_total;

        $cash_in_hand_info = \App\Report::GetLadgerDetailsByDateWithCost('Cash-in-hand','3',$search_from, $search_to, $cost_center);
        $cash_in_hand_total = \App\Report::GetLedgerWithOpeningTotal('Cash-in-hand','3',$cash_in_hand_info);
        $data['cash_in_hand_total'] = $cash_in_hand_total;




        $raw_material_info = \App\Report::GetLadgerDetailsByDateWithCost('Stocks-in raw material','4',$search_from, $search_to, $cost_center);
        $stock_in_hand_raw_materials_total = \App\Report::GetLedgerWithOpeningTotal('Stocks-in raw material','4',$raw_material_info);
        $data['stock_in_hand_raw_materials_total'] = $stock_in_hand_raw_materials_total;

        $stock_in_hand_finish_goods_total = \App\Report::FinishGoodsInwardsOpeningData($search_from, $search_to, $cost_center);
        $data['stock_in_hand_finish_goods_total'] = $stock_in_hand_finish_goods_total;


        $others_receivable_info = \App\Report::GetLadgerDetailsByDateWithCost('Others Receivable','3',$search_from, $search_to, $cost_center);
        $others_receivable_total = \App\Report::GetLedgerWithOpeningTotal('Others Receivable','3',$others_receivable_info);
        $data['others_receivable_total'] = $others_receivable_total;

        $loans_and_advances_info = \App\Report::GetLadgerDetailsByDateWithCost('Loans & Advances','3',$search_from, $search_to, $cost_center);
        $loans_and_advances_total = \App\Report::GetLedgerWithOpeningTotal('Loans & Advances','3',$loans_and_advances_info);
        $data['loans_and_advances_total'] = $loans_and_advances_total;

        $sundry_debtors_info = \App\Report::GetLadgerDetailsByDateWithCost('Sundry Debtors','3',$search_from, $search_to, $cost_center);
        $sundry_debtors_total = \App\Report::GetLedgerWithOpeningTotal('Sundry Debtors','3',$sundry_debtors_info);
        $data['sundry_debtors_total'] = $sundry_debtors_total;

        $total_current_assets=0;
        $total_current_assets=$account_receivable_total+$bank_accounts_total+$cash_in_hand_total+(isset($stock_in_hand_raw_materials_total)?$stock_in_hand_raw_materials_total: 0)+(isset($stock_in_hand_finish_goods_total)?$stock_in_hand_finish_goods_total: 0)+$others_receivable_total+$loans_and_advances_total+$sundry_debtors_total;
        $data['total_current_assets'] = $total_current_assets;


        $grand_total_fixed_assets=0;
        $fixed_assets = \App\Report::AllLedgerGetWithOpening('Fixed Assets','2',$search_from, $search_to, $cost_center);
        foreach ($fixed_assets as $key => $value) {
            $grand_total_fixed_assets=$grand_total_fixed_assets+$value['paritcular_total'];
        }
        $data['fixed_assets'] = $fixed_assets;
        $data['grand_total_fixed_assets'] = $grand_total_fixed_assets;

        $grand_total_investment_accounts=0;
            $investment_accounts = \App\Report::AllLedgerGetWithOpening('Investments','2',$search_from, $search_to, $cost_center);
            foreach ($investment_accounts as $key => $value) {
                $grand_total_investment_accounts=$grand_total_investment_accounts+$value['paritcular_total'];
            }
        $data['investment_accounts'] = $investment_accounts;
        $data['grand_total_investment_accounts'] = $grand_total_investment_accounts;


        $grand_total_assets=$total_current_assets+$grand_total_fixed_assets+$grand_total_investment_accounts;
        $data['grand_total_assets'] = $grand_total_assets;


        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

       return \View::make('pages.reports.pdf.balancesheet-print', $data);

    }else return \Redirect::to('/reports/balance-sheet');

    }


    /********************************************
    ## ReportCahsFlowPage
    *********************************************/
    public function ReportCahsFlowPage(){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        $data['cost_centers'] = \DB::table('ltech_cost_centers')->get();
        $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();


        /*------------------------------------Get Request--------------------------------------------*/
        if(isset($_GET['from']) && isset($_GET['to']) && !empty($_GET['from']) && !empty($_GET['to'])){
               
                $from = $_GET['from'];
                $to = $_GET['to'];
                $cost_center_id = isset($_GET['cost_center_id']) && !empty($_GET['cost_center_id']) ? $_GET['cost_center_id']:0;

                $cost_center_info = \DB::table('ltech_cost_centers')->where('cost_center_id',$cost_center_id)->first();

                $cost_center = !empty($cost_center_info) ? $cost_center_info->cost_center_id :0;

                $cash_accounts = \App\Report::GetBalanceSheetLedgerByDateWithCostCenter('Cash-in-hand',3,$from,$to,$cost_center);
                $bank_accounts = \App\Report::GetBalanceSheetLedgerByDateWithCostCenter('Bank Accounts',3,$from,$to,$cost_center);


                $all_cashflow_data = \App\Report::GetCashFlow($cash_accounts,$bank_accounts);
                $data['cost_center_info'] = $cost_center_info;
                $data['all_cashflow_data'] = $all_cashflow_data;
                $data['from'] = $from;
                $data['to'] = $to;

        }else{
                $now = date('Y-m-d');
                $from = $now;
                $to = $now;

                $cost_center =0;

                $cash_accounts = \App\Report::GetBalanceSheetLedgerByDateWithCostCenter('Cash-in-hand',3,$from,$to,$cost_center);
                $bank_accounts = \App\Report::GetBalanceSheetLedgerByDateWithCostCenter('Bank Accounts',3,$from,$to,$cost_center);


                $all_cashflow_data = \App\Report::GetCashFlow($cash_accounts,$bank_accounts);
                $data['all_cashflow_data'] = $all_cashflow_data;
                $data['from'] = $from;
                $data['to'] = $to;
        }

       return \View::make('pages.reports.cash-flow',$data);
    }

    /********************************************
    ## ReportCahsFlowLedgerPage
    *********************************************/
    public function ReportCahsFlowLedgerPage(){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        $data['cost_centers'] = \DB::table('ltech_cost_centers')->get();
        $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();


        /*------------------------------------Get Request--------------------------------------------*/
        if(isset($_GET['from']) && isset($_GET['to']) && !empty($_GET['from']) && !empty($_GET['to'])){
               
                $from = $_GET['from'];
                $to = $_GET['to'];
                $cost_center_id = isset($_GET['cost_center_id']) && !empty($_GET['cost_center_id']) ? $_GET['cost_center_id']:0;

                $cost_center_info = \DB::table('ltech_cost_centers')->where('cost_center_id',$cost_center_id)->first();

                $cost_center = !empty($cost_center_info) ? $cost_center_info->cost_center_id :0;

                $cash_accounts = \App\Report::GetBalanceSheetLedgerByDateWithCostCenter('Cash-in-hand',3,$from,$to,$cost_center);
                $bank_accounts = \App\Report::GetBalanceSheetLedgerByDateWithCostCenter('Bank Accounts',3,$from,$to,$cost_center);
                $all_cashflow_data = \App\Report::GetCashFlow($cash_accounts,$bank_accounts);
                $data['cost_center_info'] = $cost_center_info;
                $data['all_cashflow_data'] = $all_cashflow_data;
                $data['from'] = $from;
                $data['to'] = $to;
                $data['cash_accounts_ledger'] = \App\Report::ArrayGroupingByKey($cash_accounts,'journal_particular_name');
                $data['bank_accounts_ledger'] = \App\Report::ArrayGroupingByKey($bank_accounts,'journal_particular_name');
               
                return \View::make('pages.reports.ledger-cash-flow',$data);


        }else return \Redirect::to('/reports/cash-flow');

    }

    /********************************************
    ## AjaxAllSummaryReport
    *********************************************/
    public function AjaxAllSummaryReport(){

        //$from = $to = date('Y-m-d');
        $from = date('Y-m-d',strtotime("-30 days"));
        $to = date('Y-m-d');
        $cost_center= isset($_GET['cost_center_id']) && !empty($_GET['cost_center_id']) ? $_GET['cost_center_id']:0 ;

        /* Cash Flow Data*/
        $cash_accounts = \App\Report::GetBalanceSheetLedgerByDateWithCostCenter('Cash-in-hand',3,$from,$to,$cost_center);
        $bank_accounts = \App\Report::GetBalanceSheetLedgerByDateWithCostCenter('Bank Accounts',3,$from,$to,$cost_center);
        $all_cashflow_data = \App\Report::GetCashFlow($cash_accounts,$bank_accounts);
        $data_inflow = [
                        'label' => 'Cash Inflow',
                        'value' => (float)(isset($all_cashflow_data['total_inflow']) ? (float) number_format($all_cashflow_data['total_inflow'],2,'.',''):0)
                    ];
        $data_outflow = [
                        'label' => 'Cash Outflow',
                        'value' => (float) (isset($all_cashflow_data['total_outflow']) ? (float) number_format($all_cashflow_data['total_outflow'],2,'.',''):0)
                    ];
        $data[] = $data_inflow;
        $data[] = $data_outflow;

        /*SaleReport*/
        $receivable_accounts = \App\Report::GetBalanceSheetLedgerByDateWithCostCenter('Account Receivable',3,$from,$to,$cost_center);

        $receivable_accounts_debit_total = \App\Report::GetLedgerTotalByType($receivable_accounts,'debit');
        $cash_accounts_debit_total = \App\Report::GetLedgerTotalByType($cash_accounts,'debit');
        $bank_accounts_debit_total = \App\Report::GetLedgerTotalByType($bank_accounts,'debit');

        $data[] = [
                        'label'=>'Sales Order',
                        'value'=> (float) number_format(($receivable_accounts_debit_total+$cash_accounts_debit_total+$bank_accounts_debit_total),2,'.','')
                    ];

        /*Purchase Report*/
        $payable_accounts = \App\Report::GetBalanceSheetLedgerByDateWithCostCenter('Accounts Payable',3,$from,$to,$cost_center);

        $payable_accounts_credit_total = \App\Report::GetLedgerTotalByType($payable_accounts,'credit');
        $cash_accounts_credit_total = \App\Report::GetLedgerTotalByType($cash_accounts,'credit');
        $bank_accounts_credit_total = \App\Report::GetLedgerTotalByType($bank_accounts,'credit');
        $data[] = [
                        'label'=>'Purchase Order',
                        'value'=> (float) number_format(($payable_accounts_credit_total+$cash_accounts_credit_total+$bank_accounts_credit_total),2,'.','')
                    ];        

        return \Response::json($data);
    }


    /********************************************
    ## AjaxLineChartReport
    *********************************************/
    public function AjaxLineChartReport(){


        $to = date('Y-m-d');
        $view_days = isset($_GET['view_days']) && !empty($_GET['view_days']) ? $_GET['view_days']:7;
        $from = date('Y-m-d',strtotime("-$view_days days"));
        $cost_center= isset($_GET['cost_center_id']) && !empty($_GET['cost_center_id']) ? $_GET['cost_center_id']:0 ;

        

        /* Cash Flow Data*/
        $cash_accounts = \App\Report::GetBalanceSheetLedgerByDateWithCostCenter('Cash-in-hand',3,$from,$to,$cost_center);
        $bank_accounts = \App\Report::GetBalanceSheetLedgerByDateWithCostCenter('Bank Accounts',3,$from,$to,$cost_center);
        
        $data= \App\Report::GetCashFlowByLedgerData($bank_accounts);
            

        $cash_accounts_filter= \App\Report::ArrayGroupingByKey($cash_accounts,'journal_date');
        $bank_accounts_filter= \App\Report::ArrayGroupingByKey($bank_accounts,'journal_date');

        //$all_cashflow_merge = array_merge($cash_accounts_filter, $bank_accounts_filter);

        /*foreach ($bank_accounts_filter as $key => $ledger_data) {
            $date = $key;
            $data= \App\Report::GetCashFlowByLedgerData($ledger_data);
            echo "<br>#####################<br>";
        }*/

        /*$data_inflow = [
                        'label' => 'Cash Inflow',
                        'value' => (float)(isset($all_cashflow_data['total_inflow']) ? (float) number_format($all_cashflow_data['total_inflow'],2,'.',''):0)
                    ];
        $data_outflow = [
                        'label' => 'Cash Outflow',
                        'value' => (float) (isset($all_cashflow_data['total_outflow']) ? (float) number_format($all_cashflow_data['total_outflow'],2,'.',''):0)
                    ];
        $data[] = $data_inflow;
        $data[] = $data_outflow;*/

        /*SaleReport*/
        /*$receivable_accounts = \App\Report::GetBalanceSheetLedgerByDateWithCostCenter('Account Receivable',3,$from,$to,$cost_center);

        $receivable_accounts_debit_total = \App\Report::GetLedgerTotalByType($receivable_accounts,'debit');
        $cash_accounts_debit_total = \App\Report::GetLedgerTotalByType($cash_accounts,'debit');
        $bank_accounts_debit_total = \App\Report::GetLedgerTotalByType($bank_accounts,'debit');

        $data[] = [
                        'label'=>'Sales Order',
                        'value'=> (float) number_format(($receivable_accounts_debit_total+$cash_accounts_debit_total+$bank_accounts_debit_total),2,'.','')
                    ];*/

        /*Purchase Report*/
        /*$payable_accounts = \App\Report::GetBalanceSheetLedgerByDateWithCostCenter('Accounts Payable',3,$from,$to,$cost_center);

        $payable_accounts_credit_total = \App\Report::GetLedgerTotalByType($payable_accounts,'credit');
        $cash_accounts_credit_total = \App\Report::GetLedgerTotalByType($cash_accounts,'credit');
        $bank_accounts_credit_total = \App\Report::GetLedgerTotalByType($bank_accounts,'credit');
        $data[] = [
                        'label'=>'Purchase Order',
                        'value'=> (float) number_format(($payable_accounts_credit_total+$cash_accounts_credit_total+$bank_accounts_credit_total),2,'.','')
                    ]; */       

       // return \Response::json($data);
    }

    /********************************************
    ## SalesReportList
    *********************************************/
    public function SalesReportList(){
        $total_receviable_debit_amount=0;
        $total_bank_debit_amount=0;
        $total_cash_debit_amount=0;
        $now=date("Y-m-d");
        $cost_center=0;

        if(isset($_GET['search_from'])  ||  isset($_GET['search_to'])  ||  isset($_GET['cost_center'])){

            $search_from = $_GET['search_from'].' 00:00:00';
            $search_to = $_GET['search_to'].' 23:59:59';
            $cost_center =$_GET['cost_center'];

            $data['search_from'] = $search_from;
            $data['search_to'] = $search_to;
            $data['cost_center'] = $cost_center;

            $account_receivable_info = \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType('Account Receivable','3', $search_from, $search_to, $cost_center,'debit');
            $bank_accounts_info= \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType('Bank Accounts','3', $search_from, $search_to, $cost_center,'debit');
            $cash_in_hand_info= \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType('Cash-in-hand','3', $search_from, $search_to, $cost_center,'debit');


            if(!empty($account_receivable_info)){
                $data['account_receivable_info'] = $account_receivable_info;
                foreach ($account_receivable_info as $key => $list) {
                    $total_receviable_debit_amount=$total_receviable_debit_amount+$list->journal_particular_amount;
                }
                $data['total_receviable_debit_amount'] = $total_receviable_debit_amount;
            }



            if(!empty($bank_accounts_info)){
                $data['bank_accounts_info'] = $bank_accounts_info;
                foreach ($bank_accounts_info as $key => $list) {
                    $total_bank_debit_amount=$total_bank_debit_amount+$list->journal_particular_amount;
                }
                $data['total_bank_debit_amount'] = $total_bank_debit_amount;
            }



            if(!empty($cash_in_hand_info)){
                $data['cash_in_hand_info'] = $cash_in_hand_info;
                foreach ($cash_in_hand_info as $key => $list) {
                    $total_cash_debit_amount=$total_cash_debit_amount+$list->journal_particular_amount;
                }
                $data['total_cash_debit_amount'] = $total_cash_debit_amount;
            }

            $data['total_debit_amount'] = $total_cash_debit_amount+$total_bank_debit_amount+$total_receviable_debit_amount;


        }
        /*----------------------------------Get Request--------------------------------*/
        else{


            $data['search_from'] = $now;
            $data['search_to'] = $now;
            $data['cost_center'] = $cost_center;

            $account_receivable_info = \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType('Account Receivable','3', $now, $now, $cost_center,'debit');
            $bank_accounts_info = \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType('Bank Accounts','3',$now, $now, $cost_center,'debit');
            $cash_in_hand_info= \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType('Cash-in-hand','3',$now, $now, $cost_center,'debit');


            if(!empty($account_receivable_info)){
                $data['account_receivable_info'] = $account_receivable_info;
                foreach ($account_receivable_info as $key => $list) {
                    $total_receviable_debit_amount=$total_receviable_debit_amount+$list->journal_particular_amount;
                }
                $data['total_receviable_debit_amount'] = $total_receviable_debit_amount;

            }


            if(!empty($bank_accounts_info)){
            $data['bank_accounts_info'] = $bank_accounts_info;
                foreach ($bank_accounts_info as $key => $list) {
                    $total_bank_debit_amount=$total_bank_debit_amount+$list->journal_particular_amount;
                }
                $data['total_bank_debit_amount'] = $total_bank_debit_amount;
            }


            if(!empty($cash_in_hand_info)){
            $data['cash_in_hand_info'] = $cash_in_hand_info;
                foreach ($cash_in_hand_info as $key => $list) {
                    $total_cash_debit_amount=$total_cash_debit_amount+$list->journal_particular_amount;
                }
                $data['total_cash_debit_amount'] = $total_cash_debit_amount;
            }


            $data['total_debit_amount'] = $total_cash_debit_amount+$total_bank_debit_amount+$total_receviable_debit_amount;

        }


        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.reports.sales-list-report',$data);

    }


    /********************************************
    ## SalesReportDetailsList
    *********************************************/
    public function SalesReportDetailsList($search_from,$search_to,$cost_center_id,$type){
        $total_amount=0;
        $details_list = \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType($type,'3', $search_from, $search_to, $cost_center_id, 'debit');

        if(!empty($details_list) && count($details_list)>0){
            foreach ($details_list as $key => $list) {
                $total_amount=$total_amount+$list->journal_particular_amount;
            }
            $data['total_amount'] = $total_amount;
        } 

        $data['type'] = $type;
        $data['cost_center_id'] = $cost_center_id;
        $data['search_from'] = $search_from;
        $data['search_to'] = $search_to;

        $data['details_list'] = $details_list;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.reports.sales-list-details-report',$data);

    }



    /********************************************
    ## SalesDetailsReportPDF
    *********************************************/
    public function SalesDetailsReportPDF($search_from,$search_to,$cost_center_id,$type){

        $total_debit_amount=0;
        $details_list = \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType($type,'3', $search_from, $search_to, $cost_center_id, 'debit');

        if(!empty($details_list) && count($details_list)>0){
            foreach ($details_list as $key => $list) {
                $total_debit_amount=$total_debit_amount+$list->journal_particular_amount;
            }
            $data['total_debit_amount'] = $total_debit_amount;
        }

        $data['type'] = $type;
        $data['details_list'] = $details_list;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;


        // return \View::make('pages.reports.pdf.sales-report-details-pdf',$data);
        $pdf = \PDF::loadView('pages.reports.pdf.sales-report-details-pdf',$data);
        $pdfname = time().'sales-report-details.pdf';
        return $pdf->download($pdfname);

    }



    /********************************************
    ## SalesDetailsReportPrint
    *********************************************/
    public function SalesDetailsReportPrint($search_from,$search_to,$cost_center_id,$type){

        $total_debit_amount=0;
        $details_list = \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType($type,'3', $search_from, $search_to, $cost_center_id, 'debit');

        if(!empty($details_list) && count($details_list)>0){
            foreach ($details_list as $key => $list) {
                $total_debit_amount=$total_debit_amount+$list->journal_particular_amount;
            }
            $data['total_debit_amount'] = $total_debit_amount;
        }

        $data['type'] = $type;
        $data['details_list'] = $details_list;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        return \View::make('pages.reports.pdf.sales-report-details-print',$data);
    }



    /********************************************
    ## PurchaseListReport
    *********************************************/
    public function PurchaseListReport(){
        $total_payable_credit_amount=0;
        $total_bank_credit_amount=0;
        $total_cash_credit_amount=0;
        $total_credit_amount=0;
        $cost_center=0;
        $now=date("Y-m-d");

        if(isset($_GET['search_from'])  ||  isset($_GET['search_to'])  ||  isset($_GET['cost_center'])){

            $search_from = $_GET['search_from'].' 00:00:00';
            $search_to = $_GET['search_to'].' 23:59:59';
            $cost_center =$_GET['cost_center'];


            $data['search_from'] = $search_from;
            $data['search_to'] = $search_to;
            $data['cost_center'] = $cost_center;


            $account_payable_info = \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType('Accounts Payable','3', $search_from, $search_to, $cost_center,'credit');
            $bank_accounts_info= \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType('Bank Accounts','3', $search_from, $search_to, $cost_center,'credit');
            $cash_in_hand_info= \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType('Cash-in-hand','3', $search_from, $search_to, $cost_center,'credit');



            if(!empty($account_payable_info)){
                $data['account_payable_info'] = $account_payable_info;
                foreach ($account_payable_info as $key => $list) {
                    $total_payable_credit_amount=$total_payable_credit_amount+$list->journal_particular_amount;
                }
                $data['total_payable_credit_amount'] = $total_payable_credit_amount;
            }


            if(!empty($bank_accounts_info)){
                $data['bank_accounts_info'] = $bank_accounts_info;
                foreach ($bank_accounts_info as $key => $list) {
                    $total_bank_credit_amount=$total_bank_credit_amount+$list->journal_particular_amount;
                }
                $data['total_bank_credit_amount'] = $total_bank_credit_amount;
            }


            if(!empty($cash_in_hand_info)){
                $data['cash_in_hand_info'] = $cash_in_hand_info;
                foreach ($cash_in_hand_info as $key => $list) {
                    $total_cash_credit_amount=$total_cash_credit_amount+$list->journal_particular_amount;
                }
                $data['total_cash_credit_amount'] = $total_cash_credit_amount;
            }

            $data['total_credit_amount'] = $total_payable_credit_amount+$total_bank_credit_amount+$total_cash_credit_amount;


        }
        /*---------------------/Get Request----------------------*/
        else{

            $data['search_from'] = $now;
            $data['search_to'] = $now;
            $data['cost_center'] = $cost_center;


            $account_payable_info = \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType('Accounts Payable','3', $now, $now, $cost_center,'credit');
            $bank_accounts_info= \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType('Bank Accounts','3', $now, $now, $cost_center,'credit');
            $cash_in_hand_info= \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType('Cash-in-hand','3', $now, $now, $cost_center,'credit');



            $data['account_payable_info'] = $account_payable_info;
            $data['bank_accounts_info'] = $bank_accounts_info;
            $data['cash_in_hand_info'] = $cash_in_hand_info;



            if(!empty($account_payable_info)){
                $data['account_payable_info'] = $account_payable_info;
                foreach ($account_payable_info as $key => $list) {
                    $total_payable_credit_amount=$total_payable_credit_amount+$list->journal_particular_amount;
                }
                $data['total_payable_credit_amount'] = $total_payable_credit_amount;
            }



            if(!empty($bank_accounts_info)){
                $data['bank_accounts_info'] = $bank_accounts_info;
                foreach ($bank_accounts_info as $key => $list) {
                    $total_bank_credit_amount=$total_bank_credit_amount+$list->journal_particular_amount;
                }
                $data['total_bank_credit_amount'] = $total_bank_credit_amount;
            }



            if(!empty($cash_in_hand_info)){
                $data['cash_in_hand_info'] = $cash_in_hand_info;
                foreach ($cash_in_hand_info as $key => $list) {
                    $total_cash_credit_amount=$total_cash_credit_amount+$list->journal_particular_amount;
                }
                $data['total_cash_credit_amount'] = $total_cash_credit_amount;
            }

            $data['total_credit_amount'] = $total_payable_credit_amount+$total_bank_credit_amount+$total_cash_credit_amount;

        }

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.reports.purchase-list-report',$data);

    }



    /********************************************
    ## PurchaseReportDetailsList
    *********************************************/
    public function PurchaseReportDetailsList($search_from,$search_to,$cost_center_id,$type){
        $total_purchase_amount=0;
        $purchase_details_list = \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType($type,'3', $search_from, $search_to, $cost_center_id, 'credit');

        if(!empty($purchase_details_list) && count($purchase_details_list)>0){
            foreach ($purchase_details_list as $key => $list) {
                $total_purchase_amount=$total_purchase_amount+$list->journal_particular_amount;
            }
            $data['total_purchase_amount'] = $total_purchase_amount;
        }

        $data['type'] = $type;
        $data['cost_center_id'] = $cost_center_id;
        $data['search_from'] = $search_from;
        $data['search_to'] = $search_to;

        $data['purchase_details_list'] = $purchase_details_list;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.reports.purchase-list-details-report',$data);

    }


    /********************************************
    ## PurchaseDetailsReportPDF
    *********************************************/
    public function PurchaseDetailsReportPDF($search_from,$search_to,$cost_center_id,$type){

        $total_purchase_amount=0;
        $purchase_details_list = \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType($type,'3', $search_from, $search_to, $cost_center_id, 'credit');

        if(!empty($purchase_details_list) && count($purchase_details_list)>0){
            foreach ($purchase_details_list as $key => $list) {
                $total_purchase_amount=$total_purchase_amount+$list->journal_particular_amount;
            }
            $data['total_purchase_amount'] = $total_purchase_amount;
        } 

        $data['type'] = $type;
        $data['purchase_details_list'] = $purchase_details_list;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        
        // return \View::make('pages.reports.pdf.purchase-report-details-pdf',$data);

        $pdf = \PDF::loadView('pages.reports.pdf.purchase-report-details-pdf',$data);
        $pdfname = time().'purchase-report-details.pdf';
        return $pdf->download($pdfname);

    }


    /********************************************
    ## PurchaseDetailsReportPrint
    *********************************************/
    public function PurchaseDetailsReportPrint($search_from,$search_to,$cost_center_id,$type){

        $total_purchase_amount=0;
        $purchase_details_list = \App\Report::GetBalanceSheetLedgerByDateWithCostCenterAndType($type,'3', $search_from, $search_to, $cost_center_id, 'credit');

        if(!empty($purchase_details_list) && count($purchase_details_list)>0){
            foreach ($purchase_details_list as $key => $list) {
                $total_purchase_amount=$total_purchase_amount+$list->journal_particular_amount;
            }
            $data['total_purchase_amount'] = $total_purchase_amount;
        } 

        $data['type'] = $type;
        $data['purchase_details_list'] = $purchase_details_list;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        
        return \View::make('pages.reports.pdf.purchase-report-details-print',$data);
    }

    /********************************************
    ## PurchasePDF
    *********************************************/
    public function PurchasePDF($search_from,$search_to,$cost_center_id){

        if(!empty($search_from) && !empty($search_to) || !empty($cost_center_id)){

            $purchase_credit_info = \App\Report::GetBalanceSheetLedgerByDateWithCost('Stocks-in raw material','4', $search_from, $search_to, $cost_center_id);

            $temp = array();

            foreach ($purchase_credit_info as $key => $value) {

                $exits = \App\Report::MultiArraySerach($value->ledger_name,'ledger_name',$temp);



                if($exits){

                    $temp[$exits]['debit'] = $value->journal_particular_amount_type=='debit' ? $temp[$exits]['debit'] + $value->journal_particular_amount: $temp[$exits]['debit'];
                    $temp[$exits]['credit'] = $value->journal_particular_amount_type=='credit' ? $temp[$exits]['credit'] + $value->journal_particular_amount: $temp[$exits]['credit'];
               
                }else{

                    $data_insert = [
                                        'ledger_name' => $value->ledger_name,
                                        'debit' => ($value->journal_particular_amount_type=='debit' ?  $value->journal_particular_amount:0),
                                        'credit' => ($value->journal_particular_amount_type=='credit' ? $value->journal_particular_amount:0)
                                    ];
                    if(empty($temp))
                        $temp [1] = $data_insert;
                    else
                       $temp [] = $data_insert; 
                }               
                
            }

            $data['temp']=$temp;
            // return \View::make('pages.reports.pdf.purchase-pdf',$data);
            $pdf = \PDF::loadView('pages.reports.pdf.purchase-pdf',$data);
            $pdfname = time().'purchase-report.pdf';
            return $pdf->stream($pdfname);
        }else return \Redirect::to('/reports/purchase');

    }



    /********************************************
    ## PurchasePrint
    *********************************************/
    public function PurchasePrint($search_from,$search_to,$cost_center_id){

        if(!empty($search_from) && !empty($search_to) || !empty($cost_center_id)){

            $purchase_credit_info = \App\Report::GetBalanceSheetLedgerByDateWithCost('Stocks-in raw material','4', $search_from, $search_to, $cost_center_id);

            $temp = array();

            foreach ($purchase_credit_info as $key => $value) {

                $exits = \App\Report::MultiArraySerach($value->ledger_name,'ledger_name',$temp);

                if($exits){

                    $temp[$exits]['debit'] = $value->journal_particular_amount_type=='debit' ? $temp[$exits]['debit'] + $value->journal_particular_amount: $temp[$exits]['debit'];
                    $temp[$exits]['credit'] = $value->journal_particular_amount_type=='credit' ? $temp[$exits]['credit'] + $value->journal_particular_amount: $temp[$exits]['credit'];
               
                }else{

                    $data_insert = [
                                    'ledger_name' => $value->ledger_name,
                                    'debit' => ($value->journal_particular_amount_type=='debit' ?  $value->journal_particular_amount:0),
                                    'credit' => ($value->journal_particular_amount_type=='credit' ? $value->journal_particular_amount:0)
                                    ];
                    if(empty($temp))
                        $temp [1] = $data_insert;
                    else
                       $temp [] = $data_insert; 
                }
               
            }

            $data['temp']=$temp;
            return \View::make('pages.reports.pdf.purchase-print',$data);

        }else return \Redirect::to('/reports/purchase');


    }




    /********************************************
    ## TrailBalancePage
    *********************************************/
    public function TrailBalancePage(){

        $now=date("Y-m-d");
        if(isset($_GET['search_from'])  &&  isset($_GET['search_to']) &&  isset($_GET['cost_center_id'])){

            $search_from = $_GET['search_from'];
            $search_to = $_GET['search_to'];
            $cost_center_id = $_GET['cost_center_id'];
            $data['search_from'] = $now;
            $data['search_to'] = $now;
            $data['cost_center_id'] = $cost_center_id;
        }else{
            $search_from = $now;
            $search_to = $now; 
            $cost_center_id = 0; 
        }

        $data['search_from']=$search_from;
        $data['search_to']=$search_to;
        $data['cost_centers']=$cost_center_id;

        $all_assets=\App\Report::AllLedgerGet('Assets',1, $search_from, $search_to, $cost_center_id);
        $data['all_assets']=$all_assets;


        $all_liabilities_and_capital=\App\Report::AllLedgerGet('Liabilities & Capital',1,$search_from, $search_to, $cost_center_id);
        $data['all_liabilities_and_capital']=$all_liabilities_and_capital;



        $all_direct_incomes=\App\Report::AllLedgerGet('Direct Incomes',1,$search_from, $search_to, $cost_center_id);
        $data['all_direct_incomes']=$all_direct_incomes;


        $all_indirect_incomes=\App\Report::AllLedgerGet('Indirect incomes',1,$search_from, $search_to, $cost_center_id);
        $data['all_indirect_incomes']=$all_indirect_incomes;



        $all_direct_expenses=\App\Report::AllLedgerGet('Direct Expenses',1,$search_from, $search_to, $cost_center_id);
        $data['all_direct_expenses']=$all_direct_expenses;


        $all_indirect_expenses=\App\Report::AllLedgerGet('Indirect Expenses',1,$search_from, $search_to, $cost_center_id);
        $data['all_indirect_expenses']=$all_indirect_expenses;

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.reports.trail-balance',$data);

    }




    /********************************************
    ## TrailBalancePDF
    *********************************************/
    public function TrailBalancePDF($search_from,$search_to, $cost_center_id){

        if(!empty($search_from)  &&  !empty($search_to) || !empty($cost_center_id)){
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;

            $all_assets=\App\Report::AllLedgerGet('Assets',1, $search_from, $search_to, $cost_center_id);
            $data['all_assets']=$all_assets;


            $all_liabilities_and_capital=\App\Report::AllLedgerGet('Liabilities & Capital',1,$search_from, $search_to, $cost_center_id);
            $data['all_liabilities_and_capital']=$all_liabilities_and_capital;


            $all_direct_incomes=\App\Report::AllLedgerGet('Direct Incomes',1,$search_from, $search_to, $cost_center_id);
            $data['all_direct_incomes']=$all_direct_incomes;


            $all_indirect_incomes=\App\Report::AllLedgerGet('Indirect incomes',1,$search_from, $search_to, $cost_center_id);
            $data['all_indirect_incomes']=$all_indirect_incomes;


            $all_direct_expenses=\App\Report::AllLedgerGet('Direct Expenses',1,$search_from, $search_to, $cost_center_id);
            $data['all_direct_expenses']=$all_direct_expenses;


            $all_indirect_expenses=\App\Report::AllLedgerGet('Indirect Expenses',1,$search_from, $search_to, $cost_center_id);
            $data['all_indirect_expenses']=$all_indirect_expenses;


            // return \View::make('pages.reports.pdf.trail-balance-report-pdf',$data);
            $pdf = \PDF::loadView('pages.reports.pdf.trail-balance-report-pdf',$data);
            $pdfname = time().' trail-balance-report.pdf';
            return $pdf->download($pdfname);
        }else return \Redirect::to('/trail/balance/report');

    }


    /********************************************
    ## TrailBalancePrint
    *********************************************/
    public function TrailBalancePrint($search_from,$search_to,$cost_center_id){


        if(!empty($search_from)  &&  !empty($search_to) || !empty($cost_center_id) ){
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;

            $all_assets=\App\Report::AllLedgerGet('Assets',1, $search_from, $search_to, $cost_center_id);
            $data['all_assets']=$all_assets;


            $all_liabilities_and_capital=\App\Report::AllLedgerGet('Liabilities & Capital',1,$search_from, $search_to, $cost_center_id);
            $data['all_liabilities_and_capital']=$all_liabilities_and_capital;


            $all_direct_incomes=\App\Report::AllLedgerGet('Direct Incomes',1,$search_from, $search_to, $cost_center_id);
            $data['all_direct_incomes']=$all_direct_incomes;


            $all_indirect_incomes=\App\Report::AllLedgerGet('Indirect incomes',1,$search_from, $search_to, $cost_center_id);
            $data['all_indirect_incomes']=$all_indirect_incomes;


            $all_direct_expenses=\App\Report::AllLedgerGet('Direct Expenses',1,$search_from, $search_to, $cost_center_id);
            $data['all_direct_expenses']=$all_direct_expenses;


            $all_indirect_expenses=\App\Report::AllLedgerGet('Indirect Expenses',1,$search_from, $search_to, $cost_center_id);
            $data['all_indirect_expenses']=$all_indirect_expenses;


            return \View::make('pages.reports.pdf.trail-balance-report-print',$data);
        }else return \Redirect::to('/trail/balance/report');

    }

    /********************************************
    ## AccountPayableReport
    *********************************************/
    public function AccountPayableReport(){

        $now=date("Y-m-d");

        if(isset($_GET['search_from'])  &&  isset($_GET['search_to']) &&  isset($_GET['cost_center'])){

            $search_from = $_GET['search_from'];
            $search_to = $_GET['search_to'];
            if(isset($_GET['cost_center'])){
                $cost_center_id = $_GET['cost_center'];
            }else{
                $cost_center_id=0;
            }


            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;
        }else{
            $search_from='01-01-2014';
            $search_to=$now;
            $cost_center_id=0;
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;
        }

            $account_payable_info= \App\Report::GetPayableAndReceivableData('Accounts Payable', 3, $search_from, $search_to, $cost_center_id);

            $account_payable= \App\Report::GroupByData($account_payable_info);
            $data['account_payable']=$account_payable;

            return \View::make('pages.reports.account-payable-report',$data);
    }



    /********************************************
    ## AccountPayableReportPDF
    *********************************************/
    public function AccountPayableReportPDF($search_from,$search_to,$cost_center_id){

        $now=date("Y-m-d");
        if(!empty($search_from)  &&  !empty($search_to) || !empty($cost_center_id)){
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;

            $account_payable_info= \App\Report::GetPayableAndReceivableData('Accounts Payable','3',$search_from, $search_to, $cost_center_id);

            $account_payable= \App\Report::GroupByData($account_payable_info);
            $data['account_payable']=$account_payable;

            $pdf = \PDF::loadView('pages.reports.pdf.account-payable-report-pdf',$data);
            $pdfname = time().' account-payable-report.pdf';
            return $pdf->download($pdfname);

            // return \View::make('pages.reports.pdf.account-payable-report-pdf',$data);
        }else return \Redirect::to('/account-payable/balance/report');

    }


    /********************************************
    ## AccountPayableReportPrint
    *********************************************/
    public function AccountPayableReportPrint($search_from,$search_to,$cost_center_id){

        $now=date("Y-m-d");
        if(!empty($search_from)  &&  !empty($search_to) || !empty($cost_center_id)){
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;

            if(isset($_GET['cost_center_id'])){
                $cost_center_id = $_GET['cost_center_id'];
            }else{
                $cost_center_id=0;
            }

            $account_payable_info= \App\Report::GetPayableAndReceivableData('Accounts Payable','3',$search_from, $search_to, $cost_center_id);

            $account_payable= \App\Report::GroupByData($account_payable_info);
            $data['account_payable']=$account_payable;

            return \View::make('pages.reports.pdf.account-payable-report-print',$data);
        }else return \Redirect::to('/account-payable/balance/report');

    }



    /********************************************
    ## AccountReceivableReport
    *********************************************/
    public function AccountReceivableReport(){

        $now=date("Y-m-d");

        if(isset($_GET['search_from'])  &&  isset($_GET['search_to']) ||  isset($_GET['cost_center'])){
            $search_from = $_GET['search_from'];
            $search_to = $_GET['search_to'];
            if(isset($_GET['cost_center'])){
                $cost_center_id = $_GET['cost_center'];
            }else{
                $cost_center_id=0;
            }


            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;
        }else{
            $search_from='01-01-2014';
            $search_to=$now;
            $cost_center_id=0;
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;
        }

            $account_receivable_info= \App\Report::GetPayableAndReceivableData('Account Receivable', 3, $search_from, $search_to, $cost_center_id);


            $account_receivable= \App\Report::GroupByData($account_receivable_info);
            $data['account_receivable']=$account_receivable;

            return \View::make('pages.reports.account-receivable-report',$data);
    }



    /********************************************
    ## AccountReceivableReportPDF
    *********************************************/
    public function AccountReceivableReportPDF($search_from,$search_to,$cost_center_id){

        $now=date("Y-m-d");
        if(!empty($search_from)  &&  !empty($search_to) || !empty($cost_center_id)){
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;

            $account_receivable_info= \App\Report::GetPayableAndReceivableData('Account Receivable','3',$search_from, $search_to, $cost_center_id);

            $account_receivable= \App\Report::GroupByData($account_receivable_info);
            $data['account_receivable']=$account_receivable;


            $pdf = \PDF::loadView('pages.reports.pdf.account-receivable-report-pdf',$data);
            $pdfname = time().' account-receivable-report.pdf';
            return $pdf->download($pdfname);

            // return \View::make('pages.reports.pdf.account-receivable-report-pdf',$data);
        }else return \Redirect::to('/account-receivable/balance/report');

    }


    /********************************************
    ## AccountReceivableReportPrint
    *********************************************/
    public function AccountReceivableReportPrint($search_from,$search_to,$cost_center_id){

        $now=date("Y-m-d");
        if(!empty($search_from)  &&  !empty($search_to) || !empty($cost_center_id)){
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;

            $account_receivable_info= \App\Report::GetPayableAndReceivableData('Account Receivable','3',$search_from, $search_to, $cost_center_id);

            $account_receivable= \App\Report::GroupByData($account_receivable_info);
            $data['account_receivable']=$account_receivable;

            return \View::make('pages.reports.pdf.account-receivable-report-print',$data);
        }else return \Redirect::to('/account-payable/balance/report');

    }





    /********************************************
    ## PurchaseReportPage
    *********************************************/
    public function PurchaseReportPage(){

        $now=date("Y-m-d");
        
        if(isset($_GET['search_from'])  &&  isset($_GET['search_to']) &&  isset($_GET['cost_center'])){

            $search_from = $_GET['search_from'];
            $search_to = $_GET['search_to'];
            if(isset($_GET['cost_center'])){
                $cost_center_id = $_GET['cost_center'];
            }else{
                $cost_center_id=0;
            }

            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;
        }else{
            $search_from=$now;
            $search_to=$now;
            $cost_center_id=0;
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;
        }

        if($cost_center_id == '0'){
            $get_all_stock_transaction= \DB::table('ltech_inventory_stocks_transactions')
                ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date', [$search_from,$search_to])
                ->get();
        }else{
            $get_all_stock_transaction= \DB::table('ltech_inventory_stocks_transactions')
                ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date', [$search_from,$search_to])
                ->where('ltech_inventory_stocks_transactions.cost_center_id',$cost_center_id)
                ->get();
        }

        if(!empty($get_all_stock_transaction)){
            $purchase_item_data=\App\Report::GroupByPurchaseData($get_all_stock_transaction);
            $data['purchase_item_data']=$purchase_item_data;
        }

        return \View::make('pages.reports.purchase-report-page',$data);
    }



    /********************************************
    ## PurchaseReportPagePDF
    *********************************************/
    public function PurchaseReportPagePDF($search_from,$search_to,$cost_center_id){

        $now=date("Y-m-d");
        if(!empty($search_from)  &&  !empty($search_to) || !empty($cost_center_id)){
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;

            
            if($cost_center_id == '0'){
                $get_all_stock_transaction= \DB::table('ltech_inventory_stocks_transactions')
                    ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                    ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date', [$search_from,$search_to])
                    ->get();
            }else{
                $get_all_stock_transaction= \DB::table('ltech_inventory_stocks_transactions')
                    ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                    ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date', [$search_from,$search_to])
                    ->where('ltech_inventory_stocks_transactions.cost_center_id',$cost_center_id)
                    ->get();
            }

            if(!empty($get_all_stock_transaction)){
                $purchase_item_data=\App\Report::GroupByPurchaseData($get_all_stock_transaction);
                $data['purchase_item_data']=$purchase_item_data;
            }


            $pdf = \PDF::loadView('pages.reports.pdf.purchase-report-page-pdf',$data);
            $pdfname = time().' purchase-report-pdf.pdf';
            return $pdf->download($pdfname);

            // return \View::make('pages.reports.pdf.purchase-report-page-pdf',$data);
        }else return \Redirect::to('/purchase/balance/report');

    }


    /********************************************
    ## PurchaseReportPagePDFPrint
    *********************************************/
    public function PurchaseReportPagePDFPrint($search_from,$search_to,$cost_center_id){

        $now=date("Y-m-d");
        if(!empty($search_from)  &&  !empty($search_to) || !empty($cost_center_id)){
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;

            if($cost_center_id == '0'){
                $get_all_stock_transaction= \DB::table('ltech_inventory_stocks_transactions')
                    ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                    ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date', [$search_from,$search_to])
                    ->get();
            }else{
                $get_all_stock_transaction= \DB::table('ltech_inventory_stocks_transactions')
                    ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                    ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date', [$search_from,$search_to])
                    ->where('ltech_inventory_stocks_transactions.cost_center_id',$cost_center_id)
                    ->get();
            }

            if(!empty($get_all_stock_transaction)){
                $purchase_item_data=\App\Report::GroupByPurchaseData($get_all_stock_transaction);
                $data['purchase_item_data']=$purchase_item_data;
            }

            return \View::make('pages.reports.pdf.purchase-report-page-print',$data);
        }else return \Redirect::to('/purchase/balance/report');

    }





    /********************************************
    ## SalesReportPage
    *********************************************/
    public function SalesReportPage(){

        $now=date("Y-m-d");
        
        if(isset($_GET['search_from'])  &&  isset($_GET['search_to']) &&  isset($_GET['cost_center'])){

            $search_from = $_GET['search_from'];
            $search_to = $_GET['search_to'];
            if(isset($_GET['cost_center'])){
                $cost_center_id = $_GET['cost_center'];
            }else{
                $cost_center_id=0;
            }

            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;
        }else{
            $search_from=$now;
            $search_to=$now;
            $cost_center_id=0;
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;
        }

        if($cost_center_id == '0'){
            $get_finish_goods_transaction= \DB::table('ltech_finish_goods_transactions')
                ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date', [$search_from,$search_to])
                ->get();
        }else{
            $get_finish_goods_transaction= \DB::table('ltech_finish_goods_transactions')
                ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date', [$search_from,$search_to])
                ->where('ltech_finish_goods_transactions.cost_center_id',$cost_center_id)
                ->get();
        }

        if(!empty($get_finish_goods_transaction)){
            $sales_goods_data=\App\Report::GroupBySalesData($get_finish_goods_transaction);
            $data['sales_goods_data']=$sales_goods_data;
        }

        return \View::make('pages.reports.sales-report-page',$data);
    }



    /********************************************
    ## SalesReportPagePDF
    *********************************************/
    public function SalesReportPagePDF($search_from,$search_to,$cost_center_id){

        $now=date("Y-m-d");
        if(!empty($search_from)  &&  !empty($search_to) || !empty($cost_center_id)){
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;

            if($cost_center_id == '0'){
                $get_finish_goods_transaction= \DB::table('ltech_finish_goods_transactions')
                    ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                    ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date', [$search_from,$search_to])
                    ->get();
            }else{
                $get_finish_goods_transaction= \DB::table('ltech_finish_goods_transactions')
                    ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                    ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date', [$search_from,$search_to])
                    ->where('ltech_finish_goods_transactions.cost_center_id',$cost_center_id)
                    ->get();
            }

            if(!empty($get_finish_goods_transaction)){
                $sales_goods_data=\App\Report::GroupBySalesData($get_finish_goods_transaction);
                $data['sales_goods_data']=$sales_goods_data;
            }


            $pdf = \PDF::loadView('pages.reports.pdf.sales-report-page-pdf',$data);
            $pdfname = time().' purchase-report.pdf';
            return $pdf->download($pdfname);

            // return \View::make('pages.reports.pdf.sales-report-page-pdf',$data);
        }else return \Redirect::to('/sales/balance/report');

    }


    /********************************************
    ## SalesReportPagePDFPrint
    *********************************************/
    public function SalesReportPagePDFPrint($search_from,$search_to,$cost_center_id){

        $now=date("Y-m-d");
        if(!empty($search_from)  &&  !empty($search_to) || !empty($cost_center_id)){
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center_id']=$cost_center_id;

            if($cost_center_id == '0'){
                $get_finish_goods_transaction= \DB::table('ltech_finish_goods_transactions')
                    ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                    ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date', [$search_from,$search_to])
                    ->get();
            }else{
                $get_finish_goods_transaction= \DB::table('ltech_finish_goods_transactions')
                    ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                    ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date', [$search_from,$search_to])
                    ->where('ltech_finish_goods_transactions.cost_center_id',$cost_center_id)
                    ->get();
            }

            if(!empty($get_finish_goods_transaction)){
                $sales_goods_data=\App\Report::GroupBySalesData($get_finish_goods_transaction);
                $data['sales_goods_data']=$sales_goods_data;
            }

            return \View::make('pages.reports.pdf.sales-report-page-print',$data);
        }else return \Redirect::to('/sales/balance/report');

    }

    /********************************************
    ## ManufacturingReport
    *********************************************/
    public function ManufacturingReport(){

        $now=date("Y-m-d");
        $total_lighting_data_info=0;
        $lighting_data_info=array();

        if(isset($_GET['search_from'])  &&  isset($_GET['search_to']) ||  isset($_GET['cost_center'])){

            $search_from = $_GET['search_from'];
            $search_to = $_GET['search_to'];
            if(isset($_GET['cost_center'])){
                $cost_center = $_GET['cost_center'];
            }else{
                $cost_center=0;
            }
        }else{
            $search_from=$now;
            $search_to=$now;
            $cost_center=0;

        }
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center']=$cost_center;


            $carriage_data_info= \App\Report::GetLadgerDetailsByDateWithCost('Carriage Inwards','2',$search_from, $search_to, $cost_center);
            $total_carriage_data_info= \App\Report::GroupByManufacturingData($carriage_data_info);
            $data['total_carriage_data_info'] = $total_carriage_data_info;


            $others_expences_data_info= \App\Report::GetLadgerDetailsByDateWithCost('Other Direct Expenses','2',$search_from, $search_to, $cost_center);
            $total_others_expences_data_info= \App\Report::GetLedgerTotal($others_expences_data_info);
            $data['total_others_expences_data_info'] = $total_others_expences_data_info;



            $direct_labor_info= \App\Report::GetLadgerDetailsByDateWithCost('Direct Labor','2',$search_from, $search_to, $cost_center);
            $total_direct_labor_data= \App\Report::GetLedgerTotal($direct_labor_info);
            $data['total_direct_labor_data'] = $total_direct_labor_data;



            $total_amount_of_raw_materials_purchase_data=0;
            $raw_materials_purchase_data_info= \App\Report::GetManufacturingRawMaterials('Stocks-in raw material','4',$search_from, $search_to, $cost_center,'purchase');
            $total_raw_materials_purchase_data_info= \App\Report::GroupByManufacturingData($raw_materials_purchase_data_info);


            foreach ($total_raw_materials_purchase_data_info as $key => $value) {
                $total_amount_of_raw_materials_purchase_data=$total_amount_of_raw_materials_purchase_data+$value['debit'];
            }

            $data['total_amount_of_raw_materials_purchase_data'] = $total_amount_of_raw_materials_purchase_data;

            $all_opening_amount= \App\Report::InventoryStocksOpeningData($search_from, $search_to, $cost_center);

            $data['all_opening_amount'] = $all_opening_amount;

            $all_closing_amount= \App\Report::InventoryStocksClosingData($search_from, $search_to, $cost_center);
            $data['all_closing_amount'] =$all_closing_amount;


        $total_stock_outwards_amount=0;

        if($cost_center!=0){
            $stock_summery_outwards_list=\DB::table('ltech_inventory_stocks_transactions')
                        ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                        ->where('ltech_inventory_stocks_transactions.cost_center_id',$cost_center)
                        ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','return')
                        ->get();
        }else{
            $stock_summery_outwards_list=\DB::table('ltech_inventory_stocks_transactions')
                        ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                        ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','return')
                        ->get();   
        }

                        foreach ($stock_summery_outwards_list as $key => $value) {
                            $total_stock_outwards_amount=$total_stock_outwards_amount+$value->stocks_quantity_cost;
                        }
        $data['total_stock_outwards_amount'] = $total_stock_outwards_amount;




        $expenses_info=\DB::table('ltech_ledger_group_2')->where('ledger_name','Factory Overhead')->first();
        $overhead_info=\DB::table('ltech_ledger_group_3')->where('ledger_group_parent_id',$expenses_info->ledger_id)->get();
        $all_overhead_info= \App\Report::ManufacturingData($overhead_info);

        if(!empty($all_overhead_info)){
            foreach ($all_overhead_info as $key => $value) {
                $overhead_data_info= \App\Report::GetManufacturingReportByDate(trim($value['ledger_name']),'3',$search_from, $search_to, $cost_center);
                $total_overhead_data_info= \App\Report::GroupByManufacturingData($overhead_data_info);

            $alll_overhead_info[]= \App\Report::GroupByManufacturing($total_overhead_data_info);
            }

        }
        $data['alll_overhead_info'] = $alll_overhead_info;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

       return \View::make('pages.reports.manufacturing-report',$data);
    }





    /********************************************
    ## ManufacturingReportPDF
    *********************************************/
    public function ManufacturingReportPDF($search_from,$search_to,$cost_center){
            $now=date('Y-m-d');


        if(!empty($search_from)  &&  !empty($search_to) ||  !empty($cost_center)){

            $carriage_data_info= \App\Report::GetLadgerDetailsByDateWithCost('Carriage Inwards','2',$search_from, $search_to, $cost_center);
            $total_carriage_data_info= \App\Report::GroupByManufacturingData($carriage_data_info);
            $data['total_carriage_data_info'] = $total_carriage_data_info;


            $others_expences_data_info= \App\Report::GetLadgerDetailsByDateWithCost('Other Direct Expenses','2',$search_from, $search_to, $cost_center);
            $total_others_expences_data_info= \App\Report::GetLedgerTotal($others_expences_data_info);
            $data['total_others_expences_data_info'] = $total_others_expences_data_info;


            $direct_labor_info= \App\Report::GetLadgerDetailsByDateWithCost('Direct Labor','2',$search_from, $search_to, $cost_center);
            $total_direct_labor_data= \App\Report::GetLedgerTotal($direct_labor_info);
            $data['total_direct_labor_data'] = $total_direct_labor_data;





            $total_amount_of_raw_materials_purchase_data=0;
            $raw_materials_purchase_data_info= \App\Report::GetManufacturingRawMaterials('Stocks-in raw material','4',$search_from, $search_to, $cost_center,'purchase');
            $total_raw_materials_purchase_data_info= \App\Report::GroupByManufacturingData($raw_materials_purchase_data_info);

            foreach ($total_raw_materials_purchase_data_info as $key => $value) {
                $total_amount_of_raw_materials_purchase_data=$total_amount_of_raw_materials_purchase_data+$value['debit'];
            }
            $data['total_amount_of_raw_materials_purchase_data'] = $total_amount_of_raw_materials_purchase_data;

            $all_opening_amount= \App\Report::InventoryStocksOpeningData($search_from, $search_to, $cost_center);
            
            $data['all_opening_amount'] = $all_opening_amount;

            $all_closing_amount= \App\Report::InventoryStocksClosingData($search_from, $search_to, $cost_center);
            $data['all_closing_amount'] = $all_closing_amount;



        $total_stock_outwards_amount=0;

        if($cost_center!=0){
            $stock_summery_outwards_list=\DB::table('ltech_inventory_stocks_transactions')
                        ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                        ->where('ltech_inventory_stocks_transactions.cost_center_id',$cost_center)
                        ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','return')
                        ->get();
        }else{
            $stock_summery_outwards_list=\DB::table('ltech_inventory_stocks_transactions')
                        ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                        ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','return')
                        ->get();   
        }

                        foreach ($stock_summery_outwards_list as $key => $value) {
                            $total_stock_outwards_amount=$total_stock_outwards_amount+$value->stocks_quantity_cost;
                        }
        $data['total_stock_outwards_amount'] = $total_stock_outwards_amount;




        $expenses_info=\DB::table('ltech_ledger_group_2')->where('ledger_name','Factory Overhead')->first();
        $overhead_info=\DB::table('ltech_ledger_group_3')->where('ledger_group_parent_id',$expenses_info->ledger_id)->get();
        $all_overhead_info= \App\Report::ManufacturingData($overhead_info);

        if(!empty($all_overhead_info)){
            foreach ($all_overhead_info as $key => $value) {
                $lighting_data_info= \App\Report::GetManufacturingReportByDate(trim($value['ledger_name']),'3',$search_from, $search_to, $cost_center);
                $total_lighting_data_info= \App\Report::GroupByManufacturingData($lighting_data_info);

            $alll_overhead_info[]= \App\Report::GroupByManufacturing($total_lighting_data_info);
            }

            $data['alll_overhead_info'] = $alll_overhead_info;

        }


            $data['search_from'] = $search_from;
            $data['search_to'] = $search_to;
            $data['cost_center'] = $cost_center;
            $data['page_title'] = $this->page_title;
            $data['page_desc'] = $this->page_desc;


            $pdf = \PDF::loadView('pages.reports.pdf.manufacturing-report-pdf',$data);
            $pdfname = time().' manufacturing-report.pdf';
            return $pdf->download($pdfname);

           // return \View::make('pages.reports.pdf.manufacturing-report-pdf',$data);
        }else return \Redirect::to('/manufacturing/report');

    }




    /********************************************
    ## ManufacturingReportPrint
    *********************************************/
    public function ManufacturingReportPrint($search_from,$search_to,$cost_center){


        if(!empty($search_from)  &&  !empty($search_to) ||  !empty($cost_center)){

            $carriage_data_info= \App\Report::GetLadgerDetailsByDateWithCost('Carriage Inwards','2',$search_from, $search_to, $cost_center);
            $total_carriage_data_info= \App\Report::GroupByManufacturingData($carriage_data_info);
            $data['total_carriage_data_info'] = $total_carriage_data_info;




            $others_expences_data_info= \App\Report::GetLadgerDetailsByDateWithCost('Other Direct Expenses','2',$search_from, $search_to, $cost_center);
            $total_others_expences_data_info= \App\Report::GetLedgerTotal($others_expences_data_info);
            $data['total_others_expences_data_info'] = $total_others_expences_data_info;


            $direct_labor_info= \App\Report::GetLadgerDetailsByDateWithCost('Direct Labor','2',$search_from, $search_to, $cost_center);
            $total_direct_labor_data= \App\Report::GetLedgerTotal($direct_labor_info);
            $data['total_direct_labor_data'] = $total_direct_labor_data;



            $total_amount_of_raw_materials_purchase_data=0;
            $raw_materials_purchase_data_info= \App\Report::GetManufacturingRawMaterials('Stocks-in raw material','4',$search_from, $search_to, $cost_center,'purchase');
            $total_raw_materials_purchase_data_info= \App\Report::GroupByManufacturingData($raw_materials_purchase_data_info);

            foreach ($total_raw_materials_purchase_data_info as $key => $value) {
                $total_amount_of_raw_materials_purchase_data=$total_amount_of_raw_materials_purchase_data+$value['debit'];
            }
            $data['total_amount_of_raw_materials_purchase_data'] = $total_amount_of_raw_materials_purchase_data;

            $all_opening_amount= \App\Report::InventoryStocksOpeningData($search_from, $search_to, $cost_center);
            
            $data['all_opening_amount'] = $all_opening_amount;

            $all_closing_amount= \App\Report::InventoryStocksClosingData($search_from, $search_to, $cost_center);
            $data['all_closing_amount'] = $all_closing_amount;



        $total_stock_outwards_amount=0;

        if($cost_center!=0){
            $stock_summery_outwards_list=\DB::table('ltech_inventory_stocks_transactions')
                        ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                        ->where('ltech_inventory_stocks_transactions.cost_center_id',$cost_center)
                        ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','return')
                        ->get();
        }else{
            $stock_summery_outwards_list=\DB::table('ltech_inventory_stocks_transactions')
                        ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                        ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','return')
                        ->get();   
        }

                        foreach ($stock_summery_outwards_list as $key => $value) {
                            $total_stock_outwards_amount=$total_stock_outwards_amount+$value->stocks_quantity_cost;
                        }
        $data['total_stock_outwards_amount'] = $total_stock_outwards_amount;


        $expenses_info=\DB::table('ltech_ledger_group_2')->where('ledger_name','Factory Overhead')->first();
        $overhead_info=\DB::table('ltech_ledger_group_3')->where('ledger_group_parent_id',$expenses_info->ledger_id)->get();
        $all_overhead_info= \App\Report::ManufacturingData($overhead_info);

        if(!empty($all_overhead_info)){
            foreach ($all_overhead_info as $key => $value) {
                $lighting_data_info= \App\Report::GetManufacturingReportByDate(trim($value['ledger_name']),'3',$search_from, $search_to, $cost_center);
                $total_lighting_data_info= \App\Report::GroupByManufacturingData($lighting_data_info);

            $alll_overhead_info[]= \App\Report::GroupByManufacturing($total_lighting_data_info);
            }
            $data['alll_overhead_info'] = $alll_overhead_info;


        }

            $data['page_title'] = $this->page_title;
            $data['page_desc'] = $this->page_desc;

            $data['search_from'] = $search_from;
            $data['search_to'] = $search_to;
            $data['cost_center'] = $cost_center;

           return \View::make('pages.reports.pdf.manufacturing-report-print',$data);
        }else return \Redirect::to('/manufacturing/report');

    }

    /********************************************
    ## StockSummery
    *********************************************/
    public function StockSummeryList(){


    /*------------------------------------Get Request--------------------------------------------*/
        if(isset($_GET['search_from']) && isset($_GET['search_to']) || isset($_GET['cost_center'])){

            $search_from = $_GET['search_from'];
            $search_to = $_GET['search_to'];
            if(isset($_GET['cost_center'])){
                $cost_center = $_GET['cost_center'];

            }else{
               $cost_center=0; 
            }

                $stock_summery_list=\DB::table('ltech_inventory_stocks_transactions')
                                ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                ->where(function($query){
                                    if(isset($_GET['cost_center']) && !empty($_GET['cost_center'])){
                                        $query->where(function ($q){
                                            $q->where('ltech_inventory_stocks_transactions.cost_center_id', $_GET['cost_center']);
                                          });
                                    }
                                })
                                ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                                ->orderBy('ltech_inventory_stocks_transactions.stocks_transaction_date','asc')
                                ->get();


            $data['stock_summery_list'] = $stock_summery_list;
            $data['search_from'] = $search_from;
            $data['search_to'] = $search_to;
            $data['cost_center'] = $cost_center;
            $stock_data= \App\Report::GroupByStockData($stock_summery_list);
            $data['stock_data']=$stock_data;


         }
    /*------------------------------------/Get Request----------------------------------*/
        else{
            $today = date('Y-m-d');
            $cost_center = 0;                    


            $stock_summery_list=\DB::table('ltech_inventory_stocks_transactions')
                                ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$today,$today])
                                ->orderBy('ltech_inventory_stocks_transactions.stocks_transaction_date','asc')
                                ->get();

                                

            $data['search_from'] = $today;
            $data['search_to'] = $today;
            $data['cost_center'] = $cost_center;
            $data['stock_summery_list'] = $stock_summery_list;

            $stock_data= \App\Report::GroupByStockData($stock_summery_list);
            $data['stock_data']=$stock_data;
        }


        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        return \View::make('pages.reports.stock-summary-list',$data);

    }



    /********************************************
    ## InventoryStockSummaryPDF
    *********************************************/
    public function InventoryStockSummaryPDF($search_from,$search_to, $cost_center){


        if(!empty($search_from) && !empty($search_to) || !empty($cost_center)){

            if($cost_center != 0){
                $stock_summery_list=\DB::table('ltech_inventory_stocks_transactions')
                                ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                ->where('ltech_inventory_stocks_transactions.cost_center_id',$cost_center)
                                ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                                ->orderBy('ltech_inventory_stocks_transactions.stocks_transaction_date','asc')
                                ->get();
            }else{
                $stock_summery_list=\DB::table('ltech_inventory_stocks_transactions')
                                ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                                ->orderBy('ltech_inventory_stocks_transactions.stocks_transaction_date','asc')
                                ->get();
            }

            $data['search_from'] = $search_from;
            $data['search_to'] = $search_to;
            $data['cost_center'] = $cost_center;
            $stock_summary_data= \App\Report::GroupByStockData($stock_summery_list);
            $data['stock_summary_data']=$stock_summary_data;

            $data['page_title'] = $this->page_title;
            $data['page_desc'] = $this->page_desc;

            $pdf = \PDF::loadView('pages.reports.pdf.inventory-stock-summary-pdf',$data);
            $pdfname = time().' stock-summary.pdf';
            return $pdf->download($pdfname);
            // return \View::make('pages.reports.pdf.inventory-stock-summary-pdf',$data);


         }else return \Redirect::to('/stock/summery/list');
       


    }


    /********************************************
    ## InventoryStockSummaryPrint
    *********************************************/
    public function InventoryStockSummaryPrint($search_from,$search_to,$cost_center){


        if(!empty($search_from) && !empty($search_to) || !empty($cost_center)){


            if($cost_center != 0){
                $stock_summery_list=\DB::table('ltech_inventory_stocks_transactions')
                                ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                ->where('ltech_inventory_stocks_transactions.cost_center_id',$cost_center)
                                ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                                ->orderBy('ltech_inventory_stocks_transactions.stocks_transaction_date','asc')
                                ->get();
            }else{
                $stock_summery_list=\DB::table('ltech_inventory_stocks_transactions')
                                ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                                ->orderBy('ltech_inventory_stocks_transactions.stocks_transaction_date','asc')
                                ->get();
            }

            $data['stock_summery_list'] = $stock_summery_list;

            $data['search_from'] = $search_from;
            $data['search_to'] = $search_to;
            $data['cost_center'] = $cost_center;

            $stock_summary_data= \App\Report::GroupByStockData($stock_summery_list);
            $data['stock_summary_data']=$stock_summary_data;

            $data['page_title'] = $this->page_title;
            $data['page_desc'] = $this->page_desc;
            return \View::make('pages.reports.pdf.inventory-stock-summary-print',$data);


         }else return \Redirect::to('/stock/summery/list');
       

    } 



    /********************************************
    ## FinishGoodsSummeryList
    *********************************************/
    public function FinishGoodsSummeryList(){


    /*------------------------------------Get Request--------------------------------------------*/
        if(isset($_GET['search_from']) && isset($_GET['search_to']) || isset($_GET['cost_center'])){

            $search_from = $_GET['search_from'];
            $search_to = $_GET['search_to'];

            if($_GET['cost_center'] != 0){
                $cost_center = $_GET['cost_center'];
                $finish_goods_summery_list=\DB::table('ltech_finish_goods_transactions')
                                ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                                ->where('ltech_finish_goods_transactions.cost_center_id',$cost_center)
                                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
                                ->orderBy('ltech_finish_goods_transactions.finish_goods_transaction_date','asc')
                                ->get();
            }else{
                $cost_center = 0;
                $finish_goods_summery_list=\DB::table('ltech_finish_goods_transactions')
                                ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
                                ->orderBy('ltech_finish_goods_transactions.finish_goods_transaction_date','asc')
                                ->get();
            }

            $data['finish_goods_summery_list'] = $finish_goods_summery_list;

            $data['search_from'] = $search_from;
            $data['search_to'] = $search_to;
            $data['cost_center'] = $cost_center;
            $finish_goods_data= \App\Report::GroupByFinishGoodsData($finish_goods_summery_list);
            $data['finish_goods_data']=$finish_goods_data;
        

         }
    /*------------------------------------/Get Request-----------------------------*/
        else{
            $today = date('Y-m-d');

            $finish_goods_summery_list=\DB::table('ltech_finish_goods_transactions')
                                ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$today,$today])
                                ->orderBy('ltech_finish_goods_transactions.finish_goods_transaction_date','asc')
                                ->get();

            $data['search_from'] = $today;
            $data['search_to'] = $today;
            $data['cost_center'] = 0;
            $data['finish_goods_summery_list'] = $finish_goods_summery_list;


            $finish_goods_data= \App\Report::GroupByFinishGoodsData($finish_goods_summery_list);
            $data['finish_goods_data']=$finish_goods_data;
        }


        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        return \View::make('pages.reports.finish-goods-summary-list',$data);

    } 




    /********************************************
    ## FinishGoodsReportPDF
    *********************************************/
    public function FinishGoodsReportPDF($search_from,$search_to,$cost_center){

        if(!empty($search_from) && !empty($search_to) || !empty($cost_center)){

            if($cost_center!=0){
                $finish_goods_summery_list=\DB::table('ltech_finish_goods_transactions')
                                ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                                ->where('ltech_finish_goods_transactions.cost_center_id',$cost_center)
                                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
                                ->orderBy('ltech_finish_goods_transactions.finish_goods_transaction_date','asc')
                                ->get();
            }else{
                    $finish_goods_summery_list=\DB::table('ltech_finish_goods_transactions')
                                ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
                                ->orderBy('ltech_finish_goods_transactions.finish_goods_transaction_date','asc')
                                ->get();
            }

            $data['finish_goods_summery_list'] = $finish_goods_summery_list;

            $data['search_from'] = $search_from;
            $data['search_to'] = $search_to;
            $data['cost_center'] = $cost_center;

            $finish_goods_data= \App\Report::GroupByFinishGoodsData($finish_goods_summery_list);
            $data['finish_goods_data']=$finish_goods_data;

            $data['page_title'] = $this->page_title;
            $data['page_desc'] = $this->page_desc;

            $pdf = \PDF::loadView('pages.reports.pdf.finish-goods-summary-pdf',$data);
            $pdfname = time().' finish-goods-summary.pdf';
            return $pdf->download($pdfname);
            // return \View::make('pages.reports.pdf.finish-goods-summary-pdf',$data);


         }else return \Redirect::to('/finish-goods/summery/list');
       


    }


    /********************************************
    ## FinishGoodsReportPrint
    *********************************************/
    public function FinishGoodsReportPrint($search_from,$search_to,$cost_center){

            // $search_from = "11-01-2017";
            // $search_to = date('Y-m-d');


        if(!empty($search_from) && !empty($search_to) || !empty($cost_center)){

            if($cost_center!=0){
                $finish_goods_summery_list=\DB::table('ltech_finish_goods_transactions')
                                ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                                ->where('ltech_finish_goods_transactions.cost_center_id',$cost_center)
                                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
                                ->orderBy('ltech_finish_goods_transactions.finish_goods_transaction_date','asc')
                                ->get();
            }else{
                    $finish_goods_summery_list=\DB::table('ltech_finish_goods_transactions')
                                ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
                                ->orderBy('ltech_finish_goods_transactions.finish_goods_transaction_date','asc')
                                ->get();
            }

            $data['finish_goods_summery_list'] = $finish_goods_summery_list;

            $data['search_from'] = $search_from;
            $data['search_to'] = $search_to;
            $data['cost_center'] = $cost_center;
            
            $finish_goods_data= \App\Report::GroupByFinishGoodsData($finish_goods_summery_list);
            $data['finish_goods_data']=$finish_goods_data;

            $data['page_title'] = $this->page_title;
            $data['page_desc'] = $this->page_desc;
            return \View::make('pages.reports.pdf.finish-goods-summary-print',$data);


         }else return \Redirect::to('/finish-goods/summery/list');
       
    }  



    /********************************************
    ## IncomeStatementReport
    *********************************************/
    public function IncomeStatementReport(){

        $now=date("Y-m-d");
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        if(isset($_GET['search_from'])  &&  isset($_GET['search_to']) ||  isset($_GET['cost_center'])){
            $search_from = $_GET['search_from'];
            $search_to = $_GET['search_to'];
            if(isset($_GET['cost_center'])){
                $cost_center = $_GET['cost_center'];
            }else{
                $cost_center=0;
            }

        }else{
            $search_from=$now;
            $search_to=$now;
            $cost_center=0;

        }

            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            $data['cost_center']=$cost_center;


        $merchandise_sales_info= \App\Report::GetMerchandiseSales('Stocks-in finish goods','4',$search_from, $search_to, $cost_center,'sales');
        $total_merchandise_sales= \App\Report::GetLedgerTotal($merchandise_sales_info);

        $merchandise_sales_return_info= \App\Report::GetMerchandiseSales('Stocks-in finish goods','4',$search_from, $search_to, $cost_center,'sales_return');
        $total_merchandise_sales_return= \App\Report::GetLedgerTotal($merchandise_sales_return_info);


        if($total_merchandise_sales<0){
          $total_merchandise_sales_return=$total_merchandise_sales_return *(-1);  
        }

            $total_finish_goods=$total_merchandise_sales-$total_merchandise_sales_return;
            $data['total_finish_goods'] = $total_finish_goods;



        $other_incomes_info = \App\Report::GetLadgerDetailsByDateWithCost('Other Incomes','1',$search_from, $search_to, $cost_center);
        $total_other_incomes = \App\Report::GroupByData($other_incomes_info);
        $data['total_other_incomes'] = $total_other_incomes;

        $all_finish_goods_opening_balance= \App\Report::FinishGoodsOpeningData($search_from, $search_to, $cost_center);
        $all_finish_goods_closing_balance= \App\Report::FinishGoodsClosingData($search_from, $search_to, $cost_center);

        // $all_finish_goods_opening_balance= \App\Report::InventoryStocksOpeningData($search_from, $search_to, $cost_center);
        // $all_finish_goods_closing_balance= \App\Report::InventoryStocksClosingData($search_from, $search_to, $cost_center);
        $data['all_finish_goods_opening_balance'] = $all_finish_goods_opening_balance;
        $data['all_finish_goods_closing_balance'] = $all_finish_goods_closing_balance;


        $indirect_expenses_info = \App\Report::GetLadgerDetailsByDateWithCost('Indirect Expenses','1',$search_from, $search_to, $cost_center);
        $total_indirect_expenses = \App\Report::GroupByData($indirect_expenses_info);
        $data['total_indirect_expenses'] = $total_indirect_expenses;

        $cost_production=\App\Report::GetCostOfProduction($search_from, $search_to, $cost_center);
        $data['cost_production'] = $cost_production;

        return \View::make('pages.reports.income-statement',$data);
    }



    /********************************************
    ## IncomeStatementReportPDF
    *********************************************/
    public function IncomeStatementReportPDF($search_from,$search_to,$cost_center){

        if(!empty($search_from)  &&  !empty($search_to) || !empty($cost_center)){
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            if($cost_center!=0){
            $data['cost_center']=$cost_center;
            }else{
             $data['cost_center']=0;   
            }


            $merchandise_sales_info= \App\Report::GetMerchandiseSales('Stocks-in finish goods','4',$search_from, $search_to, $cost_center,'sales');
            $total_merchandise_sales= \App\Report::GetLedgerTotal($merchandise_sales_info);

            $merchandise_sales_return_info= \App\Report::GetMerchandiseSales('Stocks-in finish goods','4',$search_from, $search_to, $cost_center,'sales_return');
            $total_merchandise_sales_return= \App\Report::GetLedgerTotal($merchandise_sales_return_info);


            if($total_merchandise_sales<0){
              $total_merchandise_sales_return=$total_merchandise_sales_return *(-1);  
            }

            $total_finish_goods=$total_merchandise_sales-$total_merchandise_sales_return;
            $data['total_finish_goods'] = $total_finish_goods;


            $other_incomes_info = \App\Report::GetLadgerDetailsByDateWithCost('Other Incomes','1',$search_from, $search_to, $cost_center);
            $total_other_incomes = \App\Report::GroupByData($other_incomes_info);
            $data['total_other_incomes'] = $total_other_incomes;

            $all_finish_goods_opening_balance= \App\Report::FinishGoodsOpeningData($search_from, $search_to, $cost_center);
            $all_finish_goods_closing_balance= \App\Report::FinishGoodsClosingData($search_from, $search_to, $cost_center);
            $data['all_finish_goods_opening_balance'] = $all_finish_goods_opening_balance;
            $data['all_finish_goods_closing_balance'] = $all_finish_goods_closing_balance;

            $indirect_expenses_info = \App\Report::GetLadgerDetailsByDateWithCost('Indirect Expenses','1',$search_from, $search_to, $cost_center);
            $total_indirect_expenses = \App\Report::GroupByData($indirect_expenses_info);
            $data['total_indirect_expenses'] = $total_indirect_expenses;

            $cost_production=\App\Report::GetCostOfProduction($search_from, $search_to, $cost_center);
            $data['cost_production'] = $cost_production;


            $pdf = \PDF::loadView('pages.reports.pdf.income-statement-pdf',$data);
            $pdfname = time().' income-statement.pdf';
            return $pdf->download($pdfname);

            // return \View::make('pages.reports.pdf.income-statement-pdf',$data);
        }else return \Redirect::to('/income-satement/report');

    }


    /********************************************
    ## IncomeStatementReportPrint
    *********************************************/
    public function IncomeStatementReportPrint($search_from,$search_to,$cost_center){

        if(!empty($search_from)  &&  !empty($search_to) || !empty($cost_center)){
            $data['search_from']=$search_from;
            $data['search_to']=$search_to;
            if($cost_center!=0){
            $data['cost_center']=$cost_center;
            }else{
             $data['cost_center']=0;   
            }


            $merchandise_sales_info= \App\Report::GetMerchandiseSales('Stocks-in finish goods','4',$search_from, $search_to, $cost_center,'sales');
            $total_merchandise_sales= \App\Report::GetLedgerTotal($merchandise_sales_info);

            $merchandise_sales_return_info= \App\Report::GetMerchandiseSales('Stocks-in finish goods','4',$search_from, $search_to, $cost_center,'sales_return');
            $total_merchandise_sales_return= \App\Report::GetLedgerTotal($merchandise_sales_return_info);


            if($total_merchandise_sales<0){
              $total_merchandise_sales_return=$total_merchandise_sales_return *(-1);  
            }

            $total_finish_goods=$total_merchandise_sales-$total_merchandise_sales_return;
            $data['total_finish_goods'] = $total_finish_goods;


            $other_incomes_info = \App\Report::GetLadgerDetailsByDateWithCost('Other Incomes','1',$search_from, $search_to, $cost_center);
            $total_other_incomes = \App\Report::GroupByData($other_incomes_info);
            $data['total_other_incomes'] = $total_other_incomes;

            $all_finish_goods_opening_balance= \App\Report::FinishGoodsOpeningData($search_from, $search_to, $cost_center);
            $all_finish_goods_closing_balance= \App\Report::FinishGoodsClosingData($search_from, $search_to, $cost_center);
            $data['all_finish_goods_opening_balance'] = $all_finish_goods_opening_balance;
            $data['all_finish_goods_closing_balance'] = $all_finish_goods_closing_balance;

            $indirect_expenses_info = \App\Report::GetLadgerDetailsByDateWithCost('Indirect Expenses','1',$search_from, $search_to, $cost_center);
            $total_indirect_expenses = \App\Report::GroupByData($indirect_expenses_info);
            $data['total_indirect_expenses'] = $total_indirect_expenses;

            $cost_production=\App\Report::GetCostOfProduction($search_from, $search_to, $cost_center);
            $data['cost_production'] = $cost_production;


            return \View::make('pages.reports.pdf.income-statement-print',$data);
        }else return \Redirect::to('/income-satement/report');

    }











    /*********************End of Report Controller***************************/
}
