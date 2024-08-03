 <?php
$segments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
// echo "<pre>";
//  print_r($segments);
$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);
if(isset($segments[0]) && !empty($segments[0])){
   print file_get_contents('https://cherrymenu.com/login/v1/kibuncafe',false,stream_context_create($arrContextOptions));
}else{
?>
<?php }?>