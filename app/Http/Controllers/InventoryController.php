<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*******************************
#
## Inventory Controller
#
*******************************/

class InventoryController extends Controller
{
    public function __construct(){
	    $this->page_title = \Request::route()->getName();
        $description = \Request::route()->getAction();
        $this->page_desc = isset($description['desc']) ?  $description['desc']:'';
	    \App\System::AccessLogWrite();
    }


    /********************************************
    ## InventoryDashbordPage
    *********************************************/
    public function InventoryDashbordPage(){

        $data['page_title'] = $this->page_title;
        return \View::make('pages.inventory.dashboard-inventory',$data);
    }



    /********************************************
    ## InventoryStocksPurchasePage
    *********************************************/
    public function InventoryStocksPurchasePage(){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        $data['supplier_list'] = \App\Journal::GetLedgerAllChild('Accounts Payable',3);
        $data['cost_centers'] = \DB::table('ltech_cost_centers')->get();
        $data['inventory_stocks_account'] = \App\Journal::GetLedgerAllChild('Stock-in-hand',3);
        $data['account_payable'] = \App\Journal::GetLedgerChildByName('Accounts Payable',3);
        $data['account_stock_in_hand'] = \App\Journal::GetLedgerChildByName('Stock-in-hand',3);
        $data['inventory_stocks_list'] = \DB::table('ltech_inventory_stocks')
                                                ->leftjoin('ltech_item_categories','ltech_inventory_stocks.item_category_id','=','ltech_item_categories.item_category_id')
                                                ->select('ltech_inventory_stocks.*','ltech_item_categories.*')
                                                ->OrderBy('ltech_inventory_stocks.inventory_stock_id','desc')
                                                ->get();
                                         
       return \View::make('pages.inventory.stocks-purchase',$data);
    }

    /********************************************
    ## AjaxInventoryStocksFieldEntry
    *********************************************/
    public function AjaxInventoryStocksFieldEntry($filed_count){

        $data['i']=$filed_count;      
        return \View::make('pages.inventory.ajax-stocks-purchase',$data);
    }


    /********************************************
    ## InventoryLedgerAccountCreate
    *********************************************/
    public function InventoryLedgerAccountCreate(){
        $now=date('Y-m-d H:i:s');

        $rules=array(
            'stock_in_hand_group' => 'Required',
            'account_stock_in_hand_group_depth' => 'Required',
            'stock_account_name' => 'Required',
            'stock_debit_amount' => 'numeric',
            'stock_credit_amount'=> 'numeric'
            );

        $v=\Validator::make(\Request::all(), $rules);
        $back_page = \Request::input('back_page');
        if($v->passes()){

            \DB::beginTransaction();
            try{

                $stock_in_hand_group = \Request::input('stock_in_hand_group');
                $account_stock_in_hand_group_depth = \Request::input('account_stock_in_hand_group_depth');
                $stock_account_name = \Request::input('stock_account_name');


                $journal_info = \DB::table('ltech_ledger_group_'.$account_stock_in_hand_group_depth)->where('ledger_name','LIKE',$stock_in_hand_group)->first();

                $stock_account_info = \DB::table('ltech_ledger_group_'.($account_stock_in_hand_group_depth+1))->where('ledger_name','LIKE',$stock_account_name)->first();

                if(empty($journal_info) && empty($stock_account_info)){

                    $stock_account_name_slug = explode(' ', strtolower(trim($stock_account_name) ));
                    $stock_account_name_slug = implode('_', $stock_account_name_slug);

                    $stock_debit_amount = !empty(\Request::input('stock_debit_amount')) ? \Request::input('stock_debit_amount'):0;

                    $stock_credit_amount = !empty(\Request::input('stock_credit_amount')) ? \Request::input('stock_credit_amount'):0;

                    /*Stock account create*/
                    $supplier_account = \App\Journal::JournalEntryInsert($stock_account_name,$stock_account_name_slug,($account_stock_in_hand_group_depth+1),$stock_in_hand_group,$stock_debit_amount,$stock_credit_amount);
             
                    $journalupdate = \App\Journal::JournalUpdateParent($account_stock_in_hand_group_depth,$stock_in_hand_group);
                    
                    \DB::commit();

                    return \Redirect::to($back_page)->with('message',"Stock Account Created Successfully!");
                    
                }else return \Redirect::to($back_page)->with('message',"Stock Account Created Successfully!");

            }catch(\Exception $e){

                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::to($back_page)->with('errormessage','Something wrong happend Invoice Puchase');
            }


        }else return \Redirect::to($back_page)->withErrors($v->messages());
       
    }


    /********************************************
    ## InventoryStocksPurchaseInsert
    *********************************************/
    public function InventoryStocksPurchaseInsert(){

         for ($i=1;$i<=\Request::input('stocks_entry_field');$i++) {
                $rules_array['inventory_stocks_id_'.$i] =  'Required|not_in:0';
                //$rules_array['stocks_account_id_'.$i] =  'Required|not_in:0';
                $rules_array['transaction_stocks_quantity_'.$i] =  'Required|numeric';
                $rules_array['stocks_quantity_rate_'.$i] =  'Required|numeric';
                $rules_array['stocks_quantity_cost_'.$i] =  'Required|numeric';
            }
            
            $rules_array['supplier_id'] =  'Required';
            $rules_array['cost_center_id'] =  'Required';
            $rules_array['stocks_purchase_date'] =  'Required|date';
            $rules_array['purchase_desc'] = 'Required';

        $v= \Validator::make(\Request::all(), $rules_array);

        if($v->passes()){

            $supplier_detail = explode('.', \Request::input('supplier_id'));
            $supplier_account_id = $supplier_detail[0].'.'.$supplier_detail[1];
            $supplier_info = \DB::table('ltech_suppliers')->where('supplier_account_id',$supplier_account_id)->first();

            $supplier_id = $supplier_info->supplier_id;
            $supplier_ledger = $supplier_detail[0];

            $cost_center_id = \Request::input('cost_center_id');
            $stocks_purchase_date = \Request::input('stocks_purchase_date');
            $purchase_desc = trim(\Request::input('purchase_desc'));

            $now= date('Y-m-d H:i:s');

         

            \DB::beginTransaction();

            try{
                 $transaction_total_amount = 0;
                for($i=1;$i<=\Request::input('stocks_entry_field');$i++){
                    $transaction_total_amount = $transaction_total_amount + (\Request::input('transaction_stocks_quantity_'.$i) * \Request::input('stocks_quantity_rate_'.$i) );
                }


                 #General Transaction Insert
                $transaction_info = [
                            'transactions_date' =>$stocks_purchase_date,
                            'transactions_naration' =>$purchase_desc,
                            'transaction_amount' =>$transaction_total_amount,
                            'cost_center_id' =>$cost_center_id,
                            'posting_type' =>'purchase',
                            'created_by' => \Auth::user()->user_id,
                            'updated_by' => \Auth::user()->user_id,
                        ];


                $transactionRow = \DB::table('ltech_transactions')->insertGetId($transaction_info);
                //$transactionRow = \DB::table('ltech_transactions')->latest()->first();
                \App\System::EventLogWrite('insert,ltech_transactions',json_encode($transaction_info));

            }catch(\Exception $e){

                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/inventory/purchase/invoice')->with('errormessage',$message);
            }

            /***Start Stock Loop*/
            for($i=1;$i<=\Request::input('stocks_entry_field');$i++){

                $inventory_stock_id = \Request::input('inventory_stocks_id_'.$i);

                $inventory_stocks_info = \DB::table('ltech_inventory_stocks')
                                        ->where('inventory_stock_id',$inventory_stock_id)
                                        ->first();

                if(!empty($inventory_stocks_info) && !empty($supplier_info)){

                    $stocks_total_quantity = ($inventory_stocks_info->stocks_total_quantity + \Request::input('transaction_stocks_quantity_'.$i));
                    $stocks_quantity_cost = (\Request::input('transaction_stocks_quantity_'.$i) * \Request::input('stocks_quantity_rate_'.$i) );
                    $stocks_total_cost = $inventory_stocks_info->stocks_total_cost + $stocks_quantity_cost;


                    $stocks_transactions_data = [
                        'inventory_stock_id' =>$inventory_stock_id,
                        'stocks_transaction_date' => $stocks_purchase_date,
                        'item_category_id' =>$inventory_stocks_info->item_category_id,
                        'stocks_supplier_id' =>$supplier_id,
                        'stocks_transaction_desc' =>$purchase_desc,
                        'item_quantity_unit' =>$inventory_stocks_info->item_quantity_unit,
                        'stocks_transaction_type' =>'inwards',
                        'opening_transaction_stocks_quantity' =>$inventory_stocks_info->stocks_total_quantity,
                        'transaction_stocks_quantity' =>\Request::input('transaction_stocks_quantity_'.$i),
                        'closing_transaction_stocks_quantity' =>$stocks_total_quantity,
                        'stocks_quantity_rate' =>\Request::input('stocks_quantity_rate_'.$i),
                        'opening_transaction_stocks_cost' =>$inventory_stocks_info->stocks_total_cost,
                        'stocks_quantity_cost' => $stocks_quantity_cost,
                        'closing_transaction_stocks_cost' =>$stocks_total_cost,
                        'cost_center_id'=> $cost_center_id,
                        'stocks_supplier_credit_amount'=>$stocks_quantity_cost,
                        'stocks_supplier_balance_amount'=>$stocks_quantity_cost,
                        'referrence'=>$transactionRow,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                     ];



                     $stocks_update_data = [
                                        'stocks_onhand' =>($inventory_stocks_info->stocks_onhand +\Request::input('transaction_stocks_quantity_'.$i)),
                                        'stocks_total_quantity' =>$stocks_total_quantity,
                                        'stocks_total_cost' =>$stocks_total_cost,
                                        'updated_by' =>\Auth::user()->user_id,
                                        'updated_at' =>$now,
                                     ];

                    try{

                        /*stocks transaction insert*/
                        $stocks_transactions_data_insert = \DB::table('ltech_inventory_stocks_transactions')->insertGetId($stocks_transactions_data);
                        \App\System::EventLogWrite('insert,ltech_inventory_stocks_transactions',json_encode($stocks_transactions_data));
                        \App\Journal::TransactionMeta($transactionRow,'ltech_inventory_stocks_transactions',$stocks_transactions_data_insert);


                        $stocks_transactions_id = \DB::table('ltech_inventory_stocks_transactions')->latest()->first();

                       
                        $purchare_stocks_transaction[] = $stocks_transactions_data_insert;
                            

                       

                        /*stocks detail update*/
                        $stocks_update = \DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stocks_info->inventory_stock_id)->update($stocks_update_data);

                        \App\System::EventLogWrite('update,ltech_inventory_stocks',json_encode($stocks_update_data));
                        \App\Journal::TransactionMeta($transactionRow,'ltech_inventory_stocks', $inventory_stocks_info->inventory_stock_id);


                            
                        /****journal Debit Transaction****/
                        $journal = $inventory_stocks_info->item_account_id;
                        $journal_info = explode('.', $journal);
                        

                        $journal_debit_info = [
                                    'journal_date' =>$stocks_purchase_date,
                                    'journal_particular_id' =>$journal_info[0],
                                    'journal_particular_name' =>$inventory_stocks_info->item_name,
                                    'journal_particular_depth'=>$journal_info[1],
                                    'journal_particular_naration' =>$purchase_desc,
                                    'journal_particular_amount_type'=>'debit',
                                    'journal_particular_amount' =>$stocks_quantity_cost,
                                    'cost_center_id' =>$cost_center_id,
                                    'posting_type' =>'purchase',
                                    'transaction_id' =>$transactionRow,
                                    'created_by' => \Auth::user()->user_id,
                                    'updated_by' => \Auth::user()->user_id,
                                ];
                       
                        $journal_debit_data = \DB::table('ltech_general_journal')->insert($journal_debit_info);
                        
                        \App\System::EventLogWrite('insert,ltech_general_journal.journal_debit_data',json_encode($journal_debit_info));
                        
                        

                    }catch(\Exception $e){
                        \Session::forget('purchare_stocks_transaction');
                        \DB::rollback();
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/inventory/purchase/invoice')->with('errormessage',$message);
                    }



                }else{
                    \DB::rollback();
                    \Session::forget('purchare_stocks_transaction');
                    return \Redirect::to('/inventory/purchase/invoice')->with('errormessage',"Invalid Stocks");
                } 

            }
            /***End Stock Loop*/


            try{
                /**Suplier Credit **/
                 $supplier_credit_info = \DB::table('ltech_suppliers')->where('supplier_id',$supplier_id)->first();

                

                    $credit_update_data = [
                                            'supplier_net_credit_amount'=>$transaction_total_amount+$supplier_credit_info->supplier_net_credit_amount,
                                            'supplier_net_balance_amount'=>$transaction_total_amount+$supplier_credit_info->supplier_net_balance_amount,
                                            'updated_by'=> \Auth::user()->user_id,
                                            'updated_at'=> $now,
                                        ];


                    $inventory_supplier_credit_update = \DB::table('ltech_suppliers')->where('supplier_id',$supplier_id)->update($credit_update_data);
                    \App\System::EventLogWrite('update,ltech_suppliers',json_encode($credit_update_data));
                    \App\Journal::TransactionMeta($transactionRow,'ltech_suppliers',$supplier_id);




                 /*Supplier Account Payable*/
                    $journal_credit_info = [ 
                        'journal_date' =>$stocks_purchase_date,
                        'journal_particular_id' =>$supplier_detail[0],
                        'journal_particular_name' =>$supplier_detail[3],
                        'journal_particular_depth'=>$supplier_detail[1],
                        'journal_particular_naration' =>$purchase_desc,
                        'journal_particular_amount_type'=>'credit',
                        'journal_particular_amount' =>$transaction_total_amount,
                        'cost_center_id' =>$cost_center_id,
                        'posting_type' =>'purchase',
                        'transaction_id' =>$transactionRow,
                        'created_by' => \Auth::user()->user_id,
                        'updated_by' => \Auth::user()->user_id,
                    ];

                $journal_credit_data = \DB::table('ltech_general_journal')->insert($journal_credit_info);
                \App\System::EventLogWrite('insert,ltech_general_journal.journal_credit_data',json_encode($journal_credit_info));
                \Session::put('purchare_supplier_detail',$supplier_detail);
                \Session::put('purchare_stocks_transaction',$purchare_stocks_transaction);
                \DB::commit();

                return \Redirect::to('/inventory/purchase/invoice/view')->with('message','Stocks successfully saved.');
                    

            }catch(\Exception $e){
                \Session::forget('purchare_stocks_transaction');
                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/inventory/purchase/invoice')->with('errormessage',$message);
            }


                        
        }else return \Redirect::to('/inventory/purchase/invoice')->withErrors($v->messages());
        
    }


    /********************************************
    ## InventoryStocksPurchaseBillPage
    *********************************************/
    public function InventoryStocksPurchaseBillPage(){
        
        if(\Session::has('purchare_stocks_transaction') && \Session::has('purchare_supplier_detail') && !empty(\Session::get('purchare_stocks_transaction')) && !empty(\Session::get('purchare_supplier_detail')) ){
            
            $purchare_stocks_transaction =  \Session::get('purchare_stocks_transaction');
            $all_stocks_transaction = array();
            if(!empty($purchare_stocks_transaction)){
                foreach ($purchare_stocks_transaction as $key => $stocks_transaction) {
                   $all_stocks_transaction[] = \App\Inventory::StockTransactionInfo($stocks_transaction);
                }

                $supplier_data = \Session::get('purchare_supplier_detail');

                $data['supplier_info'] = \DB::table('ltech_suppliers')->where('supplier_account_id',$supplier_data[0].'.'.$supplier_data[1])->first();
                $data['page_title'] = $this->page_title;
                $data['page_desc'] = $this->page_desc;
                $data['all_stocks_transaction'] = $all_stocks_transaction;

                /*\Session::forget('purchare_stocks_transaction');
                \Session::forget('purchare_supplier_detail');*/

                \Session::put('purchare_stocks_transaction_print',$all_stocks_transaction);
                \Session::put('purchare_supplier_detail_print',$data['supplier_info']);

                \Session::put('purchare_stocks_transaction_downlaod',$all_stocks_transaction);
                \Session::put('purchare_supplier_detail_downlaod',$data['supplier_info']);


               return \View::make('pages.inventory.purchase-bill',$data);

            }else \Redirect::to('/inventory/purchase/invoice')->with('errormessage','No Invoice available');

        }else return \Redirect::to('/inventory/purchase/invoice')->with('errormessage','No Invoice available');       
        
    }


    /********************************************
    ## InventoryStocksPurchaseBillPrint
    *********************************************/
    public function InventoryStocksPurchaseBillPrint(){

        if(\Session::has('purchare_stocks_transaction_print') && \Session::has('purchare_supplier_detail_print') && !empty(\Session::get('purchare_stocks_transaction_print')) && !empty(\Session::get('purchare_supplier_detail_print')) ){

            $data['page_title'] = $this->page_title;
            $data['page_desc'] = $this->page_desc;
            $data['all_stocks_transaction'] = \Session::get('purchare_stocks_transaction_print');
            $data['supplier_info'] = \Session::get('purchare_supplier_detail_print');


            return \View::make('pages.inventory.pdf.stocks-purchase-invoice-print',$data);

            
        }else return \Redirect::to('/error/request');        
    }


    /********************************************
    ## InventoryStocksPurchaseBillDownloadPDF
    *********************************************/
    public function InventoryStocksPurchaseBillDownloadPDF(){

        if(\Session::has('purchare_stocks_transaction_downlaod') && \Session::has('purchare_supplier_detail_downlaod') && !empty(\Session::get('purchare_stocks_transaction_downlaod')) && !empty(\Session::get('purchare_supplier_detail_downlaod')) ){

            $data['page_title'] = $this->page_title;
            $data['page_desc'] = $this->page_desc;
            $data['all_stocks_transaction'] = \Session::get('purchare_stocks_transaction_downlaod');
            $data['supplier_info'] = \Session::get('purchare_supplier_detail_downlaod');


            //return \View::make('pages.inventory.pdf.stocks-purchase-invoice-pdf',$data);

            $pdf = \PDF::loadView('pages.inventory.pdf.stocks-purchase-invoice-pdf',$data);
            $pdfname = time().'_purchase_bill.pdf';
            return $pdf->download($pdfname); 

        }else return \Redirect::to('/error/request');        
    }


    /********************************************
    ## InventoryStocksOnProductionPage
    *********************************************/
    public function InventoryStocksOnProductionPage(){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $data['supplier_list'] = \DB::table('ltech_suppliers')->get();
        $data['cost_centers'] = \DB::table('ltech_cost_centers')->get();
        $data['inventory_stocks_list'] = \DB::table('ltech_inventory_stocks')->get();

      
        return \View::make('pages.inventory.stocks-on-production',$data);
    }

    /********************************************
    ## AjaxInventoryStocksProductionFieldEntry
    *********************************************/
    public function AjaxInventoryStocksProductionFieldEntry($filed_count){

        $data['i']=$filed_count;      
        return \View::make('pages.inventory.ajax-production-stocks',$data);
    }


    /********************************************
    ## AjaxInventoryStocksInfo
    *********************************************/
    public function AjaxInventoryStocksInfo($inventory_stock_id){

        $inventory_stocks_info = \DB::table('ltech_inventory_stocks')
        ->where('inventory_stock_id',$inventory_stock_id)
        ->first();
        $stocks_onhand = 0;
        if(!empty($inventory_stocks_info)){

            $stocks_inhand= $inventory_stocks_info->stocks_onhand.' '.$inventory_stocks_info->item_quantity_unit;
            return ['stocks_onhand'=>$stocks_inhand];

        }else return ['stocks_onhand'=>''];
       
    }


    /********************************************
    ## InventoryStocksOnProductionInsert
    *********************************************/
    public function InventoryStocksOnProductionInsert(){

       for ($i=1;$i<=\Request::input('production_stocks_entry_field');$i++) {
            $rules_array['production_inventory_stocks_id_'.$i] =  'Required|not_in:0';
            $rules_array['production_stocks_transaction_desc_'.$i] =  'Required';
            $rules_array['production_transaction_stocks_quantity_'.$i] =  'Required|numeric';
        }

        $rules_array['stocks_employee_id'] = 'Required';
        $rules_array['cost_center_id'] = 'Required';
        $rules_array['production_stocks_entry_date'] =  'Required|date';

        $v= \Validator::make(\Request::all(), $rules_array);


        if($v->passes()){
            $stocks_employee_id = \Request::input('stocks_employee_id');
            $cost_center_id = \Request::input('cost_center_id');
            $production_stocks_entry_date = \Request::input('production_stocks_entry_date');

            $now= date('Y-m-d H:i:s');
            \DB::beginTransaction();

            for($i=1; $i<=\Request::input('production_stocks_entry_field'); $i++){

                $inventory_stock_id = \Request::input('production_inventory_stocks_id_'.$i);

                $inventory_stocks_info = \DB::table('ltech_inventory_stocks')
                ->where('inventory_stock_id',$inventory_stock_id)
                ->first();

                if(!empty($inventory_stocks_info)){
                    $current_qty=\Request::input('production_transaction_stocks_quantity_'.$i);
                    $total_stocks_qty=$inventory_stocks_info->stocks_total_quantity;
                    $current_stocks_on_hand=$inventory_stocks_info->stocks_onhand - $current_qty;

                    if($current_qty<=$current_stocks_on_hand){

                        $stocks_cost=$inventory_stocks_info->stocks_total_cost;
                        $stocks_total_quantity  = ($inventory_stocks_info->stocks_onhand) - $current_qty;

                        $stocks_transactions_data = [
                        'inventory_stock_id' =>$inventory_stock_id,
                        'stocks_employee_id' =>$stocks_employee_id,
                        'stocks_transaction_date' => $production_stocks_entry_date,
                        'item_category_id' =>$inventory_stocks_info->item_category_id,
                        'stocks_transaction_desc' =>\Request::input('production_stocks_transaction_desc_'.$i),
                        'item_quantity_unit' =>$inventory_stocks_info->item_quantity_unit,
                        'stocks_transaction_type' =>'production',
                        'opening_transaction_stocks_quantity' =>$inventory_stocks_info->stocks_onhand,
                        'transaction_stocks_quantity'=>$current_qty,
                        'closing_transaction_stocks_quantity' =>$stocks_total_quantity,
                        'cost_center_id'=> $cost_center_id,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        ];



                        $stocks_update_data = [
                        'stocks_onhand' =>($inventory_stocks_info->stocks_onhand - $current_qty),
                        'stocks_onproduction' =>($inventory_stocks_info->stocks_onproduction + $current_qty),
                        'updated_by' =>\Auth::user()->user_id,
                        'updated_at' =>$now,
                        ];

                        

                        try{
                            /*stocks transaction insert*/

                            $stocks_transactions_data_insert = \DB::table('ltech_inventory_stocks_transactions')->insert($stocks_transactions_data);
                            \App\System::EventLogWrite('insert,ltech_inventory_stocks_transactions',json_encode($stocks_transactions_data));

                            /*stocks detail update*/
                            $stocks_update = \DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stocks_info->inventory_stock_id)->update($stocks_update_data);

                            \App\System::EventLogWrite('update,ltech_inventory_stocks',json_encode($stocks_update_data));

                           


                        }catch(\Exception $e){

                            \DB::rollback();
                            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                            \App\System::ErrorLogWrite($message);

                            return \Redirect::to('/inventory/stocks/on-production')->with('errormessage','Something wrong happend');
                        }


                    }else{ 
                        \DB::rollback();
                        return \Redirect::back()->with('errormessage','Stocks quantity empty.');
                    }

                }else return \Redirect::to('/inventory/stocks/on-production')->with('errormessage','Invenotory Products invalid'); 
            }

             \DB::commit();
             return \Redirect::to('/inventory/stocks/on-production')->with('message','Stocks successfully saved.');

        }else return \Redirect::to('/inventory/stocks/on-production')->withErrors($v->messages());
    }

    /********************************************
    ## InventoryStocksTransactionList
    *********************************************/
    public function InventoryStocksTransactionList(){

        $now=date('Y-m-d');
        if(isset($_GET['search_from'])  &&  isset($_GET['search_to'])  ||  isset($_GET['cost_center'])){

            $search_from = $_GET['search_from'].' 00:00:00';
            $search_to = $_GET['search_to'].' 23:59:59';
            if(isset($_GET['cost_center'])){
                $cost_center =$_GET['cost_center'];
            }else $cost_center=0;


            $data['search_from'] = $search_from;
            $data['search_to'] = $search_to;
            $data['cost_center'] = $cost_center;

            if($cost_center != 0){
                $inventory_stocks_list = \DB::table('ltech_inventory_stocks_transactions')
                                              ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                              ->leftjoin('ltech_cost_centers','ltech_inventory_stocks_transactions.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                              ->leftjoin('ltech_suppliers','ltech_inventory_stocks_transactions.stocks_supplier_id','=','ltech_suppliers.supplier_id')
                                              ->where('ltech_inventory_stocks_transactions.cost_center_id',$cost_center)
                                              ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                                              ->paginate(20);
            }else{
                $inventory_stocks_list = \DB::table('ltech_inventory_stocks_transactions')
                                              ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                              ->leftjoin('ltech_cost_centers','ltech_inventory_stocks_transactions.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                              ->leftjoin('ltech_suppliers','ltech_inventory_stocks_transactions.stocks_supplier_id','=','ltech_suppliers.supplier_id')
                                              ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                                              ->paginate(20);
            }

            if(isset($_GET['search_from']))
                $search_from = $_GET['search_from'];
            else $search_from = null;

            if(isset($_GET['search_to']))
                $search_to = $_GET['search_to'];
            else $search_to = null;

            if(isset($_GET['cost_center']))
                $cost_center = $_GET['cost_center'];
            else $cost_center = null;

            $inventory_stocks_list->setPath(url('inventory/stocks/trasansaction/list'));
            $inventory_stocks_list_pagination = $inventory_stocks_list->appends(['search_from' => $search_from, 'search_to'=> $search_to,'cost_center'=> $cost_center])->render();

        }else{
            $search_from=$now;
            $search_to=$now;
            $inventory_stocks_list = \DB::table('ltech_inventory_stocks_transactions')
                                              ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                              ->leftjoin('ltech_cost_centers','ltech_inventory_stocks_transactions.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                              ->leftjoin('ltech_suppliers','ltech_inventory_stocks_transactions.stocks_supplier_id','=','ltech_suppliers.supplier_id')
                                              ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                                              ->paginate(20);

            $inventory_stocks_list->setPath(url('inventory/stocks/trasansaction/list'));
            $inventory_stocks_list_pagination = $inventory_stocks_list->appends(['search_from' => $search_from, 'search_to'=> $search_to])->render();

        }
            $data['pagination']=$inventory_stocks_list_pagination;
            $data['inventory_stocks_list']= $inventory_stocks_list;

       
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.inventory.stocks-transaction-list',$data);
    }


    /********************************************
    ## InventoryStocksTransactionView
    *********************************************/
    public function InventoryStocksTransactionView($stocks_transactions_id){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

      
        $data['inventory_stocks'] = \DB::table('ltech_inventory_stocks_transactions')
                                          ->where('ltech_inventory_stocks_transactions.stocks_transactions_id',$stocks_transactions_id)
                                          ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                          ->leftjoin('ltech_cost_centers','ltech_inventory_stocks_transactions.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                          ->leftjoin('ltech_suppliers','ltech_inventory_stocks_transactions.stocks_supplier_id','=','ltech_suppliers.supplier_id')
                                          ->first();

        if(count($data['inventory_stocks'])==0)
            return \Redirect::to('/error/request');

       
   
        return \View::make('pages.inventory.stocks-transaction-view',$data);
    }


    /********************************************
    ## InventoryStocksTransactionPrint
    *********************************************/
    public function InventoryStocksTransactionPrint($stocks_transactions_id){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

      
        $data['inventory_stocks'] = \DB::table('ltech_inventory_stocks_transactions')
                                          ->where('ltech_inventory_stocks_transactions.stocks_transactions_id',$stocks_transactions_id)
                                          ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                          ->leftjoin('ltech_cost_centers','ltech_inventory_stocks_transactions.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                          ->leftjoin('ltech_suppliers','ltech_inventory_stocks_transactions.stocks_supplier_id','=','ltech_suppliers.supplier_id')
                                          ->first();

        if(count($data['inventory_stocks'])==0)
            return \Redirect::to('/error/request');

       
   
        return \View::make('pages.inventory.pdf.stocks-transaction-print',$data);
    }

    /********************************************
    ## InventoryStocksTransactionDownload
    *********************************************/
    public function InventoryStocksTransactionDownload($stocks_transactions_id){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

      
        $data['inventory_stocks'] = \DB::table('ltech_inventory_stocks_transactions')
                                          ->where('ltech_inventory_stocks_transactions.stocks_transactions_id',$stocks_transactions_id)
                                          ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                          ->leftjoin('ltech_cost_centers','ltech_inventory_stocks_transactions.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                          ->leftjoin('ltech_suppliers','ltech_inventory_stocks_transactions.stocks_supplier_id','=','ltech_suppliers.supplier_id')
                                          ->first();

        if(count($data['inventory_stocks'])==0)
            return \Redirect::to('/error/request');


        $pdf = \PDF::loadView('pages.inventory.pdf.stocks-transaction-pdf',$data);
        $pdfname = time().'_bill.pdf';
        return $pdf->download($pdfname); 
    
    }


    /********************************************
    ## InventoryStocksTransactionExcel
    *********************************************/
    public function InventoryStocksTransactionExcel($stocks_transactions_id){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

      
        $inventory_stocks = \DB::table('ltech_inventory_stocks_transactions')
                                          ->where('ltech_inventory_stocks_transactions.stocks_transactions_id',$stocks_transactions_id)
                                          ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                                          ->leftjoin('ltech_cost_centers','ltech_inventory_stocks_transactions.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                          ->leftjoin('ltech_suppliers','ltech_inventory_stocks_transactions.stocks_supplier_id','=','ltech_suppliers.supplier_id')
                                          ->first();

        if(count($inventory_stocks)==0)
            return \Redirect::to('/error/request');


        $sheet_name = time().'_bill';
        return \Excel::create($sheet_name, function($excel)  use($inventory_stocks) {
          $excel->sheet('Sheet 1', function($sheet) use($inventory_stocks) {
            $data['inventory_stocks'] = $inventory_stocks;
            
            $sheet->loadView('excelsheet.pages.stocks-transaction-excel',$data);
          });
        })->export('xlsx');
    
    }





    /********************************************
    # InventoryStock
    *********************************************/
    public function InventoryStock(){
        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->id;
        // $rule = [
        //         'item_category_id' => 'Required',
        //         'item_details_id' => 'Required',
        //         'item_quantity_unit' => 'Required',
        //         'cost_center_id' => 'Required',
        //         ];
        // $v = \Validator::make(\Request::all(),$rule);

        // if($v->passes()){
            $inventory_stock_data = [
            'item_category_id' =>\Request::input('item_category_id'),
            'item_quantity_unit' =>'t',
            'item_details_id' =>\Request::input('item_details_id'),
            'stocks_onhand' =>'0',
            'stocks_onproduction' =>'0',
            'stocks_total_quantity' =>'0',
            'cost_center_id' =>\Request::input('cost_center_id'),
            'stocks_total_cost' => '0',
            'created_at' =>$now,
            'updated_at' =>$now,
            'created_by' =>$user,
            'updated_by' =>$user,
            ]; 
       

            // try{
                \DB::table('ltech_inventory_stocks')->insert($inventory_stock_data);
                // \App\System::EventLogWrite('insert,ltech_inventory_stocks',json_encode($inventory_stock_data));
                return \Redirect::to('/inventory/dashboard')->with('message',"Item Added Successfully!");

            // }catch(\Exception $e){

            //     $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            //     \App\System::ErrorLogWrite($message);

            //     return \Redirect::to('/inventory/dashboard')->with('message',"Info Already Exist !");
            // }

        // }else return \Redirect::to('/inventory/dashboard')->withErrors($v->messages());
    }

    /********************************************
    ## ItemSettingPage
    /*********************************************/
    public function ItemSettingPage(){
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        $data['category_list'] = \DB::table('ltech_item_categories')->get();

        $item_list = \DB::table('ltech_items_details')
                        ->leftjoin('ltech_item_categories','ltech_items_details.item_category_id','ltech_item_categories.item_category_id')
                        ->OrderBy('ltech_items_details.updated_at','desc')
                        ->paginate(2);
        $item_list->setPath(url('/inventory/item/settings'));
        $item_pagination = $item_list->render();
        $data['item_pagination']=$item_pagination;
        $data['item_list']=$item_list;
        return \View::make('pages.inventory.item-settings',$data);
    }

    /********************************************
    ## AjaxItemEntry
    /*********************************************/
    public function AjaxItemEntry(){
        return \View::make('pages.inventory.ajax-item-entry');
    }
  

    /********************************************
    # ItemDetailsInsert
    *********************************************/
    public function ItemInsert(){
        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->user_id;
        $rule = [
                'item_name' => 'Required',
                'item_category_id' => 'Required',
                'item_description' => 'Required',
                'item_quantity_unit' => 'Required',
                ];
        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
            $item_name_slug = explode(' ', strtolower(\Request::input('item_name')));
            $item_name_slug = implode('_', $item_name_slug);
            $item_details_data = [
            'item_name' =>\Request::input('item_name'),
            'item_name_slug' => $item_name_slug,
            'item_category_id' =>\Request::input('item_category_id'),
            'item_quantity_unit' =>\Request::input('item_quantity_unit'),
            'item_description' =>\Request::input('item_description'),
            'created_at' =>$now,
            'updated_at' =>$now,
            'created_by' =>$user,
            'updated_by' =>$user,
            ];

            try{
                \DB::table('ltech_items_details')->insert($item_details_data);
                \App\System::EventLogWrite('insert,ltech_items_details',json_encode($item_details_data));
                return \Redirect::to('/inventory/item/settings')->with('message',"Item Added Successfully!");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/inventory/item/settings')->with('errormessage',"Info Already Exist !");
            }

        }else return \Redirect::to('/inventory/item/settings')->withErrors($v->messages());
    }

    /********************************************
    ## AjaxItemDelete
    /*********************************************/
    public function AjaxItemDelete($item_id){
        \DB::table('ltech_items_details')->where('item_id',$item_id)->delete();

    }


    /********************************************
    ## AjaxItemUpdate
    /*********************************************/
    public function AjaxItemUpdate($item_id,$item_name,$item_category_id,$item_quantity_unit,$item_description){
            $now= date('Y-m-d H:i:s');
           

            $item_update_data =[
                'item_name' =>$item_name,
                'item_category_id' =>$item_category_id,
                'item_quantity_unit' =>$item_quantity_unit,
                'item_description' =>$item_description,
                'updated_by' =>\Auth::user()->user_id,
                'updated_at' =>$now,
                ];

        try{
            \DB::table('ltech_items_details')->where('item_id',$item_id)->update($item_update_data);

            \App\System::EventLogWrite('update,ltech_items_details',json_encode($item_update_data));
            return \Redirect::to('/inventory/item/settings')->with('message',"Item Updated Successfully!");

        }catch(\Exception $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/inventory/item/settings')->with('errormessage',"Info Already Exist !");
        }


    }



    /********************************************
    ## CategorySettingPage
    *********************************************/
    public function CategorySettingPage(){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $category_list = \DB::table('ltech_item_categories')
                            ->OrderBy('updated_at','desc')
                            ->paginate(5);
        $category_list->setPath(url('/inventory/category/settings'));
        $category_pagination = $category_list->render();
        $data['category_pagination']=$category_pagination;
        $data['category_list']=$category_list;

        return \View::make('pages.inventory.category-settings',$data);
    }


    /********************************************
    ## CategorySettingInsert
    *********************************************/
    public function CategorySettingInsert(){
            
        $rules_array['item_category_name'] =  'Required';
        $rules_array['item_quantity_unit'] = 'Required';

        $v= \Validator::make(\Request::all(), $rules_array);


        if($v->passes()){

            $item_category_name = \Request::input('item_category_name');
            $item_category_name_slug = explode(' ', strtolower(\Request::input('item_category_name')));
            $item_category_name_slug = implode('_', $item_category_name_slug);

            $now= date('Y-m-d H:i:s');

            $category_data =[
                'item_category_name' =>$item_category_name,
                'item_category_name_slug' =>$item_category_name_slug,
                'item_quantity_unit' =>\Request::input('item_quantity_unit'),
                'created_by' =>\Auth::user()->user_id,
                'updated_by' =>\Auth::user()->user_id,
                'created_at' =>$now,
                'updated_at' =>$now,
                ];

            try{
                /*category insert*/
                 \DB::table('ltech_item_categories')->insert($category_data);
                \App\System::EventLogWrite('insert,ltech_item_categories',json_encode($category_data));

                return \Redirect::to('/inventory/category/settings')->with('message','Category successfully saved.');

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/inventory/category/settings')->with('errormessage','Something wrong happend');
            }

                        
        }else return \Redirect::to('/inventory/category/settings')->withErrors($v->messages());
        
    }

    /********************************************
    ## AjaxCategoryEntry
    /*********************************************/
    public function AjaxCategoryEntry(){
        return \View::make('pages.inventory.ajax-category-entry');
    }

    /********************************************
    ## AjaxCategoryDelete
    /*********************************************/
    public function AjaxCategoryDelete($item_category_id){
        \DB::table('ltech_item_categories')->where('item_category_id',$item_category_id)->delete();
        return \Redirect::to('/inventory/category/settings')->with('message',"Category Deleted Successfully!");

    }

    /********************************************
    ## AjaxCategoryUpdate
    /*********************************************/
    public function AjaxCategoryUpdate($item_category_id,$item_category_name,$item_quantity_unit){
            $now= date('Y-m-d H:i:s');

            $item_category_name_slug = explode(' ', strtolower($item_category_name));
            $item_category_name_slug = implode('_', $item_category_name_slug);

            $category_update_data =[
                'item_category_name' =>$item_category_name,
                'item_category_name_slug' =>$item_category_name_slug,
                'item_quantity_unit' =>$item_quantity_unit,
                'updated_by' =>\Auth::user()->user_id,
                'updated_at' =>$now,
                ];

        try{
            \DB::table('ltech_item_categories')->where('item_category_id',$item_category_id)->update($category_update_data);

            \App\System::EventLogWrite('update,ltech_item_categories',json_encode($category_update_data));
            return \Redirect::to('/inventory/category/settings')->with('message',"Category Updated Successfully!");

        }catch(\Exception $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/inventory/category/settings')->with('message',"Info Already Exist !");
        }


    }


    /********************************************
    ## InventorySettings
    *********************************************/
    public function InventorySettings(){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        $stock_inventory_list=\DB::table('ltech_inventory_stocks')
                        ->leftjoin('ltech_item_categories','ltech_inventory_stocks.item_category_id','ltech_item_categories.item_category_id')
                        ->OrderBy('ltech_inventory_stocks.updated_at','desc')
                        ->paginate(5);
        $stock_inventory_list->setPath(url('/inventory/item/settings'));
        $inventory_pagination = $stock_inventory_list->render();
        $data['inventory_pagination']=$inventory_pagination;
        $data['stock_inventory_list']=$stock_inventory_list;
        return \View::make('pages.inventory.inventory-settings',$data);

    }

    /********************************************
    ## AjaxStockEntry
    /*********************************************/
    public function AjaxStockEntry(){
        return \View::make('pages.inventory.ajax-new-stock-entry');
    }

  

    /********************************************
    ## AjaxCategoryList
    /*********************************************/
    public function AjaxCategoryList($item_category_id, $id){
        $item_list=\DB::table('ltech_items_details')->where('item_category_id',$item_category_id)->get();
        $data['item_list']=$item_list;

        return \View::make('pages.inventory.ajax-category-list',$data);
    }


    /********************************************
    # InventoryStockInsert
    *********************************************/
    public function InventoryStockInsert(){
        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->user_id;
        $rule = [
                'item_category_id' => 'Required',
                'item_name' => 'Required',
                'item_description' => 'Required',
                ];
        $v = \Validator::make(\Request::all(),$rule);


        if($v->passes()){

            $stocks_item_name=\Request::input('item_name');
            $item_name_slug = explode(' ', strtolower(\Request::input('item_name')));
            $item_name_slug = implode('_', $item_name_slug);

            $item_category_id=\Request::input('item_category_id');
            $item_category_details=\DB::table('ltech_item_categories')->where('item_category_id',$item_category_id)->first();
            if(!empty($item_category_details)){
                $item_quantity_unit=$item_category_details->item_quantity_unit;
            }else $item_quantity_unit=0;


            $journal_info = \DB::table('ltech_ledger_group_4')->where('ledger_name','LIKE','Stocks-in raw material')->first();
            if(!empty($journal_info)){

                $parent_id=$journal_info->ledger_id;
                $journal_stocks_in_raw_material_depth=$journal_info->depth;
                $stock_debit_amount=0;
                $stock_credit_amount=0;


                $journal_new_item_info = \DB::table('ltech_ledger_group_5')->where('ledger_name','LIKE',$stocks_item_name)->first();

                if(empty($journal_new_item_info)){

                    $inventory_account = \App\Journal::JournalEntryInsert($stocks_item_name,$item_name_slug,($journal_stocks_in_raw_material_depth+1), $parent_id,$stock_debit_amount,$stock_credit_amount);

                    $journalupdate = \App\Journal::JournalUpdateParent($journal_stocks_in_raw_material_depth,$parent_id);


                    $item_stocks_data = [
                    'item_category_id' =>\Request::input('item_category_id'),
                    'item_name' =>\Request::input('item_name'),
                    'item_name_slug' =>$item_name_slug,
                    'item_quantity_unit' =>$item_quantity_unit,
                    'item_account_id' =>$inventory_account.'.5',
                    'item_description' =>\Request::input('item_description'),
                    'stocks_onhand' =>0,
                    'stocks_type' =>'raw-materials',
                    'stocks_onproduction' =>0,
                    'stocks_total_quantity' =>0,
                    'stocks_total_cost' =>0,
                    'created_at' =>$now,
                    'updated_at' =>$now,
                    'created_by' =>$user,
                    'updated_by' =>$user,
                    ];

                    try{
                        \DB::table('ltech_inventory_stocks')->insert($item_stocks_data);
                        \App\System::EventLogWrite('insert,ltech_inventory_stocks',json_encode($item_stocks_data));
                        return \Redirect::to('/inventory/item/settings')->with('message',"Stocks Added Successfully!");

                    }catch(\Exception $e){

                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/inventory/item/settings')->with('message',"Info Already Exist !");
                    }


                }else return \Redirect::to('/inventory/item/settings')->with('errormessage',"Stocks Same Account Name Found !!!!!!!!!!!!");

            }else return \Redirect::to('/inventory/item/settings')->with('errormessage',"Info Already Exist !");


        }else return \Redirect::to('/inventory/item/settings')->withErrors($v->messages());
    }

    /********************************************
    ## AjaxInventoryStockUpdate
    /*********************************************/
     public function AjaxInventoryStockUpdate($inventory_stock_id,$item_category_id,$item_name,$item_description){
             $now= date('Y-m-d H:i:s');
            $item_name_slug = explode(' ', strtolower($item_name));
            $item_name_slug = implode('_', $item_name_slug);

            $item_category_details=\DB::table('ltech_item_categories')->where('item_category_id',$item_category_id)->first();
            if(!empty($item_category_details)){
                $item_quantity_unit=$item_category_details->item_quantity_unit;
            }else $item_quantity_unit=0;



            $inventory_item_details=\DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stock_id)->first();

            $inventory_item_account_id = explode('.',$inventory_item_details->item_account_id);

            $journal_account_info = \DB::table('ltech_ledger_group_5')->where('ledger_name','LIKE', $item_name)->first();
            if(empty($journal_account_info)){


                $journal_account_update_data =[
                    'ledger_name' =>$item_name,
                    'ledger_name_slug' =>$item_name_slug,
                    'updated_at' =>$now,
                    'updated_by' =>\Auth::user()->user_id,
                ];

                $journal_account_update = \DB::table('ltech_ledger_group_'.$inventory_item_account_id[1])->where('ledger_id',$inventory_item_account_id[0])->update($journal_account_update_data);


                $inventory_update_data =[
                    'item_category_id' =>$item_category_id,
                    'item_name' =>$item_name,
                    'item_name_slug' =>$item_name_slug,
                    'item_quantity_unit' =>$item_quantity_unit,
                    'item_description' =>$item_description,
                    'updated_at' =>$now,
                    'updated_by' =>\Auth::user()->user_id,

                    ];

                try{
                    \DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stock_id)->update($inventory_update_data);
                    \App\System::EventLogWrite('update,ltech_inventory_stocks',json_encode($inventory_update_data));
                    return \Redirect::to('/inventory/item/settings')->with('message',"Stocks Updated Successfully!");

                }catch(\Exception $e){

                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/inventory/item/settings')->with('message',"Info Already Exist !");
                }

            }else return \Redirect::to('/inventory/item/settings')->with('errormessage',"Stocks Same Account Name Found.Please try Others name !!!");
    }

    /********************************************
    ## AjaxInventoryDelete
    /*********************************************/
    public function AjaxInventoryDelete($inventory_stock_id){


        $stock_transaction=\DB::table('ltech_inventory_stocks_transactions')->where('inventory_stock_id',$inventory_stock_id)->get();
        $stock_details=\DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stock_id)->first();

        if(!empty($stock_transaction) && count($stock_transaction)!=0){
            return ['status'=>'fail','message'=>'Stocks can not deleted, because it has transaction!'];

        }else{
            \DB::beginTransaction();
            try{
                \DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stock_id)->delete();
                \DB::table('ltech_ledger_group_5')->where('ledger_name_slug',$stock_details->item_name_slug)->delete();
                \App\System::EventLogWrite('delete,ltech_inventory_stocks',json_encode($stock_details));
                \App\System::EventLogWrite('delete,ltech_ledger_group_5',json_encode($stock_details->item_name_slug));


                \DB::commit();

                return ['status'=>'fail','message'=>'Stocks Deleted Successfully.'];


            }catch(\Exception $e){

                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return ['status'=>'fail','message'=>'Something wrong!'];
            }

        }

    }

    /********************************************
    ## StockSummery
    *********************************************/
    public function StockSummery(){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        // return \View::make('pages.stock-summery',$data);
        $pdf = \PDF::loadView('pages.inventory.stock-summery',$data);
        return  $pdf->stream();

    } 


    /********************************************
    ## StockSummery
    *********************************************/
    public function StockSummeryList(){


    /*------------------------------------Get Request--------------------------------------------*/
         if(isset($_GET['search_from']) && isset($_GET['search_to']) ){

            $search_from = $_GET['search_from'].' 00:00:00';
            $search_to = $_GET['search_to'].' 23:59:59';

            $stock_summery_list = \DB::table('ltech_inventory_stocks_transactions')->whereBetween('created_at',array($search_from,$search_to))->orderBy('created_at','desc')->paginate(10);

            $stock_summery_list->setPath(url('/stock/summery/list'));

            $stock_summery_pagination = $stock_summery_list->appends(['search_from' => $_GET['search_from'], 'search_to'=> $_GET['search_to']])->render();

            $data['stock_summery_pagination'] = $stock_summery_pagination;
            $data['stock_summery_list'] = $stock_summery_list;


         }
    /*------------------------------------/Get Request--------------------------------------------*/
        else{
            $today = date('Y-m-d');
            $stock_summery_list=\DB::table('ltech_inventory_stocks_transactions')
            ->where('created_at','like',$today."%")
            ->orderBy('created_at','desc')
            ->paginate(10);
            $stock_summery_list->setPath(url('/stock/summery/list'));
            $stock_summery_pagination = $stock_summery_list->render();
            $data['stock_summery_pagination'] = $stock_summery_pagination;
            $data['stock_summery_list'] = $stock_summery_list;
        }

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        return \View::make('pages.inventory.stock-summery-list',$data);

    }  


    /********************************************
    ## FinshGoodsListPage
    *********************************************/
    public function FinshGoodsListPage(){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $data['customer_order_info'] = \DB::table('ltech_sales_orders')
                                        ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                        ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                        ->where('ltech_sales_orders.order_status',0)->get();

        $data['inventory_stocks_list'] = \DB::table('ltech_inventory_stocks')->get();
        $data['cost_centers'] = \DB::table('ltech_cost_centers')->get();

        if(isset($_GET['item_id']) && !empty($_GET['item_id']) ){

           $product_info = \DB::table('ltech_sales_order_details')->where('order_details_id',$_GET['item_id'])->first();

           if(!empty($product_info))
            $data['product_info'] = $product_info;
        }

        return \View::make('pages.inventory.finish-goods-list',$data);
    }

    /********************************************
    ## AjaxInventoryStocksFinsihgoodsFieldEntry
    *********************************************/
    public function AjaxInventoryStocksFinsihgoodsFieldEntry($filed_count){

        $data['i']=$filed_count;      
        return \View::make('pages.inventory.ajax-finishgoods-field',$data);
    }


    /********************************************
    ## AjaxInventoryStocksInfoFinishgoods
    *********************************************/
    public function AjaxInventoryStocksInfoFinishgoods($inventory_stock_id){

        $inventory_stocks_info = \DB::table('ltech_inventory_stocks')
        ->where('inventory_stock_id',$inventory_stock_id)
        ->first();
        $stocks_onhand = 0;
        if(!empty($inventory_stocks_info)){

            $stocks_inhand= $inventory_stocks_info->stocks_onhand.' '.$inventory_stocks_info->item_quantity_unit;
            return ['stocks_onhand'=>$stocks_inhand];

        }else return ['stocks_onhand'=>''];
       
    }

    /********************************************
    ## FinshGoodsSubmit
    *********************************************/
    public function FinshGoodsSubmit(){
        $now=date('Y-m-d H:i:s');

        for ($i=1;$i<=\Request::input('finishgoods_stocks_entry_field');$i++) {
            $rules_array['finishgoods_inventory_stocks_id_'.$i] =  'Required';
            $rules_array['finishgoods_stocks_onhand_quantity_'.$i] =  'Required';
            $rules_array['finishgoods_transaction_stocks_quantity_'.$i] =  'Required|numeric';
            $rules_array['finishgoods_stocks_transaction_amount_'.$i] =  'Required|numeric';
        }

        $rules_array['finish_add_date'] = 'Required|date';
        $rules_array['finish_goods_name'] = 'Required';
        $rules_array['cost_center_id'] = 'Required';
        $rules_array['finish_goods_quantity'] = 'Required|numeric';
        $rules_array['finish_goods_rate'] = 'Required|numeric';
        //$rules_array['finish_goods_amount'] = 'Required|numeric';
     
        $v= \Validator::make(\Request::all(), $rules_array);

        if($v->passes()){

                $item_name_slug = explode(' ', strtolower(trim(\Request::input('finish_goods_name'))));
                $item_name_slug = implode('_', $item_name_slug);
                $finish_add_date = \Request::input('finish_add_date');
                $cost_center_id = \Request::input('cost_center_id');

            \DB::beginTransaction();
            try{

                $inventory_total_amount=0;

                for($i=1;$i<=\Request::input('finishgoods_stocks_entry_field');$i++){
                    
                    $inventory_stocks_list_data = [ 'finishgoods_inventory_stocks_id' =>\Request::input('finishgoods_inventory_stocks_id_'.$i),
                        'finishgoods_transaction_stocks_quantity' =>\Request::input('finishgoods_transaction_stocks_quantity_'.$i),
                        'finishgoods_stocks_transaction_amount' => \Request::input('finishgoods_stocks_transaction_amount_'.$i)
                        ];


                    $inventory_stocks_list[] =  $inventory_stocks_list_data;
                    $inventory_total_amount = $inventory_total_amount + \Request::input('finishgoods_stocks_transaction_amount_'.$i);
                }

########## Can not submit Finish Goods Without Order 23-02-2017 ##############

                $finish_goods_details_info = \DB::table('ltech_sales_order_details')->where('order_details_id',\Request::input('finish_goods_item_id'))->first();
                if(empty($finish_goods_details_info)){
                    return\Redirect::to('/finish-goods/list')->with('errormessage','Please Select The Order');
                }

################## Can not submit Finish Goods Without Order #######################


                /****General Transactin******/
                $transaction_info = [
                            'transactions_date' =>$finish_add_date,
                            'transactions_naration' =>"Finish Goods Add in stocks",
                            'transaction_amount' =>$inventory_total_amount,
                            'cost_center_id' =>$cost_center_id,
                            'posting_type' =>'journal',
                            'created_by' => \Auth::user()->user_id,
                            'updated_by' => \Auth::user()->user_id,
                        ];


                $transactionRow = \DB::table('ltech_transactions')->insertGetId($transaction_info);
                \App\System::EventLogWrite('insert,ltech_transactions',json_encode($transaction_info));


                /****journal credit stocks insert****/
                for($i=1;$i<=\Request::input('finishgoods_stocks_entry_field');$i++){


                     $inventory_stock_id = \Request::input('finishgoods_inventory_stocks_id_'.$i);

                    $inventory_stocks_info = \DB::table('ltech_inventory_stocks')
                    ->where('inventory_stock_id',$inventory_stock_id)
                    ->first();

                
                    $current_qty=\Request::input('finishgoods_transaction_stocks_quantity_'.$i);
                    $current_transaction_cost=\Request::input('finishgoods_stocks_transaction_amount_'.$i);
                    $total_stocks_qty=$inventory_stocks_info->stocks_total_quantity;
                    $current_stocks_on_hand=$inventory_stocks_info->stocks_onhand - $current_qty;

                    if($current_stocks_on_hand > 0){

                        $stocks_cost=$inventory_stocks_info->stocks_total_cost;
                        $stocks_total_quantity  = ($inventory_stocks_info->stocks_onhand) - $current_qty;

                        $stocks_transactions_data = [
                        'inventory_stock_id' =>$inventory_stock_id,
                        'stocks_employee_id' =>\Auth::user()->user_id,
                        'stocks_transaction_date' => $finish_add_date,
                        'item_category_id' =>$inventory_stocks_info->item_category_id,
                        'stocks_transaction_desc' =>'products for finish goods',
                        'item_quantity_unit' =>$inventory_stocks_info->item_quantity_unit,
                        'stocks_transaction_type' =>'outwards',
                        'opening_transaction_stocks_quantity' =>$inventory_stocks_info->stocks_onhand,
                        'transaction_stocks_quantity'=>$current_qty,
                        'closing_transaction_stocks_quantity' =>$stocks_total_quantity,

                        'opening_transaction_stocks_cost'=>$inventory_stocks_info->stocks_onproduction_cost,
                        'stocks_quantity_cost'=>$current_transaction_cost,

                        'closing_transaction_stocks_cost'=>$inventory_stocks_info->stocks_onproduction_cost+(!empty($inventory_stocks_info->stocks_onproduction_cost) ? $inventory_stocks_info->stocks_onproduction_cost:\Request::input('finishgoods_stocks_transaction_amount_'.$i)),

                        'cost_center_id'=> $cost_center_id,
                        'referrence'=>$transactionRow,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        ];


                        $stocks_update_data = [
                        'stocks_onhand' =>($inventory_stocks_info->stocks_onhand - $current_qty),
                        'stocks_onproduction' =>($inventory_stocks_info->stocks_onproduction + $current_qty),
                        'stocks_onproduction_cost' =>($inventory_stocks_info->stocks_onproduction_cost + (\Request::input('finishgoods_stocks_transaction_amount_'.$i))),
                        'updated_by' =>\Auth::user()->user_id,
                        'updated_at' =>$now,
                        ];

                         $stocks_transactions_data_insert = \DB::table('ltech_inventory_stocks_transactions')->insertGetId($stocks_transactions_data);
                        \App\System::EventLogWrite('insert,ltech_inventory_stocks_transactions',json_encode($stocks_transactions_data));
                        \App\Journal::TransactionMeta($transactionRow,'ltech_inventory_stocks_transactions',$stocks_transactions_data_insert);


                        /*stocks detail update*/
                        $stocks_update = \DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stocks_info->inventory_stock_id)->update($stocks_update_data);
                        \App\System::EventLogWrite('update,ltech_inventory_stocks',json_encode($stocks_update_data));
                        \App\Journal::TransactionMeta($transactionRow,'ltech_inventory_stocks',$inventory_stocks_info->inventory_stock_id);


                        $stocks_accounts_info = explode('.', $inventory_stocks_info->item_account_id);

                        $journal_credit_info = [
                                'journal_date' =>$finish_add_date,
                                'journal_particular_id' =>$stocks_accounts_info[0],
                                'journal_particular_name' =>$inventory_stocks_info->item_name,
                                'journal_particular_depth'=>$stocks_accounts_info[1],
                                'journal_particular_naration' =>'Stocks in raw material for finish goods',
                                'journal_particular_amount_type'=>'credit',
                                'journal_particular_amount' =>\Request::input('finishgoods_stocks_transaction_amount_'.$i),
                                'cost_center_id' =>$cost_center_id,
                                'posting_type' =>'journal',
                                'transaction_id' =>$transactionRow,
                                'created_by' => \Auth::user()->user_id,
                                'updated_by' => \Auth::user()->user_id,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                            ];
                   
                        $journal_debit_data = \DB::table('ltech_general_journal')->insert($journal_credit_info);
                        \App\System::EventLogWrite('insert,ltech_general_journal.journal_credit_data',json_encode($journal_credit_info));

                    }else{
                        \DB::rollback();
                        return \Redirect::to('/finish-goods/list')->with('errormessage',"Stocks on hand quantity cross limit.!");
                    }                    
                }//end of loop

                #Order Item Update
                $finish_goods_info = \DB::table('ltech_sales_order_details')->where('order_details_id',\Request::input('finish_goods_item_id'))->first();

                $finish_goods_name = !empty($finish_goods_info) ? $finish_goods_info->order_item_name:\Request::input('finish_goods_name');
                $finish_goods_quantity = \Request::input('finish_goods_quantity');
                $finish_goods_rate = \Request::input('finish_goods_rate');
                $finish_goods_amount = $finish_goods_quantity*$finish_goods_rate;


                if($finish_goods_amount !=$inventory_total_amount){
                    \DB::rollback();
                    return \Redirect::to('/finish-goods/list')->with('errormessage',"Production Cost $inventory_total_amount and Finish Good costs finish_goods_amount must be same.!");
                }

                #Finsish Good Ledger Account Create
                $journal_info = \DB::table('ltech_ledger_group_4')->where('ledger_name','LIKE','Stocks-in finish goods')->first();

                if(!empty($journal_info)){

                    $parent_id=$journal_info->ledger_id;
                    $journal_stocks_in_finishgoods_depth =$journal_info->depth;
                    $stock_debit_amount=0;
                    $stock_credit_amount=0;

                    $finish_goods_name_slug = explode(' ',strtolower($finish_goods_name));
                    $finish_goods_name_slug = implode('_', $finish_goods_name_slug);

                    $journal_new_item_info = \DB::table('ltech_ledger_group_'.($journal_info->depth+1))
                                            ->where('ltech_ledger_group_5.ledger_group_parent_id','LIKE',$parent_id)
                                            ->where('ltech_ledger_group_5.ledger_name_slug','LIKE',$finish_goods_name_slug)
                                            ->Join('ltech_finish_goods_stocks','ltech_ledger_group_5.ledger_name_slug','=','ltech_finish_goods_stocks.finish_goods_name_slug')
                                            ->first();

                    if(empty($journal_new_item_info)){

                        $inventory_account = \App\Journal::JournalEntryInsert($finish_goods_name,$finish_goods_name_slug,($journal_stocks_in_finishgoods_depth+1), $parent_id,$stock_debit_amount,$stock_credit_amount);

                        $journalupdate = \App\Journal::JournalUpdateParent($journal_stocks_in_finishgoods_depth,$parent_id);

                        #Finish Stocks Entry
                        $finish_goods_stocks_insert = [
                                                        'finish_goods_entry_date' =>$finish_add_date,
                                                        'finish_goods_accounts_id'=>$inventory_account.'.'.($journal_stocks_in_finishgoods_depth+1),
                                                        'finish_goods_name' =>$finish_goods_name,
                                                        'finish_goods_name_slug'=>$finish_goods_name_slug,
                                                        'finish_goods_net_production_cost' =>$finish_goods_amount,
                                                        'finish_goods_net_production_quantity'=>$finish_goods_quantity,
                                                        'finish_goods_net_cost'=>$finish_goods_amount,
                                                        'finish_goods_net_quantity'=>$finish_goods_quantity,
                                                        'created_by' => \Auth::user()->user_id,
                                                        'updated_by' => \Auth::user()->user_id,
                                                        'created_at' =>$now,
                                                        'updated_at' =>$now,

                                                    ];
                        $finish_goods_stocks_insert_data = \DB::table('ltech_finish_goods_stocks')->insertGetId($finish_goods_stocks_insert);
                        \App\System::EventLogWrite('insert,ltech_finish_goods_stocks.finish_goods_production_data',json_encode($finish_goods_stocks_insert));
                        \App\Journal::TransactionMeta($transactionRow,'ltech_finish_goods_stocks',$finish_goods_stocks_insert_data);


                    }else{
                        # Update Finish Stocks
                        $finish_goods_stocks_update = [
                                                        'finish_goods_net_production_cost' =>$journal_new_item_info->finish_goods_net_production_cost + $finish_goods_amount,
                                                        'finish_goods_net_production_quantity'=>($journal_new_item_info->finish_goods_net_production_quantity + $finish_goods_quantity),
                                                        'finish_goods_net_cost'=>($journal_new_item_info->finish_goods_net_cost+$finish_goods_amount),
                                                        'finish_goods_net_quantity'=>($journal_new_item_info->finish_goods_net_quantity+$finish_goods_quantity),
                                                        'updated_by' => \Auth::user()->user_id,
                                                        'updated_at' =>$now,

                                                    ];
                        $finish_goods_stocks_update_data = \DB::table('ltech_finish_goods_stocks')->where('finish_goods_id',$journal_new_item_info->finish_goods_id)->update($finish_goods_stocks_update);
                        \App\System::EventLogWrite('update,ltech_finish_goods_stocks.finish_goods_production_data',json_encode($finish_goods_stocks_update));
                        \App\Journal::TransactionMeta($transactionRow,'ltech_finish_goods_stocks',$journal_new_item_info->finish_goods_id);


                    }


                    #Finish Goods Transaction Entry
                    $finish_goods_accounts_id = empty($journal_new_item_info) ? $inventory_account.'.'.($journal_stocks_in_finishgoods_depth+1):$journal_new_item_info->finish_goods_accounts_id;

                    $finish_goods_id = empty($journal_new_item_info) ? $finish_goods_stocks_insert_data:$journal_new_item_info->finish_goods_id;

                    $opening_transaction_finish_goods_quantity = empty($journal_new_item_info) ? $finish_goods_quantity:$journal_new_item_info->finish_goods_net_production_quantity;

                    $closing_transaction_finish_goods_quantity = empty($journal_new_item_info) ? $finish_goods_quantity:$journal_new_item_info->finish_goods_net_quantity+$finish_goods_quantity;

                    $opening_transaction_finish_goods_cost = empty($journal_new_item_info) ? $finish_goods_amount:$journal_new_item_info->finish_goods_net_production_cost;

                    $closing_transaction_finish_goods_cost = empty($journal_new_item_info) ? $finish_goods_amount:$journal_new_item_info->finish_goods_net_production_cost+$finish_goods_amount;


                    $finish_transaction_insert = [
                                                    'finish_goods_transaction_date'=>$finish_add_date,
                                                    'finish_goods_accounts_id' =>$finish_goods_accounts_id,
                                                    'finish_goods_id' =>$finish_goods_id,
                                                    'cost_center_id' =>$cost_center_id,
                                                    'finish_goods_type' =>  !empty($finish_goods_info) ? 'ordered':'non-orderd',
                                                    'finish_goods_transaction_type' =>'inwards',
                                                    'opening_transaction_finish_goods_quantity'=>$opening_transaction_finish_goods_quantity,
                                                    'transaction_finish_goods_quantity' =>$finish_goods_quantity,
                                                    'closing_transaction_finish_goods_quantity'=>$closing_transaction_finish_goods_quantity,
                                                    'finish_goods_quantity_rate' =>$finish_goods_rate,
                                                    'opening_transaction_finish_goods_cost' =>$opening_transaction_finish_goods_cost,
                                                    'finish_goods_quantity_cost'=>$finish_goods_amount,
                                                    'closing_transaction_finish_goods_cost'=>$closing_transaction_finish_goods_cost,
                                                    'finish_goods_inventory' =>serialize($inventory_stocks_list),
                                                    'referrence' =>$transactionRow,
                                                    'created_by' => \Auth::user()->user_id,
                                                    'updated_by' => \Auth::user()->user_id,
                                                    'created_at' =>$now,
                                                    'updated_at' =>$now,


                                                ];

                    $finish_transaction_insert_data = \DB::table('ltech_finish_goods_transactions')->insertGetId($finish_transaction_insert);
                    \App\System::EventLogWrite('insert,ltech_finish_goods_transactions.finish_goods_transaction_production_data',json_encode($finish_transaction_insert));
                    \App\Journal::TransactionMeta($transactionRow,'ltech_finish_goods_transactions',$finish_transaction_insert_data);



                    #Joural debit Finish Goods account
                    $goods_accounts_info = explode('.', $finish_goods_accounts_id);

                    $journal_debit_info = [
                            'journal_date' =>$finish_add_date,
                            'journal_particular_id' =>$goods_accounts_info[0],
                            'journal_particular_name' =>$finish_goods_name,
                            'journal_particular_depth'=>$goods_accounts_info[1],
                            'journal_particular_naration' =>'Stocks inwards finish goods',
                            'journal_particular_amount_type'=>'debit',
                            'journal_particular_amount' =>$finish_goods_amount,
                            'cost_center_id' =>$cost_center_id,
                            'posting_type' =>'journal',
                            'transaction_id' =>$transactionRow,
                            'created_by' => \Auth::user()->user_id,
                            'updated_by' => \Auth::user()->user_id,
                            'created_at' =>$now,
                            'updated_at' =>$now,
                        ];
               
                    $journal_debit_data = \DB::table('ltech_general_journal')->insert($journal_debit_info);
                    \App\System::EventLogWrite('insert,ltech_general_journal.journal_debit_data',json_encode($journal_debit_info));

                    #Order details item update

                    if(!empty($finish_goods_info)){

                        $update_order_item = [
                                            'order_item_process_status' =>1,
                                            'order_item_process_date' =>$finish_add_date,
                                            'order_item_process_list' => serialize($inventory_stocks_list),
                                            'updated_at' =>$now,
                                            'updated_by' => \Auth::user()->user_id
                                        ];
                        $update_order_item_data = \DB::table('ltech_sales_order_details')->where('order_details_id',$finish_goods_info->order_details_id)->update($update_order_item);
                        \App\System::EventLogWrite('update,ltech_sales_order_details.journal_debit_data',json_encode($update_order_item));
                        \App\Journal::TransactionMeta($transactionRow,'ltech_sales_order_details',$finish_goods_info->order_details_id);


                        #main Order Update
                        $order_remain = \DB::table('ltech_sales_order_details')->where('order_id',$finish_goods_info->order_id)->where('order_item_process_status',0)->count();

                        if($order_remain==0){
                           $update_order = [
                                            'order_status' =>1,
                                            'updated_at' =>$now,
                                            'updated_by' => \Auth::user()->user_id
                                        ];
                            $update_order_item_data = \DB::table('ltech_sales_orders')->where('order_id',$finish_goods_info->order_id)->update($update_order);
                            \App\System::EventLogWrite('update,ltech_sales_orders',json_encode($update_order)); 
                            \App\Journal::TransactionMeta($transactionRow,'ltech_sales_orders',$finish_goods_info->order_id);

                        }


                    }


                    \DB::commit();
                    return \Redirect::to('/finish-goods/list')->with('message',"Finish goods listed successfully!!!");
                    

                }else{

                    \DB::rollback();
                    return \Redirect::to('/finish-goods/list')->with('errormessage',"Finish goods ledger missing!!!");
                }

            }catch(\Exception $e){

                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::to('/finish-goods/list')->with('errormessage',"Something wrong happend for finish goods!");
            }

        }else return \Redirect::to('/finish-goods/list')->withErrors($v->messages());
    }

    /********************************************
    ## InventoryStockItemList
    *********************************************/
    public function InventoryStockItemList(){
        $inventory_stock_total_item_cost=0;
        $inventory_stock_item_list = \DB::table('ltech_inventory_stocks')
                            ->orderBy('ltech_inventory_stocks.updated_at','asc')
                            ->paginate(5);
        $inventory_stock_item_list->setPath(url('inventory/stock/item/list'));
        $stock_list_pagination=$inventory_stock_item_list->render();

       
        $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();
        $data['stock_list_pagination'] = $stock_list_pagination;
        $data['inventory_stock_item_list'] = $inventory_stock_item_list;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.inventory.inventory-stock-item-list',$data);
    }



    /********************************************
    ## InventoryStockItemListPDF
    *********************************************/
    public function InventoryStockItemListPDF(){

        $inventory_stock_total_item_cost=0;
        $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();
        $inventory_stock_all_item_list = \DB::table('ltech_inventory_stocks')
                    ->orderBy('ltech_inventory_stocks.updated_at','asc')
                    ->get();

        if(!empty($inventory_stock_all_item_list)){
            foreach ($inventory_stock_all_item_list as $key => $value) {
                $inventory_stock_total_item_cost=$inventory_stock_total_item_cost+$value->stocks_total_cost;
            }

            $data['inventory_stock_all_item_list'] = $inventory_stock_all_item_list;
            $data['inventory_stock_total_item_cost'] = $inventory_stock_total_item_cost;
            $data['page_title'] = $this->page_title;
            $data['page_desc'] = $this->page_desc;
            // return \View::make('pages.inventory.pdf.inventory-stock-item-list-pdf',$data);

            $pdf = \PDF::loadView('pages.inventory.pdf.inventory-stock-item-list-pdf',$data);
            $pdfname = time().' inventory-stocks-item.pdf';
            return $pdf->download($pdfname);

        }else return \Redirect::to('/error/request')->with('errormessage','Something Wrong in orders');

    } 



    /********************************************
    ## InventoryStockItemListPrint
    *********************************************/
    public function InventoryStockItemListPrint(){

        $inventory_stock_total_item_cost=0;
        $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();
        $inventory_stock_all_item_list = \DB::table('ltech_inventory_stocks')
                    ->orderBy('ltech_inventory_stocks.updated_at','asc')
                    ->get();

        if(!empty($inventory_stock_all_item_list)){
            foreach ($inventory_stock_all_item_list as $key => $value) {
                $inventory_stock_total_item_cost=$inventory_stock_total_item_cost+$value->stocks_total_cost;
            }
            $data['inventory_stock_all_item_list'] = $inventory_stock_all_item_list;
            $data['inventory_stock_total_item_cost'] = $inventory_stock_total_item_cost;
            $data['page_title'] = $this->page_title;
            $data['page_desc'] = $this->page_desc;

            return \View::make('pages.inventory.pdf.inventory-stock-item-list-print',$data);

        }else return \Redirect::to('/error/request')->with('errormessage','Something Wrong in orders');

    }




    /********************************************
    ## DeliveryFinishGoodsList
    *********************************************/
    public function DeliveryFinishGoodsList(){

        $delivery_finish_goods = \DB::table('ltech_finish_goods_stocks')
                    ->where('ltech_finish_goods_stocks.goods_status','1')
                    ->where('ltech_finish_goods_stocks.finish_goods_net_quantity','!=','0')
                    ->orderBy('ltech_finish_goods_stocks.updated_at','asc')
                    ->paginate(10);
        $delivery_finish_goods->setPath(url('/delivery/finish-goods/list'));
        $delivery_finish_goods_pagination = $delivery_finish_goods->render();

        $data['pagination']=$delivery_finish_goods_pagination;
        $data['delivery_finish_goods'] = $delivery_finish_goods;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        return \View::make('pages.inventory.delivery-finish-goods',$data);

    }

    /********************************************
    ## WasteFinishGoods
    *********************************************/
    public function WasteFinishGoods($finish_goods_id){
        $now=date('Y-m-d');

        $finish_goods_info = \DB::table('ltech_finish_goods_stocks')
                    ->where('ltech_finish_goods_stocks.finish_goods_id',$finish_goods_id)
                    ->first();

        $finish_goods_transaction = \DB::table('ltech_finish_goods_transactions')
                    ->where('ltech_finish_goods_transactions.finish_goods_id',$finish_goods_id)
                    ->where('ltech_finish_goods_transactions.finish_goods_transaction_type','inwards')
                    ->first();


        \DB::beginTransaction();
        try{

            #General Transactin
            $transaction_info = [
                        'transactions_date' =>$now,
                        'transactions_naration' =>"Waste Goods",
                        'transaction_amount' =>$finish_goods_info->finish_goods_net_cost,
                        'cost_center_id' =>$finish_goods_transaction->cost_center_id,
                        'posting_type' =>'waste_goods_journal',
                        'created_by' => \Auth::user()->user_id,
                        'updated_by' => \Auth::user()->user_id,
                        'created_at' =>$now,
                        'updated_at'=>$now
                    ];


            $transactionRow = \DB::table('ltech_transactions')->insertGetId($transaction_info);
            \App\System::EventLogWrite('insert,ltech_transactions',json_encode($transaction_info));

            $journal_debit_account_info = \DB::table('ltech_ledger_group_2')
                        ->where('ltech_ledger_group_2.ledger_name','Waste Goods')
                        ->first();


            $journal_debit_info = [
                                'journal_date' =>$now,
                                'journal_particular_id' =>$journal_debit_account_info->ledger_id,
                                'journal_particular_name' =>'Waste Goods',
                                'journal_particular_depth'=>$journal_debit_account_info->depth,
                                'journal_particular_naration' =>'Waste goods',
                                'journal_particular_amount_type'=>'debit',
                                'journal_particular_amount' =>$finish_goods_info->finish_goods_net_cost,
                                'cost_center_id' =>$finish_goods_transaction->cost_center_id,
                                'posting_type' =>'waste_goods_journal',
                                'transaction_id' =>$transactionRow,
                                'created_by' => \Auth::user()->user_id,
                                'updated_by' => \Auth::user()->user_id,
                                'created_at' =>$now,
                                'updated_at'=>$now
                            ];

                   
            $journal_debit_data = \DB::table('ltech_general_journal')->insert($journal_debit_info);
            \App\System::EventLogWrite('insert,ltech_general_journal.journal_debit_data',json_encode($journal_debit_info));


            $journal_credit_account_info = \DB::table('ltech_ledger_group_5')
                        ->where('ltech_ledger_group_5.ledger_name',$finish_goods_info->finish_goods_name)
                        ->first();
            #Finish Goods Credit insert

            $journal_credit_info = [
                    'journal_date' =>$now,
                    'journal_particular_id' =>$journal_credit_account_info->ledger_id,
                    'journal_particular_name' =>$journal_credit_account_info->ledger_name,
                    'journal_particular_depth'=>$journal_credit_account_info->depth,
                    'journal_particular_naration' =>'Waste goods',
                    'journal_particular_amount_type'=>'credit',
                    'journal_particular_amount' =>$finish_goods_info->finish_goods_net_cost,
                    'cost_center_id' =>$finish_goods_transaction->cost_center_id,
                    'posting_type' =>'waste_goods_journal',
                    'transaction_id' =>$transactionRow,
                    'created_by' => \Auth::user()->user_id,
                    'updated_by' => \Auth::user()->user_id,
                    'created_at' =>$now,
                    'updated_at'=>$now
                ];

       
            $journal_debit_data = \DB::table('ltech_general_journal')->insert($journal_credit_info);
            \App\System::EventLogWrite('insert,ltech_general_journal.journal_credit_data',json_encode($journal_credit_info));


            #Finishgoods Stocks Update
            $finish_goods_update_data = [
                        'finish_goods_waste_cost' =>$finish_goods_info->finish_goods_waste_cost+$finish_goods_info->finish_goods_net_cost,
                        'finish_goods_waste_quantity' =>$finish_goods_info->finish_goods_waste_quantity+$finish_goods_info->finish_goods_net_quantity,
                        'finish_goods_net_cost'=>0,
                        'finish_goods_net_quantity'=>0,
                        'goods_status'=>'1',
                        'updated_by' => \Auth::user()->user_id,
                        'updated_at'=>$now

                    ];


            $finish_goods_update = \DB::table('ltech_finish_goods_stocks')->where('finish_goods_id',$finish_goods_info->finish_goods_id)->update($finish_goods_update_data);
            \App\Journal::TransactionMeta($transactionRow,'ltech_finish_goods_stocks',$finish_goods_info->finish_goods_id);
            \App\Journal::TransactionMeta($transactionRow,'ltech_finish_goods_stocks-qty',$finish_goods_info->finish_goods_net_quantity);
            \DB::commit();
            return \Redirect::to('/delivery/finish-goods/list')->with('message',"Waste goods manage successfully!");



        }catch(\Exception $e){

            \DB::rollback();
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return \Redirect::to('/delivery/finish-goods/list')->with('errormessage',"Something wrong happend for finish goods!");
        }

    }



    ############################ End #################################
}
