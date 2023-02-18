<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Eagle';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;




$route['baseurl']                           = 'eagle/baseUrl';
$route['splashdata']                        = 'eagle/splashScreen';
$route['signin']                            = 'eagle/signIn';
$route['otp']                               = 'eagle/validateOtp';
$route['smartcard/(:any)/insert']           = 'eagle/addSmartCardDetails/$1';
$route['smartcard/(:any)/show']             = 'eagle/getKidsData/$1';
$route['user/(:any)/show']                  = 'eagle/getUserDetails/$1';
$route['user/(:any)/insert']                = 'eagle/completeRegistration/$1';
$route['user/(:any)/secondary/insert']      = 'eagle/addSecondaryParent/$1';
$route['user/(:any)/(:any)/safearea/show']  = 'eagle/getSafeArea/$1/$2';
$route['user/(:any)/(:any)/safearea/insert']= 'eagle/setSafeArea/$1/$2';
$route['user/safearea/(:any)/status']       = 'eagle/setSafeAreaStatus/$1';
$route['smartcard/(:any)/location/insert']  = 'eagle/setLocation/$1';
$route['smartcard/(:any)/location/show']    = 'eagle/getLocation/$1';
$route['device/details']                    = 'eagle/getDeviceDetails';
$route['smartcard/(:any)/location/history'] = 'eagle/getLocationHistory/$1';
$route['location/address']                  = 'eagle/latlong_address';
$route['subscription/(:any)/buy/(:any)']    = 'eagle/setSubscription/$1/$2';
$route['subscription/(:any)/status/update'] = 'eagle/setSubscriptionStatus/$1';
$route['subscription/(:any)/status/show']   = 'eagle/getSubscriptionStatus/$1';
$route['package/(:any)']                    = 'eagle/getSinglePackage/$1';


// --------- NOT WORKING --------- //
//   | | | | | | | | | | | | | |   //
//  \:/:\:/:\:/:\/:\:/:\:/:\:/:\:/ //


// $route['package/insert']                    = 'eagle/addPackage';
// $route['package/show']                      = 'eagle/getPackages';








?>