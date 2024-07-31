<!DOCTYPE html>
<html>
<head>
   <meta content="width=device-width, initial-scale=1" name="viewport" />
   <link rel="icon" href="https://www.cherrymenu.com/login/public/webmenu/Favicon.png" type="image/favicon" sizes="21x21">
<link href='https://fonts.googleapis.com/css?family=Lato:100,400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<?php if(@$_COOKIE['langmainid']==2){?>
<link rel="stylesheet" href="https://www.cherrymenu.com//css/testRTL1.css">
<?php }else{?>
<link rel="stylesheet" href="https://www.cherrymenu.com//css/test1.css">
<?php }?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.0/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.0/assets/owl.theme.default.min.css">
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js" type="text/javascript"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <!-- <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.0/owl.carousel.min.js"></script>

<style type="text/css">

</style>

</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <div class="resturanrt-logo2">
      <div class="col-xs-8 col-sm-8 col-md-10">
        
       <img src="<?php echo "https://www.cherrymenu.com/login/public/settings/logo/".$rest_image;?>" class="resturanrt-logo" >

     </div>
     <div class="col-xs-2 col-sm-4 col-md-2">
        
     <select id="langmainid">
      <option value="">Language</option>
      <option value="1" <?php echo @$_COOKIE['langmainid']==1 ? 'selected':''; ?>>English</option>
      <option value="2" <?php echo @$_COOKIE['langmainid']==2 ? 'selected':''; ?>>Arabic</option>
    </select>

     </div>
      </div>
    </div>
  </div>
  <?php  
   
foreach ($item_data as $key => $value) {
     $catname=$value['name'];  
    $catnames[]=@$_COOKIE['langmainid']==2 ? $value['arabic_name'] : $value['name'];  
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
      <div id="owl-example" class="owl-carousel sssssss">
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
      <?php $i=1; // echo "<pre>"; print_r($item_data);
      end($item_data); 
     $last_key = key($item_data); 
foreach ($item_data as $key => $value) {
  //$a > 15 ? 20 : 5; 
  $catname=@$_COOKIE['langmainid']==2 ? $value['arabic_name'] : $value['name'];
    // $catname=$value['name']; 
  ?>
<div class="page-section hero" id="<?php echo $i;?>">
  <?php  foreach ($value as $key1 => $value2) {
   if(is_array($value2)){

    ?>

      <h3><?php echo $catname;?></h3>
      <div class="row">
<?php     
    foreach ($value2 as $key2 => $value3) {
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
            <p><?php echo $atitlee=@$_COOKIE['langmainid']==2 ? $value3['arabian_title'] : $value3['title'];?></p>
            <h2><?php echo $currency;?> <?php echo number_format((float)$value3['price'], 2, '.', '');?></h2>
           <!--  <h2>AED <?php echo number_format($value3['price']);?></h2> -->
           </div>
         </a>
          </div>
        </div> <?php }?>
       </div> <?php } }?>
     
</div>
<?php 
  $i++;  
   if($key==$last_key){
     echo "<br><br>";
   }
 
 }
?> 
</div>
</div>
</section>

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
  $(document).ready(function() {
     $('a[href*=#]').bind('click', function(e) {
        e.preventDefault(); // prevent hard jump, the default behavior

        var target = $(this).attr("href"); // Set the target as variable

        // perform animated scrolling by getting top-position of target-element and set it as scroll target
        $('html, body').stop().animate({
            scrollTop: $(target).offset().top-170
        }, 600, function() {
            location.hash = $(target).offset().top-170; //attach the hash (#jumptarget) to the pageurl
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
        if ($(this).position().top-250 <= scrollDistance) {
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
       
      var langmainid=$.cookie("langmainid");
      if(langmainid==2){
$(".owl-carousel").owlCarousel({
   rtl:true });
      }else{
      $(".owl-carousel").owlCarousel({
      //     items:4,
    // loop:true,
    // margin:10,
    // autoplay:true,
    // autoplayTimeout:3000,
    // autoplayHoverPause:true
      });
    }
      $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        //e.target // newly activated tab
        //e.relatedTarget // previous active tab
        //$(".owl-carousel").trigger('refresh.owl.carousel');
      });

       $("select").on("change", function() {
  var id = $(this).val();
  if(id){
  $.cookie("langmainid", id, { expires: 1 });
  location.reload();
  // alert(id);
  }
});

    });



</script>
</body>
</html> 
