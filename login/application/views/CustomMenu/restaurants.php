<!DOCTYPE html>
<html lang="en">
<head>
  <title>Item Details</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="https://www.cherrymenu.com/login/public/webmenu/Favicon.png" type="image/favicon" sizes="21x21">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/owl.carousel.min.js"></script>

  
  
  <style type="text/css">
    .logo-part img{
      width: 7%;
    }
    .dish-description{
      padding: 0 15px;
    }
    .dish-description strong{
      color:#aaa;
    }
    .dish-description p{
      font-size: 15px; 
      color: #4a4a4a;
      font-weight: 400;
    }
    .dish-description h3{
      font-size: 20px;
    color: #4a4a4a;
    font-weight: 600;
    }
    .dish-description h2{
      font-size: 22px;
    color: #4a4a4a;
    font-weight: 600;
    }
    .pcl {
      margin:15px 0; 
    }
    .pcl ul{
      padding: 0px;
    }
    .pcl ul li{
      padding: 0px 5px;
     list-style-type: none;
     display: inline-block;
    }
  .carousel-wrap {
    margin: 40px auto;
    padding: 0 2%;
    width: 100%;
    position: relative;
}

/* fix blank or flashing items on carousel */
.owl-carousel .item {
  position: relative;
  z-index: 100; 
  -webkit-backface-visibility: hidden; 
}

/* end fix */
.owl-nav > div {
  margin-top: -26px;
  position: absolute;
  top: 50%;
  color: #cdcbcd;
}

.owl-nav i {
  font-size: 52px;
}

.owl-nav .owl-prev {
  left: -30px;
}

.owl-nav .owl-next {
  right: -30px;
}
.carousel-wrap h2{
  font-size: 22px;
    color: #4a4a4a;
    font-weight: 600;
    margin-bottom: 24px;
}
.content {
  background-color: #F8F9FA;
  padding: 0px;
  border-radius: 6px;
}
.content h4{
  padding: 2px 5px;
    margin: 0;
    font-size: 18px;
    line-height: 2;
}
.content p{
  padding: 3px 5px;
    margin: 8px 0px 0px 0px;
    font-size: 14px;
    line-height: 1.2;
}
.content a{
  color: #4a4a4a;
    text-decoration: none;
}
.carousel-inner {
    position: relative;
    width: 100%;
    overflow: hidden;
}
.owl-carousel .owl-item img {
    height: 160px;
}

.hero {
    width: 100%;
    height: 100%;
    overflow: hidden;
    position: relative;
    background: #000;
    color: #fff;
}

.hero .alpha-bg {
    background-repeat: no-repeat;
    background-position: center;
    background-image: url("http://flitz.96.lt/img/flitz.jpeg");
    background-size: cover;
    background-attachment: fixed;
    width: 100%;
    height: 100%;
    position: absolute;
    opacity: .50;
}

.center {
    width: 100%;
    height: 100%;
    overflow: hidden;
    display: table;
    opacity: 10;
}

.content-wrap {
    display: table-cell;
    position: relative;
    vertical-align: middle;
    text-align: center;
}

.header {
    width: 100%;
    height: 185px;
    top: 0;
    margin: 0;
    padding: 18px 0;
    background: #fff;
    z-index: 999;
    position: fixed;
}

.header .logo {
    width: 100%;
    font-size: 1.3em;
    margin: 0 auto;
    padding: 0;
    color: #fff;
    text-align: center;
}

.header nav {
    float: right;
    height: 100%;
    margin: 0;
    padding: 0;
}

.header nav ul {
    padding: 0 2rem 0 0;
    margin: -.5rem 0 0 0;
    position: relative;
    top: 50%;
    display: block;
}

.header nav ul li {
    margin: 0;
    padding: 0;
    display: inline;
}

.header nav ul li a {
    color: #fff;
    padding: 0 1em;
    display: inline-block;
    line-height: inherit;
    text-transform: uppercase;
    text-decoration: none;
}

.header nav ul li a:hover,
.header nav ul li a:active {
    color: #ff9800;
}
 
.carousel {
    position: relative;
    margin-top: 15%;
    padding: 0;
}
.logo-part{
  margin-right: 17%;
}
.bcbtn{
  float: left;
  padding: 4% 5%;
}
.bottom-right {
    background: #fff;
    color: #4A90E2;
    position: absolute;
    bottom: 1px;
    right: 1px;
    padding: 0px 3px;
    font-size: 13px;
}
.bottom-right ul{
  margin: 0px !important;
  padding: 0px !important;
}
.bottom-right ul li{
  list-style-type: none;
    display: inline-block;
    margin: 0px 3px;
    vertical-align: bottom;
}
.footer {
   position: fixed;
   left: 0;
   bottom: 0;
   width: 100%;
   background-color: #ffffff;
   color: #4a4a4a;
   text-align: center;
   padding: 10px 0;
   z-index: 99999;
}
.foot-content{
  text-align: center;
    border-top: 1px solid #4a4a4a;
}
.foot-content h5{
  font-size: 16px;
}
@media only screen and  (max-width: 1024px){
.owl-carousel .owl-item img {
    height: 160px;
}
.logo img {
    width: 13%;
}
.bcbtn {
    float: left;
    padding: 5%;
}
.header {
    width: 100%;
    height: 252px;
    top: 0;
    margin: 0;
    padding: 18px 0;
    background: #fff;
    z-index: 999;
    position: fixed;
}
.carousel {
    position: relative;
    margin-top: 30%;
    padding: 0;
}
.foot-content h5 {
    font-size: 16px;
    margin: 8px;
}
.footer {
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    background-color: #ffffff;
    color: #4a4a4a;
    text-align: center;
    padding: 0px 0;
    z-index: 99999;
}
} 
@media only screen and  (max-width: 768px){
.owl-carousel .owl-item img {
    height: 160px;
}
.logo img {
    width: 16%;
}
.bcbtn {
    float: left;
    padding: 7% 5%;
} 
.header {
    width: 100%;
    height: 235px;
    top: 0;
    margin: 0;
    padding: 18px 0;
    background: #fff;
    z-index: 999;
    position: fixed;
}
.carousel {
    position: relative;
    margin-top: 35%;
    padding: 0;
}
.foot-content h5 {
    font-size: 13px;
    margin: 8px;
}
.footer {
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    background-color: #ffffff;
    color: #4a4a4a;
    text-align: center;
    padding: 0px 0;
    z-index: 99999;
}
}
@media only screen and  (max-width: 480px){
.owl-carousel .owl-item img {
    height: 100%;
}

.carousel-inner {
    position: relative;
    width: 100%;
    overflow: hidden;
}
.header {
    width: 100%;
    height: 140px;
    top: 0;
    margin: 0;
    padding: 18px 0;
    background: #fff;
    z-index: 999;
    position: fixed;
}
.logo img{
  width: 15%;
} 
.carousel-wrap {
    margin: 40px auto;
    padding: 0 7%;
    width: 100%;
    position: relative;
}
.carousel {
    position: relative;
    margin-top: 38%;
    padding: 0 4%;
}
.logo-part{
  margin-right: 17%;
}
.bcbtn{
  float: left;padding: 20px 15px;
}
.bottom-right {
    background: #fff;
    color: #4A90E2;
    position: absolute;
    bottom: 1px;
    right: 1px;
    padding: 0px 3px;
    font-size: 11px;
}
.foot-content{
      margin-top: 6px;
}
.foot-content h5 {
    font-size: 10px;
    margin: 4px;
}
.footer {
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    background-color: #ffffff;
    color: #4a4a4a;
    text-align: center;
    padding: 0px 0;
    z-index: 99999;
}
} 
 </style>

</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Header -->

<div class="header">
    <div class="logo">
      <a href="<?php echo $sesurl;?>"  class="bcbtn" ><i class="fa fa-chevron-left" aria-hidden="true"></i></a> 
      <a href="javascript:void();" class="logo-part"><img src="<?php echo "https://www.cherrymenu.com/login/public/settings/logo/".$rest_image;?>" ></a>
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
    $description=$singleitem['Item'][0]['description'];
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
        <li><img src="https://www.cherrymenu.com/login/public/webmenu/Fork-Knife.png"> <span> <?php echo $calories; ?> </span> Calories</li>
            <?php }?>
            <?php if(isset($time) && !empty($time)){?>
          <li><img src="https://www.cherrymenu.com/login/public/webmenu/Clock.png"> <span> <?php echo $time; ?> </span> Mins</li>
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
    <h3><?php echo $itemname;?></h3>
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
  <p><?php echo $description;?></p>
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
</script>
</body>
</html>
