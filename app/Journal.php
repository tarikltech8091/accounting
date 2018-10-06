<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*******************************
#
## Journal Model
#
*******************************/

class Journal extends Model
{
    /********************************************
    ## GetJournalData 
    *********************************************/

    public static function GetJournalData($depth){


    	$journal_data = \DB::table('ltech_ledger_group_'.$depth)->get();

    	return $journal_data;
    }


    /********************************************
    ## JournalEntryCheck
    *********************************************/

    public static function JournalEntryCheck($ledger_name_slug,$depth){

    	$journal_data = \DB::table('ltech_ledger_group_'.$depth)->where('ledger_name_slug',$ledger_name_slug)->first();

    	return $journal_data;
    }

    /********************************************
    ## JournalEntryInsert
    *********************************************/

    public static function JournalEntryInsert($ledger_name,$ledger_name_slug,$depth,$parent_ledger,$ledger_debit,$ledger_credit){

        $now = date('Y-m-d H:i:s');
    	$journal_data = \DB::table('ltech_ledger_group_'.$depth)
    					->insertGetId([
    							'ledger_name' =>$ledger_name,
    							'ledger_name_slug' =>$ledger_name_slug,
    							'ledger_group_parent_id' =>$parent_ledger,
    							'ledger_group_have_child' =>0,
    							'depth' =>$depth,
                                'ledger_debit'=>$ledger_debit,
                                'ledger_credit'=>$ledger_credit,
    							'created_by' => \Auth::user()->user_id,
    							'updated_by' => \Auth::user()->user_id,
                                'created_at' =>$now,
                                'updated_at' =>$now
    						]);

    	return $journal_data;
    }

    /********************************************
    ## JournalUpdateParent
    *********************************************/

    public static function JournalUpdateParent($depth,$parent_ledger){


        $journal_update = \DB::table('ltech_ledger_group_'.$depth)->where('ledger_id',$parent_ledger)
                        ->update([
                                'ledger_group_have_child' =>1,
                                'updated_by' => \Auth::user()->user_id,
                                'updated_at' =>date('Y-m-d H:i:s')
                            ]);

        return $journal_update;
    }


    /********************************************
    ## GetJournalChildData
    *********************************************/

    public static function GetJournalChildData($ledger_id,$depth){

        $journal_data = \DB::table('ltech_ledger_group_'.$depth)->where('ledger_group_parent_id',$ledger_id)->get();

        return $journal_data;
    }


    /********************************************
    ## GetLedgerChildByName
    *********************************************/

    public static function GetLedgerChildByName($ledger_name,$depth){

        $ledger_data = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_name','like',$ledger_name)
                        ->leftjoin('ltech_ledger_group_'.($depth+1),'ltech_ledger_group_'.$depth.'.ledger_id','=','ltech_ledger_group_'.($depth+1).'.ledger_group_parent_id') 
                        ->get();

        return $ledger_data;
    }

    /********************************************
    ## JournalEntryinfo
    *********************************************/

    public static function JournalEntryinfo($ledger_id,$depth){

        $journal_data = \DB::table('ltech_ledger_group_'.$depth)->where('ledger_id',$ledger_id)->where('depth',$depth)->first();

        return $journal_data;
    }


    /********************************************
    ## GetJournalEntryList
    *********************************************/

    public static function GetJournalEntryList(){


        $journal_list = \DB::table('ltech_ledger_group_1')
                                ->where('ledger_group_have_child',0);
        for($i = 1; $i <=7; $i++){
            $journal_data_sub = \DB::table('ltech_ledger_group_'.$i)
                                ->where('ledger_group_have_child',0);
            $journal_list = $journal_list->union($journal_data_sub);
        }

        $all_data = $journal_list->get();
                       

        return $all_data;
    }


    /********************************************
    ## GetLedgerAllChild
    *********************************************/

    public static function GetLedgerAllChild($ledger_name,$depth){


        $get_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_name','LIKE',$ledger_name);
        $demo_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_name','LIKE',$ledger_name);
            
            for($i=($depth+1); $i<=7; $i++){

                $demo_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');

                if($demo_journal->count() !=0){
             
                    $get_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');
   
                
                }else break;
     
            }


        $all_data= $get_journal->get();
      
        return $all_data;
    }


    /********************************************
    ## TransactionInsert
    *********************************************/

    public static function TransactionInsert($transaction_amount,$transaction_details,$cost_center,$posting_type){

        \DB::beginTransaction();

        try{
                $posting_info = \DB::table('ltech_posting_types')->where('posting_type_slug',$posting_type)->first();

                if(count($posting_info) <= 0){
                    \Session::flash('errormessage','Posting Type Information Missing.');
                    return -1;
                }

                $transaction_info = [
                                'transactions_date' =>date('Y-m-d'),
                                'transactions_naration' =>$transaction_details,
                                'transaction_amount' =>$transaction_amount,
                                'cost_center_id' =>$cost_center,
                                'posting_type' =>$posting_info->posting_type_id,
                                'created_by' => \Auth::user()->user_id,
                                'updated_by' => \Auth::user()->user_id,
                            ];


                $journal_data = \DB::table('ltech_transactions')->insert($transaction_info);
                $lastInsertedRow = \DB::table('ltech_transactions')->latest()->first();

                \App\System::EventLogWrite('insert,ltech_transactions',json_encode($transaction_info));
                \DB::commit();
                return $lastInsertedRow;

            }catch(\Exception $e){
                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                \Session::flash('errormessage','Somthing wrong...!!');
                return -1;
            }
        
    }


    /********************************************
    ## GeneralTransactionInsert
    *********************************************/

    public static function GeneralTransactionInsert($transaction_amount,$transaction_details,$posting_type){
        \DB::beginTransaction();
        try{
                
                $transaction_info = [
                                'transactions_date' =>date('Y-m-d'),
                                'transactions_naration' =>$transaction_details,
                                'transaction_amount' =>$transaction_amount,
                                'posting_type' =>$posting_type,
                                'created_by' => \Auth::user()->user_id,
                                'updated_by' => \Auth::user()->user_id,
                            ];


                $journal_data = \DB::table('ltech_transactions')->insert($transaction_info);
                $lastInsertedRow = \DB::table('ltech_transactions')->latest()->first();

                \App\System::EventLogWrite('insert,ltech_transactions',json_encode($transaction_info));

                \DB::commit();
                return $lastInsertedRow;

            }catch(\Exception $e){
                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                \Session::flash('errormessage','Somthing wrong...!!');
                return -1;
            }
        
    }


    /********************************************
    ## GeneralTransactionInsertByDate
    *********************************************/

    public static function GeneralTransactionInsertByDate($transactions_date,$cost_center,$transaction_amount,$transaction_details,$posting_type){
        \DB::beginTransaction();
        try{
                
                $transaction_info = [
                                'transactions_date' =>$transactions_date,
                                'cost_center_id' =>$cost_center,
                                'transactions_naration' =>$transaction_details,
                                'transaction_amount' =>$transaction_amount,
                                'posting_type' =>$posting_type,
                                'created_by' => \Auth::user()->user_id,
                                'updated_by' => \Auth::user()->user_id,
                            ];


                // $journal_data = \DB::table('ltech_transactions')->insert($transaction_info);
                $lastInsertedRow = \DB::table('ltech_transactions')->insertGetId($transaction_info);
                // $lastInsertedRow = \DB::table('ltech_transactions')->latest()->first();

                \App\System::EventLogWrite('insert,ltech_transactions',json_encode($transaction_info));

                \DB::commit();
                return $lastInsertedRow;

            }catch(\Exception $e){
                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                \Session::flash('errormessage','Somthing wrong...!!');
                return -1;
            }
        
    }


    /********************************************
    ## TransactionInsert
    *********************************************/

    public static function JournalTransactionInsert($Request,$cost_center,$transaction_id){


        $debit_account_info = \App\Journal::JournalEntryinfo($Request['debit_ledger'],$Request['debit_ledger_depth']);
        $credit_account_info = \App\Journal::JournalEntryinfo($Request['credit_ledger'],$Request['credit_ledger_depth']);

        if(empty($debit_account_info) || empty($credit_account_info)){
            \Session::flash('errormessage','Somthing wrong in Account Information');
            return -2;
        }
        \DB::beginTransaction();
        try{

                 $posting_info = \DB::table('ltech_posting_types')->where('posting_type_slug',$Request['posting_type'])->first();

                if(count($posting_info) <= 0){
                     \Session::flash('errormessage','Posting Type Information Missing.');
                    return -1;
                }

                $journal_debit_info = [
                                'journal_date' =>date('Y-m-d'),
                                'journal_particular_id' =>$debit_account_info->ledger_id,
                                'journal_particular_name' =>$debit_account_info->ledger_name,
                                'journal_particular_depth'=>$Request['debit_ledger_depth'],
                                'journal_particular_naration' =>$Request['debit_naration'],
                                'journal_particular_amount_type'=>'debit',
                                'journal_particular_amount' =>$Request['transaction_amount'],
                                'cost_center_id' =>$cost_center,
                                'posting_type' =>$Request['posting_type'],
                                'transaction_id' =>$transaction_id,
                                'created_by' => \Auth::user()->user_id,
                                'updated_by' => \Auth::user()->user_id,
                            ];

                $journal_credit_info = [ 
                                'journal_date' =>date('Y-m-d'),
                                'journal_particular_id' =>$credit_account_info->ledger_id,
                                'journal_particular_name' =>$credit_account_info->ledger_name,
                                'journal_particular_depth'=>$Request['credit_ledger_depth'],
                                'journal_particular_naration' =>$Request['credit_naration'],
                                'journal_particular_amount_type'=>'credit',
                                'journal_particular_amount' =>$Request['transaction_amount'],
                                'cost_center_id' =>$cost_center,
                                'posting_type' =>$Request['posting_type'],
                                'transaction_id' =>$transaction_id,
                                'created_by' => \Auth::user()->user_id,
                                'updated_by' => \Auth::user()->user_id,
                            ];


                $journal_debit_data = \DB::table('ltech_general_journal')->insert($journal_debit_info);
                $journal_credit_data = \DB::table('ltech_general_journal')->insert($journal_credit_info);

                

                \App\System::EventLogWrite('insert,ltech_general_journal.journal_debit_data',json_encode($journal_debit_info));
                \App\System::EventLogWrite('insert,ltech_general_journal.journal_credit_data',json_encode($journal_credit_info));

                \Session::flash('message','Posting Succesfully completed.');

                \DB::commit();
                return $journal_debit_data;

            }catch(\Exception $e){
                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                \Session::flash('errormessage','Somthing wrong...!!');
                return -1;
            }
        
    }

    /********************************************
    ## ArrayGroupingByKey
    *********************************************/

    public static function ArrayGroupingByKey($all_data,$key_name){

        $result = array();
        foreach ($all_data as $data) {
          $id = $data->$key_name;
          if (isset($result[$id])) {
             $result[$id][] = $data;
          } else {
             $result[$id] = array($data);
          }
        }
        return $result;
    }


    /********************************************
    ## MultiArrayStringSerach
    *********************************************/

    public static function MultiArrayStringSerach($search,$search_key,$array){

       foreach ($array as $key => $value) {
        if(strpos(strtolower($value[$search_key]),$search) !== false)
           return 1;
       }
       return 0;
    }

    /********************************************
    ## JournalDebitPosting
    *********************************************/

    public static function JournalDebitPosting($debit_info,$transaction_id){
        $now=date('Y-m-d');
        \DB::beginTransaction();
        try{

                $journal_debit_info = [
                                'journal_date' =>date('Y-m-d'),
                                'journal_particular_id' =>$debit_info['debit_id'],
                                'journal_particular_name' =>$debit_info['debit_name'],
                                'journal_particular_depth'=>$debit_info['debit_depth'],
                                'journal_particular_naration' =>$debit_info['debit_naration'],
                                'journal_particular_amount_type'=>'debit',
                                'journal_particular_amount' =>$debit_info['debit_transaction_amount'],
                                'cost_center_id' =>empty($debit_info['debit_costcenter']) ? 0:$debit_info['debit_costcenter'],
                                'posting_type' =>$debit_info['posting_type'],
                                'transaction_id' =>$transaction_id,
                                'created_by' => \Auth::user()->user_id,
                                'updated_by' => \Auth::user()->user_id,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                            ];


                $journal_debit_data = \DB::table('ltech_general_journal')->insert($journal_debit_info);
               

                \App\System::EventLogWrite('insert,ltech_general_journal.journal_debit_data',json_encode($journal_debit_info));
                \DB::commit();
                return $journal_debit_data;

            }catch(\Exception $e){
                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                \Session::flash('errormessage','Somthing wrong...!!');
                return -1;
            }
        
    }


    /********************************************
    ## JournalCreditPosting
    *********************************************/

    public static function JournalCreditPosting($credit_info,$transaction_id){
        $now=date('Y-m-d');
        \DB::beginTransaction();
        try{

                $journal_credit_info = [
                                'journal_date' =>date('Y-m-d'),
                                'journal_particular_id' =>$credit_info['credit_id'],
                                'journal_particular_name' =>$credit_info['credit_name'],
                                'journal_particular_depth'=>$credit_info['credit_depth'],
                                'journal_particular_naration' =>$credit_info['credit_naration'],
                                'journal_particular_amount_type'=>'credit',
                                'journal_particular_amount' =>$credit_info['credit_transaction_amount'],
                                'cost_center_id' =>empty($credit_info['credit_costcenter']) ? 0:$credit_info['credit_costcenter'],
                                'posting_type' =>$credit_info['posting_type'],
                                'transaction_id' =>$transaction_id,
                                'created_by' => \Auth::user()->user_id,
                                'updated_by' => \Auth::user()->user_id,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                            ];


                $journal_credit_data = \DB::table('ltech_general_journal')->insert($journal_credit_info);
               

                \App\System::EventLogWrite('insert,ltech_general_journal.journal_credit_data',json_encode($journal_credit_info));
                \DB::commit();
                return $journal_credit_data;

            }catch(\Exception $e){
                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                \Session::flash('errormessage','Somthing wrong...!!');
                return -1;
            }
        
    }

    /********************************************
    ## TransactionMeta
    *********************************************/

    public static function TransactionMeta($transaction_id,$field_name, $field_value){

        $meta_data = \DB::table('ltech_transaction_meta')->insert(
                                [
                                    'transaction_id' => $transaction_id,
                                    'field_name'=> $field_name,
                                    'field_value'=>$field_value,
                                    'created_by' => \Auth::user()->user_id,
                                    'updated_by' => \Auth::user()->user_id,
                                ]
                            );

        return $meta_data;
    }






    /*********************End of Journal***************************/
}
