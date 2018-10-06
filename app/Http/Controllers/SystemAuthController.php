<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;


/*******************************
#
## SystemAuth Controller
#
*******************************/

class SystemAuthController extends Controller
{

	public function __construct(){

		$this->page_title = \Request::route()->getName();
        $description = \Request::route()->getAction();
        $this->page_desc = isset($description['desc']) ?  $description['desc']:'';
        \App\System::AccessLogWrite();
	}
	

	/********************************************
    ## LoginPage 
    *********************************************/

    public function LoginPage(){

        $data['page_title'] = $this->page_title;

        if(isset($_GET['box']) && ($_GET['box']=='login'))
            \Session::forget('current_user_info');

        if(\Session::has('current_user_info'))
            $data['current_user_info'] = \Session::get('current_user_info');


        if(\Auth::check()){

            if(!empty(\Auth::user()->user_type)){

                \App\User::LogInStatusUpdate("login");
                
                return \Redirect::to('/dashboard/'.\Auth::user()->user_role.'/'.\Auth::user()->name_slug);

                if(\Auth::user()->user_type=="admin"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/dashboard/admin/'.\Auth::user()->name_slug);

                }
                elseif(\Auth::user()->user_type=="account"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/dashboard/account/'.\Auth::user()->name_slug);

                }

                elseif(\Auth::user()->user_type=="inventory"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/dashboard/inventory/'.\Auth::user()->name_slug);

                }
                
            }else{

                \Auth::logout();

                return \Redirect::to('/login')->with('errormessage',"Whoops, looks like something went wrong.");
            }
            
        }else{

           return \View::make('pages.login',$data);

        }

    }

    /********************************************
    ## AuthenticationCheck 
    *********************************************/

    public function AuthenticationCheck(){
         $rules = [
                    'email' =>'required',
                    'password'=> 'required',
                ];

        $v = \Validator::make(\Request::all(),$rules);


        if($v->passes()){

            $remember = (\Request::has('remember')) ? true : false;
            $credentials = [
                        'email' => \Request::input('email'),
                        'password'=> \Request::input('password'),
                        'user_status'=>1,
                     ];

            if(\Auth::attempt($credentials,$remember)){


                if($remember)
                    \Session::put('current_user_info',\Auth::user());

                if ( \Session::has('pre_login_url') ){ //redirect cureent page after login
         
                    $url = \Session::get('pre_login_url');
                    \Session::forget('pre_login_url');
                   return \Redirect::to($url);
                }

                elseif(\Auth::user()->user_type=="admin"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/dashboard/admin/'.\Auth::user()->name_slug);

                }
                elseif(\Auth::user()->user_type=="account"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/dashboard/account/'.\Auth::user()->name_slug);

                }
                elseif(\Auth::user()->user_type=="inventory"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/dashboard/inventory/'.\Auth::user()->name_slug);

                }
                else{

                    \App\User::LogInStatusUpdate("logout");
                    \Auth::logout();

                    return \Redirect::to('/login')->with('errormessage',"Whoops, looks like something went wrong.");
                }

            }else return \Redirect::to('/login')->with('errormessage',"Incorrect combinations.Please try again.");

       }else return  \Redirect::to('/login')->withInput()->withErrors($v->messages());

    }




    /********************************************
    ## AdminLogout 
    *********************************************/

    public function Logout($name_slug){

        if(\Auth::check()){

            $user_info = \App\User::where('email',\Auth::user()->email)->first();

            if(!empty($user_info) && ($name_slug==$user_info->name_slug)){
                \App\User::LogInStatusUpdate("logout");
                \Auth::logout();
                return \Redirect::to('/login');

            }else return \Redirect::to('/login');

        }else return \Redirect::to('/login')->with('errormessage',"Error logout");
        
    }

    /********************************************
    ## ErrorRequestPage
    *********************************************/
    public function ErrorRequestPage(){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        return \View::make('errors.internal-404',$data);
    }
	
	/********************************************
    ## Page404
    *********************************************/
    public function Page404(){

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;

        return \View::make('errors.404',$data);
    }



    #-------------------END--------------------#

}
