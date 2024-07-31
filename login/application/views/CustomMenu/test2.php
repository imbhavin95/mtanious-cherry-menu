<!DOCTYPE html>
<html lang="en">
<head>
  <title>Item Details</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="https://www.cherrymenu.com/login/public/webmenu/Favicon.png" type="image/favicon" sizes="21x21">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <?php if(@$_COOKIE['lang_ar_id']==2){?>
<link rel="stylesheet" href="https://www.cherrymenu.com//css/testRTL2.css">
<?php }else{?>
<link rel="stylesheet" href="https://www.cherrymenu.com//css/test2.css">
<?php }?> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/owl.carousel.min.js"></script>

  
  
  <style type="text/css">
    
 </style>

</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Header -->

<div class="header  ">
   <div class="row">
    <div class="col-xs-8 col-sm-9 col-md-10">
    <div class="logo">
      <a href="<?php echo $sesurl;?>"  class="bcbtn" ><i class="fa fa-chevron-left" aria-hidden="true"></i></a> 
      <a href="javascript:void();" class="logo-part"><img src="<?php echo "https://www.cherrymenu.com/login/public/settings/logo/".$rest_image;?>" ></a>
    </div>
  </div>
      <div class="col-xs-4 col-sm-3 col-md-2">
        
     <select id="lang_ar_id">
      <option value="">Language</option>
      <option value="1" <?php echo @$_COOKIE['lang_ar_id']==1 ? 'selected':''; ?>>English</option>
      <option value="2" <?php echo @$_COOKIE['lang_ar_id']==2 ? 'selected':''; ?>>Arabic</option>
    </select>

     </div>
   </div>
</div>

  </div>
</div>
  <div class="container">
  <div class="row">
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <?php $imgarr; foreach($singleitem['images']['new'] as $value){
       $url="https://www.cherrymenu.com/login/public/restaurants/".$restid."/items/".$value['item_id']."/".$value['image'];
        $imgarr[]=$url;
    }
    
    $itemname=$singleitem['Item'][0]['title'];
    $arabic_itemname=$singleitem['Item'][0]['arabian_title'];
    $description=$singleitem['Item'][0]['description'];
    $arabic_description=$singleitem['Item'][0]['arabian_description'];
    $price=$singleitem['Item'][0]['price'];
    $featured=$singleitem['Item'][0]['is_featured'];
    $calories=$singleitem['Item'][0]['calories'];
    $time=$singleitem['Item'][0]['time'];
     
     ?>
    <ol class="carousel-indicators">
      <?php $i=0;  if(isset($imgarr) && !empty($imgarr)){ foreach($imgarr as $value){
      if (strpos($value, 'jpg') !== false || strpos($value, 'png') !== false) 
      {
        ?>
      <li data-target="#myCarousel" data-slide-to="<?php echo $i;?>" <?php if($i==0){echo "class='active'";}?>></li>
    <?php $i++; }}}?>
     </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
<?php $i=0;
  if(isset($imgarr) && !empty($imgarr)){ 
    foreach($imgarr as $value){ 
    if (strpos($value, 'jpg') !== false || strpos($value, 'png') !== false) 
      {?>
      <div <?php if($i){ echo "class='item'";}else{echo "class='item active'";}?>>
        <img src="<?php echo $value;?>" alt="Image Not Available" style="width:100%;">
      </div>
        <?php $i++;
      } }  if($i>1){ ?>

      <!-- Left and right controls -->
  <!--   <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a> -->

   <?php }}
        else{?>
 <div class="item active"  >
        <img src="https://www.cherrymenu.com/login/public/restaurants/118/5bc589256de631539672357.png" alt="Image Not Available" style="width:100%;">
      </div>
        <?php }?>
        <?php if(isset($calories) && !empty($calories) || isset($time) && !empty($time)){?>
        <div class="bottom-right">
          <ul>
            <?php if(isset($calories) && !empty($calories)){?>
        <li><img src="https://www.cherrymenu.com/login/public/webmenu/Fork-Knife.png"> <span> <?php echo $calories; ?> </span><?php echo @$_COOKIE['lang_ar_id']==2 ? 'السعرات الحرارية':'Calories';?></li>
            <?php }?>
            <?php if(isset($time) && !empty($time)){?>
          <li><img src="https://www.cherrymenu.com/login/public/webmenu/Clock.png"> <span> <?php echo $time; ?> </span><?php echo @$_COOKIE['lang_ar_id']==2 ? 'مدة التحضير':'Mins';?></li>
            <?php } ?>
          </ul>
        </div>
      <?php } ?>
    </div>

    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="dish-description">
      <br>
      <!-- <strong><?php echo $rest_name;?>  -  <?php echo $menu_name;?></strong> -->
    <h3><?php echo @$_COOKIE['lang_ar_id']==2 ? $arabic_itemname :$itemname;?></h3>
    <div class="pcl">
    <ul>
      <li><img src="https://www.cherrymenu.com/login/public/webmenu/Peanut.png"></li>
      <li><img src="https://www.cherrymenu.com/login/public/webmenu/Chilly.png"></li>
      <li><img src="https://www.cherrymenu.com/login/public/webmenu/Leaf.png"></li>
      <?php if($featured){?>
      <li><img src="https://www.cherrymenu.com/login/public/webmenu/Circle-Star.png"></li>
    <?php }?>
    </ul>
  </div>
  <p><?php echo @$_COOKIE['lang_ar_id']==2 ? $arabic_description:$description;?></p>
  <h2><?php echo $currency;?> <?php echo number_format((float)$price, 2, '.', '');?></h3>
  </div>
  <br> <br> <br> <br>
  <div class="row" style="display: none;">
    <div class="carousel-wrap">
      <h2>Recommended with</h2>
  <div class="owl-carousel">
    <?php foreach($item_data as $value){
       $aurl="https://www.cherrymenu.com/login/item_detail?cid=".$cat_id."&itid=".$value['id']."&rid=".$restid;
      $url="https://www.cherrymenu.com/login/public/restaurants/".$restid."/items/".$value['id']."/thumbnail/".$value['thumbnail'];
      ?>
    <div class="item content">
      <a href="<?php echo $aurl;?>">
        <img src="<?php echo $url;?>" onerror="this.src='https://www.cherrymenu.com/login/public/webmenu/default_img.png';" alt="Image not Available">
        <p><?php echo $value['title'];?></p>
        <h4><?php echo $currency;?> <?php echo number_format((float)$value['price'], 2, '.', '');?></h4>
      </a>
    </div>
  <?php }?>
  </div>
</div>
  </div>
</div>
</div>

<div class="footer">
  <div class="container">
  <div class="row">
  <div class="col-xs-1 col-md-3"></div>
  <div class="col-xs-10 col-md-6">
   <div class="foot-content"> 
   <h5>Contactless & tablet menus by <a href="https://www.cherrymenu.com/" style="color: #000;
    font-weight: 500;
    text-decoration: underline;">Cherrymenu.com</a></h5>
  </div>
  </div>
  <div class="col-xs-1 col-md-3"></div>
  </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js" type="text/javascript"></script>
<script type="text/javascript">
  $('.owl-carousel').owlCarousel({
  loop: false,
  margin: 10,
  nav: true,
  navText: [
    "<i class='fa fa-caret-left'></i>",
    "<i class='fa fa-caret-right'></i>"
  ],
  autoplay: true,
  autoplayHoverPause: true,
  responsive: {
    0: {
      items: 1
    },
    600: {
      items: 3
    },
    1000: {
      items: 5
    }
  }
})
</script>
<script>
function goBack() {
  window.history.back();
}

    $("select").on("change", function() {
  var id = $(this).val();
 if(id){
  $.cookie("lang_ar_id", id, { expires: 1 });
  location.reload();
  // alert(id);
}
});
</script>
</body>
</html>
