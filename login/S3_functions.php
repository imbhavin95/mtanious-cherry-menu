<?php
require_once('S3.php');

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


function upload_images_to_s3_in_diff_sizes($temp_target_path, $main_folder, $sub_folder, $img_real_path, $targetPath='', $img,  $file_extension,$sizes){

    $S3 = new S3();
    $bucekts = $S3->listBuckets();
    $bucket = $bucekts[0];
    echo "<li>Bucket Name : ".$bucket;
    echo "<li>Base IP : ".base_ip;

    $main_folder = str_replace(' ', '_', $main_folder);
    $sub_folder = str_replace(' ', '_', $sub_folder);

    # Get the image name
    $x = explode(".",$img);

    # Main Image
    //$S3->SaveObject($main_folder.'/'.$sub_folder, $targetPath, $img, $file_extension, $bucket);

    # Images in different size
    for($i=0;$i<count($sizes);$i++){
        $timthumb = base_ip."/timthumb.php?src=".$img_real_path."&w=".$sizes[$i]['w']."&h=".$sizes[$i]['h']."&zc=".$sizes[$i]['zc']."&q=".$sizes[$i]['q'];
        $new_img = $x[0].'_'.$sizes[$i]['w'].'_'.$sizes[$i]['h'].'.'.$file_extension;
        $destination = $temp_target_path.''.$new_img;

        if( move_uploaded_file($timthumb, $destination) || copy($timthumb, $destination)){
            $S3->SaveObject($main_folder.'/'.$sub_folder,$destination, $new_img, $file_extension, $bucket);
        }else{
            echo "<li style='background:red;color:white;'>Failed</li>";
        }
    }
}


function upload_single_image($main_folder,$sub_folder,$targetPath,$img,$file_extension){
    
    $S3 = new S3();
    $bucekts = $S3->listBuckets();
    $bucket = $bucekts[0];

    $main_folder = str_replace(' ', '_', $main_folder);
    $sub_folder = str_replace(' ', '_', $sub_folder);

    $S3->SaveObject($main_folder.'/'.$sub_folder, $targetPath, $img, $file_extension, $bucket);
    
}

?>
