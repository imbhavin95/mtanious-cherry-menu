    <?php

defined('BASEPATH') or exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';
class Api extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('AUTHORIZATION');
        $this->load->helper('JWT');
          $this->load->model('Menus_model');
          $this->load->model('Categories_model');
        $this->load->model('Items_model');
    }

    /**
     * @api {Post} api/restaurantlogin Restaurant Login
     * @apiName Restaurant Login
     *
     * @apiGroup Login
     *
     * @apiParam {email} email email of the Restaurant.
     * @apiParam {password} password password of the Restaurant.   
     * @apiParam {name} name name of the login device.
     * @apiParam {version} version version of the login device.
     * @apiParam {token} token token of the login device.
     *
     * @apiSuccess {Integer} id id of the Restaurant.
     * @apiSuccess {String} name name of the Restaurant.
     * @apiSuccess {String} email  email of the Restaurant.
     * @apiSuccess {String} role  role of the Restaurant.
     * @apiSuccess {String} token  Users unique access-key for API.
     */
    public function restaurantlogin_post()
    {
        $result = $this->users_model->get_user_detail(['email' => trim($this->input->post('email')), 'role' => RESTAURANT, 'is_deleted' => 0]);

        //print_r($result);
        if ($result) {
            if ($result['is_active'] == 0) 
            {
                $this->set_response([
                    'status' => false,
                    'message' => 'Please verify your mail',
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            } 
            else
            if (!password_verify($this->input->post('password'), $result['password'])) 
            {
                $this->set_response([
                    'status' => false,
                    'message' => 'Invalid Email/Password',
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

            }
            else  if ($result['is_deleted'] == 1) 
            {
                $this->set_response([
                    'status' => false,
                    'message' => 'Your Account has been blocked! Please contact system administrator',
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            } else 
            {
                //check device login limitation
                  $device_info = $this->users_model->sql_select(TBL_ACTIVE_DEVICES, '*', ['where' => ['restaurant_id' => $result['id'], 'is_login' => 1, 'is_deleted' => 0]], ['single' => false,'count' => 'id']);

                  //echo "string";
 //echo  $this->db->last_query(); //die;
                  $device_flag=0;
                  //echo $result['devices_limit'];//+" "+$device_info;
                 // echo "<br>";
                 // echo $device_info;
                  //echo "break";
                  //print_r($device_info,"device_info");
                if($result['devices_limit'] > $device_info)
                {
                     //echo "in else part part";
                }else{
                         $device_flag=1;
                }
                    $device = [
                        'restaurant_id' => $result['id'],
                        'name' =>  $this->input->post('name'),
                        'version' =>  $this->input->post('version'),
                        'app_version' => $this->input->post('app_version'),
                        'device' => $this->input->post('device'),
                        'token' =>  $this->input->post('token'),
                        'is_deleted' =>  0,
                        'is_login' =>  1,
                    ];
                    $device_data = ($this->users_model->sql_select(TBL_ACTIVE_DEVICES, '*', ['where' => ['token' =>$this->input->post('token'),'restaurant_id' => $result['id']]]));
                     if($device_data)
                    {
                        $device['updated_at'] = date('Y-m-d H:i:s');
                        $this->users_model->common_insert_update('update', TBL_ACTIVE_DEVICES, $device,['id' => $device_data[0]['id']]);
                    }
                    else
                    {
                        $device['created_at'] = date('Y-m-d H:i:s');
                        $inserted_id = $this->users_model->common_insert_update('insert', TBL_ACTIVE_DEVICES, $device);
                    }
                
                    $tokenData = array();
                    $tokenData['id'] = $result['id'];
                    $tokenData['email'] = $result['email'];

                    // $date = new DateTime();
                    // $tokenData['iat'] = $date->getTimestamp();
                    // $tokenData['exp'] = $date->getTimestamp() + $this->config->item('token_timeout');
                    $token = Authorization::generateToken($tokenData);
                    
                    $users = $this->db->query("SELECT * FROM users  WHERE id = '".$result['id']."'");
           
                    // echo  $this->db->last_query(); die;
                       if($users->num_rows() > 0 ){
                            foreach ($users->result() as $row9)
                            {
                                    $order_feature = $row9->order_feature;
                            }
                        }


                        
                    if($result['id'])
                    {
                        $id=$result['id'];
                    }else{
                        $id=$result['restaurant_id'];
                    }

                    $query="SELECT * FROM `package_details` where DATE_ADD(created_at, INTERVAL 30 DAY)>=CURRENT_TIMESTAMP and `restaurant_id` = '".$id."' and status='activate' and package_id='3'";
                    $resultdata=$this->users_model->run_manual_query($query);
                    if(!$resultdata){
						  $query1="SELECT * FROM `package_details` where  `restaurant_id` = '".$id."' and status!='activate' and package_id!='3' or `restaurant_id` = '".$id."' and status='activate' and package_id!='3' and date(now())>date(end_date)  and flag=1";
                        $resultdata1=$this->users_model->run_manual_query($query1);

                        $query2="SELECT * FROM `package_details` where  `restaurant_id` = '".$id."' and status='activate' and package_id!='3' and date(now())<=date(end_date) and flag=1";
                        $resultdata2=$this->users_model->run_manual_query($query2);

                        $query3="SELECT * FROM `package_details` where  `restaurant_id` = '".$id."' and status!='activate' and package_id='3'";
                        $resultdata3=$this->users_model->run_manual_query($query3);

                          $query4="SELECT * FROM `package_details` where  `restaurant_id` = '".$id."' and status='activate' and package_id='3' and DATE_ADD(created_at, INTERVAL 30 DAY)<CURRENT_TIMESTAMP";
                        $resultdata4=$this->users_model->run_manual_query($query4);
                        
                        // echo "<pre>";
                        // print_r($resultdata2);
                        // echo "<pre>";
                        // print_r($resultdata3);
                        // echo "<pre>";
                        // print_r($resultdata4);
 
                         

                        if($resultdata2 && !$device_flag)
                        { 
                        	$data = array(
                        'id' => $result['id'],
                        'name' => $result['name'],
                        'email' => $result['email'],
                        'role' => $result['role'],
                        'token' => $token,
                        'order_feature' => $order_feature,
                    );
                    $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

                        }else 


                        if($resultdata1 && !$resultdata2)
                        { 
                            $this->set_response([
                            'status' => false,
                            'message' => 'Your Subscription has expired. Kindly contact our team to renew your plan.',
                        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

                        }else

                        if(($resultdata3 || $resultdata4) && !$resultdata2)
                        {   
                        	$this->set_response([
                            'status' => false,
                            'message' => 'Your Free Plan has expired. kindly contact our team to subscribe to a plan.',
                        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

                        }else

                        if($device_flag)
                        {     
                                $this->set_response([
                                'status' => false,
                                'message' => 'Your account has reached login limit.',
                            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                        } 
                              
                        // $this->set_response([
                        //     'status' => false,
                        //     'message' => 'The Plan is expired! Contact the Administrator to Renew the Plan',
                        // ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

                         }
                         else
                         if($device_flag)
                        {
                                $this->set_response([
                                'status' => false,
                                'message' => 'Your Account has login limit reached',
                            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                        }
                        else
                        {
                                 	 $data = array(
                                'id' => $result['id'],
                                'name' => $result['name'],
                                'email' => $result['email'],
                                'role' => $result['role'],
                                'token' => $token,
                                'order_feature' => $order_feature,
                        );
                    $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

                         }


                         
                
            }
        } else {
            $this->set_response([
                'status' => false,
                'message' => 'Invalid Email/Password',
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    /**
     * @api {get} api/staffs/:restaurant_id Staff Listing
     * @apiName Staff Listing
     * @apiGroup Staffs
     *
     * @apiHeader {String} Authorization Users unique access-key.
     * 
     * @apiParam {Integer} restaurant_id restaurant unique ID.
     *
     * @apiSuccess {Integer} id id of the Staff.
     * @apiSuccess {String} name name of the Staff.
     * @apiSuccess {String} email  email of the Staff.
     * @apiSuccess {String} role  role of the Staff.
     * @apiSuccess {String} image  Image name of the Staff.
     * @apiSuccess {Boolean} is_active Return menu Active = 1 else 0
     */
    public function staffs_get($restaurantId)
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
        {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) 
            {
                $staffs = $this->users_model->sql_select(TBL_USERS, '*', ['where' => ['restaurant_id' => $restaurantId, 'role' => STAFF, 'is_deleted' => 0]], ['single' => false]);
                $id = $this->get('id');

                if ($id === null) {
                    // Check if the staffs data store contains staffs (in case the database result returns NULL)
                    if ($staffs) {
                        // Set the response and exit
                        $data = null;
                        foreach ($staffs as $key => $value)
                        {
                            $data[$key] = array(
                                'id' => $value['id'],
                                'name' => $value['name'],
                                'email' => $value['email'],
                                'role' => $value['role'],
                                'image' => $value['image'],
                                'is_active' => $value['is_active'],
                                'created_at' => $value['created_at'],
                                'updated_at' => $value['updated_at']
                            );
                        }
                        $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                    } else {
                        // Set the response and exit
                        $this->response([
                            'status' => false,
                            'message' => 'No staff were found',
                        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                    }
                }
                // Find and return a single record for a particular user.
                $id = (int) $id;
                // Validate the id.
                if ($id <= 0) {
                    // Invalid id, set the response and exit.
                    $this->response(null, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
                }
                // Get the user from the array, using the id as key for retreival.
                // Usually a model is to be used for this.
                $staff = null;
                if (!empty($staffs)) {
                    foreach ($staffs as $key => $value) {
                        if (isset($value['id']) && $value['id'] === $id) {
                            $staff = $value;
                        }
                    }
                }

                if (!empty($staff)) {
                    $this->set_response($staff, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                } else {
                    $this->set_response([
                        'status' => false,
                        'message' => 'Staff could not be found',
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
            }
        }
        else
        {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @api {get} api/menus/:restaurant_id Menus Listing
     * @apiName Menus Listing
     * @apiGroup Menus
     *
     * @apiHeader {String} Authorization Users unique access-key.
     * 
     * @apiParam {Integer} restaurant_id restaurant unique ID.
     *
     * @apiSuccess {Object[]} new Listing new menus 
     * @apiSuccess {Object[]} updated Listing updated menus
     * @apiSuccess {Object[]} deleted Listing deleted menus
     */

      /**
     * @api {get} api/menus/:restaurant_id/:timestamp Menus Listing with Timestamp
     * @apiName Menus Listing with Timestamp
     * 
     * @apiGroup Menus
     * 
     * @apiHeader {String} Authorization Users unique access-key.
     *
     * @apiParam {Integer} restaurant_id restaurant unique ID.
     * @apiParam {Timestamp} timestamp Timestamp for get record.
     *
     * @apiSuccess {Object[]} new Listing new menus 
     * @apiSuccess {Object[]} updated Listing updated menus based on timestamp
     * @apiSuccess {Object[]} deleted Listing deleted menus based on timestamp
     */
    public function menus_get($restaurantId, $timestamp = null)
    {

        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
        {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) 
            {
                if (!is_null($restaurantId)) 
                {
                    $restaurant = $this->users_model->sql_select(TBL_USERS, '*', ['where' =>['is_deleted' => 0, 'id' => $restaurantId]], ['single' => false]);
                    if(!empty($restaurant))
                    {
                        $where = ['restaurant_id' => $restaurantId];
                        //get all records when didnt pass any time stamp
                        if(!is_null($timestamp))
                        {
                            $timestamp = urldecode($timestamp);
                            $new = $this->users_model->sql_select(TBL_MENUS, '*', ['where' =>array_merge($where, ['created_at > '=> $timestamp, 'is_deleted' => 0])], ['single' => false]);
                            if(!empty($new))
                            {
                                $where = ['restaurant_id'=> $restaurantId ,'created_at <'=> $timestamp] ;
                                $updated = $this->users_model->sql_select(TBL_MENUS, '*', ['where' =>array_merge($where, ['updated_at >='=> $timestamp,'updated_at!=' => NULL, 'is_deleted' => 0])], ['single' => false]);
                                // $new = $this->users_model->sql_select(TBL_MENUS, '*', ['where' =>array_merge($where, ['created_at > '=> $timestamp,'updated_at' => NULL, 'is_deleted' => 0])], ['single' => false]);
                                $deleted = $this->users_model->sql_select(TBL_MENUS, '*', ['where' =>array_merge($where, ['is_deleted' => 1])], ['single' => false]);
                            }else
                            {
                                $where = ['restaurant_id'=> $restaurantId ,'updated_at >='=> $timestamp] ;
                                $updated = $this->users_model->sql_select(TBL_MENUS, '*', ['where' =>array_merge($where, ['updated_at!=' => NULL, 'is_deleted' => 0])], ['single' => false]);
                                $deleted = $this->users_model->sql_select(TBL_MENUS, '*', ['where' =>array_merge($where, ['is_deleted' => 1])], ['single' => false]);
                            } 
                        }else
                        {
                            $new = $this->users_model->sql_select(TBL_MENUS, '*', ['where' =>array_merge($where, ['is_deleted' => 0])], ['single' => false]);
                            $updated = [];
                            $deleted = [];
                        }
                        $this->response(['new' => $new, 'updated' => $updated,'deleted' => $deleted], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                    }else
                    {
                        $this->response([
                            'status' => false,
                            'message' => 'No menus were found',
                        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'No menus were found',
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
            }else
            {
                $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);   
            }
        }
        else
        {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @api {get} api/categories/:menu_id Categories Listing
     * @apiName Categories Listing
     * 
     * @apiGroup Categories
     *
     * @apiHeader {String} Authorization Users unique access-key.
     * 
     * @apiParam {Integer} menu_id Menu unique ID.
     *
     * @apiSuccess {Object[]} new Listing new categories 
     * @apiSuccess {Object[]} updated Listing updated categories
     * @apiSuccess {Object[]} deleted Listing deleted categories
     */

     /**
     * @api {get} api/categories/:menu_id/:timestamp Categories Listing with Timestamp
     * @apiName Categories Listing with Timestamp
     * 
     * @apiGroup Categories
     *
     * @apiHeader {String} Authorization Users unique access-key.
     * 
     * @apiParam {Integer} restaurant_id restaurant unique ID.
     * @apiParam {Timestamp} timestamp Timestamp for get record.
     *
     * @apiSuccess {Object[]} new Listing new categories 
     * @apiSuccess {Object[]} updated Listing updated categories based on timestamp
     * @apiSuccess {Object[]} deleted Listing deleted categories based on timestamp
     */
    public function categories1_get($menuId, $timestamp = null)
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
        {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) 
            {
                if (!is_null($menuId)) 
                {
                    $menus = $this->users_model->sql_select(TBL_MENUS, '*', ['where' =>['is_deleted' => 0, 'id' => $menuId]], ['single' => false]);
                    if(!empty($menus))
                    {
                        //get all records when didnt pass any time stamp
                        if(!is_null($timestamp))
                        {
                            $timestamp = urldecode($timestamp);
                            $new = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.is_deleted = 0 AND cate.created_at > "'.$timestamp.'"  AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC ')->result_array();
                            if(!empty($new))
                            {
                                $updated = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.updated_at != "" AND cate.updated_at >= "'.$timestamp.'" AND cate.is_deleted = 0 AND cate.created_at < "'.$timestamp.'"  AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC')->result_array();
                                $deleted = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.is_deleted = 1 AND cate.created_at < "'.$timestamp.'"  AND cd.menu_id = '.$menuId.'')->result_array();
                            }else
                            {
                                $updated = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.updated_at != "" AND cate.updated_at >= "'.$timestamp.'" AND cate.is_deleted = 0 AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC')->result_array();
                                $deleted = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.is_deleted = 1 AND cate.updated_at >= "'.$timestamp.'" AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC')->result_array();
                            } 
                        }else
                        {
                            $new = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.is_deleted = 0 AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC')->result_array();
                            $updated = [];
                            $deleted = [];
                        }
                        $this->response(['new' => $new, 'updated' => $updated,'deleted' => $deleted], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                    }else
                    {
                        $this->response([
                            'status' => false,
                            'message' => 'No categories were found',
                        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'No categories were found',
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
            }
        }else
        {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @api {get} api/items/:category_id Items Listing
     * @apiName Items Listing
     * @apiGroup Items
     *
     * @apiHeader {String} Authorization Users unique access-key.
     * 
     * @apiParam {Integer} category_id Category unique ID.
     *
     * @apiSuccess {Object[]} new Listing new items 
     * @apiSuccess {Object[]} updated Listing updated items
     * @apiSuccess {Object[]} deleted Listing deleted items
     */

     /**
     * @api {get} api/items/:category_id/:timestamp Items Listing with Timestamp
     * @apiName Items Listing with Timestamp
     * 
     * @apiGroup Items
     *
     * @apiHeader {String} Authorization Users unique access-key.
     * 
     * @apiParam {Integer} category_id category unique ID.
     * @apiParam {Timestamp} timestamp Timestamp for get record.
     *
     * @apiSuccess {Object[]} new Listing new items 
     * @apiSuccess {Object[]} updated Listing updated items based on timestamp
     * @apiSuccess {Object[]} deleted Listing deleted items based on timestamp
     */

    public function items1_get($categoryId, $timestamp = null)
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
        {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) 
            {
                if (!is_null($categoryId)) 
                {
                    $categories = $this->users_model->sql_select(TBL_CATEGORIES, '*', ['where' =>['is_deleted' => 0, 'id' => $categoryId]], ['single' => false]);
                    if(!empty($categories))
                    {
                        if(!is_null($timestamp))
                        {
                            $timestamp = urldecode($timestamp);
                            $new = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 0 AND item.created_at > "'.$timestamp.'"  AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                            if(!empty($new))
                            {
                                $updated = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 0 AND item.created_at < "'.$timestamp.'" AND item.updated_at != "" AND item.updated_at >= "'.$timestamp.'" AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                                $deleted = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 1 AND item.created_at < "'.$timestamp.'" AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                            }else
                            {
                                $updated = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 0 AND item.updated_at != " " AND item.updated_at >= "'.$timestamp.'" AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                                $deleted = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 1 AND item.updated_at >= "'.$timestamp.'" AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                            } 
                        }else
                        {
                            $new = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 0 AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                            $updated = [];
                            $deleted = [];
                        }
                        $this->response(['new' => $new, 'updated' => $updated,'deleted' => $deleted], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                    }else
                    {
                        $this->response([
                            'status' => false,
                            'message' => 'No items were found',
                        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'No items were found',
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
            }
        }
        else
        {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @api {get} api/itemdetails/:item_id Item Details
     * @apiName Item Details
     * @apiGroup Items
     *
     * @apiHeader {String} Authorization Users unique access-key.
     * 
     * @apiParam {Integer} item_id Item unique ID.
     *
     * @apiSuccess {Object[]} item Details of item 
     * @apiSuccess {Object[]} images Listing Images with New,updated and deleteditem images
     */

     /**
     * @api {get} api/itemdetails/:item_id/:timestamp Item Images Details with Timestamp
     * @apiName Item Images Details with Timestamp
     * 
     * @apiGroup Items
     *
     * @apiHeader {String} Authorization Users unique access-key.
     * 
     * @apiParam {Integer} item_id Item unique ID.
     * @apiParam {Timestamp} timestamp Timestamp for get record.
     *
     * @apiSuccess {Object[]} item Details of item
     * @apiSuccess {Object[]} images Listing Images with new,updated and deleted item images based on timestamp
     */

    public function itemdetails_get($itemID,$timestamp = null)
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
        {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) 
            {
                if (!is_null($itemID)) 
                {
                    $items = $this->users_model->sql_select(TBL_ITEMS, '*', ['where' =>['is_deleted' => 0, 'id' => $itemID]], ['single' => false]);
                    if(!empty($items))
                    {
                        $data = null;
                        foreach ($items as $key => $value)
                        {
                            $data[$key] = array(
                                'id' => $value['id'],
                                'title' => $value['title'],
                                'arabian_title' => $value['arabian_title'],
                                'price' => $value['price'],
                                'description' => $value['description'],
                                'arabian_description' => $value['arabian_description'],
                                'calories' => $value['calories'],
                                'is_featured' => $value['is_featured'],
                                'is_dish_new' => $value['is_dish_new'],
                                'time' => $value['time'],
                                'type' => $value['type'],
                                'is_active' => $value['is_active'],
                            );
                        }
                        $where = ['item_id' => $itemID];
                        //get all records when didnt pass any time stamp
                        if(!is_null($timestamp))
                        {
                            $timestamp = urldecode($timestamp);
                            $new = $this->users_model->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>array_merge($where, ['created_at > '=> $timestamp, 'is_deleted' => 0])], ['single' => false]);
                            if(!empty($new))
                            {
                                $where = ['item_id'=> $itemID ,'created_at <'=> $timestamp] ;
                                $updated = $this->users_model->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>array_merge($where, ['updated_at >='=> $timestamp,'updated_at!=' => NULL, 'is_deleted' => 0])], ['single' => false]);
                                $deleted = $this->users_model->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>array_merge($where, ['is_deleted' => 1])], ['single' => false]);
                                // print_r($this->db->last_query());
                                // die;
                            }else
                            {
                                $where = ['item_id'=> $itemID ,'updated_at >= '=> $timestamp] ;
                                $updated = $this->users_model->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>array_merge($where, ['updated_at!=' => NULL, 'is_deleted' => 0])], ['single' => false]);
                                $deleted = $this->users_model->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>array_merge($where, ['is_deleted' => 1])], ['single' => false]);
                            } 
                        }else
                        {
                            $new = $this->users_model->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>array_merge($where, ['is_deleted' => 0])], ['single' => false]);
                            $updated = [];
                            $deleted = [];
                        }
                        $this->response(['Item' => $data, 'images' => ['new' => $new, 'updated' => $updated,'deleted' => $deleted]], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                    }else
                    {
                        $this->response([
                            'status' => false,
                            'message' => 'No items were found',
                        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                    }
                } 
                else 
                {
                    $this->response([
                        'status' => false,
                        'message' => 'No items were found',
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
            }
            else
            {
                $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
            }
        }else
        {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

       
    /**
     * @api {Post} api/feedback Create Feedback
     * @apiName Create Feedback
     *
     * @apiHeader {String} Authorization Users unique access-key.
     * 
     * @apiGroup Feedback
     *
     * @apiParam {json} feedbacks Request-Example:
     *     {
     *      "staff_id" : 1,
     *      "restaurant_id" : 6,
     *      "staff_name" : acbd,
     *      "stars" : 2,
     *      "customer_name" : 'abcd',
     *      "feedback" : 'good service'
     *     },
     *     {.. },..
     *
     * @apiSuccess {Boolean} status Response status.
     * @apiSuccess {String} message Response message.
     *
     */
    public function feedback_post()
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
        {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) 
            {			
				$data = json_decode(file_get_contents("php://input"));
                $dataArr = $data->feedbacks;
				if(!empty($dataArr))
                {
                    foreach ($dataArr as $row) 
                    {
						$feedback = [
							'staff_id' => $row->staff_id,
							'restaurant_id' => $row->restaurant_id,
							'staff_name' => $row->staff_name,
							'stars' => $row->stars,
							'customer_name' => $row->customer_name,
							'feedback' => $row->feedback,
						];
						
						$inserted_id = $this->users_model->common_insert_update('insert', TBL_FEEDBACKS, $feedback);
						if (!empty($inserted_id)) {
							$this->set_response([
								'status' => true,
								'message' => 'Feedback inserted successfully',
							], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
						} else {
							$this->set_response([
								'status' => false,
								'message' => 'Feedback could not inserted',
							], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
						}
					}
				}
				else
				{
					$this->set_response([
							'status' => false,
							'message' => 'Invalid request',
						], REST_Controller::HTTP_NOT_FOUND); 
				}
            }
        }else
        {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }


    /**
     * @api {get} api/defaultsettings Background Setting before rsestaurant login
     * @apiName Background setting before login
     * 
     * @apiGroup Settings
     *
     * @apiSuccess {Integer} id Id of the Setting.
     * @apiSuccess {String} logo Logo for Setting.
     * @apiSuccess {String} bg_before_login Image of the background setting.
     * @apiSuccess {Boolean} is_active Return item Active = 1 else 0
     * @apiSuccess {Boolean} is_deleted Return item Deleted = 1 else 0
     * @apiSuccess {Timestamp} created_at Date of item creation.
     */

    /**
     * @api {get} api/defaultsettings/:restaurant_id Background Setting after rsestaurant login
     * @apiName Background setting after login
     * 
     * @apiGroup Settings
     *
     * @apiHeader {String} Authorization Users unique access-key.
     * 
     * @apiParam {Integer} restaurant_id Restaurant unique ID.
     *
     * @apiSuccess {Integer} id Id of the Setting.
     * @apiSuccess {String} logo Logo for Setting.
     * @apiSuccess {String} bg_after_login Image of the background setting.
     * @apiSuccess {Boolean} is_active Return item Active = 1 else 0
     * @apiSuccess {Boolean} is_deleted Return item Deleted = 1 else 0
     * @apiSuccess {Timestamp} created_at Date of item creation.
     */
    public function defaultsettings_get($restaurantId = null)
    {
        if ($restaurantId !== null) 
        {
            $headers = $this->input->request_headers();
            if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
            {
                $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
                if ($decodedToken != false) 
                {
                    $settings = $this->users_model->sql_select(TBL_SETTINGS, '*', ['where' => ['user_id' => $restaurantId, 'is_deleted' => 0]], ['single' => false]);
                    // $settings['currencies'] = $this->users_model->sql_select('default_currencies');
                }
            }
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        } else 
        {
            $settings = $this->users_model->sql_select(TBL_SETTINGS, '*', ['where' => ['user_id' => 1, 'is_deleted' => 0]], ['single' => false]);
        }

        if (!empty($settings)) 
        {
            $this->set_response($settings, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->set_response([
                'status' => false,
                'message' => 'Setting could not be found',
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }       
    }


    /**
     * @api {Post} api/item_clicks Create or Update Item Clicks
     * @apiName Create or Update Item Clicks
     *
     * @apiGroup Clicks
     * 
     * @apiHeader {String} Authorization Users unique access-key.
     *
     * @apiParam {json} item_clicks Request-Example:
     *     {
     *      "staff_id" : 1,
     *      "item_id" : 6,
     *      "restaurant_id" : 6,
     *      "no_of_clicks" : 7,
     *      "timestamp" : "2018-09-28 12:52:07"
     *     },
     *     {.. },..
     *
     * @apiSuccess {Boolean} status Response status.
     * @apiSuccess {String} message Response message.
     */
    public function item_clicks_post()
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
        {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) 
            {                
                $data = json_decode(file_get_contents("php://input"));
                $dataArr = $data->item_clicks;

                // $dataArr = json_decode($this->post('item_clicks'));
                if(!empty($dataArr))
                {
                    foreach ($dataArr as $row) 
                    {                       
                        $item_clicks = [
                            'staff_id' => $row->staff_id,
                            'item_id' => $row->item_id,
                            'restaurant_id' => $row->restaurant_id,
                            'no_of_clicks' => $row->no_of_clicks,
                            'created_at' => $row->timestamp,
                        ];
                    
                        $date = date('Y-m-d ',strtotime($row->timestamp));
                    
                        $result = ($this->users_model->sql_select(TBL_ITEM_CLICKS, '*', ['where' => ['is_deleted' => 0, 'staff_id' =>$row->staff_id,'item_id' =>$row->item_id,'restaurant_id' => $row->restaurant_id,'DATE(created_at)' => $date]]));
                        if(!empty($result))
                        {
                            $update = $this->users_model->common_insert_update('update', TBL_ITEM_CLICKS, ['no_of_clicks' => $result[0]['no_of_clicks'] + 1],['staff_id' => $row->staff_id,'item_id' => $row->item_id,'restaurant_id' => $row->restaurant_id,'DATE(created_at)' => $date]);
                            if($update) 
                            {
                                $this->set_response([
                                    'status' => true,
                                    'message' => 'Item clicks inserted successfully',
                                ], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
                            } 
                            else 
                            {
                                $this->set_response([
                                    'status' => false,
                                    'message' => 'Item clicks could not inserted',
                                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                            }
                        }
                        else
                        {
                            $inserted_id = $this->users_model->common_insert_update('insert', TBL_ITEM_CLICKS,  $item_clicks);
                            if($inserted_id) 
                            {
                                $this->set_response([
                                    'status' => true,
                                    'message' => 'Item clicks inserted successfully',
                                ], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
                            } 
                            else 
                            {
                                $this->set_response([
                                    'status' => false,
                                    'message' => 'Item clicks could not inserted',
                                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                            }
                        }
                    }
                }
            }
        }
        else
        {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        }
    
    }


 /**
     * @api {Post} api/category_clicks Create or Update Category Clicks
     * @apiName Create or Update category Clicks
     *
     * @apiGroup Clicks
     * 
     * @apiHeader {String} Authorization Users unique access-key.
     *
     * @apiParam {json} category_clicks Request-Example:
     *     {
     *      "staff_id" : 1,
     *      "category_id" : 6,
     *      "restaurant_id" : 6,
     *      "no_of_clicks" : 7,
     *      "timestamp" : "2018-09-28 12:52:07"
     *     },
     *     {.. },..
     *
     * @apiSuccess {Boolean} status Response status.
     * @apiSuccess {String} message Response message.
     */
    public function category_clicks_post()
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
        {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) 
            {                
                $data = json_decode(file_get_contents("php://input"));
                $dataArr = $data->category_clicks;

                if(!empty($dataArr))
                {
                    foreach ($dataArr as $row) 
                    {                       
                        $category_clicks = [
                            'staff_id' => $row->staff_id,
                            'category_id' => $row->category_id,
                            'restaurant_id' => $row->restaurant_id,
                            'no_of_clicks' => $row->no_of_clicks,
                            'created_at' => $row->timestamp,
                        ];
                    
                        $date = date('Y-m-d ',strtotime($row->timestamp));
                    
                        $result = ($this->users_model->sql_select(TBL_CATEGORY_CLICKS, '*', ['where' => ['is_deleted' => 0, 'staff_id' =>$row->staff_id,'category_id' =>$row->category_id,'restaurant_id' => $row->restaurant_id,'DATE(created_at)' => $date]]));
                        if(!empty($result))
                        {
                            $update = $this->users_model->common_insert_update('update', TBL_CATEGORY_CLICKS, ['no_of_clicks' => $result[0]['no_of_clicks'] + 1],['staff_id' => $row->staff_id,'category_id' => $row->category_id,'restaurant_id' => $row->restaurant_id,'DATE(created_at)' => $date]);
                            if($update) 
                            {
                                $this->set_response([
                                    'status' => true,
                                    'message' => 'Category clicks inserted successfully',
                                ], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
                            } 
                            else 
                            {
                                $this->set_response([
                                    'status' => false,
                                    'message' => 'Category clicks could not inserted',
                                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                            }
                        }
                        else
                        {
                            $inserted_id = $this->users_model->common_insert_update('insert', TBL_CATEGORY_CLICKS,  $category_clicks);
                            if($inserted_id) 
                            {
                                $this->set_response([
                                    'status' => true,
                                    'message' => 'Category clicks inserted successfully',
                                ], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
                            } 
                            else 
                            {
                                $this->set_response([
                                    'status' => false,
                                    'message' => 'Category clicks could not inserted',
                                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                            }
                        }
                    }
                }
            }
        }
        else
        {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        }
    
    }

    /**
     * @api {Post} api/forgot_password Forgot Password
     * @apiName Forgot Password
     *
     * @apiGroup Forgot Password
     *
     * @apiParam {String} email Email Id
     *
     * 
     */
    public function forgot_password_post()
    {   
        $requested_email = trim($this->post('email'));
        if(!is_null($requested_email) and !empty($requested_email))
        {
            $user = $this->users_model->get_user_detail(['email' => $requested_email, 'role' => RESTAURANT, 'is_deleted' => 0, 'is_active' => 1]);
            if (empty($user)) 
            {
                $this->set_response([
                    'status' => false,
                    'message' => 'Email is not registered',
                ], REST_Controller::HTTP_NOT_FOUND);
            } else 
            {
                $verification_code = verification_code();
                $this->users_model->common_insert_update('update', TBL_USERS, array('verification_code' => $verification_code), array('id' => $user['id']));
                $verification_code = base64_encode($verification_code);
                $encoded_verification_code = $verification_code;
                $email_data = [];
                $email_data['url'] = base_url() . 'Login/reset_password?code=' . $encoded_verification_code;
                $email_data['name'] = $user['name'];
                $email_data['email'] = trim($this->post('email'));
                $email_data['subject'] = 'Reset Password';
                send_email(trim($this->post('email')), 'forgot_password', $email_data);
                $this->set_response([
                    'status' => true,
                    'message' => 'Activation link sent to your registered Email',
                ], REST_Controller::HTTP_OK); 
            }
        }else
        {
            $this->set_response([
                'status' => false,
                'message' => 'Invalid Request',
            ], REST_Controller::HTTP_NOT_FOUND);
        }   
    }


    /**
     * @api {get} api/help_topics Help Topics before rsestaurant login
     * @apiName Help Topics before login
     * 
     * @apiGroup Help Topics
     *
     * @apiSuccess {Integer} id Id of the Helptopic.
     * @apiSuccess {String} title Logo for Helptopic.
     * @apiSuccess {String} description of the Helptopic.
     * @apiSuccess {Boolean} is_active Return help topic Active = 1 else 0
     * @apiSuccess {Boolean} is_deleted Return help topic Deleted = 1 else 0
     * @apiSuccess {Timestamp} created_at Date of help topic creation.
     */

    public function help_topics_get()
    {
        $helptopic = $this->users_model->sql_select(TBL_HELP_TOPICS, '*', ['where' => ['is_deleted' => 0]], ['single' => false]);
        if (!empty($helptopic)) {
            $this->set_response($helptopic, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->set_response([
                'status' => false,
                'message' => 'Help Topics could not be found',
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    /**
     * @api {get} api/packages Packages Listing
     * @apiName Packages Listing
     * @apiGroup Packages
     *
     * @apiHeader {String} Authorization Users unique access-key.
     * 
     *
     * @apiSuccess {Integer} id id of the Package.
     * @apiSuccess {String} name name of the Package.
     * @apiSuccess {String} arabic_name  Arabic name of the Package.
     * @apiSuccess {Double} price  Price of the Package.
     * @apiSuccess {Date} start_date  Starting date of the Package.
     * @apiSuccess {Date} end_date  End date of the Package.
     * @apiSuccess {Double} discount Discount of the Package.
     * @apiSuccess {Integer} users Limitation of users.
     * @apiSuccess {Integer} menus Limitation of menus.
     * @apiSuccess {Integet} categories Limitation of categories.
     * @apiSuccess {Integer} items Limitation of items.
     * @apiSuccess {Integer} devices_limit Limitation of device login.
     * @apiSuccess {Boolean} is_active Return menu Active = 1 else 0
     */

    public function packages_get()
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
        {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) 
            {
                $packages = $this->users_model->sql_select(TBL_PACKAGES, '*', ['where' =>['is_deleted' => 0]], ['single' => false]);
                if(!empty($packages))
                {
                    $this->set_response($packages, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
                else
                {
                    $this->set_response([
                        'status' => false,
                        'message' => 'Setting could not be found',
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
            }
        }
        else
        {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @api {Post} api/package_request Send package request
     * @apiName Send package request
     *
     * @apiGroup Packages
     * 
     * @apiHeader {String} Authorization Users unique access-key.
     * 
     * @apiParam {package_id} package_id Id of Package.
     * @apiParam {restaurant_id} restaurant_id Id of the Restaurant.
     *
     * @apiSuccess {Boolean} status Response status.
     * @apiSuccess {String} message Response message.
     */
    public function package_request_post()
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
        {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) 
            {
                $package = ($this->users_model->sql_select(TBL_PACKAGES, '*', ['where' => ['is_deleted' => 0,'id' => $this->input->post('package_id')]]));
                $restaurant = ($this->users_model->sql_select(TBL_USERS, '*', ['where' => ['is_deleted' => 0,'id' => $this->input->post('restaurant_id')]]));
                if (!empty($package) && !empty($restaurant)) 
                {
                    $dataArr = array(
                        'package_id' => $this->input->post('package_id'),
                        'restaurant_id' => $this->input->post('restaurant_id'),
                        'status' => 'new',
                    );
                    $dataArr['request_date'] = date('Y-m-d');
                    $dataArr['created_at'] = date('Y-m-d H:i:s');
                    $this->users_model->common_insert_update('insert', TBL_PACKAGE_DETAILS, $dataArr);
                    $this->set_response([
                        'status' => true,
                        'message' => 'Request has been send successfully!',
                    ], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
                }
                else
                {
                    $this->set_response([
                        'status' => false,
                        'message' => 'Invalid Request',
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }  
            }
        }
        else
        {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /**
    * @api {Post} api/logout Send logout request
    * @apiName Send logout request
    *
    * @apiGroup Logout
    * 
    * @apiHeader {String} Authorization Users unique access-key.
    * 
    * @apiParam {restaurant_id} restaurant_id Id of restaurant.
    * @apiParam {name} name name of the login device.
    * @apiParam {version} version version of the login device.
    * @apiParam {token} token token of the login device.
    *
    * @apiSuccess {Boolean} status Response status.
    * @apiSuccess {String} message Response message.
    */
    public function logout_post()
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
        {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) 
            {
                $device = [
                    'restaurant_id' => $this->input->post('restaurant_id'),
                    'name' =>  $this->input->post('name'),
                    'version' =>  $this->input->post('version'),
                    'token' =>  $this->input->post('token'),
                    'is_login' =>  0,
                ];
                $device_data = ($this->users_model->sql_select(TBL_ACTIVE_DEVICES, '*', ['where' => ['token' =>$this->input->post('token'),'restaurant_id' =>$this->input->post('restaurant_id')]]));
                if($device_data)
                {
                    $device['updated_at'] = date('Y-m-d H:i:s');
                    $this->users_model->common_insert_update('update', TBL_ACTIVE_DEVICES, $device,['id' => $device_data[0]['id']]);
                    $this->set_response([
                        'status' => true,
                        'message' => 'Logout successfully!',
                    ], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
                }
                else
                {
                    $this->set_response([
                        'status' => false,
                        'message' => 'Invalid Request',
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }  
            }
        }
        else
        {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /**
    * @api {Post} api/device_status Send device status request
    * @apiName Send Device status request
    *
    *@apiDescription This API is use for check status where device is deleted from web or not?.
    *
    * @apiGroup Device status
    * 
    * @apiHeader {String} Authorization Users unique access-key.
    * 
    * @apiParam {restaurant_id} restaurant_id Id of restaurant.
    * @apiParam {token} token token of the login device.
    *
    * @apiSuccess {Boolean} status Response status.
    * @apiSuccess {String} message Response message.
    */
    public function device_status_post()
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
        {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) 
            {
                $device = [
                            'app_version' => $this->input->post('app_version'),
                            'device' => $this->input->post('device'),
                          ];
                $this->users_model->common_insert_update('update', TBL_ACTIVE_DEVICES, $device,['token' =>$this->input->post('token'),'restaurant_id' =>$this->input->post('restaurant_id')]);
                $device_data = ($this->users_model->sql_select(TBL_ACTIVE_DEVICES, '*', ['where' => ['token' =>$this->input->post('token'),'restaurant_id' =>$this->input->post('restaurant_id')]]));
                if($device_data)
                {
                      $flag=0;
                     $id=$this->input->post('restaurant_id');

                      $order_data1=$this->users_model->sql_select('users', 'order_feature', ['where' => ['id' =>$this->input->post('restaurant_id')]]);
                       
                        foreach ($order_data1 as   $value) {
                            $order_data=$value['order_feature'];
                        }

                	 $query="SELECT * FROM `package_details` where DATE_ADD(created_at, INTERVAL 30 DAY)>=CURRENT_TIMESTAMP and `restaurant_id` = '".$id."' and status='activate' and package_id='3'";
                    $resultdata=$this->users_model->run_manual_query($query);
                    if(!$resultdata){
						$query1="SELECT * FROM `package_details` where  `restaurant_id` = '".$id."' and status!='activate' and package_id!='3'";
                        $resultdata1=$this->users_model->run_manual_query($query1);

                         $query2="SELECT * FROM `package_details` where  `restaurant_id` = '".$id."' and status='activate' and package_id!='3' and date(now())<=date(end_date) and flag=1";
                        $resultdata2=$this->users_model->run_manual_query($query2);

                        $query3="SELECT * FROM `package_details` where  `restaurant_id` = '".$id."' and status!='activate' and package_id='3'";
                        $resultdata3=$this->users_model->run_manual_query($query3);


                        $query4="SELECT * FROM `package_details` where  `restaurant_id` = '".$id."' and status!='activate' and package_id='3'";
                        $resultdata4=$this->users_model->run_manual_query($query3);

                       

                        if($resultdata2)
                        { 
                           $flag=2;
                        }else
                        if($resultdata1)
                        {
                           $flag=1;
                        }else
                        if($resultdata3)
                        { 
                          $flag=1;
                        } 

                         if($flag==2)
                        {
                          $flag=0;
                        }else{
                        	$flag=1;
                        }

                        
                        
                       
                      }
                    
                      if($flag==1)
                      {
                      	 $data = array(
                        'id' => $device_data[0]['id'],
                        'is_login' => $device_data[0]['is_login'],
                        'token' => $device_data[0]['token'],
                        'is_deleted' => "1",
                        "order_feature" =>"$order_data"
                    );
                    $this->response($data, REST_Controller::HTTP_OK);
                      }else{
                      	 $data = array(
                        'id' => $device_data[0]['id'],
                        'is_login' => $device_data[0]['is_login'],
                        'token' => $device_data[0]['token'],
                        'is_deleted' => $device_data[0]['is_deleted'],
                        "order_feature" =>"$order_data"
                    );
                    $this->response($data, REST_Controller::HTTP_OK);
                      }

                }
                else
                {
                    $this->set_response([
                        'status' => false,
                        'message' => 'Invalid Request',
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }  
            }
        }
        else
        {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }


    public function categories2_get($menuId, $timestamp = null)
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
        {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) 
            {
                if (!is_null($menuId)) 
                {
                    $menus = $this->users_model->sql_select(TBL_MENUS, '*', ['where' =>['is_deleted' => 0, 'id' => $menuId]], ['single' => false]);
                    if(!empty($menus))
                    {
                        //get all records when didn't pass any timestamp
                        if(!is_null($timestamp))
                        {
                            $timestamp = urldecode($timestamp);
                            $new = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_NEW_CATEGORY.' as nc,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.is_deleted = 0 AND nc.menu_id = menu.id AND nc.category_id = cate.id AND nc.created_at > "'.$timestamp.'"  AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC ')->result_array();
                            if(!empty($new))
                            {
                      
                                $updated = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.updated_at != "" AND cate.updated_at >= "'.$timestamp.'" AND cate.is_deleted = 0 AND cate.created_at < "'.$timestamp.'"  AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC')->result_array();
                                $deleted = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.is_deleted = 1 AND cate.created_at < "'.$timestamp.'"  AND cd.menu_id = '.$menuId.'')->result_array();
                                $deleted_category = $this->db->query('SELECT * from  '.TBL_DELETE_CATEGORY.' where is_deleted = 1 AND menu_id = '.$menuId.' AND created_at >= "'.$timestamp.'"')->result_array();
                                $new_category = $this->db->query('SELECT * from  '.TBL_NEW_CATEGORY.' where menu_id = '.$menuId.' AND created_at >= "'.$timestamp.'"')->result_array();
                                if(!empty($new_category))
                                {
                                    foreach ($new_category as $key => $rows) 
                                    {
                                        $get_category= $this->db->query('SELECT * from  '.TBL_CATEGORIES.' where id = '.$rows['category_id'].'')->row_array();
                                        $data[$key] = array('id' => $get_category['id'],
                                        'order' => $get_category['order'],
                                        'title' => $get_category['title'],
                                        'arabian_title' => $get_category['arabian_title'],
                                        'background_image' => $get_category['background_image'],
                                        'image' => $get_category['image'],
                                        'order' => 'null',
                                        'is_active' => $get_category['is_active'],
                                        'is_deleted' => 0,
                                        'created_at' => $get_category['created_at'],
                                        'updated_at' => $rows['created_at'], //get timestamp from delete_category table
                                        'menu_id' => $rows['menu_id'] //get menu id from delete_category table
                                       );
                                    }
                                    $new = array_merge($new,$data);
                                }
                                if(!empty($deleted_category))
                                {
                                    foreach ($deleted_category as $key => $rows) 
                                    {
                                        $get_category= $this->db->query('SELECT * from  '.TBL_CATEGORIES.' where id = '.$rows['category_id'].'')->row_array();
                                        $data[$key] = array('id' => $get_category['id'],
                                        'order' => $get_category['order'],
                                        'title' => $get_category['title'],
                                        'arabian_title' => $get_category['arabian_title'],
                                        'background_image' => $get_category['background_image'],
                                        'image' => $get_category['image'],
                                        'order' => 'null',
                                        'is_active' => $get_category['is_active'],
                                        'is_deleted' => 1,
                                        'created_at' => $get_category['created_at'],
                                        'updated_at' => $rows['created_at'], //get timestamp from delete_category table
                                        'menu_id' => $rows['menu_id'] //get menu id from delete_category table
                                       );
                                    }
                                    $deleted = array_merge($deleted,$data);
                                }
                            }else
                            {
                                $updated = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.updated_at != "" AND cate.updated_at >= "'.$timestamp.'" AND cate.is_deleted = 0 AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC')->result_array();
                                $deleted = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.is_deleted = 1 AND cate.updated_at >= "'.$timestamp.'" AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC')->result_array();
                                $deleted_category = $this->db->query('SELECT * from  '.TBL_DELETE_CATEGORY.' where is_deleted = 1 AND menu_id = '.$menuId.' AND created_at >= "'.$timestamp.'"')->result_array();
                                $new_category = $this->db->query('SELECT * from  '.TBL_NEW_CATEGORY.' where menu_id = '.$menuId.' AND created_at >= "'.$timestamp.'"')->result_array();
                                // echo '<pre>';
                                // print_r($new_category);
                                // print_r($deleted_category);
                                // die;
                                if(!empty($new_category))
                                {
                                    foreach ($new_category as $key => $rows) 
                                    {
                                        $get_category= $this->db->query('SELECT * from  '.TBL_CATEGORIES.' where id = '.$rows['category_id'].'')->row_array();
                                        $data[$key] = array('id' => $get_category['id'],
                                        'order' => $get_category['order'],
                                        'title' => $get_category['title'],
                                        'arabian_title' => $get_category['arabian_title'],
                                        'background_image' => $get_category['background_image'],
                                        'image' => $get_category['image'],
                                        'order' => 'null',
                                        'is_active' => $get_category['is_active'],
                                        'is_deleted' => 0,
                                        'created_at' => $get_category['created_at'],
                                        'updated_at' => $rows['created_at'], //get timestamp from delete_category table
                                        'menu_id' => $rows['menu_id'] //get menu id from delete_category table
                                       );
                                    }
                                    $new = array_merge($new,$data);
                                    $updated = [];
                                    //$new = array_unique(array_merge($updated,$data));
                                    //$updated = array_diff($new,$data);
                                    // print_r(array_diff($new,$data));
                                    // die;
                                }
                                if(!empty($deleted_category))
                                {
                                    foreach ($deleted_category as $key => $rows) 
                                    {
                                        $get_category= $this->db->query('SELECT * from  '.TBL_CATEGORIES.' where id = '.$rows['category_id'].'')->row_array();
                                        $data[$key] = array('id' => $get_category['id'],
                                        'order' => $get_category['order'],
                                        'title' => $get_category['title'],
                                        'arabian_title' => $get_category['arabian_title'],
                                        'background_image' => $get_category['background_image'],
                                        'image' => $get_category['image'],
                                        'order' => 'null',
                                        'is_active' => $get_category['is_active'],
                                        'is_deleted' => 1,
                                        'created_at' => $get_category['created_at'],
                                        'updated_at' => $rows['created_at'], //get timestamp from delete_category table
                                        'menu_id' => $rows['menu_id'] //get menu id from delete_category table
                                       );
                                    }
                                    $deleted = array_merge($deleted,$data);
                                }
                            } 
                        }else
                        {
                            $new = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.is_deleted = 0 AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC')->result_array();
                            $updated = [];
                            $deleted = [];
                        }
                        $this->response(['new' => $new, 'updated' => $updated,'deleted' => $deleted], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                    }else
                    {
                        $this->response([
                            'status' => false,
                            'message' => 'No categories were found',
                        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'No categories were found',
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
            }
        }else
        {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function categories_get($menuId, $timestamp = null)
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
        {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) 
            {
                if (!is_null($menuId)) 
                {
                    $menus = $this->users_model->sql_select(TBL_MENUS, '*', ['where' =>['is_deleted' => 0, 'id' => $menuId]], ['single' => false]);
                    if(!empty($menus))
                    {
                        //get all records when didn't pass any timestamp
                        if(!is_null($timestamp))
                        {
                            $timestamp = urldecode($timestamp);
                            $new = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.is_deleted = 0 AND cate.created_at > "'.$timestamp.'"  AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC ')->result_array();
                            if(!empty($new))
                            {
                      
                                // $updated = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.updated_at != "" AND cate.updated_at >= "'.$timestamp.'" AND cate.is_deleted = 0 AND cate.created_at < "'.$timestamp.'"  AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC')->result_array();
                                $updated = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.updated_at >= "'.$timestamp.'" AND cate.is_deleted = 0 AND cate.created_at < "'.$timestamp.'"  AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC')->result_array();
                                $deleted = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.is_deleted = 1 AND cate.created_at < "'.$timestamp.'"  AND cd.menu_id = '.$menuId.'')->result_array();
                                $deleted_category = $this->db->query('SELECT * from  '.TBL_DELETE_CATEGORY.' where is_deleted = 1 AND menu_id = '.$menuId.' AND created_at >= "'.$timestamp.'"')->result_array();
                                $new_category = $this->db->query('SELECT * from  '.TBL_NEW_CATEGORY.' where menu_id = '.$menuId.' AND created_at >= "'.$timestamp.'"')->result_array();
                                if(!empty($new_category))
                                {
                                    foreach ($new_category as $key => $rows) 
                                    {
                                        $get_category= $this->db->query('SELECT * from  '.TBL_CATEGORIES.' where id = '.$rows['category_id'].'')->row_array();
                                        $data[$key] = array('id' => $get_category['id'],
                                        'order' => $get_category['order'],
                                        'title' => $get_category['title'],
                                        'arabian_title' => $get_category['arabian_title'],
                                        'background_image' => $get_category['background_image'],
                                        'image' => $get_category['image'],
                                        'order' => null,
                                        'is_active' => $get_category['is_active'],
                                        'is_deleted' => 0,
                                        'created_at' => $get_category['created_at'],
                                        'updated_at' => $rows['created_at'], //get timestamp from delete_category table
                                        'menu_id' => $rows['menu_id'] //get menu id from delete_category table
                                       );
                                    }
                                    // $new = array_merge($new,$data);
                                    $new = $data;
                                }
                                if(!empty($deleted_category))
                                {
                                    foreach ($deleted_category as $key => $rows) 
                                    {
                                        $get_category= $this->db->query('SELECT * from  '.TBL_CATEGORIES.' where id = '.$rows['category_id'].'')->row_array();
                                        $data[$key] = array('id' => $get_category['id'],
                                        'order' => $get_category['order'],
                                        'title' => $get_category['title'],
                                        'arabian_title' => $get_category['arabian_title'],
                                        'background_image' => $get_category['background_image'],
                                        'image' => $get_category['image'],
                                        'order' => null,
                                        'is_active' => $get_category['is_active'],
                                        'is_deleted' => 1,
                                        'created_at' => $get_category['created_at'],
                                        'updated_at' => $rows['created_at'], //get timestamp from delete_category table
                                        'menu_id' => $rows['menu_id'] //get menu id from delete_category table
                                       );
                                    }
                                    $deleted = $data;
                                }
                            }else
                            {
                                // $updated = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.updated_at != null AND cate.updated_at >= "'.$timestamp.'" AND cate.is_deleted = 0 AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC')->result_array();
                                $updated = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.updated_at >= "'.$timestamp.'" AND cate.is_deleted = 0 AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC')->result_array();
                                $deleted = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.is_deleted = 1 AND cate.updated_at >= "'.$timestamp.'" AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC')->result_array();
                                $deleted_category = $this->db->query('SELECT * from  '.TBL_DELETE_CATEGORY.' where is_deleted = 1 AND menu_id = '.$menuId.' AND created_at >= "'.$timestamp.'"')->result_array();
                                $new_category = $this->db->query('SELECT * from  '.TBL_NEW_CATEGORY.' where menu_id = '.$menuId.' AND created_at >= "'.$timestamp.'"')->result_array();
                                // echo '<pre>';
                                // print_r($new_category);
                                // print_r($deleted_category);
                                // die;
                                if(!empty($new_category))
                                {
                                    foreach ($new_category as $key => $rows) 
                                    {
                                        $get_category= $this->db->query('SELECT * from  '.TBL_CATEGORIES.' where id = '.$rows['category_id'].'')->row_array();
                                        $data[$key] = array('id' => $get_category['id'],
                                        'order' => $get_category['order'],
                                        'title' => $get_category['title'],
                                        'arabian_title' => $get_category['arabian_title'],
                                        'background_image' => $get_category['background_image'],
                                        'image' => $get_category['image'],
                                        'order' => null,
                                        'is_active' => $get_category['is_active'],
                                        'is_deleted' => 0,
                                        'created_at' => $get_category['created_at'],
                                        'updated_at' => $rows['created_at'], //get timestamp from delete_category table
                                        'menu_id' => $rows['menu_id'] //get menu id from delete_category table
                                       );
                                    }
                                    $new = array_merge($new,$data);
                                    $updated = [];
                                    //$new = array_unique(array_merge($updated,$data));
                                    //$updated = array_diff($new,$data);
                                    // print_r(array_diff($new,$data));
                                    // die;
                                }
                                if(!empty($deleted_category))
                                {
                                    foreach ($deleted_category as $key => $rows) 
                                    {
                                        $get_category= $this->db->query('SELECT * from  '.TBL_CATEGORIES.' where id = '.$rows['category_id'].'')->row_array();
                                        $data[$key] = array('id' => $get_category['id'],
                                        'order' => $get_category['order'],
                                        'title' => $get_category['title'],
                                        'arabian_title' => $get_category['arabian_title'],
                                        'background_image' => $get_category['background_image'],
                                        'image' => $get_category['image'],
                                        'order' => null,
                                        'is_active' => $get_category['is_active'],
                                        'is_deleted' => 1,
                                        'created_at' => $get_category['created_at'],
                                        'updated_at' => $rows['created_at'], //get timestamp from delete_category table
                                        'menu_id' => $rows['menu_id'] //get menu id from delete_category table
                                       );
                                    }
                                    $deleted = $data;
                                }
                            } 
                        }else
                        {
                            $new = $this->db->query('SELECT DISTINCT cate.id,cate.order,cate.title,cate.arabian_title,cate.background_image,cate.image,cate.is_active,cate.is_deleted,cate.created_at,cate.updated_at,cd.menu_id FROM '.TBL_MENUS.' as menu,'.TBL_CATEGORY_DETAILS.' as cd ,'.TBL_CATEGORIES.' as cate WHERE menu.id = cd.menu_id AND cate.id = cd.category_id AND cate.is_deleted = 0 AND cd.menu_id = '.$menuId.' ORDER BY cate.order ASC')->result_array();
                            $updated = [];
                            $deleted = [];
                        }
                    //    print_r($this->array_column_multi($new, array('id','order','title','arabian_title','background_image','image','is_active','is_deleted','menu_id')));
                    //    die;
                        $this->response(['new' => $new, 'updated' => $updated,'deleted' => $deleted], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                    }else
                    {
                        $this->response([
                            'status' => false,
                            'message' => 'No categories were found',
                        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'No categories were found',
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
            }
        }else
        {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    function array_column_multi(array $input, array $column_keys) {
        $result = array();
        $column_keys = array_flip($column_keys);
        foreach($input as $key => $el) {
            $result[$key] = array_intersect_key($el, $column_keys);
        }
        return $result;
    }

    public function items_get($categoryId, $timestamp = null)
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) 
        {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) 
            {
                if (!is_null($categoryId)) 
                {
                    $categories = $this->users_model->sql_select(TBL_CATEGORIES, '*', ['where' =>['is_deleted' => 0, 'id' => $categoryId]], ['single' => false]);
                    if(!empty($categories))
                    {
                        if(!is_null($timestamp))
                        {
                            $timestamp = urldecode($timestamp);
                            $new = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 0 AND item.created_at > "'.$timestamp.'"  AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                            if(!empty($new))
                            {
                                // $updated = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 0 AND item.created_at < "'.$timestamp.'" AND item.updated_at != "" AND item.updated_at >= "'.$timestamp.'" AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                                $updated = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 0 AND item.created_at < "'.$timestamp.'" AND item.updated_at >= "'.$timestamp.'" AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                                $deleted = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 1 AND item.created_at < "'.$timestamp.'" AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                                
                                $deleted_item = $this->db->query('SELECT * from  '.TBL_DELETE_ITEMS.' where is_deleted = 1 AND category_id = '.$categoryId.' AND created_at >= "'.$timestamp.'"')->result_array();
                                $new_item = $this->db->query('SELECT * from  '.TBL_NEW_ITEMS.' where category_id = '.$categoryId.' AND created_at >= "'.$timestamp.'"')->result_array();
                                if(!empty($new_item))
                                {
                                    $new_data;
                                    foreach ($new_item as $key => $rows) 
                                    {
                                        $get_items= $this->db->query('SELECT * from  '.TBL_ITEMS.' where id = '.$rows['item_id'].'')->row_array();
                                        $new_data[$key] = array('id' => $get_items['id'],
                                        'order' => $get_items['order'],
                                        'title' => $get_items['title'],
                                        'arabian_title' => $get_items['arabian_title'],
                                        'price' => $get_items['price'],
                                        'description' => $get_items['description'],
                                        'arabian_description' => $get_items['arabian_description'],
                                        'calories' => $get_items['calories'],
                                        'is_featured' => $get_items['is_featured'],
                                        'is_dish_new' => $get_items['is_dish_new'],
                                        'time' => $get_items['time'],
                                        'type' => $get_items['type'],
                                        'is_active' => $get_items['is_active'],
                                        'is_deleted' => 0,
                                        'created_at' => $get_items['created_at'],
                                        'updated_at' => $rows['created_at'], //get timestamp from 
                                        'category_id' => $rows['category_id'] //get category_id
                                       );
                                    }
                                    $new = $new_data;
                                }
                                if(!empty($deleted_item))
                                {
                                    foreach ($deleted_item as $key => $rows) 
                                    {
                                        $get_items= $this->db->query('SELECT * from  '.TBL_ITEMS.' where id = '.$rows['item_id'].'')->row_array();
                                        $data[$key] = array('id' => $get_items['id'],
                                        'order' => $get_items['order'],
                                        'title' => $get_items['title'],
                                        'arabian_title' => $get_items['arabian_title'],
                                        'price' => $get_items['price'],
                                        'description' => $get_items['description'],
                                        'arabian_description' => $get_items['arabian_description'],
                                        'calories' => $get_items['calories'],
                                        'is_featured' => $get_items['is_featured'],
                                        'is_dish_new' => $get_items['is_dish_new'],
                                        'time' => $get_items['time'],
                                        'type' => $get_items['type'],
                                        'is_active' => $get_items['is_active'],
                                        'is_deleted' => 0,
                                        'created_at' => $get_items['created_at'],
                                        'updated_at' => $rows['created_at'], //get timestamp from 
                                        'category_id' => $rows['category_id'] //get category_id
                                       );
                                    }
                                    $deleted = $data;
                                }
                            }else
                            {
                                // $updated = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 0 AND item.updated_at != " " AND item.updated_at >= "'.$timestamp.'" AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                                $updated = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 0 AND item.updated_at >= "'.$timestamp.'" AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                                $deleted = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 1 AND item.updated_at >= "'.$timestamp.'" AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                                $deleted_item = $this->db->query('SELECT * from  '.TBL_DELETE_ITEMS.' where is_deleted = 1 AND category_id = '.$categoryId.' AND created_at >= "'.$timestamp.'"')->result_array();
                                if(!empty($deleted_item))
                                {
                                    foreach ($deleted_item as $key => $rows) 
                                    {
                                        $get_items= $this->db->query('SELECT * from  '.TBL_ITEMS.' where id = '.$rows['item_id'].'')->row_array();
                                        $data[$key] = array('id' => $get_items['id'],
                                        'order' => $get_items['order'],
                                        'title' => $get_items['title'],
                                        'arabian_title' => $get_items['arabian_title'],
                                        'price' => $get_items['price'],
                                        'description' => $get_items['description'],
                                        'arabian_description' => $get_items['arabian_description'],
                                        'calories' => $get_items['calories'],
                                        'is_featured' => $get_items['is_featured'],
                                        'is_dish_new' => $get_items['is_dish_new'],
                                        'time' => $get_items['time'],
                                        'type' => $get_items['type'],
                                        'is_active' => $get_items['is_active'],
                                        'is_deleted' => 0,
                                        'created_at' => $get_items['created_at'],
                                        'updated_at' => $rows['created_at'], //get timestamp from 
                                        'category_id' => $rows['category_id'] //get category_id
                                       );
                                    }
                                    $deleted = $data;
                                }
                            } 
                        }else
                        {
                            $new = $this->db->query('SELECT DISTINCT item.id,item.order,item.title,item.arabian_title,item.price,item.description,item.arabian_description,item.calories,item.is_featured,item.is_dish_new,item.time,item.type,item.is_active,item.is_deleted,item.created_at,item.updated_at,item_d.category_id FROM '.TBL_ITEMS.' as item,'.TBL_ITEM_DETAILS.' as item_d ,'.TBL_CATEGORIES.' as cate WHERE item.id = item_d.item_id AND cate.id = item_d.category_id AND item.is_deleted = 0 AND item_d.category_id = '.$categoryId.' ORDER BY item.order ASC')->result_array();
                            $updated = [];
                            $deleted = [];
                        }
                        
                        // Thumbnail images
                        if(!empty($new))
                        {
                            foreach ($new as $key => $rows) 
                            {
                                $itemImages = $this->users_model->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>['item_id' => $rows['id'] ,'is_deleted' => 0]], ['single' => false]);
                                $img = null;
                                foreach($itemImages as $row)
                                {
                                    $thumbnail = explode('.',$row['image']);
                                    if ($thumbnail['1'] === 'jpg' || $thumbnail['1'] === 'jpeg' || $thumbnail['1'] === 'png' || $thumbnail['1'] === 'PNG' || $thumbnail['1'] === 'JPG' || $thumbnail['1'] === 'JPEG') {
                                        $img = $thumbnail['0'].'_thumb.'.$thumbnail['1'];
                                        break;
                                    }
                                }
                                $data[$key] = array('id' => $rows['id'],
                                'order' => $rows['order'],
                                'title' => $rows['title'],
                                'arabian_title' => $rows['arabian_title'],
                                'price' => $rows['price'],
                                'description' => $rows['description'],
                                'arabian_description' => $rows['arabian_description'],
                                'calories' => $rows['calories'],
                                'is_featured' => $rows['is_featured'],
                                'is_dish_new' => $rows['is_dish_new'],
                                'time' => $rows['time'],
                                'type' => $rows['type'],
                                'is_active' => $rows['is_active'],
                                'is_deleted' => 0,
                                'created_at' => $rows['created_at'],
                                'updated_at' => $rows['updated_at'], //get timestamp from 
                                'category_id' => $rows['category_id'], //get category_id
                                'thumbnail' => $img
                               );
                            }
                            $new = $data;
                        }

                        // Thumbnail images
                        if(!empty($updated))
                        {
                            foreach ($updated as $key => $rows) 
                            {
                                $itemImages = $this->users_model->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>['item_id' => $rows['id'] ,'is_deleted' => 0]], ['single' => false]);
                                $img = null;
                                foreach($itemImages as $row)
                                {
                                    $thumbnail = explode('.',$row['image']);
                                    if ($thumbnail['1'] === 'jpg' || $thumbnail['1'] === 'jpeg' || $thumbnail['1'] === 'png' || $thumbnail['1'] === 'PNG' || $thumbnail['1'] === 'JPG' || $thumbnail['1'] === 'JPEG') {
                                        $img = $thumbnail['0'].'_thumb.'.$thumbnail['1'];
                                        break;
                                    }
                                }
                                // if(!empty($itemImages[0]))
                                // {
                                //     $img = $itemImages[0]['image'];
                                //     $thumbnail = explode('.',$img);
                                //     $img = $thumbnail['0'].'_thumb.'.$thumbnail['1'];
                                // }
                                $data[$key] = array('id' => $rows['id'],
                                'order' => $rows['order'],
                                'title' => $rows['title'],
                                'arabian_title' => $rows['arabian_title'],
                                'price' => $rows['price'],
                                'description' => $rows['description'],
                                'arabian_description' => $rows['arabian_description'],
                                'calories' => $rows['calories'],
                                'is_featured' => $rows['is_featured'],
                                'is_dish_new' => $rows['is_dish_new'],
                                'time' => $rows['time'],
                                'type' => $rows['type'],
                                'is_active' => $rows['is_active'],
                                'is_deleted' => 0,
                                'created_at' => $rows['created_at'],
                                'updated_at' => $rows['updated_at'], //get timestamp from 
                                'category_id' => $rows['category_id'], //get category_id
                                'thumbnail' => $img
                               );
                            }
                            $updated = $data;
                        }

                        // Thumbnail images
                        if(!empty($deleted))
                        {
                            $dele;
                            foreach ($deleted as $key => $rows) 
                            {
                               // print_r($rows['id']);
                                $itemImages = $this->users_model->sql_select(TBL_ITEM_IMAGES, '*', ['where' =>['item_id' => $rows['id'] ,'is_deleted' => 0]], ['single' => false]);
                                $img = null;
                                foreach($itemImages as $row)
                                {
                                    $thumbnail = explode('.',$row['image']);
                                    if ($thumbnail['1'] === 'jpg' || $thumbnail['1'] === 'jpeg' || $thumbnail['1'] === 'png' || $thumbnail['1'] === 'PNG' || $thumbnail['1'] === 'JPG' || $thumbnail['1'] === 'JPEG') {
                                        $img = $thumbnail['0'].'_thumb.'.$thumbnail['1'];
                                        break;
                                    }
                                }
                                // if(!empty($itemImages[0]))
                                // {
                                //     $img = $itemImages[0]['image'];
                                //     $thumbnail = explode('.',$img);
                                //     $img = $thumbnail['0'].'_thumb.'.$thumbnail['1'];
                                // }
                                $dele[$key] = array('id' => $rows['id'],
                                'order' => $rows['order'],
                                'title' => $rows['title'],
                                'arabian_title' => $rows['arabian_title'],
                                'price' => $rows['price'],
                                'description' => $rows['description'],
                                'arabian_description' => $rows['arabian_description'],
                                'calories' => $rows['calories'],
                                'is_featured' => $rows['is_featured'],
                                'is_dish_new' => $rows['is_dish_new'],
                                'time' => $rows['time'],
                                'type' => $rows['type'],
                                'is_active' => $rows['is_active'],
                                'is_deleted' => 0,
                                'created_at' => $rows['created_at'],
                                'updated_at' => $rows['updated_at'], //get timestamp from 
                                'category_id' => $rows['category_id'], //get category_id
                                'thumbnail' => $img
                               );
                            }
                            $deleted = $dele;
                        }
                        $this->response(['new' => $new, 'updated' => $updated,'deleted' => $deleted], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                    }else
                    {
                        $this->response([
                            'status' => false,
                            'message' => 'No items were found',
                        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'No items were found',
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
            }
        }
        else
        {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

      /**
     * @api {Post} api/registration Restaurant Registration
     * @apiName Restaurant Registration
     *
     * @apiGroup Registration
     *
     * @apiParam {name} name name of the Restaurant.
     * @apiParam {email} email email of the Restaurant.
     * @apiParam {password} password password of the Restaurant. 
     * @apiParam {confirm_password} confirm_password Confirm password of the Restaurant. 
     *
     * @apiSuccess {String} success success message of registration.
     */
    public function registration_post()
    {
        if(!empty($this->input->post()))
        {
            $name = trim($this->input->post('name'));
            $email = trim($this->input->post('email'));
            $password = trim($this->input->post('password'));
            $phone_number = trim($this->input->post('phone_number'));
            $confirm_password = trim($this->input->post('confirm_password'));
            //Check null value or not
            if(!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password))
            {
                $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
                if(preg_match($pattern, $email))
                {
                    $result = $this->users_model->get_user_detail(['email' => trim($email), 'is_deleted' => 0]);
                    if(!empty($result))
                    {
                        $this->set_response([
                            'status' => false,
                            'message' => 'Email is already exists.',
                        ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                    }else{
                        if($password === $confirm_password)
                        {
                            $dataArr = array(
                                'role' => RESTAURANT,
                                'name' => trim($name),
                                'devices_limit' => 0,
                                'users_limit' => 0,
                                'menus_limit' => 0,
                                'categories_limit' => 0,
                                'items_limit' => 0,
                                'is_active' => 0
                            );
                      
                            $password = $this->input->post('password');
                            $dataArr['email'] = trim($email);
                            $dataArr['password'] = password_hash($password, PASSWORD_BCRYPT);
                            $dataArr['phone_number'] =$phone_number;
                            $dataArr['created_at'] = date('Y-m-d H:i:s');
                            $inserted_id = $this->users_model->common_insert_update('insert', TBL_USERS, $dataArr); 
                            
                            if($this->input->post('currency')){
                            $dataArr_settings = array(
                                'user_id' => $inserted_id,
                                'currency' => $this->input->post('currency')
                            );
                             $dataArr_settings['created_at'] = date('Y-m-d H:i:s');
                             $this->users_model->common_insert_update('insert', TBL_SETTINGS, $dataArr_settings);
                            }

                            $default_staff_id = $this->users_model->common_insert_update('insert', TBL_USERS, ['role' => STAFF,'name' => 'staff','restaurant_id' => $inserted_id,'is_active' => 1]); //Insert Default Staff user 

                            $directory = RESTAURANT_IMAGES . '/' . $inserted_id;
                            if (!file_exists(RESTAURANT_IMAGES)) {
                                mkdir(RESTAURANT_IMAGES);
                            }
                            if (!file_exists($directory)) {
                                mkdir($directory);
                            }
                        
                            $verification_code = verification_code();
                            $this->users_model->common_insert_update('update', TBL_USERS, array('verification_code' => $verification_code), array('id' => $inserted_id));
                            $url = base_url() . 'login?code='.base64_encode($verification_code);
                            $email_data = ['name' => trim($name), 'type' => 'restaurant','email' => trim($email), 'url' => $url, 'password' => $password, 'subject' => 'Welcome! Please confirm your email'];
                        
                          
                      $email_data2 = ['name' => trim($this->input->post('name')), 'type' => 'restaurant','email' => trim($this->input->post('email')), 'phone_number' => @$phone_number,'subject' => 'New User signup In Cherrymenu '];
                            //Send registration mail.
                            send_email(trim($this->input->post('email')), 'new_restaurant', $email_data);
                            send_email('info@cherrymenu.com', 'new_restaurant2', $email_data2);

                            //send_email('alissar@virtualdusk.com', 'new_restaurant2', $email_data2); 
                            //$this->freeTrial(trim($email), trim($name));
                         
                           // code for Default setting
                            $this->defaultVal($inserted_id);
                        // end

                            //Get Free package if exist in Package Detail table.
                           // $freeTriel = $this->users_model->sql_select(TBL_PACKAGES,'*',['is_deleted' => 0,'is_active' => 1,'type' => 'free'],['single' => true]);
                            
                            //$freeTriel = $this->Packages_model->get_package_detail(['is_deleted' => 0,'is_active' => 1,'type' => 'free']);
                            // if(!empty($freeTriel))
                            // {
                            //     $package = array(
                            //         'package_id' => $freeTriel['id'],
                            //         'restaurant_id' => $inserted_id,
                            //         'status' => 'activate',
                            //         'flag' => 1
                            //     );
                            //     $package['request_date'] = date('Y-m-d');
                            //     $package['created_at'] = date('Y-m-d H:i:s');
                            //     //Assign free packages limit.
                            //     $this->users_model->common_insert_update('update', TBL_USERS, ['devices_limit' => $freeTriel['devices_limit'], 'users_limit' => $freeTriel['users'], 'menus_limit' => $freeTriel['menus'], 'categories_limit' => $freeTriel['categories'],'items_limit' => $freeTriel['items']], ['id' => $inserted_id]);
                            //     $package_detail_id = $this->users_model->common_insert_update('insert', TBL_PACKAGE_DETAILS, $package); 
                            // }
                            $this->response([
                                'status' => true,
                                'message' => 'Restaurant has been registered successfully and Email has been sent to user successfully',
                            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

                        }else{
                            $this->set_response([
                                'status' => false,
                                'message' => 'Invalid confirm password',
                            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                        }
                    }
                }else{
                    $this->set_response([
                        'status' => false,
                        'message' => 'invalid email',
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
            }
            else
            {
                $this->set_response([
                    'status' => false,
                    'message' => 'Require all fields',
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }else{
            $this->set_response([
                'status' => false,
                'message' => 'Require all fields',
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }


    public function countries_get(){
        $currencies=$this->users_model->run_manual_query('SELECT * FROM `default_currencies` ');
         $this->response([ 'currencies' => $currencies, 
                            ], REST_Controller::HTTP_OK);

    }

    /**
    * This function used to send email for free trial
    * 
    */
    public function freeTrial($email,$name)
    {
        $email_data = ['name' => $name, 'email' => trim($email),'subject' => 'Your trial has started!'];
        $email_data['head_title'] = "Welcome on board!";
        $email_data['message'] = "Your 30 days trial has started, we shall be sending you few reminders before the trial expires";
        //$this->load->view('Backend/email_templates/free_trial',$email_data);
        send_email(trim($email), 'reminder', $email_data);
    }


    public function order_post()
    {       
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data)){
          foreach ($data->ListOfOrders as $key => $row){   
                       $order = [
                        'restaurant_id' => $row->restaurant_id,
                        'staff_id' => $row->staff_id,
                        'ordered_time' => $row->ordered_time,
                        ];  
                     $inserted_id = $this->users_model->common_insert_update('insert', TBL_ORDER, $order);
                     if($inserted_id){
                            foreach($row->orders as $ord){
                                $orderdetails = [
                                    'item_id' => $ord->item_id,
                                    'order_id' => $inserted_id,
                                    'category_id' => $ord->category_id,
                                    'menu_id' => $ord->menu_id,
                                    'item_qty' => $ord->item_qty,
                                    'item_price' => $ord->item_price,
                                ];
                            $inserted_order = $this->users_model->common_insert_update('insert', TBL_ORDER_DETAILS, $orderdetails);  
                             }
                        } 
                        if (!empty($inserted_order)){
                            $this->set_response([
                                'status' => true,
                                'message' => 'Order inserted successfully',
                            ], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
                        }else{
                            $this->set_response([
                                'status' => false,
                                'message' => 'Something went wrong',
                            ], REST_Controller::HTTP_NOT_FOUND); 
                        }       
            }  
        }
        
        
    }
   /* public function updateitemsync_post()
    {       $timestamp = trim($this->input->post('timestamp'));
            $restaurant_id = trim($this->input->post('restaurant_id'));
        if(!empty($this->input->post()))
        {
           $result = $this->users_model->getitemsync($timestamp,$restaurant_id);
           $result1 = $this->users_model->getstaffsync($timestamp,$restaurant_id);
           $result2 = $this->users_model->getbackgroundsync($timestamp,$restaurant_id);
        // echo  $this->db->last_query(); die;
           if($result > 0 || $result1 > 0 || $result2 > 0){
            $this->response([
                                'status' => true,
                                'message' => 'Update Required',
                            ], REST_Controller::HTTP_OK);
           }else{
            $this->set_response([
                'status' => false,
                'message' => 'No updates',
            ], REST_Controller::HTTP_NOT_FOUND);
           }
           

        }
    }
    public function GetLatestTimestamp_post()
    {       
            $restaurant_id = trim($this->input->post('restaurant_id'));
        if(!empty($this->input->post()))
        {
           $result = $this->db->query("SELECT GREATEST(MAX(m.created_at), MAX(m.updated_at),MAX(u.created_at), MAX(u.updated_at),MAX(s.created_at), MAX(s.updated_at)) as latesttimestamp FROM menus m LEFT JOIN users as u on u.restaurant_id = m.restaurant_id LEFT JOIN settings as s on s.user_id = m.restaurant_id WHERE m.restaurant_id = '".$restaurant_id."'");
           
        // echo  $this->db->last_query(); die;
           if($result->num_rows() > 0 ){
                foreach ($result->result() as $row)
                {
                        $time = $row->latesttimestamp;
                }
            $this->response([   'latesttimestamp' => $time,
                                'status' => true,
                                'message' => 'Update Required',
                            ], REST_Controller::HTTP_OK);
           }else{
            $this->set_response([
                'latesttimestamp' => '',
                'status' => false,
                'message' => 'No updates',
            ], REST_Controller::HTTP_NOT_FOUND);
           }
           

        }
    }*/






        function defaultVal($ins_id=''){

         $userId=$ins_id;
                $directory_background = RESTAURANT_IMAGES . '/' . $userId. '/menus/backgrounds/';
                if (!file_exists(RESTAURANT_IMAGES . '/' . $userId. '/menus/')) {
                    mkdir(RESTAURANT_IMAGES . '/' . $userId. '/menus/');
                }
                if (!file_exists($directory_background)) {
                    mkdir($directory_background);
                }
            
                $file = '/var/www/html/login/public/bck.png';
                copy($file,'/var/www/html/login/'.$directory_background.'bck.png');
              //move_uploaded_file('/var/www/html/login/public/bck.png', $directory_background."bck.png");

       

         $menus = $this->Menus_model->get_menus_default('result');
         foreach ($menus as $key => $val) {
         
               $file = '/var/www/html/login/public/restaurants/505/menus/backgrounds/'.$val['background_image'];
                copy($file,'/var/www/html/login/'.$directory_background.''.$val['background_image']);

            $dataArr = array(
                  'restaurant_id' => $userId,
                 'title' => $val['title'],
                 'arabian_title' => $val['arabian_title'],
                'background_image' => $val['background_image'],
                'is_disable_feedback' =>$val['is_disable_feedback'],
               'is_active' =>$val['is_active'],
                );
          $dataArr['created_at'] = date('Y-m-d H:i:s');
          $menuId=$this->Menus_model->common_insert_update('insert', TBL_MENUS, $dataArr);
        }
          
       $categories = $this->Categories_model->get_categories_default('result');
     
     foreach($categories as $category){
          

            $id = $category['id'];
            $menu_ids = ($this->Categories_model->sql_select(TBL_CATEGORY_DETAILS, '*', ['where' => ['category_id' => $id]]));
           
                 $directory_background2 = RESTAURANT_IMAGES . '/' . $userId. '/categories/backgrounds/';
                 $directory_background3 = RESTAURANT_IMAGES . '/' . $userId. '/categories/';
                if (!file_exists(RESTAURANT_IMAGES . '/' . $userId. '/categories/')) {
                    mkdir(RESTAURANT_IMAGES . '/' . $userId. '/categories/');
                }
                if (!file_exists($directory_background2)) {
                    mkdir($directory_background2);
                }            
            
    
         $defaultId=505;

        $file1=RESTAURANT_IMAGES . '/' . $defaultId. '/categories/backgrounds/'.$category['background_image'];
       copy($file1,'/var/www/html/login/'.$directory_background2.''.$category['background_image']);

        $file2=RESTAURANT_IMAGES . '/' . $defaultId. '/categories/'.$category['image'];
      copy($file2,'/var/www/html/login/'.$directory_background3.''.$category['image']);

               $dataArr3 = array(
                    'title' => trim($category['title']),
                    'arabian_title' => trim($category['arabian_title']),
                    'background_image' =>$category['background_image'],
                    'image' => $category['image'],
                    'is_active' =>$category['is_active'],
                );
                $dataArr3['created_at'] = date('Y-m-d H:i:s');

             $inserted_id = $this->Categories_model->common_insert_update('insert', TBL_CATEGORIES, $dataArr3);

       
             $user_menus = $this->Menus_model->get_user_menus('result',$userId);

             //$default_menus = $this->Menus_model->get_user_menus('result',505);
            
             foreach($menu_ids as $user_menusId)
                {  
                   
                   $default_data=$this->Menus_model->get_default_menu(['id' => $user_menusId['menu_id'], 'is_deleted' => 0]);
               
                   if(!empty($default_data)){

                     foreach ($user_menus as  $user_menu) {
                       
                       if($default_data[0]['title']==$user_menu['title']){

                     $Menu_Id=$user_menu['id'];
                  $this->users_model->common_delete(TBL_CATEGORY_DETAILS, ['menu_id' => $Menu_Id,'category_id' => $inserted_id]);
                    $this->Categories_model->common_insert_update('insert', TBL_NEW_CATEGORY, array('menu_id' => $Menu_Id,'category_id' => $inserted_id,'created_at' =>  date('Y-m-d H:i:s')));
                    $this->Categories_model->common_insert_update('insert', TBL_CATEGORY_DETAILS, array('menu_id' => $Menu_Id,'category_id' => $inserted_id));
                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $Menu_Id]);



                       }
                     } 


                   }             
                                            
                 
                                            

                }

            
       }               
                     
               

                $this->item1($userId);    
        
     }


     public function basicinfo()
     {
        $response='{
        "status":"1",
                                                 "message":"Success",
        "data":"app_update_type":"1","ios_version_number":"2.0.0","version_number":"12.0","app_update_text":"A new version of SouqPlace App is available. Bug fixes and further enhancement. Please update to new version now."}}';
        print_r(json_encode($response));
     }


function item1($userId=''){

        $userId=$userId;
        $defaultId=505;
        $sql = "SELECT `i`.*
        FROM `item_details` `idls`
        LEFT JOIN `items` as `i` ON `idls`.`item_id`=`i`.`id`
        LEFT JOIN `categories` as `ca` ON `idls`.`category_id`=`ca`.`id`
        LEFT JOIN `menus` as `m` ON `idls`.`menu_id`=`m`.`id`
        WHERE `m`.`restaurant_id` = '505'
        AND `i`.`is_deleted` = 0
        AND `ca`.`is_deleted` = 0
        AND `m`.`is_deleted` = 0
        GROUP BY `i`.`id`
        ORDER BY `i`.`order` ASC
        LIMIT 50";
        $query=$this->db->query($sql);
        $results = $query->result_array();
        $userItem=[];

      foreach($results as $result){


          $userItem['title']=$result['title'];
          $userItem['arabian_title']=$result['arabian_title'];
          $userItem['price']=$result['price'];
          $userItem['calories']=$result['calories'];
          $userItem['description']=$result['description'];
          $userItem['arabian_description']=$result['arabian_description'];
          $userItem['type']=$result['type'];
          $userItem['time']=$result['time'];
          $userItem['is_featured']=$result['is_featured'];
          $userItem['is_dish_new']=$result['is_dish_new'];
          $userItem['created_at']=date('Y-m-d H:i:s');
          $inserted_id = $this->Items_model->common_insert_update('insert', TBL_ITEMS, $userItem);
       
        

          $itemId=$result['id'];
          $itemTitle=$result['title'];


        $itemImages=$this->Items_model->get_item_images_new($type = 'result',$itemId);
        if(!empty($itemImages)){
           
             foreach ($itemImages as  $itemImage) {

           $thumbnail = explode('.',$itemImage['image']);
            if ($thumbnail['1'] === 'jpg' || $thumbnail['1'] === 'jpeg' || $thumbnail['1'] === 'png') {
                 $thumbnailImg = $thumbnail['0'].'_thumb.'.$thumbnail['1'];
               
            }
                    
                    $directory = RESTAURANT_IMAGES . $userId. '/items/' .$inserted_id;
                    $thumbnailDirectory = RESTAURANT_IMAGES . $userId. '/items/' .$inserted_id.'/thumbnail';
                    if (!file_exists(RESTAURANT_IMAGES . $userId. '/items/')) {
                        mkdir(RESTAURANT_IMAGES . $userId. '/items/');
                    }


                     if (!file_exists($thumbnailDirectory)) {
                        mkdir($thumbnailDirectory,0777, true); 
                    }
                     if (!file_exists($directory)) {
                           mkdir($directory);
                      }

                 /*   $thumbnailDirectory ="/var/www/html/login/public/restaurants/$userId/items/$inserted_id/thumbnail";
                  if (!file_exists($thumbnailDirectory)) {
                        mkdir( $thumbnailDirectory, 0777, true);
                    }*/
                     $file2=RESTAURANT_IMAGES . '/' . $defaultId. '/items/'.$itemId.'/'.$itemImage['image'];
                    copy($file2,'/var/www/html/login/'.$directory.'/'.$itemImage['image']);
                    
                    $file3=RESTAURANT_IMAGES .'/'. $defaultId.'/items/'.$itemId.'/thumbnail/'.$thumbnailImg;
                    copy($file3,'/var/www/html/login/'.$thumbnailDirectory.'/'.$thumbnailImg);
                  

        $dataArrNew = array(
            'item_id' => $inserted_id,
            'image' => $itemImage['image'],
        );
        $dataArrNew['created_at'] = date('Y-m-d H:i:s');
         $this->Items_model->common_insert_update('insert', TBL_ITEM_IMAGES, $dataArrNew);
          
          }
        }          

          $demoItemsDetails=$this->Items_model->get_default_item(['item_id ' =>$itemId]);

          $demoCatIds=[];

           foreach ($demoItemsDetails as $key => $demoItemData) {
              $demoCatIds[]=$demoItemData['category_id'];
          
          }


                    foreach ($demoCatIds as $key => $demoCatId) {
           $catDetail=$this->Categories_model->get_category_detail(['id ' =>$demoCatId]);
           $demoCatTitle=$catDetail['title'];
           $user_categories =$this->Categories_model->get_categories_user_default($type = 'result',$userId);
           foreach ($user_categories as  $user_categor) {
            if( trim($demoCatTitle) == trim($user_categor['title']) ){
                       $userCatId=$user_categor['id'];
                       $Category_Id=$user_categor['id'];
                       $user_cat_details =$this->Categories_model->get_category_details(['category_id ' =>$userCatId]);
                     foreach ($user_cat_details as  $user_cat_detail) {
                     $menus_ids = $this->Items_model->get_menu_ids_new($userCatId,$userId);
                      if(!empty($menus_ids))
                    {
                        foreach ($menus_ids as $key => $rows) 
                        {
                            //echo "in";
                            $this->Categories_model->common_delete(TBL_NEW_ITEMS, ['menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id]);
                            $this->Categories_model->common_insert_update('insert', TBL_NEW_ITEMS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id,'created_at'=>date('Y-m-d H:i:s')));
                            $this->Categories_model->common_insert_update('insert', TBL_ITEM_DETAILS, array('menu_id' => $rows['id'],'item_id' => $inserted_id,'category_id' => $Category_Id));
                            $this->users_model->common_insert_update('update', TBL_CATEGORIES,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $Category_Id]);
                            $menus = ($this->Items_model->sql_select(TBL_CATEGORY_DETAILS, 'menu_id', ['where' => ['category_id' => $Category_Id]])); //get menu Id's
                            if(!empty($menus))
                            {
                                foreach ($menus as $key => $value) 
                                {
                                    $this->users_model->common_insert_update('update', TBL_MENUS,['updated_at' => date('Y-m-d H:i:s')] , ['id' => $value['menu_id']]);   
                                }
                            }
                        }
                        
                    }


                     }


                   }
              }

          }


      }


}
    
}
