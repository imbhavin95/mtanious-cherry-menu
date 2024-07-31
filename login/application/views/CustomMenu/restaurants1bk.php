<!DOCTYPE html>
<html>
<head>
   <meta content="width=device-width, initial-scale=1" name="viewport" />
<link href='https://fonts.googleapis.com/css?family=Lato:100,400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.0/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.0/assets/owl.theme.default.min.css">
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>-->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.0/owl.carousel.min.js"></script>

<style type="text/css">
  * { 
  font-family: 'Lato', sans-serif;
  font-weight: 300;
  transition: all .1s ease; 
}

html, body {
    height: 100%;
    margin: 0px;
}

h1 { font-size: 64px; }

.page-section {
  position: relative;
  padding: 0em 3em;
  margin-bottom: 5%;
}

.navigation {
 padding: 0% 10%;
  background-color: #F8F9FA;
  z-index: 99999;
  position: fixed;
  width: 100%;
  margin-top: 2%;
}
 .navigation__link {
   display: inline-block;
  color: #000;
  text-decoration: none;
  padding: 0.5em 2em;
  font-weight: 400;
}
 .navigation__link:hover {
   background-color: #aaa;
   color: #fff;
   text-decoration: none;
}
 .navigation__link.active {
   color: #ffffff;
    background-color: #CD0D2D;
    border-radius: 16px;
}
.resturanrt-logo{
  padding: 25px 0;
  z-index: 99;
}
.cats-sec{
  margin-top: 5%;
}
.menu-sec{
margin-top: 8%;
}
.resturanrt-logo2{
  height: 138px;
    background: #fff;
    position: fixed;
    top: 0;
    /* left: 2%; */
    z-index: 99999;
    width: 100%;
    text-align: center;
}
.item-des{
  background: #f1f1f1;
  padding: 15px;
  border-bottom-right-radius: 6px;
    border-bottom-left-radius: 6px;
}
.item-des p{
  font-size: 16px;
  color: #4a4a4a;
  height: 25px;
    overflow: hidden;
    margin-bottom: 5px;
}
 .item-des h2{
  font-size: 21px;
  color: #4a4a4a;
  margin-top: 0px;
  margin-bottom: 0px;
}
.item-img-name{
  margin-bottom: 15px;
}
.item-img-name a{
  text-decoration: none;
}
.item-img-name img{
height: 222px;
}
.page-section h3{
  margin-bottom: 30px;
}
.owl-carousel.owl-drag .owl-item {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    width: 225px !important;
}
@media only screen and  (max-width: 1024px){

.navigation {
    padding: 0% 10%;
    background-color: #F8F9FA;
    z-index: 99999;
    position: fixed;
    width: 100%;
    margin-top: 5%;
}
.cats-sec {
    margin-top: 9%;
}
.page-section {
    position: relative;
    padding: 0em 3em;
    margin-bottom: 10%;
}
}
@media only screen and  (max-width: 768px){
.navigation {
    padding: 0% 5%;
    background-color: #F8F9FA;
    z-index: 99999;
    position: fixed;
    width: 1000px;
    margin-top: 10%;
}
.cats-sec {
    margin-top: 20%;
}
.page-section {
    position: relative;
    padding: 0em 3em;
    margin-bottom: 10%;
}
}
@media only screen and  (max-width: 480px){
.navigation{
   padding: 1% 2%;
    background-color: #F8F9FA;
    z-index: 99999;
    position: fixed;
    width: 1000px;
    margin-top: 28%;
}
.navigation__link {
    display: inline-block;
    color: #000;
    text-decoration: none;
    padding: 0.5em 1em;
    font-weight: 400;
    font-size: 12px;
}
.cats-sec {
    margin-top: 30%;
}
.owl-carousel.owl-drag .owl-item {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    width: 165px !important;
}
.page-section {
    position: relative;
    padding: 1em 2em;
    margin-bottom: 10%;
}
.resturanrt-logo2 {
    height: 150px;
    background: #fff;
    position: fixed;
    top: 0;
    /* left: 2%; */
    z-index: 99999;
    width: 100%;
    text-align: center;
}
}
</style>

</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <div class="resturanrt-logo2">
      <div class="col-md-12">
        
       <img src="<?php echo "https://www.cherrymenu.com/login/public/settings/logo/".$rest_image;?>" class="resturanrt-logo" style="width: 100px;height: 130px;">
     </div>
      </div>
    </div>
  </div>
  <?php  
foreach ($item_data as $key => $value) {
     $catname=$value['name'];  
    $catnames[]=$value['name'];  
  foreach ($value as $key1 => $value2) {
     
  if(is_array($value2)){
    foreach ($value2 as $key => $value3) {
       $imgname= $value3['thumbnail'];//echo "<br>"; 
      //$imgname= str_replace(".","_thumb.",$value3['image']);//echo "<br>";
      $item_id=$value3['id'];
      $url="https://www.cherrymenu.com/login/public/restaurants/".$restid."/items/"."$item_id"."/thumbnail/".$imgname;
    "<img src='$url' style='width:100px;height:100px;'>";   
    }
  } 
  }
}
?>
  <section class="menu-sec">
  <div class="container-fluid">
       <div class="row">
    <nav class="navigation" id="mainNav">
      <div id="owl-example" class="owl-carousel">
      <?php $i=1; foreach($catnames as $value){?>
      <a class="navigation__link" href="#<?php echo $i;?>"><?php echo $value;?></a>
    <?php $i++; }?> 
  </div>
    </nav>
   </div>
    
  </div>
  </section>
   

<section class="cats-sec">
  <div class="container">
    <div class="row">
      <?php $i=1;
foreach ($item_data as $key => $value) {
     $catname=$value['name']; 
  ?>
<div class="page-section hero" id="<?php echo $i;?>">
  <?php  foreach ($value as $key1 => $value2) {
   if(is_array($value2)){

    ?>
      <h3><?php echo $catname;?></h3>
      <div class="row">
<?php     
    foreach ($value2 as $key => $value3) {
      $imgname= $value3['thumbnail'];
      //$imgname= str_replace(".","_thumb.",$value3['thumbnail']); 
      $item_id=$value3['id'];
      $cat_id=$value3['category_id'];
      $url="https://www.cherrymenu.com/login/public/restaurants/".$restid."/items/"."$item_id"."/thumbnail/".$imgname;
            $aurl="https://www.cherrymenu.com/login/item_detail?cid=".$cat_id."&itid=".$item_id."&rid=".$restid;
          // $aurl='';?>
        <div class="col-sm-4 col-md-4">
          <div class="item-img-name">
            <a href="<?php echo $aurl;?>"  >
           <img src="<?php echo $url;?>" onerror="this.src='https://www.cherrymenu.com/login/public/webmenu/default_img.png';" width="100%">
           <div class="item-des">
            <p><?php echo $value3['title'];?></p>
            <h2>AED <?php echo number_format((float)$value3['price'], 2, '.', '');?></h2>
           <!--  <h2>AED <?php echo number_format($value3['price']);?></h2> -->
           </div>
         </a>
          </div>
        </div> <?php }?>
       </div> <?php } }?>
     
</div>
<?php 
   $i++;}
?> 
</div>
</div>
</section>
<script type="text/javascript">
  $(document).ready(function() {
    $('a[href*=#]').bind('click', function(e) {
        e.preventDefault(); // prevent hard jump, the default behavior

        var target = $(this).attr("href"); // Set the target as variable

        // perform animated scrolling by getting top-position of target-element and set it as scroll target
        $('html, body').stop().animate({
            scrollTop: $(target).offset().top-200
        }, 600, function() {
            location.hash = $(target).offset().top-200; //attach the hash (#jumptarget) to the pageurl
        });

        return true;
    });
});

$(window).scroll(function() {
    var scrollDistance = $(window).scrollTop();

    // Show/hide menu on scroll
    //if (scrollDistance >= 850) {
    //    $('nav').fadeIn("fast");
    //} else {
    //    $('nav').fadeOut("fast");
    //}
  
    // Assign active class to nav links while scolling
    $('.page-section').each(function(i) {
        if ($(this).position().top-200 <= scrollDistance) {
            $('.navigation a.active').removeClass('active');
            $('.navigation a').eq(i).addClass('active');
        }
    });
}).scroll();
/*var lastId,
    topMenu = $("#top-menu"),
    topMenuHeight = topMenu.outerHeight()+15,
    // All list items
    menuItems = topMenu.find("a"),
    // Anchors corresponding to menu items
    scrollItems = menuItems.map(function(){
      var item = $($(this).attr("href"));
      if (item.length) { return item; }
    });
// Bind to scroll
$(window).scroll(function(){
   // Get container scroll position
   var fromTop = $(this).scrollTop()+topMenuHeight;
   
   // Get id of current scroll item
   var cur = scrollItems.map(function(){
     if ($(this).offset().top < fromTop)
       return this;
   });
   // Get the id of the current element
   cur = cur[cur.length-1];
   var id = cur && cur.length ? cur[0].id : "";
   
   if (lastId !== id) {
       lastId = id;
       // Set/remove active class
       menuItems
         .parent().removeClass("active")
         .end().filter("[href='#"+id+"']").parent().addClass("active");
   }                   
});*/

 $(document).ready(function() {
     $('.navigation__link').bind('click', function(e) {
var target = $(this).attr("href"); // Set the target as variable
var i = target.replace("#", "");
$('.navigation a.active').removeClass('active');
$('.navigation a').eq(i-1).addClass('active');
});
     
      $(".owl-carousel").owlCarousel(/*{
        items:4,
    loop:true,
    margin:10,
    autoplay:true,
    autoplayTimeout:3000,
    autoplayHoverPause:true
      }*/);
      $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        //e.target // newly activated tab
        //e.relatedTarget // previous active tab
        //$(".owl-carousel").trigger('refresh.owl.carousel');
      });
    });
</script>
</body>
</html> 
