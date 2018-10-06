<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

/*******************************
#
## Journal Controller
#
*******************************/

class JournalController extends Controller
{
    public function __construct(){
        $this->page_title = \Request::route()->getName();
        $description = \Request::route()->getAction();
        $this->page_desc = isset($description['desc']) ?  $description['desc']:'';
        \App\System::AccessLogWrite();
    }

    /********************************************
    ## JournalPostingPage 
    *********************************************/
    public function JournalPostingPage(){

        if(isset($_GET['post_tab']) && !empty($_GET['post_tab'])){

            $data['post_tab'] = $_GET['post_tab'];

        }else $data['post_tab'] = 'panel_journal';

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        $data['journal_posting_field'] = \App\Journal::GetJournalEntryList();
        return \View::make('pages.journal.posting',$data);
    }


    /********************************************
    ## JuournalGroupDataInsert 
    *********************************************/
    public function JuournalGroupDataAddPage($ledger_id,$depth){


        if(isset($_GET['tab']) && !empty($_GET['tab'])){

            $data['tab'] = $_GET['tab'];

        }else $data['tab'] = 'add_ledger';
       
       if($depth==1)
            \Session::flash('errormessage','You cannot Add ledger Head in this level');

        $journalinfo= \App\Journal::JournalEntryinfo($ledger_id,$depth);

        if(!empty($journalinfo)){

           if($depth>1) 
             $data['journal_data_node']= \App\Journal::GetJournalData(($depth-1));
            else
                $data['journal_data_node'] = null;

            $data['journalinfo']= $journalinfo;
            $data['journal_level'] =$depth;

        }else \Session::flash('errormessage','Something worng happend.!!!');

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.journal.ledgeradd',$data); 
        

    }


    /********************************************
    ## JuournalGroupDataInsert 
    *********************************************/
    public function JuournalGroupDataInsert($ledger_id,$depth){


        if(\Request::input('add_type')=='add_ledger'){
             $rules=array(
                'add_ledger_name' => 'Required',
                'add_ledger_parent_id' => 'Required|not_in:0',
                'ledger_debit' => 'numeric',
                'ledger_credit' => 'numeric',
            );

        }else if(\Request::input('add_type')=='add_sub_ledger'){
             $rules=array(
                'add_sub_ledger_name' => 'Required',
                'ledger_debit' => 'numeric',
                'ledger_credit' => 'numeric',
            );
        }else return \Redirect::to('/journal/ledger-'.$ledger_id.'/depth-'.$depth)->with('errormessage',"Incorrect combinations.Please try again.");

        $type = \Request::input('add_type');
        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $ledger_debit = !empty(\Request::input('ledger_debit')) ? \Request::input('ledger_debit'):0;
            $ledger_credit = !empty(\Request::input('ledger_credit')) ? \Request::input('ledger_credit'):0;
            if($type=='add_ledger'){
                if($depth !=1){
                    $ledger_name = \Request::input('add_ledger_name');
                    $ledger_name_slug = explode(' ', strtolower(trim($ledger_name)));
                    $ledger_name_slug = implode('_', $ledger_name_slug);
                    $exits= \App\Journal::JournalEntryCheck($ledger_name_slug,$depth);
                    $parent_ledger = \Request::input('add_ledger_parent_id');
                    


                    if(count($exits) ==0 ){

                        $jurnalentry= \App\Journal::JournalEntryInsert($ledger_name,$ledger_name_slug,$depth,$parent_ledger,$ledger_debit,$ledger_credit);

                        $jurnalupdate = \App\Journal::JournalUpdateParent(($depth-1),$parent_ledger);

                      
                        return \Redirect::to('/journal/ledger-'.$ledger_id.'/depth-'.$depth)->with('message',"Successfully Added");  
                      
                     }else return \Redirect::to('/journal/ledger-'.$ledger_id.'/depth-'.$depth)->with('errormessage',"Alredy exits");  
                    
                }else return \Redirect::to('/journal/ledger-'.$ledger_id.'/depth-'.$depth)->with('errormessage',"You cannot create ledger.Its reserves for system"); 
            }else{

                $ledger_name = \Request::input('add_sub_ledger_name');
                $ledger_name_slug = explode(' ', strtolower(trim($ledger_name)));
                $ledger_name_slug = implode('_', $ledger_name_slug);
                $exits= \App\Journal::JournalEntryCheck($ledger_name_slug,($depth+1));
                if(count($exits) ==0 ){

                     $jurnalentry= \App\Journal::JournalEntryInsert($ledger_name,$ledger_name_slug,($depth+1),$ledger_id,$ledger_debit,$ledger_credit);

                    $jurnalupdate = \App\Journal::JournalUpdateParent($depth,$ledger_id);

                    return \Redirect::to('/journal/ledger-'.$ledger_id.'/depth-'.$depth)->with('message',"Successfully Added");  
                  
                 }else return \Redirect::to('/journal/ledger-'.$ledger_id.'/depth-'.$depth)->with('errormessage',"Alredy exits");
            }
        }else return  \Redirect::to('/journal/ledger-'.$ledger_id.'/depth-'.$depth.'?tab='.$type)->withInput()->withErrors($v->messages());

        
    }


    /********************************************
    ## JuournalAjaxSubnodeList
    *********************************************/
    public function JuournalAjaxSubnodeList($group_id,$group_number){

        $data['journal_data_child']= \App\Journal::GetJournalChildData($group_id,($group_number+1));
        $data['journal_level'] =$group_number;


        return \View::make('pages.journal.ajax-sub-node',$data);
    }


    /********************************************
    ## JournalPostingSave
    *********************************************/
    public function JournalPostingSave(){

        $rules=array(
            'transaction_amount' => 'Required|numeric',
            'debit_ledger' => 'Required',
            'debit_naration' => 'Required',
            'credit_ledger' => 'Required',
            'credit_naration' => 'Required',
            'transaction_details' => 'Required',
            'debit_ledger_depth' =>'Required',
            'credit_ledger_depth' =>'Required',
            );

        $v= \Validator::make(\Request::all(), $rules);


        $posting_type = \Request::input('posting_type');

        if($v->passes()){
          

            $transaction_amount = \Request::input('transaction_amount');
            $debit_ledger = \Request::input('debit_ledger');
            $debit_naration = \Request::input('debit_naration');
            $credit_ledger = \Request::input('credit_ledger');
            $credit_naration = \Request::input('credit_naration');
            $transaction_details = trim(\Request::input('transaction_details'));
            $cost_center =1;


            $transaction_info = \App\Journal::TransactionInsert($transaction_amount,$transaction_details,$cost_center,$posting_type);
         
            if($transaction_info->transactions_id != -1){

                $general_journal = \App\Journal::JournalTransactionInsert(\Request::all(),$cost_center,$transaction_info->transactions_id);

                 return \Redirect::to('/journal');

            }else return \Redirect::to('/journal')->with('errormessage',"Something wrong in Transaction.Please try again!!");
            

        }else return \Redirect::to('/journal?post_tab=panel_'.$posting_type)->withErrors($v->messages());
     
    }


    /********************************************
    ## JuournalTransactionView
    *********************************************/
    public function JuournalTransactionView(){

        $user=\Auth::user()->user_id;
        $now=date('Y-m-d');

        if(isset($_GET['search_from']) && isset($_GET['search_to']) || isset($_GET['cost_center']) || isset($_GET['post_type']) || isset($_GET['user_name'])){

            $search_from = $_GET['search_from'].' 00:00:00';
            $search_to = $_GET['search_to'].' 23:59:59';
            $cost_center = $_GET['cost_center'];
            $post_type = $_GET['post_type'];
            $user_name = $_GET['user_name'];
            

            $journal_transaction = \DB::table('ltech_general_journal')
                            ->where(function($query){

                               if(isset($_GET['cost_center']) && !empty($_GET['cost_center'])){
                                    $query->where(function ($q){
                                        $q->where('ltech_general_journal.cost_center_id', $_GET['cost_center']);
                                      });
                                }
                                if(isset($_GET['post_type']) && !empty($_GET['post_type'])){
                                    $query->where(function ($q){
                                        $q->where('ltech_general_journal.posting_type', $_GET['post_type']);
                                      });
                                }

                                if(isset($_GET['user_name']) && !empty($_GET['user_name'])){
                                    $query->where(function ($q){
                                        $q->where('ltech_general_journal.created_by', $_GET['user_name']);
                                      });
                                }

                            }) 

                            ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
                            ->join('ltech_cost_centers','ltech_general_journal.cost_center_id','like','ltech_cost_centers.cost_center_id')
                            ->leftjoin('ltech_posting_types','ltech_general_journal.posting_type','like','ltech_posting_types.posting_type_slug')
                            ->paginate(10);

            $journal_transaction->setPath(url('/journal/transaction'));
            $journal_pagination = $journal_transaction->appends(['search_from' => $_GET['search_from'], 'search_to'=> $_GET['search_to'],'post_type'=>$_GET['post_type'],'cost_center'=>$_GET['cost_center'],'user_name'=>$_GET['user_name']])->render();

        }else{
            $search_from = $now;
            $search_to = $now;

            $journal_transaction = \DB::table('ltech_general_journal')
                            ->leftjoin('ltech_cost_centers','ltech_general_journal.cost_center_id','=','ltech_cost_centers.cost_center_id')
                            ->leftjoin('ltech_posting_types','ltech_general_journal.posting_type','=','ltech_posting_types.posting_type_slug')
                            ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
                            ->paginate(10);

            $journal_transaction->setPath(url('/journal/transaction'));
            $journal_pagination = $journal_transaction->render();  
        }

        $data['journal_pagination']=$journal_pagination;
        $data['journal_transaction'] = \App\Journal::ArrayGroupingByKey($journal_transaction,'transaction_id');

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        
        return \View::make('pages.journal.transaction-detail',$data);
    }



    /********************************************
    ## JuournalTransactionViewByUser
    *********************************************/
    public function JuournalTransactionViewByUser(){

        $user=\Auth::user()->user_id;
        $now=date('Y-m-d');

        if(isset($_GET['search_from']) && isset($_GET['search_to']) || isset($_GET['cost_center']) || isset($_GET['post_type'])){

            $search_from = $_GET['search_from'].' 00:00:00';
            $search_to = $_GET['search_to'].' 23:59:59';
            $cost_center = $_GET['cost_center'];
            $post_type = $_GET['post_type'];

            $journal_transaction = \DB::table('ltech_general_journal')
                            ->where(function($query){

                               if(isset($_GET['cost_center']) && !empty($_GET['cost_center'])){
                                    $query->where(function ($q){
                                        $q->where('ltech_general_journal.cost_center_id', $_GET['cost_center']);
                                      });
                                }
                                if(isset($_GET['post_type']) && !empty($_GET['post_type'])){
                                    $query->where(function ($q){
                                        $q->where('ltech_general_journal.posting_type', $_GET['post_type']);
                                      });
                                }
                            }) 

                            ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
                            ->where('ltech_general_journal.created_by',$user)
                            ->join('ltech_cost_centers','ltech_general_journal.cost_center_id','like','ltech_cost_centers.cost_center_id')
                            ->leftjoin('ltech_posting_types','ltech_general_journal.posting_type','like','ltech_posting_types.posting_type_slug')
                            ->paginate(10);

            $journal_transaction->setPath(url('/journal/transaction'));
            $journal_pagination = $journal_transaction->appends(['search_from' => $_GET['search_from'], 'search_to'=> $_GET['search_to'],'post_type'=>$_GET['post_type'],'cost_center'=>$_GET['cost_center']])->render();
        }else{
            $search_from = $now;
            $search_to = $now;

            $journal_transaction = \DB::table('ltech_general_journal')
                            ->leftjoin('ltech_cost_centers','ltech_general_journal.cost_center_id','=','ltech_cost_centers.cost_center_id')
                            ->leftjoin('ltech_posting_types','ltech_general_journal.posting_type','=','ltech_posting_types.posting_type_slug')
                            ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
                            ->where('ltech_general_journal.created_by',$user)
                            ->paginate(10);

            $journal_transaction->setPath(url('/journal/transaction'));
            $journal_pagination = $journal_transaction->render(); 
        }

        $data['journal_pagination']=$journal_pagination;
        $data['journal_transaction'] = \App\Journal::ArrayGroupingByKey($journal_transaction,'transaction_id');

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        
        return \View::make('pages.journal.journal-list-by-user',$data);
    }


    /********************************************
    ## JuournalPostingPage
    *********************************************/
    public function JuournalPostingPage($posting_type){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        $posting_info = \DB::table('ltech_posting_types')->where('posting_type_slug','LIKE',$posting_type)->first();

        $ltech_transactions = \DB::table('ltech_transactions')->OrderBy('ltech_transactions.transactions_id','desc')->first();
        if(!empty($ltech_transactions)){
            $new_transaction_id=$ltech_transactions->transactions_id;
        }else{
            $new_transaction_id=0;
        }
        $new_transactions_id=$new_transaction_id+1;
        $data['new_transactions_id']=$new_transactions_id;
        if(!empty($posting_info)){
            $data['posting_type']=$posting_info->posting_type_slug;
            $data['posting_type_name']=$posting_info->posting_type;
        }else{
            $data['posting_type']='others_receipt';
            $data['posting_type_name']='Others Receipt';
        }
        
        $data['cost_centers'] = \DB::table('ltech_cost_centers')->get();
        $data['journal_posting_field'] = \App\Journal::GetJournalEntryList();
        return \View::make('pages.journal.posting-journal',$data);
    }

    /********************************************
    ## JuournalAjaxPostingField
    *********************************************/
    public function JuournalAjaxPostingField($add_type){

        $data['journal_posting_field'] = \App\Journal::GetJournalEntryList();
        $data['cost_centers'] = \DB::table('ltech_cost_centers')->get();

        if($add_type=='debit')
            return \View::make('pages.journal.more-debit',$data);
        else
            return \View::make('pages.journal.more-credit',$data);
       
        
    }


    /********************************************
    ## JuournalPostingGet
    *********************************************/
    public function JuournalPostingSubmit($posting_type){

       $rules=array(
            
            'debit_ledger.*' => 'Required',
            'debit_transaction_amount.*' => 'Required|numeric',
            'credit_ledger.*' => 'Required',
            'credit_transaction_amount.*' => 'Required|numeric',
            'transaction_details' => 'Required',
            'cost_center' => 'Required'
            );

        $v= \Validator::make(\Request::all(), $rules);


        if($v->passes()){

            $transactions_date = \Request::input('transactions_date');
            $cost_center = \Request::input('cost_center');

            $debit_ledger = \Request::input('debit_ledger');
            $debit_transaction_amount = \Request::input('debit_transaction_amount');
            $debit_costcenter = \Request::input('debit_costcenter');
            $debit_naration = \Request::input('debit_naration');

            $credit_ledger = \Request::input('credit_ledger');
            $credit_transaction_amount = \Request::input('credit_transaction_amount');
            $credit_costcenter = \Request::input('credit_costcenter');
            $credit_naration = \Request::input('credit_naration');

            $transaction_details = \Request::input('transaction_details');
            
            $posting_info = \DB::table('ltech_posting_types')->where('posting_type_slug','LIKE',$posting_type)->first();
            $total_debit_amount=0;
            /*debit ledger processing*/
            foreach ($debit_ledger as $key => $value) {
                
                $debit_account_info = \App\Journal::JournalEntryinfo($value,substr($value,0,1));

                if(!empty($debit_account_info)){

                   $all_debit[] =[
                                'debit_id' =>$debit_account_info->ledger_id,
                                'debit_name' =>$debit_account_info->ledger_name,
                                'debit_depth' =>$debit_account_info->depth,
                                'debit_naration' =>$debit_naration[$key],
                                'debit_transaction_amount' =>$debit_transaction_amount[$key],
                                'debit_costcenter' =>$cost_center,
                                'posting_type' =>$posting_type
                                
                            ]; 
                    $total_debit_amount = $total_debit_amount+$debit_transaction_amount[$key];

                }else return \Redirect::to('/journal/posting/type-'.$posting_type)->with('errormessage',"Debit Account Missing.Please try again!!");
            }

            $total_credit_amount=0;
            /*credit ledger processing*/
            foreach ($credit_ledger as $key => $value) {
                
                $credit_account_info = \App\Journal::JournalEntryinfo($value,substr($value,0,1));

                if(!empty($credit_account_info)){

                   $all_credit[] =[
                                'credit_id' =>$credit_account_info->ledger_id,
                                'credit_name' =>$credit_account_info->ledger_name,
                                'credit_depth' =>$credit_account_info->depth,
                                'credit_naration' =>$credit_naration[$key],
                                'credit_transaction_amount' =>$credit_transaction_amount[$key],
                                'credit_costcenter' =>$cost_center,
                                'posting_type' =>$posting_type
                            ]; 
                    $total_credit_amount = $total_credit_amount+$credit_transaction_amount[$key];

                }else return \Redirect::to('/journal/posting/type-'.$posting_type)->with('errormessage',"Credit Account Missing.Please try again!!");
            }


            /*debit credit amount validation chaeck*/
            if($total_debit_amount != $total_credit_amount)
                return \Redirect::to('/journal/posting/type-'.$posting_type)->with('errormessage',"Debit And Credit amount must be equal.!!");

             /*posting check ofr receipt and payment*/
            if($posting_type=='general_receipt' || $posting_type=='general_payment'){

                $debit_cash = \App\Journal::MultiArrayStringSerach('cash','debit_name',$all_debit);
                $credit_cash = \App\Journal::MultiArrayStringSerach('cash','credit_name',$all_credit);

                $debit_bank = \App\Journal::MultiArrayStringSerach('bank','debit_name',$all_debit);
                $credit_bank = \App\Journal::MultiArrayStringSerach('bank','credit_name',$all_credit);

                if($debit_cash ==0 && $credit_cash==0 && $debit_bank==0 && $credit_bank==0)
                    return \Redirect::to('/journal/posting/type-'.$posting_type)->with('errormessage',"Cash or Bank Entry Required.");

            }

            $transaction_info = \App\Journal::GeneralTransactionInsertByDate($transactions_date, $cost_center, $total_debit_amount,$transaction_details,$posting_info->posting_type_slug);
         
            if(isset($transaction_info) && $transaction_info != -1){

               /**debit posting Insert*/
               foreach ($all_debit as $key => $debit_info) {

                   $debit_posting = \App\Journal::JournalDebitPosting($debit_info,$transaction_info);

                   if(! $debit_posting)
                    return \Redirect::to('/journal/posting/type-'.$posting_type)->with('errormessage',"Something wrong in Debit Posting.Please try again!!");
               }

               /**credit posting Insert*/
               foreach ($all_credit as $key => $credit_info) {

                   $credit_posting = \App\Journal::JournalCreditPosting($credit_info,$transaction_info);

                   if(! $credit_posting)
                    return \Redirect::to('/journal/posting/type-'.$posting_type)->with('errormessage',"Something wrong in Credit Posting.Please try again!!");
               }


               return  \Redirect::to('/journal/posting/type-'.$posting_type)->with('message',"Posting is successfully completed.!!");

            }else return \Redirect::to('/journal/posting/type-'.$posting_type)->with('errormessage',"Something wrong in Transaction.Please try again!!");

        }else return \Redirect::to('/journal/posting/type-'.$posting_type)->withErrors($v->messages());
       
        
    }

    /********************************************
    ## GeneralAllTransactionList
    *********************************************/
    public function GeneralAllTransactionList(){


        if(isset($_GET['search_from']) && isset($_GET['search_to']) || isset($_GET['cost_center']) || isset($_GET['post_type']) || isset($_GET['user_name'])){

            $search_from = $_GET['search_from'];
            $search_to = $_GET['search_to'];
            $cost_center = $_GET['cost_center'];
            $post_type = $_GET['post_type'];
            $user_name = $_GET['user_name'];

            $all_transaction = \DB::table('ltech_transactions')
                            ->where(function($query){

                               if(isset($_GET['cost_center']) && !empty($_GET['cost_center'])){
                                    $query->where(function ($q){
                                        $q->where('ltech_transactions.cost_center_id', $_GET['cost_center']);
                                      });
                                }
                                if(isset($_GET['post_type']) && !empty($_GET['post_type'])){
                                    $query->where(function ($q){
                                        $q->where('ltech_transactions.posting_type', $_GET['post_type']);
                                      });
                                }

                                if(isset($_GET['user_name']) && !empty($_GET['user_name'])){
                                    $query->where(function ($q){
                                        $q->where('ltech_transactions.created_by', $_GET['user_name']);
                                      });
                                }

                            }) 
                            ->leftjoin('ltech_cost_centers','ltech_transactions.cost_center_id','like','ltech_cost_centers.cost_center_id')
                            ->leftjoin('ltech_posting_types','ltech_transactions.posting_type','like','ltech_posting_types.posting_type_slug')
                            ->whereBetween('ltech_transactions.transactions_date', [$search_from,$search_to])
                            ->OrderBy('ltech_transactions.transactions_id','desc')
                            ->paginate(10);

            $all_transaction->setPath(url('/general/transaction-list'));
            $transaction_pagination = $all_transaction->appends(['search_from' => $_GET['search_from'], 'search_to'=> $_GET['search_to'],'post_type'=>$_GET['post_type'],'cost_center'=>$_GET['cost_center'],'user_name'=>$_GET['user_name']])->render();

            $data['transaction_pagination'] = $transaction_pagination;
            $data['all_transaction'] = $all_transaction;

        }
        /*----------------------------/Get Request-----------------------------------*/

        else{
        $now=date('Y-m-d');
        $search_from = $now;
        $search_to = $now;
        $cost_center = 0;
        $post_type = 0;
        $user_name = 0;

        $all_transaction=\DB::table('ltech_transactions')
                ->leftjoin('ltech_cost_centers','ltech_transactions.cost_center_id','=','ltech_cost_centers.cost_center_id')
                ->leftjoin('ltech_posting_types','ltech_transactions.posting_type','=','ltech_posting_types.posting_type_slug')
                ->whereBetween('ltech_transactions.transactions_date',[$search_from,$search_to])
                ->OrderBy('ltech_transactions.transactions_id','desc')
                ->paginate(10);

        $all_transaction->setPath(url('/general/transaction-list'));
        $transaction_pagination = $all_transaction->render();;
        $data['transaction_pagination']=$transaction_pagination;
        $data['all_transaction'] = $all_transaction;
        }

            $data['search_from'] = $search_from;
            $data['search_to'] = $search_to;
            $data['cost_center'] = $cost_center;
            $data['post_type'] = $post_type;
            $data['user_name'] = $user_name;

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.journal.all-transaction-list',$data);
    }

    /********************************************
    ## GeneralAllTransactionListPDF
    *********************************************/
    public function GeneralAllTransactionListPDF($search_from,$search_to,$cost_center,$post_type,$user_name){


        if(!empty($search_from) && !empty($search_to) || !empty($cost_center) || !empty($post_type) || !empty($user_name)){

            $data['search_from'] = $search_from;
            $data['search_to'] = $all_transaction;
            $data['cost_center'] = $cost_center;
            $data['post_type'] = $post_type;
            $data['user_name'] = $user_name;


            $all_transaction = \DB::table('ltech_transactions')
                            ->where(function($query){

                               if(isset($_GET['cost_center']) && !empty($_GET['cost_center'])){
                                    $query->where(function ($q){
                                        $q->where('ltech_transactions.cost_center_id', $_GET['cost_center']);
                                      });
                                }
                                if(isset($_GET['post_type']) && !empty($_GET['post_type'])){
                                    $query->where(function ($q){
                                        $q->where('ltech_transactions.posting_type', $_GET['post_type']);
                                      });
                                }

                                if(isset($_GET['user_name']) && !empty($_GET['user_name'])){
                                    $query->where(function ($q){
                                        $q->where('ltech_transactions.created_by', $_GET['user_name']);
                                      });
                                }

                            }) 
                            ->leftjoin('ltech_cost_centers','ltech_transactions.cost_center_id','like','ltech_cost_centers.cost_center_id')
                            ->leftjoin('ltech_posting_types','ltech_transactions.posting_type','like','ltech_posting_types.posting_type_id')
                            ->whereBetween('ltech_transactions.transactions_date', [$search_from,$search_to])
                            ->OrderBy('ltech_transactions.transactions_id','desc')
                            ->get();

            $data['all_transaction'] = $all_transaction;
            return \View::make('pages.journal.all-transaction-pdf',$data);

        }else return \Redirect::to('/general/transaction-list');

    }


    /********************************************
    ## DeleteGeneralTransaction
    *********************************************/
    public function DeleteGeneralTransaction($transactions_id, $posting_type){
        $now=date('Y-m-d');

        \DB::beginTransaction();
        $current_transaction_info=\DB::table('ltech_transactions')
                            ->where('transactions_id',$transactions_id)
                            ->first();
        $current_transaction_amount=$current_transaction_info->transaction_amount;

        #################################

            if(($current_transaction_info->posting_type) == 'journal'){


                $stocks_transactions_info=\DB::table('ltech_inventory_stocks_transactions')
                                        ->where('referrence',$transactions_id)->first();
                // $inventory_stock_id=$stocks_transactions_info->inventory_stock_id;

                    $finish_goods_transactions_info=\DB::table('ltech_finish_goods_transactions')
                                                ->where('referrence',$transactions_id)->first();

                        $finish_goods_id=$finish_goods_transactions_info->finish_goods_id;
                        $current_quantity=$finish_goods_transactions_info->transaction_finish_goods_quantity;


                    $finish_goods_stocks_data=\DB::table('ltech_finish_goods_stocks')->where('finish_goods_id',$finish_goods_id)->first();

                    $finish_goods_inventory=unserialize($finish_goods_transactions_info->finish_goods_inventory);


                    foreach ($finish_goods_inventory as $key => $value) {
                        $ltech_inventory_stocks_info=\DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$value['finishgoods_inventory_stocks_id'])->first();
                        $stocks_quantity=$value['finishgoods_transaction_stocks_quantity'];
                        $stocks_cost=$value['finishgoods_stocks_transaction_amount'];
                        $current_stocks_quantity=$ltech_inventory_stocks_info->stocks_total_quantity;
                        $current_stocks_cost=$ltech_inventory_stocks_info->stocks_total_cost;
                        $stocks_onhand=$ltech_inventory_stocks_info->stocks_onhand;
                        $stocks_onproduction=$ltech_inventory_stocks_info->stocks_onproduction;

                        $invantory_stocks_update_data=[
                            // 'stocks_total_quantity'=>$current_stocks_quantity+$stocks_quantity,
                            'stocks_onhand'=>$stocks_onhand+$stocks_quantity,
                            'stocks_onproduction'=>$stocks_onproduction-$stocks_quantity,
                            'stocks_total_cost'=>$current_stocks_cost+$stocks_cost,
                        ];
                        
                        try{
                            \DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$value['finishgoods_inventory_stocks_id'])->update($invantory_stocks_update_data);
                            \App\System::EventLogWrite('update,ltech_inventory_stocks',json_encode($invantory_stocks_update_data));

                        }catch(\Exception $e){

                            \DB::rollback();
                            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                            \App\System::ErrorLogWrite($message);
                        }
                    }


                        $opening_transaction_stocks_cost=$stocks_transactions_info->opening_transaction_stocks_cost+$current_transaction_amount;
                        $closing_transaction_stocks_cost=$stocks_transactions_info->closing_transaction_stocks_cost+$current_transaction_amount;


                        $finish_goods_net_production_cost=$finish_goods_stocks_data->finish_goods_net_production_cost-$current_transaction_amount;


                        $finish_goods_net_production_quantity=$finish_goods_stocks_data->finish_goods_net_production_quantity-$current_quantity;


                        $inventory_stocks_transactions_data = [
                            'closing_transaction_stocks_cost' =>$closing_transaction_stocks_cost,
                            'updated_by' => \Auth::user()->user_id,
                            'updated_at' =>$now,
                        ];

                        $finish_goods_data = [
                            'finish_goods_net_production_cost' =>$finish_goods_net_production_cost,
                            'finish_goods_net_production_quantity' =>$finish_goods_net_production_quantity,
                            'updated_by' => \Auth::user()->user_id,
                            'updated_at' =>$now,
                        ];

                    $meta_sales_order_info=\DB::table('ltech_transaction_meta')
                                            ->where('transaction_id',$transactions_id)
                                            ->where('field_name','ltech_sales_orders')
                                            ->first();

                    $sales_order_data_info=\DB::table('ltech_sales_orders')->where('order_id',$meta_sales_order_info->field_value)->first();

                    $sales_order_details_data_info=\DB::table('ltech_sales_order_details')->where('order_id',$sales_order_data_info->order_id)->first();

                        $sales_order_data=[
                            'order_status' =>0,
                            // 'order_item_process_list' => serialize($inventory_stocks_list),
                            'created_by'=>\Auth::user()->user_id,
                            'updated_at'=>$now,
                        ];


                        $sales_order_details_data=[
                            'order_item_process_status' =>0,
                            'created_by'=>\Auth::user()->user_id,
                            'updated_at'=>$now,

                        ];

                    try{
                        \DB::table('ltech_finish_goods_stocks')->where('finish_goods_id',$finish_goods_id)->update($finish_goods_data);
                        \DB::table('ltech_inventory_stocks_transactions')->where('referrence',$transactions_id)->delete();
                        \DB::table('ltech_finish_goods_transactions')->where('referrence',$transactions_id)->delete();

                        \DB::table('ltech_sales_orders')->where('order_id',$meta_sales_order_info->field_value)->update($sales_order_data);
                        \DB::table('ltech_sales_order_details')->where('order_id',$sales_order_data_info->order_id)->update($sales_order_details_data);

                        \App\System::EventLogWrite('update,ltech_sales_orders',json_encode($sales_order_data));
                        \App\System::EventLogWrite('update,ltech_sales_order_details',json_encode($sales_order_details_data));


                        \App\System::EventLogWrite('update,ltech_finish_goods_stocks',json_encode($finish_goods_data));
                        \App\System::EventLogWrite('delete,ltech_inventory_stocks_transactions',json_encode($stocks_transactions_info));
                        \App\System::EventLogWrite('delete,ltech_finish_goods_transactions',json_encode($finish_goods_transactions_info));


                    }catch(\Exception $e){

                        \DB::rollback();
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                    }

            }

        #####################################



        if(($current_transaction_info->posting_type) == 'sales'){

                $finish_goods_transactions=\DB::table('ltech_finish_goods_transactions')
                                        ->where('referrence',$transactions_id)->get();
                $count=count($finish_goods_transactions);

                    $grand_total_cost=0;

                    foreach ($finish_goods_transactions as $key => $value) {

                        $finish_goods_transactions=\DB::table('ltech_finish_goods_transactions')
                                                    ->where('ltech_finish_goods_transactions_id',$value->ltech_finish_goods_transactions_id)
                                                    ->first();

                        $customer_id=$finish_goods_transactions->customer_id;
                        $finish_goods_id=$finish_goods_transactions->finish_goods_id;
                        $current_quantity=$finish_goods_transactions->transaction_finish_goods_quantity;
                        $finish_goods_quantity_cost=$finish_goods_transactions->finish_goods_quantity_cost;


                        $ltech_customers_info=\DB::table('ltech_customers')
                                    ->where('customer_id',$customer_id)->first();

                        $finish_goods_stocks_data_info=\DB::table('ltech_finish_goods_stocks')
                                                    ->where('finish_goods_id',$finish_goods_transactions->finish_goods_id)
                                                    ->first();
                        $finish_goods_rate=($finish_goods_stocks_data_info->finish_goods_net_production_cost)/($finish_goods_stocks_data_info->finish_goods_net_production_quantity);


                        $finish_goods_net_sales_quantity=$finish_goods_stocks_data_info->finish_goods_net_sales_quantity-$current_quantity;
                        $finish_goods_net_quantity=$finish_goods_stocks_data_info->finish_goods_net_quantity+$current_quantity;

                    
                        $finish_goods_net_sales_cost=$finish_goods_stocks_data_info->finish_goods_net_sales_cost-$finish_goods_quantity_cost;
                        // $finish_goods_net_cost=$finish_goods_stocks_data_info->finish_goods_net_cost+$finish_goods_quantity_cost;
                        $finish_goods_net_cost=$finish_goods_stocks_data_info->finish_goods_net_cost+($finish_goods_rate*$current_quantity);

                        $customer_net_credit_amount=$ltech_customers_info->customer_net_credit_amount-$finish_goods_quantity_cost;
                        $customer_net_balance_amount=$ltech_customers_info->customer_net_balance_amount-$finish_goods_quantity_cost;

                            
                            $finish_goods_info = [
                                    'finish_goods_net_sales_cost' =>$finish_goods_net_sales_cost,
                                    'finish_goods_net_sales_quantity' =>$finish_goods_net_sales_quantity,
                                    'finish_goods_net_cost' =>$finish_goods_net_cost,
                                    'finish_goods_net_quantity' =>$finish_goods_net_quantity,
                                    'updated_by' => \Auth::user()->user_id,
                                    'updated_at' =>$now,

                            ];

                            $customer_info = [
                                    'customer_net_credit_amount' =>$customer_net_credit_amount,
                                    'customer_net_balance_amount' =>$customer_net_balance_amount,
                                    'updated_by' => \Auth::user()->user_id,
                                    'updated_at' =>$now,
                            ];



                            try{

                                \DB::table('ltech_finish_goods_stocks')->where('finish_goods_id',$finish_goods_transactions->finish_goods_id)->update($finish_goods_info);

                                \DB::table('ltech_customers')->where('customer_id',$customer_id)->update($customer_info);


                                \App\System::EventLogWrite('update,ltech_customers',json_encode($customer_info));

                                \App\System::EventLogWrite('update,ltech_finish_goods_stocks',json_encode($finish_goods_info));

                            }catch(\Exception $e){

                                \DB::rollback();
                                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                                \App\System::ErrorLogWrite($message);

                                return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                            }


                    }


                        $sales_orders_info=\DB::table('ltech_sales_orders')
                                    ->where('sales_referrence',$transactions_id)->first();
                        $current_sales_order_cost=$sales_orders_info->order_delivery_balance_amount;
                        $added_amount=$current_sales_order_cost-$grand_total_cost;
                        $added_amount=\App\Report::CreatePositiveData($added_amount);


                        $meta_sales_order_info=\DB::table('ltech_transaction_meta')
                                            ->where('transaction_id',$transactions_id)
                                            ->where('field_name','ltech_sales_orders')
                                            ->first();

                        $sales_order_info=\DB::table('ltech_sales_orders')
                                            ->where('order_id',$meta_sales_order_info->field_value)
                                            ->first();

                        $sales_order_details_info=\DB::table('ltech_sales_order_details')
                                            ->where('order_id',$sales_order_info->order_id)
                                            ->get();

                        $sales_order_data=[
                                        'updated_by' =>\Auth::user()->user_id,
                                        'updated_at' => $now,
                                        'order_delivery_amount'=>0,
                                        'order_delivery_net_amount'=>0,
                                        'order_delivery_credit_amount' =>0,
                                        'order_delivery_balance_amount' =>0,
                                        'customer_order_delivery_net_balance_amount'=>0,
                                        'order_status'=>1,
                                    ];

                        $order_details_data=[
                                'order_item_deliverd_quantity'=>0,
                                'order_item_deliverd_quantity_rate'=>0,
                                'order_item_deliverd_cost'=>0,
                            ];
                                    
                            try{

                                \DB::table('ltech_sales_orders')->where('sales_referrence',$transactions_id)->update($sales_order_data);
                                \DB::table('ltech_sales_order_details')->where('order_id',$sales_order_info->order_id)->update($order_details_data);
                                \DB::table('ltech_finish_goods_transactions')->where('referrence',$transactions_id)->delete();

                                \App\System::EventLogWrite('update,ltech_sales_order_details',json_encode($order_details_data));
                                \App\System::EventLogWrite('update,ltech_sales_orders',json_encode($sales_order_data));

                            }catch(\Exception $e){

                                \DB::rollback();
                                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                                \App\System::ErrorLogWrite($message);

                                return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                            }


        }




################ Sales Return Delete ####################

        if($current_transaction_info->posting_type == 'sales_return'){


                    $finish_goods_transactions_data=\DB::table('ltech_finish_goods_transactions')
                                            ->where('referrence',$transactions_id)->get();
                    $count=count($finish_goods_transactions_data);


                    foreach ($finish_goods_transactions_data as $key => $value) {

                        $finish_goods_transactions=\DB::table('ltech_finish_goods_transactions')
                                                ->where('ltech_finish_goods_transactions_id',($value->ltech_finish_goods_transactions_id))->first();

                        $customer_id=$value->customer_id;
                        $select_transaction_id=$value->referrence;
                        $finish_goods_id=$value->finish_goods_id;
                        $current_finish_goods_quantity=$value->transaction_finish_goods_quantity;
                        $current_finish_goods_quantity_cost=$value->finish_goods_quantity_cost;

                        $finish_goods_stocks_data_info=\DB::table('ltech_finish_goods_stocks')
                                                    ->where('finish_goods_id',$value->finish_goods_id)
                                                    ->first();
                        $finish_goods_rate=($finish_goods_stocks_data_info->finish_goods_net_production_cost)/($finish_goods_stocks_data_info->finish_goods_net_production_quantity);

                        $ltech_customers_info=\DB::table('ltech_customers')
                                    ->where('customer_id',$customer_id)->first();

                        $sales_orders_info=\DB::table('ltech_sales_orders')
                                    ->where('sales_return_referrence',$transactions_id)->first();

                        $sales_order_details_info=\DB::table('ltech_sales_order_details')
                                    ->where('order_id',$sales_orders_info->order_id)
                                    ->where('order_item_name','LIKE',$finish_goods_stocks_data_info->finish_goods_name)
                                    ->first();

                        $finish_goods_net_sales_quantity=$finish_goods_stocks_data_info->finish_goods_net_sales_quantity+$current_finish_goods_quantity;
                        $finish_goods_net_quantity=$finish_goods_stocks_data_info->finish_goods_net_quantity-$current_finish_goods_quantity;
                        $finish_goods_net_sales_cost=$finish_goods_stocks_data_info->finish_goods_net_sales_cost+$current_finish_goods_quantity_cost;
                        $finish_goods_net_cost=$finish_goods_stocks_data_info->finish_goods_net_cost-($finish_goods_rate*$current_finish_goods_quantity);

                        $customer_net_credit_amount=$ltech_customers_info->customer_net_credit_amount+$current_finish_goods_quantity_cost;
                        $customer_net_balance_amount=$ltech_customers_info->customer_net_balance_amount+$current_finish_goods_quantity_cost;

                        $finish_goods_info = [
                                'finish_goods_net_sales_cost' =>$finish_goods_net_sales_cost,
                                'finish_goods_net_sales_quantity' =>$finish_goods_net_sales_quantity,
                                'finish_goods_net_cost' =>$finish_goods_net_cost,
                                'finish_goods_net_quantity' =>$finish_goods_net_quantity,
                                'updated_by' => \Auth::user()->user_id,
                                'updated_at' =>$now,

                        ];


                        $customer_info = [
                                'customer_net_credit_amount' =>$customer_net_credit_amount,
                                'customer_net_balance_amount' =>$customer_net_balance_amount,
                                'updated_by' => \Auth::user()->user_id,
                                'updated_at' =>$now,
                        ];


                        $order_item_deliverd_quantity=$sales_order_details_info->order_item_deliverd_quantity+$current_finish_goods_quantity; 

                        $order_item_deliverd_cost=$sales_order_details_info->order_item_deliverd_cost+$current_finish_goods_quantity_cost;


                        $order_delivery_amount=$sales_orders_info->order_delivery_amount+$current_finish_goods_quantity_cost;
                        $order_delivery_net_amount=$sales_orders_info->order_delivery_net_amount+$current_finish_goods_quantity_cost;
                        $order_delivery_credit_amount=$sales_orders_info->order_delivery_credit_amount+$current_finish_goods_quantity_cost;
                        $order_delivery_balance_amount=$sales_orders_info->order_delivery_balance_amount+$current_finish_goods_quantity_cost;
                        $customer_order_delivery_net_balance_amount=$sales_orders_info->customer_order_delivery_net_balance_amount+$current_finish_goods_quantity_cost;


                        $order_details_data=[
                            'order_item_deliverd_quantity'=>$order_item_deliverd_quantity,
                            'order_item_deliverd_cost'=>$order_item_deliverd_cost
                        ];

                        $sales_order_data=[
                                    'order_delivery_amount'=>$order_delivery_amount,
                                    'order_delivery_net_amount'=>$order_delivery_net_amount,
                                    'order_delivery_credit_amount' =>$order_delivery_credit_amount,
                                    'order_delivery_balance_amount' =>$order_delivery_balance_amount,
                                    'customer_order_delivery_net_balance_amount'=>$customer_order_delivery_net_balance_amount,
                                    'updated_by' =>\Auth::user()->user_id,
                                    'updated_at' => $now,
                        ];



                        try{

                            \DB::table('ltech_sales_order_details')->where('order_details_id',$sales_order_details_info->order_details_id)->update($order_details_data);
                            \DB::table('ltech_finish_goods_stocks')->where('finish_goods_id',$finish_goods_stocks_data_info->finish_goods_id)->update($finish_goods_info);
                            \DB::table('ltech_customers')->where('customer_id',$ltech_customers_info->customer_id)->update($customer_info);
                            \DB::table('ltech_sales_orders')->where('sales_return_referrence',$transactions_id)->update($sales_order_data);
                            \DB::table('ltech_finish_goods_transactions')->where('referrence',$transactions_id)->delete();

                            \App\System::EventLogWrite('update,ltech_sales_orders',json_encode($sales_order_data));
                            \App\System::EventLogWrite('update,ltech_customers',json_encode($customer_info));
                            \App\System::EventLogWrite('update,ltech_sales_order_details',json_encode($order_details_data));
                            \App\System::EventLogWrite('update,ltech_finish_goods_stocks',json_encode($finish_goods_info));
                            \App\System::EventLogWrite('delete,ltech_finish_goods_transactions',json_encode($finish_goods_transactions_data));

                        }catch(\Exception $e){

                            \DB::rollback();
                            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                            \App\System::ErrorLogWrite($message);

                            return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                        }


                    }


            }

############################## Receipt ############################


        if(($current_transaction_info->posting_type) == 'receipt'){
            $customer_payment_transactions_info=\DB::table('ltech_customer_payment_transactions')
                                        ->where('referrence',$transactions_id)->first();
            $customer_id=$customer_payment_transactions_info->customer_id;

            $customer_data_info=\DB::table('ltech_customers')
                                        ->where('customer_id',$customer_id)->first();
            $customer_net_debit_amount=$customer_data_info->customer_net_debit_amount-$customer_payment_transactions_info->transaction_amount;
            $customer_net_balance_amount=$customer_data_info->customer_net_balance_amount+$customer_payment_transactions_info->transaction_amount;

            #################### 17-02-2017 ###############################
            $meta_sales_order_info=\DB::table('ltech_transaction_meta')
                                            ->where('transaction_id',$transactions_id)
                                            ->where('field_name','ltech_sales_orders')
                                            ->first();

            $sales_order_data_info=\DB::table('ltech_sales_orders')->where('order_id',$meta_sales_order_info->field_value)->first();

            $sales_order_details_data_info=\DB::table('ltech_sales_order_details')->where('order_id',$sales_order_data_info->order_id)->first();

            $update_payment_order_data = [
                    'order_delivery_debit_amount'=>$sales_order_data_info->order_delivery_debit_amount-$current_transaction_amount,
                    'order_delivery_balance_amount'=>$sales_order_data_info->order_delivery_balance_amount+$current_transaction_amount,
                    'customer_order_delivery_net_balance_amount'=>$customer_net_balance_amount,
                    'updated_by'=> \Auth::user()->user_id,
                    'updated_at'=> $now,
                ];

                
            try{

                $customer_order_update_data = \DB::table('ltech_sales_orders')->where('order_id',$meta_sales_order_info->field_value)->update($update_payment_order_data);
                \App\System::EventLogWrite('update,ltech_sales_orders',json_encode($update_payment_order_data));

            }catch(\Exception $e){

                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
            }

            #################### 17-02-2017###############################


            

            $customer_data= [
                            'customer_net_debit_amount' =>$customer_net_debit_amount,
                            'customer_net_balance_amount' =>$customer_net_balance_amount,
                            'updated_by' => \Auth::user()->user_id,
                            'updated_at' =>$now,
                        ];

            try{
                \DB::table('ltech_customers')->where('customer_id',$customer_id)->update($customer_data);
                \DB::table('ltech_customer_payment_transactions')->where('referrence',$transactions_id)->delete();
                \App\System::EventLogWrite('update,ltech_transactions',json_encode($customer_data));
                \App\System::EventLogWrite('delete,ltech_customer_payment_transactions',json_encode($customer_payment_transactions_info));

            }catch(\Exception $e){

                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
            }
        }


        if(($current_transaction_info->posting_type) == 'purchase'){

            $now=date('Y-m-d');
            $all_inventory_stocks_transactions_info=\DB::table('ltech_inventory_stocks_transactions')->where('referrence',$transactions_id)->get();
            foreach ($all_inventory_stocks_transactions_info as $key => $value) {

                $supplier_id=$value->stocks_supplier_id;
                $inventory_stock_id=$value->inventory_stock_id;
                $current_quantity=$value->transaction_stocks_quantity;
                $current_cost=$value->stocks_quantity_cost;

                $inventory_stocks_data_info=\DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stock_id)->first();

                $supplier_data_info=\DB::table('ltech_suppliers')->where('supplier_id',$supplier_id)->first();

                $supplier_net_credit_amount=$supplier_data_info->supplier_net_credit_amount-$current_cost;
                $supplier_net_balance_amount=$supplier_data_info->supplier_net_balance_amount-$current_cost;

                $stocks_total_cost=$inventory_stocks_data_info->stocks_total_cost-$current_cost;


                $stocks_onhand=$inventory_stocks_data_info->stocks_onhand-$current_quantity;
                $stocks_total_quantity=$inventory_stocks_data_info->stocks_total_quantity-$current_quantity;

                $supplier_data= [
                                'supplier_net_credit_amount' =>$supplier_net_credit_amount,
                                'supplier_net_balance_amount' =>$supplier_net_balance_amount,
                                'updated_by' => \Auth::user()->user_id,
                                'updated_at' =>$now,
                            ];

                $inventory_stocks_data = [
                            'stocks_onhand' =>$stocks_onhand,
                            'stocks_total_quantity' =>$stocks_total_quantity,
                            'stocks_total_cost' =>$stocks_total_cost,
                            'updated_by' => \Auth::user()->user_id,
                            'updated_at' =>$now,

                        ];

                try{
                    \DB::table('ltech_suppliers')->where('supplier_id',$supplier_id)->update($supplier_data);
                    \DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stock_id)->update($inventory_stocks_data);
                    \DB::table('ltech_inventory_stocks_transactions')->where('stocks_transactions_id',$value->stocks_transactions_id)->delete();

                    \App\System::EventLogWrite('update,ltech_suppliers',json_encode($supplier_data));
                    \App\System::EventLogWrite('update,ltech_inventory_stocks',json_encode($inventory_stocks_data));
                    \App\System::EventLogWrite('delete,ltech_inventory_stocks_transactions',json_encode($value));

                }catch(\Exception $e){

                    \DB::rollback();
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                }
            }

        }


        ################################


            if($current_transaction_info->posting_type == 'purchase_return'){

                $inventory_stocks_transactions=\DB::table('ltech_inventory_stocks_transactions')
                                        ->where('referrence',$transactions_id)->first();
                $current_quantity=$inventory_stocks_transactions->transaction_stocks_quantity;
                $inventory_stock_id=$inventory_stocks_transactions->inventory_stock_id;
                $inventory_stocks_data_info=\DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stock_id)->first();
                $supplier_credit_transactions=\DB::table('ltech_inventory_supplier_credit_transactions')->where('referrence',$transactions_id)->first();
                $supplier_id=$inventory_stocks_transactions->stocks_supplier_id;
                $inventory_stock_id=$inventory_stocks_transactions->inventory_stock_id;

                $ltech_suppliers_info=\DB::table('ltech_suppliers')
                                    ->where('supplier_id',$supplier_id)->first();

                    $supplier_net_credit_amount=($ltech_suppliers_info->supplier_net_credit_amount)+$current_transaction_amount;
                    $supplier_net_balance_amount=($ltech_suppliers_info->supplier_net_balance_amount)+$current_transaction_amount;

                    $stocks_total_cost=$inventory_stocks_data_info->stocks_total_cost+$current_transaction_amount;
                    $stocks_onhand=$inventory_stocks_data_info->stocks_onhand-$current_quantity;
                    $stocks_total_quantity=$inventory_stocks_data_info->stocks_total_quantity+$current_quantity;


                    $supplier_info = [
                        'supplier_net_credit_amount' =>$supplier_net_credit_amount,
                        'supplier_net_balance_amount' =>$supplier_net_balance_amount,
                        'updated_by' => \Auth::user()->user_id,
                        'updated_at' =>$now,

                    ];

                    $inventory_stocks_data = [
                        'stocks_onhand' =>$stocks_onhand,
                        'stocks_total_quantity' =>$stocks_total_quantity,
                        'stocks_total_cost' =>$stocks_total_cost,
                        'updated_by' => \Auth::user()->user_id,
                        'updated_at' =>$now,

                    ];

                try{

                    \DB::table('ltech_suppliers')->where('supplier_id',$supplier_id)->update($supplier_info);
                    \DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stock_id)->update($inventory_stocks_data);
                    \DB::table('ltech_inventory_stocks_transactions')->where('referrence',$transactions_id)->delete();
                    \DB::table('ltech_inventory_supplier_credit_transactions')->where('referrence',$transactions_id)->delete();


                    \App\System::EventLogWrite('update,ltech_suppliers',json_encode($supplier_info));
                    \App\System::EventLogWrite('update,ltech_inventory_stocks',json_encode($inventory_stocks_data));
                    \App\System::EventLogWrite('delete,ltech_inventory_supplier_credit_transactions',json_encode($supplier_credit_transactions));
                    \App\System::EventLogWrite('delete,ltech_inventory_stocks_transactions',json_encode($inventory_stocks_transactions));


                }catch(\Exception $e){

                    \DB::rollback();
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                }


            }

        ####################################



        if(($current_transaction_info->posting_type) == 'payment'){
            $supplier_credit_transactions_info=\DB::table('ltech_inventory_supplier_credit_transactions')->where('referrence',$transactions_id)->first();
            $supplier_id=$supplier_credit_transactions_info->supplier_id;

            $supplier_data_info=\DB::table('ltech_suppliers')
                                        ->where('supplier_id',$supplier_id)->first();
            $supplier_net_debit_amount=$supplier_data_info->supplier_net_debit_amount-$supplier_credit_transactions_info->transaction_amount;
            // $supplier_net_credit_amount=$supplier_data_info->supplier_net_credit_amount-$supplier_credit_transactions_info->transaction_amount;
            $supplier_net_balance_amount=$supplier_data_info->supplier_net_balance_amount+$supplier_credit_transactions_info->transaction_amount;

            $supplier_data= [
                            'supplier_net_debit_amount' =>$supplier_net_debit_amount,
                            // 'supplier_net_credit_amount' =>$supplier_net_credit_amount,
                            'supplier_net_balance_amount' =>$supplier_net_balance_amount,
                            'updated_by' => \Auth::user()->user_id,
                            'updated_at' =>$now,
                        ];

            try{
                \DB::table('ltech_suppliers')->where('supplier_id',$supplier_id)->update($supplier_data);
                \DB::table('ltech_inventory_supplier_credit_transactions')->where('referrence',$transactions_id)->delete();
                \App\System::EventLogWrite('update,ltech_transactions',json_encode($supplier_data));
                \App\System::EventLogWrite('delete,ltech_inventory_supplier_credit_transactions',json_encode($supplier_credit_transactions_info));

            }catch(\Exception $e){

                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
            }
        }

        if(($current_transaction_info->posting_type) == 'waste_goods_journal'){

            
            $finish_goods_meta_info = \DB::table('ltech_transaction_meta')
                    ->where('transaction_id',$transactions_id)
                    ->where('field_name','ltech_finish_goods_stocks')
                    ->first();

            if(!empty($finish_goods_meta_info)){

                $finish_goods_info = \DB::table('ltech_finish_goods_stocks')
                        ->where('ltech_finish_goods_stocks.finish_goods_id',$finish_goods_meta_info->field_value)
                        ->first();

                $finish_goods_waste_qty = \DB::table('ltech_transaction_meta')
                        ->where('transaction_id',$transactions_id)
                        ->where('field_name','ltech_finish_goods_stocks-qty')
                        ->first();

                #Finishgoods Stocks Update
                $finish_goods_update_data = [
                            'finish_goods_waste_cost' =>$finish_goods_info->finish_goods_waste_cost-$current_transaction_amount,
                            'finish_goods_waste_quantity' =>$finish_goods_info->finish_goods_waste_quantity-$finish_goods_waste_qty->field_value,
                            'finish_goods_net_cost'=>$finish_goods_info->finish_goods_net_cost+$current_transaction_amount,
                            'finish_goods_net_quantity'=>$finish_goods_info->finish_goods_net_quantity+$finish_goods_waste_qty->field_value,
                            'updated_by' => \Auth::user()->user_id,
                            'updated_at'=>$now

                        ];
                try{

                    $finish_goods_update = \DB::table('ltech_finish_goods_stocks')->where('finish_goods_id',$finish_goods_info->finish_goods_id)->update($finish_goods_update_data);
                    \App\System::EventLogWrite('update,ltech_finish_goods_stocks',json_encode($finish_goods_update_data));

                }catch(\Exception $e){

                    \DB::rollback();
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                }

            }else{
                return \Redirect::to('/general/transaction-list')->with('errormessage','Something wrong !!!!!!');
            }

        }



        try{

            $delete_journal_info=\DB::table('ltech_general_journal')
                                ->where('transaction_id',$transactions_id)
                                ->delete();
            \App\System::EventLogWrite('delete,ltech_general_journal',json_encode($delete_journal_info));
        }catch(\Exception $e){

            \DB::rollback();
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
        }



        try{

            $delete_transactions=\DB::table('ltech_transactions')
                            ->where('transactions_id',$transactions_id)
                            ->delete();
            \App\System::EventLogWrite('delete,ltech_transactions',json_encode($delete_transactions));
            \DB::commit();
            return \Redirect::to('/general/transaction-list')->with('message','Successfully Deleted');


        }catch(\Exception $e){

            \DB::rollback();
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
        }

    }


    /********************************************
    ## EditGeneralTransaction
    *********************************************/
    public function EditGeneralTransaction($transactions_id,$posting_type){

        if($posting_type == 'waste_goods_journal'){

            return \Redirect::to('/general/transaction-list')->with('errormessage','You can not edit this type posting.');
        }

        $cost_centers=\DB::table('ltech_cost_centers')->get();
        $posting_types=\DB::table('ltech_posting_types')
                        ->WhereNotIn('posting_type_slug',['general_journal','general_receipt','general_payment','general_sales','general_purchase','general_sales_return','general_purchase_return'])->get();

        $general_posting_types=\DB::table('ltech_posting_types')
                                ->WhereNotIn('posting_type_slug',['journal','receipt','payment','sales','purchase','sales_return','purchase_return'])->get();
        $edit_ltech_transactions=\DB::table('ltech_transactions')->where('transactions_id',$transactions_id)->first();
        if(!empty($edit_ltech_transactions)){
            $transactions_id=$edit_ltech_transactions->transactions_id;
        }
        $edit_journal_info=\DB::table('ltech_general_journal')
                            ->where('transaction_id',$transactions_id)
                            ->OrderBy('ltech_general_journal.journal_id','asc')
                            ->get();

        $journal_posting_field = \App\Journal::GetJournalEntryList();
        $data['journal_posting_field'] = $journal_posting_field;


        $data['edit_journal_info'] = $edit_journal_info;
        $data['edit_ltech_transactions'] = $edit_ltech_transactions;
        $data['cost_centers'] = $cost_centers;
        $data['posting_types'] = $posting_types;
        $data['general_posting_types'] = $general_posting_types;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;



        return \View::make('pages.journal.edit-transaction-list',$data);
    }

    /********************************************
    ## AjaxEditGeneralTransactionFieldEntry
    *********************************************/
    public function AjaxEditGeneralTransactionFieldEntry($filed_count){

        $journal_posting_field = \App\Journal::GetJournalEntryList();
        $data['journal_posting_field'] = $journal_posting_field;
        $data['i']=$filed_count;      
        return \View::make('pages.journal.ajax-edit-transaction-list',$data);
    }


    /********************************************
    ## UpdateGeneralTransaction
    *********************************************/
    public function UpdateGeneralTransaction(){

         for ($i=1;$i<=\Request::input('journal_entry_field');$i++) {
                $rules_array['journal_particular_name_'.$i] =  'Required';
                $rules_array['journal_particular_amount_type_'.$i] =  'Required';
                $rules_array['journal_particular_amount_'.$i] =  'Required';
                $rules_array['journal_particular_naration_'.$i] =  'Required';
            }
            
            // $rules_array['posting_type'] =  'Required';
            $rules_array['transaction_amount'] =  'Required';
            $rules_array['cost_center_id'] =  'Required';
            // $rules_array['transactions_naration'] = 'Required';

        $v= \Validator::make(\Request::all(), $rules_array);

        if($v->passes()){

            // $posting_type = \Request::input('posting_type');
            $transaction_amount = \Request::input('transaction_amount');
            $cost_center_id = trim(\Request::input('cost_center_id'));
            $transactions_naration = trim(\Request::input('transactions_naration'));
            $transactions_id = \Request::input('transactions_id');
            $transactions_date = \Request::input('transactions_date');

            $now= date('Y-m-d H:i:s');
            $customer_net_debit_amount=0;
            $customer_net_balance_amount=0;

        if($transaction_amount>=0){

        
            \DB::beginTransaction();

            try{
                $transaction_total_amount = 0;
                $current_transaction_info = \DB::table('ltech_transactions')->where('transactions_id',$transactions_id)->first();
                $transactions_id=$current_transaction_info->transactions_id;
                $posting_type =$current_transaction_info->posting_type;

                $current_transaction_amount=$current_transaction_info->transaction_amount;
                $grand_main_balance=$current_transaction_amount-$transaction_amount;
                $grand_main_balance=\App\Report::CreatePositiveData($grand_main_balance);


                #General Transaction Insert
                $transaction_info = [
                            'transactions_naration' =>$transactions_naration,
                            'transaction_amount' =>$transaction_amount,
                            'cost_center_id' =>$cost_center_id,
                            // 'posting_type' =>$posting_type,
                            'updated_by' => \Auth::user()->user_id,
                            'updated_at' =>$now,
                        ];

                $transactionRow = \DB::table('ltech_transactions')->where('transactions_id',$transactions_id)->update($transaction_info);
                \App\System::EventLogWrite('update,ltech_transactions',json_encode($transaction_info));

            }catch(\Exception $e){

                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
            }


        ############## others transactions data update ###############

            if(($current_transaction_info->posting_type) == 'journal'){

                $quantity = \Request::input('quantity');
                $quantity_rate = \Request::input('quantity_rate');
                $quantity_cost = \Request::input('quantity_cost');

                if(($quantity*$quantity_rate) != $quantity_cost){
                       \DB::rollback();
                       return \Redirect::to('/general/transaction-list')->with('errormessage', 'Quantity and rate multipication is to be same of  quantity cost.');
                    }
                $grand_main_balance=\App\Report::CreatePositiveData($grand_main_balance);

                $stocks_transactions_info=\DB::table('ltech_inventory_stocks_transactions')
                                        ->where('referrence',$transactions_id)->first();
                $inventory_stock_id=$stocks_transactions_info->inventory_stock_id;


                $finish_goods_transactions_info=\DB::table('ltech_finish_goods_transactions')
                                        ->where('referrence',$transactions_id)->first();

                    $finish_goods_id=$finish_goods_transactions_info->finish_goods_id;
                    $current_quantity=$finish_goods_transactions_info->transaction_finish_goods_quantity;
                    $final_quantity=$current_quantity-$quantity;
                    $final_quantity=\App\Report::CreatePositiveData($final_quantity);

                    $current_finish_goods_inventory=$finish_goods_transactions_info->finish_goods_inventory;

                    $finish_goods_inventory=unserialize($finish_goods_transactions_info->finish_goods_inventory);

                    $new_grand_total_stocks_cost=0;
                    foreach ($finish_goods_inventory as $key => $value) {
                        $i=$key+1;

                        $stocks_id= \Request::input('stocks_id_'.$i);
                        $new_stocks_quantity= \Request::input('stocks_quantity_'.$i);
                        $new_stocks_cost = (int)(\Request::input('stocks_cost_'.$i));

                        $inventory_stocks_list_data = [ 
                            'finishgoods_inventory_stocks_id' =>\Request::input('stocks_id_'.$i),
                            'finishgoods_transaction_stocks_quantity' =>\Request::input('stocks_quantity_'.$i),
                            'finishgoods_stocks_transaction_amount' => \Request::input('stocks_cost_'.$i)
                        ];

                        $inventory_stocks_list[] =  $inventory_stocks_list_data;


                        $new_grand_total_stocks_cost =$new_grand_total_stocks_cost+$new_stocks_cost ;
                        $ltech_inventory_stocks_info=\DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$stocks_id)->first();
                        $stocks_transactions_data_info=\DB::table('ltech_inventory_stocks_transactions')->where('referrence',$transactions_id)->where('inventory_stock_id',$stocks_id)->first();

                        $stocks_quantity=$value['finishgoods_transaction_stocks_quantity'];
                        $stocks_cost=$value['finishgoods_stocks_transaction_amount'];
                        $total_stocks_cost=$new_stocks_cost-$stocks_cost;
                        // $stocks_cost=\App\Report::CreatePositiveData($stocks_cost);
                        $total_stocks_cost=\App\Report::CreatePositiveData($total_stocks_cost);
                        $total_stocks_quantity=$new_stocks_quantity-$stocks_quantity;
                        $total_stocks_quantity=\App\Report::CreatePositiveData($total_stocks_quantity);


                        if($new_stocks_cost>=$stocks_cost){
                            $current_stocks_cost=$ltech_inventory_stocks_info->stocks_onproduction_cost+$total_stocks_cost;
                            $closing_transaction_stocks_cost=$stocks_transactions_data_info->closing_transaction_stocks_cost+$total_stocks_cost;

                        }else{
                            $current_stocks_cost=$ltech_inventory_stocks_info->stocks_onproduction_cost-$total_stocks_cost;
                            $closing_transaction_stocks_cost=$stocks_transactions_data_info->closing_transaction_stocks_cost-$total_stocks_cost;

                        }

                        if($new_stocks_quantity>=$stocks_quantity){
                            $current_stocks_quantity=$ltech_inventory_stocks_info->stocks_total_quantity+$total_stocks_quantity;
                            $stocks_onhand=$ltech_inventory_stocks_info->stocks_onhand-$total_stocks_quantity;
                            $stocks_onproduction=$ltech_inventory_stocks_info->stocks_onproduction+$total_stocks_quantity;
                            $closing_transaction_stocks_quantity=$stocks_transactions_data_info->closing_transaction_stocks_quantity+$total_stocks_quantity;

                        }else{
                            $current_stocks_quantity=$ltech_inventory_stocks_info->stocks_total_quantity+$total_stocks_quantity;
                            $stocks_onhand=$ltech_inventory_stocks_info->stocks_onhand+$total_stocks_quantity;
                            $stocks_onproduction=$ltech_inventory_stocks_info->stocks_onproduction-$total_stocks_quantity;
                            $closing_transaction_stocks_quantity=$stocks_transactions_data_info->closing_transaction_stocks_quantity-$total_stocks_quantity;


                        }
                        $order_item_process_data=array();

                        $invantory_stocks_update_data=[
                            'stocks_onhand'=>$stocks_onhand,
                            'stocks_onproduction'=>$stocks_onproduction,
                            'stocks_onproduction_cost'=>$current_stocks_cost,
                            'updated_at' =>$now,

                        ];

                        $inventory_stocks_transactions_data = [
                            'stocks_quantity_cost' =>$new_stocks_cost,
                            'transaction_stocks_quantity' =>$new_stocks_quantity,
                            'closing_transaction_stocks_quantity' =>$closing_transaction_stocks_quantity,
                            'closing_transaction_stocks_cost' =>$closing_transaction_stocks_cost,
                            'updated_by' => \Auth::user()->user_id,
                            'updated_at' =>$now,
                        ];

                        $order_item_process_data[]=$inventory_stocks_transactions_data;

                        
                        try{
                            \DB::table('ltech_inventory_stocks_transactions')->where('referrence',$transactions_id)->where('inventory_stock_id',$stocks_id)->update($inventory_stocks_transactions_data);
                            \DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$stocks_id)->update($invantory_stocks_update_data);
                            \App\System::EventLogWrite('update,ltech_inventory_stocks',json_encode($invantory_stocks_update_data));
                            \App\System::EventLogWrite('update,ltech_inventory_stocks_transactions',json_encode($stocks_transactions_data_info));


                        }catch(\Exception $e){

                            \DB::rollback();
                            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                            \App\System::ErrorLogWrite($message);
                        }
                    }



                    if($new_grand_total_stocks_cost != $transaction_amount){
                       \DB::rollback();
                       return \Redirect::to('/general/transaction-list')->with('errormessage', 'Production balance and main balance is to be equal.');
                    }

                    

                    $finish_goods_stocks_data=\DB::table('ltech_finish_goods_stocks')->where('finish_goods_id',$finish_goods_id)->first();

                    $meta_sales_order_info=\DB::table('ltech_transaction_meta')
                                            ->where('transaction_id',$transactions_id)
                                            ->where('field_name','ltech_sales_orders')
                                            ->first();

                    $sales_order_data_info=\DB::table('ltech_sales_orders')->where('order_id',$meta_sales_order_info->field_value)->first();

                    $sales_order_details_data_info=\DB::table('ltech_sales_order_details')->where('order_id',$sales_order_data_info->order_id)->first();

                    if($transaction_amount >= $current_transaction_amount){

                        $opening_transaction_stocks_cost=$stocks_transactions_info->opening_transaction_stocks_cost+$grand_main_balance;
                        $closing_transaction_stocks_cost=$stocks_transactions_info->closing_transaction_stocks_cost+$grand_main_balance;

                        $finish_goods_quantity_cost=$finish_goods_transactions_info->finish_goods_quantity_cost+$grand_main_balance;
                        $opening_transaction_finish_goods_cost=$finish_goods_transactions_info->opening_transaction_finish_goods_cost+$grand_main_balance;

                        $closing_transaction_finish_goods_cost=$finish_goods_transactions_info->closing_transaction_finish_goods_cost+$grand_main_balance;

                        $finish_goods_net_production_cost=$finish_goods_stocks_data->finish_goods_net_production_cost+$grand_main_balance;

                        $finish_goods_net_cost=$finish_goods_stocks_data->finish_goods_net_cost+$grand_main_balance;

                        $stocks_quantity_cost=$stocks_transactions_info->stocks_quantity_cost+$grand_main_balance;

                        $order_net_amount=$sales_order_data_info->order_net_amount+$grand_main_balance;

                        $order_item_cost=$sales_order_details_data_info->order_item_cost+$grand_main_balance;


                    }else{

                        $opening_transaction_stocks_cost=$stocks_transactions_info->opening_transaction_stocks_cost+$grand_main_balance;
                        $closing_transaction_stocks_cost=$stocks_transactions_info->closing_transaction_stocks_cost-$grand_main_balance;

                        $finish_goods_quantity_cost=$finish_goods_transactions_info->finish_goods_quantity_cost-$grand_main_balance;
                        $opening_transaction_finish_goods_cost=$finish_goods_transactions_info->opening_transaction_finish_goods_cost-$grand_main_balance;

                        $closing_transaction_finish_goods_cost=$finish_goods_transactions_info->closing_transaction_finish_goods_cost-$grand_main_balance;
                        $finish_goods_net_production_cost=$finish_goods_stocks_data->finish_goods_net_production_cost-$grand_main_balance;

                        $finish_goods_net_cost=$finish_goods_stocks_data->finish_goods_net_cost-$grand_main_balance;
                        $stocks_quantity_cost=$stocks_transactions_info->stocks_quantity_cost-$grand_main_balance;


                        $order_net_amount=$sales_order_data_info->order_net_amount-$grand_main_balance;

                        $order_item_cost=$sales_order_details_data_info->order_item_cost-$grand_main_balance;


                    }


                    if($quantity >= ($finish_goods_transactions_info->transaction_finish_goods_quantity)){

                        $closing_transaction_finish_goods_quantity=$finish_goods_transactions_info->closing_transaction_finish_goods_quantity+$final_quantity;
                        $finish_goods_net_production_quantity=$finish_goods_stocks_data->finish_goods_net_production_quantity+$final_quantity;

                        $finish_goods_net_quantity=$finish_goods_stocks_data->finish_goods_net_quantity+$final_quantity;

                        $order_item_quantity=$sales_order_details_data_info->order_item_quantity+$final_quantity;

                    }else{
                        $closing_transaction_finish_goods_quantity=$finish_goods_transactions_info->closing_transaction_finish_goods_quantity-$final_quantity;
                        $finish_goods_net_production_quantity=$finish_goods_stocks_data->finish_goods_net_production_quantity-$final_quantity;
                        $finish_goods_net_quantity=$finish_goods_stocks_data->finish_goods_net_quantity-$final_quantity;
                        $order_item_quantity=$sales_order_details_data_info->order_item_quantity-$final_quantity;

                    }


                        $finish_goods_transaction_info = [
                            'finish_goods_quantity_cost' =>$finish_goods_quantity_cost,
                            'closing_transaction_finish_goods_cost' =>$closing_transaction_finish_goods_cost,
                            'finish_goods_quantity_rate' =>$quantity_rate,
                            'transaction_finish_goods_quantity' =>$quantity,
                            'closing_transaction_finish_goods_quantity' =>$closing_transaction_finish_goods_quantity,
                            'opening_transaction_finish_goods_cost' =>$opening_transaction_finish_goods_cost,
                            'finish_goods_inventory' =>serialize($inventory_stocks_list),
                            'updated_by' => \Auth::user()->user_id,
                            'updated_at' =>$now,

                        ];

                        $finish_goods_data = [
                            'finish_goods_net_production_cost' =>$finish_goods_net_production_cost,
                            'finish_goods_net_production_quantity' =>$finish_goods_net_production_quantity,
                            'finish_goods_net_cost' =>$finish_goods_net_cost,
                            'finish_goods_net_quantity' =>$finish_goods_net_quantity,
                            'updated_by' => \Auth::user()->user_id,
                            'updated_at' =>$now,
                        ];


                        $sales_order_data=[
                            'order_status'=>1,
                            'updated_by'=>\Auth::user()->user_id,
                            'updated_at'=>$now,
                        ];


                        $sales_order_details_data=[
                            'order_item_process_status'=>1,
                            'order_item_process_list'=>serialize($inventory_stocks_list),
                            'updated_by'=>\Auth::user()->user_id,
                            'updated_at'=>$now,

                        ];

                    try{
                        \DB::table('ltech_finish_goods_transactions')->where('referrence',$transactions_id)->update($finish_goods_transaction_info);
                        \DB::table('ltech_finish_goods_stocks')->where('finish_goods_id',$finish_goods_id)->update($finish_goods_data);
                        \DB::table('ltech_sales_orders')->where('order_id',$meta_sales_order_info->field_value)->update($sales_order_data);
                        \DB::table('ltech_sales_order_details')->where('order_id',$sales_order_data_info->order_id)->update($sales_order_details_data);

                        \App\System::EventLogWrite('update,ltech_finish_goods_transactions',json_encode($finish_goods_transaction_info));
                        \App\System::EventLogWrite('update,ltech_finish_goods_stocks',json_encode($finish_goods_data));
                        \App\System::EventLogWrite('update,ltech_sales_orders',json_encode($sales_order_data));
                        \App\System::EventLogWrite('update,ltech_sales_order_details',json_encode($sales_order_details_data));

                    }catch(\Exception $e){

                        \DB::rollback();
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                    }





            }


            if($current_transaction_info->posting_type == 'sales'){

                $finish_goods_transactions=\DB::table('ltech_finish_goods_transactions')
                                        ->where('referrence',$transactions_id)->get();
                $count=count($finish_goods_transactions);

                    $grand_total_cost=0;

                    for ($i=1; $i<=$count ; $i++) {

                            $order_details_item_id= \Request::input('order_details_item_'.$i);
                            $order_item_quantity= \Request::input('order_item_quantity_'.$i);
                            $order_quantity_rate = (int)(\Request::input('order_quantity_rate_'.$i));
                            $order_quantity_cost = (int)(\Request::input('order_quantity_cost_'.$i));

                            $finish_goods_tran_id= \Request::input('finish_goods_tran_id_'.$i);
                            $transaction_item_quantity= \Request::input('transaction_item_quantity_'.$i);
                            $transaction_quantity_rate = (int)(\Request::input('transaction_quantity_rate_'.$i));
                            $transaction_quantity_cost = (int)(\Request::input('transaction_quantity_cost_'.$i));

                        $finish_goods_transactions=\DB::table('ltech_finish_goods_transactions')
                                                    ->where('ltech_finish_goods_transactions_id',$finish_goods_tran_id)
                                                    ->first();
                        $customer_id=$finish_goods_transactions->customer_id;
                        $current_quantity=$finish_goods_transactions->transaction_finish_goods_quantity;
                        $final_quantity=$current_quantity-$order_item_quantity;
                        $final_quantity=\App\Report::CreatePositiveData($final_quantity);

                        $ltech_customers_info=\DB::table('ltech_customers')
                                    ->where('customer_id',$customer_id)->first();

                        $finish_goods_stocks_data_info=\DB::table('ltech_finish_goods_stocks')
                                                    ->where('finish_goods_id',$finish_goods_transactions->finish_goods_id)
                                                    ->first();
                        $finish_goods_rate=($finish_goods_stocks_data_info->finish_goods_net_production_cost)/($finish_goods_stocks_data_info->finish_goods_net_production_quantity);

                        $sales_order_details_info=\DB::table('ltech_sales_order_details')
                                    ->where('order_details_id',$order_details_item_id)->first();


                            if(($order_quantity_cost !=$transaction_quantity_cost) || ($order_item_quantity!=$transaction_item_quantity) || ($order_quantity_rate!=$transaction_quantity_rate)){
                                \DB::rollback();
                                return \Redirect::to('/general/transaction-list')->with('errormessage','Transaction cost and Order cost must be same.');
                            }

                            if(($order_item_quantity*$order_quantity_rate !=$order_quantity_cost)){
                                \DB::rollback();
                                return \Redirect::to('/general/transaction-list')->with('errormessage','Order quantity and rate multification must be same to transaction cost');
                            }


                            $grand_total_cost=$grand_total_cost+$order_quantity_cost;

                            $current_quantity=$sales_order_details_info->order_item_deliverd_quantity;
                            $current_rate=$sales_order_details_info->order_item_deliverd_quantity_rate;
                            $current_cost=$sales_order_details_info->order_item_deliverd_cost;
                            $total_quantity=$order_item_quantity-$current_quantity;
                            $total_quantity=\App\Report::CreatePositiveData($total_quantity);

                            $total_cost=$current_cost-$order_quantity_cost;
                            $total_cost=\App\Report::CreatePositiveData($total_cost);

                            if($order_item_quantity>=$current_quantity){
                                $order_item_deliverd_quantity=$sales_order_details_info->order_item_deliverd_quantity+$total_quantity;


                                $closing_transaction_finish_goods_quantity=$finish_goods_transactions->closing_transaction_finish_goods_quantity+$final_quantity;
                                $finish_goods_net_sales_quantity=$finish_goods_stocks_data_info->finish_goods_net_sales_quantity+$final_quantity;
                                $finish_goods_net_quantity=$finish_goods_stocks_data_info->finish_goods_net_quantity-$final_quantity;
                            }else{
                                $order_item_deliverd_quantity=$sales_order_details_info->order_item_deliverd_quantity-$total_quantity;

                                $closing_transaction_finish_goods_quantity=$finish_goods_transactions->closing_transaction_finish_goods_quantity-$final_quantity;
                                $finish_goods_net_sales_quantity=$finish_goods_stocks_data_info->finish_goods_net_sales_quantity-$final_quantity;
                                $finish_goods_net_quantity=$finish_goods_stocks_data_info->finish_goods_net_quantity+$final_quantity;


                            }

                            if($order_quantity_cost>=$current_cost){

                                $order_item_deliverd_cost=$sales_order_details_info->order_item_deliverd_cost+$total_cost;


                                $finish_goods_quantity_cost=$finish_goods_transactions->finish_goods_quantity_cost+$total_cost;
                                $closing_transaction_finish_goods_cost=$finish_goods_transactions->closing_transaction_finish_goods_cost+$total_cost;

                                $finish_goods_net_sales_cost=$finish_goods_stocks_data_info->finish_goods_net_sales_cost+$total_cost;
                                $finish_goods_net_cost=$finish_goods_stocks_data_info->finish_goods_net_cost-($finish_goods_rate*$final_quantity);


                                $customer_net_credit_amount=$ltech_customers_info->customer_net_credit_amount+$total_cost;
                                $customer_net_balance_amount=$ltech_customers_info->customer_net_balance_amount+$total_cost;


                            }else{
                                $order_item_deliverd_cost=$sales_order_details_info->order_item_deliverd_cost-$total_cost;

                                $finish_goods_quantity_cost=$finish_goods_transactions->finish_goods_quantity_cost-$total_cost;
                                $closing_transaction_finish_goods_cost=$finish_goods_transactions->closing_transaction_finish_goods_cost-$total_cost;

                                $finish_goods_net_sales_cost=$finish_goods_stocks_data_info->finish_goods_net_sales_cost-$total_cost;
                                // $finish_goods_net_cost=$finish_goods_stocks_data_info->finish_goods_net_cost+$total_cost;
                                $finish_goods_net_cost=$finish_goods_stocks_data_info->finish_goods_net_cost+($finish_goods_rate*$final_quantity);

                                $customer_net_credit_amount=$ltech_customers_info->customer_net_credit_amount-$total_cost;
                                $customer_net_balance_amount=$ltech_customers_info->customer_net_balance_amount-$total_cost;

                            }


                            $order_details_data=[
                                'order_item_deliverd_quantity'=>$order_item_deliverd_quantity,
                                'order_item_deliverd_quantity_rate'=>$order_quantity_rate,
                                'order_item_deliverd_cost'=>$order_item_deliverd_cost
                            ];


                            $finish_goods_transaction_info = [
                                        'finish_goods_quantity_cost' =>$transaction_quantity_cost,
                                        'closing_transaction_finish_goods_cost' =>$closing_transaction_finish_goods_cost,
                                        'transaction_finish_goods_quantity'=>$transaction_item_quantity,
                                        'finish_goods_quantity_rate'=>$transaction_quantity_rate,
                                        'closing_transaction_finish_goods_quantity'=>$closing_transaction_finish_goods_quantity,
                                        'updated_by' => \Auth::user()->user_id,
                                        'updated_at' =>$now,

                                    ];

                            $finish_goods_info = [
                                    'finish_goods_net_sales_cost' =>$finish_goods_net_sales_cost,
                                    'finish_goods_net_sales_quantity' =>$finish_goods_net_sales_quantity,
                                    'finish_goods_net_cost' =>$finish_goods_net_cost,
                                    'finish_goods_net_quantity' =>$finish_goods_net_quantity,
                                    'updated_by' => \Auth::user()->user_id,
                                    'updated_at' =>$now,

                            ];

                            $customer_info = [
                                    'customer_net_credit_amount' =>$customer_net_credit_amount,
                                    'customer_net_balance_amount' =>$customer_net_balance_amount,
                                    'updated_by' => \Auth::user()->user_id,
                                    'updated_at' =>$now,
                            ];

                            $meta_sales_order_info=\DB::table('ltech_transaction_meta')
                                            ->where('transaction_id',$transactions_id)
                                            ->where('field_name','ltech_finish_goods_transactions')
                                            ->where('field_value','ltech_sales_orders')
                                            ->first();


                            try{

                                \DB::table('ltech_sales_order_details')->where('order_details_id',$order_details_item_id)->update($order_details_data);
                                \DB::table('ltech_finish_goods_transactions')
                                ->where('ltech_finish_goods_transactions_id',$finish_goods_tran_id)
                                ->update($finish_goods_transaction_info);

                                \DB::table('ltech_finish_goods_stocks')->where('finish_goods_id',$finish_goods_transactions->finish_goods_id)->update($finish_goods_info);

                                \DB::table('ltech_customers')->where('customer_id',$customer_id)->update($customer_info);

                                \App\System::EventLogWrite('update,ltech_customers',json_encode($customer_info));

                                \App\System::EventLogWrite('update,ltech_sales_order_details',json_encode($order_details_data));
                                \App\System::EventLogWrite('update,ltech_finish_goods_transactions',json_encode($finish_goods_transaction_info));
                                \App\System::EventLogWrite('update,ltech_finish_goods_stocks',json_encode($finish_goods_info));

                            }catch(\Exception $e){

                                \DB::rollback();
                                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                                \App\System::ErrorLogWrite($message);

                                return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                            }


                    }

                        if($transaction_amount!=$grand_total_cost){
                            \DB::rollback();
                            return \Redirect::to('/general/transaction-list')->with('errormessage','Transaction cost and Order cost must be same.');
                        }

                        $sales_orders_info=\DB::table('ltech_sales_orders')
                                    ->where('sales_referrence',$transactions_id)->first();
                        $current_sales_order_cost=$sales_orders_info->order_delivery_balance_amount;
                        $added_amount=$current_sales_order_cost-$grand_total_cost;
                        $added_amount=\App\Report::CreatePositiveData($added_amount);

                            if($grand_total_cost>=$current_sales_order_cost){

                                $order_delivery_amount=$sales_orders_info->order_delivery_amount+$added_amount;
                                $order_delivery_net_amount=$sales_orders_info->order_delivery_net_amount+$added_amount;
                                $order_delivery_credit_amount=$sales_orders_info->order_delivery_credit_amount+$added_amount;
                                $order_delivery_balance_amount=$sales_orders_info->order_delivery_balance_amount+$added_amount;
                                $customer_order_delivery_net_balance_amount=$sales_orders_info->customer_order_delivery_net_balance_amount+$added_amount;

                            }else{

                                $order_delivery_amount=$sales_orders_info->order_delivery_amount-$added_amount;
                                $order_delivery_net_amount=$sales_orders_info->order_delivery_net_amount-$added_amount;
                                $order_delivery_credit_amount=$sales_orders_info->order_delivery_credit_amount-$added_amount;
                                $order_delivery_balance_amount=$sales_orders_info->order_delivery_balance_amount-$added_amount;
                                $customer_order_delivery_net_balance_amount=$sales_orders_info->customer_order_delivery_net_balance_amount-$added_amount;

                            }


                            $sales_order_data=[
                                        'updated_by' =>\Auth::user()->user_id,
                                        'updated_at' => $now,
                                        'order_delivery_amount'=>$order_delivery_amount,
                                        'order_delivery_net_amount'=>$order_delivery_net_amount,
                                        'order_delivery_credit_amount' =>$order_delivery_credit_amount,
                                        'order_delivery_balance_amount' =>$order_delivery_balance_amount,
                                        'customer_order_delivery_net_balance_amount'=>$customer_order_delivery_net_balance_amount,
                                    ];
                                    
                            try{
                                \DB::table('ltech_sales_orders')->where('sales_referrence',$transactions_id)->update($sales_order_data);
                                \App\System::EventLogWrite('update,ltech_sales_orders',json_encode($sales_order_data));

                            }catch(\Exception $e){

                                \DB::rollback();
                                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                                \App\System::ErrorLogWrite($message);

                                return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                            }


            }


            if($current_transaction_info->posting_type == 'purchase'){

                $all_inventory_stocks_transactions=\DB::table('ltech_inventory_stocks_transactions')->where('referrence',$transactions_id)->get();
                $count=count($all_inventory_stocks_transactions);

                $grand_total_stocks_cost=0;

                    foreach ($all_inventory_stocks_transactions as $key => $value) {
                    $i=$key+1;


                    $select_transactions_id = \Request::input('stocks_tran_id_'.$i);
                    $quantity = (\Request::input('quantity_'.$i));
                    $quantity_rate = (\Request::input('quantity_rate_'.$i));
                    $quantity_cost =(\Request::input('quantity_cost_'.$i));

                    if(($quantity*$quantity_rate) !=$quantity_cost || $select_transactions_id !=($value->stocks_transactions_id)){
                        \DB::rollback();
                        return \Redirect::to('/general/transaction-list')->with('errormessage','Quantity and rate multification is to be same of cost');
                    }


                    $inventory_stocks_transactions=\DB::table('ltech_inventory_stocks_transactions')->where('referrence',$select_transactions_id)->first();

                    $current_quantity =$value->transaction_stocks_quantity;

                    $final_quantity=$current_quantity-$quantity;
                    $final_quantity=\App\Report::CreatePositiveData($final_quantity);
                    $current_transaction_amount=$value->closing_transaction_stocks_cost;
                    $main_quantity_cost=$current_transaction_amount-$quantity_cost;
                    $main_quantity_cost=\App\Report::CreatePositiveData($main_quantity_cost);


                    $supplier_id=$value->stocks_supplier_id;
                    $inventory_stock_id=$value->inventory_stock_id;

                    $inventory_stocks_data_info=\DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stock_id)->first();

                    $ltech_suppliers_info=\DB::table('ltech_suppliers')
                                        ->where('supplier_id',$supplier_id)->first();

                    if($quantity_cost >= $current_transaction_amount){

                        $supplier_net_debit_amount=$ltech_suppliers_info->supplier_net_credit_amount+$main_quantity_cost;
                        $supplier_net_balance_amount=$ltech_suppliers_info->supplier_net_balance_amount+$main_quantity_cost;

                        $stocks_supplier_credit_amount=$value->stocks_supplier_credit_amount+$main_quantity_cost;
                        $stocks_supplier_balance_amount=$value->stocks_supplier_balance_amount+$main_quantity_cost;



                        $stocks_total_cost=$inventory_stocks_data_info->stocks_total_cost+$main_quantity_cost;
                        $closing_transaction_stocks_cost=$value->closing_transaction_stocks_cost+$main_quantity_cost;

                    }else{

                        $supplier_net_debit_amount=$ltech_suppliers_info->supplier_net_credit_amount-$main_quantity_cost;
                        $supplier_net_balance_amount=$ltech_suppliers_info->supplier_net_balance_amount-$main_quantity_cost;

                        $stocks_supplier_credit_amount=$value->stocks_supplier_credit_amount-$main_quantity_cost;
                        $stocks_supplier_balance_amount=$value->stocks_supplier_balance_amount-$main_quantity_cost;

                        $closing_transaction_stocks_cost=$value->closing_transaction_stocks_cost-$main_quantity_cost;
                        $stocks_total_cost=$inventory_stocks_data_info->stocks_total_cost-$main_quantity_cost;


                    }

                    if($quantity >= ($value->transaction_stocks_quantity)){
                        $closing_transaction_stocks_quantity=$value->closing_transaction_stocks_quantity+$final_quantity;

                        $stocks_onhand=$inventory_stocks_data_info->stocks_onhand+$final_quantity;
                        $stocks_total_quantity=$inventory_stocks_data_info->stocks_total_quantity+$final_quantity;

                    }else{
                        $closing_transaction_stocks_quantity=$value->closing_transaction_stocks_quantity-$final_quantity;

                        $stocks_onhand=$inventory_stocks_data_info->stocks_onhand-$final_quantity;
                        $stocks_total_quantity=$inventory_stocks_data_info->stocks_total_quantity-$final_quantity;

                    }

                        $supplier_info = [
                            'supplier_net_credit_amount' =>$supplier_net_debit_amount,
                            'supplier_net_balance_amount' =>$supplier_net_balance_amount,
                            'updated_by' => \Auth::user()->user_id,
                            'updated_at' =>$now,

                        ];

                        $inventory_stocks_transactions_data = [
                            'transaction_stocks_quantity' =>$quantity,
                            'closing_transaction_stocks_quantity' =>$closing_transaction_stocks_quantity,
                            'stocks_quantity_rate' =>$quantity_rate,
                            'stocks_quantity_cost' =>$stocks_total_cost,
                            'closing_transaction_stocks_cost' =>$closing_transaction_stocks_cost,
                            'stocks_supplier_credit_amount' =>$stocks_supplier_credit_amount,
                            'stocks_supplier_balance_amount' =>$stocks_supplier_balance_amount,
                            'updated_by' => \Auth::user()->user_id,
                            'updated_at' =>$now,

                        ];

                        $inventory_stocks_data = [
                            'stocks_onhand' =>$stocks_onhand,
                            'stocks_total_quantity' =>$stocks_total_quantity,
                            'stocks_total_cost' =>$stocks_total_cost,
                            'updated_by' => \Auth::user()->user_id,
                            'updated_at' =>$now,

                        ];
                    $grand_total_stocks_cost=$grand_total_stocks_cost+$quantity_cost;


                    try{
                        \DB::table('ltech_suppliers')->where('supplier_id',$supplier_id)->update($supplier_info);
                        \DB::table('ltech_inventory_stocks_transactions')->where('stocks_transactions_id',$select_transactions_id)->update($inventory_stocks_transactions_data);
                        \DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stock_id)->update($inventory_stocks_data);

                        \App\System::EventLogWrite('update,ltech_suppliers',json_encode($supplier_info));
                        \App\System::EventLogWrite('update,ltech_inventory_stocks_transactions',json_encode($inventory_stocks_transactions_data));
                        \App\System::EventLogWrite('update,ltech_inventory_stocks',json_encode($inventory_stocks_data));

                    }catch(\Exception $e){

                        \DB::rollback();
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                    }
                }

                if($grand_total_stocks_cost!=$transaction_amount){
                    \DB::rollback();
                    return \Redirect::to('/general/transaction-list')->with('errormessage','Transaction cost and all cost is to be equal');
                }

            }

#########################################


            if($current_transaction_info->posting_type == 'receipt'){


                $customer_payment_transactions_info=\DB::table('ltech_customer_payment_transactions')->where('referrence',$transactions_id)->first();

                $customer_id=$customer_payment_transactions_info->customer_id;

                $ltech_customers_info=\DB::table('ltech_customers')
                                    ->where('customer_id',$customer_id)->first();

                $meta_sales_order_info=\DB::table('ltech_transaction_meta')
                                            ->where('transaction_id',$transactions_id)
                                            ->where('field_name','ltech_sales_orders')
                                            ->first();
                $sales_order_data_info=\DB::table('ltech_sales_orders')->where('order_id',$meta_sales_order_info->field_value)->first();


                if($transaction_amount >= $current_transaction_amount){

                    $customer_net_debit_amount=$ltech_customers_info->customer_net_debit_amount+$grand_main_balance;
                    $customer_net_balance_amount=$ltech_customers_info->customer_net_balance_amount-$grand_main_balance;

                    $closing_customer_debit_amount=$customer_payment_transactions_info->closing_customer_debit_amount+$grand_main_balance;
                    $closing_customer_balance_amount=$customer_payment_transactions_info->closing_customer_balance_amount-$grand_main_balance;

                    $order_delivery_debit_amount=$sales_order_data_info->order_delivery_debit_amount+$grand_main_balance;
                    $order_delivery_balance_amount=$sales_order_data_info->order_delivery_balance_amount-$grand_main_balance;
                    $customer_order_delivery_net_balance_amount=$sales_order_data_info->customer_order_delivery_net_balance_amount-$grand_main_balance;


                }else{

                    $customer_net_debit_amount=$ltech_customers_info->customer_net_debit_amount-$grand_main_balance;
                    $customer_net_balance_amount=$ltech_customers_info->customer_net_balance_amount+$grand_main_balance;


                    $closing_customer_debit_amount=$customer_payment_transactions_info->closing_customer_debit_amount-$grand_main_balance;
                    $closing_customer_balance_amount=$customer_payment_transactions_info->closing_customer_balance_amount+$grand_main_balance;

                    $order_delivery_debit_amount=$sales_order_data_info->order_delivery_debit_amount-$grand_main_balance;
                    $order_delivery_balance_amount=$sales_order_data_info->order_delivery_balance_amount+$grand_main_balance;
                    $customer_order_delivery_net_balance_amount=$sales_order_data_info->customer_order_delivery_net_balance_amount+$grand_main_balance;


                }

                    $customer_info = [
                        'customer_net_debit_amount' =>$customer_net_debit_amount,
                        'customer_net_balance_amount' =>$customer_net_balance_amount,
                        'updated_by' => \Auth::user()->user_id,
                        'updated_at' =>$now,

                    ];
                    $customer_payment_transactions_data = [
                        'closing_customer_debit_amount' =>$closing_customer_debit_amount,
                        'closing_customer_balance_amount' =>$closing_customer_balance_amount,
                        'transaction_amount'=>$transaction_amount,
                        'updated_by' => \Auth::user()->user_id,
                        'updated_at' =>$now,

                    ];

                    $update_payment_order_data = [
                        'order_delivery_debit_amount'=>$order_delivery_debit_amount,
                        'order_delivery_balance_amount'=>$order_delivery_balance_amount,
                        'customer_order_delivery_net_balance_amount'=>$customer_order_delivery_net_balance_amount,
                        'updated_by'=> \Auth::user()->user_id,
                        'updated_at'=> $now,
                    ];


                try{
                    \DB::table('ltech_customers')->where('customer_id',$customer_id)->update($customer_info);
                    \DB::table('ltech_customer_payment_transactions')->where('referrence',$transactions_id)->update($customer_payment_transactions_data);

                    \DB::table('ltech_sales_orders')->where('order_id',$meta_sales_order_info->field_value)->update($update_payment_order_data);

                    \App\System::EventLogWrite('update,ltech_sales_orders',json_encode($update_payment_order_data));

                    \App\System::EventLogWrite('update,ltech_customers',json_encode($customer_info));
                    \App\System::EventLogWrite('update,ltech_customer_payment_transactions',json_encode($customer_payment_transactions_data));

                }catch(\Exception $e){

                    \DB::rollback();
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                }

            }



                if($current_transaction_info->posting_type == 'sales_return'){

                    $finish_goods_transactions=\DB::table('ltech_finish_goods_transactions')
                                        ->where('referrence',$transactions_id)->get();
                    $count=count($finish_goods_transactions);

                    $grand_total_cost=0;
                    $total_order_quantity_cost=0;
                    $total_transaction_quantity_cost=0;

                    for ($i=1; $i<=$count ; $i++) {

                        $order_details_item_id= \Request::input('order_details_item_'.$i);
                        $order_item_quantity= \Request::input('order_item_quantity_'.$i);
                        $order_quantity_rate = (int)(\Request::input('order_quantity_rate_'.$i));
                        $order_quantity_cost = (int)(\Request::input('order_quantity_cost_'.$i));
                        $total_order_quantity_cost=$total_order_quantity_cost+$order_quantity_cost;


                        $finish_goods_tran_id= \Request::input('finish_goods_tran_id_'.$i);
                        $transaction_item_quantity= \Request::input('transaction_item_quantity_'.$i);
                        $transaction_quantity_rate = (int)(\Request::input('transaction_quantity_rate_'.$i));
                        $transaction_quantity_cost = (int)(\Request::input('transaction_quantity_cost_'.$i));

                        if(($order_item_quantity*$order_quantity_rate)!=$order_quantity_cost ||($transaction_item_quantity*$transaction_quantity_rate)!=$transaction_quantity_cost){
                            \DB::rollback();
                            return \Redirect::to('/general/transaction-list')->with('errormessage','Quantity and rate multification is to be same of cost.');
                        }


                        $total_transaction_quantity_cost=$total_transaction_quantity_cost+$transaction_quantity_cost;


                        $finish_goods_transactions=\DB::table('ltech_finish_goods_transactions')
                                                    ->where('ltech_finish_goods_transactions_id',$finish_goods_tran_id)
                                                    ->first();
                        $customer_id=$finish_goods_transactions->customer_id;
                        $current_quantity=$finish_goods_transactions->transaction_finish_goods_quantity;
                        $current_quantity_cost=$finish_goods_transactions->finish_goods_quantity_cost;
                        $final_quantity=$current_quantity-$transaction_item_quantity;
                        $final_quantity=\App\Report::CreatePositiveData($final_quantity);

                        $final_quantity_cost=$current_quantity_cost-$transaction_quantity_cost;
                        $final_quantity_cost=\App\Report::CreatePositiveData($final_quantity_cost);

                        $finish_goods_stocks_data_info=\DB::table('ltech_finish_goods_stocks')
                                                    ->where('finish_goods_id',$finish_goods_transactions->finish_goods_id)
                                                    ->first();
                        $finish_goods_rate=($finish_goods_stocks_data_info->finish_goods_net_production_cost)/($finish_goods_stocks_data_info->finish_goods_net_production_quantity);


                        $ltech_customers_info=\DB::table('ltech_customers')
                                    ->where('customer_id',$customer_id)->first();
                        $sales_orders_info=\DB::table('ltech_sales_orders')
                                    ->where('sales_return_referrence',$transactions_id)->first();

                        $sales_order_details_info=\DB::table('ltech_sales_order_details')
                                    ->where('order_details_id',$order_details_item_id)->first();

                        $current_order_quantity=$sales_order_details_info->order_item_deliverd_quantity;
                        $current_order_quantity_cost=$sales_order_details_info->order_item_deliverd_cost;
                        $final_order_quantity=$current_order_quantity-$order_item_quantity;
                        $final_order_quantity=\App\Report::CreatePositiveData($final_order_quantity);
                        $final_order_quantity_cost=$current_order_quantity_cost-$order_quantity_cost;
                        $final_order_quantity_cost=\App\Report::CreatePositiveData($final_order_quantity_cost);


                            if($transaction_item_quantity>=$current_quantity){

                                $transaction_finish_goods_quantity=$finish_goods_transactions->transaction_finish_goods_quantity+$final_quantity;
                                $closing_transaction_finish_goods_quantity=$finish_goods_transactions->closing_transaction_finish_goods_quantity-$final_quantity;
                                $finish_goods_net_sales_quantity=$finish_goods_stocks_data_info->finish_goods_net_sales_quantity-$final_quantity;
                                $finish_goods_net_quantity=$finish_goods_stocks_data_info->finish_goods_net_quantity+$final_quantity;

                            }else{

                                $transaction_finish_goods_quantity=$finish_goods_transactions->transaction_finish_goods_quantity-$final_quantity;
                                $closing_transaction_finish_goods_quantity=$finish_goods_transactions->closing_transaction_finish_goods_quantity+$final_quantity;
                                $finish_goods_net_sales_quantity=$finish_goods_stocks_data_info->finish_goods_net_sales_quantity+$final_quantity;
                                $finish_goods_net_quantity=$finish_goods_stocks_data_info->finish_goods_net_quantity-$final_quantity;

                            }

                            if($transaction_quantity_cost>=$current_quantity_cost){


                                $finish_goods_quantity_cost=$finish_goods_transactions->finish_goods_quantity_cost+$final_quantity_cost;
                                $closing_transaction_finish_goods_cost=$finish_goods_transactions->closing_transaction_finish_goods_cost-$final_quantity_cost;

                                $finish_goods_net_sales_cost=$finish_goods_stocks_data_info->finish_goods_net_sales_cost-$final_quantity_cost;
                                // $finish_goods_net_cost=$finish_goods_stocks_data_info->finish_goods_net_cost+$final_quantity_cost;
                                $finish_goods_net_cost=$finish_goods_stocks_data_info->finish_goods_net_cost+($finish_goods_rate*$final_quantity);


                                $customer_net_credit_amount=$ltech_customers_info->customer_net_credit_amount-$final_quantity_cost;
                                $customer_net_balance_amount=$ltech_customers_info->customer_net_balance_amount-$final_quantity_cost;


                            }else{

                                $finish_goods_quantity_cost=$finish_goods_transactions->finish_goods_quantity_cost-$final_quantity_cost;
                                $closing_transaction_finish_goods_cost=$finish_goods_transactions->closing_transaction_finish_goods_cost+$final_quantity_cost;

                                $finish_goods_net_sales_cost=$finish_goods_stocks_data_info->finish_goods_net_sales_cost+$final_quantity_cost;
                                $finish_goods_net_cost=$finish_goods_stocks_data_info->finish_goods_net_cost-($finish_goods_rate*$final_quantity);

                                $customer_net_credit_amount=$ltech_customers_info->customer_net_credit_amount+$final_quantity_cost;
                                $customer_net_balance_amount=$ltech_customers_info->customer_net_balance_amount+$final_quantity_cost;

                            }



                            $finish_goods_transaction_info = [
                                        'finish_goods_quantity_cost' =>$transaction_quantity_cost,
                                        'closing_transaction_finish_goods_cost' =>$closing_transaction_finish_goods_cost,
                                        'transaction_finish_goods_quantity'=>$transaction_finish_goods_quantity,
                                        'finish_goods_quantity_rate'=>$transaction_quantity_rate,
                                        'closing_transaction_finish_goods_quantity'=>$closing_transaction_finish_goods_quantity,
                                        'finish_goods_inventory' =>'',
                                        'updated_by' => \Auth::user()->user_id,
                                        'updated_at' =>$now,

                                    ];

                            $finish_goods_info = [
                                    'finish_goods_net_sales_cost' =>$finish_goods_net_sales_cost,
                                    'finish_goods_net_sales_quantity' =>$finish_goods_net_sales_quantity,
                                    'finish_goods_net_cost' =>$finish_goods_net_cost,
                                    'finish_goods_net_quantity' =>$finish_goods_net_quantity,
                                    'updated_by' => \Auth::user()->user_id,
                                    'updated_at' =>$now,

                            ];


                            if($order_item_quantity>=$current_order_quantity){

                                $order_item_deliverd_quantity=$sales_order_details_info->order_item_deliverd_quantity+$final_order_quantity; 

                            }else{

                                $order_item_deliverd_quantity=$sales_order_details_info->order_item_deliverd_quantity-$final_order_quantity;

                            }

                            if($order_quantity_cost>=$current_order_quantity_cost){

                                $order_item_deliverd_cost=$sales_order_details_info->order_item_deliverd_cost+$final_order_quantity_cost;


                                $order_delivery_amount=$sales_orders_info->order_delivery_amount-$final_order_quantity_cost;
                                $order_delivery_net_amount=$sales_orders_info->order_delivery_net_amount+$final_order_quantity_cost;
                                $order_delivery_credit_amount=$sales_orders_info->order_delivery_credit_amount+$final_order_quantity_cost;
                                $order_delivery_balance_amount=$sales_orders_info->order_delivery_balance_amount+$final_order_quantity_cost;
                                $customer_order_delivery_net_balance_amount=$sales_orders_info->customer_order_delivery_net_balance_amount+$final_order_quantity_cost;

                            }else{

                                $order_item_deliverd_cost=$sales_order_details_info->order_item_deliverd_cost-$final_order_quantity_cost;


                                $order_delivery_amount=$sales_orders_info->order_delivery_amount+$final_order_quantity_cost;
                                $order_delivery_net_amount=$sales_orders_info->order_delivery_net_amount-$final_order_quantity_cost;
                                $order_delivery_credit_amount=$sales_orders_info->order_delivery_credit_amount-$final_order_quantity_cost;
                                $order_delivery_balance_amount=$sales_orders_info->order_delivery_balance_amount-$final_order_quantity_cost;
                                $customer_order_delivery_net_balance_amount=$sales_orders_info->customer_order_delivery_net_balance_amount-$final_order_quantity_cost;

                            }

                            $order_details_data=[
                                'order_item_deliverd_quantity'=>$order_item_deliverd_quantity,
                                'order_item_deliverd_quantity_rate'=>$order_quantity_rate,
                                'order_item_deliverd_cost'=>$order_item_deliverd_cost
                            ];

                            $sales_order_data=[
                                        'order_delivery_amount'=>$order_delivery_amount,
                                        'order_delivery_net_amount'=>$order_delivery_net_amount,
                                        'order_delivery_credit_amount' =>$order_delivery_credit_amount,
                                        'order_delivery_balance_amount' =>$order_delivery_balance_amount,
                                        'customer_order_delivery_net_balance_amount'=>$customer_order_delivery_net_balance_amount,
                                        'updated_by' =>\Auth::user()->user_id,
                                        'updated_at' => $now,

                            ];



                            $customer_info = [
                                    'customer_net_credit_amount' =>$customer_net_credit_amount,
                                    'customer_net_balance_amount' =>$customer_net_balance_amount,
                                    'updated_by' => \Auth::user()->user_id,
                                    'updated_at' =>$now,
                            ];


                            $meta_sales_order_info=\DB::table('ltech_transaction_meta')
                                            ->where('transaction_id',$transactions_id)
                                            ->where('field_name','ltech_finish_goods_transactions')
                                            ->where('field_value','ltech_sales_orders')
                                            ->first();


                            try{

                                \DB::table('ltech_sales_order_details')->where('order_details_id',$order_details_item_id)->update($order_details_data);
                                \DB::table('ltech_finish_goods_transactions')->where('ltech_finish_goods_transactions_id',$finish_goods_tran_id)->update($finish_goods_transaction_info);
                                \DB::table('ltech_finish_goods_stocks')->where('finish_goods_id',$finish_goods_transactions->finish_goods_id)->update($finish_goods_info);
                                \DB::table('ltech_customers')->where('customer_id',$customer_id)->update($customer_info);
                                \DB::table('ltech_sales_orders')->where('sales_return_referrence',$transactions_id)->update($sales_order_data);

                                \App\System::EventLogWrite('update,ltech_sales_orders',json_encode($sales_order_data));
                                \App\System::EventLogWrite('update,ltech_customers',json_encode($customer_info));
                                \App\System::EventLogWrite('update,ltech_sales_order_details',json_encode($order_details_data));
                                \App\System::EventLogWrite('update,ltech_finish_goods_transactions',json_encode($finish_goods_transaction_info));
                                \App\System::EventLogWrite('update,ltech_finish_goods_stocks',json_encode($finish_goods_info));

                            }catch(\Exception $e){

                                \DB::rollback();
                                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                                \App\System::ErrorLogWrite($message);

                                return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                            }


                    }

                        if($total_transaction_quantity_cost !=$transaction_amount){
                            \DB::rollback();
                            return \Redirect::to('/general/transaction-list')->with('errormessage','All transaction cost must be same.');

                        }



                }


            if($current_transaction_info->posting_type == 'payment'){

                $supplier_credit_transactions=\DB::table('ltech_inventory_supplier_credit_transactions')->where('referrence',$transactions_id)->first();

                $supplier_id=$supplier_credit_transactions->supplier_id;

                $ltech_suppliers_info=\DB::table('ltech_suppliers')
                                    ->where('supplier_id',$supplier_id)->first();


                if($transaction_amount >= $current_transaction_amount){

                    $supplier_net_debit_amount=$ltech_suppliers_info->supplier_net_debit_amount+$grand_main_balance;
                    $supplier_net_balance_amount=$ltech_suppliers_info->supplier_net_balance_amount-$grand_main_balance;

                    $closing_stocks_debit_amount=$supplier_credit_transactions->closing_stocks_debit_amount+$grand_main_balance;
                    $closing_stocks_balance_amount=$supplier_credit_transactions->closing_stocks_balance_amount-$grand_main_balance;


                }else{

                    $supplier_net_debit_amount=$ltech_suppliers_info->supplier_net_debit_amount-$grand_main_balance;
                    $supplier_net_balance_amount=$ltech_suppliers_info->supplier_net_balance_amount+$grand_main_balance;

                    $closing_stocks_debit_amount=$supplier_credit_transactions->closing_stocks_debit_amount-$grand_main_balance;
                    $closing_stocks_balance_amount=$supplier_credit_transactions->closing_stocks_balance_amount+$grand_main_balance;


                }


                    $supplier_info = [
                        'supplier_net_debit_amount' =>$supplier_net_debit_amount,
                        // 'supplier_net_credit_amount' =>$supplier_net_credit_amount,
                        'supplier_net_balance_amount' =>$supplier_net_balance_amount,
                        'updated_by' => \Auth::user()->user_id,
                        'updated_at' =>$now,

                    ];

                    $supplier_credit_transactions_data = [
                        'closing_stocks_debit_amount' =>$closing_stocks_debit_amount,
                        'closing_stocks_balance_amount' =>$closing_stocks_balance_amount,
                        'transaction_amount'=>$transaction_amount,
                        'updated_by' => \Auth::user()->user_id,
                        'updated_at' =>$now,

                    ];
                    try{    

                        \DB::table('ltech_suppliers')->where('supplier_id',$supplier_id)->update($supplier_info);
                        \DB::table('ltech_inventory_supplier_credit_transactions')->where('referrence',$transactions_id)->update($supplier_credit_transactions_data);

                        \App\System::EventLogWrite('update,ltech_suppliers',json_encode($supplier_info));
                        \App\System::EventLogWrite('update,ltech_inventory_supplier_credit_transactions',json_encode($supplier_credit_transactions_data));

                    }catch(\Exception $e){

                        \DB::rollback();
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                    }



            }


            if($current_transaction_info->posting_type == 'purchase_return'){

                $quantity = \Request::input('quantity');
                $quantity_rate = \Request::input('quantity_rate');
                $quantity_cost = \Request::input('quantity_cost');
                $stocks_transactions_id = \Request::input('stocks_tran_id');
                if(($quantity*$quantity_rate !=$quantity_cost )|| ($quantity_cost !=$transaction_amount)){
                    \DB::rollback();
                    return \Redirect::to('/general/transaction-list')->with('errormessage','There are an error for rate/quantity/cost.');
                }

                $inventory_stocks_transactions=\DB::table('ltech_inventory_stocks_transactions')
                                        ->where('stocks_transactions_id',$stocks_transactions_id)
                                        ->first();

                $current_quantity=$inventory_stocks_transactions->transaction_stocks_quantity;
                $final_quantity=$current_quantity-$quantity;
                $final_quantity=\App\Report::CreatePositiveData($final_quantity);

                $inventory_stock_id=$inventory_stocks_transactions->inventory_stock_id;

                $inventory_stocks_data_info=\DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stock_id)->first();

                $supplier_credit_transactions=\DB::table('ltech_inventory_supplier_credit_transactions')->where('referrence',$transactions_id)->first();
                

                $supplier_id=$inventory_stocks_transactions->stocks_supplier_id;
                $inventory_stock_id=$inventory_stocks_transactions->inventory_stock_id;

                $ltech_suppliers_info=\DB::table('ltech_suppliers')
                                    ->where('supplier_id',$supplier_id)->first();


                if($transaction_amount >= $current_transaction_amount){
                    $stocks_quantity_cost=$inventory_stocks_transactions->stocks_quantity_cost+$grand_main_balance;
                    $closing_transaction_stocks_cost=$inventory_stocks_transactions->closing_transaction_stocks_cost-$grand_main_balance;

                    $supplier_net_credit_amount=($ltech_suppliers_info->supplier_net_credit_amount)-$grand_main_balance;
                    $supplier_net_balance_amount=($ltech_suppliers_info->supplier_net_balance_amount)-$grand_main_balance;

                    $closing_stocks_credit_amount=$supplier_credit_transactions->closing_stocks_credit_amount-$grand_main_balance;
                    $closing_stocks_balance_amount=$supplier_credit_transactions->closing_stocks_balance_amount-$grand_main_balance;


                    $stocks_total_cost=$inventory_stocks_data_info->stocks_total_cost-$grand_main_balance;


                    $stocks_supplier_credit_amount=$inventory_stocks_transactions->stocks_supplier_credit_amount-$grand_main_balance;
                    $stocks_supplier_debit_amount=$inventory_stocks_transactions->stocks_supplier_debit_amount+$grand_main_balance;
                    $stocks_supplier_balance_amount=$inventory_stocks_transactions->stocks_supplier_balance_amount-$grand_main_balance;

                }else{
                    $stocks_quantity_cost=$inventory_stocks_transactions->stocks_quantity_cost-$grand_main_balance;
                    $closing_transaction_stocks_cost=$inventory_stocks_transactions->closing_transaction_stocks_cost+$grand_main_balance;

                    $supplier_net_credit_amount=($ltech_suppliers_info->supplier_net_credit_amount)+$grand_main_balance;
                    $supplier_net_balance_amount=($ltech_suppliers_info->supplier_net_balance_amount)+$grand_main_balance;

                    $closing_stocks_credit_amount=$supplier_credit_transactions->closing_stocks_credit_amount+$grand_main_balance;
                    $closing_stocks_balance_amount=$supplier_credit_transactions->closing_stocks_balance_amount+$grand_main_balance;


                    $stocks_total_cost=$inventory_stocks_data_info->stocks_total_cost+$grand_main_balance;



                    $stocks_supplier_debit_amount=$inventory_stocks_transactions->stocks_supplier_debit_amount-$grand_main_balance;
                    $stocks_supplier_credit_amount=$inventory_stocks_transactions->stocks_supplier_credit_amount+$grand_main_balance;

                    $stocks_supplier_balance_amount=$inventory_stocks_transactions->stocks_supplier_balance_amount+$grand_main_balance;
                }


                if($quantity >= ($inventory_stocks_transactions->transaction_stocks_quantity)){
                    $closing_transaction_stocks_quantity=$inventory_stocks_transactions->closing_transaction_stocks_quantity-$final_quantity;

                    $stocks_onhand=$inventory_stocks_data_info->stocks_onhand-$final_quantity;
                    $stocks_total_quantity=$inventory_stocks_data_info->stocks_total_quantity-$final_quantity;

                }else{

                    $closing_transaction_stocks_quantity=$inventory_stocks_transactions->closing_transaction_stocks_quantity+$final_quantity;

                    $stocks_onhand=$inventory_stocks_data_info->stocks_onhand+$final_quantity;
                    $stocks_total_quantity=$inventory_stocks_data_info->stocks_total_quantity+$final_quantity;


                }

                    $inventory_stocks_transactions_info=[

                        'transaction_stocks_quantity' =>$quantity,
                        'stocks_quantity_rate' =>$quantity_rate,
                        'closing_transaction_stocks_quantity' =>$closing_transaction_stocks_quantity,
                        'stocks_quantity_cost' =>$transaction_amount,
                        'closing_transaction_stocks_cost' =>$closing_transaction_stocks_cost,
                        'stocks_supplier_debit_amount' =>$stocks_supplier_debit_amount,
                        'stocks_supplier_balance_amount' =>$stocks_supplier_balance_amount,
                        'updated_by' => \Auth::user()->user_id,
                        'updated_at' =>$now,
                    ];

                    $supplier_info = [
                        'supplier_net_credit_amount' =>$supplier_net_credit_amount,
                        'supplier_net_balance_amount' =>$supplier_net_balance_amount,
                        'updated_by' => \Auth::user()->user_id,
                        'updated_at' =>$now,

                    ];

                    $supplier_credit_transactions_data = [
                        'closing_stocks_credit_amount' =>$closing_stocks_credit_amount,
                        'closing_stocks_balance_amount' =>$closing_stocks_balance_amount,
                        'transaction_amount' =>$transaction_amount,
                        'updated_by' => \Auth::user()->user_id,
                        'updated_at' =>$now,

                    ];

                    $inventory_stocks_data = [
                        'stocks_onhand' =>$stocks_onhand,
                        'stocks_total_quantity' =>$stocks_total_quantity,
                        'stocks_total_cost' =>$stocks_total_cost,
                        'updated_by' => \Auth::user()->user_id,
                        'updated_at' =>$now,

                    ];



                try{

                    \DB::table('ltech_suppliers')->where('supplier_id',$supplier_id)->update($supplier_info);
                    \DB::table('ltech_inventory_stocks_transactions')->where('referrence',$transactions_id)->update($inventory_stocks_transactions_info);
                    \DB::table('ltech_inventory_supplier_credit_transactions')->where('referrence',$transactions_id)->update($supplier_credit_transactions_data);
                    \DB::table('ltech_inventory_stocks')->where('inventory_stock_id',$inventory_stock_id)->update($inventory_stocks_data);


                    \App\System::EventLogWrite('update,ltech_suppliers',json_encode($supplier_info));
                    \App\System::EventLogWrite('update,ltech_inventory_stocks_transactions',json_encode($inventory_stocks_transactions_info));
                    \App\System::EventLogWrite('update,ltech_inventory_supplier_credit_transactions',json_encode($supplier_credit_transactions_data));

                }catch(\Exception $e){

                    \DB::rollback();
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                }


            }

        ############## others transactions data update ###############


            /***Start journal Loop*/

            for($i=1;$i<=\Request::input('journal_entry_field');$i++){

                $journal_id = (int)(\Request::input('journal_id_'.$i));
                $journal_particular_name = \Request::input('journal_particular_name_'.$i);
                $ledger_name_depth_id = explode('.', trim($journal_particular_name));

                $particular_narartion = \Request::input('journal_particular_naration_'.$i);
                $journal_particular_amount = \Request::input('journal_particular_amount_'.$i);
                $particular_amount_type = \Request::input('journal_particular_amount_type_'.$i);
                $transaction_total_amount = $transaction_total_amount+$journal_particular_amount;


                    try{

                        if(!empty(\Request::input('journal_id_'.$i))){

                            
                            $journal_data_info = [
                                        'journal_particular_id' =>$ledger_name_depth_id[0],
                                        'journal_particular_name' =>$ledger_name_depth_id[1],
                                        'journal_particular_depth'=>$ledger_name_depth_id[2],
                                        'journal_particular_naration' =>\Request::input('journal_particular_naration_'.$i),
                                        'journal_particular_amount_type'=>$particular_amount_type,
                                        'journal_particular_amount' =>$journal_particular_amount,
                                        'cost_center_id' =>$cost_center_id,
                                        // 'posting_type' =>$posting_type,
                                        'updated_by' => \Auth::user()->user_id,
                                        'updated_at' =>$now,

                                    ];

                            $journal_update_data = \DB::table('ltech_general_journal')->where('journal_id',$journal_id)->update($journal_data_info);
                            \App\System::EventLogWrite('insert,ltech_general_journal.journal_data_info',json_encode($journal_update_data));


                        }else{
                            $journal_data_info = [
                                        'journal_date' =>$transactions_date,
                                        'transaction_id' =>$transactions_id,
                                        'journal_particular_id' =>$ledger_name_depth_id[0],
                                        'journal_particular_name' =>$ledger_name_depth_id[1],
                                        'journal_particular_depth'=>$ledger_name_depth_id[2],
                                        'journal_particular_naration'=>\Request::input('journal_particular_naration_'.$i),
                                        'journal_particular_amount_type'=>$particular_amount_type,
                                        'journal_particular_amount' =>$journal_particular_amount,
                                        'cost_center_id' =>$cost_center_id,
                                        'posting_type' =>$posting_type,
                                        'updated_by' => \Auth::user()->user_id,
                                        'created_by' => \Auth::user()->user_id,
                                        'created_at' =>$now,
                                        'updated_at' =>$now,

                                    ];
                            $journal_update_data = \DB::table('ltech_general_journal')->insert($journal_data_info);
                            \App\System::EventLogWrite('insert,ltech_general_journal.journal_data_info',json_encode($journal_update_data));


                        }

  

                    }catch(\Exception $e){
                        \DB::rollback();
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/general/transaction-list')->with('errormessage',$message);
                    }

            }
            /***End Stock Loop*/


            $get_transaction_info = \DB::table('ltech_general_journal')->where('transaction_id',$transactions_id)->get();

            $all_debit=0;
            $all_credit=0;

            if(!empty($get_transaction_info) && count($get_transaction_info)>0){
                foreach ($get_transaction_info as $key => $value) {
                    if($value->journal_particular_amount_type == 'debit'){
                        $all_debit=$all_debit+$value->journal_particular_amount;
                    }elseif($value->journal_particular_amount_type == 'credit'){
                        $all_credit=$all_credit+$value->journal_particular_amount;
                    }
                }
            }else{
                \DB::rollback();
                return \Redirect::to('/general/transaction-list')->with('errormessage',"Something Wrong Happen");   
            }


            if(($transaction_amount == $all_debit) && ($transaction_amount == $all_credit)){

                \DB::commit();
                return \Redirect::to('/general/transaction-list')->with('message',"Successfully Updated");
            }else{
                \DB::rollback();
                return \Redirect::to('/general/transaction-list')->with('errormessage',"Journal amount is greater/less than  transaction amount.");
            }

        }else return \Redirect::to('/general/transaction-list')->with('errormessage','Amount is to be grater than zero.');

                        
        }else return \Redirect::to('/general/transaction-list')->withErrors($v->messages());
    }




    /********************************************
    ## GeneralAllTransactionListByUser
    *********************************************/
    public function GeneralAllTransactionListByUser(){

        $user=\Auth::user()->user_id;
        if(isset($_GET['search_from']) && isset($_GET['search_to']) || isset($_GET['cost_center']) || isset($_GET['post_type'])){

            $search_from = $_GET['search_from'];
            $search_to = $_GET['search_to'];

            $all_transaction = \DB::table('ltech_transactions')
                            ->where(function($query){

                               if(isset($_GET['cost_center']) && !empty($_GET['cost_center'])){
                                    $query->where(function ($q){
                                        $q->where('ltech_transactions.cost_center_id', $_GET['cost_center']);
                                      });
                                }
                                if(isset($_GET['post_type']) && !empty($_GET['post_type'])){
                                    $query->where(function ($q){
                                        $q->where('ltech_transactions.posting_type', $_GET['post_type']);
                                      });
                                }

                            })
                            ->where('ltech_transactions.created_by',$user)
                            ->leftjoin('ltech_cost_centers','ltech_transactions.cost_center_id','like','ltech_cost_centers.cost_center_id')
                            ->leftjoin('ltech_posting_types','ltech_transactions.posting_type','like','ltech_posting_types.posting_type_slug')
                            ->whereBetween('ltech_transactions.transactions_date', [$search_from,$search_to])
                            ->paginate(5);
            $all_transaction->setPath(url('/customer/registration'));
            $transaction_pagination = $all_transaction->appends(['search_from' => $_GET['search_from'], 'search_to'=> $_GET['search_to'],'post_type'=>$_GET['post_type'],'cost_center'=>$_GET['cost_center']])->render();
            $data['transaction_pagination'] = $transaction_pagination;

            $data['all_transaction'] = $all_transaction;

        }else{
            $now=date('Y-m-d');
            $search_from = $now;
            $search_to = $now;

            $all_transaction=\DB::table('ltech_transactions')
                    ->leftjoin('ltech_cost_centers','ltech_transactions.cost_center_id','=','ltech_cost_centers.cost_center_id')
                    ->leftjoin('ltech_posting_types','ltech_transactions.posting_type','=','ltech_posting_types.posting_type_slug')
                    ->where('ltech_transactions.created_by',$user)
                    ->whereBetween('ltech_transactions.transactions_date',[$search_from,$search_to])
                    ->paginate(10);
            $all_transaction->setPath(url('/customer/registration'));
            $transaction_pagination = $all_transaction->render();
            $data['transaction_pagination'] = $transaction_pagination;

            $data['all_transaction'] = $all_transaction;
        }

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.journal.transaction-list-by-user',$data);
    }

    /********************************************
    ## AjaxCostCenterPage
    *********************************************/
    public function AjaxCostCenterPage($cost_center_id){
        $transaction_by_cost=\DB::table('ltech_transactions')
                        ->where('ltech_transactions.cost_center_id',$cost_center_id)
                        ->leftjoin('ltech_cost_centers','ltech_transactions.cost_center_id','=','ltech_cost_centers.cost_center_id')
                        ->leftjoin('ltech_posting_types','ltech_transactions.posting_type','=','ltech_posting_types.posting_type_id')
                        ->OrderBy('ltech_transactions.updated_at','desc')
                        ->get();
                        //->paginate(10);
        // $transaction_by_cost->setPath(url('/general/transaction-list'));
        // $transaction_by_cost_pagination = $transaction_by_cost->render();
        // $data['transaction_by_cost_pagination']=$transaction_by_cost_pagination;
        $data['transaction_by_cost'] = $transaction_by_cost;
        $data['page_title'] = $this->page_title;
        return \View::make('pages.journal.ajax-transaction-list-by-cost',$data);

    }


    /********************************************
    ## AjaxPostingPage
    *********************************************/
    public function AjaxPostingPage($posting_type_id){
        $transaction_by_posting=\DB::table('ltech_transactions')
                        ->where('ltech_transactions.posting_type',$posting_type_id)
                        ->leftjoin('ltech_cost_centers','ltech_transactions.cost_center_id','=','ltech_cost_centers.cost_center_id')
                        ->leftjoin('ltech_posting_types','ltech_transactions.posting_type','=','ltech_posting_types.posting_type_id')
                        ->OrderBy('ltech_transactions.updated_at','desc')
                        ->get();
                        //->paginate(10);
        // $transaction_by_posting->setPath(url('/general/transaction-list'));
        // $transaction_by_posting_pagination = $transaction_by_posting->render();
        // $data['transaction_by_posting_pagination']=$transaction_by_posting_pagination;
        $data['transaction_by_posting'] = $transaction_by_posting;
        $data['page_title'] = $this->page_title;
        return \View::make('pages.journal.ajax-transaction-by-posting',$data);

    }

    /********************************************
    ## JuournalPostingPrint
    *********************************************/
    public function JuournalPostingPrint($transaction_id){

       $journalinfo = \DB::table('ltech_general_journal')->where('ltech_general_journal.transaction_id',$transaction_id)
                        ->leftjoin('ltech_posting_types','ltech_general_journal.posting_type','=','ltech_posting_types.posting_type_id')
                        ->leftjoin('ltech_cost_centers','ltech_general_journal.cost_center_id','=','ltech_cost_centers.cost_center_id')

                        ->get();

        if(count($journalinfo)>0){
            $data['journalinfo'] = $journalinfo;
            \Session::flash('download_url',url('/journal/posting/download/'.$transaction_id));
            return \View::make('pages.journal.pdf.voucher-pdf',$data);
            
            /*$pdf = \PDF::loadView('pages.journal.pdf.voucher-pdf',$data);
            $pdfname = time().'_'.$journalinfo[0]->posting_type.'pdf';
            return  $pdf->stream($pdfname);*/

            

        }else return \Redirect::to('/journal/transaction')->with('errormessage','Invalid Transaction!!.');
   
        
    }

    /********************************************
    ## JuournalPostingDownload
    *********************************************/
    public function JuournalPostingDownload($transaction_id){

       $journalinfo = \DB::table('ltech_general_journal')->where('ltech_general_journal.transaction_id',$transaction_id)
                        ->leftjoin('ltech_posting_types','ltech_general_journal.posting_type','=','ltech_posting_types.posting_type_id')
                        ->leftjoin('ltech_cost_centers','ltech_general_journal.cost_center_id','=','ltech_cost_centers.cost_center_id')

                        ->get();

        if(count($journalinfo)>0){
            $data['journalinfo'] = $journalinfo;

            //return \View::make('pages.journal.pdf.voucher-pdf',$data);
            $pdf = \PDF::loadView('pages.journal.pdf.voucher-pdf',$data);
            $pdfname = time().'_'.$journalinfo[0]->posting_type.'.pdf';
            return $pdf->download($pdfname);   

        }else return \Redirect::to('/journal/transaction')->with('errormessage','Invalid Transaction!!.');
   
        
    }


    /********************************************
    ## AllJournalList
    *********************************************/
    public function AllJournalList(){

        $journal_data_node= \App\Journal::GetJournalEntryList();

        $data['journal_data_node']=$journal_data_node;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.journal.all-journal-list',$data);

    }

    /********************************************
    ## LedgerOpeningBalance
    *********************************************/
    public function LedgerOpeningBalance(){

        $journal_data_node= \App\Journal::GetJournalEntryList();

        $data['journal_data_node']=$journal_data_node;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.journal.ledger-opening-balance',$data);

    }


    /********************************************
    ## AjaxLedgerOpeningBalance
    *********************************************/
    public function AjaxLedgerOpeningBalance($ledger_id,$depth){

        $select_ledger_info=\DB::table('ltech_ledger_group_'.$depth)->where('ledger_id',$ledger_id)->first();
        $data['select_ledger_info']=$select_ledger_info;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.journal.ajax-ledger-opening-balance',$data);
    }

    /********************************************
    ## LedgerOpeningBalanceConfirm
    *********************************************/
    public function LedgerOpeningBalanceConfirm(){

        $rules_array['ledger_id'] = 'Required';
        $rules_array['depth'] = 'Required';
        $rules_array['ledger_credit'] = 'Required|numeric';
        $rules_array['ledger_debit'] =  'Required|numeric';

        $v= \Validator::make(\Request::all(), $rules_array);

        if($v->passes()){
            $now=date('Y-m-d');
            $ledger_id= \Request::input('ledger_id');
            $depth= \Request::input('depth');

            $ledger_update_data = [
                    'ledger_debit' => \Request::input('ledger_debit'),
                    'ledger_credit' => \Request::input('ledger_credit'),
                    'updated_by' =>\Auth::user()->user_id,
                    'updated_at' =>$now,
                ];

            try{
                $customer_order_insert = \DB::table('ltech_ledger_group_'.$depth)->where('ledger_id',$ledger_id)->update($ledger_update_data);
                \App\System::EventLogWrite('update,ltech_ledger_group_'.$depth,json_encode($ledger_update_data));
                return \Redirect::to('/ledger/opening/balance')->with('message','Ledger Update successfully');
            }catch(\Exception $e){

                    \DB::rollback();
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                    return \Redirect::to($back_page)->with('errormessage','Something wrong happend Invoice Puchase');
            }
        }else return \Redirect::to('/ledger/opening/balance')->withErrors($v->messages());


    }


    /********************************************
    ## JournalListDetails
    *********************************************/
    public function JournalListDetails($ledger_id){
        $all_debit_amount=0;
        $all_credit_amount=0;
        $now=date('Y-m-d');

        if(isset($_GET['search_from'])  ||  isset($_GET['search_to'])  ||  isset($_GET['cost_center'])){

            $search_from = $_GET['search_from'].' 00:00:00';
            $search_to = $_GET['search_to'].' 23:59:59';
            if(isset($_GET['cost_center'])){
                $cost_center =$_GET['cost_center'];
            }else $cost_center=0;


            $data['search_from'] = $search_from;
            $data['search_to'] = $search_to;
            $data['cost_center'] = $cost_center;

            if($cost_center != 0){

                $journal_details_info=\DB::table('ltech_general_journal')
                                ->where('journal_particular_id',$ledger_id)
                                ->where('cost_center_id',$cost_center)
                                ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
                                ->paginate(10);

            }else{
                $journal_details_info=\DB::table('ltech_general_journal')
                                ->where('journal_particular_id',$ledger_id)
                                ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
                                ->paginate(10);
                  
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

                $journal_details_info->setPath(url('/journal/debit-cerdit/details/id-'.$ledger_id));
                $journal_detail_pagination = $journal_details_info->appends(['search_from' => $search_from, 'search_to'=> $search_to])->render();

                $all_journal_details_info=\DB::table('ltech_general_journal')
                                    ->where('journal_particular_id',$ledger_id)
                                    ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
                                    ->get();

          
        }else{

            $search_from = $now;
            $search_to = $now;

            $journal_details_info=\DB::table('ltech_general_journal')
                                ->where('journal_particular_id',$ledger_id)
                                ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
                                ->paginate(10);

            $journal_details_info->setPath(url('/journal/debit-cerdit/details/id-'.$ledger_id));
            $journal_detail_pagination = $journal_details_info->appends(['search_from' => $search_from, 'search_to'=> $search_to])->render();

            $all_journal_details_info=\DB::table('ltech_general_journal')
                                ->where('journal_particular_id',$ledger_id)
                                ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
                                ->get();


            
        }

            if(!empty($all_journal_details_info)){
                foreach ($all_journal_details_info as $key => $list) {

                    if($list->journal_particular_amount_type == 'debit'){
                        $all_debit_amount=$all_debit_amount+$list->journal_particular_amount;
                    }
                    
                    if($list->journal_particular_amount_type == 'credit'){
                        $all_credit_amount=$all_credit_amount+$list->journal_particular_amount;
                    }
                    
                }
            }

        $data['all_debit_amount'] = $all_debit_amount;
        $data['all_credit_amount'] = $all_credit_amount;
        $data['journal_detail_pagination'] = $journal_detail_pagination;
        $data['journal_details_info'] = $journal_details_info;
        $data['ledger_id'] = $ledger_id;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.journal.journal-list-details',$data);

    }


    /********************************************
    ## JournalDetailsPDF
    *********************************************/
    public function JournalDetailsPDF($ledger_id){

        $all_debit_amount=0;
        $all_credit_amount=0;

        $journal_details_info=\DB::table('ltech_general_journal')
                            ->where('journal_particular_id',$ledger_id)
                            ->get();

        if(!empty($journal_details_info)){
            foreach ($journal_details_info as $key => $list) {

                if($list->journal_particular_amount_type == 'debit'){
                    $all_debit_amount=$all_debit_amount+$list->journal_particular_amount;
                }
                
                if($list->journal_particular_amount_type == 'credit'){
                    $all_credit_amount=$all_credit_amount+$list->journal_particular_amount;
                }
                
            }
        }

        $data['all_debit_amount'] = $all_debit_amount;
        $data['all_credit_amount'] = $all_credit_amount;
        $data['journal_details_info'] = $journal_details_info;
        $data['ledger_id'] = $ledger_id;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        // return \View::make('pages.journal.pdf.journal-details-pdf',$data);
        $pdf = \PDF::loadView('pages.journal.pdf.journal-details-pdf',$data);
        return  $pdf->download();

    }


    /********************************************
    ## JournalDetailsPDFPrint
    *********************************************/
    public function JournalDetailsPDFPrint($ledger_id){

        $all_debit_amount=0;
        $all_credit_amount=0;

        $journal_details_info=\DB::table('ltech_general_journal')
                            ->where('journal_particular_id',$ledger_id)
                            ->get();

        if(!empty($journal_details_info)){
            foreach ($journal_details_info as $key => $list) {

                if($list->journal_particular_amount_type == 'debit'){
                    $all_debit_amount=$all_debit_amount+$list->journal_particular_amount;
                }
                
                if($list->journal_particular_amount_type == 'credit'){
                    $all_credit_amount=$all_credit_amount+$list->journal_particular_amount;
                }
                
            }
        }

        $data['all_debit_amount'] = $all_debit_amount;
        $data['all_credit_amount'] = $all_credit_amount;
        $data['journal_details_info'] = $journal_details_info;
        $data['ledger_id'] = $ledger_id;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        return \View::make('pages.journal.pdf.journal-details-pdf-print',$data);

    }

    /*********************End of Journal Controller***************************/
}
