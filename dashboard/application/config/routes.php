<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "welcome";
$route['404_override'] = '';
$route['resetpass'] = 'welcome/resetpass';
$route['^(message_templates)(/:any)?$'] = 'dashboard/$0';
$route['^(system)(/:any)?$'] = 'dashboard/$0';





// institution routes
$route['^()(/:any)?$'] = 'istitution/$0';
$route['^(update_institution)(/:any)?$'] = 'istitution/$0';
$route['^(delete_institution)(/:any)?$'] = 'istitution/$0';

// service routes
$route['^()(/:any)?$'] = 'services/$0';
$route['^(delete_service)(/:any)?$'] = 'istitution/$0';
$route['^(delete_institution)(/:any)?$'] = 'istitution/$0';
// persons routes
$route['^()(/:any)?$'] = 'persons/$0';
$route['^(change_password)(/:any)?$'] = 'persons/$0';
$route['^(delete_person)(/:any)?$'] = 'persons/$0';
// templates routes
$route['^()(/:any)?$'] = 'templates/$0';

// institution routes

$route['^()(/:any)?$'] = 'pm/$0';
$route['^(pm)(/:any)?$'] = 'pm/$0';
$route['^(read_pm)(/:any)?$'] = 'pm/$0';

// institution routes

$route['^()(/:any)?$'] = 'profile/$0';

// persons routes
$route['^()(/:any)?$'] = 'blacklist/$0';
$route['^(reconfirm_banlist)(/:any)?$'] = 'blacklist/$0';

// file managament
$route['^()(/:any)?$'] = 'files/$0';




$route['^()(/:any)?$'] = 'logout/$0';

$route['^()(/:any)?$'] = 'stattistics/$0';
$route['^(get_all_data)(/:any)?$'] = 'stattistics/$0';
#$route['^(update_service)(/:any)?$'] = 'dashboard/$0';

$route['^(history)(/:any)?$'] = 'history/$0';
$route['^(get_all_data)(/:any)?$'] = 'history/$0';
$route['^(view_history)(/:any)?$'] = 'history/$0';
$route['^(export_history)(/:any)?$'] = 'history/$0';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
