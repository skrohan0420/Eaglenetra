<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
//////////////////////////////////////////////////////////
define('rest_controller_path', 'libraries/RestController.php');
//////////////////////////////////////////////////////////
define('header_allow_origin', 'Access-Control-Allow-Origin: *');
define('header_allow_headers', 'Access-Control-Allow-Headers: *');
define('header_allow_methods', "Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, PATCH");
//////////////////////////////////////////////////////////
define('helper_form', 'form');
define('helper_url', 'url');
//////////////////////////////////////////////////////////
define('base_url','http://localhost/eaglenetra/');
/////////////////////////////////////////////////////////
define('lang_app_message', 'app_messages');
//////////////////////////////////////////////////////////
define('content_type_application_json', 'application/json');
//////////////////////////////////////////////////////////
define('library_upload', 'upload');
//////////////////////////////////////////////////////////
define('path_customer_profile_image', 'assets/images/customer_profile_image');
define('type_allowed', 'jpg|jpeg|png');
///////////////////////data_tables///////////////////////////////////
define('table_splash', 'splash_screen');
define('table_otp', 'otp');
define('table_user','user');
define('table_smart_card','smart_card');
define('table_safe_area', 'safe_area');
define('table_last_location','last_location');
define('table_last_user_location' , 'last_user_location');
define('table_location', 'location');
define('table_access_management', 'access_management');
define('table_tracking_for' , 'tracking_for');
define('table_emergency_numbers' , 'emergency_numbers');
define('table_packages', 'packages');
define('table_subscriptions', 'subscriptions');
//////////////////////////////////////////////////////////
define('key_baseurl', 'baseUrl');
define('key_inserted','inserted');
define('key_status', 'status');
define('key_message','message');
define('key_base_url','baseUrl');
define('key_isAdded','isAdded');
define('key_id', 'id');
define('key_heading', 'heading');
define('key_body', 'body');
define('key_cover_image', 'coverImage');
define('key_response', 'responses');
define('key_codes','codes');
define('key_flag','flag');
define('key_get_started_data', 'getStartedData');
define('key_otp', 'otp');
define('key_user_id', 'userId');
define('key_is_sent' , 'isSent');
define('key_upload_path', 'upload_path');
define('key_allowed_types', 'allowed_types');
define('key_description', 'description');
define('key_safe_area_status', 'safe_area_status');
define('key_subscription_data', 'subscription_data');
//////////////////////////////////////////////////////////
define('text_base_url_found_successfully', 'base_url_found_successfully');
define('text_record_found', 'record_found');
define('text_no_record_found', 'no_record_found');
define('text_record_inserted', 'record_inserted');
define('text_no_record_inserted', 'record_not_inserted');
define('text_mobile_number_must_be_ten_digit', 'mobile_number_must_be_ten_digit');
define('text_user_already_exist', 'user_already_exist');
define('text_user_not_exist', 'user_not_exist');
define('text_name_required', 'name_required');
define('text_email_required', 'email_required');
define('text_otp_not_matched', 'otp_not_matched');
define('text_otp_matched', 'otp_matched');
define('text_tracking_required', 'tracking_required');
define('text_registration_successfull', 'registration_successfull');
define('text_registration_failed', 'registration_failed');
define('text_card_number_required', 'card_number_required');
define('text_device_id_required', 'device_id_required');
define('text_class_age_required', 'class_age_required');
define('text_device_exists','text device exists');
define('text_device_added','device added');
define('text_number_required', 'number_required');
define('text_user_exist', 'user_exist');
define('text_user_id_required', 'user_id_required');
define('text_user_id_not_matched', 'user_id_not_matched');
define('text_new_user','new user');
define('text_safe_area_name_required', 'safe_area_name_required');
define('text_longitude_required', 'longitude_required');
define('text_latitude_required', 'latitude_required'); 
define('text_alert_on_required',  'alert_on_required');
define('text_safe_area_radius_required', 'safe_area_radius_required');
define('text_safe_area_address_required' ,'safe_area_address_required');
define('text_safe_area_added','safe_area_added');
define('text_loaction_updated','loaction_updated');
define('text_loaction_not_updated','loaction_not_updated');
define('text_device_id_not_found', 'device_id_not_found');
define('text_loaction_found', 'loaction_found');
define('text_loaction_not_found', 'loaction_not_found');
define('text_new_guardian_added','text_new_guardian_added');
define('text_all_feilds_are_required','text_all_feilds_are_required');
define('text_loaction_inserted', 'text_loaction_inserted');
define('text_loaction_not_inserted', 'text_loaction_not_inserted');
define('text_image_required' , 'image_required');
define('text_status_updated', 'status_updated');
define('text_allready_subscribed', 'allready_subscribed');
define('text_subscribed_successfully' , 'subscribed_successfully');
define('text_cannot_subscribe' , 'cannot_subscribe');
define('text_invalid_card_number' , 'invalid_card_number');
define('text_invalid_relationship' , 'invalid_relationship');
define('text_invalid_price' ,  'invalid_price');
define('text_invalid_validity', 'invalid_validity');
define('text_subscription_data_found', 'subscription_data_found');
define('text_subscription_data_not_found', 'subscription_data_not_found');
define('text_userid_required','user_id_required');
define('text_username_required','username_required');
define('text_userdetails_required','user details_required');
define('text_all_required','all_fields_are_required');
define('text_address_required', 'Address is required');
define('text_details_added' , 'details_added');
define('text_details_not_added' , 'details_not_added');
define('text_subscription_is_valid' , 'subscription_is_valid');
define('text_subscription_expired' , 'subscription_expired');
define('text_user_not_subscribed' ,'user_not_subscribed');
define('text_invalid_pnone_number' , 'invalid_pnone_number');
define('text_image_upload_failed', 'image_upload_failed');
define('text_invalid_email', 'invaild_email');
define('text_invalid_name', 'invalid_name');
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////////
define('http_ok', 200);
define('http_no_content', 204);
define('http_bad_request',400);
define('http_forbidden_error',403);
define('http_not_found',404);
define('http_Conflict',409);
define('http_server_error',500);
define('http_unprocessable_entity',422); 
define('http_failed_dependency',424);
//////////////////////////////////////////////////////////
define('DATA', 'data');
define('HTTP_STATUS', 'http_status');
/////////////////////////get/////////////////////////////////
define('query_param_phone_number', 'phone_number');
define('query_param_otp', 'otp');
/////////////////////////post/////////////////////////////////
define('param_phone_number', 'phone_number');
define('param_otp', 'otp');
define('param_user_name', 'user_name');
define('param_user_email', 'user_email');
define('param_tarcking_for', 'tracking_for');
define('param_name' , 'name');
define('param_relationship','relationship');
define('param_card_number', 'card_number');
define('param_device_id', 'device_id');
define('param_class', 'class');
define('param_age', 'age');
define('param_number', 'number');
define('param_number1', 'number1');
define('param_number2', 'number2');
define('param_number3', 'number3');
define('param_safe_area_name', 'safe_area_name');
define('param_address', 'address');
define('param_longitude', 'longitude');
define('param_latitude', 'latitude');
define('param_safe_area_radius','safe_area_radius');
define('param_alert_on_exit','alert_on_exit');
define('param_alert_on_entry', 'alert_on_entry');
define('param_price', 'price');
define('param_validity', 'validity');
define('text_invalid_coordinates' , 'invalid_coordinates');
define('text_invalid_safe_area_radius', 'invalid_safe_area_radius');
define('text_invalid_address', 'invalid_address');      
//////////////////////////////////////////////////////////
define('file_profile_image', 'profile_image');
//////////////////////////////////////////////////////////
define('filename', 'file_name');
//////////////////////////////////////////////////////////
define('model_eagle', 'Eagle_model');
///////////////////////////////////////////////////////////
define('app_messages_lang','app_messages');
//////////////////////////////////////////////////////////
define('field_status','status');
define('field_type','type');
define('field_type_sarathi','sarathi');
define('field_user_id','user_id');
define('field_id', 'uid');
define('field_image', 'image');
define('field_heading', 'heading');
define('field_body', 'body');
define('field_description', 'description');
define('feild_status','status');
//////////////////////////////////////
define('key_user','user');
define('key_credit','credit');
define('key_is_added','isAdded');
define('key_is_reset','isReset');
define('key_is_submitted','isSubmitted');
define('key_data','data');
define('key_isreset','isReset');
define('key_subscribed', 'subscribed');
define('key_subscription_status' ,'subscription_status');
define('key_encrypt_name' , 'encrypt_name');
///////////////////////////////////////

define('constant_active','active');

