<?php 
defined('BASEPATH') or exit('nothing found');

class Eagle_model extends CI_Model {

    public function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
    }

    public function newotp(){
        return rand(1000 , 9999);
    }

    public function lang_message($str){
        return ($this->lang->line($str));
    }

    public function getSplashScreen(){
        
        $query = $this->db
        ->select(field_id . ' as id,' . field_image .','. field_heading .','. field_description)
        ->where(feild_status , constant_active)
        ->get(table_splash);
        
        $query = $query->result_array();

        foreach($query as $key=>$val){
            $query[$key]['image'] = base_url . $val[field_image];
        }
        return $query;
    }

    public function new_uid(){
        return (bin2hex(openssl_random_pseudo_bytes(16)).'_'.time());
    }

    public function registered($number){
        $query = $this->db
        ->select('uid as id')
        ->where('phone_number', $number)
        ->get(table_user);

        $query = $query->result_array();

        return !empty($query) ? true : false;
    }

    public function do_upload($path, $send_img){
        $resp = function ($data) {
            $data_final = [
                key_status => $data[0],
                key_message => $data[1],
                key_isadd => $data[2],
            ];
            return $data_final;
        };
        $config[key_upload_path]   = './' . $path;
        $config[key_allowed_types] = type_allowed;
        // $config[key_encrypt_name] = TRUE;

        $this->load->library(library_upload, $config);
        $this->upload->initialize($config);

        return (!$this->upload->do_upload($send_img)) ? false : true;
    }

    public function addNumOtp($number, $otp, $uid){

        $userData = array(
            'uid' => $uid,
            'user_type' => 'user_primary',
            'phone_number' => $number
        );
        $otpData = array(
            'user_id' => $uid,
            'otp'=> $otp,
            'uid'=> 'OTP_' . $this->new_uid()
        );
        $otpQuery = $this->db->insert(table_otp, $otpData);
        $userQuery = $this->db->insert(table_user, $userData);
    }

    public function getOtp($number){
        $id = $this->db
        ->select('uid as id')
        ->where('phone_number', $number)
        ->get(table_user);

        $id = $id->result_array();
        $id = $id[0]['id'];

        $otp = $this->db
        ->select('otp')
        ->where('user_id' , $id)
        ->get(table_otp);
        $otp = $otp->result_array();

        return $otp;
    }

    public function completeRegistration($name,$email,$trackingFor,$id,$base_img){
        $trackingForId = $this->db->select('uid as id')->where('tracking_for',$trackingFor)->get(table_tracking_for);

        $trackingForId = $trackingForId->result_array();
        $trackingForId = $trackingForId[0]['id'];

        $userData = array(
            'name' => $name,
            'email' => $email,
            'tracking_for_id' =>  $trackingForId,
            'image' => $base_img
        );

        $setData = $this->db->where('uid' , $id)->update(table_user, $userData); 
        

        return $setData;

    }

    public function validateOtp($number, $otp){
        if(strlen($number) > 10 || strlen($number) < 10 ){
            return null;
        }
        $id = $this->db
        ->select('uid as id')
        ->where('phone_number',$number)
        ->get(table_user);

        $id = $id->result_array();
        $id = $id = $id[0]['id'];

        $otpDb = $this->db
        ->select('otp')
        ->where('user_id' , $id)
        ->get(table_otp);
        $otpDb = $otpDb->result_array();
        $otpDb = $otpDb[0]['otp'];

        if($otpDb == $otp){
            return $id;
        }else{
            return null;
        }
       

        
    }

    public function addSmartCardDetails($name, $user_id, $cardNumber, $deviceId, $class, $age, $numbers, $img){
        
        $smartCardId = 'SMARTCARD_'. $this->new_uid();
        
        $emergencyData = array();

        foreach ($numbers as $key => $val) {
            $row = [
                'uid' => 'EMERGENCY_NUM_'. $this->new_uid(),
                'smart_card_id' => $smartCardId,
                'emergency_contact' => $numbers[$key]
            ];
            array_push($emergencyData, $row);
        }        
        $cardData = array(
            'uid'=> $smartCardId,
            'name'=> $name,
            'user_id'=>$user_id,
            'device_id'=> $deviceId,
            'card_number'=>$cardNumber,
            'class'=> $class,
            'age'=> $age,
            'profile_image'=> $img,
        );

        $device_id = $this->db->select('*')->where('device_id', $deviceId)->get(table_smart_card);
        $device_id = $device_id->num_rows();

        $userExists = $this->db->select('*')->where('uid', $user_id)->get(table_user);
        $userExists = $userExists->num_rows();

        // return $emergencyData;

        if($userExists > 0){
            if($device_id > 0){
               return false;    
            }
            $cardQ = $this->db->insert(table_smart_card, $cardData);
            $emergencyQ = $this->db->insert_batch(table_emergency_numbers, $emergencyData);
            return true;
        }
        return false;
    }
    
    public function userExists($user_id){ 
        $user = $this->db
        ->select('*')
        ->where('uid', $user_id)
        ->get(table_user);
        $user = $user->num_rows();

        if($user > 0){
            return true;
        }else{
            return false;
        }
    }

    public function smartCardExists($smartCardId){
        $smartCard = $this->db
                    ->select('*')
                    ->where('uid', $smartCardId)
                    ->get(table_smart_card);

        $smartCard = $smartCard->num_rows();

        if($smartCard > 0){
            return true;
        }else{
            return false;
        }
    }

    public function keyExists($safeAreaId){
        $key = $this->db
        ->select('*')
        ->where('uid',$safeAreaId)
        ->get(table_safe_area);
        $key = $key->num_rows();

        if($key > 0){
            return true;
        }else{
            return false;
        }
    }

    public function deviceExists($deviceId){
        $key = $this->db
        ->select('*')
        ->where('device_id',$deviceId)
        ->get(table_smart_card);
        $key = $key->num_rows();

        if($key > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getKidsData($user_id){
        $kidData = $this->db
        ->select('uid as id, name, age, class, profile_image, created_at, device_id')
        ->where('user_id', $user_id)
        ->get(table_smart_card);

        $kidDataNumRow = $kidData->num_rows();
        $kidData = $kidData->result_array();

        if($kidDataNumRow > 0){
            foreach($kidData as $key => $val){
                $kidData[$key]['profile_image'] = base_url($val['profile_image']);
            }
            return $kidData;
        }
        return false;
    }

    public function setSafeArea($address,$safeAreaName,$longitude,$latitude, $alertOnExit,$alertOnEntry, $safeAreaRadius, $user_id){
        $alertOnExit = $alertOnExit == 'true' ? true : false;
        $alertOnEntry = $alertOnEntry == 'true' ? true : false;

        $safeAreaData = array(
            'uid' => 'SAFEAREA_'.$this->new_uid(),
            'user_id' => $user_id,
            'safe_area_name' => $safeAreaName,
            'longitude' => $longitude,
            'latitude' => $latitude,
            'alert_on_exit'=> $alertOnExit,
            'alert_on_entry'=> $alertOnEntry,
            'address'=> $address,
            'safe_area_radius'=> $safeAreaRadius
        );
        $insert = $this->db->insert(table_safe_area, $safeAreaData);
        return $safeAreaData;
    }

    public function getUserDetails($user_id){
        $userData = $this->db
        ->select('name , email, image')
        ->where('uid', $user_id)
        ->get(table_user);

        $userDataNumRow = $userData->num_rows();
        $userData = $userData->result_array();

        if($userDataNumRow > 0){
            foreach($userData as $key => $val){
                $userData[$key]['image'] = base_url($val['image']);
            }
            return $userData;
        }else{
            return null;
        }
    }

    public function getSafeArea($user_id){
        $safeAreaData = $this->db
        ->select('uid as id, safe_area_name, address, alert_on_exit, alert_on_entry, safe_area_radius,status')
        ->where('user_id', $user_id)
        ->get(table_safe_area);
        $safeAreaNumRow = $safeAreaData->num_rows();
        $safeAreaData = $safeAreaData->result_array();

        if($safeAreaNumRow > 0){
            foreach($safeAreaData as $key => $val){
                if($safeAreaData[$key]['status'] == 'active'){
                    $safeAreaData[$key]['status'] = true;
                }else{
                    $safeAreaData[$key]['status'] = false;
                }

                if($safeAreaData[$key]['alert_on_exit'] == 1 && $safeAreaData[$key]['alert_on_entry'] == 1){
                    $safeAreaData[$key]['alert_on'] = 'Entry & Exit';
                }
                if($safeAreaData[$key]['alert_on_exit'] == 0 && $safeAreaData[$key]['alert_on_entry'] == 1){
                    $safeAreaData[$key]['alert_on'] = 'Entry';
                }
                if($safeAreaData[$key]['alert_on_exit'] == 1 && $safeAreaData[$key]['alert_on_entry'] == 0){
                    $safeAreaData[$key]['alert_on'] = 'Exit';
                }
                if($safeAreaData[$key]['alert_on_exit'] == 0 && $safeAreaData[$key]['alert_on_entry'] == 0){
                    $safeAreaData[$key]['alert_on'] = 'No action found';
                }
                unset($safeAreaData[$key]['alert_on_exit']);
                unset($safeAreaData[$key]['alert_on_entry']);
            }
            
            return $safeAreaData;
        }else{
            return null;
        }
    }

    public function setSafeAreaStatus($safeAreaId){
        $status = $this->db
        ->select('status')
        ->where('uid',$safeAreaId)
        ->get(table_safe_area);
        $status = $status->result_array();
        $status = $status[0]['status'];
       
        if($status == 'active'){
            $q = $this->db
                        ->set('status','deactive')
                        ->where('uid',$safeAreaId)
                        ->update(table_safe_area);
            return false;
        }else{
            $q = $this->db
                        ->set('status','active')
                        ->where('uid',$safeAreaId)
                        ->update(table_safe_area);
            return true;
        }       
    }

    public function setLocation($smartCardId, $long, $lat){
        $t = time();

        $data = [
            'uid' => 'LOCATION_'.$this->new_uid(),
            'smart_card_id' => $smartCardId,
            'longitude' => $long,
            'latitude' => $lat,
            'created_on' => date("Y-m-d H:i:s",$t)
        ];
        $insertLatLong = $this->db->insert(table_location, $data);
        return $insertLatLong;

    }

    public function getLocation($smartCardId){
        $getData  = $this->db
                    ->select('uid as id , longitude ,latitude, created_on as added_on')
                    ->where('smart_card_id', $smartCardId)
                    ->order_by('created_on','desc')
                    ->get(table_location);

        $getData = $getData->result_array();
        $getData = $getData[0];
        return $getData;
    }

    public function getLocationHistory($smartCardId){
        $getData  = $this->db
                    ->select('uid as id , longitude ,latitude, created_on as added_on')
                    ->where('smart_card_id', $smartCardId)
                    ->order_by('created_on','desc')
                    ->get(table_location);

        $getData = $getData->result_array();
        return $getData;
    }

    public function numberExists($number){
        $number = $this->db
                        ->select('*')
                        ->where('phone_number' , $number)
                        ->get(table_user);

        $rows = $number->num_rows();

        if($rows > 0){
            return true;
        }
        return false;
    }
    
    public function addSecondaryParent($name, $number, $relationship){
        $uid = 'USER_'.$this->new_uid();
        $parentData = [
            'uid' => $uid,
            'name' => $name,
            'phone_number' => $number,
            'relationship' => $relationship,
            'user_type' => 'user_secondary'
        ];
        $otpData = [
            'uid' => 'OTP_'.$this->new_uid(),
            'user_id' => $uid,
            'otp' =>  $this->newotp(),
        ];

        $addUser = $this->db->insert(table_user, $parentData);
        $addOtp = $this->db->insert(table_otp, $otpData);

        if($addOtp && $addUser){
            return true;
        }
        return false;
        


    }

    public function addPackage($price, $validity){
        $pkgData = [
            'uid' => 'PACKAGE_'.$this->new_uid(),
            'price' => $price,
            'validity' => $validity . ' days'
        ];
        $setData = $this->db->insert(table_packages, $pkgData);
        return $setData;
    }

    public function getPackages(){
        $pkgData = $this->db
                        ->select('uid as id , price , validity')
                        ->get(table_packages);

        $pkgData = $pkgData->result_array();

        return $pkgData;
    }
    
    public function getSinglePackage($package_id){
        $pkgData = $this->db
                        ->select('uid as id , price , validity')
                        ->where('uid', $package_id)
                        ->get(table_packages);

        $pkgData = $pkgData->result_array();

        return $pkgData;
    }

    public function isSubscribed($smartCardId){
        $packageActive = $this->db
                                ->select('*')
                                ->where('smart_card_id', $smartCardId)
                                ->where('status', 'active')
                                ->get(table_subscriptions);

        $packageActive = $packageActive->num_rows();

        if($packageActive > 0){
            return true;
        }
        return false;
    }

    public function setSubscription($smartCardId,$package_id){

        $packageVaidity = $this->db
                                ->select('validity')
                                ->where('uid', $package_id)
                                ->get(table_packages);

        $packageVaidity = $packageVaidity->result_array();
        $packageVaidity = $packageVaidity[0]['validity'];
        $currentDate = date("Y-m-d H:i:s", time());
        $expiryDate = strtotime($currentDate . ' + ' .$packageVaidity);
        $expiryDate = date("Y-m-d H:i:s",$expiryDate);

        $subscriptionData =[
            'uid' => 'SUBSCRIPTION_'.$this->new_uid(),
            'smart_card_id' => $smartCardId,
            'package_id' => $package_id,
            'expiry_date' => $expiryDate,
        ];

        $setSubscription = $this->db->insert(table_subscriptions , $subscriptionData);

        return $setSubscription;
    }

    public function setStatus($smartCardId){
        $expiryDate = $this->db
                            ->select('expiry_date')
                            ->where('smart_card_id', $smartCardId)
                            ->where('status', 'active')
                            ->get(table_subscriptions);

        $expiryDate = $expiryDate->result_array();
        $expiryDate = $expiryDate[0]['expiry_date'];
        $currentDate = date("Y-m-d H:i:s", time());


        if(strtotime($expiryDate) < strtotime($currentDate)){
            $setStatus = $this->db
                            ->set('status','deactive')
                            ->where('smart_card_id', $smartCardId)
                            ->where('status', 'active')
                            ->update(table_subscriptions);
            return false;
        }
        return true;
    }

    public function getSubscriptionStatus($smartCardId){
        $status = $this->db
                        ->select('uid as id,expiry_date,status')
                        ->where('smart_card_id', $smartCardId)
                        ->where('status', 'active')
                        ->get(table_subscriptions);
        $status = $status->result_array();
        return $status;
        
        
    }




}
?>