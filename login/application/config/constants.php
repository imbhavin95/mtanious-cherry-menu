<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', true);

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
defined('FILE_READ_MODE') or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
 */
defined('FOPEN_READ') or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

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
defined('EXIT_SUCCESS') or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

//Website name
define('WEBNAME', 'Cherrymenu');

define('ADMIN', 'admin');
define('SUB_ADMIN', 'sub_admin');
define('RESTAURANT', 'restaurant');
define('USERS', 'user');
define('STAFF', 'staff');
define('WAITER', 'waiter');

// Tables Name
define('TBL_USERS', 'users');
define('TBL_MENUS', 'menus');
define('TBL_ITEMS', 'items');
define('TBL_ITEM_DETAILS', 'item_details');
define('TBL_TYPES', 'types');
define('TBL_CATEGORIES', 'categories');
define('TBL_CATEGORY_DETAILS', 'category_details');
define('TBL_HELP_TOPICS', 'help_topics');
define('TBL_ITEM_CLICKS', 'item_clicks');
define('TBL_ITEM_IMAGES', 'item_images');
define('TBL_SETTINGS', 'settings');
define('TBL_FEEDBACKS', 'feedbacks');
define('TBL_CLICKS_TIME', 'clicks_time');
define('TBL_PACKAGES', 'packages');
define('TBL_PACKAGE_DETAILS', 'package_details');
define('TBL_CATEGORY_CLICKS', 'category_clicks');
define('TBL_CATEGORY_CLICKS_TIME', 'category_clicks_time');
define('TBL_ACTIVE_DEVICES', 'device_details');
define('TBL_DELETE_CATEGORY', 'deleted_category');
define('TBL_NEW_CATEGORY', 'new_categories');
define('TBL_DELETE_ITEMS', 'deleted_items');
define('TBL_NEW_ITEMS', 'new_items');
define('TBL_ORDER', 'orders');
define('TBL_ORDER_DETAILS', 'order_details');
define('TBL_ANDROIDVERSION', 'android_app_version');

//Path
define('DEFAULT_USER_IMG', 'public/default_user.png');
define('DEFAULT_IMG', 'public/default.png');
define('DEFAULT_BACKGROUND', 'public/settings/default/');
define('CATEGORIES_IMG', 'public/products/categories/');
define('ITEMS_IMG', 'public/products/items/');
define('ITEMS_MULTIPLE_IMG', 'public/products/item_images/');
define('USER_IMAGES', 'public/users/');
define('RESTAURANT_IMAGES', 'public/restaurants/'); //new
define('USERS_IMAGES', 'public/restaurants/users/'); //new
define('REMEMBER_ME_COOKIE_NAME', 'ecs908f7d89f');
define('BACKGROUND_IMG', 'public/settings/background/');
define('LOGO_IMG', 'public/settings/logo/');
define('LOGO_REST', 'public/settings/default/');

//array
define ("STATUS", json_encode(array('new' => 'New', 'pending' => 'Pending', 'activate' => 'Activate', 'deactivate' => 'Deactivate'))); 
