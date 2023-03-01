<?php
defined('BASEPATH') or exit('nothing found');
require APPPATH . rest_controller_path;     
use eaglenetra\RestServer\RestController;

class Eagle extends RestController{

    private $googleApiUrl = "https://maps.google.com/maps/api/";
    private $googleApiGeocodeLibrary = "geocode";
    private $googleApiReturnJsonDataType = "json";

    public function __construct(){
        header(header_allow_origin);
        header(header_allow_methods);

        parent::__construct();
        $this->load->database();   
        $this->load->helper(helper_url);
        $this->lang->load(app_messages_lang);      
    }

    public function new_uid(){
        return (bin2hex(openssl_random_pseudo_bytes(16)).'_'.time());
    }

    private function do_upload($path,$send_img){
        $config[key_upload_path]   = './'.$path;
        $config[key_allowed_types] = '*'; 
        $config[key_encrypt_name] = TRUE;

        $this->load->library(library_upload, $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($send_img)){
            return false;           
        }
        else{
            return true;
        }
    }

    private function initializeEagleModel(){
        $this->load->model(model_eagle);
    }
    
    private function newotp(){
        return rand(1000 , 9999);
    }

    private function final_response($resp,$response){
        $final_response[DATA] = $resp($response);
        $final_response[HTTP_STATUS] = http_ok;
        return $final_response;
    }

    private function lang_message($str){
        return ($this->lang->line($str));
    }

    private function baseUrl(){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_baseurl => $data[2]
            ];
            return $data_final;
        };
        $message = $this->lang_message(text_base_url_found_successfully);
        $response = [true, $message, base_url()];
        return $this->final_response($resp, $response);
    }

    private function splashScreen(){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_data => $data[2]
            ];
            return $data_final;
        };
        $this->initializeEagleModel();
        $splashData = $this->Eagle_model->getSplashScreen();
        $message = (!empty($splashData)) ? $this->lang_message(text_record_found) : $this->lang_message(text_no_record_found);
        $response = [true , $message , $splashData];
        return $this->final_response($resp,$response);
    }

    private function signIn(){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_otp => $data[2],
                key_isSend => $data[3]
            ];
            return $data_final;
        };
        $isOTPsend = false;
        $this->initializeEagleModel();
        $number = $this->input->post(param_phone_number);
		if(!preg_match("/^[6-9]\d{9}$/", $number)){
			$message =  $this->lang_message(text_invalid_pnone_number);    
			$response = [true , $message , "", $isOTPsend];
			return $this->final_response($resp,$response);
		}
		$isregistered = $this->Eagle_model->registered($number);
		if(!$isregistered){
			$otp = strval($this->newotp());
			$uid = 'USER_' . $this->new_uid();
			$this->Eagle_model->addNumOtp($number, $otp, $uid);
		}
		$otp = $this->Eagle_model->getOtp($number);
		$otp = $otp[0][key_otp];
		$message = $isregistered ? $this->lang_message(text_user_already_exist) : $this->lang_message(text_new_user);
		$response = [true , $message , $otp, true];
		return $this->final_response($resp,$response);     
    }

    private function vaidateOtp(){
       
        $resp = function($data){
            $this->initializeEagleModel();
            $number = $this->input->get(query_param_phone_number);
            $otp = $this->input->get(query_param_otp);
            $isRegistered = $this->Eagle_model->isUserExists($number, $otp);
            $data_final = [
                key_status => $data[0],
                key_userStatus => $isRegistered ? 'REGISTERED': 'UNREGISTERED',
                key_message => $data[1],
                key_userId =>  $data[2] == null ? '' :$data[2],
                key_isVerified => $data[3],
                key_details => $data[4]
            ];
            return $data_final;
        };
        $this->initializeEagleModel();
        $number = $this->input->get(query_param_phone_number);
        $otp = $this->input->get(query_param_otp);
        $uid = $this->Eagle_model->getUserId($number, $otp);
        $uid = $uid == null ? "" :$uid ;

		if(!preg_match("/^[6-9]\d{9}$/", $number)){
            $message =  $this->lang_message(text_invalid_pnone_number);    
			$response = [true , $message ,'', false , null];
			return $this->final_response($resp,$response);
		}

        $isRegistered = $this->Eagle_model->isUserExists($number, $otp);
        if(!empty($isRegistered)){

            $details = $this->Eagle_model->registrationDetails($uid);
            if(!empty($details)){
                foreach($details as $key => $val){
                    $details[$key]['image'] = base_url($details[$key]['image']);
                }
            }
            $result = $this->Eagle_model->validateOtp($number,$otp);
            $otp = $this->Eagle_model->getOtp($number); 
            $otp = empty($otp[0]['otp']) ? "" : $otp[0]['otp'];
            $response = [true ,$this->lang_message(text_otp_matched), $uid, true , $details];
            return $this->final_response($resp,$response);
        }      
        $result = $this->Eagle_model->validateOtp($number,$otp);
        $otp = $this->Eagle_model->getOtp($number);
        $otp = empty($otp[0]['otp']) ? "" : $otp[0]['otp'];
        $message = '';
        $message = $result ? $this->lang_message(text_otp_matched) : $this->lang_message(text_otp_not_matched).'. Your otp is '.$otp;
        $isVerified = $result ? true : false;
        $response = [$isVerified , $message ,$uid,  true , null];
        return $this->final_response($resp,$response);
        
        
    }

    private function completeRegistration($id){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_inserted => $data[2]
            ];
            return $data_final;
        };
        $this->initializeEagleModel();
        
        $path = 'assets';

        if($this->do_upload($path, file_profile_image)){

            $img = $path .'/'. $this->upload->data(filename);
            
        }else{
            $message = $this->lang_message(text_image_upload_failed);
            $response = [true, $message, false];
            return $this->final_response($resp,$response);
        }       
        
        $name = $this->input->post(param_user_name);
        $email = $this->input->post(param_user_email);
        $trackingFor = $this->input->post(param_tarcking_for);
        $base_img =  $img;
		if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)){
			$message = $this->lang_message(text_invalid_email);
            $response = [true, $message, false];
            return $this->final_response($resp,$response);
		}
		if(!preg_match('/^[A-Za-z]+([\ A-Za-z]+)*/', $name)){
			$message = $this->lang_message(text_invalid_name);
            $response = [true ,$message,  false ];
            return $this->final_response($resp,$response);   
		}
        if(!empty($name) && !empty($email) && !empty($trackingFor)){
            $setData = $this->Eagle_model->completeRegistration($name, $email, $trackingFor,$id, $base_img);
            $message = $setData ? $this->lang_message(text_registration_successfull) : $this->lang_message(text_registration_failed);
            $response = [true ,$message , $setData ];
            return $this->final_response($resp,$response); 
        }
        $response = [true , $this->lang_message(text_all_feilds_are_required), false];
        return $this->final_response($resp,$response); 
    }

    private function addSmartCardDetails($user_id = null){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_is_added => $data[2]
            ];
            return $data_final;
        };
        $this->initializeEagleModel();

        $path = 'assets';

        if($this->do_upload($path, file_profile_image)){
            $img = $path .'/'. $this->upload->data(filename);
        }else{
            $message = $this->lang_message(text_image_upload_failed);
            $response = [false, $message, null];
            return $this->final_response($resp,$response);  
        }
        $name = $this->input->post(param_name);
        $cardNumber = $this->input->post(param_card_number);
        $deviceId = $this->input->post(param_device_id);
        $class = $this->input->post(param_class);
        $age = $this->input->post(param_age);
        $number1 = $this->input->post(param_number1);
        $number2 = $this->input->post(param_number2);
        $number3 = $this->input->post(param_number1);
		if(!preg_match('/^[A-Za-z]+([\ A-Za-z]+)*/', $name)){
			$message = $this->lang_message(text_invalid_name);
            $response = [true ,$message,  false ];
            return $this->final_response($resp,$response);   
		}
		if(!preg_match("/^[6-9]\d{9}$/", $number1) || !preg_match("/^[6-9]\d{9}$/", $number1) || !preg_match("/^[6-9]\d{9}$/", $number1)){
			$message =  $this->lang_message(text_invalid_pnone_number);    
			$response = [true , $message , false];
			return $this->final_response($resp,$response);
		}
		if(strlen($cardNumber) < 10 || strlen($cardNumber) > 10){
			$message =  $this->lang_message(text_invalid_card_number);    
			$response = [true , $message , false];
			return $this->final_response($resp,$response);
		}
        if(!empty($name) && 
           !empty($cardNumber) && 
           !empty($deviceId) && 
           !empty($class) && 
           !empty($age) && 
           (!empty($number1) || 
           !empty($number2) || 
           !empty($number3))){
            $numbers = array(
                'n1' => $number1,
                'n2' => $number2,
                'n3' => $number3
            );
            foreach($numbers as $key => $val ){
                if($numbers[$key] == ''){
                    unset($numbers[$key]);
                }
            }
            $setData = $this->Eagle_model->addSmartCardDetails($name,$user_id, $cardNumber, $deviceId, $class, $age, $numbers, $img);
            $message = $setData ? $this->lang_message(text_details_added) : $this->lang_message(text_device_exists);
            $response = [true ,$message,  $setData ];
            return $this->final_response($resp,$response);     
        }
        $response = [true , $this->lang_message(text_all_feilds_are_required), false];
        return $this->final_response($resp,$response);  
    }

    private function getKidsData($user_id){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_short_details => $data[2]
            ];
            return $data_final;
        };
        $this->initializeEagleModel();
        $userExists = $this->Eagle_model->userExists($user_id);
        if($userExists){
            $kidsData = $this->Eagle_model->getKidsData($user_id);
            $response = [true , $this->lang_message(text_user_exist), $kidsData];
            return $this->final_response($resp,$response);
        }
        $response = [true , $this->lang_message(text_user_not_exist), null];
        return $this->final_response($resp,$response);          
    }

    private function setSafeArea($user_id, $smartCardId){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_is_saved => $data[2]
            ];
            return $data_final;
        };
        $this->initializeEagleModel();
        $userExists = $this->Eagle_model->userExists($user_id);
        $smartCardExists = $this->Eagle_model->smartCardExists($smartCardId );
        if($userExists && $smartCardExists){
  
            $safeAreaName = $this->input->post(param_safe_area_name);
            $longitude = $this->input->post(param_longitude);
            $latitude = $this->input->post(param_latitude);
            $address = $this->input->post(param_address);
            $safeAreaRadius = $this->input->post(param_safe_area_radius);
            $alertOnExit = $this->input->post(param_alert_on_exit);
            $alertOnEntry = $this->input->post(param_alert_on_entry);

            if((empty($alertOnEntry) && empty($alertOnExit)) || ($alertOnEntry == "false" && $alertOnExit == "false")){
                $message = $this->lang_message(text_alert_status_not_given);
				$response = [true ,$message,  false ];
				return $this->final_response($resp,$response);   
            }
			if(!preg_match('/^[A-Za-z]+([\ A-Za-z]+)*/', $safeAreaName)){
				$message = $this->lang_message(text_invalid_name);
				$response = [true ,$message,  false ];
				return $this->final_response($resp,$response);   
			}
			if(empty($address)){
				$message = $this->lang_message(text_invalid_address);
				$response = [true ,$message,  false ];
				return $this->final_response($resp,$response);   
			}
			if(!preg_match('/^-?[0-9999.]{0,999}(?:\.[0-9999]{1,9999})?$/', $latitude) || 
				!preg_match('/^-?[0-9999.]{0,999}(?:\.[0-9999]{1,9999})?$/', $longitude)){
				$message = $this->lang_message(text_invalid_coordinates);
				$response = [true ,$message,  false ];
				return $this->final_response($resp,$response);
			}
			if(empty($safeAreaRadius)){
				$message = $this->lang_message(text_invalid_safe_area_radius);
				$response = [true ,$message,  false ];
				return $this->final_response($resp,$response);
			}
			if(	!empty($safeAreaName) &&
				!empty($longitude) &&
				!empty($latitude) &&
				!empty($safeAreaRadius) &&
				!empty($address)){
                $result = $this
                        ->Eagle_model
                        ->setSafeArea($address,$safeAreaName,$longitude,$latitude,$alertOnExit,$alertOnEntry,$safeAreaRadius,$user_id,$smartCardId);
                $response = [true , $this->lang_message(text_safe_area_added), true];
                return $this->final_response($resp,$response);
            }
            $response = [true , $this->lang_message(text_all_feilds_are_required), false];
            return $this->final_response($resp,$response);  
            
        }
        $response = [true , $this->lang_message(text_user_not_exist), false];
        return $this->final_response($resp,$response);
    }

    private function getUserDetails($user_id){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_shortprofile => $data[2]
            ];
            return $data_final;
        };
        $this->initializeEagleModel();
        $userExists = $this->Eagle_model->userExists($user_id);
        if($userExists){
            $userData = $this->Eagle_model->getUserDetails($user_id);
            $response = [true , $this->lang_message(text_record_found), $userData[0] ];
            return $this->final_response($resp,$response);
        }
        $response = [true , $this->lang_message(text_user_not_exist), null];
        return $this->final_response($resp,$response);
        
    }

    private function getSafeArea($user_id, $smartCardId){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_area_details => $data[2]
            ];
            return $data_final;
        };
        $this->initializeEagleModel();

        $userExists = $this->Eagle_model->userExists($user_id);
        $smartCardExists = $this->Eagle_model->smartCardExists($smartCardId);

        if($userExists && $smartCardExists){
            $safeAreaData = $this->Eagle_model->getSafeArea($user_id,$smartCardId);
            if(empty($safeAreaData)){
                $response = [true , $this->lang_message(text_no_safe_area_found), null];
                return $this->final_response($resp,$response);
            }
            $response = [true , $this->lang_message(text_user_exist), $safeAreaData];
            return $this->final_response($resp,$response);
        }
        $response = [true , $this->lang_message(text_user_not_exist), null];
        return $this->final_response($resp,$response);        
    }

    private function setSafeAreaStatus($safeAreaId){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_isActivated => $data[2]
            ];
            return $data_final;
        };
        $this->initializeEagleModel();
 
        $isActive = $this->Eagle_model->setSafeAreaStatus($safeAreaId); 

        if($isActive == "notUpdated"){
            $response = [true , $this->lang_message(text_invalid_id), false];
            return $this->final_response($resp,$response);
        }
        if($isActive == "active"){
            $response = [true , $this->lang_message(text_status_updated), true];
            return $this->final_response($resp,$response);
        }       
        if($isActive == "deactive"){
            $response = [true , $this->lang_message(text_status_updated), false];
            return $this->final_response($resp,$response);
        } 
    }

    private function setLocation($smartCardId){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_inserted => $data[2]
            ];
            return $data_final;
        };
        $this->initializeEagleModel();
		$long = $this->input->post('long');
		$lat = $this->input->post('lat');
		if(empty($long) || empty($lat)){
			$message = $this->lang_message(text_invalid_coordinates);
			$response = [true ,$message,  false ];
			return $this->final_response($resp,$response);
		}
		if(!preg_match('/^-?[0-9999.]{0,999}(?:\.[0-9999]{1,9999})?$/', $lat) || 
			!preg_match('/^-?[0-9999.]{0,999}(?:\.[0-9999]{1,9999})?$/', $long)){
			$message = $this->lang_message(text_invalid_coordinates);
			$response = [true ,$message,  false ];
			return $this->final_response($resp,$response);
		}

        $smartCardExists = $this->Eagle_model->smartCardExists($smartCardId );
        if($smartCardExists){
           
            $setData = $this->Eagle_model->setLocation($smartCardId,$long,$lat);
            $response = [true, $this->lang_message(text_loaction_inserted),true];
            return $this->final_response($resp,$response);
        }
        $response = [true, $this->lang_message(text_loaction_not_inserted),false];
        return $this->final_response($resp,$response);
    }

    private function getLocation($smartCardId){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_data => $data[2]
            ];
            return $data_final;
        };
        $this->initializeEagleModel();

        $smartCardExists = $this->Eagle_model->smartCardExists($smartCardId);

        if($smartCardExists){
            $latLong = $this->Eagle_model->getLocation($smartCardId);
            $response = [true, $this->lang_message(text_loaction_found), $latLong];
            return $this->final_response($resp,$response);
        }
        $response = [true, $this->lang_message(text_loaction_not_found), null];
        return $this->final_response($resp,$response);        
    }

    private function getLocationHistory($smartCardId){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_data => $data[2]
            ];
            return $data_final;
        };
        $this->initializeEagleModel();

        $smartCardExists = $this->Eagle_model->smartCardExists($smartCardId);

        if($smartCardExists){
            $latLong = $this->Eagle_model->getLocationHistory($smartCardId);
            // print_r($latLong);
            // die();
            $response = [true, $this->lang_message(text_loaction_found), $latLong];
            return $this->final_response($resp,$response);  
        }
        $response = [true, $this->lang_message(text_loaction_not_found), null];
        return $this->final_response($resp,$response);  
        
    }

    private function getLocationBetweenTime($smartCardId, $date, $startTime, $endTime){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_latlongData => $data[2]
            ];
            return $data_final;
        };
        $this->initializeEagleModel();

        $smartCardExists = $this->Eagle_model->smartCardExists($smartCardId);

        if($smartCardExists){
            $locationDetails = $this->Eagle_model->getLocationBetweenTime($smartCardId, $date, $startTime, $endTime);
            if(empty($locationDetails)){
                $response = [true, $this->lang_message(text_loaction_not_found), []];
                return $this->final_response($resp,$response);  
            }
            $response = [true, $this->lang_message(text_loaction_found), $locationDetails];
            return $this->final_response($resp,$response);   
        }
        $response = [true, $this->lang_message(text_loaction_not_found), []];
        return $this->final_response($resp,$response);  

    }

    private function addSecondaryParent($user_id){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_isAdded => $data[2]
            ];
            return $data_final;            
        };

        $this->initializeEagleModel();
        
        $path = 'assets';

        if($this->do_upload($path, file_profile_image)){

            $img = $path .'/'. $this->upload->data(filename);
            
        }else{
            $message = $this->lang_message(text_image_upload_failed);
            $response = [true, $message, false];
            return $this->final_response($resp,$response);
        }

        $this->initializeEagleModel(); 
        $number = $this->input->post(param_number);
        $name = $this->input->post(param_name);
        $relationship = $this->input->post(param_relationship);
        $userExists =  $this->Eagle_model->userExists($user_id);
        $numberExists = $this->Eagle_model->numberExists($number);
        $base_img =  $img;
		if(!preg_match("/^[6-9]\d{9}$/", $number)){
			$message =  $this->lang_message(text_invalid_pnone_number);    
			$response = [true , $message , false];
			return $this->final_response($resp,$response);
		}
		if(!preg_match('/^[A-Za-z]+([\ A-Za-z]+)*/', $relationship)){
			$message = $this->lang_message(text_invalid_relationship);
            $response = [true ,$message,  false ];
            return $this->final_response($resp,$response);   
		}
		if(!preg_match('/^[A-Za-z]+([\ A-Za-z]+)*/', $name)){
			$message = $this->lang_message(text_invalid_name);
            $response = [true ,$message,  false ];
            return $this->final_response($resp,$response);   
		}
        if(!$userExists){
            $response = [true, $this->lang_message(text_user_not_exist),false];
            return $this->final_response($resp,$response);
        }
        if(empty($name) || empty($number) || empty($relationship)){
            $response = [true, $this->lang_message(text_all_feilds_are_required),false];
            return $this->final_response($resp,$response);
        }  
        if($numberExists){
            $response = [true, $this->lang_message(text_user_already_exist),false];
            return $this->final_response($resp,$response);
        }
        $addDetails = $this->Eagle_model->addSecondaryParent($user_id,$name, $number, $relationship,$base_img);
        $message = $addDetails ? $this->lang_message(text_details_added) : $this->lang_message(text_details_not_added);
        $response = [true,$message,$addDetails];
        return $this->final_response($resp,$response);
    }
    
    private function getSecondaryParent($user_id){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_accessDetails  => $data[2]
            ];
            return $data_final;            
        };

        $this->initializeEagleModel();
        $userExists = $this->Eagle_model->userExists($user_id);

        if($userExists){
            $data = $this->Eagle_model->getSecondaryParent($user_id);
            $message = empty($data) ? $this->lang_message(text_no_record_found) : $this->lang_message(text_record_found); 
            $data = empty($data) ? false : $data;
            $response = [true,$message,$data];
            return $this->final_response($resp,$response);           
        }
        $response = [true, $this->lang_message(text_user_not_exist),false];
        return $this->final_response($resp,$response);
    }

    private function addPackage(){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_inserted => $data[2]
            ];
            return $data_final;            
        };
        $this->initializeEagleModel(); 
        $price = $this->input->post(param_price);
        $validity = $this->input->post(param_validity);
		if(!preg_match('/^[0-9]*$/',$price)){
			$message = $this->lang_message(text_invalid_price);
            $response = [true, $message, false];
            return $this->final_response($resp,$response);
		}
		if(!preg_match('/^[0-9]*$/',$validity)){
			$message = $this->lang_message(text_invalid_validity);
            $response = [true, $message, false];
            return $this->final_response($resp,$response);
		}
        if(!empty($price) && !empty($validity)){
            $setData = $this->Eagle_model->addPackage($price, $validity);
            $message = $setData ? $this->lang_message(text_details_added) : $this->lang_message(text_details_not_added);
            $response = [true, $message, true];
            return $this->final_response($resp,$response);
        }
        $response = [true, $this->lang_message(text_all_feilds_are_required),false];
        return $this->final_response($resp,$response);
    }

    private function getPackages(){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_data => $data[2]
            ];
            return $data_final;            
        };
        $this->initializeEagleModel();
        $getData = $this->Eagle_model->getPackages();
        
        if(empty($getData)){
            $response = [true, $this->lang_message(text_no_package_found), []];
            return $this->final_response($resp,$response);
        }
        $response = [true, $this->lang_message(text_record_found), $getData];
        return $this->final_response($resp,$response);
    }

    private function getSinglePackage($package_id){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_data => $data[2]
            ];
            return $data_final;            
        };
        $this->initializeEagleModel();
        $getData = $this->Eagle_model->getSinglePackage($package_id);

        if($getData){
            $message = $getData ? $this->lang_message(text_record_found) : $this->lang_message(text_no_record_found);
            $response = [true, $message, $getData];
            return $this->final_response($resp,$response);
        }
        $response = [true, $this->lang_message(text_no_record_found),false];
        return $this->final_response($resp,$response);

    }
    
    private function setSubscription($smartCardId,$package_id){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_subscribed => $data[2]
            ];
            return $data_final;            
        };
        $this->initializeEagleModel();

        $isSubscribed = $this->Eagle_model->isSubscribed($smartCardId);

        if($isSubscribed){
            $response = [true, $this->lang_message(text_allready_subscribed),true];
            return $this->final_response($resp,$response);
        }
        $setSubscription = $this->Eagle_model->setSubscription($smartCardId,$package_id);
        if($setSubscription){
            $response = [true, $this->lang_message(text_subscribed_successfully) , true];
            return $this->final_response($resp,$response);
        }
        $response = [true, $this->lang_message(text_cannot_subscribe) , false];
        return $this->final_response($resp,$response);        
    }

    private function setSubscriptionStatus($smartCardId){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_subscription_status => $data[2]
            ];
            return $data_final;            
        };
        $this->initializeEagleModel();
        $isSubscribed = $this->Eagle_model->isSubscribed($smartCardId);
        if($isSubscribed){
            $setStatus = $this->Eagle_model->setStatus($smartCardId);
            $message = $setStatus ? $this->lang_message(text_subscription_is_valid) : $this->lang_message(text_subscription_expired);
            $response = [true, $message , $setStatus];
            return $this->final_response($resp,$response);
        }
        $response = [true, $this->lang_message(text_user_not_subscribed), false];
        return $this->final_response($resp,$response); 
    }

    private function getSubscriptionStatus($smartCardId){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_subscription_data => $data[2]
            ];
            return $data_final;            
        };
        $this->initializeEagleModel();
        $smartCardExists = $this->Eagle_model->smartCardExists($smartCardId);

        if($smartCardExists){
            $subStatus = $this->Eagle_model->getSubscriptionStatus($smartCardId);
            $message =  $subStatus ? $this->lang_message(text_subscription_data_found):$this->lang_message(text_subscription_data_not_found);
            $response = [true, $message , $subStatus];
            return $this->final_response($resp,$response);
        }
        $message = $this->lang_message(text_user_not_exist);
        $response = [true, $message , $smartCardExists];
        return $this->final_response($resp,$response);
         
    }

    private function latLngToAddress($lat, $long){ 
        $resp = function($data){
            $data_final = [
                key_status              => $data[0],
                key_message             => $data[1],
                key_location_details    => $data[2]
            ];
            return $data_final;            
        };
        $googleApiUrl               = $this->googleApiUrl;
        $googleApiLibrary           = $this->googleApiGeocodeLibrary;
        $googleApiReturnDataType    = $this->googleApiReturnJsonDataType;       

        $url            = "{$googleApiUrl}{$googleApiLibrary}/{$googleApiReturnDataType}?latlng={$lat},{$long}&key=".MAP_API_KEY;
        $geocode        = @file_get_contents($url);
        
        $json           = ($geocode) ? json_decode($geocode) : (object)[];

        $status         = property_exists($json, 'status') ? $json->status : 'INVALID_REQUEST';
        $results        = property_exists($json, 'results') ? $json->results : [];

        $successMsg     = ucwords("address found");
        $errorMsg       = ucwords("something went wrong");
        $locationDetails = "INVALID_REQUEST";
        
        if(empty($results)){
            $response = [true, $errorMsg, $locationDetails];
            return $this->final_response($resp,$response);  
        }
        
        if(!array_key_exists(0, $results)){
            $response = [true, $errorMsg, $locationDetails];
            return $this->final_response($resp,$response);  
        }

        $results = (array)$results[0];
        $address = (array_key_exists('formatted_address', $results)) ? $results['formatted_address'] : $locationDetails;

        $response = ($status == "OK") ? [true, $successMsg, $address] : [true, $errorMsg, $locationDetails];
        
        return $this->final_response($resp,$response);        
    }

    private function getDeviceDetails(){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_data => $data[2]
            ];
            return $data_final;            
        };
        $posId = $this->input->get('posId');

        if(!empty($posId)){
            $this->initializeEagleModel();
            $deviceDetails = $this->Eagle_model->getDeviceDetails($posId);
            // print_r($deviceDetails);
            if(!$deviceDetails){
                $response = [true, 'Position id not found' , ''];
                return $this->final_response($resp,$response);
            }
            $lat = $deviceDetails['lat'];
            $long = $deviceDetails['long']; 
            $address = $this->latLngToAddress($lat, $long);

            // $deviceDetails['devicedate'] = substr($deviceDetails['devicedate'],  0, strpos($deviceDetails['devicedate'], " "));
            $deviceDetails['devicedate'] = strtotime($deviceDetails['devicedate']);
            $deviceDetails['devicedate'] = date("jS F Y", $deviceDetails['devicedate']);
            $deviceDetails['devicetime'] = substr($deviceDetails['devicetime'], 11, strpos($deviceDetails['devicetime'], " "));
            $deviceDetails['devicetime'] = date('h:i a', strtotime($deviceDetails['devicetime']));
            $deviceDetails['devicelocation'] = $address['data']['locationDetails'];
            $deviceDetails['batteryperformance'] = "";
            $deviceDetails['condition'] = "";

            unset($deviceDetails['long']);
            unset($deviceDetails['lat']);
            $response = [true, 'true' , $deviceDetails];
            return $this->final_response($resp,$response);
        }
        $response = [true, 'Wrong position id given' , []];
        return $this->final_response($resp,$response);
    }

    private function getSupportDetails(){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_data => $data[2]
            ];
            return $data_final;            
        };        

        $this->initializeEagleModel();

        $data = $this->Eagle_model->getSupportDetails();

        $message = empty($data) ? $this->lang_message(text_no_record_found) : $this->lang_message(text_record_found); 
        $data = empty($data) ? false : $data;
        $response = [true,$message,$data[0]];
        return $this->final_response($resp,$response);

    }
    
    private function getKidsDetails($user_id){
        $resp = function($data){
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_short_details => $data[2]
            ];
            return $data_final;
        };

        $this->initializeEagleModel();

        
        $userExists = $this->Eagle_model->userExists($user_id);
        if($userExists){
            $data = $this->Eagle_model->registrationDetails($user_id);

            foreach($data as $key => $val){
                $latLong = $this->Eagle_model->getLocation($data[$key]['smartCardId']);
                if($latLong){
                    $latLong['postionalTime'] = $latLong['added_on'];
                    $latLong['posId'] = $latLong['id'];
                    $latLong['latLong']['lat'] = (double)$latLong['latitude'];
                    $latLong['latLong']['lng'] = (double)$latLong['longitude'];



                    unset($latLong['latitude']);
                    unset($latLong['longitude']);
                    unset($latLong['added_on']);
                    unset($latLong['id']);
                }
                
                

                $data[$key]['latLong'] =  $latLong;
                $data[$key]['image'] = base_url($data[$key]['image']);
            }

            $response = [true, $this->lang_message(text_loaction_found),$data];
            return $this->final_response($resp,$response);
        }
        $response = [true,$this->lang_message(text_loaction_not_found),false];
        return $this->final_response($resp,$response);


    }





















    public function getSupportDetails_get(){
        $response = $this->getSupportDetails();
        $this->response($response[DATA], $response[HTTP_STATUS]);
    } 
    
    public function  baseUrl_get(){
        $response = $this->baseUrl();
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function  getSubscriptionStatus_get($smartCardId){
        $response = $this->getSubscriptionStatus($smartCardId);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }
    
    public function  setSubscriptionStatus_post($smartCardId){
        $response = $this->setSubscriptionStatus($smartCardId);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function setSubscription_post($smartCardId,$package_id){
        $response = $this->setSubscription($smartCardId,$package_id);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function getSinglePackage_get($package_id){
        $response = $this->getSinglePackage($package_id);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function getPackages_get(){
        $response = $this->getPackages();
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function addPackage_post(){
        $response = $this->addPackage();
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function addSecondaryParent_post($user_id){
        $response = $this->addSecondaryParent($user_id);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function  getSecondaryParent_get(){
        $userId = $this->input->get('userId'); 
        $response = $this->getSecondaryParent($userId);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function getLocation_get($smartCardId){
        $response = $this->getLocation($smartCardId);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function getLocationHistory_get($smartCardId){
        $response = $this->getLocationHistory($smartCardId);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function setLocation_post($smartCardId){
        $response = $this->setLocation($smartCardId);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function setSafeAreaStatus_post(){
        $safeAreaId = $this->input->post('safeAreaId');

        $response = $this->setSafeAreaStatus($safeAreaId);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function getSafeArea_get($user_id, $smartCardId){
        $response = $this->getSafeArea($user_id, $smartCardId);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function getUserDetails_get($user_id){
        $response = $this->getUserDetails($user_id);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function setSafeArea_post($user_id, $smartCardId){
        $response = $this->setSafeArea($user_id, $smartCardId);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function getKidsData_get($user_id){
        $response = $this->getKidsData($user_id);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function addSmartCardDetails_post($user_id){
        $response = $this->addSmartCardDetails($user_id);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function validateOtp_get(){
        $response = $this->vaidateOtp();
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function completeRegistration_post($id){
        $response = $this->completeRegistration($id);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function signIn_post(){
        $response = $this->signIn();
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }
   
    public function splashScreen_get(){
        $response = $this->splashScreen();
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }


    public function latlong_address_get(){
        $lat      = (double)$this->input->get('lat');
        $long     = (double)$this->input->get('long');
        $response = $this->latLngToAddress($lat, $long);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function getDeviceDetails_get(){
        $response = $this->getDeviceDetails();
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }

    public function getLocationBetweenTime_get(){
        $smartCardId = $this->input->get('smartCardId');   
        $startTime   = $this->input->get('startTime');   
        $endTime     = $this->input->get('endTime');   
        $date        = $this->input->get('date');

        $startTime = $startTime .":00";
        $endTime   = $endTime .":00";
        $response  = $this->getLocationBetweenTime($smartCardId,$date, $startTime, $endTime);

        $this->response($response[DATA], $response[HTTP_STATUS]);

    }

    public function getKidsDetails_get(){
        $user_id = $this->input->get('userId');

        $response = $this->getKidsDetails($user_id);
        $this->response($response[DATA], $response[HTTP_STATUS]);
    }



}
?>
