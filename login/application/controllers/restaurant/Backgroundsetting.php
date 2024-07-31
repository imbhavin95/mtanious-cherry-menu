<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BackgroundSetting extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Settings_model');
        $this->load->model('Menus_model');
        if(is_sub_admin())
        {
            $this->session->set_flashdata('error', 'Access denied!');
            redirect('restaurant/menus');
        }
    }

    public function index($id = null)
    {
        $settings = $this->Settings_model->get_settings_detail(['user_id' => $this->session->userdata('login_user')['id'], 'is_deleted' => 0, 'is_active' => 1]);
        $image = isset($settings) ? $settings['bg_after_login'] : null;
        $logo_image = isset($settings) ? $settings['logo'] : null;
        $rest_video = isset($settings) ? $settings['video'] : null;
        $data['settings'] = $settings;
        $data['rest_name']=$rest_name=$settings['rest_name'];
        $data['title'] = WEBNAME.' | ID Settings';
        $data['head'] = 'ID Settings';
        $data['label'] = 'ID Settings';
        $data['currencies']=$this->users_model->run_manual_query('SELECT * FROM `default_currencies` ');
        // $rest_name=str_replace(' ', '_', $this->input->post('rest_name'));		
        $rest_name=$rest_name ? str_replace('', '_', $rest_name): "";

        if(isset($_POST) && !empty($_POST)){
          $restnameres=$this->Menus_model->sql_select(TBL_SETTINGS, 'id', ['where' => ['is_deleted' => 0, 'is_active' => 1,'rest_name' => $rest_name,'user_id!=' =>$this->session->userdata('login_user')['id']]], ['count' => true]);
        if($restnameres){
            $this->session->set_flashdata('error', 'Restaurant name taken. Please enter different one');
            redirect('restaurant/backgroundsetting');
        }
        }
       

        if (isset($_FILES['backgroundimage'])) {
            if ($_FILES['backgroundimage']['name'] != '') {
                /*$image_data = upload_image('backgroundimage', DEFAULT_BACKGROUND);
                if (is_array($image_data)) {
                    $data['background_image_validation'] = $image_data['errors'];
                } else {
                    if ($image != '') {
                        unlink(DEFAULT_BACKGROUND . $image);
                    }
                    $image = $image_data;
                }*/
                        $extension = explode('/', $_FILES['backgroundimage']['type']);
                        $randname = uniqid() . time() . '.' . end($extension);
                        if($_FILES['backgroundimage']['size'] / 1024 <= 2048) { // 2MB
                         if($_FILES['backgroundimage']['type'] == 'image/jpeg' || 
                         $_FILES['backgroundimage']['type'] == 'image/pjpeg' || 
                         $_FILES['backgroundimage']['type'] == 'image/png' ||
                         $_FILES['backgroundimage']['type'] == 'image/gif'){
                      
                            $source_file = $_FILES['backgroundimage']['tmp_name'];
                            $target_file = DEFAULT_BACKGROUND . $randname; 
                            $width      = '';
                            $height     = '';
                            $quality    = '40';
                            //$image_name = $_FILES['uploadImg']['name'];
                            $success = compress_image($source_file, $target_file, $width, $height, $quality);
                            if ($image != '') {
                                unlink(DEFAULT_BACKGROUND . $image);
                                }
                            $image = $randname;
                                }
                             }
            }

            if ($_FILES['logo']['name'] != '') {
                /*$image_data = upload_image('logo', LOGO_IMG);
                if (is_array($image_data)) {
                    $data['logo_image_validation'] = $image_data['errors'];
                } else {
                    if ($logo_image != '') {
                        unlink(LOGO_IMG . $logo_image);
                    }
                    $logo_image = $image_data;
                }*/
                        $extension = explode('/', $_FILES['logo']['type']);
                        $randname = uniqid() . time() . '.' . end($extension);
                        if($_FILES['logo']['size'] / 1024 <= 4096) {  // 4MB
                         if($_FILES['logo']['type'] == 'image/jpeg' || 
                         $_FILES['logo']['type'] == 'image/pjpeg' || 
                         $_FILES['logo']['type'] == 'image/png' ||
                         $_FILES['logo']['type'] == 'image/gif'){
                      
                            $source_file = $_FILES['logo']['tmp_name'];
                            $target_file = LOGO_IMG . $randname; 
                            $width      = '';
                            $height     = '';
                            $quality    = '40';
                            //$image_name = $_FILES['uploadImg']['name'];
                            $success = compress_image($source_file, $target_file, $width, $height, $quality);
                            if ($logo_image != '') {
                                unlink(LOGO_IMG . $logo_image);
                                }
                            $logo_image = $randname;
                                }
                             }
                             

            }

            

            if ($_FILES['video']['name'] != '') {

                    //$allowedExts = array("jpg", "jpeg", "gif", "png", "mp3", "mp4", "wma");
                    $allowedExts = array("mp4");
                    //$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

                    $extension = explode('/', $_FILES['video']['type']);
                    $randname = uniqid() . time() . '.' . end($extension);

                    if (($_FILES["video"]["type"] == "video/mp4")
                    && ($_FILES["video"]["size"] < 50000000)
                    && in_array('mp4',$extension))
                      {

                        $source_file = $_FILES['video']['tmp_name'];
                            $target_file = LOGO_REST . $randname; 
                            $width      = '';
                            $height     = '';
                            $quality    = '100';
                            //$image_name = $_FILES['uploadImg']['name'];
                               //compress_image($source_file, $target_file, $width, $height, $quality);

                            $success =move_uploaded_file($_FILES["video"]["tmp_name"],$target_file);
                               
                            if ($rest_video != '') {
                                unlink(LOGO_REST . $rest_video);
                                }
                            $rest_video = $randname;
                                }else{ 
                                 $this->session->set_flashdata('error', 'Video not uploaded. Please read note for video upload');
                                 redirect('restaurant/backgroundsetting');
                                }
                             } 

            $dataArr = array(
                'user_id' => $this->session->userdata('login_user')['id'],
                'bg_after_login' => $image,
                'logo' => $logo_image,
                'video' => $rest_video,
                'currency' =>$this->input->post('currency_code'),
                'rest_name' => $rest_name
            );
            if ($settings) {
                $dataArr['updated_at'] = date('Y-m-d H:i:s');
                $this->users_model->common_insert_update('update', TBL_SETTINGS, $dataArr, ['id' => $this->input->post('hidden_id')]);
            } else {
                $dataArr['created_at'] = date('Y-m-d H:i:s');
                $inserted_id = $this->users_model->common_insert_update('insert', TBL_SETTINGS, $dataArr);
            }
            $this->session->set_flashdata('success', 'Background Setting has been added successfully');
            redirect('restaurant/backgroundsetting');
        }
        $this->template->load('default', 'Backend/restaurant/settings/manage', $data);
    }
}
