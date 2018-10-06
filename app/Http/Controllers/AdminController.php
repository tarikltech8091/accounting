<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\System;
use App\User;
//use Session;

/*******************************
#
## Admin Controller
#
*******************************/

class AdminController extends Controller
{
    public function __construct(){
       
        $this->page_title = \Request::route()->getName();
        $description = \Request::route()->getAction();
        $this->page_desc = isset($description['desc']) ?  $description['desc']:'';
        \App\System::AccessLogWrite();
    }

    /********************************************
    ## AdminDashboardPage 
    *********************************************/
    public function AdminDashboardPage(){

        $today = date('Y-m-d');
        $today_count=0;
        $weekly_count=0;
        $monthly_count=0;
        $yearly_count=0;

        $today_count=\DB::table('ltech_transactions')
             ->where('created_at','like',$today."%")
             ->get();
             
        $data['today_count']=count($today_count);

        $from = date('Y-m-d')." 00:00:00";
        $last_week = date("Y-m-d", strtotime("-1 week"))." 23:59:59";
        
        $weekly_count=\DB::table('ltech_transactions')
             ->whereBetween('ltech_transactions.created_at',array($last_week,$from))
             ->get();
        $data['weekly_count']=count($weekly_count);



        $last_month = date("Y-m-d", strtotime("-1 month"))." 23:59:59";
        $monthly_count=\DB::table('ltech_transactions')
                     ->whereBetween('ltech_transactions.created_at',array($last_month,$from))
                    ->get();
        $data['monthly_count']=count($monthly_count);


        $last_year= date("Y-m-d", strtotime("-1 year"))." 23:59:59";
        $yearly_count=\DB::table('ltech_transactions')
                    ->whereBetween('ltech_transactions.created_at',array($last_year,$from))
                    ->get();
        $data['yearly_count']=count($yearly_count);

        $data['cost_centers'] = \DB::table('ltech_cost_centers')->get();
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
      
        return \View::make('pages.dashboard.dashboard-admin',$data);
    }



    /********************************************
    ## AdminDashboardAccounts 
    *********************************************/
    public function AdminDashboardAccounts(){

        $today = date('Y-m-d');
        $today_count=0;
        $weekly_count=0;
        $monthly_count=0;
        $yearly_count=0;

        $today_count=\DB::table('ltech_transactions')
             ->where('created_at','like',$today."%")
             ->get();
             
        $data['today_count']=count($today_count);

        $from = date('Y-m-d')." 00:00:00";
        $last_week = date("Y-m-d", strtotime("-1 week"))." 23:59:59";
        
        $weekly_count=\DB::table('ltech_transactions')
             ->whereBetween('ltech_transactions.created_at',array($last_week,$from))
             ->get();
        $data['weekly_count']=count($weekly_count);



        $last_month = date("Y-m-d", strtotime("-1 month"))." 23:59:59";
        $monthly_count=\DB::table('ltech_transactions')
                     ->whereBetween('ltech_transactions.created_at',array($last_month,$from))
                    ->get();
        $data['monthly_count']=count($monthly_count);


        $last_year= date("Y-m-d", strtotime("-1 year"))." 23:59:59";
        $yearly_count=\DB::table('ltech_transactions')
                    ->whereBetween('ltech_transactions.created_at',array($last_year,$from))
                    ->get();
        $data['yearly_count']=count($yearly_count);

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
      
        return \View::make('pages.dashboard.dashboard-accounts',$data);
    }

    /********************************************
    ## AdminDashboardPage 
    *********************************************/
    public function AdminUserManagement(){


            $data['page_title'] = $this->page_title;

            if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
                $tab = $_REQUEST['tab'];
            }else $tab = 'create_user';
            $data['tab']=$tab;

            $data['user_info']=\DB::table('users')->get();
            

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
      
        return \View::make('pages.dashboard.admin-user-management',$data);
    }

    /********************************************
    ## AdminUserRegistration 
    *********************************************/
    public function AdminUserRegistration(){
        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->user_id;
        $rule = [
                'name' => 'Required',
                'user_type' => 'Required',
                'user_role' => 'Required',
                'email' => 'Required',
                'user_mobile' => 'Required',
                'password' => 'required',
                'confirm_password' => 'required|in_array:password',

                ];
        $v = \Validator::make(\Request::all(),$rule);

         if($v->passes()){
            $name_slug = explode(' ', strtolower(\Request::input('name')));
            $name_slug = implode('.', $name_slug);


            if(!empty(\Request::file('user_profile_image'))){

                $image = \Request::file('user_profile_image');
                $img_location=$image->getRealPath();
                $img_ext=$image->getClientOriginalExtension();
                $user_profile_image=\App\Admin::UserImageUpload($img_location, $name_slug, $img_ext);

                $user_profile_image=$user_profile_image;

                $user_new_img = array(
                 'user_profile_image' => $user_profile_image,
                 );

            }
            else{
                $user_profile_image='';
            }


            $users_data = [
            'name' =>\Request::input('name'),
            'name_slug' => $name_slug,
            'email' =>\Request::input('email'),
            'password' =>\Hash::make(\Request::input('password')),
            'user_mobile' =>\Request::input('user_mobile'),
            'user_type' =>\Request::input('user_type'),
            'user_role' =>\Request::input('user_role'),
            'user_profile_image' => $user_profile_image,
            'login_status' =>'0',
            'user_status' =>1,
            'created_at' =>$now,
            'updated_at' =>$now,
            'created_by' =>$user,
            'updated_by' =>$user,
            ];

           
            \DB::table('users')->insert($users_data);

            
            return \Redirect::to('/dashboard/admin/user/management')->with('message',"User Added Successfully!");


         }else return \Redirect::to('/dashboard/admin/user/management')->withErrors($v->messages());
    }

    /********************************************
    ## ChangeUserStatus 
    *********************************************/
    public function ChangeUserStatus($user_id, $status){

            $now=date('Y-m-d H:i:s');
            if (!empty($user_id) && !empty($status)) {

                $update_data=array(
                    'user_status' => $status,
                    'updated_at' => $now,
                    );
                $user_info_update=\DB::table('users')->where('user_id', $user_id)->update($update_data);

                return 1;
            }

        else return \Redirect::to('/dashboard/admin/user/management')->with('message',"Request Wrong Url !");

    }

    /********************************************
    # UserProfile
    *********************************************/
    public function UserProfile(){

        if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
            $tab = $_REQUEST['tab'];
        }else $tab = 'panel_overview';
        $data['tab']=$tab;

        $user_info=\DB::table('users')->where('email', \Auth::user()->email)
        ->first();
        $data['user_info']=$user_info;
        $data['name']=explode(' ', $user_info->name);

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.dashboard.user-profile',$data);
    }




    /********************************************
    ## ProfileUpdate 
    *********************************************/
    public function ProfileUpdate(){

        $user_id=\Auth::user()->id;

        $rules=array(
            'name' => 'Required',
            'email' => 'Required|email',
            'user_mobile' => '',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $now=date('Y-m-d H:i:s');
            $name_slug = explode(' ', strtolower(\Request::input('name')));
            $name_slug = implode('_', $name_slug);

            $user_info=\DB::table('users')->where('user_id', $user_id)->first();

            if(!empty(\Request::file('image_url'))){

                $image = \Request::file('image_url');
                $img_location=$image->getRealPath();
                $img_ext=$image->getClientOriginalExtension();
                $user_profile_image=\App\Admin::UserImageUpload($img_location, $name_slug, $img_ext);

                $user_profile_image=$user_profile_image;

                $user_new_img = array(
                 'user_profile_image' => $user_profile_image,
                 );
                $user_img_update=\DB::table('users')->where('id', $user_id)->update($user_new_img);

            }

            $user_info_update_data=array(
                'name' =>  \Request::input('name'),
                'name_slug' => $name_slug,
                'email' => \Request::input('email'),
                'user_mobile' => empty(\Request::input('user_mobile')) ? $user_info->user_mobile:\Request::input('user_mobile'),
                'updated_at' => $now,
                );

            try{

                $update_user_info=\DB::table('users')->where('id', $user_id)->update($user_info_update_data);
                \App\System::EventLogWrite('update,users',json_encode($user_info_update_data));
                return \Redirect::to('/user/profile')->with('message',"Info Updated Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/user/profile')->with('message',"Info Already Exist !");
            }

        }else return \Redirect::to('/user/profile')->withErrors($v->messages());


    }


    /********************************************
    # UserProfileUpdatePage
    *********************************************/
    public function UserProfileUpdatePage($user_id){

        if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
            $tab = $_REQUEST['tab'];
        }else $tab = 'panel_overview';
        $data['tab']=$tab;

        $user_info=\DB::table('users')->where('user_id', $user_id)
        ->first();
        $data['user_info']=$user_info;
        $data['name']=explode(' ', $user_info->name);

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        return \View::make('pages.dashboard.user-profile-view',$data);
    }

    /********************************************
    ## UserProfileUpdateSubmit 
    *********************************************/
    public function UserProfileUpdateSubmit($user_id){


        $rules=array(
            'name' => 'Required',
            'email' => 'Required|email',
            'user_mobile' => '',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $now=date('Y-m-d H:i:s');
            $name_slug = explode(' ', strtolower(\Request::input('name')));
            $name_slug = implode('_', $name_slug);

            $user_info=\DB::table('users')->where('user_id', $user_id)->first();

            if(!empty(\Request::file('image_url'))){

                $image = \Request::file('image_url');
                $img_location=$image->getRealPath();
                $img_ext=$image->getClientOriginalExtension();
                $user_profile_image=\App\Admin::UserImageUpload($img_location, $name_slug, $img_ext);

                $user_profile_image=$user_profile_image;

                $user_new_img = array(
                 'user_profile_image' => $user_profile_image,
                 );
                $user_img_update=\DB::table('users')->where('user_id', $user_id)->update($user_new_img);

            }

            $user_info_update_data=array(
                'name' =>  \Request::input('name'),
                'name_slug' => $name_slug,
                'email' => \Request::input('email'),
                'user_mobile' => empty(\Request::input('user_mobile')) ? $user_info->user_mobile:\Request::input('user_mobile'),
                'updated_at' => $now,
                );

            try{

                $update_user_info=\DB::table('users')->where('user_id', $user_id)->update($user_info_update_data);
                \App\System::EventLogWrite('update,users',json_encode($user_info_update_data));
                return \Redirect::to('/user/profile/view/id-'.$user_id)->with('message',"Info Updated Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/user/profile/view/id-'.$user_id)->with('errormessage',"Something Wrong");
            }

        }else return \Redirect::to('/user/profile/view/id-'.$user_id)->withErrors($v->messages());


    }



    /********************************************
    ## UserChangePassword 
    *********************************************/
    public function UserChangePassword(){

        $now=date('Y-m-d H:i:s');

        $rules=array(
            'new_password' => 'Required',
            'confirm_password' => 'Required',
            'current_password' => 'Required',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $new_password=\Request::input('new_password');
            $confirm_password=\Request::input('confirm_password');
            
            if($new_password==$confirm_password){

                if (\Hash::check(\Request::input('current_password'), \Auth::user()->password)) {

                    $update_password=array(
                        'password' => bcrypt(\Request::input('new_password')),
                        'updated_at' => $now,
                        );

                    try{
                        $update=\DB::table('users')->where('user_id', \Auth::user()->user_id)->update($update_password);
                        \App\System::EventLogWrite('update,users', 'password changed');

                        return \Redirect::to('/user/profile')->with('message',"Password Updated Successfully !");

                    }catch(\Exception $e){

                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::to('/user/profile')->with('message',"Password Update Failed  !");
                    }

                }else return \Redirect::to('/user/profile?tab=change_password')->with('message',"Current Password Doesn't Match !");

            }else return \Redirect::to('/user/profile?tab=change_password')->with('message',"Password Combination Doesn't Match !");

        }else return \Redirect::to('/user/profile?tab=change_password')->withErrors($v->messages());

    }

    /********************************************
    ## UserProfileUpdatePassword 
    *********************************************/
    public function UserProfileUpdatePassword($user_id){

        $now=date('Y-m-d H:i:s');

        $rules=array(
            'new_password' => 'Required',
            'confirm_password' => 'Required',
            'current_password' => 'Required',
            );

        $v=\Validator::make(\Request::all(), $rules);
        

        if($v->passes()){

            $new_password=\Request::input('new_password');
            $confirm_password=\Request::input('confirm_password');

            $user_info=\DB::table('users')->where('user_id', $user_id)->first();
            
            if($new_password==$confirm_password){

                if (\Hash::check(\Request::input('current_password'), $user_info->password)) {

                    $update_password=array(
                        'password' => bcrypt(\Request::input('new_password')),
                        'updated_at' => $now,
                        );

                    try{
                        $update=\DB::table('users')->where('user_id', $user_info->user_id)->update($update_password);
                        \App\System::EventLogWrite('update,users', 'password changed');

                        return \Redirect::to('/user/profile/view/id-'.$user_id)->with('message',"Password Updated Successfully !");

                    }catch(\Exception $e){

                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::to('/user/profile/view/id-'.$user_id)->with('message',"Password Update Failed  !");
                    }

                }else return \Redirect::to('/user/profile/view/id-'.$user_id.'?tab=change_password')->with('message',"Current Password Doesn't Match !");

            }else return \Redirect::to('/user/profile/view/id-'.$user_id.'?tab=change_password')->with('message',"Password Combination Doesn't Match !");

        }else return \Redirect::to('/user/profile/view/id-'.$user_id.'?tab=change_password')->withErrors($v->messages());

    }

    /********************************************
    # UserProfileDelete
    *********************************************/
    public function UserProfileDelete($user_id){
        if(\Auth::user()->user_type=='admin'){

            $data=\DB::table('users')->where('user_id',$user_id)->delete();
            return \Redirect::to('/dashboard/admin/user/management')->with('message'," USER Deleted Successfully!");
        }else return \Redirect::to('/error/request');
        
    }

    /********************************************
    ## CostCenterPage
    *********************************************/
    public function CostCenterPage(){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        $all_cost=\DB::table('ltech_cost_centers')
                ->OrderBy('updated_at','desc')
                ->paginate(10);
        $all_cost->setPath(url('/dashboard/cost-center'));
        $cost_pagination = $all_cost->render();
        $data['cost_pagination']=$cost_pagination;
        $data['all_cost'] = $all_cost;
        
        return \View::make('pages.dashboard.cost-center',$data);
    }

    /********************************************
    # CostCenterInsert
    *********************************************/
    public function CostCenterInsert(){
        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->user_id;
        $rule = ['cost_center_name' => 'Required',];
        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
            $cost_center_slug = explode(' ', strtolower(\Request::input('cost_center_name')));
            $cost_center_slug = implode('_', $cost_center_slug);
            $cost_center_data = [
            'cost_center_name' =>\Request::input('cost_center_name'),
            'cost_center_slug' => $cost_center_slug,
            'created_at' =>$now,
            'updated_at' =>$now,
            'created_by' =>$user,
            'updated_by' =>$user,
            ];

            try{
                \DB::table('ltech_cost_centers')->insert($cost_center_data);
                \App\System::EventLogWrite('insert,ltech_cost_centers',json_encode($cost_center_data));
                return \Redirect::to('/dashboard/cost-center')->with('message',"Cost Name Added Successfully!");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/cost-center')->with('message',"Info Already Exist !");
            }

        }else return \Redirect::to('/dashboard/cost-center')->withErrors($v->messages());
    }

    /********************************************
    ## CostCenterEditPage
    *********************************************/
    public function CostCenterEditPage($cost_center_id){
        $edit_cost=\DB::table('ltech_cost_centers')
                        ->where('cost_center_id',$cost_center_id)
                        ->first();
                        
        $all_cost=\DB::table('ltech_cost_centers')
                ->OrderBy('updated_at','desc')
                ->paginate(10);
        $all_cost->setPath(url('/dashboard/cost-center'));
        $cost_pagination = $all_cost->render();
        $data['cost_pagination']=$cost_pagination;
        $data['all_cost'] = $all_cost;
        $data['edit_cost'] = $edit_cost;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return \View::make('pages.dashboard.edit-cost-center',$data);

    }

    /********************************************
    # CostCenterUpdate
    *********************************************/
    public function CostCenterUpdate($cost_center_id){

        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->user_id;
        $rule = ['cost_center_name' => 'Required',];
        
        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){

            $update_cost_center_slug = explode(' ', strtolower(\Request::input('cost_center_name')));
            $update_cost_center_slug = implode('_', $update_cost_center_slug);
            $cost_update_data = [
            'cost_center_name' =>\Request::input('cost_center_name'),
            'cost_center_slug' => $update_cost_center_slug,
            'updated_at' =>$now,
            'updated_by' =>$user,
            ];


            try{
                \DB::table('ltech_cost_centers')->where('cost_center_id',$cost_center_id)->update($cost_update_data);

                \App\System::EventLogWrite('update,ltech_cost_centers',json_encode($cost_update_data));
                return \Redirect::to('/dashboard/cost-center')->with('message',"Cost update Successfully!");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/cost-center')->with('message',"Info Already Exist !");
            }


        }else return \Redirect::to('/dashboard/cost-center')->withErrors($v->messages());
    }


    /********************************************
    # CostCenterDelete
    *********************************************/
    public function CostCenterDelete($cost_center_id){

        $transaction_cost_center=\DB::table('ltech_transactions')->where('cost_center_id',$cost_center_id)->get();
        if(!empty($transaction_cost_center) && count($transaction_cost_center)!=0){
            return \Redirect::to('/dashboard/cost-center')->with('errormessage'," Cost center can not deleted, because it has transaction.");
        }else{
            $data=\DB::table('ltech_cost_centers')->where('cost_center_id',$cost_center_id)->delete();

            return \Redirect::to('/dashboard/cost-center')->with('message'," Cost Center Deleted Successfully!");
        }


        
    }

   /********************************************
    ## CompanyPage
    *********************************************/
    public function CompanyPage(){

        if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
            $tab = $_REQUEST['tab'];
        }else $tab = 'company_overview';
        $data['tab']=$tab;
        $company_info=\DB::table('company_details')->latest()->first();
        $data['company_info']=$company_info;
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
       
        return \View::make('pages.company.company-details',$data);
    }


    /********************************************
    ## AdminUserRegistration 
    *********************************************/
    public function CompanyDetailInsert(){
        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->user_id;
        $rule = [
                'company_name' => 'Required',
                'company_address' => 'Required',
                'company_email' => 'Required',
                'company_contact' => 'Required',
                'foundation_date' => 'Required',
                'company_title' => 'Required',
                'company_moto' => '',
                'company_tax_no' => '',
                ];

        $v = \Validator::make(\Request::all(),$rule);

         if($v->passes()){

            $company_id=\Request::input('company_id');

            if($company_id!= null){

                $company_update_name_slug = explode(' ', strtolower(\Request::input('company_name')));
                $company_update_name_slug = implode('_', $company_update_name_slug);

                if(!empty(\Request::file('company_logo'))){

                    $image = \Request::file('company_logo');
                    $img_location=$image->getRealPath();
                    $img_ext=$image->getClientOriginalExtension();
                    $company_image_updates=\App\Admin::CompanyImageUpload($img_location, $company_update_name_slug, $img_ext);
                    $company_update_img = array(
                       'company_logo' => $company_image_updates,
                       );
                    \DB::table('company_details')->where('company_id',$company_id)->update($company_update_img);
                 }

                    $company_update_data = [
                    'company_name' =>\Request::input('company_name'),
                    'company_name_slug' => $company_update_name_slug,
                    'company_email' =>\Request::input('company_email'),
                    'company_address' =>\Request::input('company_address'),
                    'company_tax_no' =>\Request::input('company_tax_no'),
                    'company_moto' =>\Request::input('company_moto'),
                    'foundation_date' =>\Request::input('foundation_date'),
                    'company_contact' =>\Request::input('company_contact'),
                    'company_title' =>\Request::input('company_title'),
                    'updated_at' =>$now,
                    'updated_by' =>$user,
                    ];

                    try{
                        \DB::table('company_details')->where('company_id',$company_id)->update($company_update_data);
                        \App\System::EventLogWrite('update,company_details',json_encode($company_update_data));
                        return \Redirect::to('/dashboard/company/info')->with('message',"Company Details Updated Successfully!");
                    }catch(\Exception $e){

                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/dashboard/company/info')->with('message',"Info Already Exist !");
                    }

            }


            $company_name_slug = explode(' ', strtolower(\Request::input('company_name')));
            $company_name_slug = implode('_', $company_name_slug);
            if(!empty(\Request::file('company_logo'))){

                $image = \Request::file('company_logo');
                $img_location=$image->getRealPath();
                $img_ext=$image->getClientOriginalExtension();
                $company_logo=\App\Admin::CompanyImageUpload($img_location, $company_name_slug, $img_ext);
            }
            else{
                $company_logo='';
            }

            $company_data = [

            'company_name' =>\Request::input('company_name'),
            'company_name_slug' => $company_name_slug,
            'company_email' =>\Request::input('company_email'),
            'company_address' =>\Request::input('company_address'),
            'company_tax_no' =>\Request::input('company_tax_no'),
            'company_moto' =>\Request::input('company_moto'),
            'company_contact' =>\Request::input('company_contact'),
            'company_title' =>\Request::input('company_title'),
            'company_logo' => $company_logo,
            'created_at' =>$now,
            'updated_at' =>$now,
            'created_by' =>$user,
            'updated_by' =>$user,
            ];
           
            

            try{
                \DB::table('company_details')->insert($company_data);

                \App\System::EventLogWrite('insert,company_details',json_encode($company_data));
                return \Redirect::to('/dashboard/company/info')->with('message',"Company Details Added Successfully!");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/company/info')->with('message',"Info Already Exist !");
            }





         }else return \Redirect::to('/dashboard/company/info')->withErrors($v->messages());
    }


    /********************************************
    ## InventoryDashbordPage
    *********************************************/
    public function InventoryDashbordPage(){

        $data['page_title'] = $this->page_title;
        return \View::make('pages.dashboard.dashboard-inventory',$data);
    }
    
    /********************************************
    ## InventoryPage
    *********************************************/
    public function InventoryPage(){

        $data['page_title'] = $this->page_title;
        $stock_inventory_list=\DB::table('ltech_inventory_stocks')
                            ->leftjoin('ltech_item_categories','ltech_inventory_stocks.item_category_id','=','ltech_item_categories.item_category_id')
                            //->leftjoin('ltech_cost_centers','ltech_inventory_stocks.cost_center_id','=','ltech_cost_centers.cost_center_id')
                            ->get();
        $data['stock_inventory_list'] = $stock_inventory_list;        
        return \View::make('pages.dashboard.inventory-dashboard',$data);
    }




    /********************************************
    ## AccessLogListPage
    *********************************************/

    public function AccessLogs(){
        
        
        /*------------------------------------Get Request--------------------------------------------*/
         if(isset($_GET['form_search_date']) && isset($_GET['to_search_date']) || isset($_GET['user_name']) ){

            $form_search_date = $_GET['form_search_date'].' 00:00:00';
            $to_search_date = $_GET['to_search_date'].' 23:59:59';
            if(isset($_GET['user_name'])){
                $user = $_GET['user_name'];
            }else{
                $user=0;
            }


            if($user != 0){

                $access_log_list = \DB::table('access_log')
                                ->whereBetween('access_log.created_at',array($form_search_date,$to_search_date))
                                ->where('access_log.access_user_id',$user)
                                ->leftJoin('users','access_log.access_user_id','=','users.user_id')
                                ->select('access_log.*','users.name','users.user_id')
                                ->orderBy('access_log.created_at','desc')
                                ->paginate(10);

                $access_log_list->setPath(url('/system-admin/access_log-logs'));

                $pagination = $access_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date'],'user_name'=> $_GET['user_name']])->render();
            }else{

                $access_log_list = \DB::table('access_log')
                                    ->whereBetween('access_log.created_at',array($form_search_date,$to_search_date))
                                    ->leftJoin('users','access_log.access_user_id','=','users.user_id')
                                    ->select('access_log.*','users.name','users.user_id')
                                    ->orderBy('access_log.created_at','desc')
                                    ->paginate(10);

                $access_log_list->setPath(url('/system-admin/access-logs'));

                $pagination = $access_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date']])->render();
            }

            $data['pagination'] = $pagination;
            $data['access_log_list'] = $access_log_list;
            

         }

        /*------------------------------------/Get Request--------------------------------------------*/
        else{
            $today = date('Y-m-d');
            $access_log_list=\DB::table('access_log')->where('access_log.created_at','like',$today."%")
                            ->leftJoin('users','access_log.access_user_id','=','users.user_id')
                            ->select('access_log.*','users.name','users.user_id')

                            ->orderBy('access_log.created_at','desc') 
                            ->paginate(10);
            $access_log_list->setPath(url('/system-admin/access-logs'));
            $pagination = $access_log_list->appends(['form_search_date' => $today, 'to_search_date'=> $today])->render();
            
            $data['pagination'] = $pagination;
            $data['access_log_list'] = $access_log_list;
        }
        $data['page_title'] = $this->page_title;

        return \View::make('pages.system-admin.access-log',$data);

    }



    /********************************************
    ## ErrorLogListPage
    *********************************************/

    public function ErrorLogs(){

        /*------------------------------------Get Request--------------------------------------------*/
         if(isset($_GET['form_search_date']) && isset($_GET['to_search_date']) || isset($_GET['user
            _name'])){

            $form_search_date = $_GET['form_search_date'].' 00:00:00';
            $to_search_date = $_GET['to_search_date'].' 23:59:59';
            if(isset($_GET['user_name'])){
                $user = $_GET['user_name'];
            }else{
                $user=0;
            }

            if($user != 0){

                $error_log_list = \DB::table('error_log')
                                    ->whereBetween('error_log.created_at',array($form_search_date,$to_search_date))
                                    ->where('error_log.error_user_id',$user)
                                    ->leftJoin('users','error_log.error_user_id','=','users.user_id')
                                    ->select('error_log.*','users.name','users.user_id')
                                    ->orderBy('error_log.created_at','desc')
                                    ->paginate(10);

                $error_log_list->setPath(url('/system-admin/error-logs'));

                $error_pagination = $error_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date'],'user_name'=> $_GET['user_name']])->render();
            }else{

                $error_log_list = \DB::table('error_log')
                                    ->whereBetween('error_log.created_at',array($form_search_date,$to_search_date))
                                    ->leftJoin('users','error_log.error_user_id','=','users.user_id')
                                    ->select('error_log.*','users.name','users.user_id')
                                    ->orderBy('error_log.created_at','desc')
                                    ->paginate(10);

                $error_log_list->setPath(url('/system-admin/error-logs'));

                $error_pagination = $error_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date']])->render();
            }

            $data['error_pagination'] = $error_pagination;
            $data['error_log_list'] = $error_log_list;

            
            

         }
        /*------------------------------------/Get Request-----------------------------*/
        else{
            $today = date('Y-m-d');
            $error_log_list=\DB::table('error_log')->where('error_log.created_at','like',$today."%")
                            ->leftJoin('users','error_log.error_user_id','=','users.user_id')
                            ->select('error_log.*','users.name','users.user_id')
                            ->orderBy('error_log.created_at','desc')
                            ->paginate(10);
            $error_log_list->setPath(url('/system-admin/error-logs'));
            $error_pagination = $error_log_list->appends(['form_search_date' => $today, 'to_search_date'=> $today])->render();

            $data['error_pagination'] = $error_pagination;
            $data['error_log_list'] = $error_log_list;
        }
        $data['page_title'] = $this->page_title;
                
        return \View::make('pages.system-admin.error-log',$data);
    }






    /********************************************
    ## EventLogListPage
    *********************************************/

    public function EventLogs(){

        /*------------------------------------Get Request--------------------------------------------*/
         if(isset($_GET['form_search_date']) && isset($_GET['to_search_date']) || isset($_GET['user_name'])){

            $form_search_date = $_GET['form_search_date'].' 00:00:00';
            $to_search_date = $_GET['to_search_date'].' 23:59:59';
            if(isset($_GET['user_name'])){
                $user = $_GET['user_name'];
            }else{
                $user=0;
            }



            if($user != 0){

                $event_log_list = \DB::table('event_log')
                              ->whereBetween('event_log.created_at',array($form_search_date,$to_search_date))
                              ->where('event_log.event_user_id',$user)
                              ->leftJoin('users','event_log.event_user_id','=','users.user_id')
                              ->select('event_log.*','users.name','users.user_id')

                              ->orderBy('event_log.created_at','desc')
                              ->paginate(10);

                $event_log_list->setPath(url('/system-admin/event-logs'));
                $event_pagination = $event_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date'], 'user_name'=> $_GET['user_name']])->render();
            }else{

                $event_log_list = \DB::table('event_log')
                                  ->whereBetween('event_log.created_at',array($form_search_date,$to_search_date))
                                  ->leftJoin('users','event_log.event_user_id','=','users.user_id')
                                  ->select('event_log.*','users.name','users.user_id')

                                  ->orderBy('event_log.created_at','desc')
                                  ->paginate(10);

                $event_log_list->setPath(url('/system-admin/event-logs'));
                $event_pagination = $event_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date']])->render();
            }

            $data['event_pagination'] = $event_pagination;
            $data['event_log_list'] = $event_log_list;

            
            

         }
        /*--------------------/Get Request------------------------*/
        else{
            $today = date('Y-m-d');
            $event_log_list=\DB::table('event_log')->where('event_log.created_at','like',$today."%")
                            ->leftJoin('users','event_log.event_user_id','=','users.user_id')
                            ->select('event_log.*','users.name','users.user_id')
                            ->orderBy('event_log.created_at','desc')
                            ->paginate(10);
            $event_log_list->setPath(url('/system-admin/event-logs'));
            $event_pagination = $event_log_list->appends(['form_search_date' => $today, 'to_search_date'=> $today])->render();

            $data['event_pagination'] = $event_pagination;
            $data['event_log_list'] = $event_log_list;
        }
        $data['page_title'] = $this->page_title;
                
        return \View::make('pages.system-admin.event-log',$data);
    }


    /********************************************
    # EventLogsDetails
    *********************************************/
    public function EventLogsDetails($event_id){     

      $data['page_title'] = $this->page_title;

      $event_log_view=\DB::table('event_log')
                    ->where('event_log.event_id', $event_id)
                    ->first();
      $all_data=json_decode($event_log_view->event_data);
      $all_type=explode(',', $event_log_view->event_type);
      $data['all_type']=$all_type;
      $data['all_data']=$all_data;
      $data['event_log_view']=$event_log_view;
      return \View::make('pages.system-admin.ajax-event-log-details',$data);

    }





    /********************************************
    ## AuthLogListPage
    *********************************************/

    public function AuthLogs(){

        /*------------------------------------Get Request--------------------------------------------*/
         if(isset($_GET['form_search_date']) && isset($_GET['to_search_date']) || isset($_GET['user_name'])){

            $form_search_date = $_GET['form_search_date'].' 00:00:00';
            $to_search_date = $_GET['to_search_date'].' 23:59:59';
            if(isset($_GET['user_name'])){
                $user = $_GET['user_name'];
            }else{
                $user=0;
            }

            if($user != 0){

                $auth_log_list = \DB::table('auth_log')->whereBetween('auth_log.created_at',array($form_search_date,$to_search_date))
                                  ->where('auth_log.auth_user_id',$user)
                                  ->leftJoin('users','auth_log.auth_user_id','=','users.user_id')
                                  ->select('auth_log.*','users.name','users.user_id')

                                  ->orderBy('auth_log.created_at','desc')
                                  ->paginate(15);

                $auth_log_list->setPath(url('/system-admin/auth-logs'));

                $auth_pagination = $auth_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date'], 'user_name'=> $_GET['user_name']])->render();
            }else{

                $auth_log_list = \DB::table('auth_log')->whereBetween('auth_log.created_at',array($form_search_date,$to_search_date))
                                  ->leftJoin('users','auth_log.auth_user_id','=','users.user_id')
                                  ->select('auth_log.*','users.name','users.user_id')

                                  ->orderBy('auth_log.created_at','desc')
                                  ->paginate(15);

                $auth_log_list->setPath(url('/system-admin/auth-logs'));

                $auth_pagination = $auth_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date']])->render();
            }

            $data['auth_pagination'] = $auth_pagination;
            $data['auth_log_list'] = $auth_log_list;

            
            

         }
        /*------------------------------------/Get Request--------------------------------------------*/
        else{
            $today = date('Y-m-d');
            $auth_log_list=\DB::table('auth_log')->where('auth_log.created_at','like',$today."%")
                            ->leftJoin('users','auth_log.auth_user_id','=','users.user_id')
                            ->select('auth_log.*','users.name','users.user_id')
                            ->orderBy('auth_log.created_at','desc')
                            ->paginate(15);
            $auth_log_list->setPath(url('/system-admin/auth-logs'));
            $auth_pagination = $auth_log_list->appends(['form_search_date' => $today, 'to_search_date'=> $today])->render();
            $data['auth_pagination'] = $auth_pagination;
            $data['auth_log_list'] = $auth_log_list;
        }
        $data['page_title'] = $this->page_title;
                
        return \View::make('pages.system-admin.auth-log',$data);
    }

















    /***********End of Admin Controlller****************/
}
