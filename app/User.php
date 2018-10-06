<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $primaryKey = 'user_id';

    /********************************************
    ## LogInStatusUpdate
    *********************************************/
    public static function LogInStatusUpdate($status){

        if(\Auth::checK()){

            if($status=='login')
                $change_status=1;
            else  $change_status=0;
            $now =date('Y-m-d H:i:s');

            $loginstatuschange = \App\User::where('user_id',\Auth::user()->user_id)
            ->update(array('login_status'=>$change_status,'last_login'=>$now,'updated_at'=>$now));

            \App\System::AuthLogWrite($change_status);

            return $loginstatuschange;
        }
        
    }


    public static function ParentModel(){

        return $check = \DB::table('demo AS u1')
        ->where('u1.account_name','accounts payable')
        ->join('demo AS u2','u1.parent_account', '=', 'u2.account_id')
        ->select('u1.account_id AS Account_Code','u1.account_name AS Account_Name','u2.account_id AS Parent_Code','u2.account_name AS Parent_Name')
        ->get();
        
    }
    

    
}
