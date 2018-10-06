<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*******************************
#
## Customer Controller
#
*******************************/

class CustomerController extends Controller
{
    public function __construct(){
	    $this->page_title = \Request::route()->getName();
	        $description = \Request::route()->getAction();
        $this->page_desc = isset($description['desc']) ?  $description['desc']:'';
	    \App\System::AccessLogWrite();

    }

    /********************************************
    # CustomerRegistration
    *********************************************/
    public function CustomerRegistration(){
        $customer_data=\DB::table('ltech_customers')
        			->paginate(1);
        $customer_data->setPath(url('/customer/registration'));
        $customer_pagination = $customer_data->render();

        $data['customer_pagination'] = $customer_pagination;
        $data['customer_data'] = $customer_data;
        $data['page_title'] = $this->page_title;
        return \View::make('pages.customer.customer-registration',$data);
    }



    /********************************************
    ## CustomerOrderPage
    *********************************************/
    public function CustomerOrderPage(){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;


        $data['customer_list'] = \App\Journal::GetLedgerAllChild('Account Receivable',3);
        $data['inventory_stocks_account'] = \App\Journal::GetLedgerAllChild('Cash-in-hand',3);
        $data['account_receivable'] = \App\Journal::GetLedgerChildByName('Account Receivable',3);
        $data['account_stock_in_hand'] = \App\Journal::GetLedgerChildByName('Cash-in-hand',3);



        $data['inventory_stocks_list'] = \DB::table('ltech_inventory_stocks')
        ->get();
        
        $data['cost_centers'] = \DB::table('ltech_cost_centers')->get();
        return \View::make('pages.customer.customer-order',$data);

    }

    /********************************************
    ## CustomerOrderInsert
    *********************************************/
    public function CustomerOrderInsert(){

        for ($i=1;$i<=\Request::input('sales_order_entry_field');$i++) {
            $rules_array['order_quantity_name_'.$i] =  'Required';
            $rules_array['sales_order_quantity_type_'.$i] =  'Required';
            $rules_array['sales_order_rate_'.$i] =  'Required';
            $rules_array['sales_order_quantity_'.$i] =  'Required|numeric';
        }

        $rules_array['order_customer_id'] = 'Required';
        $rules_array['order_description'] = 'Required';
        $rules_array['cost_center_id'] = 'Required';
        $rules_array['order_date'] =  'Required|date';
     

        $v= \Validator::make(\Request::all(), $rules_array);


        if($v->passes()){
            $order_customer_id = \Request::input('order_customer_id');
            $cost_center_id = \Request::input('cost_center_id');
            $order_date = \Request::input('order_date');
            $order_description = \Request::input('order_description');
            $order_delivery_date = \Request::input('order_delivery_date');

            $order_total_amount = 0;

            for($i=1;$i<=\Request::input('sales_order_entry_field');$i++){
                $order_total_amount = $order_total_amount + (\Request::input('sales_order_rate_'.$i) * \Request::input('sales_order_quantity_'.$i) );
            }

            $now= date('Y-m-d H:i:s');
            $order_customer_id_info = \DB::table('ltech_customers')->where('customer_account_id',$order_customer_id)->first();
      
            $customer_order_data = [
                'order_date' => $order_date,
                'order_customer_id' => $order_customer_id_info->customer_id,
                'order_description' => trim($order_description),
                'cost_center_id' => $cost_center_id,
                'order_net_amount' => $order_total_amount,
                'order_delivery_date' => $order_delivery_date,
                'order_status'=> 0,
                'created_by' =>\Auth::user()->user_id,
                'updated_by' =>\Auth::user()->user_id,
                'created_at' =>$now,
                'updated_at' =>$now,
            ];


            \DB::beginTransaction();
            try{
                /* order  insert*/
                $customer_order_insert = \DB::table('ltech_sales_orders')->insertGetId($customer_order_data);
                \App\System::EventLogWrite('insert,ltech_sales_orders',json_encode($customer_order_data));


                #Order Details Insert
                for($i=1; $i<=\Request::input('sales_order_entry_field'); $i++){

                    $order_details_data = [
                    'order_id' =>$customer_order_insert,
                    'order_customer_id' => $order_customer_id_info->customer_id,
                    'cost_center_id'=> $cost_center_id,
                    'order_item_name' => \Request::input('order_quantity_name_'.$i),
                    'order_item_quantity_type' =>\Request::input('sales_order_quantity_type_'.$i),
                    'order_item_quantity' =>\Request::input('sales_order_quantity_'.$i),
                    'order_item_quantity_rate' =>\Request::input('sales_order_rate_'.$i),
                    'order_item_cost'=> (\Request::input('sales_order_rate_'.$i) * \Request::input('sales_order_quantity_'.$i)),
                    'order_item_process_status' =>0,
                    'created_by' =>\Auth::user()->user_id,
                    'updated_by' =>\Auth::user()->user_id,
                    'created_at' =>$now,
                    'updated_at' =>$now,
                    ];
                    
                    
                    $order_details_data_insert = \DB::table('ltech_sales_order_details')->insertGetId($order_details_data);
                    \App\System::EventLogWrite('insert,ltech_sales_order_details',json_encode($order_details_data));


                    $all_order_data [] = $order_details_data;

                }

                //\Session::put('all_order_data',$all_order_data);

                \DB::commit();

                return \Redirect::to('/customer/order-list/'.$customer_order_insert)->with('message','Order successfully completed');

            }catch(\Exception $e){

                \DB::rollback();
                //\Session::forget('all_order_data');
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/customer/order')->with('errormessage','Something wrong happend'.$message);
            }
            

        }else return \Redirect::to('/customer/order')->withErrors($v->messages());
    } 

    /********************************************
    ## CustomerOrderIndividualList
    *********************************************/
    public function CustomerOrderIndividualList($order_id){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $ltech_sales_orders= \DB::table('ltech_sales_orders')->where('order_id',$order_id)->first();

        if(!empty($ltech_sales_orders)){

            $order_id=$ltech_sales_orders->order_id;
            $order_customer_id=$ltech_sales_orders->order_customer_id;

            $data['ltech_sales_order_details'] = \DB::table('ltech_sales_order_details')
                                        ->where('ltech_sales_order_details.order_id',$order_id)->get();
            $data['order_customer_details'] = \DB::table('ltech_customers')
                                        ->where('customer_id',$order_customer_id)->first();
            $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();

            $data['ltech_sales_orders'] =$ltech_sales_orders;
            return \View::make('pages.customer.customer-order-details-report',$data);

        }else return \Redirect::to('/error/request')->with('errormessage','Something Wrong in orders');

    } 




    /********************************************
    ## CustomerOrderPDF
    *********************************************/
    public function CustomerOrderPDF($order_id){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $ltech_sales_orders= \DB::table('ltech_sales_orders')->where('order_id',$order_id)->first();

        if(!empty($ltech_sales_orders)){

            $order_id=$ltech_sales_orders->order_id;
            $order_customer_id=$ltech_sales_orders->order_customer_id;

            $data['ltech_sales_order_details'] = \DB::table('ltech_sales_order_details')
                                        ->where('ltech_sales_order_details.order_id',$order_id)->get();
            $data['order_customer_details'] = \DB::table('ltech_customers')
                                        ->where('customer_id',$order_customer_id)->first();
            $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();

            $data['ltech_sales_orders'] =$ltech_sales_orders;
            //return \View::make('pages.customer.pdf.order-pdf',$data);

            $pdf = \PDF::loadView('pages.customer.pdf.order-pdf',$data);
            $pdfname = time().'_order.pdf';
            return $pdf->download($pdfname);

        }else return \Redirect::to('/error/request')->with('errormessage','Something Wrong in orders');

    } 



    /********************************************
    ## CustomerOrderPDFPrint
    *********************************************/
    public function CustomerOrderPDFPrint($order_id){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $ltech_sales_orders= \DB::table('ltech_sales_orders')->where('order_id',$order_id)->first();

        if(!empty($ltech_sales_orders)){

            $order_id=$ltech_sales_orders->order_id;
            $order_customer_id=$ltech_sales_orders->order_customer_id;

            $data['ltech_sales_order_details'] = \DB::table('ltech_sales_order_details')
                                        ->where('ltech_sales_order_details.order_id',$order_id)->get();
            $data['order_customer_details'] = \DB::table('ltech_customers')
                                        ->where('customer_id',$order_customer_id)->first();
            $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();

            $data['ltech_sales_orders'] =$ltech_sales_orders;
            return \View::make('pages.customer.pdf.order-pdf-print',$data);

        }else return \Redirect::to('/error/request')->with('errormessage','Something Wrong in orders');

    }


    /********************************************
    ## AjaxSalesOrderFieldEntry
    *********************************************/
    public function AjaxSalesOrderFieldEntry($filed_count){

        $data['i']=$filed_count;      
        return \View::make('pages.customer.ajax-customer-order',$data);
    }


    /********************************************
    ## AjaxSalesOrderInfo
    *********************************************/
    public function AjaxSalesOrderInfo($inventory_stock_id){

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
    # Customer Registration Confirm
    *********************************************/
    public function CustomerRegistrationConfirm(){
        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->user_id;
        $rule = [
                    'customer_name' => 'Required|max:25',
                    'customer_company' => 'Required|max:25',
                    'customer_mobile' => 'Required|regex:/^[^0-9]*(88)?0/|max:11',
                    'customer_email' => 'Required|email',
                    'customer_tax_reg_no' => '',
                    'customer_address' => 'Required',
                ];
        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
                $customer_account_info=\DB::table('ltech_customers')->latest()->first();
                if(!empty($customer_account_info)){
                    $customer_account_id=$customer_account_info->customer_id;
                }else $customer_account_id=0;

            $customer_company_slug = explode(' ', strtolower(\Request::input('customer_company')));
            $customer_company_slug = implode('_', $customer_company_slug);


         
            \DB::beginTransaction();


                $customer_company = \Request::input('customer_company');
                $customer_account_group = \Request::input('customer_account_group');
                $customer_account_group_depth = (int)(\Request::input('customer_account_group_depth'));


                $journal_info = \DB::table('ltech_ledger_group_'.$customer_account_group_depth)->where('ledger_name','LIKE',$customer_company)->first();

                $customer_info = \DB::table('ltech_customers')->where('customer_company','LIKE',$customer_company)->first();

                if(empty($journal_info) && empty($customer_info)){

                    /*Supplier account create*/
                    $customer_account = \App\Journal::JournalEntryInsert($customer_company, $customer_company_slug, ($customer_account_group_depth+1), $customer_account_group, 0, 0);

             
                    $journalupdate = \App\Journal::JournalUpdateParent($customer_account_group_depth,$customer_account_group);


                    \App\System::EventLogWrite('insert,ltech_ledger_group_'.$customer_account_group_depth,$customer_company_slug);



                $customer_data = [
                'customer_account_id' => $customer_account.'.'.($customer_account_group_depth+1),
                'customer_company' =>\Request::input('customer_company'),
                'customer_company_slug' => $customer_company_slug,
                'customer_name' =>\Request::input('customer_name'),
                'customer_mobile' =>\Request::input('customer_mobile'),
                'customer_email' =>\Request::input('customer_email'),
                'customer_tax_reg_no' =>\Request::input('customer_tax_reg_no'),
                'customer_address' =>\Request::input('customer_address'),
                'customer_status' =>0,
                'created_at' =>$now,
                'updated_at' =>$now,
                'created_by' =>$user,
                'updated_by' =>$user,
                ];
            try{

                \DB::table('ltech_customers')->insert($customer_data);
                \App\System::EventLogWrite('insert,ltech_customers',json_encode($customer_data));
                    \DB::commit();

                return \Redirect::back()->with('message',"Customer Registration Successfully!");



            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::back()->with('errormessage',"Info Already Exist !");
            }
        }

        }else return \Redirect::back()->withErrors($v->messages());
    }


    /********************************************
    # CustomerOrderDeliveryPage
    *********************************************/
    public function CustomerOrderDeliveryPage(){
        

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $data['customer_list'] = \DB::table('ltech_customers')->orderBy('customer_id','desc')->get();

        if(isset($_GET['customer_ref']) && !empty($_GET['customer_ref']) && isset($_GET['customer_id']) && !empty($_GET['customer_id']) && isset($_GET['customer']) && !empty($_GET['customer']) ){

           $customer_id=$_GET['customer_id'];

           $customer_info = \DB::table('ltech_customers')->where('customer_id',$_GET['customer_id'])->first();

           if(!empty($customer_info)){
                $data['customer_order_info'] = \DB::table('ltech_sales_orders')->where('order_customer_id',$customer_id)->get();

                $data['customer_info'] = $customer_info;
           }
        }

        if(isset($_GET['customer_ref']) && !empty($_GET['customer_ref']) && isset($_GET['customer_id']) && !empty($_GET['customer_id']) && isset($_GET['customer']) && !empty($_GET['customer']) && isset($_GET['customer_order_id']) && !empty($_GET['customer_order_id'])){

            $data['delivery_confirm_order_info'] = \DB::table('ltech_sales_order_details')->where('ltech_sales_order_details.order_id',$_GET['customer_order_id'])->leftjoin('ltech_sales_orders','ltech_sales_order_details.order_id','=','ltech_sales_orders.order_id')->get();

        }
       
        return \View::make('pages.customer.customer-delivery',$data);
    }


    /********************************************
    # CustomerOrderAjaxDelivery
    *********************************************/
    public function CustomerOrderAjaxDelivery($order_id,$field_count){

        $data['ltech_sales_order_details'] = \DB::table('ltech_sales_order_details')->where('order_id',$order_id)->get();

        $data['i'] = ($field_count==1) ? $field_count:($field_count+1);

        return \View::make('pages.customer.ajax-delivery-field',$data);
    }


    /********************************************
    ## CustomerOrderDeliveryConfirm
    *********************************************/
    public function CustomerOrderDeliveryConfirm(){

        $now=date('Y-m-d H:i:s');

        for ($i=1;$i<=\Request::input('delivery_confirm_entry_field');$i++) {
            $rules_array['delivery_quantity_name_'.$i] =  'Required';
            $rules_array['delivery_quantity_rate_'.$i] =  'Required|numeric';
            $rules_array['delivery_quantity_'.$i] =  'Required|numeric';
            $rules_array['delivery_order_item_id_'.$i] =  'Required';

        }

        $rules_array['delivery_customer_id'] = 'Required';
        $rules_array['delivery_order_id'] =  'Required';
        $rules_array['delivery_cost_center_id'] =  'Required';
        $rules_array['delivery_confirm_date'] =  'Required|date';
     

        $v= \Validator::make(\Request::all(), $rules_array);

        $parse_url = parse_url(\Request::fullUrl(), PHP_URL_QUERY);

        if($v->passes()){

            $delivery_confirm_date = \Request::input('delivery_confirm_date');
            $delivery_customer_id = \Request::input('delivery_customer_id');
            $delivery_order_id = \Request::input('delivery_order_id');
            $cost_center_id = \Request::input('delivery_cost_center_id');

            \DB::beginTransaction();
            try{
               #Main Loop
                $temp = array();
                $total_delivery_cost = 0;

                for($i=1;$i<=\Request::input('delivery_confirm_entry_field');$i++){
                   
                    $total_delivery_cost = $total_delivery_cost + (\Request::input('delivery_quantity_'.$i) * \Request::input('delivery_quantity_rate_'.$i));
                }

                $customer_info = \DB::table('ltech_customers')->where('customer_id',\Request::input('delivery_customer_id'))->first();
                                                    
                                                    

                #General Transactin
                $transaction_info = [
                            'transactions_date' =>$delivery_confirm_date,
                            'transactions_naration' =>"Customer Order Delivery",
                            'transaction_amount' =>$total_delivery_cost,
                            'cost_center_id' =>$cost_center_id,
                            'posting_type' =>'sales',
                            'created_by' => \Auth::user()->user_id,
                            'updated_by' => \Auth::user()->user_id,
                            'created_at' =>$now,
                            'updated_at'=>$now
                        ];


                $transactionRow = \DB::table('ltech_transactions')->insertGetId($transaction_info);
                \App\System::EventLogWrite('insert,ltech_transactions',json_encode($transaction_info));

                #Customer Debit Journal entry
                $customer_accounts_info = explode('.', $customer_info->customer_account_id);

                    $journal_debit_info = [
                            'journal_date' =>$delivery_confirm_date,
                            'journal_particular_id' =>$customer_accounts_info[0],
                            'journal_particular_name' =>$customer_info->customer_company,
                            'journal_particular_depth'=>$customer_accounts_info[1],
                            'journal_particular_naration' =>'Order goods delivery',
                            'journal_particular_amount_type'=>'debit',
                            'journal_particular_amount' =>$total_delivery_cost,
                            'cost_center_id' =>$cost_center_id,
                            'posting_type' =>'sales',
                            'transaction_id' =>$transactionRow,
                            'created_by' => \Auth::user()->user_id,
                            'updated_by' => \Auth::user()->user_id,
                            'created_at' =>$now,
                            'updated_at'=>$now
                        ];
               
                    $journal_debit_data = \DB::table('ltech_general_journal')->insert($journal_debit_info);
                    \App\System::EventLogWrite('insert,ltech_general_journal.journal_debit_data',json_encode($journal_debit_info));

                #Customer Info Update
                $customer_update_data = [
                        'customer_net_credit_amount' =>($customer_info->customer_net_credit_amount + $total_delivery_cost),
                        'customer_net_balance_amount' =>($customer_info->customer_net_balance_amount + $total_delivery_cost),
                        'updated_by' =>\Auth::user()->user_id,
                        'updated_at' => $now,
                    ];
                
                $customer_update = \DB::table('ltech_customers')->where('customer_id',$customer_info->customer_id)->update($customer_update_data);
                \App\System::EventLogWrite('update,ltech_customers.',json_encode($journal_debit_info));

                #Order Update
                $order_update_data = [ 
                                        'order_status' =>2,
                                        'order_delivered_customer_date' =>$delivery_confirm_date,
                                        'order_delivered_by' => \Auth::user()->user_id,
                                        'updated_by' =>\Auth::user()->user_id,
                                        'updated_at' => $now,
                                        'order_delivery_amount'=>$total_delivery_cost,
                                        'order_delivery_net_amount'=>$total_delivery_cost,
                                        'order_delivery_credit_amount' =>$total_delivery_cost,
                                        'order_delivery_balance_amount' =>$total_delivery_cost,
                                        'customer_order_delivery_net_balance_amount'=>$total_delivery_cost,
                                        'sales_referrence'=>$transactionRow
                                    ];

                $order_update = \DB::table('ltech_sales_orders')->where('order_id',$delivery_order_id)->update($order_update_data);
                \App\Journal::TransactionMeta($transactionRow,'ltech_sales_orders',$delivery_order_id);
                \App\System::EventLogWrite('update,ltech_sales_orders.',json_encode($journal_debit_info));


                for ($i=1;$i<=\Request::input('delivery_confirm_entry_field');$i++) {


                    #OrderDetailsUpdate
                    $finish_goods_and_order_info = \DB::table('ltech_sales_order_details')
                                                    ->where('order_details_id',\Request::input('delivery_order_item_id_'.$i))
                                                    ->join('ltech_finish_goods_stocks','ltech_sales_order_details.order_item_name','ltech_finish_goods_stocks.finish_goods_name')
                                                    ->first();

                    if(empty($finish_goods_and_order_info))
                        return \Redirect::to('/customer/order/delivery')->with('errormessage',"Finish goods missing for Order Delivery!");


                    $delivery_quantity = \Request::input('delivery_quantity_'.$i);
                    $delivery_quantity_rate = \Request::input('delivery_quantity_rate_'.$i);
                    $order_item_deliverd_cost = $delivery_quantity * $delivery_quantity_rate;
        

                    $order_details_update = [
                                    'order_item_deliverd_quantity' => $delivery_quantity,
                                    'order_item_deliverd_quantity_rate' => $delivery_quantity_rate,
                                    'order_item_deliverd_cost' => $order_item_deliverd_cost,       
                                    ];

                    $order_details_update_data = \DB::table('ltech_sales_order_details')->where('order_details_id',\Request::input('delivery_order_item_id_'.$i))->update($order_details_update);
                    \App\Journal::TransactionMeta($transactionRow,'ltech_sales_order_details',\Request::input('delivery_order_item_id_'.$i));

                    \App\System::EventLogWrite('update,ltech_sales_order_details',json_encode($order_details_update)); 

                    $select_finish_goods_info = \DB::table('ltech_finish_goods_stocks')->where('finish_goods_id',$finish_goods_and_order_info->finish_goods_id)->first();
                    $select_finish_goods_pro_cost=$select_finish_goods_info->finish_goods_net_production_cost;
                    $select_finish_goods_pro_quantity=$select_finish_goods_info->finish_goods_net_production_quantity;
                    $select_finish_goods_pro_rate=$select_finish_goods_pro_cost/$select_finish_goods_pro_quantity;
                    $production_deliverd_cost=$delivery_quantity*$select_finish_goods_pro_rate;
                    

                    #Finishgoods Stocks Update
                    $finish_goods_update_data = [
                                'finish_goods_net_sales_cost' =>$finish_goods_and_order_info->finish_goods_net_sales_cost+$order_item_deliverd_cost,
                                'finish_goods_net_sales_quantity' =>$finish_goods_and_order_info->finish_goods_net_sales_quantity+$delivery_quantity,
                                // 'finish_goods_net_cost'=>$finish_goods_and_order_info->finish_goods_net_cost-$order_item_deliverd_cost,
                                'finish_goods_net_cost'=>$finish_goods_and_order_info->finish_goods_net_cost-$production_deliverd_cost,
                                'finish_goods_net_quantity'=>$finish_goods_and_order_info->finish_goods_net_quantity-$delivery_quantity,
                                'goods_status'=>'1',
                                'updated_by' => \Auth::user()->user_id,
                                'updated_at'=>$now

                            ];

                    $finish_goods_update = \DB::table('ltech_finish_goods_stocks')->where('finish_goods_id',$finish_goods_and_order_info->finish_goods_id)->update($finish_goods_update_data);
                    \App\Journal::TransactionMeta($transactionRow,'ltech_finish_goods_stocks',$finish_goods_and_order_info->finish_goods_id);

                    \App\System::EventLogWrite('update,ltech_finish_goods_stocks',json_encode($finish_goods_update_data));

                    #Finish Goods Transaction
                    $opening_transaction_finish_goods_quantity = empty($finish_goods_and_order_info->finish_goods_net_sales_quantity) ? $delivery_quantity : $finish_goods_and_order_info->finish_goods_net_sales_quantity;
                    $closing_transaction_finish_goods_quantity = empty($finish_goods_and_order_info->finish_goods_net_sales_quantity) ? $delivery_quantity : $finish_goods_and_order_info->finish_goods_net_sales_quantity+$delivery_quantity;

                    $opening_transaction_finish_goods_cost = empty($finish_goods_and_order_info->finish_goods_net_cost) ? $order_item_deliverd_cost : $finish_goods_and_order_info->finish_goods_net_cost;
                    $closing_transaction_finish_goods_cost = empty($finish_goods_and_order_info->finish_goods_net_sales_cost) ? $order_item_deliverd_cost : $finish_goods_and_order_info->finish_goods_net_sales_cost+$order_item_deliverd_cost;
                    // $closing_transaction_finish_goods_cost =$order_item_deliverd_cost;


                    $finish_transaction_insert = [
                                                    'finish_goods_transaction_date'=>$delivery_confirm_date,
                                                    'finish_goods_accounts_id' =>$finish_goods_and_order_info->finish_goods_accounts_id,
                                                    'finish_goods_id' =>$finish_goods_and_order_info->finish_goods_id,
                                                    'cost_center_id' =>$finish_goods_and_order_info->cost_center_id,
                                                    'finish_goods_type' => 'ordered',
                                                    'finish_goods_transaction_type' =>'outwards',
                                                    'opening_transaction_finish_goods_quantity'=>$opening_transaction_finish_goods_quantity,
                                                    'transaction_finish_goods_quantity' =>$delivery_quantity,
                                                    'closing_transaction_finish_goods_quantity'=>$closing_transaction_finish_goods_quantity,
                                                    'finish_goods_quantity_rate' =>$delivery_quantity_rate,
                                                    'opening_transaction_finish_goods_cost' =>$opening_transaction_finish_goods_cost,
                                                    'finish_goods_quantity_cost'=>$order_item_deliverd_cost,
                                                    'closing_transaction_finish_goods_cost'=>$closing_transaction_finish_goods_cost,
                                                    'finish_goods_inventory' =>$finish_goods_and_order_info->order_item_process_list,
                                                    'customer_id' =>$delivery_customer_id,
                                                    'referrence' =>$transactionRow,
                                                    'created_by' => \Auth::user()->user_id,
                                                    'updated_by' => \Auth::user()->user_id,
                                                    'created_at' =>$now,
                                                    'updated_at' =>$now,


                                                ];

                    $finish_transaction_insert_data = \DB::table('ltech_finish_goods_transactions')->insertGetId($finish_transaction_insert);
                    \App\Journal::TransactionMeta($transactionRow,'ltech_finish_goods_transactions',$finish_transaction_insert_data);

                    \App\System::EventLogWrite('insert,ltech_finish_goods_transactions.finish_goods_transaction_production_data',json_encode($finish_transaction_insert));

                    #Finish Goods Credit insert
                    $goods_accounts_info = explode('.', $finish_goods_and_order_info->finish_goods_accounts_id);

                    $journal_credit_info = [
                            'journal_date' =>$delivery_confirm_date,
                            'journal_particular_id' =>$goods_accounts_info[0],
                            'journal_particular_name' =>$finish_goods_and_order_info->order_item_name,
                            'journal_particular_depth'=>$goods_accounts_info[1],
                            'journal_particular_naration' =>'finish goods sales',
                            'journal_particular_amount_type'=>'credit',
                            'journal_particular_amount' =>$order_item_deliverd_cost,
                            'cost_center_id' =>$cost_center_id,
                            'posting_type' =>'sales',
                            'transaction_id' =>$transactionRow,
                            'created_by' => \Auth::user()->user_id,
                            'updated_by' => \Auth::user()->user_id,
                            'created_at' =>$now,
                            'updated_at'=>$now
                        ];
               
                    $journal_debit_data = \DB::table('ltech_general_journal')->insert($journal_credit_info);
                    \App\System::EventLogWrite('insert,ltech_general_journal.journal_credit_data',json_encode($journal_credit_info));
                }

                \DB::commit();

                return \Redirect::to('/customer/sales/invoice/order-'.$delivery_order_id)->with('message','Order has been successfully deliverd.');

            }catch(\Exception $e){

                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::to('/customer/order/delivery?'.$parse_url)->with('errormessage',"Something wrong happend for Order Delivery!");
            } 
              

        }else return \Redirect::to('/customer/order/delivery?'.$parse_url)->withErrors($v->messages());
    }


    /********************************************
    ## CustomerSalesInvoicePage
    *********************************************/
    public function CustomerSalesInvoicePage($order_id){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $ltech_sales_orders= \DB::table('ltech_sales_orders')->where('order_id',$order_id)->first();

        if(!empty($ltech_sales_orders)){

            $order_id=$ltech_sales_orders->order_id;
            $order_customer_id=$ltech_sales_orders->order_customer_id;

            $data['ltech_sales_order_details'] = \DB::table('ltech_sales_order_details')
                                        ->where('ltech_sales_order_details.order_id',$order_id)->get();
            $data['order_customer_details'] = \DB::table('ltech_customers')
                                        ->where('customer_id',$order_customer_id)->first();
            $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();

            $data['ltech_sales_orders'] =$ltech_sales_orders;
            return \View::make('pages.customer.customer-sales-invoice',$data);

        }else return \Redirect::to('/error/request')->with('errormessage','Something Wrong in orders');

    } 

    /********************************************
    ## CustomerSalesInvoicePrint
    *********************************************/
    public function CustomerSalesInvoicePrint($order_id){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $ltech_sales_orders= \DB::table('ltech_sales_orders')->where('order_id',$order_id)->first();

        if(!empty($ltech_sales_orders)){

            $order_id=$ltech_sales_orders->order_id;
            $order_customer_id=$ltech_sales_orders->order_customer_id;

            $data['ltech_sales_order_details'] = \DB::table('ltech_sales_order_details')
                                        ->where('ltech_sales_order_details.order_id',$order_id)->get();
            $data['order_customer_details'] = \DB::table('ltech_customers')
                                        ->where('customer_id',$order_customer_id)->first();
            $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();

            $data['ltech_sales_orders'] =$ltech_sales_orders;
            return \View::make('pages.customer.pdf.order-invoice-print',$data);

        }else return \Redirect::to('/error/request')->with('errormessage','Something Wrong in orders');

    } 
    /********************************************
    ## CustomerSalesInvoiceDownloadPDF
    *********************************************/
    public function CustomerSalesInvoiceDownloadPDF($order_id){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $ltech_sales_orders= \DB::table('ltech_sales_orders')->where('order_id',$order_id)->first();

        if(!empty($ltech_sales_orders)){

            $order_id=$ltech_sales_orders->order_id;
            $order_customer_id=$ltech_sales_orders->order_customer_id;

            $data['ltech_sales_order_details'] = \DB::table('ltech_sales_order_details')
                                        ->where('ltech_sales_order_details.order_id',$order_id)->get();
            $data['order_customer_details'] = \DB::table('ltech_customers')
                                        ->where('customer_id',$order_customer_id)->first();
            $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();

            $data['ltech_sales_orders'] =$ltech_sales_orders;
            //return \View::make('pages.customer.pdf.order-invoice-pdf',$data);

            $pdf = \PDF::loadView('pages.customer.pdf.order-invoice-pdf',$data);
            $pdfname = time().'_order-delivary.pdf';
            return $pdf->download($pdfname);

        }else return \Redirect::to('/error/request')->with('errormessage','Something Wrong in orders');

    } 

    /********************************************
    # CustomerPaymentPage
    *********************************************/
    public function CustomerPaymentPage(){
        //$data = \Session::forget('customer_payment_voucher_data');

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $data['customer_list'] = \DB::table('ltech_customers')->orderBy('customer_id','desc')->get();

        if(isset($_GET['customer_ref']) && !empty($_GET['customer_ref']) && isset($_GET['customer_id']) && !empty($_GET['customer_id']) && isset($_GET['customer']) && !empty($_GET['customer']) ){

           $customer_id=$_GET['customer_id'];
           $customer_info = \DB::table('ltech_customers')->where('customer_id',$_GET['customer_id'])->first();

            $customer_order_info = \DB::table('ltech_sales_orders')->where('order_customer_id',$customer_id)->where('order_status',2)->get();
            $data['customer_order_info'] = $customer_order_info;

           if(!empty($customer_info) && ($_GET['customer_ref']==$customer_info->customer_account_id)){
                $data['cost_centers'] = \DB::table('ltech_cost_centers')->get();
                $data['customer_order_transactions'] = \App\Inventory::CustomerAllOrderTransaction($customer_id);
                $data['customer_info'] = $customer_info;

                $data['customer_credit_transactions'] = \DB::table('ltech_customer_payment_transactions')
                ->where('ltech_customer_payment_transactions.customer_id',$customer_info->customer_id)
                ->leftjoin('ltech_customers','ltech_customer_payment_transactions.customer_id','=','ltech_customers.customer_id')
                ->leftjoin('ltech_sales_orders','ltech_customer_payment_transactions.order_id','=','ltech_sales_orders.order_id')
                ->orderBy('ltech_customer_payment_transactions.transaction_date','asc')
                ->get();
               // $data['customer_credit_transactions'] = \App\Inventory::customerCreditTransaction($customer_id);
            }

        }
        return \View::make('pages.customer.customer-payment',$data);
    }


    /********************************************
    # CustomerPaymentAccountSelectBox
    *********************************************/
    public function CustomerPaymentAccountSelectBox($method_type){
        
            $data['page_title'] = $this->page_title;
            if($method_type=='bank')
                $data['payment_account'] = \App\Journal::GetLedgerAllChild('Bank Accounts',3);
            if($method_type=='cash')
                $data['payment_account'] = \App\Journal::GetLedgerAllChild('Cash-in-hand',3);

            return \View::make('pages.customer.ajax-paymentAccountSelectOption',$data);
    }



    /********************************************
    # AjaxCustomerOrderAmount
    *********************************************/
    public function AjaxCustomerOrderAmount($customer_order_id){
        
        $data['page_title'] = $this->page_title;
        $customer_order_info= \DB::table('ltech_sales_orders')->where('order_id',$customer_order_id)->first();
        $data['customer_order_info'] = $customer_order_info;
        return \View::make('pages.customer.ajax-order-balance',$data);
    }


    /********************************************
    # AjaxCustomerOrderPayment
    *********************************************/
    public function AjaxCustomerOrderPayment($customer_order_id, $rid){
        
        $data['page_title'] = $this->page_title;
        $customer_order_info= \DB::table('ltech_sales_orders')
                            ->where('ltech_sales_orders.order_id',$customer_order_id)
                            ->first();
        $data['customer_order_info'] = $customer_order_info;
        $i=$rid;
        $data['i'] = $i;
        return \View::make('pages.customer.ajax-payment-addrow',$data);
    }





    /********************************************
    # CustomerPaymentSubmit
    *********************************************/
    public function CustomerPaymentSubmit(){


        $now=date('Y-m-d H:i:s');
        $user=\Auth::user()->user_id;
        $total_debit_amount=0;

        for ($i=1;$i<=\Request::input('payment_entry_field');$i++) {
            $rules_array['payment_order_id_'.$i] =  'Required';
            $rules_array['customer_paid_amount_'.$i] =  'Required|numeric';
        }

        $rules_array['customer_payment_method'] = 'Required';
        $rules_array['customer_paid_account'] = 'Required';
        $rules_array['customer_payment_date'] = 'Required';
        $rules_array['customer_pay_note'] = 'Required';


        $v = \Validator::make(\Request::all(),$rules_array);
        if($v->passes()){

                $customer_payment_account_id = \Request::input('customer_payment_account_id');
                $customer_info = $customer_payment_account_id;
                $customer_actual_account_info = explode('-', $customer_info);
                $customer_actual_account_id=$customer_actual_account_info[0];
                $customer_actual_account_name=$customer_actual_account_info[1];

                $all_journal_info = $customer_actual_account_info[0];
                $journal_info = explode('.', $all_journal_info);

                $customer_payment_method = \Request::input('customer_payment_method');
                $customer_paid_account = \Request::input('customer_paid_account');
                
                $customer_payment_date = \Request::input('customer_payment_date');
                $customer_pay_note = \Request::input('customer_pay_note');
                $customer_paid_account_info = explode('.', $customer_paid_account);

                var_dump($customer_paid_account_info[2]);

                \DB::beginTransaction();

                try{
                        for($i=1; $i<=\Request::input('payment_entry_field'); $i++){
                            $payment_order_id = \Request::input('payment_order_id_'.$i);
                            $customer_debit_amount = \Request::input('customer_paid_amount_'.$i);

                            $order_balance_amount_info = \DB::table('ltech_sales_orders')->where('order_id',$payment_order_id)->first();


                            #General Transactin
                            $transaction_info = [
                                        'transactions_date' =>$customer_payment_date,
                                        'transactions_naration' =>trim($customer_pay_note),
                                        'transaction_amount' =>$customer_debit_amount,
                                        'cost_center_id' =>$order_balance_amount_info->cost_center_id,
                                        'posting_type' =>'receipt',
                                        'created_by' => \Auth::user()->user_id,
                                        'updated_by' => \Auth::user()->user_id,
                                        'created_at' => $now,
                                        'updated_at' => $now,
                                    ];

                                
                            $transactionRow = \DB::table('ltech_transactions')->insertGetId($transaction_info);
                            \App\System::EventLogWrite('insert,ltech_transactions',json_encode($transaction_info));


                            if(!empty($order_balance_amount_info)){
                                $order_balance_amount=$order_balance_amount_info->order_delivery_balance_amount-$customer_debit_amount;

                                $customer_balance_amount_info = \DB::table('ltech_customers')->where('customer_id',$order_balance_amount_info->order_customer_id)->first();

                                $customer_sales_order_info = \DB::table('ltech_sales_orders')->where('order_id',$payment_order_id)->first();


                                    $update_payment_order_data = [

                                        'order_delivery_debit_amount'=>$customer_sales_order_info->order_delivery_debit_amount+$customer_debit_amount,
                                        'order_delivery_balance_amount'=>$customer_sales_order_info->order_delivery_balance_amount-$customer_debit_amount,
                                        'customer_order_delivery_net_balance_amount'=>$customer_balance_amount_info->customer_net_balance_amount-$customer_debit_amount,
                                        'customer_order_delivery_net_balance_amount' =>$customer_sales_order_info->customer_order_delivery_net_balance_amount-$customer_debit_amount,
                                        'order_status' => ($customer_sales_order_info->order_delivery_balance_amount-$customer_debit_amount)==0 ? 3:2 ,
                                        'updated_by'=> \Auth::user()->user_id,
                                        'updated_at'=> $now,
                                    ];

                                    

                                    $customer_order_update_data = \DB::table('ltech_sales_orders')->where('order_id',$payment_order_id)->update($update_payment_order_data);
                                    \App\Journal::TransactionMeta($transactionRow,'ltech_sales_orders',$payment_order_id);

                                    \App\System::EventLogWrite('update,ltech_sales_orders',json_encode($update_payment_order_data));
                            }else{
                                \DB::rollback();
                                 return \Redirect::to('/customer/payment')->with('errormessage',"Invalid Order ");
                            }



                    #journal debit for Bank/Cash
                        $customer_journal_info = $customer_actual_account_info[0];
                        $journal_dr_info = explode('.', $customer_journal_info);

                        $journal_debit_info = [
                                    'journal_date' =>$customer_payment_date,
                                     'journal_particular_id' =>$customer_paid_account_info[0],
                                    'journal_particular_name' =>$customer_paid_account_info[2],
                                    'journal_particular_depth'=>$customer_paid_account_info[1],
                                    'journal_particular_naration' =>trim($customer_pay_note),
                                    'journal_particular_amount_type'=>'debit',
                                    'journal_particular_amount' =>$customer_debit_amount,
                                    'cost_center_id' =>$order_balance_amount_info->cost_center_id,
                                    'posting_type' =>'receipt',
                                    'transaction_id' =>$transactionRow,
                                    'created_by' => \Auth::user()->user_id,
                                    'updated_by' => \Auth::user()->user_id,
                                    'created_at' =>$now,
                                    'updated_at' =>$now,
                                ];
                            
                        $journal_debit_data = \DB::table('ltech_general_journal')->insert($journal_debit_info);
                        \App\System::EventLogWrite('insert,ltech_general_journal.journal_debit_data',json_encode($journal_debit_info));



                    #journal Credit Customer
                        $customer_journal_credit_info = $customer_actual_account_info[0];
                        $journal_cr_info = explode('.', $customer_journal_credit_info);
                        $journal_credit_info = [ 
                            'journal_date' =>$customer_payment_date,
                            'journal_particular_id' =>$journal_cr_info[0],
                            'journal_particular_name' =>$customer_actual_account_info[1],
                            'journal_particular_depth'=>$journal_cr_info[1],
                            'journal_particular_naration' =>trim($customer_pay_note),
                            'journal_particular_amount_type'=>'credit',
                            'journal_particular_amount' =>$customer_debit_amount,
                            'cost_center_id' =>$order_balance_amount_info->cost_center_id,
                            'posting_type' =>'receipt',
                            'transaction_id' =>$transactionRow,
                            'created_by' => \Auth::user()->user_id,
                            'updated_by' => \Auth::user()->user_id,
                            'created_at' =>$now,
                            'updated_at' =>$now,
                        ];

                     
                        $journal_credit_data = \DB::table('ltech_general_journal')->insert($journal_credit_info);
                        \App\System::EventLogWrite('insert,ltech_general_journal.journal_credit_data',json_encode($journal_credit_info));

                    
                        #Customer Credit Transactions
                        $customer_get_info = \DB::table('ltech_customers')
                                            ->where('customer_account_id',$customer_actual_account_info[0])
                                            ->first();

                        $customer_id=$customer_get_info->customer_id;
                        $opening_customer_credit_amount=$customer_get_info->customer_net_balance_amount;
                        $closing_customer_credit_amount=$customer_get_info->customer_net_balance_amount-$customer_debit_amount;
                        $opening_customer_debit_amount=$customer_get_info->customer_net_debit_amount;
                        $closing_customer_debit_amount=$customer_get_info->customer_net_debit_amount+$customer_debit_amount;
                        $opening_customer_balance_amount=$customer_get_info->customer_net_balance_amount;
                        $closing_customer_balance_amount=$customer_get_info->customer_net_balance_amount-$customer_debit_amount;


                        $customer_payment_transaction_info = [
                                    'customer_id' =>$customer_id,
                                    'order_id' =>$payment_order_id,
                                    'opening_customer_credit_amount' =>$opening_customer_credit_amount,
                                    'closing_customer_credit_amount'=>$closing_customer_credit_amount,
                                    'opening_customer_debit_amount' =>$opening_customer_debit_amount,
                                    'closing_customer_debit_amount'=>$closing_customer_debit_amount,
                                    'opening_customer_balance_amount' =>$opening_customer_balance_amount,
                                    'closing_customer_balance_amount' =>$closing_customer_balance_amount,
                                    'payment_method' =>$customer_payment_method,
                                    'transaction_date' =>$customer_payment_date,
                                    'payment_account' =>$customer_payment_account_id,
                                    'transaction_amount' =>$customer_debit_amount,
                                    'referrence' =>$transactionRow,
                                    'created_by' => \Auth::user()->user_id,
                                    'updated_by' => \Auth::user()->user_id,
                                    'created_at' =>$now,
                                    'updated_at' =>$now,
                                ];

                            
                        $journal_debit_data = \DB::table('ltech_customer_payment_transactions')->insertGetId($customer_payment_transaction_info);
                        \App\Journal::TransactionMeta($transactionRow,'ltech_customer_payment_transactions',$journal_debit_data);
                
                        \App\System::EventLogWrite('insert,ltech_customer_payment_transactions',json_encode($customer_payment_transaction_info));


                        #Customer Info
                            $customer_credit_info = \DB::table('ltech_customers')
                                            ->where('customer_account_id',$customer_actual_account_id)
                                            ->first();

                            $customer_net_debit_amount=$customer_credit_info->customer_net_debit_amount;
                            $customer_net_credit_amount=$customer_credit_info->customer_net_credit_amount;
                            $customer_net_balance_amount=$customer_credit_info->customer_net_balance_amount;

                            $customer_credit_update_data = [
                                'customer_net_debit_amount'=>$customer_net_debit_amount+$customer_debit_amount,
                                'customer_net_balance_amount'=>$customer_net_balance_amount-$customer_debit_amount,
                                'updated_by'=> \Auth::user()->user_id,
                                'updated_at'=> $now,
                            ];


                            $customer_order_credit_update = \DB::table('ltech_customers')->where('customer_account_id',$customer_actual_account_id)->update($customer_credit_update_data);
                            \App\Journal::TransactionMeta($transactionRow,'ltech_customers',$customer_actual_account_id);

                            \App\System::EventLogWrite('update,ltech_customers',json_encode($customer_credit_update_data));
                            

                        $total_debit_amount=$total_debit_amount+$customer_debit_amount;

                    } //end for


                    ######## Payment Voucher #############
                    $data['customer_info'] = $customer_credit_info;
                    $data['payment_method'] = $customer_payment_method;
                    $data['total_debit_amount'] = $total_debit_amount;
                    $data['customer_pay_note'] = $customer_pay_note;
                    $data['customer_payment_date'] = $customer_payment_date;
                    $data['customer_pay_note'] = $customer_pay_note;
                    $data['payment_account_info'] = \App\Journal::JournalEntryinfo($journal_info[0],$journal_info[1]);
                    $data['customer_account_info'] = $customer_paid_account_info[2];

                    \Session::put('customer_payment_voucher_data',$data);

                    \DB::commit();
                    return \Redirect::to('/customer/payment/voucher/view');
                }catch(\Exception $e){

                    \DB::rollback();
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/customer/payment')->with('errormessage',"Info Already Exist !");
                }

        }else return \Redirect::to('/customer/payment')->with('errormessage', 'Please fill up all entry');
    }

    /********************************************
    # CustomerPaymentVoucherPage
    *********************************************/
    public function CustomerPaymentVoucherPage(){
        
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        if(\Session::has('customer_payment_voucher_data') && !empty(\Session::get('customer_payment_voucher_data'))){
            $data = \Session::get('customer_payment_voucher_data'); 
   
            return \View::make('pages.customer.customer-payment-voucher',$data);
        }else  return \Redirect::to('/customer/payment')->with('errormessage','No Payment voucher available');
            
    }



    /********************************************
    # CustomerPaymentPDFPage
    *********************************************/
    public function CustomerPaymentPDFPage(){
        
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        if(\Session::has('customer_payment_voucher_data') && !empty(\Session::get('customer_payment_voucher_data'))){
            $data = \Session::get('customer_payment_voucher_data');
           // \Session::forget('customer_payment_voucher_data');
            $pdf = \PDF::loadView('pages.customer.customer-payment-voucher-pdf',$data);
            $pdfname = time().'_customer_payment_voucher.pdf';
            return $pdf->download($pdfname);

            // return \View::make('pages.customer.customer-payment-voucher-pdf',$data);
        }else  return \Redirect::to('/customer/payment')->with('errormessage','No Payment voucher available');
            
    }


    /********************************************
    # CustomerPaymentPDFPrintPage
    *********************************************/
    public function CustomerPaymentPDFPrintPage(){
        
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        if(\Session::has('customer_payment_voucher_data') && !empty(\Session::get('customer_payment_voucher_data'))){
            $data = \Session::get('customer_payment_voucher_data');

            //\Session::forget('customer_payment_voucher_data');

            return \View::make('pages.customer.customer-payment-voucher-print',$data);
        }else  return \Redirect::to('/customer/payment')->with('errormessage','No Payment voucher available');
            
    }

    /********************************************
    ## CustomerAllOrderList
    *********************************************/
    public function CustomerAllOrderList(){
        $total_amount=0;
        $cost_center=0;
        $customer=0;


        if(isset($_GET['search_from'])  ||  isset($_GET['search_to'])  || isset($_GET['customer']) || isset($_GET['cost_center'])){

            $search_from = $_GET['search_from'].' 00:00:00';
            $search_to = $_GET['search_to'].' 23:59:59';
            $cost_center =(int)$_GET['cost_center'];
            $customer =(int)$_GET['customer'];

            $data['search_from'] = $search_from;
            $data['search_to'] = $search_to;
            $data['cost_center'] = $cost_center;
            $data['customer'] = $customer;




            if(!empty($_GET['search_from']) && !empty($_GET['search_to']) && !empty($_GET['customer']) && !empty($_GET['cost_center'])){

                $all_order_list= \DB::table('ltech_sales_orders')
                                ->where('ltech_sales_orders.order_customer_id', $customer)
                                ->where('ltech_sales_orders.cost_center_id', $cost_center)
                                ->wherebetween('ltech_sales_orders.order_date',[$search_from,$search_to])
                                ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                ->get();
            }

            elseif(!empty($_GET['search_from']) && !empty($_GET['search_to']) && !empty($_GET['customer'])){
                $all_order_list= \DB::table('ltech_sales_orders')
                                ->where('ltech_sales_orders.order_customer_id', $customer)
                                ->wherebetween('ltech_sales_orders.order_date',[$search_from,$search_to])
                                ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                ->get(); 
            }

            elseif(!empty($_GET['search_from']) && !empty($_GET['search_to']) && !empty($_GET['cost_center'])){
                $all_order_list= \DB::table('ltech_sales_orders')
                                ->where('ltech_sales_orders.cost_center_id', $cost_center)
                                ->wherebetween('ltech_sales_orders.order_date',[$search_from,$search_to])
                                ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                ->get(); 
            }

            elseif(!empty($_GET['search_from']) && !empty($_GET['search_to'])){
                $all_order_list= \DB::table('ltech_sales_orders')
                                ->wherebetween('ltech_sales_orders.order_date',[$search_from,$search_to])
                                ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                ->get();

            }
            elseif(!empty($_GET['customer']) && !empty($_GET['cost_center'])){
                $all_order_list= \DB::table('ltech_sales_orders')
                                ->where('ltech_sales_orders.order_customer_id', $customer)
                                ->where('ltech_sales_orders.cost_center_id', $cost_center)
                                ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                ->get();
            }


            $data['all_order_list'] = $all_order_list;

        }else{

            $now=date("Y-m-d");
            $all_order_list= \DB::table('ltech_sales_orders')
                            ->wherebetween('ltech_sales_orders.order_date',[$now,$now])
                            ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                            ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                            ->get();
            $data['all_order_list'] = $all_order_list;

            $data['search_from'] = $now;
            $data['search_to'] = $now;
            $data['cost_center'] = $cost_center;
            $data['customer'] = $customer;


        }


            foreach ($all_order_list as $key => $list){
                $total_amount=$total_amount+$list->order_net_amount;
            }
            $data['total_amount'] = $total_amount;


            $data['page_title'] = $this->page_title;
            $data['page_desc'] = $this->page_desc;
            // $data['cost_centers'] = \DB::table('ltech_cost_centers')->get();
        return \View::make('pages.customer.customer-all-order-list',$data);

    }


    /********************************************
    ## CustomerOrderListPDF
    *********************************************/
    public function CustomerOrderListPDF($search_from,$search_to,$cost_center,$customer){

        $total_amount=0;
        $data['search_from'] = $search_from;
        $data['search_to'] = $search_to;
        $data['cost_center'] = $cost_center;
        $data['customer'] = $customer;

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();


            if(!empty($search_from) && !empty($search_to) && !empty($cost_center) && !empty($customer)){

                $all_order_list= \DB::table('ltech_sales_orders')
                                ->where('ltech_sales_orders.order_customer_id', $customer)
                                ->where('ltech_sales_orders.cost_center_id', $cost_center)
                                ->wherebetween('ltech_sales_orders.updated_at',[$search_from,$search_to])
                                ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                ->get();
            }

            elseif(!empty($search_from) && !empty($search_to)   && !empty($customer)){
                $all_order_list= \DB::table('ltech_sales_orders')
                                ->where('ltech_sales_orders.order_customer_id', $customer)
                                ->wherebetween('ltech_sales_orders.order_date',[$search_from,$search_to])
                                ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                ->get(); 
            }

            elseif(!empty($search_from) && !empty($search_to) && !empty($cost_center)){
                $all_order_list= \DB::table('ltech_sales_orders')
                                ->where('ltech_sales_orders.cost_center_id', $cost_center)
                                ->wherebetween('ltech_sales_orders.order_date',[$search_from,$search_to])
                                ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                ->get(); 
            }

            elseif(!empty($search_from) && !empty($search_to)){
                $all_order_list= \DB::table('ltech_sales_orders')
                                ->wherebetween('ltech_sales_orders.order_date',[$search_from,$search_to])
                                ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                ->get();

            }
            elseif(!empty($cost_center)  && !empty($customer)){
                $all_order_list= \DB::table('ltech_sales_orders')
                                ->where('ltech_sales_orders.order_customer_id', $customer)
                                ->where('ltech_sales_orders.cost_center_id', $cost_center)
                                ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                ->get();
            }



        if(!empty($all_order_list)){

            $data['all_order_list'] =$all_order_list;

            foreach ($all_order_list as $key => $list){
                $total_amount=$total_amount+$list->order_net_amount;
            }
            $data['total_amount'] = $total_amount;

            //return \View::make('pages.customer.pdf.order-pdf',$data);

            $pdf = \PDF::loadView('pages.customer.pdf.order-list-pdf',$data);
            return $pdf->stream();

        }else return \Redirect::to('/error/request')->with('errormessage','Something Wrong in orders');

    } 



    /********************************************
    ## CustomerOrderListPrint
    *********************************************/
    public function CustomerOrderListPrint($search_from,$search_to,$cost_center,$customer){

        $total_amount=0;
        $data['search_from'] = $search_from;
        $data['search_to'] = $search_to;
        $data['cost_center'] = $cost_center;
        $data['customer'] = $customer;

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();


            if(!empty($search_from) && !empty($search_to) && !empty($cost_center) && !empty($customer)){

                $all_order_list= \DB::table('ltech_sales_orders')
                                ->where('ltech_sales_orders.order_customer_id', $customer)
                                ->where('ltech_sales_orders.cost_center_id', $cost_center)
                                ->wherebetween('ltech_sales_orders.updated_at',[$search_from,$search_to])
                                ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                ->get();
            }

            elseif(!empty($search_from) && !empty($search_to)   && !empty($customer)){
                $all_order_list= \DB::table('ltech_sales_orders')
                                ->where('ltech_sales_orders.order_customer_id', $customer)
                                ->wherebetween('ltech_sales_orders.order_date',[$search_from,$search_to])
                                ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                ->get(); 
            }

            elseif(!empty($search_from) && !empty($search_to) && !empty($cost_center)){
                $all_order_list= \DB::table('ltech_sales_orders')
                                ->where('ltech_sales_orders.cost_center_id', $cost_center)
                                ->wherebetween('ltech_sales_orders.order_date',[$search_from,$search_to])
                                ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                ->get(); 
            }

            elseif(!empty($search_from) && !empty($search_to)){
                $all_order_list= \DB::table('ltech_sales_orders')
                                ->wherebetween('ltech_sales_orders.order_date',[$search_from,$search_to])
                                ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                ->get();

            }
            elseif(!empty($cost_center)  && !empty($customer)){
                $all_order_list= \DB::table('ltech_sales_orders')
                                ->where('ltech_sales_orders.order_customer_id', $customer)
                                ->where('ltech_sales_orders.cost_center_id', $cost_center)
                                ->leftjoin('ltech_cost_centers','ltech_sales_orders.cost_center_id','=','ltech_cost_centers.cost_center_id')
                                ->leftjoin('ltech_customers','ltech_sales_orders.order_customer_id','=','ltech_customers.customer_id')
                                ->get();
            }



        if(!empty($all_order_list)){

            $data['all_order_list'] =$all_order_list;

            foreach ($all_order_list as $key => $list){
                $total_amount=$total_amount+$list->order_net_amount;
            }
            $data['total_amount'] = $total_amount;

            return \View::make('pages.customer.pdf.order-list-print',$data);


        }else return \Redirect::to('/error/request')->with('errormessage','Something Wrong in orders');

    } 




    /********************************************
    ## CustomerOrderDetailsList
    *********************************************/
    public function CustomerOrderDetailsList($order_id){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $ltech_sales_orders= \DB::table('ltech_sales_orders')->where('order_id',$order_id)->first();

        if(!empty($ltech_sales_orders)){

            $order_id=$ltech_sales_orders->order_id;
            $order_customer_id=$ltech_sales_orders->order_customer_id;

            $data['ltech_sales_order_details'] = \DB::table('ltech_sales_order_details')
                                            ->where('ltech_sales_order_details.order_id',$order_id)
                                            ->get();
            $data['order_customer_details'] = \DB::table('ltech_customers')
                                        ->where('customer_id',$order_customer_id)->first();
            $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();

            $data['ltech_sales_orders'] =$ltech_sales_orders;
            return \View::make('pages.customer.customer-order-details',$data);

        }else return \Redirect::to('/error/request')->with('errormessage','Something Wrong in orders');

    }


    /********************************************
    ## CustomerOrderDetailsPDF
    *********************************************/
    public function CustomerOrderDetailsPDF($order_id){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $ltech_sales_orders= \DB::table('ltech_sales_orders')->where('order_id',$order_id)->first();

        if(!empty($ltech_sales_orders)){

            $order_id=$ltech_sales_orders->order_id;
            $order_customer_id=$ltech_sales_orders->order_customer_id;

            $data['ltech_sales_order_details'] = \DB::table('ltech_sales_order_details')
                                        ->where('ltech_sales_order_details.order_id',$order_id)->get();
            $data['order_customer_details'] = \DB::table('ltech_customers')
                                        ->where('customer_id',$order_customer_id)->first();
            $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();

            $data['ltech_sales_orders'] =$ltech_sales_orders;
            //return \View::make('pages.customer.pdf.order-pdf',$data);

            $pdf = \PDF::loadView('pages.customer.pdf.order-details-pdf',$data);
            $pdfname = time().'_order_invoice.pdf';
            return $pdf->download($pdfname);
             

        }else return \Redirect::to('/error/request')->with('errormessage','Something Wrong in orders');

    } 



    /********************************************
    ## CustomerOrderDetailsPDFPrint
    *********************************************/
    public function CustomerOrderDetailsPDFPrint($order_id){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $ltech_sales_orders= \DB::table('ltech_sales_orders')->where('order_id',$order_id)->first();

        if(!empty($ltech_sales_orders)){

            $order_id=$ltech_sales_orders->order_id;
            $order_customer_id=$ltech_sales_orders->order_customer_id;

            $data['ltech_sales_order_details'] = \DB::table('ltech_sales_order_details')
                                        ->where('ltech_sales_order_details.order_id',$order_id)->get();
            $data['order_customer_details'] = \DB::table('ltech_customers')
                                        ->where('customer_id',$order_customer_id)->first();
            $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();

            $data['ltech_sales_orders'] =$ltech_sales_orders;
            return \View::make('pages.customer.pdf.order-details-print',$data);

        }else return \Redirect::to('/error/request')->with('errormessage','Something Wrong in orders');

    }

    /********************************************
    # CustomerSalesReturnPage
    *********************************************/
    public function CustomerSalesReturnPage(){
        

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $data['customer_list'] = \DB::table('ltech_customers')->orderBy('customer_id','desc')->get();

        if(isset($_GET['customer_ref']) && !empty($_GET['customer_ref']) && isset($_GET['customer_id']) && !empty($_GET['customer_id']) && isset($_GET['customer']) && !empty($_GET['customer']) ){

           $customer_id=$_GET['customer_id'];

           $customer_info = \DB::table('ltech_customers')->where('customer_id',$_GET['customer_id'])->first();

           if(!empty($customer_info)){
                $data['customer_order_info'] = \DB::table('ltech_sales_orders')->where('order_customer_id',$customer_id)->get();

                $data['customer_info'] = $customer_info;
           }
        }

        if(isset($_GET['customer_ref']) && !empty($_GET['customer_ref']) && isset($_GET['customer_id']) && !empty($_GET['customer_id']) && isset($_GET['customer']) && !empty($_GET['customer']) && isset($_GET['customer_return_order_id']) && !empty($_GET['customer_return_order_id'])){

            $data['return_order_info'] = \DB::table('ltech_sales_orders')
                                                    ->where('ltech_sales_orders.order_id',$_GET['customer_return_order_id'])
                                                    ->join('ltech_sales_order_details','ltech_sales_order_details.order_id','=','ltech_sales_orders.order_id')->get();

            if(isset($_GET['order_item_id']) && !empty($_GET['order_item_id'])){
                $order_item_id=$_GET['order_item_id'];
                $data['return_order_info'] = \DB::table('ltech_sales_order_details')
                                                    ->where('ltech_sales_order_details.order_details_id', $order_item_id)
                                                    ->join('ltech_sales_orders','ltech_sales_order_details.order_id','=','ltech_sales_orders.order_id')
                                                    ->get();

                                                 
            }
        }
       
       return \View::make('pages.customer.customer-sales-return',$data);
    }

    /********************************************
    ## CustomerSalesReturnSubmit
    *********************************************/
    public function CustomerSalesReturnSubmit(){

        $now=date('Y-m-d H:i:s');

        for ($i=1;$i<=\Request::input('return_confirm_entry_field');$i++) {
            $rules_array['return_quantity_name_'.$i] =  'Required';
            $rules_array['return_quantity_rate_'.$i] =  'Required|numeric';
            $rules_array['return_quantity_'.$i] =  'Required|numeric';
            $rules_array['return_order_item_id_'.$i] =  'Required';
            $rules_array['return_amount_'.$i]= 'Required|numeric';

        }

        $rules_array['return_customer_id'] = 'Required';
        $rules_array['return_order_id'] =  'Required';
        $rules_array['return_cost_center_id'] =  'Required';
        $rules_array['return_sales_date'] =  'Required|date';
     

        $v= \Validator::make(\Request::all(), $rules_array);

        $parse_url = parse_url(\Request::fullUrl(), PHP_URL_QUERY);

        if($v->passes()){

            $return_sales_date = \Request::input('return_sales_date');
            $return_customer_id = \Request::input('return_customer_id');
            $return_order_id = \Request::input('return_order_id');
            $cost_center_id = \Request::input('return_cost_center_id');

            \DB::beginTransaction();
            try{
               #Main Loop
                $temp = array();
                $total_return_cost = 0;

                for($i=1;$i<=\Request::input('return_confirm_entry_field');$i++){
                   
                    $total_return_cost = $total_return_cost + (\Request::input('return_quantity_'.$i) * \Request::input('return_quantity_rate_'.$i) );
                }

                $customer_info = \DB::table('ltech_customers')->where('customer_id',$return_customer_id)->first();
                                                    
                                                    

                #General Transactin
                $transaction_info = [
                            'transactions_date' =>$return_sales_date,
                            'transactions_naration' =>"Customer Sales Return",
                            'transaction_amount' =>$total_return_cost,
                            'cost_center_id' =>$cost_center_id,
                            'posting_type' =>'sales_return',
                            'created_by' => \Auth::user()->user_id,
                            'updated_by' => \Auth::user()->user_id,
                            'created_at' =>$now,
                            'updated_at'=>$now
                        ];


                $transactionRow = \DB::table('ltech_transactions')->insertGetId($transaction_info);
                \App\System::EventLogWrite('insert,ltech_transactions',json_encode($transaction_info));

                #Customer credit Journal entry
                $customer_accounts_info = explode('.', $customer_info->customer_account_id);

                    $journal_credit_info = [
                            'journal_date' =>$return_sales_date,
                            'journal_particular_id' =>$customer_accounts_info[0],
                            'journal_particular_name' =>$customer_info->customer_company,
                            'journal_particular_depth'=>$customer_accounts_info[1],
                            'journal_particular_naration' =>'Customer Sales Return',
                            'journal_particular_amount_type'=>'credit',
                            'journal_particular_amount' =>$total_return_cost,
                            'cost_center_id' =>$cost_center_id,
                            'posting_type' =>'sales_return',
                            'transaction_id' =>$transactionRow,
                            'created_by' => \Auth::user()->user_id,
                            'updated_by' => \Auth::user()->user_id,
                            'created_at' =>$now,
                            'updated_at'=>$now
                        ];
               
                    $journal_credit_data = \DB::table('ltech_general_journal')->insert($journal_credit_info);
                    \App\System::EventLogWrite('insert,ltech_general_journal.journal_debit_data',json_encode($journal_credit_info));

                #Customer Info Update
                $customer_update_data = [
                        'customer_net_credit_amount' =>($customer_info->customer_net_credit_amount - $total_return_cost),
                        'customer_net_balance_amount' =>($customer_info->customer_net_balance_amount - $total_return_cost),
                        'updated_by' =>\Auth::user()->user_id,
                        'updated_at' => $now,
                    ];
                
                $customer_update = \DB::table('ltech_customers')->where('customer_id',$customer_info->customer_id)->update($customer_update_data);
                \App\System::EventLogWrite('update,ltech_customers.',json_encode($customer_update_data));
                \App\Journal::TransactionMeta($transactionRow,'ltech_customers',$customer_info->customer_id);


                $order_info = \DB::table('ltech_sales_orders')->where('order_id',$return_order_id)->where('order_status','2')->first();
                #Order Update
                $order_update_data = [ 
                                        'updated_by' =>\Auth::user()->user_id,
                                        'updated_at' => $now,
                                        'order_delivery_amount'=>$order_info->order_delivery_amount-$total_return_cost,
                                        'order_delivery_net_amount'=>$order_info->order_delivery_net_amount-$total_return_cost,
                                        'order_delivery_credit_amount' =>$order_info->order_delivery_credit_amount-$total_return_cost,
                                        'order_delivery_balance_amount' =>$order_info->order_delivery_balance_amount-$total_return_cost,
                                        'customer_order_delivery_net_balance_amount'=>$order_info->customer_order_delivery_net_balance_amount-$total_return_cost,
                                        'sales_return_referrence'=>$transactionRow
                                    ];

                $order_update = \DB::table('ltech_sales_orders')->where('order_id',$return_order_id)->update($order_update_data);
                \App\Journal::TransactionMeta($transactionRow,'ltech_sales_orders',$return_order_id);
                \App\System::EventLogWrite('update,ltech_sales_orders.',json_encode($order_update_data));



                for ($i=1;$i<=\Request::input('return_confirm_entry_field');$i++) {


                    #OrderDetailsUpdate
                    $finish_goods_and_order_info = \DB::table('ltech_sales_order_details')
                                                    ->where('order_details_id',\Request::input('return_order_item_id_'.$i))
                                                    ->join('ltech_finish_goods_stocks','ltech_sales_order_details.order_item_name','ltech_finish_goods_stocks.finish_goods_name')
                                                    ->first();

                    if(empty($finish_goods_and_order_info))
                        return \Redirect::to('/customer/sales/return')->with('errormessage',"Finish goods missing for Order Delivery!");


                    $return_quantity = \Request::input('return_quantity_'.$i);
                    $return_quantity_rate = \Request::input('return_quantity_rate_'.$i);
                    $order_item_return_cost = $return_quantity * $return_quantity_rate;
        

                    $order_details_update = [
                                    'order_item_process_status' => 5,
                                    'order_item_deliverd_quantity' =>$finish_goods_and_order_info->order_item_deliverd_quantity-$return_quantity,
                                    // 'order_item_deliverd_quantity_rate' =>$finish_goods_and_order_info->order_item_deliverd_quantity_rate-$return_quantity_rate,
                                    'order_item_deliverd_quantity_rate' =>$finish_goods_and_order_info->order_item_deliverd_quantity_rate,
                                    'order_item_deliverd_cost' =>$finish_goods_and_order_info->order_item_deliverd_cost- $order_item_return_cost,       
                                    ];

                    $order_details_update_data = \DB::table('ltech_sales_order_details')->where('order_details_id',\Request::input('return_order_item_id_'.$i))->update($order_details_update);
                    \App\System::EventLogWrite('update,ltech_sales_order_details',json_encode($order_details_update)); 
                    \App\Journal::TransactionMeta($transactionRow,'ltech_sales_order_details',\Request::input('return_order_item_id_'.$i));

            ################# 05-03-2017#################
                $select_finish_goods = \DB::table('ltech_finish_goods_stocks')->where('finish_goods_id',$finish_goods_and_order_info->finish_goods_id)->first();
                    $finish_goods_net_production_cost=$select_finish_goods->finish_goods_net_production_cost;
                    $finish_goods_net_production_quantity=$select_finish_goods->finish_goods_net_production_quantity;
                    $finish_goods_rate=$finish_goods_net_production_cost/$finish_goods_net_production_quantity;
            ################# 05-03-2017##################

                    #Finishgoods Stocks Update
                    $finish_goods_update_data = [
                                'finish_goods_net_sales_cost' =>$finish_goods_and_order_info->finish_goods_net_sales_cost-$order_item_return_cost,
                                'finish_goods_net_sales_quantity' =>$finish_goods_and_order_info->finish_goods_net_sales_quantity-$return_quantity,
                                // 'finish_goods_net_cost'=>$finish_goods_and_order_info->finish_goods_net_cost+$order_item_return_cost,
                                'finish_goods_net_cost'=>$select_finish_goods->finish_goods_net_cost+$finish_goods_rate*$return_quantity,
                                'finish_goods_net_quantity'=>$finish_goods_and_order_info->finish_goods_net_quantity+$return_quantity,
                                'updated_by' => \Auth::user()->user_id,
                                'updated_at'=>$now

                            ];

                    $finish_goods_update = \DB::table('ltech_finish_goods_stocks')->where('finish_goods_id',$finish_goods_and_order_info->finish_goods_id)->update($finish_goods_update_data);

                    \App\System::EventLogWrite('update,ltech_finish_goods_stocks',json_encode($finish_goods_update_data));
                    \App\Journal::TransactionMeta($transactionRow,'ltech_finish_goods_stocks',$finish_goods_and_order_info->finish_goods_id);


                    #Finish Goods Transaction
                    $opening_transaction_finish_goods_quantity =  $finish_goods_and_order_info->finish_goods_net_sales_quantity;
                    $closing_transaction_finish_goods_quantity = $finish_goods_and_order_info->finish_goods_net_sales_quantity-$return_quantity;

                    $opening_transaction_finish_goods_cost =  $finish_goods_and_order_info->finish_goods_net_sales_cost;
                    $closing_transaction_finish_goods_cost = $finish_goods_and_order_info->finish_goods_net_sales_cost-$order_item_return_cost;


                    $finish_transaction_insert = [
                                                    'finish_goods_transaction_date'=>$return_sales_date,
                                                    'finish_goods_accounts_id' =>$finish_goods_and_order_info->finish_goods_accounts_id,
                                                    'finish_goods_id' =>$finish_goods_and_order_info->finish_goods_id,
                                                    'cost_center_id' =>$finish_goods_and_order_info->cost_center_id,
                                                    'customer_id' =>$return_customer_id,
                                                    'finish_goods_type' => 'ordered',
                                                    'finish_goods_transaction_type' =>'return',
                                                    'opening_transaction_finish_goods_quantity'=>$opening_transaction_finish_goods_quantity,
                                                    'transaction_finish_goods_quantity' =>$return_quantity,
                                                    'closing_transaction_finish_goods_quantity'=>$closing_transaction_finish_goods_quantity,
                                                    'finish_goods_quantity_rate' =>$return_quantity_rate,
                                                    'opening_transaction_finish_goods_cost' =>$opening_transaction_finish_goods_cost,
                                                    'finish_goods_quantity_cost'=>$order_item_return_cost,
                                                    'closing_transaction_finish_goods_cost'=>$closing_transaction_finish_goods_cost,
                                                    'finish_goods_inventory' =>$finish_goods_and_order_info->order_item_process_list,
                                                    'referrence' =>$transactionRow,
                                                    'created_by' => \Auth::user()->user_id,
                                                    'updated_by' => \Auth::user()->user_id,
                                                    'created_at' =>$now,
                                                    'updated_at' =>$now,


                                                ];

                    $finish_transaction_insert_data = \DB::table('ltech_finish_goods_transactions')->insertGetId($finish_transaction_insert);
                    \App\System::EventLogWrite('insert,ltech_finish_goods_transactions.finish_goods_transaction_production_data',json_encode($finish_transaction_insert));
                    \App\Journal::TransactionMeta($transactionRow,'ltech_finish_goods_transactions',$finish_transaction_insert_data);


                    #Finish Goods Credit insert
                    $goods_accounts_info = explode('.', $finish_goods_and_order_info->finish_goods_accounts_id);

                    $journal_debit_info = [
                            'journal_date' =>$return_sales_date,
                            'journal_particular_id' =>$goods_accounts_info[0],
                            'journal_particular_name' =>$finish_goods_and_order_info->order_item_name,
                            'journal_particular_depth'=>$goods_accounts_info[1],
                            'journal_particular_naration' =>'finish goods sales return',
                            'journal_particular_amount_type'=>'debit',
                            'journal_particular_amount' =>$order_item_return_cost,
                            'cost_center_id' =>$cost_center_id,
                            'posting_type' =>'sales_return',
                            'transaction_id' =>$transactionRow,
                            'created_by' => \Auth::user()->user_id,
                            'updated_by' => \Auth::user()->user_id,
                            'created_at' =>$now,
                            'updated_at'=>$now
                        ];
               
                    $journal_debit_data = \DB::table('ltech_general_journal')->insert($journal_debit_info);
                    \App\System::EventLogWrite('insert,ltech_general_journal.journal_credit_data',json_encode($journal_debit_info));

                    //$all_finish_transaction ['finish_tran_'.$i]=$finish_transaction_insert_data;
                    
                }

                \Session::put('sales_return_all_data',\Request::all());
                \DB::commit();
                return \Redirect::to('/customer/sales/return/invoice');

            }catch(\Exception $e){

                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::to('/customer/sales/return?'.$parse_url)->with('errormessage',"Something wrong happend for Sales Return!");
            } 
              

        }else return \Redirect::to('/customer/sales/return?'.$parse_url)->withErrors($v->messages());
    }

     /********************************************
    ## CustomerSalesReturnInvoice
    *********************************************/
    public function CustomerSalesReturnInvoice(){

        if(\Session::has('sales_return_all_data')){
            $data['page_title'] = $this->page_title;
            $data['page_desc'] = $this->page_desc;
            $sales_return_all_data=\Session::get('sales_return_all_data');
            
            $data['company_info'] = \DB::table('company_details')->where('company_name','D. F Tex')->first();
            $data['order_customer_details'] = \DB::table('ltech_customers')
                                                ->where('customer_id',$sales_return_all_data['return_customer_id'])->first();
            $data['sales_return_all_data'] = $sales_return_all_data;
            $data['ltech_sales_orders'] = \DB::table('ltech_sales_orders')
                                                ->where('.order_id',$sales_return_all_data['return_order_id'])->first();
            \Session::put('sales_return_print',$data);
            return \View::make('pages.customer.customer-sales-return-invoice',$data);
        }else return \Redirect::to('/error/request');

    } 


    /********************************************
    ## CustomerSalesReturnInvoicePrint
    *********************************************/
    public function CustomerSalesReturnInvoicePrint(){

        if(\Session::has('sales_return_print')){
          
            $data=\Session::get('sales_return_print');
            return \View::make('pages.customer.pdf.sales-return-print',$data);
        }else return \Redirect::to('/error/request');
    }

    /********************************************
    ## CustomerSalesReturnInvoiceDownloadPDF
    *********************************************/
    public function CustomerSalesReturnInvoiceDownloadPDF(){

        if(\Session::has('sales_return_print')){
          
            $data=\Session::get('sales_return_print');
            //return \View::make('pages.customer.pdf.sales-return-pdf',$data);
            $pdf = \PDF::loadView('pages.customer.pdf.sales-return-pdf',$data);
            $pdfname = time().'_sales_return_bill.pdf';
            return $pdf->download($pdfname);
        }else return \Redirect::to('/error/request');
    }



    /********************************************
    ## CustomerListPage
    *********************************************/
    public function CustomerListPage(){

        $customer_lists=\DB::table('ltech_customers')
        ->paginate(10);
        $customer_lists->setPath(url('/customer/list'));
        $customer_pagination = $customer_lists->render();
        $data['customer_pagination'] = $customer_pagination;  
        $data['customer_lists'] = $customer_lists;  
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.customer.customer-list-page',$data);
    }


    /********************************************
    ## EditCustomerPage
    *********************************************/
    public function EditCustomerPage($customer_id){
        $selected_customer_list=\DB::table('ltech_customers')->where('customer_id',$customer_id)->first();
        $data['selected_customer_list'] = $selected_customer_list;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.customer.edit-customer-details',$data);

    }

    /********************************************
    ## UpdateCustomer
    *********************************************/
    public function UpdateCustomer($customer_id){
        

        $rule = [
        'customer_name' => 'Required',
        'customer_mobile' => 'Required|regex:/^[^0-9]*(88)?0/|max:11',
        'customer_email' => 'Required|email',
        'customer_tax_reg_no' => 'Required',
        'customer_address' => 'Required',
        ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){ 

            $now=date('Y-m-d H:i:s');
            $user =\Auth::user()->user_id;

            $update_customer_data = [

                'customer_name' =>\Request::input('customer_name'),
                'customer_mobile' =>\Request::input('customer_mobile'),
                'customer_email' =>\Request::input('customer_email'),
                'customer_tax_reg_no' =>\Request::input('customer_tax_reg_no'),
                'customer_address' =>\Request::input('customer_address'),
                'updated_at' =>$now,
                'updated_by' =>\Auth::user()->user_id,

            ];

            try{
                \DB::table('ltech_customers')->where('customer_id',$customer_id)->update($update_customer_data);
                \App\System::EventLogWrite('update',json_encode($update_customer_data));
            }catch(\Exception  $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::back()->with('message','Something wrong !!');
            }

            return \Redirect::to('/customer/list')->with('message','Customer Updated Successfully.');
        }else return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());

    }


############################### End Controller #####################################


}
