<?php

/**
 * Common Helper
 * Common functions used
 * @author KU
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Common Send Email Function
 * @param string $to - To Email ID
 * @param string $template - Email Template file
 * @param Array $data - Data to be passed
 * @return boolean
 */
function send_email($to = '', $template = '', $data = [],$attach = null)
{
//    print_r($data);
//    exit;
    if (empty($to) || empty($template) || empty($data)) {
        return false;
    }
    $ci = &get_instance();
    $ci->load->library('email');

    $config['protocol'] = 'smtp';
    $config['smtp_host'] = 'ssl://smtp.gmail.com';
    $config['smtp_port'] = '465';
    $config['smtp_user'] = 'cherrymenu6@gmail.com';//'Send@cherrymenu.com';//'pav.narola@gmail.com';//'info@cherrymenu.com'//cherrymenuapp@gmail.com
    $config['smtp_pass'] = 'csku yjbf esuj jgfz';//'rnspmbuuwmjtbduo';//'Send13579/';//'narola21';//'Tony6@tony'
    $config['charset'] = 'utf-8';
    $config['newline'] = "\r\n";
    $config['mailtype'] = 'html';
    $config['validation'] = true;

    $ci->email->initialize($config);
     
    $ci->email->clear(TRUE);
    $ci->email->to($to);
    $ci->email->from('no-reply@Cherrymenu.com', 'Cherrymenu');
    $ci->email->subject($data['subject']);
    $view = $ci->load->view('email_templates/' . $template, $data, true);

    $ci->email->message($view);
    if($attach != null)
    {
        $ci->email->attach($attach);
    }
    $ci->email->send();
   // print_r( $ci->email->print_debugger());die;
}




/**
 * Common Send Email Function
 * @param string $to - To Email ID
 * @param string $template - Email Template file
 * @param Array $data - Data to be passed
 * @return boolean
 */
function send_email_multiple_attachment($to = '', $template = '', $data = [],$attach = null)
{
    if (empty($to) || empty($template) || empty($data)) {
        return false;
    }
    $ci = &get_instance();
    $ci->load->library('email');

    $config['protocol'] = 'smtp';
    $config['smtp_host'] = 'ssl://smtp.gmail.com';
    $config['smtp_port'] = '465';
    $config['smtp_user'] = 'cherrymenuapp@gmail.com';//'Send@cherrymenu.com';//'pav.narola@gmail.com';//'info@cherrymenu.com'
    $config['smtp_pass'] = 'Tony6@tony';//'rnspmbuuwmjtbduo';//'Send13579/';//'narola21';//'Tony6@tony'
    $config['charset'] = 'utf-8';
    $config['newline'] = "\r\n";
    $config['mailtype'] = 'html';
    $config['validation'] = true;

    $ci->email->initialize($config);

    $ci->email->to($to);
    $ci->email->from('no-reply@Cherrymenu.com', 'Cherrymenu');
    $ci->email->subject($data['subject']);
    $view = $ci->load->view('Backend/email_templates/' . $template, $data, true);
    $ci->email->message($view);
    if($attach != null)
    {
        foreach ($attach  as $value) {
         $ci->email->attach($value);
        }
    }
    $ci->email->send();
   // print_r( $ci->email->print_debugger());die;
}

/**
 * Print array/string.
 * @param array $data - data which is going to be printed
 * @param boolean $is_die - if set to true then excecution will stop after print.
 */
function p($data, $is_die = false)
{

    if (is_array($data)) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    } else {
        echo $data;
    }

    if ($is_die) {
        die;
    }

}

/**
 * Print last executed query
 * @param boolean $bool - if set to true then excecution will stop after print
 */
function qry($bool = false)
{
    $CI = &get_instance();
    echo $CI->db->last_query();
    if ($bool) {
        die;
    }

}

/**
 * Return verfication code with check already exit or not for users table
 */
function verification_code()
{
    $CI = &get_instance();
    for ($i = 0; $i < 1; $i++) {
        $verification_string = 'abcdefghijk123' . time();
        $verification_code = str_shuffle($verification_string);

        $check_code = $CI->users_model->check_verification_code($verification_code);
        if (!empty($check_code)) {
            $i--;
        } else {
            return $verification_code;
        }
        // if (sizeof($check_code) > 0) {
        //     $i--;
        // } else {
        //     return $verification_code;
        // }
    }
}

/**
 * Uploads image
 * @param string $image_name
 * @param string $image_path
 * @return array - Either name of the image if uploaded successfully or Array of errors if image is not uploaded successfully
 */
function upload_image($image_name, $image_path)
{
    $CI = &get_instance();
    $extension = explode('/', $_FILES[$image_name]['type']);
    $randname = uniqid() . time() . '.' . end($extension);
    $config = array(
        'upload_path' => $image_path,
        'allowed_types' => "png|jpg|jpeg|gif",
        'max_size' => "2048",
        'file_name' => $randname,
    );
    //--Load the upload library
    $CI->load->library('upload');
    $CI->upload->initialize($config);
    if ($CI->upload->do_upload($image_name)) {
        $img_data = $CI->upload->data();
        $imgname = $img_data['file_name'];
    } else {
        $imgname = array('errors' => $CI->upload->display_errors());
    }
    return $imgname;
}


/**
 * Uploads image
 * @param string $image_name
 * @param string $image_path
 * @return array - Either name of the image if uploaded successfully or Array of errors if image is not uploaded successfully
 */
function upload_item_images($image_name, $image_path)
{
    $CI = &get_instance();
    $extension = explode('/', $_FILES[$image_name]['type']);
    $file_extension = explode('/', $_FILES[$image_name]['name']);
    $file_extension = explode('.', end($file_extension));
    $randname = uniqid() . time() . '.' . end($file_extension);
    $config = array(
        'upload_path' => $image_path,
        'allowed_types' => "avi|mp4|flv|wmv|png|jpg|jpeg|gif",
        'file_name' => $randname,
        'max_size' => "51200",
    );
    //--Load the upload library
    $CI->load->library('upload');
    $CI->upload->initialize($config);
    if ($CI->upload->do_upload($image_name)) {
        $img_data = $CI->upload->data();

        $source_path = $image_path .'/'. $img_data['file_name'];
        $target_path = $image_path . '/thumbnail/';
        if (!file_exists($target_path)) {
            mkdir($target_path);
        }else
        {
            echo $target_path; 
        }
        $config_manip = array(
            'image_library' => 'gd2',
            'source_image' => $source_path,
            'new_image' => $target_path,
            'maintain_ratio' => TRUE,
            'create_thumb' => TRUE,
            'thumb_marker' => '_thumb',
            'width' => 500,
            'height' => 500
        );
       $CI->load->library('image_lib', $config_manip);
      if (!$CI->image_lib->resize()) {
          echo $CI->image_lib->display_errors();
      }
        $CI->image_lib->clear();
        // $CI->load->library('image_lib', $config_manip);
        /*$CI->load->library('image_lib');
        $CI->image_lib->initialize($config_manip);
        if (!$CI->image_lib->resize()) {
            echo $CI->image_lib->display_errors();
        }
        $CI->image_lib->clear();*/
        $imgname = $img_data['file_name'];
    } 
    else 
    {
        $imgname = array('errors' => $CI->upload->display_errors());
    }
    return $imgname;
}

/**
 * Generated random password
 * @return generated password
 */
function randomPassword()
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

/**
 * Check User is allowed for the following permission or not
 * @param string $page_name
 * @param string $permission - add/edit/delete/view
 * @param int $flag
 * @return boolean
 */
function is_admin()
{
    $CI = &get_instance();
    $user_id = $CI->session->userdata('login_user')['id'];
    $user_role = $CI->session->userdata('login_user')['role'];
    if ($user_role === ADMIN) 
    {
        return true;
    }
    return false;
}

function is_restaurant()
{
    $CI = &get_instance();
    $user_id = $CI->session->userdata('login_user')['id'];
    $user_role = $CI->session->userdata('login_user')['role'];
    if ($user_role === RESTAURANT) 
    {
        return true;
    }
    return false;
}

function is_sub_admin()
{
    $CI = &get_instance();
    $user_id = $CI->session->userdata('login_user')['id'];
    $user_role = $CI->session->userdata('login_user')['role'];
    if ($user_role === SUB_ADMIN) 
    {
        return true;
    }
    return false;
}

/**
 * Add/Update subscriber into MailChimp
 * @param array $data
 */
function mailchimp($data)
{
    $CI = &get_instance();
    $apiKey = $CI->config->item('Mailchimp_api_key');
    $email = $data['email_address'];
    $memberId = md5(strtolower($email));
    $dataCenter = substr($apiKey, strpos($apiKey, '-') + 1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . LIST_ID . '/members/' . $memberId;
    $json = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $arr = json_decode($result, true);
}

/**
 * Return details of mailchimp subscriber
 * @param string $email
 */
function get_mailchimp_subscriber($email)
{
    $CI = &get_instance();
    $apiKey = $CI->config->item('Mailchimp_api_key');
    $memberId = md5(strtolower($email));
    $dataCenter = substr($apiKey, strpos($apiKey, '-') + 1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . LIST_ID . '/members/' . $memberId;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $arr = json_decode($result, true);
    return $arr;
}

/**
 * Delete subscriber from MailChimp Account
 * @param array $data
 * @author sm
 */
function delete_mailchimp_subscriber($data)
{
    $CI = &get_instance();
    $apiKey = $CI->config->item('Mailchimp_api_key');
    $email = $data['email_address'];
    $memberId = md5(strtolower($email));
    $dataCenter = substr($apiKey, strpos($apiKey, '-') + 1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . LIST_ID . '/members/' . $memberId;
    $json = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $arr = json_decode($result, true);
}

/**
 * Set up configuration array for pagination
 * @return array - Configuration array for pagination
 */
function front_pagination()
{
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['first_link'] = 'First';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li style="display:none"></li><li class="active"><a data-type="checked" style="background-color:#62a0b4;color:#ffffff; pointer-events: none;">';
    $config['cur_tag_close'] = '</a></li><li style="display:none"></li>';
    $config['prev_link'] = '&laquo;';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = '&raquo;';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['last_link'] = 'Last';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    return $config;
}

/**
 * Resise image to specified dimensions
 * @param string $src - Source of image
 * @param string $dest - Destination of image
 * @param int $width - Width of image
 * @param int $height - Height of image
 */
function resize_image($src, $dest, $width, $height)
{
    $CI = &get_instance();
    $CI->load->library('image_lib');
    $CI->image_lib->clear();
    $config['image_library'] = 'gd2';
    $config['source_image'] = $src;
    $config['maintain_ratio'] = true;
    $config['create_thumb'] = TRUE;
    $config['width'] = $width;
    $config['height'] = $height;
    $config['new_image'] = $dest;
    $CI->image_lib->initialize($config);
    $CI->image_lib->resize();
}

/**
 * Returns file size in GB/MB or KB
 * @param int $bytes
 * @return string
 */
function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }
    return $bytes;
}

/**
 * Returns file size in GB/MB or KB
 * @param int $bytes
 * @return string
 */
function CheckImageType($path)
{
    if(file_exists($path))
    {
        $info = getimagesize($path);
        $extension = image_type_to_extension($info[2]);
        if ($extension === '.jpg' || $extension === '.jpeg' || $extension === '.png') {
        return true;
        } else {
            return false;
        }
    }
    return false;
}

/**
 * Crops the image
 * @param int $source_x
 * @param int $source_y
 * @param int $width
 * @param int $height
 * @param string $image_name
 */
function crop_image($source_x, $source_y, $width, $height, $image_name)
{
    ini_set('memory_limit', '500M');

    $output_path = CROP_FACES;
    $extension = pathinfo($image_name, PATHINFO_EXTENSION);
    $filename = pathinfo($image_name, PATHINFO_FILENAME);

    if ($extension == 'jpg' || $extension == 'jpeg') {
        $image = imagecreatefromjpeg($image_name);
    } else if ($extension == 'png') {
        $image = imagecreatefrompng($image_name);
    }

    $new_image = imagecreatetruecolor($width, $height);
    imagecopy($new_image, $image, 0, 0, $source_x, $source_y, $width, $height);
    // Now $new_image has the portion cropped from the source and you can output or save it.
    if ($extension == 'jpg' || $extension == 'jpeg') {
        imagejpeg($new_image, $output_path . $filename . '.' . $extension);
    } else if ($extension == 'png') {
        imagepng($new_image, $output_path . $filename . '.' . $extension);
    }
}

function compress_image($source_file, $target_file, $nwidth, $nheight, $quality) {
  //Return an array consisting of image type, height, widh and mime type.
  $image_info = getimagesize($source_file);
  if(!($nwidth > 0)) $nwidth = $image_info[0];
  if(!($nheight > 0)) $nheight = $image_info[1];
  
  if(!empty($image_info)) {
    switch($image_info['mime']) {
      case 'image/jpeg' :
        if($quality == '' || $quality < 0 || $quality > 100) $quality = 75; //Default quality
        // Create a new image from the file or the url.
        $image = imagecreatefromjpeg($source_file);
        $thumb = imagecreatetruecolor($nwidth, $nheight);
        //Resize the $thumb image
        imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
        //Output image to the browser or file.
        return imagejpeg($thumb, $target_file, $quality); 
        
        break;
      
      case 'image/png' :
        if($quality == '' || $quality < 0 || $quality > 9) $quality = 6; //Default quality
        // Create a new image from the file or the url.
        $image = imagecreatefrompng($source_file);
        $thumb = imagecreatetruecolor($nwidth, $nheight);
        //Resize the $thumb image
        imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
        // Output image to the browser or file.
        return imagepng($thumb, $target_file, $quality);
        break;
        
      case 'image/gif' :
        if($quality == '' || $quality < 0 || $quality > 100) $quality = 75; //Default quality
        // Create a new image from the file or the url.
        $image = imagecreatefromgif($source_file);
        $thumb = imagecreatetruecolor($nwidth, $nheight);
        //Resize the $thumb image
        imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
        // Output image to the browser or file.
        return imagegif($thumb, $target_file, $quality); //$success = true;
        break;
        
      default:
        echo "<h4>File type not supported!</h4>";
        break;
    }
  }
}
