<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*******************************
#
## Supplier Controller
#
*******************************/

class SupplierController extends Controller
{
    

    public function __construct(){
	    $this->page_title = \Request::route()->getName();
	        $description = \Request::route()->getAction();
        $this->page_desc = isset($description['desc']) ?  $description['desc']:'';
	    \App\System::AccessLogWrite();
    }


    /********************************************
    # SupplierPaymentPage
    *********************************************/
    public function SupplierPaymentPage(){
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $data['supplier_list'] = \DB::table('ltech_suppliers')->orderBy('supplier_id','desc')->get();

        if(isset($_GET['supplier_ref']) && !empty($_GET['supplier_ref']) && isset($_GET['supplier_id']) && !empty($_GET['supplier_id']) && isset($_GET['supplier']) && !empty($_GET['supplier']) ){

           $supplier_id=$_GET['supplier_id'];
           $supplier_info = \DB::table('ltech_suppliers')->where('supplier_id',$_GET['supplier_id'])->first();

           if(!empty($supplier_info) && ($_GET['supplier_ref']==$supplier_info->supplier_account_id)){
                $data['cost_centers'] = \DB::table('ltech_cost_centers')->get();
                $data['supplier_inventory_transactions'] = \App\Inventory::SupplierAllStockTransaction($supplier_id);
                $data['supplier_info'] = $supplier_info;
                $data['supplier_credit_transactions'] = \App\Inventory::SupplierCreditTransaction($supplier_id);
            }

        }
        return \View::make('pages.supplier.supplier-payment',$data);
    }


    /********************************************
    # AjaxSupplierPaymentField
    *********************************************/
    public function AjaxSupplierPaymentField($filed_count,$stocks_transactions_id){
        
            $data['page_title'] = $this->page_title;
            $data['i']=$filed_count;
            $data['stocks_transactions_id']=$stocks_transactions_id;

            return \View::make('pages.supplier.ajax-paymentfield',$data);
    }


    /********************************************
    # SupplierPaymentAccountSelectBox
    *********************************************/
    public function SupplierPaymentAccountSelectBox($method_type){
        
            $data['page_title'] = $this->page_title;
            if($method_type=='bank')
                $data['payment_account'] = \App\Journal::GetLedgerAllChild('Bank Accounts',3);
            if($method_type=='cash')
                $data['payment_account'] = \App\Journal::GetLedgerAllChild('Cash-in-hand',3);

            return \View::make('pages.supplier.ajax-paymentAccountSelectOption',$data);
    }


    /********************************************
    # SupplierPaymentSubmit
    *********************************************/
    public function SupplierPaymentSubmit(){

        $now=date('Y-m-d H:i:s');
        $user=\Auth::user()->user_id;

        for ($i=1;$i<=\Request::input('supplier_payment_entry_field');$i++) {
                $rules_array['stocks_trasnsaction_id_'.$i] =  'Required';
                $rules_array['cost_center_id_'.$i] =  'Required';
                $rules_array['stocks_payment_amount_'.$i] =  'Required|numeric';
                
            }
            
            $rules_array['supplier_total_payment_amount'] =  'Required|numeric';
            $rules_array['supplier_payment_account_id'] =  'Required';
            $rules_array['supplier_payment_method'] =  'Required';
            $rules_array['supplier_paid_account'] =  'Required';
            $rules_array['supplier_payment_date'] = 'Required';
            $rules_array['supplier_pay_note'] = 'Required';


        $v = \Validator::make(\Request::all(),$rules_array);
        if($v->passes()){

            $supplier_payment_method = \Request::input('supplier_payment_method');
            $supplier_paid_account = \Request::input('supplier_paid_account');
            $supplier_payment_date = \Request::input('supplier_payment_date');
            $supplier_pay_note = \Request::input('supplier_pay_note');
            $supplier_payment_account_id  = \Request::input('supplier_payment_account_id');

            $supplier_journal = $supplier_payment_account_id;
            $supplier_journal_info = explode('.', $supplier_journal);

            $journal = $supplier_paid_account;
            $journal_info = explode('.', $journal);

            \DB::beginTransaction();

            try{
                $supplier_total_payment_amount =0;
                for ($i=1;$i<=\Request::input('supplier_payment_entry_field');$i++) {

                    $supplier_payment_amount = \Request::input('stocks_payment_amount_'.$i);
                    $cost_center_id = \Request::input('cost_center_id_'.$i);
                    $stocks_transactions_id = \Request::input('stocks_trasnsaction_id_'.$i);

                    /****General Transactin******/
                    $transaction_info = [
                                'transactions_date' =>$supplier_payment_date,
                                'transactions_naration' =>trim($supplier_pay_note),
                                'transaction_amount' =>$supplier_payment_amount,
                                'cost_center_id' =>$cost_center_id,
                                'posting_type' =>'payment',
                                'created_by' => \Auth::user()->user_id,
                                'updated_by' => \Auth::user()->user_id,
                                'created_at' => $now,
                                'updated_at' =>$now,
                            ];


                    $transactionRow = \DB::table('ltech_transactions')->insertGetId($transaction_info);
                    //$transactionRow = \DB::table('ltech_transactions')->latest()->first();
                    \App\System::EventLogWrite('insert,ltech_transactions',json_encode($transaction_info));

                    /****journal Debit for Supplier Accout Transaction****/
                    $journal_debit_info = [
                                'journal_date' =>$supplier_payment_date,
                                'journal_particular_id' =>$supplier_journal_info[0],
                                'journal_particular_name' =>$supplier_journal_info[2],
                                'journal_particular_depth'=>$supplier_journal_info[1],
                                'journal_particular_naration' =>trim($supplier_pay_note),
                                'journal_particular_amount_type'=>'debit',
                                'journal_particular_amount' =>$supplier_payment_amount,
                                'cost_center_id' =>$cost_center_id,
                                'posting_type' =>'purchase',
                                'transaction_id' =>$transactionRow,
                                'created_by' => \Auth::user()->user_id,
                                'updated_by' => \Auth::user()->user_id,
                                'created_at' => $now,
                                'updated_at' =>$now,
                            ];
                   
                    $journal_debit_data = \DB::table('ltech_general_journal')->insert($journal_debit_info);
                    
                    \App\System::EventLogWrite('insert,ltech_general_journal.journal_debit_data',json_encode($journal_debit_info));


                     /*journal Credit for Bank/Cash*/
                        

                        $journal_credit_info = [ 
                            'journal_date' =>$supplier_payment_date,
                            'journal_particular_id' =>$journal_info[0],
                            'journal_particular_name' =>$journal_info[2],
                            'journal_particular_depth'=>$journal_info[1],
                            'journal_particular_naration' =>trim($supplier_pay_note),
                            'journal_particular_amount_type'=>'credit',
                            'journal_particular_amount' =>$supplier_payment_amount,
                            'cost_center_id' =>$cost_center_id,
                            'posting_type' =>'purchase',
                            'transaction_id' =>$transactionRow,
                            'created_by' => \Auth::user()->user_id,
                            'updated_by' => \Auth::user()->user_id,
                            'created_at' => $now,
                            'updated_at' =>$now,
                        ];

                    $journal_credit_data = \DB::table('ltech_general_journal')->insert($journal_credit_info);
                    \App\System::EventLogWrite('insert,ltech_general_journal.journal_credit_data',json_encode($journal_credit_info));


                    


                    $stocks_credit_transaction_info = \App\Inventory::StocksCreditTransactionInfo($stocks_transactions_id);
                    $stocks_info = \App\Inventory::StockTransactionInfo($stocks_transactions_id);

                    /*Supplier Creddit Transaction Insert*/
                    
                    $supplier_credit_transacttion = [
                                                        'supplier_id' =>$supplier_journal_info[3],
                                                        'stocks_transactions_id' => $stocks_transactions_id,
                                                        'opening_stocks_credit_amount'=> $stocks_info->stocks_supplier_credit_amount,
                                                        'closing_stocks_credit_amount'=>$stocks_info->stocks_supplier_credit_amount,
                                                        'opening_stocks_debit_amount'=>$stocks_info->stocks_supplier_debit_amount,
                                                        'closing_stocks_debit_amount'=>$stocks_info->stocks_supplier_debit_amount+$supplier_payment_amount,
                                                        'opening_stocks_balance_amount'=>($stocks_info->stocks_supplier_balance_amount - $stocks_info->stocks_supplier_debit_amount),
                                                        'closing_stocks_balance_amount'=>($stocks_info->stocks_supplier_balance_amount -$supplier_payment_amount),
                                                        'transaction_date' =>$supplier_payment_date,
                                                        'transaction_amount'=>$supplier_payment_amount,
                                                        'referrence' =>$transactionRow,
                                                        'payment_account' =>$journal_info[0].$journal_info[1],
                                                        'payment_method'=>$supplier_payment_method,
                                                        'created_by' => \Auth::user()->user_id,
                                                        'updated_by' => \Auth::user()->user_id,
                                                        'created_at' => $now,
                                                        'updated_at' =>$now

                                                    ];
                     $supplier_credit_transacttion_insert = \DB::table('ltech_inventory_supplier_credit_transactions')->insert($supplier_credit_transacttion);
                     $supplier_credit_transacttion_lastrow = \DB::table('ltech_inventory_supplier_credit_transactions')->latest()->first();

                    \App\System::EventLogWrite('insert,ltech_inventory_supplier_credit_transactions',json_encode($supplier_credit_transacttion));
                    \App\Journal::TransactionMeta($transactionRow,'ltech_inventory_supplier_credit_transactions',$supplier_credit_transacttion_lastrow->supplier_credit_transactions_id);



                    /**Suplier Stocks Transaction Update **/

                    $stocks_supplier_debit_update_data = [
                                            'stocks_supplier_debit_amount'=>$stocks_info->stocks_supplier_debit_amount+$supplier_payment_amount,
                                            'stocks_supplier_balance_amount'=>($stocks_info->stocks_supplier_balance_amount - $supplier_payment_amount),
                                            'updated_by'=> \Auth::user()->user_id,
                                            'updated_at'=> $supplier_payment_date,
                                        ];

                    $inventory_supplier_credit_update = \DB::table('ltech_inventory_stocks_transactions')->where('stocks_transactions_id',$stocks_transactions_id)->update($stocks_supplier_debit_update_data);
                    \App\System::EventLogWrite('update,ltech_inventory_stocks_transactions',json_encode($stocks_supplier_debit_update_data));
                    \App\Journal::TransactionMeta($transactionRow,'ltech_inventory_stocks_transactions',$stocks_transactions_id);

                    

                    $supplier_total_payment_amount =$supplier_total_payment_amount+$supplier_payment_amount;

                }


                /**Suplier Credit Transaction Update **/
                    $supplier_credit_info = \DB::table('ltech_suppliers')->where('supplier_account_id',$supplier_journal_info[0].'.'.$supplier_journal_info[1])->first();

                

                    $credit_update_data = [
                                            'supplier_net_debit_amount'=>($supplier_credit_info->supplier_net_debit_amount+$supplier_total_payment_amount),
                                            'supplier_net_balance_amount'=>($supplier_credit_info->supplier_net_balance_amount-$supplier_total_payment_amount),
                                            'updated_by'=> \Auth::user()->user_id,
                                            'updated_at'=> $supplier_payment_date,
                                        ];
                $inventory_supplier_credit_update = \DB::table('ltech_suppliers')->where('supplier_account_id',$supplier_journal_info[0].'.'.$supplier_journal_info[1])->update($credit_update_data);
                \App\System::EventLogWrite('update,ltech_suppliers',json_encode($credit_update_data));
                \App\Journal::TransactionMeta($transactionRow,'ltech_suppliers',$supplier_journal_info[0].'.'.$supplier_journal_info[1]);


                /*Payment Voucher*/
                $data['supplier_info'] = $supplier_credit_info;
                $data['supplier_account_info'] = \App\Journal::JournalEntryinfo($supplier_journal_info[0],$supplier_journal_info[1]);
                $data['transactions_naration'] = trim($supplier_pay_note);
                $data['payment_method'] = $supplier_payment_method;
                $data['payment_account_info'] = \App\Journal::JournalEntryinfo($journal_info[0],$journal_info[1]);
                $data['payment_account_transaction'] =$supplier_credit_transacttion_lastrow;
                $data['supplier_total_payment_amount']=$supplier_total_payment_amount;
                \Session::put('supplier_payment_voucher_data',$data);
                \DB::commit();

                return \Redirect::to('/supplier/payment/voucher/view');


            }catch(\Exception $e){
                \Session::forget('supplier_payment_voucher_data');
                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/supplier/payment')->with('errormessage',$message);
            }

        }else return \Redirect::to('/supplier/payment')->withErrors($v->messages());
    }


    /********************************************
    # SupplierPaymentVoucherPage
    *********************************************/
    public function SupplierPaymentVoucherPage(){
        
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        if(\Session::has('supplier_payment_voucher_data') && !empty(\Session::get('supplier_payment_voucher_data'))){
            $data = \Session::get('supplier_payment_voucher_data');

            \Session::put('supplier_payment_voucher_pdf',$data);
            \Session::put('supplier_payment_voucher_print',$data);
    
             return \View::make('pages.supplier.payment-voucher',$data);
        }else  return \Redirect::to('/supplier/payment')->with('errormessage','No Payment voucher available');
            
    }


    /********************************************
    # SupplierPaymentVoucherPrint
    *********************************************/
    public function SupplierPaymentVoucherPrint(){
        
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        if(\Session::has('supplier_payment_voucher_print') && !empty(\Session::get('supplier_payment_voucher_print'))){
            $data = \Session::get('supplier_payment_voucher_print');
            return \View::make('pages.supplier.pdf.supplier-payment-voucher-print',$data);
        }else  return \Redirect::to('/supplier/payment')->with('errormessage','No Payment voucher available');
            
    }

    /********************************************
    # SupplierPaymentVoucherDownloadPDF
    *********************************************/
    public function SupplierPaymentVoucherDownloadPDF(){
        
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        if(\Session::has('supplier_payment_voucher_pdf') && !empty(\Session::get('supplier_payment_voucher_pdf'))){
            $data = \Session::get('supplier_payment_voucher_pdf');
            // return \View::make('pages.supplier.pdf.supplier-payment-voucher-pdf',$data);

            $pdf = \PDF::loadView('pages.supplier.pdf.supplier-payment-voucher-pdf',$data);
            $pdfname = time().'_payment_voucher.pdf';
            return $pdf->download($pdfname); 

        }else  return \Redirect::to('/supplier/payment')->with('errormessage','No Payment voucher available');
            
    }


    /********************************************
    # SupplierModalRegistrationConfirm
    *********************************************/
    public function SupplierModalRegistrationConfirm(){
        
        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->id;
        $rule = [
                'supplier_name' => 'Required|max:25',
                'supplier_company' => 'Required|max:25',
                'supplier_mobile' => 'Required|regex:/^[^0-9]*(88)?0/|max:11',
                'supplier_email' => 'Required|email',
                'supplier_tax_reg_no' => '',
                'supplier_address' => 'Required',
                'supplier_account_group'=> 'Required',
                'supplier_account_group_depth'=> 'Required',
                ];
        $back_page = \Request::input('back_page');
        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
            $supplier_company_slug = explode(' ', strtolower(\Request::input('supplier_company')));
            $supplier_company_slug = implode('_', $supplier_company_slug);

            
            
            \DB::beginTransaction();
            try{

                $supplier_company = \Request::input('supplier_company');
                $supplier_account_group =(int)(\Request::input('supplier_account_group'));
                $supplier_account_group_depth = (int)(\Request::input('supplier_account_group_depth'));

                
                $journal_info = \DB::table('ltech_ledger_group_'.$supplier_account_group_depth)->where('ledger_name','LIKE',$supplier_company)->first();

                $supplier_info = \DB::table('ltech_suppliers')->where('supplier_company','LIKE',$supplier_company)->first();

                if(empty($journal_info) && empty($supplier_info)){

                    /*Supplier account create*/
                    $supplier_account = \App\Journal::JournalEntryInsert($supplier_company,$supplier_company_slug,($supplier_account_group_depth+1),$supplier_account_group,0,0);

                    $journalupdate = \App\Journal::JournalUpdateParent($supplier_account_group_depth,$supplier_account_group);


                    \App\System::EventLogWrite('insert,ltech_ledger_group_'.$supplier_account_group_depth,$supplier_company_slug);
                    //\DB::commit();

                    $supplier_data = [
                    'supplier_account_id' => $supplier_account.'.'.($supplier_account_group_depth+1),
                    'supplier_company_slug' => $supplier_company_slug,
                    'supplier_company' =>\Request::input('supplier_company'),
                    'supplier_name' =>\Request::input('supplier_name'),
                    'supplier_mobile' =>\Request::input('supplier_mobile'),
                    'supplier_email' =>\Request::input('supplier_email'),
                    'supplier_tax_reg_no' =>\Request::input('supplier_tax_reg_no'),
                    'supplier_address' =>\Request::input('supplier_address'),
                    'supplier_status' =>1,
                    'created_at' =>$now,
                    'updated_at' =>$now,
                    'created_by' =>\Auth::user()->user_id,
                    'updated_by' =>\Auth::user()->user_id,
                    ];


                    \DB::table('ltech_suppliers')->insert($supplier_data);
                    \App\System::EventLogWrite('insert,ltech_suppliers',json_encode($supplier_data));
                    \DB::commit();

                    return \Redirect::to($back_page)->with('message',"Supplier Add Successfully!");


                }else  return \Redirect::to($back_page)->with('errormessage',"Info Already Exist !");


            }catch(\Exception $e){
                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to($back_page)->with('errormessage',"Something wrong !");
            }

        }else return \Redirect::to($back_page)->withErrors($v->messages());
    }


    /********************************************
    # SupplierPurchaseReturnPage
    *********************************************/
    public function SupplierPurchaseReturnPage(){
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $data['supplier_list'] = \DB::table('ltech_suppliers')->orderBy('supplier_id','desc')->get();

        if(isset($_GET['supplier_ref']) && !empty($_GET['supplier_ref']) && isset($_GET['supplier_id']) && !empty($_GET['supplier_id']) && isset($_GET['supplier']) && !empty($_GET['supplier']) ){

           $supplier_id=$_GET['supplier_id'];
           $supplier_info = \DB::table('ltech_suppliers')->where('supplier_id',$_GET['supplier_id'])->first();

           if(!empty($supplier_info) && ($_GET['supplier_ref']==$supplier_info->supplier_account_id)){
                $data['cost_centers'] = \DB::table('ltech_cost_centers')->get();
                $data['supplier_inventory_transactions'] = \App\Inventory::SupplierAllStockTransactionForReturn($supplier_id);
                $data['supplier_info'] = $supplier_info;
                $data['supplier_credit_transactions'] = \App\Inventory::SupplierCreditTransaction($supplier_id);

                if(isset($_GET['stocks_transactions_id']) && !empty($_GET['stocks_transactions_id'])){

                    $data['inventory_transaction_info'] = \DB::table('ltech_inventory_stocks_transactions')
                                                    ->where('ltech_inventory_stocks_transactions.stocks_transactions_id',$_GET['stocks_transactions_id'])
                                                    ->where('ltech_inventory_stocks_transactions.stocks_supplier_id',$supplier_id)
                                                    ->join('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                                    ->join('ltech_suppliers','ltech_inventory_stocks_transactions.stocks_supplier_id','=','ltech_suppliers.supplier_id')
                                                    ->first();
                }
            }

        }
        return \View::make('pages.supplier.supplier-purchase-return',$data);
    }

    /********************************************
    # SupplierPurchaseReturnSubmit
    *********************************************/
    public function SupplierPurchaseReturnSubmit(){

        $now=date('Y-m-d H:i:s');
        $user=\Auth::user()->user_id;
            
        $rules_array['return_product_quantity'] =  'Required|numeric';
        $rules_array['return_product_rate'] =  'Required|numeric';
        $rules_array['return_product_cost'] =  'Required|numeric';
        $rules_array['return_stocks_tran_id'] =  'Required';
        $rules_array['return_stocks_account_id'] = 'Required';

        $rules_array['supplier_payment_account_id'] =  'Required';
        $rules_array['supplier_return_date'] =  'Required';
        $rules_array['supplier_return_note'] = 'Required';


        $v = \Validator::make(\Request::all(),$rules_array);
        $parse_url =parse_url(\Request::fullUrl(), PHP_URL_QUERY);
        if($v->passes()){

            $supplier_return_note = \Request::input('supplier_return_note');
            $supplier_return_date = \Request::input('supplier_return_date');
            $supplier_payment_account_id  = \Request::input('supplier_payment_account_id');

            $return_product_quantity = \Request::input('return_product_quantity');
            $return_product_rate = \Request::input('return_product_rate');
            $return_product_blade_cost = \Request::input('return_product_cost');
            $return_product_cost = $return_product_quantity * $return_product_rate;
            $return_stocks_tran_id = \Request::input('return_stocks_tran_id');
            $cost_center_id = \Request::input('return_cost_center_id');

            $supplier_journal = $supplier_payment_account_id;
            $supplier_journal_info = explode('.', $supplier_journal);

            $stock_jurnal = \Request::input('return_stocks_account_id');
            $journal_info = explode('.', $stock_jurnal);

            

            \DB::beginTransaction();

            try{
                


                    #General Transactin
                    $transaction_info = [
                                'transactions_date' =>$supplier_return_date,
                                'transactions_naration' =>trim($supplier_return_note),
                                'transaction_amount' =>$return_product_cost,
                                'cost_center_id' =>$cost_center_id,
                                'posting_type' =>'purchase_return',
                                'created_by' => \Auth::user()->user_id,
                                'updated_by' => \Auth::user()->user_id,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                            ];


                    $transactionRow = \DB::table('ltech_transactions')->insertGetId($transaction_info);
                    \App\System::EventLogWrite('insert,ltech_transactions',json_encode($transaction_info));

                    #journal Debit for Supplier Account Transaction
                
                    $journal_debit_info = [
                                'journal_date' =>$supplier_return_date,
                                'journal_particular_id' =>$supplier_journal_info[0],
                                'journal_particular_name' =>$supplier_journal_info[3],
                                'journal_particular_depth'=>$supplier_journal_info[1],
                                'journal_particular_naration' =>trim($supplier_return_note),
                                'journal_particular_amount_type'=>'debit',
                                'journal_particular_amount' =>$return_product_cost,
                                'cost_center_id' =>$cost_center_id,
                                'posting_type' =>'purchase_return',
                                'transaction_id' =>$transactionRow,
                                'created_by' => \Auth::user()->user_id,
                                'updated_by' => \Auth::user()->user_id,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                            ];
                   
                    $journal_debit_data = \DB::table('ltech_general_journal')->insert($journal_debit_info);
                    
                    \App\System::EventLogWrite('insert,ltech_general_journal.journal_debit_data',json_encode($journal_debit_info));


                    #Stocks Credit Transaction
                     $inventory_stocks_info = \DB::table('ltech_inventory_stocks_transactions')
                                                    ->where('ltech_inventory_stocks_transactions.stocks_transactions_id',$return_stocks_tran_id)
                                                    ->join('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                                    ->join('ltech_suppliers','ltech_inventory_stocks_transactions.stocks_supplier_id','=','ltech_suppliers.supplier_id')
                                                    ->first();
                        $journal = $inventory_stocks_info->item_account_id;
                        $journal_info = explode('.', $journal);

                        $journal_credit_info = [
                                    'journal_date' =>$supplier_return_date,
                                    'journal_particular_id' =>$journal_info[0],
                                    'journal_particular_name' =>$inventory_stocks_info->item_name,
                                    'journal_particular_depth'=>$journal_info[1],
                                    'journal_particular_naration' =>$supplier_return_note,
                                    'journal_particular_amount_type'=>'credit',
                                    'journal_particular_amount' =>$return_product_cost,
                                    'cost_center_id' =>$cost_center_id,
                                    'posting_type' =>'purchase_return',
                                    'transaction_id' =>$transactionRow,
                                    'created_by' => \Auth::user()->user_id,
                                    'updated_by' => \Auth::user()->user_id,
                                    'created_at' =>$now,
                                    'updated_at' =>$now,
                                ];
                       
                        $journal_credit_data = \DB::table('ltech_general_journal')->insert($journal_credit_info);
                        
                        \App\System::EventLogWrite('insert,ltech_general_journal.journal_credit_data',json_encode($journal_credit_info));


                    $stocks_credit_transaction_info = \App\Inventory::StocksCreditTransactionInfo($return_stocks_tran_id);
                    $stocks_info = \App\Inventory::StockTransactionInfo($return_stocks_tran_id);

                    #Stocks Transaction Insert
                    $stocks_transactions_data = [
                        'inventory_stock_id' =>$inventory_stocks_info->inventory_stock_id,
                        'stocks_transaction_date' => $supplier_return_date,
                        'item_category_id' =>$inventory_stocks_info->item_category_id,
                        'stocks_supplier_id' =>$inventory_stocks_info->stocks_supplier_id,
                        'stocks_transaction_desc' =>$supplier_return_note,
                        'item_quantity_unit' =>$inventory_stocks_info->item_quantity_unit,
                        'stocks_transaction_type' =>'return',
                        'opening_transaction_stocks_quantity' =>$inventory_stocks_info->stocks_onhand,
                        'transaction_stocks_quantity' =>$return_product_quantity,
                        'closing_transaction_stocks_quantity' =>$inventory_stocks_info->stocks_onhand-$return_product_quantity,
                        'stocks_quantity_rate' =>$return_product_rate,
                        'opening_transaction_stocks_cost' =>$inventory_stocks_info->stocks_total_cost,
                        'stocks_quantity_cost' => $return_product_cost,
                        'closing_transaction_stocks_cost' =>$inventory_stocks_info->stocks_total_cost-$return_product_cost,
                        'cost_center_id'=> $cost_center_id,
                        'stocks_supplier_debit_amount'=>$inventory_stocks_info->stocks_supplier_debit_amount+$return_product_cost,
                        'stocks_supplier_balance_amount'=>$inventory_stocks_info->supplier_net_balance_amount-$return_product_cost,
                        'referrence' =>$transactionRow,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                     ];

                     $stocks_transactions_previous_data = [
                        'return_status' =>1,
                     ];

                      #stocks transaction insert
                    $stocks_transactions_data_update = \DB::table('ltech_inventory_stocks_transactions')->where('stocks_transactions_id',$inventory_stocks_info->stocks_transactions_id)->update($stocks_transactions_previous_data);
                    $stocks_transactions_data_insert = \DB::table('ltech_inventory_stocks_transactions')->insertGetId($stocks_transactions_data);
                    \App\System::EventLogWrite('insert,ltech_inventory_stocks_transactions',json_encode($stocks_transactions_data));
                    \App\Journal::TransactionMeta($transactionRow,'ltech_inventory_stocks_transactions',$stocks_transactions_data_insert);

                    if(($return_product_quantity>($inventory_stocks_info->stocks_onhand)) || ($return_product_blade_cost != ($return_product_quantity*$return_product_rate))){

                        \DB::rollback();
                        return \Redirect::to('/supplier/purchase/return?'.$parse_url)->with('errormessage','Something wrong in quantity or rate or cost.');
                    }


                     #Stocks Transaction Update
                     $stocks_update_data = [
                                        'stocks_onhand' =>($inventory_stocks_info->stocks_onhand-$return_product_quantity),
                                        'stocks_total_quantity' =>$inventory_stocks_info->stocks_total_quantity-$return_product_quantity,
                                        'stocks_total_cost' =>$inventory_stocks_info->stocks_total_cost-$return_product_cost,
                                        'updated_by' =>\Auth::user()->user_id,
                                        'updated_at' =>$now,
                                     ];
                   
                    #stocks detail update
                    $stocks_update = \DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stocks_info->inventory_stock_id)->update($stocks_update_data);

                    \App\System::EventLogWrite('update,ltech_inventory_stocks',json_encode($stocks_update_data));
                    \App\Journal::TransactionMeta($transactionRow,'ltech_inventory_stocks',$inventory_stocks_info->inventory_stock_id);



                    #Supplier Creddit Transaction Insert
                    
                    $supplier_credit_transacttion = [
                                                        'supplier_id' =>$supplier_journal_info[2],
                                                        'stocks_transactions_id' => $return_stocks_tran_id,
                                                        'opening_stocks_credit_amount'=> $inventory_stocks_info->supplier_net_credit_amount,
                                                        'closing_stocks_credit_amount'=>$inventory_stocks_info->supplier_net_credit_amount-$return_product_cost,
                                                        'opening_stocks_debit_amount'=>$inventory_stocks_info->supplier_net_debit_amount,
                                                        'closing_stocks_debit_amount'=>$inventory_stocks_info->supplier_net_debit_amount,
                                                        'opening_stocks_balance_amount'=>($inventory_stocks_info->supplier_net_balance_amount),
                                                        'closing_stocks_balance_amount'=>($inventory_stocks_info->supplier_net_balance_amount - $return_product_cost),
                                                        'transaction_date' =>$supplier_return_date,
                                                        'transaction_amount'=>$return_product_cost,
                                                        'referrence' =>$transactionRow,
                                                        'created_by' => \Auth::user()->user_id,
                                                        'updated_by' => \Auth::user()->user_id,
                                                        'created_at' => $now,
                                                        'updated_at' =>$now

                                                    ];
                     $supplier_credit_transacttion_insert = \DB::table('ltech_inventory_supplier_credit_transactions')->insertGetId($supplier_credit_transacttion);
                    

                    \App\System::EventLogWrite('insert,ltech_inventory_supplier_credit_transactions',json_encode($supplier_credit_transacttion));
                    \App\Journal::TransactionMeta($transactionRow,'ltech_inventory_supplier_credit_transactions',$supplier_credit_transacttion_insert);



                    #Suplier Stocks Transaction Update
                    $stocks_supplier_credit_update_data = [
                                            'supplier_net_credit_amount'=>$inventory_stocks_info->supplier_net_credit_amount-$return_product_cost,
                                            'supplier_net_balance_amount'=>($inventory_stocks_info->supplier_net_balance_amount - $return_product_cost),
                                            'updated_by'=> \Auth::user()->user_id,
                                            'updated_at'=> $supplier_return_date,
                                        ];
                    $inventory_supplier_credit_update = \DB::table('ltech_suppliers')->where('supplier_id',$supplier_journal_info[2])->update($stocks_supplier_credit_update_data);
                    \App\System::EventLogWrite('update,ltech_suppliers',json_encode($stocks_supplier_credit_update_data));
                    \App\Journal::TransactionMeta($transactionRow,'ltech_suppliers',$supplier_journal_info[2]);

                    
                
                \Session::put('supplier_purchare_return_stocks',$stocks_transactions_data_insert);
                \DB::commit();

                return \Redirect::to('/supplier/purchase/return/invoice/stocks-tran-'.$stocks_transactions_data_insert);


            }catch(\Exception $e){
                \Session::forget('supplier_purchare_return_stocks');
                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/supplier/purchase/return?'.$parse_url)->with('errormessage',$message);
            }

        }else return \Redirect::to('/supplier/purchase/return?'.$parse_url)->withErrors($v->messages());
    }


    /********************************************
    ## SupplierPurchaseReturnInvoicePage
    *********************************************/
    public function SupplierPurchaseReturnInvoicePage($stocks_transactions_id){


         $inventory_stocks_info = \DB::table('ltech_inventory_stocks_transactions')
                                                    ->where('ltech_inventory_stocks_transactions.stocks_transactions_id',$stocks_transactions_id)
                                                    ->join('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                                    ->join('ltech_suppliers','ltech_inventory_stocks_transactions.stocks_supplier_id','=','ltech_suppliers.supplier_id')
                                                    ->first();

        if(empty($inventory_stocks_info))
            return \Redirect::to('/error/request')->with('errormessage','Invalid Transaction ID');
        $data['supplier_info'] = $inventory_stocks_info;
        $data['all_stocks_transaction'] = $inventory_stocks_info;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        return \View::make('pages.supplier.supplier-return-invoice',$data);
                
    }

    /********************************************
    ## SupplierPurchaseReturnInvoiceDownloadPDF
    *********************************************/
    public function SupplierPurchaseReturnInvoiceDownloadPDF($stocks_transactions_id){


         $inventory_stocks_info = \DB::table('ltech_inventory_stocks_transactions')
                                                    ->where('ltech_inventory_stocks_transactions.stocks_transactions_id',$stocks_transactions_id)
                                                    ->join('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                                    ->join('ltech_suppliers','ltech_inventory_stocks_transactions.stocks_supplier_id','=','ltech_suppliers.supplier_id')
                                                    ->first();

        if(empty($inventory_stocks_info))
            return \Redirect::to('/error/request')->with('errormessage','Invalid Transaction ID');
        $data['supplier_info'] = $inventory_stocks_info;
        $data['all_stocks_transaction'] = $inventory_stocks_info;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        //return \View::make('pages.supplier.pdf.supplier-purchase-return-pdf',$data);

        $pdf = \PDF::loadView('pages.supplier.pdf.supplier-purchase-return-pdf',$data);
        $pdfname = time().'_purchase_return.pdf';
        return $pdf->download($pdfname); 
                
    }

    /********************************************
    ## SupplierPurchaseReturnInvoicePrint
    *********************************************/
    public function SupplierPurchaseReturnInvoicePrint($stocks_transactions_id){


         $inventory_stocks_info = \DB::table('ltech_inventory_stocks_transactions')
                                                    ->where('ltech_inventory_stocks_transactions.stocks_transactions_id',$stocks_transactions_id)
                                                    ->join('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                                    ->join('ltech_suppliers','ltech_inventory_stocks_transactions.stocks_supplier_id','=','ltech_suppliers.supplier_id')
                                                    ->first();

        if(empty($inventory_stocks_info))
            return \Redirect::to('/error/request')->with('errormessage','Invalid Transaction ID');
        $data['supplier_info'] = $inventory_stocks_info;
        $data['all_stocks_transaction'] = $inventory_stocks_info;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        return \View::make('pages.supplier.pdf.supplier-purchase-return-print',$data);
                
    }


    /********************************************
    ## SupplierListPage
    *********************************************/
    public function SupplierListPage(){

        $supplier_lists=\DB::table('ltech_suppliers')
                ->paginate(10);
        $supplier_lists->setPath(url('/supplier/list'));
        $supplier_pagination = $supplier_lists->render();
        $data['supplier_pagination'] = $supplier_pagination;
        $data['supplier_lists'] = $supplier_lists;  
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.supplier.supplier-list-page',$data);
    }


    /********************************************
    ## EditSupplierPage
    *********************************************/
    public function EditSupplierPage($supplier_id){
        $selected_supplier_list=\DB::table('ltech_suppliers')->where('supplier_id',$supplier_id)->first();
        $data['selected_supplier_list'] = $selected_supplier_list;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.supplier.edit-supplier-details',$data);

    }

    /********************************************
    ## UpdateSupplier
    *********************************************/
    public function UpdateSupplier($supplier_id){
        

        $rule = [
        'supplier_name' => 'Required',
        'supplier_mobile' => 'Required|regex:/^[^0-9]*(88)?0/|max:11',
        'supplier_email' => 'Required|email',
        'supplier_tax_reg_no' => 'Required',
        'supplier_address' => 'Required',
        ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){ 

            $now=date('Y-m-d H:i:s');
            $user =\Auth::user()->user_id;

            $update_supplier_data = [

                'supplier_name' =>\Request::input('supplier_name'),
                'supplier_mobile' =>\Request::input('supplier_mobile'),
                'supplier_email' =>\Request::input('supplier_email'),
                'supplier_tax_reg_no' =>\Request::input('supplier_tax_reg_no'),
                'supplier_address' =>\Request::input('supplier_address'),
                'updated_at' =>$now,
                'updated_by' =>\Auth::user()->user_id,

            ];

            try{
                \DB::table('ltech_suppliers')->where('supplier_id',$supplier_id)->update($update_supplier_data);
                \App\System::EventLogWrite('update',json_encode($update_supplier_data));
            }catch(\Exception  $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::back()->with('message','Something wrong !!');
            }

            return \Redirect::to('/supplier/list')->with('message','Supplier Updated Successfully.');
        }else return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());

    }


############################### End Controller ###########################

}
