<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*******************************
#
## Report Model
#
*******************************/

class Report extends Model
{




############################
## AllLedgerGet 
############################
    public static function AllLedgerGet($ledger_name, $depth,$search_from,$search_to,$cost_center_id){

      $get_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_name','LIKE',$ledger_name)->leftjoin('ltech_ledger_group_'.($depth+1),'ltech_ledger_group_'.$depth.'.ledger_id', '=', 'ltech_ledger_group_'.($depth+1).'.ledger_group_parent_id')->get();

      if(!empty($get_journal)){

      foreach ($get_journal as $key => $value) {

            if($value->ledger_group_have_child == '0'){
                    $total_data[]=[
                    'ledger_id'=>$value->ledger_id,
                    'ladger_name'=>$value->ledger_name,
                    'depth'=>$value->depth,
                    ];
            }
            if($value->ledger_group_have_child == '1'){
                    $total_ladger_2_parent_data[]=[
                    'ledger_id'=>$value->ledger_id,
                    'ladger_name'=>$value->ledger_name,
                    'depth'=>$value->depth,

                    ];
            }


      }
      }


      $depth=$depth+2;

      if(!empty($total_ladger_2_parent_data)){

      foreach ($total_ladger_2_parent_data as $key => $list) {


            $get_journal2 = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_group_parent_id','LIKE',$list['ledger_id'])->get();

            foreach ($get_journal2 as $key => $value) {

                  if($value->ledger_group_have_child == '0'){
                        $total_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,

                          ];
                  }
                  if($value->ledger_group_have_child == '1'){
                          $total_ladger_3_parent_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,

                          ];
                  }
            }
              
      }
      }



      $depth++;

      if(!empty($total_ladger_3_parent_data)){

      foreach ($total_ladger_3_parent_data as $key => $list) {


            $get_journal2 = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_group_parent_id','LIKE',$list['ledger_id'])->get();

            foreach ($get_journal2 as $key => $value) {

                  if($value->ledger_group_have_child == '0'){
                        $total_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
                  if($value->ledger_group_have_child == '1'){
                          $total_ladger_4_parent_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
            }
              
      }
      }

      $depth++;
      if(!empty($total_ladger_4_parent_data)){
      foreach ($total_ladger_4_parent_data as $key => $list) {

            $get_journal2 = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_group_parent_id','LIKE',$list['ledger_id'])->get();

            foreach ($get_journal2 as $key => $value) {

                  if($value->ledger_group_have_child == '0'){
                        $total_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
                  if($value->ledger_group_have_child == '1'){
                          $total_ladger_5_parent_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
            }      
      }
      }

      $depth++;


      if(!empty($total_ladger_5_parent_data)){

      foreach ($total_ladger_5_parent_data as $key => $list) {


            $get_journal2 = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_group_parent_id','LIKE',$list['ledger_id'])->get();

            foreach ($get_journal2 as $key => $value) {

                  if($value->ledger_group_have_child == '0'){
                        $total_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
                  if($value->ledger_group_have_child == '1'){
                          $total_ladger_6_parent_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
            }

              
      }
      }


      $depth++;
      if(!empty($total_ladger_6_parent_data)){

      foreach ($total_ladger_6_parent_data as $key => $list) {


            $get_journal2 = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_group_parent_id','LIKE',$list['ledger_id'])->get();

            foreach ($get_journal2 as $key => $value) {

                  if($value->ledger_group_have_child == '0'){
                        $total_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
                  if($value->ledger_group_have_child == '1'){
                          $total_ladger_7_parent_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
            }     
      }
      }



      $get_result = array();

      if(!empty($total_data)){
            foreach ($total_data as $key => $value) {

              if($cost_center_id ==0){

                  $all_data=  \DB::table('ltech_general_journal')
                          ->where('ltech_general_journal.journal_particular_id', $value['ledger_id'])
                          ->Join('ltech_ledger_group_'.$value['depth'],'ltech_general_journal.journal_particular_id','=','ltech_ledger_group_'.$value['depth'].'.ledger_id')
                          ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
                          ->get();
              }else{

                   $all_data=  \DB::table('ltech_general_journal')
                          ->where('ltech_general_journal.journal_particular_id', $value['ledger_id'])
                          ->Join('ltech_ledger_group_'.$value['depth'],'ltech_general_journal.journal_particular_id','=','ltech_ledger_group_'.$value['depth'].'.ledger_id')
                          ->where('ltech_general_journal.cost_center_id', $cost_center_id)
                          ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
                          ->get();               
              }


                  $journal_particular_data[$value['ledger_id']]=$all_data;


            }


          foreach ($journal_particular_data as $key => $list) {

                if(count($list)>0){
                      

                      $result =[
                                  'particular_name' =>$list[0]->journal_particular_name,
                                  'paritcular_total' => \App\Report::GetLedgerTotal($list)
                            ];

                  $get_result []= $result;          
                }      
          }

    }

      return $get_result;

}




############################
## AllLedgerGetWithOpening
############################
    public static function AllLedgerGetWithOpening($ledger_name, $depth,$search_from,$search_to,$cost_center_id){
      $first_depth=$depth;

      $get_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_name','LIKE',$ledger_name)->leftjoin('ltech_ledger_group_'.($depth+1),'ltech_ledger_group_'.$depth.'.ledger_id', '=', 'ltech_ledger_group_'.($depth+1).'.ledger_group_parent_id')->get();

      if(!empty($get_journal)){

      foreach ($get_journal as $key => $value) {

            if($value->ledger_group_have_child == '0'){
                    $total_data[]=[
                    'ledger_id'=>$value->ledger_id,
                    'ladger_name'=>$value->ledger_name,
                    'depth'=>$value->depth,
                    ];
            }
            if($value->ledger_group_have_child == '1'){
                    $total_ladger_2_parent_data[]=[
                    'ledger_id'=>$value->ledger_id,
                    'ladger_name'=>$value->ledger_name,
                    'depth'=>$value->depth,

                    ];
            }


      }
      }


      $depth=$depth+2;

      if(!empty($total_ladger_2_parent_data)){

      foreach ($total_ladger_2_parent_data as $key => $list) {


            $get_journal2 = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_group_parent_id','LIKE',$list['ledger_id'])->get();

            foreach ($get_journal2 as $key => $value) {

                  if($value->ledger_group_have_child == '0'){
                        $total_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,

                          ];
                  }
                  if($value->ledger_group_have_child == '1'){
                          $total_ladger_3_parent_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,

                          ];
                  }
            }
              
      }
      }



      $depth++;

      if(!empty($total_ladger_3_parent_data)){

      foreach ($total_ladger_3_parent_data as $key => $list) {


            $get_journal2 = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_group_parent_id','LIKE',$list['ledger_id'])->get();

            foreach ($get_journal2 as $key => $value) {

                  if($value->ledger_group_have_child == '0'){
                        $total_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
                  if($value->ledger_group_have_child == '1'){
                          $total_ladger_4_parent_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
            }
              
      }
      }

      $depth++;
      if(!empty($total_ladger_4_parent_data)){
      foreach ($total_ladger_4_parent_data as $key => $list) {

            $get_journal2 = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_group_parent_id','LIKE',$list['ledger_id'])->get();

            foreach ($get_journal2 as $key => $value) {

                  if($value->ledger_group_have_child == '0'){
                        $total_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
                  if($value->ledger_group_have_child == '1'){
                          $total_ladger_5_parent_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
            }      
      }
      }

      $depth++;


      if(!empty($total_ladger_5_parent_data)){

      foreach ($total_ladger_5_parent_data as $key => $list) {


            $get_journal2 = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_group_parent_id','LIKE',$list['ledger_id'])->get();

            foreach ($get_journal2 as $key => $value) {

                  if($value->ledger_group_have_child == '0'){
                        $total_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
                  if($value->ledger_group_have_child == '1'){
                          $total_ladger_6_parent_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
            }

              
      }
      }


      $depth++;
      if(!empty($total_ladger_6_parent_data)){

      foreach ($total_ladger_6_parent_data as $key => $list) {


            $get_journal2 = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_group_parent_id','LIKE',$list['ledger_id'])->get();

            foreach ($get_journal2 as $key => $value) {

                  if($value->ledger_group_have_child == '0'){
                        $total_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
                  if($value->ledger_group_have_child == '1'){
                          $total_ladger_7_parent_data[]=[
                          'ledger_id'=>$value->ledger_id,
                          'ladger_name'=>$value->ledger_name,
                          'depth'=>$value->depth,
                          ];
                  }
            }     
      }
      }



      $get_result = array();

      if(!empty($total_data)){
            foreach ($total_data as $key => $value) {

              if($cost_center_id ==0){

                  $all_data=  \DB::table('ltech_general_journal')
                          ->where('ltech_general_journal.journal_particular_id', $value['ledger_id'])
                          ->Join('ltech_ledger_group_'.$value['depth'],'ltech_general_journal.journal_particular_id','=','ltech_ledger_group_'.$value['depth'].'.ledger_id')
                          ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
                          ->get();
              }else{

                   $all_data=  \DB::table('ltech_general_journal')
                          ->where('ltech_general_journal.journal_particular_id', $value['ledger_id'])
                          ->Join('ltech_ledger_group_'.$value['depth'],'ltech_general_journal.journal_particular_id','=','ltech_ledger_group_'.$value['depth'].'.ledger_id')
                          ->where('ltech_general_journal.cost_center_id', $cost_center_id)
                          ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
                          ->get();               
              }


                  $journal_particular_data[$value['ledger_id']]=$all_data;


            }


      $total =0;
      $total_credit=0;
      $total_debit=0;
      $temp = array();
      $total_opening_debit=0;
      $total_opening_credit=0;

                
          foreach ($journal_particular_data as $key => $list) {

                if(count($list)>0){

                      if(!in_array($list[0]->journal_particular_name, $temp)){

                        $temp[]=$list[0]->journal_particular_name;
                      }
                      

                      $result =[
                                  'particular_name' =>$list[0]->journal_particular_name,
                                  'paritcular_total' => \App\Report::GetLedgerWithOpeningTotal($list[0]->journal_particular_name, $list[0]->journal_particular_depth, $list)
                            ];

                  $get_result []= $result;          
                }      
          }

          $all_ledger_info = \App\Journal::GetLedgerAllChild($ledger_name,$first_depth);
          foreach ($all_ledger_info as $key => $list) {
                if(!in_array($list->ledger_name, $temp)){

                      $temp[]=$list->ledger_name;
                      $particular_name=$list->ledger_name;
                      $total_opening_debit =$list->ledger_debit;
                      $total_opening_credit =$list->ledger_credit;
                      $total=$total_opening_debit-$total_opening_credit;
                      $result =[
                                  'particular_name' =>$particular_name,
                                  'paritcular_total' =>$total,
                            ];
                      $get_result []= $result;          

                }

          }  


    }

      return $get_result;

}




    /********************************************
    ## GetBalanceSheetLedger 
    *********************************************/

    public static function GetBalanceSheetLedger($ledger_head){


  		$get_journal = \DB::table('ltech_ledger_group_2')->where('ltech_ledger_group_2.ledger_name','LIKE',$ledger_head)
      ->join('ltech_ledger_group_3','ltech_ledger_group_2.ledger_id', '=', 'ltech_ledger_group_3.ledger_group_parent_id');
		//$demo_journal = \DB::table('ltech_ledger_group_2')->where('ltech_ledger_group_2.ledger_name','LIKE',$ledger_head);

		 for($i=4; $i<=5; $i++){

        $subquery = $get_journal->join('ltech_ledger_group_'.$i,'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');

        $get_journal = $get_journal->union($subquery);       
		        
		     

		 
		  }


		/* $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
		  ->whereBetween('ltech_general_journal.journal_date',['2016-12-08','2016-12-12'])
		  ->get();*/

		  //return $get_journal->get();
    }


    /********************************************
    ## GetBalanceSheetLedgerByDateWithCostCenter 
    *********************************************/

    public static function GetBalanceSheetLedgerByDateWithCostCenter($ledger_head,$depth,$form,$to,$cost_center){


        $get_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_name','LIKE',$ledger_head);
        $demo_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_name','LIKE',$ledger_head);

         for($i=($depth+1); $i<=7; $i++){

              $demo_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');

              if($demo_journal->count() !=0){
             
                $get_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');
                
              }else break;

         
          }

          if($cost_center==0){
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
              ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
              ->get();
          }else{
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
              ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
              ->where('ltech_general_journal.cost_center_id',$cost_center)
              ->get();
          }
         

          return $all_data;
    }


    /********************************************
    ## GetLedgerTotal
    *********************************************/

    public static function GetLedgerTotal($ledger_data){

    	$total =0;
      $total_credit=0;
      $total_debit=0;
      $temp = array();
      $total_opening_debit=0;
      $total_opening_credit=0;

      

      if(count($ledger_data)>0){
    		foreach ($ledger_data as $key => $data) {          

          if(!in_array($data->ledger_name, $temp)){

            $temp[]=$data->ledger_name;
            $total_opening_debit =$data->ledger_debit;
            $total_opening_credit =$data->ledger_credit;
          }

                
            if($data->journal_particular_amount_type=='credit')
			        $total_credit =$total_credit+$data->journal_particular_amount;

            if($data->journal_particular_amount_type=='debit')
              $total_debit =$total_debit+$data->journal_particular_amount;

    		}

          $total=$total_debit+(isset($total_opening_debit)?$total_opening_debit:0)-$total_credit-(isset($total_opening_credit)?$total_opening_credit:0);

    	}


    	return $total;
    }



    /********************************************
    ## GetLedgerWithOpeningTotal
    *********************************************/

    public static function GetLedgerWithOpeningTotal($ledger_head,$depth,$ledger_data){

      $total =0;
      $total_credit=0;
      $total_debit=0;
      $temp = array();
      $total_opening_debit=0;
      $total_opening_credit=0;
      $total_opening_debit2=0;
      $total_opening_credit2=0;

      if(count($ledger_data)>0){
            foreach ($ledger_data as $key => $data) {          

                if(!in_array($data->ledger_name, $temp)){

                  $temp[]=$data->ledger_name;
                  $total_opening_debit =$data->ledger_debit;
                  $total_opening_credit =$data->ledger_credit;
                }

                
                  if($data->journal_particular_amount_type=='credit')
                                $total_credit =$total_credit+$data->journal_particular_amount;

                  if($data->journal_particular_amount_type=='debit')
                    $total_debit =$total_debit+$data->journal_particular_amount;

            }

            $total=$total_debit+(isset($total_opening_debit)?$total_opening_debit:0)-$total_credit-(isset($total_opening_credit)?$total_opening_credit:0);

      }

      $all_ledger_info = \App\Journal::GetLedgerAllChild($ledger_head,$depth);
      foreach ($all_ledger_info as $key => $list) {
            if(!in_array($list->ledger_name, $temp)){

                  $temp[]=$list->ledger_name;
                  $total_opening_debit2 =$total_opening_debit2+$list->ledger_debit;
                  $total_opening_credit2 =$total_opening_credit2+$list->ledger_credit;
            }

      }       


      $total=$total+(isset($total_opening_debit2)?$total_opening_debit2:0)-(isset($total_opening_credit2)?$total_opening_credit2:0);

      return $total;
    }


    /********************************************
    ## GetLedgerOpeningTotal
    *********************************************/

    public static function GetLedgerOpeningTotal($depth,$ledger_head){

      $total =0;
      $total_credit=0;
      $total_debit=0;
      $temp = array();
      $total_opening_debit=0;
      $total_opening_credit=0;

      
      $get_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_name','LIKE',$ledger_head);
        $demo_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_name','LIKE',$ledger_head);

         for($i=($depth+1); $i<=7; $i++){

              $demo_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');

              if($demo_journal->count() !=0){
             
                $get_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');
                
              }else break;
          }
          $get_balance=$get_journal->get();

          if(!empty($get_balance) && count($get_balance)>0){
            foreach ($get_balance as $key => $data) {          


                $total_opening_debit =$total_opening_debit+$data->ledger_debit;
                $total_opening_credit =$total_opening_credit+$data->ledger_credit;

            }

              $total=$total_opening_debit+$total_opening_credit;

          }


          return $total;
    }




    /********************************************
    ## GetLedgerTotal 
    *********************************************/

    public static function GetAllTotal($ledger_data){

      $total =0;
        $total_credit=0;
        $total_debit=0;
      if(count($ledger_data)>0){
        foreach ($ledger_data as $key => $data) {

                // if($data['journal_particular_amount_type']=='credit')
              $total_credit = $total_credit+$data['paritcular_total'];

                // if($data['journal_particular_amount_type']=='debit')
                    // $total_debit = $total_debit+$data['journal_particular_amount'];
        }

           // $total=$total_debit-$total_credit;
      }


      return $total;
    }








    /********************************************
    ## GetCashFlow 
    *********************************************/

    public static function GetCashFlow($cash_accounts,$bank_accounts){

        $total_inflow = 0;
        $total_outflow =0;

        if(count($cash_accounts)>0){
            foreach ($cash_accounts as $key => $data) {

                if($data->journal_particular_amount_type=='credit')
                    $total_outflow = $total_outflow+$data->journal_particular_amount;

                if($data->journal_particular_amount_type=='debit')
                    $total_inflow = $total_inflow+$data->journal_particular_amount;
            }
        }


        if(count($bank_accounts)>0){
            foreach ($bank_accounts as $key => $data) {

                if($data->journal_particular_amount_type=='credit')
                    $total_outflow = $total_outflow+$data->journal_particular_amount;

                if($data->journal_particular_amount_type=='debit')
                    $total_inflow = $total_inflow+$data->journal_particular_amount;
            }
        }

        return [
                'total_inflow' => $total_inflow,
                'total_outflow' => $total_outflow,
              ];
        
    }

    /********************************************
    ## GetCashFlow 
    *********************************************/

    public static function GetCashFlowByLedgerData($ledger_data){

        $total_inflow = 0;
        $total_outflow =0;

        if(count($ledger_data)>0){
            foreach ($ledger_data as $key => $data) {

                if($data->journal_particular_amount_type=='credit')
                    $total_outflow = $total_outflow+$data->journal_particular_amount;

                if($data->journal_particular_amount_type=='debit')
                    $total_inflow = $total_inflow+$data->journal_particular_amount;
            }
        }

        return [
                'total_inflow' => $total_inflow,
                'total_outflow' => $total_outflow,
              ];
    }

    /********************************************
    ## GetLedgerTotalByType
    *********************************************/

    public static function GetLedgerTotalByType($ledger_data,$type){

        $total =0;
      
        if(count($ledger_data)>0){
            foreach ($ledger_data as $key => $data) {
                if($data->journal_particular_amount_type==$type)
                    $total = $total+$data->journal_particular_amount;
            }
        }

        return $total;
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
    ## GetBalanceSheetLedgerByDateWithCostCenter 
    *********************************************/

    public static function GetBalanceSheetLedgerByDateWithCostCenterAndType($ledger_head,$depth,$form,$to,$cost_center, $amount_type){


        $get_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_3.ledger_name','LIKE',$ledger_head);
        $demo_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_3.ledger_name','LIKE',$ledger_head);

         for($i=($depth+1); $i<=7; $i++){

              $demo_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');

              if($demo_journal->count() !=0){
             
                $get_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');
                
              }else break;

         
          }

          if($cost_center == 0){
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
              ->where('ltech_general_journal.journal_particular_amount_type', $amount_type)
              ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
              ->get();
          }else{
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
              ->where('ltech_general_journal.journal_particular_amount_type', $amount_type)
              ->where('ltech_general_journal.cost_center_id', $cost_center)
              ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
              ->get();
          }
         

          return $all_data;
    }

    /********************************************
    ## GetTrailBalanceByDate 
    *********************************************/

    public static function GetTrailBalanceByDate($ledger_head,$depth,$search_from,$search_to,$cost_center_id){

        $get_journal = \DB::table('ltech_ledger_group_'.$depth)
        ->where('ltech_ledger_group_1.ledger_name','LIKE',$ledger_head);
        $demo_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_1.ledger_name','LIKE',$ledger_head);

         for($i=($depth+1); $i<=7; $i++){

              $demo_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id')
                ->where('ltech_ledger_group_'.$i.'.ledger_name','!=','Stock-in-hand');

              if($demo_journal->count() !=0){
             
                $get_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id')
                ->where('ltech_ledger_group_'.$i.'.ledger_name','!=','Stock-in-hand');
                
              }else break;

         
          }

          if($cost_center_id == 0){
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
              ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
              ->get();
          }else{

            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
            ->where('ltech_general_journal.cost_center_id',$cost_center_id)
            ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
            ->get();
          }



          return $all_data;
    }

    /********************************************
    ## GetPayableAndReceivableData 
    *********************************************/

    public static function GetPayableAndReceivableData($ledger_head,$depth,$search_from,$search_to,$cost_center_id){

        $get_journal = \DB::table('ltech_ledger_group_'.$depth)
        ->where('ltech_ledger_group_3.ledger_name','LIKE',$ledger_head);
        $demo_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_3.ledger_name','LIKE',$ledger_head);

         for($i=($depth+1); $i<=7; $i++){

              $demo_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id')
                ->where('ltech_ledger_group_'.$i.'.ledger_name','!=','Stock-in-hand');

              if($demo_journal->count() !=0){
             
                $get_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id')
                ->where('ltech_ledger_group_'.$i.'.ledger_name','!=','Stock-in-hand');
                
              }else break;

         
          }

          if($cost_center_id == 0){
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
              ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
              ->get();
          }else{

            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
            ->where('ltech_general_journal.cost_center_id',$cost_center_id)
            ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
            ->get();
          }

          return $all_data;
    }


    /********************************************
    ## GroupByPurchaseData
    *********************************************/

    public static function GroupByPurchaseData($group_data_info){

      if(count($group_data_info)>0){

        #Group By Item Name
        $result = array();
         foreach ($group_data_info as $group_data) {
           $id = $group_data->item_name;
           if (isset($result[$id])) {
              $result[$id][] = $group_data;
           } else {
              $result[$id] = array($group_data);
           }
         }

         #Group Data Process

         $all_data = array();
         foreach ($result as $key => $item_detail) {
            $inwards_amount=0;
            $inwards_quantity=0;
            $return_amount=0;
            $return_quantity=0;
             $item_name = $key;
            foreach ($item_detail as $node => $item){

              if($node==0){
                $item_id = $item->inventory_stock_id;
              }

              if($item->stocks_transaction_type=='inwards'){
                $inwards_amount=$inwards_amount+$item->stocks_quantity_cost;
                $inwards_quantity=$inwards_quantity+$item->transaction_stocks_quantity;
              }
              if($item->stocks_transaction_type=='return'){
                $return_amount=$return_amount+$item->stocks_quantity_cost;
                $return_quantity=$return_quantity+$item->transaction_stocks_quantity;
              }
            }

            $data = [
                      'item_name' =>$item_name,
                      'item_id' =>$item_id,
                      'inwards_amount' =>$inwards_amount,
                      'inwards_quantity' =>$inwards_quantity,
                      'return_amount' => $return_amount,
                      'return_quantity' => $return_quantity
                    ];
            $all_data[]= $data;
         }

        return $all_data;

      }

    }



    /********************************************
    ## GroupBySalesData
    *********************************************/

    public static function GroupBySalesData($group_data_info){

      if(count($group_data_info)>0){

        #Group By Item Name
        $result = array();
         foreach ($group_data_info as $group_data) {
           $id = $group_data->finish_goods_name;
           if (isset($result[$id])) {
              $result[$id][] = $group_data;
           } else {
              $result[$id] = array($group_data);
           }
         }

         #Group Data Process

         $all_data = array();
         foreach ($result as $key => $item_detail) {
            $inwards_amount=0;
            $inwards_quantity=0;
            $outwards_amount=0;
            $outwards_quantity=0;
            $return_amount=0;
            $return_quantity=0;
            $finish_goods_name = $key;
            
            foreach ($item_detail as $node => $item){

              if($node==0){
                $finish_goods_id = $item->finish_goods_id;
              }

              if($item->finish_goods_transaction_type=='outwards'){
                $outwards_amount=$outwards_amount+$item->finish_goods_quantity_cost;
                $outwards_quantity=$outwards_quantity+$item->transaction_finish_goods_quantity;
              }
              if($item->finish_goods_transaction_type=='return'){
                $return_amount=$return_amount+$item->finish_goods_quantity_cost;
                $return_quantity=$return_quantity+$item->transaction_finish_goods_quantity;
              }
              if($item->finish_goods_transaction_type=='inwards'){
                $inwards_amount=$inwards_amount+$item->finish_goods_quantity_cost;
                $inwards_quantity=$inwards_quantity+$item->transaction_finish_goods_quantity;
              }
            }


            $data = [
                      'finish_goods_name' =>$finish_goods_name,
                      'finish_goods_id' =>$finish_goods_id,
                      'inwards_amount' =>$inwards_amount,
                      'inwards_quantity' =>$inwards_quantity,
                      'outwards_amount' =>$outwards_amount,
                      'outwards_quantity' =>$outwards_quantity,
                      'return_amount' => $return_amount,
                      'return_quantity' => $return_quantity
                    ];

            $all_data[]= $data;
         }

        return $all_data;

      }

    }



    /********************************************
    ## GetPurchaseAndSalesData
    *********************************************/

    public static function GetPurchaseAndSalesData($ledger_head,$depth,$search_from,$search_to,$cost_center_id){

        $get_journal = \DB::table('ltech_ledger_group_'.$depth)
        ->where('ltech_ledger_group_4.ledger_name','LIKE',$ledger_head);
        $demo_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_4.ledger_name','LIKE',$ledger_head);

         for($i=($depth+1); $i<=7; $i++){

              $demo_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id')
                ->where('ltech_ledger_group_'.$i.'.ledger_name','!=','Stock-in-hand');

              if($demo_journal->count() !=0){
             
                $get_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id')
                ->where('ltech_ledger_group_'.$i.'.ledger_name','!=','Stock-in-hand');
                
              }else break;

         
          }

          if($cost_center_id == 0){
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
              // ->where('ltech_general_journal.journal_particular_amount_type','debit')
              ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
              ->get();
          }else{

            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
            // ->where('ltech_general_journal.journal_particular_amount_type','debit')
            ->where('ltech_general_journal.cost_center_id',$cost_center_id)
            ->whereBetween('ltech_general_journal.journal_date',[$search_from,$search_to])
            ->get();
          }



          return $all_data;
    }


    /********************************************
    ## GroupByData
    *********************************************/

   /* public static function GroupByData($group_data_info){

          $temp = array();
            foreach ($group_data_info as $key => $value) {

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

        return $temp;
    }*/

    /********************************************
    ## GetManufacturingRawMaterials
    *********************************************/

    public static function GetManufacturingRawMaterials($ledger_head,$depth,$form,$to,$cost_center,$transaction_type){

        $get_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_4.ledger_name','LIKE',$ledger_head);
        $demo_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_4.ledger_name','LIKE',$ledger_head);

         for($i=($depth+1); $i<=7; $i++){

              $demo_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');

              if($demo_journal->count() !=0){
             
                $get_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');
                
              }else break;

         
          }

          if($cost_center!=0){
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
            ->where('ltech_general_journal.cost_center_id',$cost_center)
            ->where('ltech_general_journal.posting_type',$transaction_type)
            ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
            ->get();
          }else{
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
            ->where('ltech_general_journal.posting_type',$transaction_type)
            ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
            ->get();
          }

          return $all_data;
    }




    /********************************************
    ## GetMerchandiseSales
    *********************************************/

    public static function GetMerchandiseSales($ledger_head,$depth,$form,$to,$cost_center,$transaction_type){

        $get_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_4.ledger_name','LIKE',$ledger_head);
        $demo_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_4.ledger_name','LIKE',$ledger_head);

         for($i=($depth+1); $i<=7; $i++){

              $demo_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');

              if($demo_journal->count() !=0){
             
                $get_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');
                
              }else break;

         
          }

          if($cost_center!=0){
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
            ->where('ltech_general_journal.cost_center_id',$cost_center)
            ->where('ltech_general_journal.posting_type',$transaction_type)
            ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
            ->get();
          }else{
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
            ->where('ltech_general_journal.posting_type',$transaction_type)
            ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
            ->get();
          }

          return $all_data;
    }




    /********************************************
    ## GetManufacturingReportByDate 
    *********************************************/

    public static function GetManufacturingReportByDate($ledger_head,$depth,$form,$to,$cost_center){

        $get_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_3.ledger_name','LIKE',$ledger_head);
        $demo_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_3.ledger_name','LIKE',$ledger_head);

         for($i=($depth+1); $i<=7; $i++){

              $demo_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');

              if($demo_journal->count() !=0){
             
                $get_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');
                
              }else break;

         
          }

          if($cost_center== 0){
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
            ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
            ->get();
          }else{
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
            ->where('ltech_general_journal.cost_center_id',$cost_center)
            ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
            ->get();
          }

          return $all_data;
    }


    /********************************************
    ## GroupByManufacturingData
    *********************************************/

    public static function GroupByManufacturingData($group_data_info){

          $temp = array();
            foreach ($group_data_info as $key => $value) {

                $exits = \App\Report::MultiArraySerach($value->ledger_name,'ledger_name',$temp);
                if($exits){
                    $temp[$exits]['debit'] = $value->journal_particular_amount_type=='debit' ? $temp[$exits]['debit'] + $value->journal_particular_amount: $temp[$exits]['debit'];

                    $temp[$exits]['credit'] = $value->journal_particular_amount_type=='credit' ? $temp[$exits]['credit'] + $value->journal_particular_amount: $temp[$exits]['credit'];
                }else{
                    $data_insert = [
                                    'ledger_name' => $value->ledger_name,
                                    'debit'=> (($value->ledger_debit)? $value->ledger_debit:0)+($value->journal_particular_amount_type=='debit' ? $value->journal_particular_amount:0),
                                    'credit'=> (($value->ledger_credit)? $value->ledger_credit:0)+($value->journal_particular_amount_type=='credit' ? $value->journal_particular_amount:0)
                                    ];
                    if(empty($temp))
                        $temp [1] = $data_insert;
                    else
                       $temp [] = $data_insert; 
                }    
            }

        return $temp;
    }



    /********************************************
    ## GroupByManufacturingData
    *********************************************/

    public static function ManufacturingData($group_data_info){

          $temp = array();
            foreach ($group_data_info as $key => $value) {

                    $data_insert = [
                                    'ledger_name' => $value->ledger_name,
                                    ];
                    if(empty($temp))
                        $temp [1] = $data_insert;
                    else
                       $temp [] = $data_insert; 
                }    

        return $temp;
    }



    /********************************************
    ## GroupByManufacturing
    *********************************************/

    public static function GroupByManufacturing($group_data_info){

          $temp = array();
            foreach ($group_data_info as $key => $value) {
              
                    $data_insert = [
                                    'journal_particular_name' => $value['ledger_name'],
                                    'debit' => $value['debit'],
                                    'credit' => $value['credit'],
                                    ];

                    if(empty($temp))
                        $temp [1] = $data_insert;
                    else
                       $temp [] = $data_insert; 
                }    

        return $temp;
    }




    /********************************************
    ## OpeningClosingData
    *********************************************/

    public static function OpeningClosingData($group_data_info){

          $temp = array();
            foreach ($group_data_info as $key => $value) {

                    $data_insert = [
                                    'stock_id' => $value->inventory_stock_id,
                                    ];
                    if(empty($temp))
                        $temp [1] = $data_insert;
                    else
                       $temp [] = $data_insert; 
                }   

        return $temp;
    }

    /********************************************
    ## GroupByData
    *********************************************/

    public static function GroupByData($group_data_info){

          $temp = array();
            foreach ($group_data_info as $key => $value) {

                $exits = \App\Report::MultiArraySerach($value->ledger_name,'ledger_name',$temp);
                if($exits){
                    $temp[$exits]['debit'] = $value->journal_particular_amount_type=='debit' ? $temp[$exits]['debit'] + $value->journal_particular_amount: $temp[$exits]['debit'];
                    $temp[$exits]['credit'] = $value->journal_particular_amount_type=='credit' ? $temp[$exits]['credit'] + $value->journal_particular_amount: $temp[$exits]['credit'];
                }else{
                  $opening_data=\App\Journal::JournalEntryinfo($value->ledger_id, $value->depth);
                    $data_insert = [
                                    'ledger_name' =>$value->ledger_name,
                                    'debit' =>$opening_data->ledger_debit+($value->journal_particular_amount_type=='debit' ?  $value->journal_particular_amount:0),
                                    'credit' =>$opening_data->ledger_credit+($value->journal_particular_amount_type=='credit' ? $value->journal_particular_amount:0)
                                    ];

                    if(empty($temp))
                        $temp [1] = $data_insert;
                    else
                       $temp [] = $data_insert; 
                }    
            }

        return $temp;
    }




    /********************************************
    ## StockMultiArraySerach 
    *********************************************/

    public static function StockMultiArraySerach($search,$search_key,$array){

       foreach ($array as $key => $value) {
           if ($value[$search_key] === $search) {
               return $key;
           }
       }
       return null;
    }



    /********************************************
    ## GroupByStockData
    *********************************************/

    public static function GroupByStockData($group_data_info){

          $temp = array();
            foreach ($group_data_info as $key => $value) {

                $exits = \App\Report::StockMultiArraySerach($value->inventory_stock_id,'inventory_stock_id',$temp);
                if($exits){
                    $temp[$exits]['outwards_qty'] = $value->stocks_transaction_type=='outwards' ? $temp[$exits]['outwards_qty'] + $value->transaction_stocks_quantity: $temp[$exits]['outwards_qty'];

                    $temp[$exits]['outwards_cost'] = $value->stocks_transaction_type=='outwards' ? $temp[$exits]['outwards_cost'] + $value->stocks_quantity_cost: $temp[$exits]['outwards_cost'];

                    $temp[$exits]['inwards_qty'] = $value->stocks_transaction_type=='inwards' ? $temp[$exits]['inwards_qty'] + $value->transaction_stocks_quantity: $temp[$exits]['inwards_qty'];

                    $temp[$exits]['inwards_cost'] = $value->stocks_transaction_type=='inwards' ? $temp[$exits]['inwards_cost'] + $value->stocks_quantity_cost: $temp[$exits]['inwards_cost'];


                    $temp[$exits]['return_qty'] = $value->stocks_transaction_type=='return' ? $temp[$exits]['return_qty'] + $value->transaction_stocks_quantity: $temp[$exits]['return_qty'];

                    $temp[$exits]['return_cost'] = $value->stocks_transaction_type=='return' ? $temp[$exits]['return_cost'] + $value->stocks_quantity_cost: $temp[$exits]['return_cost'];

                }else{
                    $data_insert = [
                                    'inventory_stock_id' => $value->inventory_stock_id,
                                    'outwards_qty' => ($value->stocks_transaction_type=='outwards' ?  $value->transaction_stocks_quantity:0),
                                    'outwards_cost' => ($value->stocks_transaction_type=='outwards' ? $value->stocks_quantity_cost:0),

                                    'inwards_qty' => ($value->stocks_transaction_type=='inwards' ?  $value->transaction_stocks_quantity:0),
                                    'inwards_cost' => ($value->stocks_transaction_type=='inwards' ? $value->stocks_quantity_cost:0),

                                    'return_qty' => ($value->stocks_transaction_type=='return' ?  $value->transaction_stocks_quantity:0),
                                    'return_cost' => ($value->stocks_transaction_type=='return' ? $value->stocks_quantity_cost:0),
                                    ];
                    if(empty($temp))
                        $temp [1] = $data_insert;
                    else
                       $temp [] = $data_insert; 
                }    
            }

        return $temp;
    }



    /********************************************
    ## GroupByFinishGoodsData
    *********************************************/

    public static function GroupByFinishGoodsData($group_data_info){

          $temp = array();
            foreach ($group_data_info as $key => $value) {

                $exits = \App\Report::StockMultiArraySerach($value->finish_goods_id,'finish_goods_id',$temp);
                if($exits){
                    $temp[$exits]['outwards_qty'] = $value->finish_goods_transaction_type=='outwards' ? $temp[$exits]['outwards_qty'] + $value->transaction_finish_goods_quantity: $temp[$exits]['outwards_qty'];

                    $temp[$exits]['outwards_cost'] = $value->finish_goods_transaction_type=='outwards' ? $temp[$exits]['outwards_cost'] + $value->finish_goods_quantity_cost: $temp[$exits]['outwards_cost'];

                    $temp[$exits]['inwards_qty'] = $value->finish_goods_transaction_type=='inwards' ? $temp[$exits]['inwards_qty'] + $value->transaction_finish_goods_quantity: $temp[$exits]['inwards_qty'];
                    $temp[$exits]['inwards_rate'] = $value->finish_goods_transaction_type=='inwards' ? $temp[$exits]['inwards_rate'] + $value->transaction_finish_goods_quantity: $temp[$exits]['inwards_rate'];

                    $temp[$exits]['inwards_cost'] = $value->finish_goods_transaction_type=='inwards' ? $temp[$exits]['inwards_cost'] + $value->finish_goods_quantity_cost: $temp[$exits]['inwards_cost'];


                    $temp[$exits]['return_qty'] = $value->finish_goods_transaction_type=='return' ? $temp[$exits]['return_qty'] + $value->transaction_finish_goods_quantity: $temp[$exits]['return_qty'];

                    $temp[$exits]['return_cost'] = $value->finish_goods_transaction_type=='return' ? $temp[$exits]['return_cost'] + $value->finish_goods_quantity_cost: $temp[$exits]['return_cost'];

                }else{
                    $data_insert = [
                                    'finish_goods_id' => $value->finish_goods_id,
                                    'outwards_qty' => ($value->finish_goods_transaction_type=='outwards' ?  $value->transaction_finish_goods_quantity:0),
                                    'outwards_cost' => ($value->finish_goods_transaction_type=='outwards' ? $value->finish_goods_quantity_cost:0),

                                    'inwards_qty' => ($value->finish_goods_transaction_type=='inwards' ?  $value->transaction_finish_goods_quantity:0),
                                    'inwards_cost' => ($value->finish_goods_transaction_type=='inwards' ? $value->finish_goods_quantity_cost:0),
                                    'inwards_rate' => ($value->finish_goods_transaction_type=='inwards' ? $value->finish_goods_quantity_rate:0),
                                    
                                    'return_qty' => ($value->finish_goods_transaction_type =='return' ?  $value->transaction_finish_goods_quantity:0),
                                    'return_cost' => ($value->finish_goods_transaction_type=='return' ? $value->finish_goods_quantity_cost:0),
                                    ];
                    if(empty($temp))
                        $temp [1] = $data_insert;
                    else
                       $temp [] = $data_insert; 
                }    
            }

        return $temp;
    }

    /********************************************
    ## BalancesheetByDateAndCostCenter
    *********************************************/

    public static function BalancesheetByDateAndCostCenter($ledger_head,$depth,$form,$to,$cost_center){

        $get_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_2.ledger_name','LIKE',$ledger_head);
        $demo_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_2.ledger_name','LIKE',$ledger_head);

         for($i=($depth+1); $i<=7; $i++){

              $demo_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');

              if($demo_journal->count() !=0){
             
                $get_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');
                
              }else break;
          
          }

          if($cost_center!=0){
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
            ->where('ltech_general_journal.cost_center_id',$cost_center)
            ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
            ->get();
          }else{
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
            ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
            ->get();
          }

          return $all_data;
    }


    /********************************************
    ## multiArraySerach 
    *********************************************/

    public static function MultiArraySerach($search,$search_key,$array){

       foreach ($array as $key => $value) {
           if ($value[$search_key] === $search) {
               return $key;
           }
       }
       return null;
    }





###########################################


    /********************************************
    ## ABC
    *********************************************/

    public static function ABC($ledger_head,$depth,$form,$to,$cost_center){


        $get_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_name','LIKE',$ledger_head);
        $demo_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_name','LIKE',$ledger_head);

         for($i=($depth+1); $i<=7; $i++){

              $demo_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');

              if($demo_journal->count() !=0){
             
                $get_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');
                
              }else break;

         
          }

          if($cost_center == 0){
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
              ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
              ->get();
          }else{
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
              ->where('ltech_general_journal.cost_center_id', $cost_center)
              ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
              ->get();
          }


          return $all_data;
    }






################# 04-02-2017 #######################


    /********************************************
    ## GetLadgerDetailsByDateWithCost
    *********************************************/

    public static function GetLadgerDetailsByDateWithCost($ledger_head,$depth,$form,$to,$cost_center){


        $get_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_name','LIKE',$ledger_head);
        $demo_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_name','LIKE',$ledger_head);

         for($i=($depth+1); $i<=7; $i++){

              $demo_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');

              if($demo_journal->count() !=0){
             
                $get_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id');
                
              }else break;
          }

          if($cost_center == 0){
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
              ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
              ->get();
          }else{
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
              ->where('ltech_general_journal.cost_center_id', $cost_center)
              ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
              ->get();
          }
         

          return $all_data;
    }



    /********************************************
    ## OpeningClosingFinishGoodsData
    *********************************************/

    public static function OpeningClosingFinishGoodsData($group_data_info){

          $temp = array();
            foreach ($group_data_info as $key => $value) {

                    $data_insert = [
                                    'finish_goods_stock_id' => $value->finish_goods_id,
                                    ];
                    if(empty($temp))
                        $temp [1] = $data_insert;
                    else
                       $temp [] = $data_insert; 
                }    

        return $temp;
    }



    /********************************************
    ## FinishGoodsOpeningData
    *********************************************/

    public static function FinishGoodsOpeningData($search_from, $search_to, $cost_center){


        $ltech_finish_goods_stocks_id=\DB::table('ltech_finish_goods_stocks')->get();

            $all_finish_goods_stocks_id_info= \App\Report::OpeningClosingFinishGoodsData($ltech_finish_goods_stocks_id);
            $all_opening_amount=0;

            foreach ($all_finish_goods_stocks_id_info as $key => $value){
            if($cost_center!=0){
                $finish_goods_opening_list=\DB::table('ltech_finish_goods_transactions')
                                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
                                ->where('ltech_finish_goods_transactions.finish_goods_id',trim($value['finish_goods_stock_id']))
                                ->where('ltech_finish_goods_transactions.cost_center_id',$cost_center)
                                ->where('ltech_finish_goods_transactions.finish_goods_transaction_type','inwards')
                                ->orderBy('ltech_finish_goods_transactions.ltech_finish_goods_transactions_id','asc')
                                ->first();
                }else{
                    $finish_goods_opening_list=\DB::table('ltech_finish_goods_transactions')
                                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
                                ->where('ltech_finish_goods_transactions.finish_goods_id',trim($value['finish_goods_stock_id']))
                                ->where('ltech_finish_goods_transactions.finish_goods_transaction_type','inwards')
                                ->orderBy('ltech_finish_goods_transactions.ltech_finish_goods_transactions_id','asc')
                                ->first(); 
                }

                $opening_data = !empty($finish_goods_opening_list->opening_transaction_finish_goods_cost) ? $finish_goods_opening_list->opening_transaction_finish_goods_cost:0;

                $all_opening_amount=$all_opening_amount+$opening_data;
            }

    return $all_opening_amount;
  }



    /********************************************
    ## FinishGoodsClosingData
    *********************************************/

    public static function FinishGoodsClosingData($search_from, $search_to, $cost_center){


        $ltech_finish_goods_stocks_id=\DB::table('ltech_finish_goods_stocks')->get();
        $all_finish_goods_stocks_id_info= \App\Report::OpeningClosingFinishGoodsData($ltech_finish_goods_stocks_id);
        $all_closing_amount=0;

            foreach ($all_finish_goods_stocks_id_info as $key => $value){
                if($cost_center!=0){
                    $finish_goods_opening_list=\DB::table('ltech_finish_goods_transactions')
                                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
                                ->where('ltech_finish_goods_transactions.finish_goods_id',trim($value['finish_goods_stock_id']))
                                ->where('ltech_finish_goods_transactions.cost_center_id',$cost_center)
                                ->where('ltech_finish_goods_transactions.finish_goods_transaction_type','inwards')
                                ->orderBy('ltech_finish_goods_transactions.ltech_finish_goods_transactions_id','desc')
                                ->first();
                }else{
                    $finish_goods_opening_list=\DB::table('ltech_finish_goods_transactions')
                                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
                                ->where('ltech_finish_goods_transactions.finish_goods_id',trim($value['finish_goods_stock_id']))
                                ->where('ltech_finish_goods_transactions.finish_goods_transaction_type','inwards')
                                ->orderBy('ltech_finish_goods_transactions.ltech_finish_goods_transactions_id','desc')
                                ->first(); 
                }

                $closing_data = !empty($finish_goods_opening_list->closing_transaction_finish_goods_cost) ? $finish_goods_opening_list->closing_transaction_finish_goods_cost:0;

                $all_closing_amount=$all_closing_amount+$closing_data;
            }

    return $all_closing_amount;
  }

    /********************************************
    ## FinishGoodsInwardsData
    *********************************************/

    public static function FinishGoodsInwardsData($search_from, $search_to, $cost_center){

        $all_inwards_amount=0;

            // $ltech_finish_goods_stocks_id=\DB::table('ltech_finish_goods_stocks')->get();
            // $all_finish_goods_stocks_id_info= \App\Report::OpeningClosingFinishGoodsData($ltech_finish_goods_stocks_id);

            // foreach ($all_finish_goods_stocks_id_info as $key1 => $value){
              if($cost_center!=0){
                  $finish_goods_inwards_list=\DB::table('ltech_finish_goods_transactions')
                                  ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
                                  ->where('ltech_finish_goods_transactions.cost_center_id',$cost_center)
                                  ->where('ltech_finish_goods_transactions.finish_goods_transaction_type','inwards')
                                  ->get();
                  }else{
                      $finish_goods_inwards_list=\DB::table('ltech_finish_goods_transactions')
                                  ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
                                  ->where('ltech_finish_goods_transactions.finish_goods_transaction_type','inwards')
                                  ->get(); 
                  }
                // $all_inwards_amount=0;

                foreach ($finish_goods_inwards_list as $key2 => $list) {
                  $inwards_data = !empty($list->finish_goods_quantity_cost) ? $list->finish_goods_quantity_cost:0;
                  $all_inwards_amount=$all_inwards_amount+$inwards_data;
                  
                }

            // }


      return $all_inwards_amount;
    }

    /********************************************
    ## FinishGoodsInwardsOpeningData
    *********************************************/

    public static function FinishGoodsInwardsOpeningData($search_from, $search_to, $cost_center){

        $total =0;
        $total_credit=0;
        $total_debit=0;
        $temp = array();
        $total_opening_debit=0;
        $total_opening_credit=0;
        $total_opening_debit2=0;
        $total_opening_credit2=0;
        $all_inwards_amount=0;

              if($cost_center!=0){
                  $finish_goods_inwards_list=\DB::table('ltech_finish_goods_transactions')
                                  ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
                                  ->where('ltech_finish_goods_transactions.cost_center_id',$cost_center)
                                  ->where('ltech_finish_goods_transactions.finish_goods_transaction_type','inwards')
                                  ->get();
                  }else{
                      $finish_goods_inwards_list=\DB::table('ltech_finish_goods_transactions')
                                  ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date',[$search_from,$search_to])
                                  ->where('ltech_finish_goods_transactions.finish_goods_transaction_type','inwards')
                                  ->get(); 
                  }

                foreach ($finish_goods_inwards_list as $key2 => $list) {

                    $goods_account_info=$list->finish_goods_accounts_id;
                    $goods_account_info_details = explode('.', ($goods_account_info));
                    $goods_account_id=$goods_account_info_details[0];
                    $goods_account_depth=$goods_account_info_details[1];

                    $ledger_info=\DB::table('ltech_ledger_group_'.$goods_account_depth)->where('ledger_id',$goods_account_id)->first();

                    if(!in_array($ledger_info->ledger_name, $temp)){
                      $temp[]=$ledger_info->ledger_name;
                      $total_opening_debit =$ledger_info->ledger_debit;
                      $total_opening_credit =$ledger_info->ledger_credit;
                    }

                  $inwards_data = (!empty($list->finish_goods_quantity_cost) ? $list->finish_goods_quantity_cost:0)+$total_opening_debit-$total_opening_credit;
                  $all_inwards_amount=$all_inwards_amount+$inwards_data;
                  
                }

                 $all_ledger_info = \App\Journal::GetLedgerAllChild('Stocks-in finish goods','4');
                  foreach ($all_ledger_info as $key => $list) {
                        if(!in_array($list->ledger_name, $temp)){

                              $temp[]=$list->ledger_name;
                              $total_opening_debit2 =$list->ledger_debit;
                              $total_opening_credit2 =$list->ledger_credit;
                              $all_inwards_amount=$all_inwards_amount+$total_opening_debit2-$total_opening_debit2;
                        }

                  }


      return $all_inwards_amount;
    }





    /********************************************
    ## InventoryStocksOpeningData
    *********************************************/

    public static function InventoryStocksOpeningData($search_from, $search_to, $cost_center){

            
            $ltech_inventory_stocks_id=\DB::table('ltech_inventory_stocks')->get();

            $all_stocks_id_info= \App\Report::OpeningClosingData($ltech_inventory_stocks_id);
            $all_opening_amount=0;

            foreach ($all_stocks_id_info as $key => $value){
            if($cost_center!=0){
                $stock_summery_opening_list=\DB::table('ltech_inventory_stocks_transactions')
                                ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                                ->where('ltech_inventory_stocks_transactions.inventory_stock_id',trim($value['stock_id']))
                                ->where('ltech_inventory_stocks_transactions.cost_center_id',$cost_center)
                                ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','inwards')
                                ->orderBy('ltech_inventory_stocks_transactions.stocks_transactions_id','asc')
                                ->first();
                }else{
                    $stock_summery_opening_list=\DB::table('ltech_inventory_stocks_transactions')
                                ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                                ->where('ltech_inventory_stocks_transactions.inventory_stock_id',trim($value['stock_id']))
                                ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','inwards')
                                ->orderBy('ltech_inventory_stocks_transactions.stocks_transactions_id','asc')
                                ->first(); 
                }

                $opening_data = !empty($stock_summery_opening_list->opening_transaction_stocks_cost) ? $stock_summery_opening_list->opening_transaction_stocks_cost:0;

                $all_opening_amount=$all_opening_amount+$opening_data;
            }

    return $all_opening_amount;
  }



    /********************************************
    ## InventoryStocksClosingData
    *********************************************/

    public static function InventoryStocksClosingData($search_from, $search_to, $cost_center){

            
        $ltech_inventory_stocks_id=\DB::table('ltech_inventory_stocks')->get();

        $all_stocks_id_info= \App\Report::OpeningClosingData($ltech_inventory_stocks_id);

        $all_closing_amount=0;
            foreach ($all_stocks_id_info as $key => $value) {

            if($cost_center!=0){
                $stock_closing_list=\DB::table('ltech_inventory_stocks_transactions')
                                ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                                ->where('ltech_inventory_stocks_transactions.inventory_stock_id',trim($value['stock_id']))
                                ->where('ltech_inventory_stocks_transactions.cost_center_id',$cost_center)
                                ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','!=','outwards')
                                ->orderBy('ltech_inventory_stocks_transactions.stocks_transactions_id','desc')
                                ->first();

                      

                }else{
                        $stock_closing_list=\DB::table('ltech_inventory_stocks_transactions')
                                ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
                                ->where('ltech_inventory_stocks_transactions.inventory_stock_id',trim($value['stock_id']))
                                ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','!=','outwards')
                                ->orderBy('ltech_inventory_stocks_transactions.stocks_transactions_id','desc')
                                ->first();
                        
                }
              

                $closing_data = !empty($stock_closing_list->closing_transaction_stocks_cost) ? $stock_closing_list->closing_transaction_stocks_cost:0;
                $all_closing_amount=$all_closing_amount+$closing_data;
            }


    return $all_closing_amount;
  }



    /********************************************
    ## GetCostOfProduction
    *********************************************/
    public static function GetCostOfProduction($search_from,$search_to,$cost_center){

        $total_carriage_balance=0;
        $total_direct_expenses_balance=0;
        $grand_total_direct_expenses_balance=0;
        $grand_total_carriage_balance=0;
        $total_stock_outwards_amount=0;

        $grand_total_balance=0;

        ##### Opening Raw Materials Amount #####
            
        //     $all_opening_amount= \App\Report::InventoryStocksOpeningData($search_from, $search_to, $cost_center);

        //     if(!empty($all_opening_amount) && count($all_opening_amount)>0){
        //         $grand_total_balance=$grand_total_balance+$all_opening_amount;
        //     }

        // ##### Carriage Inwards Amount #####

        //     $total_amount_of_raw_materials_purchase_data=0;
        //     $raw_materials_purchase_data_info= \App\Report::GetManufacturingRawMaterials('Stocks-in raw material','4',$search_from, $search_to, $cost_center,'purchase');
        //     $total_raw_materials_purchase_data_info= \App\Report::GroupByManufacturingData($raw_materials_purchase_data_info);

        //     foreach ($total_raw_materials_purchase_data_info as $key => $value) {
        //         $total_amount_of_raw_materials_purchase_data=$total_amount_of_raw_materials_purchase_data+$value['debit'];
        //     }
        //     $data['total_amount_of_raw_materials_purchase_data'] = $total_amount_of_raw_materials_purchase_data;

        //     if(!empty($total_amount_of_raw_materials_purchase_data) && count($total_amount_of_raw_materials_purchase_data)>0){
        //         $grand_total_balance=$grand_total_balance+$total_amount_of_raw_materials_purchase_data;
        //     }


        // ##### Carriage Inwards Amount #####

        //     $carriage_data_info= \App\Report::GetLadgerDetailsByDateWithCost('Carriage Inwards','2',$search_from, $search_to, $cost_center);
        //     $total_carriage_data_info= \App\Report::GroupByManufacturingData($carriage_data_info);
        //     $data['total_carriage_data_info'] = $total_carriage_data_info;

        //     if(!empty($total_carriage_data_info) && count($total_carriage_data_info)>0){
        //     foreach($total_carriage_data_info as $key => $list){
        //         $total_carriage_balance=$list['debit']-$list['credit'];
        //         $grand_total_carriage_balance=$grand_total_carriage_balance+$total_carriage_balance;
        //     }
        //         $grand_total_balance=$grand_total_balance+$total_carriage_balance;
        //     }

        // ##### Outwards Raw Materials Amount #####
        // if($cost_center!=0){
        //     $stock_summery_outwards_list=\DB::table('ltech_inventory_stocks_transactions')
        //                 ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
        //                 ->where('ltech_inventory_stocks_transactions.cost_center_id',$cost_center)
        //                 ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','return')
        //                 ->get();
        // }else{
        //     $stock_summery_outwards_list=\DB::table('ltech_inventory_stocks_transactions')
        //                 ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date',[$search_from,$search_to])
        //                 ->where('ltech_inventory_stocks_transactions.stocks_transaction_type','return')
        //                 ->get();   
        // }

        // if(!empty($stock_summery_outwards_list) && count($stock_summery_outwards_list)>0){

        // foreach ($stock_summery_outwards_list as $key => $value) {
        //     $total_stock_outwards_amount=$total_stock_outwards_amount+$value->stocks_quantity_cost;
        // }
        //     $grand_total_balance=$grand_total_balance-$total_stock_outwards_amount;

        // }



        // ##### Closing Raw Materials Amount #####

        // $all_closing_amount= \App\Report::InventoryStocksClosingData($search_from, $search_to, $cost_center);

        // if(!empty($all_closing_amount) && count($all_closing_amount)>0){
        //     $grand_total_balance=$grand_total_balance-$all_closing_amount;
        // }


        //     $direct_labor_info= \App\Report::GetLadgerDetailsByDateWithCost('Direct Labor','2',$search_from, $search_to, $cost_center);
        //     $total_direct_labor_data= \App\Report::GetLedgerTotal($direct_labor_info);
        //     $data['total_direct_labor_data'] = $total_direct_labor_data;


        // ##### Other Direct Expenses #####

        // $others_expences_data_info= \App\Report::GetLadgerDetailsByDateWithCost('Other Direct Expenses','2',$search_from, $search_to, $cost_center);
        // $total_others_expences_data_info= \App\Report::GetLedgerTotal($others_expences_data_info);
        // $data['total_others_expences_data_info'] = $total_others_expences_data_info;




        // ##### Factory Overhead Expenses #####
        // $expenses_info=\DB::table('ltech_ledger_group_2')->where('ledger_name','Factory Overhead')->first();
        // $overhead_info=\DB::table('ltech_ledger_group_3')->where('ledger_group_parent_id',$expenses_info->ledger_id)->get();
        // $all_overhead_info= \App\Report::ManufacturingData($overhead_info);

        // if(!empty($all_overhead_info)){
        //     foreach ($all_overhead_info as $key => $value) {
        //         $lighting_data_info= \App\Report::GetManufacturingReportByDate(trim($value['ledger_name']),'3',$search_from, $search_to, $cost_center);
        //         $total_lighting_data_info= \App\Report::GroupByManufacturingData($lighting_data_info);

        //     $alll_overhead_info[]= \App\Report::GroupByManufacturing($total_lighting_data_info);
        //     // $alll_overhead_info= \App\Report::GetLedgerTotal($total_lighting_data_info);
        //     }

        // }







            $all_opening_amount= \App\Report::InventoryStocksOpeningData($search_from, $search_to, $cost_center);
            $grand_total_balance=$grand_total_balance+$all_opening_amount;

            $total_amount_of_raw_materials_purchase_data=0;
            $raw_materials_purchase_data_info= \App\Report::GetManufacturingRawMaterials('Stocks-in raw material','4',$search_from, $search_to, $cost_center,'purchase');
            $total_raw_materials_purchase_data_info= \App\Report::GroupByManufacturingData($raw_materials_purchase_data_info);


            foreach ($total_raw_materials_purchase_data_info as $key => $value) {
                $total_amount_of_raw_materials_purchase_data=$total_amount_of_raw_materials_purchase_data+$value['debit'];
            }


            $grand_total_balance=$grand_total_balance+$total_amount_of_raw_materials_purchase_data;


            $carriage_data_info= \App\Report::GetLadgerDetailsByDateWithCost('Carriage Inwards','2',$search_from, $search_to, $cost_center);
            $total_carriage_data_info= \App\Report::GroupByManufacturingData($carriage_data_info);
            $data['total_carriage_data_info'] = $total_carriage_data_info;

            if(!empty($total_carriage_data_info) && count($total_carriage_data_info)>0){
            foreach($total_carriage_data_info as $key => $list){
                $total_carriage_balance=$list['debit']-$list['credit'];
                $grand_total_carriage_balance=$grand_total_carriage_balance+$total_carriage_balance;
            }
                $grand_total_balance=$grand_total_balance+$total_carriage_balance;
            }




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


            $grand_total_balance=$grand_total_balance-$total_stock_outwards_amount;


            $all_closing_amount= \App\Report::InventoryStocksClosingData($search_from, $search_to, $cost_center);

            // $total_closing_balance=0;
            // $total_closing_balance=$grand_total_balance-$all_closing_amount;


            $grand_total_balance=$grand_total_balance-$all_closing_amount;



            $direct_labor_info= \App\Report::GetLadgerDetailsByDateWithCost('Direct Labor','2',$search_from, $search_to, $cost_center);
            $total_direct_labor_data= \App\Report::GetLedgerTotal($direct_labor_info);
            $grand_total_balance=$grand_total_balance+$total_direct_labor_data;




            $others_expences_data_info= \App\Report::GetLadgerDetailsByDateWithCost('Other Direct Expenses','2',$search_from, $search_to, $cost_center);
            $total_others_expences_data_info= \App\Report::GetLedgerTotal($others_expences_data_info);
            $grand_total_balance=$grand_total_balance+$total_others_expences_data_info;





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



        $balance=0;
        $total_balance=0;
     
        if(isset($alll_overhead_info) && count($alll_overhead_info)>0){
            foreach($alll_overhead_info as $key => $list){
                if(count($list)>0){
                    foreach($list as $key => $value){

                            if(($value['debit']-$value['credit']) < 0){
                                $balance = (-1)*($value['debit']-$value['credit']);
                            }else{
                                $balance=($value['debit']-$value['credit']);
                            }

                     $total_balance=$total_balance+$balance; 
                    }
                }
            }

            $grand_total_balance=$grand_total_balance+$total_balance;


        }

       return $grand_total_balance;

    }



    /********************************************
    ## CurrentAssetsByDateAndCostCenter
    *********************************************/

    public static function CurrentAssetsByDateAndCostCenter($ledger_head,$depth,$form,$to,$cost_center){

        $get_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_name','LIKE',$ledger_head);
        $demo_journal = \DB::table('ltech_ledger_group_'.$depth)->where('ltech_ledger_group_'.$depth.'.ledger_name','LIKE',$ledger_head);

         for($i=($depth+1); $i<=7; $i++){

              $demo_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id')
            ->whereNotIn('ltech_ledger_group_'.$i.'.ledger_name',['Stocks-in raw material','Stocks-in finish goods','Customer']);

              if($demo_journal->count() !=0){
             
                $get_journal->join('ltech_ledger_group_'.$i, 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_ledger_group_'.$i.'.ledger_group_parent_id')
            ->whereNotIn('ltech_ledger_group_'.$i.'.ledger_name',['Stocks-in raw material','Stocks-in finish goods','Customer']);
                
              }else break;
         
          }

          if($cost_center!=0){
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
            ->where('ltech_general_journal.cost_center_id',$cost_center)
            ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
            ->get();
          }else{
            $all_data=  $get_journal->join('ltech_general_journal', 'ltech_ledger_group_'.($i-1).'.ledger_id', '=', 'ltech_general_journal.journal_particular_id')
            ->whereBetween('ltech_general_journal.journal_date',[$form,$to])
            ->get();
          }

          return $all_data;
    }






    /********************************************
    ## MakePositiveData
    *********************************************/

    public static function MakePositiveData($balance_amount){

          $balance_amount= number_format($balance_amount,2,'.','');

          if($balance_amount<0){
            $all_data= '(-) '.(-1)*$balance_amount;
          }else{
            $all_data=$balance_amount;
          }

        return $all_data;
    }

    /********************************************
    ## CreatePositiveData
    *********************************************/

    public static function CreatePositiveData($balance_amount){

          // $balance_amount= number_format($balance_amount,2,'.','');

          if($balance_amount<0){
            $all_data=(-1)*$balance_amount;
          }else{
            $all_data=$balance_amount;
          }

        return $all_data;
    }



    /********************************************
    ## SummationOfDebitAndCreditData
    *********************************************/

    public static function SummationOfDebitAndCreditData($debit_data_info,$credit_data_info){

      $final_date=$debit_data_info-$credit_data_info;

        return $final_date;
    }


    /********************************************
    ## ProfitandLossReport
    *********************************************/
    public static function ProfitandLossReport($search_from,$search_to,$cost_center){

        $total_net_revenues=0;
        $total_net_expenses=0;
        $total_net_income=0;

        $data['search_from']=$search_from;
        $data['search_to']=$search_to;
        $data['cost_center']=$cost_center;


        // $finish_goods_info = \App\Report::GetLadgerDetailsByDateWithCost('Stocks-in finish goods','4',$search_from, $search_to, $cost_center);
        // $total_finish_goods = \App\Report::GetLedgerTotal($finish_goods_info);

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
        $total_other_incomes = \App\Report::GetLedgerTotal($other_incomes_info);


        $total_net_revenues=$total_net_revenues + ($total_finish_goods + $total_other_incomes);

        $all_finish_goods_opening_balance= \App\Report::FinishGoodsOpeningData($search_from, $search_to, $cost_center);
        $all_finish_goods_closing_balance= \App\Report::FinishGoodsClosingData($search_from, $search_to, $cost_center);
        // $all_finish_goods_return_balance= \App\Report::FinishGoodsReturnData($search_from, $search_to, $cost_center);


        $indirect_expenses_info = \App\Report::GetLadgerDetailsByDateWithCost('Indirect Expenses','1',$search_from, $search_to, $cost_center);
        $total_indirect_expenses = \App\Report::GetLedgerTotal($indirect_expenses_info);


        $cost_production=\App\Report::GetCostOfProduction($search_from, $search_to, $cost_center);

        $total_net_expenses=$all_finish_goods_opening_balance+$cost_production-$all_finish_goods_closing_balance;

        if($total_net_revenues<0){
          $total_net_revenues=$total_net_revenues *(-1);  
        }

        if($total_net_expenses<0){
          $total_net_expenses=$total_net_expenses *(-1);  
        }

        $total_net_income=$total_net_revenues-$total_net_expenses;

        return $total_net_income;


    }



    /********************************************
    ## RawMaterialsTransaction
    *********************************************/
    public static function RawMaterialsTransaction($search_from,$search_to,$cost_center){

        if($cost_center == '0'){
            $get_all_stock_transaction= \DB::table('ltech_inventory_stocks_transactions')
                ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date', [$search_from,$search_to])
                ->get();
        }else{
            $get_all_stock_transaction= \DB::table('ltech_inventory_stocks_transactions')
                ->leftjoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
                ->whereBetween('ltech_inventory_stocks_transactions.stocks_transaction_date', [$search_from,$search_to])
                ->where('ltech_inventory_stocks_transactions.cost_center_id',$cost_center)
                ->get();
        }
        return $get_all_stock_transaction;

      }



    /********************************************
    ## FinishGoodsTransaction
    *********************************************/
    public static function FinishGoodsTransaction($search_from,$search_to,$cost_center){

        if($cost_center == '0'){
            $get_finish_goods_transaction= \DB::table('ltech_finish_goods_transactions')
                ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date', [$search_from,$search_to])
                ->get();
        }else{
            $get_finish_goods_transaction= \DB::table('ltech_finish_goods_transactions')
                ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date', [$search_from,$search_to])
                ->where('ltech_finish_goods_transactions.cost_center_id',$cost_center)
                ->get();
        }
        return $get_finish_goods_transaction;

      }



    /********************************************
    ## FinishGoodsTransactionInwards
    *********************************************/
    public static function FinishGoodsTransactionInwards($search_from,$search_to,$cost_center,$type){

        if($cost_center == '0'){
            $get_finish_goods_transaction= \DB::table('ltech_finish_goods_transactions')
                ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                ->where('ltech_finish_goods_transactions.finish_goods_transaction_type','inwards')
                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date', [$search_from,$search_to])
                ->get();
        }else{
            $get_finish_goods_transaction= \DB::table('ltech_finish_goods_transactions')
                ->leftjoin('ltech_finish_goods_stocks','ltech_finish_goods_transactions.finish_goods_id','=','ltech_finish_goods_stocks.finish_goods_id')
                ->where('ltech_finish_goods_transactions.finish_goods_transaction_type','inwards')
                ->whereBetween('ltech_finish_goods_transactions.finish_goods_transaction_date', [$search_from,$search_to])
                ->where('ltech_finish_goods_transactions.cost_center_id',$cost_center)
                ->get();
        }
        return $get_finish_goods_transaction;

      }






















}



