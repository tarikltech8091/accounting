<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    
	/********************************************
    ## UserImageUpload
    *********************************************/

	public static function UserImageUpload($img_location, $name_slug, $img_ext){

		$filename  = $name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext;

		/*directory create*/
		if (!file_exists('assets/images/userprofile/'))
		   mkdir('assets/images/userprofile/', 0777, true);

		$path = public_path('assets/images/userprofile/' . $filename);
		\Image::make($img_location)->resize(150, 150)->save($path);

		/*directory create*/
		if (!file_exists('assets/images/userprofile/small-icon/'))
		   mkdir('assets/images/userprofile/small-icon/', 0777, true);

		$path2 = public_path('assets/images/userprofile/small-icon/' . $filename);
		\Image::make($img_location)->resize(50, 50)->save($path2);

		$user_profile_image='assets/images/userprofile/'.$filename;
		return $user_profile_image;
	}


	/********************************************
    ## CompanyImageUpload
    *********************************************/

	 public static function CompanyImageUpload($img_location, $company_name_slug, $img_ext){

	  $filename  = $company_name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext;

	  /*directory create*/
		if (!file_exists('assets/images/company/'))
		   mkdir('assets/images/company/', 0777, true);


	  $path = public_path('assets/images/company/' . $filename);
	  \Image::make($img_location)->resize(117, 30)->save($path);

	  $company_logo='assets/images/company/'.$filename;
	  return $company_logo;
	 }

	
}
