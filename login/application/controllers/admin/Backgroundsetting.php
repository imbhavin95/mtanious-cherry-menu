<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Backgroundsetting extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Settings_model');
    }

    public function index($id = null)
    {
        $settings = $this->Settings_model->get_settings_detail(['user_id' => 1, 'is_deleted' => 0, 'is_active' => 1]);
        // $this->form_validation->set_rules('backgroundimage', 'Background Image', 'required');
        $image = isset($settings) ? $settings['bg_before_login'] : null;
        $data['settings'] = $settings;
        $data['title'] = WEBNAME.' | Background Setting';
        $data['head'] = 'Background Setting';
        $data['label'] = 'Background Setting';
        $appversiondata=$this->users_model->run_manual_query('select * from android_app_version where id=1');
        $data['app_version']=$appversiondata[0]['android_version'];

        if (isset($_FILES['backgroundimage'])) {
            $flag = 0;
            if ($_FILES['backgroundimage']['name'] != '') {
                $image_data = upload_image('backgroundimage', DEFAULT_BACKGROUND);
                if (is_array($image_data)) {
                    $flag = 1;
                    $data['profile_image_validation'] = $image_data['errors'];
                } else {
                    if ($image != '') {
                        unlink(DEFAULT_BACKGROUND . $image);
                    }
                    $image = $image_data;
                }
            }
            $dataArr = array(
                'user_id' => 1,
                'bg_before_login' => $image,
            );
            $dataArr1 = array(
                  'android_version' => $this->input->post('android_version'),
            );
            if ($settings) {
                $dataArr['updated_at'] = date('Y-m-d H:i:s');
                $this->users_model->common_insert_update('update', TBL_SETTINGS, $dataArr, ['id' => $this->input->post('hidden_id')]);
                $this->users_model->common_insert_update('update', 'android_app_version1', $dataArr1, ['id' => '1']);
            } else {
                $dataArr['created_at'] = date('Y-m-d H:i:s');
                $inserted_id = $this->users_model->common_insert_update('insert', TBL_SETTINGS, $dataArr);
            }

            $this->session->set_flashdata('success', 'Background Setting has been added successfully');
            redirect('admin/backgroundsetting');
        }
        $this->template->load('default', 'Backend/admin/settings/manage', $data);
    }
}
