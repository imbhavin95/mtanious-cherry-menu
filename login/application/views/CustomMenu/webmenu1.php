<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://www.cherrymenu.com/login/public/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://www.cherrymenu.com/login/public/css/style.css" />
        <link rel="stylesheet" href="https://www.cherrymenu.com/login/public/css/font-awesome.min.css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bxslider/4.2.15/jquery.bxslider.min.css" rel="stylesheet" />

        <title>TheWaresh</title>
    </head>
    <body>
        <section id="logo">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-xs-12 text-center">
                        <a href="index.html"><img src="https://www.cherrymenu.com/login/public/image/logo.png" /></a>
                    </div>
                </div>
            </div>
        </section>

                <section id="slider">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-xs-12 flush">
                                    <ul class="bxslider" id="top-menu">
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
 <?php $i=1; foreach($catnames as $value){
          if($i==1){               ?>
      <li class="active">
      <?php }else{ ?>
        <li>
        <?php } ?>
                                            <a href="#<?php echo $i;?>"><?php echo $value;?></a>
                                        </li>
    <?php $i++; }?> 

                                        <!-- <li class="active">
                                            <a href="#breakfast">Breakfast</a>
                                        </li>
                                        <li>
                                            <a href="#Manakeesh">Manakeesh</a>
                                        </li>
                                        <li>
                                            <a href="#Appetizers-Soup">Appetizers/Soup</a>
                                        </li>
                                        <li>
                                            <a href="#Sandwiches-Lite-Bites">Sandwiches/Lite Bites</a>
                                        </li>
                                        <li>
                                            <a href="#Main-Course">Main Course</a>
                                        </li>
                                        <li>
                                            <a href="#Salads">Salads</a>
                                        </li>
                                        <li>
                                            <a href="#Pizza">Pizza</a>
                                        </li>
                                        <li>
                                            <a href="#Pasta">Pasta</a>
                                        </li>
                                        <li>
                                            <a href="#Coffee-Specialty">Coffee Specialty</a>
                                        </li>
                                        <li>
                                            <a href="#Hot-Beverages">Hot Beverages</a>
                                        </li>
                                        <li>
                                            <a href="#">Dessert</a>
                                        </li>
                                        <li>
                                            <a href="#">Fresh & healthy beverages</a>
                                        </li>
                                        <li>
                                            <a href="#">Shakes & Smoothies</a>
                                        </li>
                                        <li>
                                            <a href="#">Cold Beverages</a>
                                        </li>
                                        <li>
                                            <a href="#">Lemonade & Mojito</a>
                                        </li>
                                        <li>
                                            <a href="#">Soft Beverages</a>
                                        </li>
                                        <li>
                                            <a href="#">Specials</a>
                                        </li> -->
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>          
                </section>


 <?php $i=1; //echo "<pre>"; print_r($item_data);
     end($item_data); 
     $last_key = key($item_data); 
foreach ($item_data as $key => $value) {
     $catname=$value['name']; 
  ?>

  <section id="<?php echo $i;?>">
    <?php  foreach ($value as $key1 => $value2) {
   if(is_array($value2)){

    ?>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-xs-12">
                        <h3><?php echo $catname;?></h3>
                    </div>
                </div>
                <div class="row">
                    <?php     
    foreach ($value2 as $key2 => $value3) {
      $imgname= $value3['thumbnail'];
      //$imgname= str_replace(".","_thumb.",$value3['thumbnail']); 
      $item_id=$value3['id'];
      $cat_id=$value3['category_id'];
      $url="https://www.cherrymenu.com/login/public/restaurants/".$restid."/items/"."$item_id"."/thumbnail/".$imgname;
            $aurl="https://www.cherrymenu.com/login/item_detail?cid=".$cat_id."&itid=".$item_id."&rid=".$restid."&sid=".$i;
          // $aurl='';?> 
                    <div class="col-lg-4 col-sm-4 col-xs-12">
                        <a href="inner-page.html">
                            <div class="card">
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-xs-12">
                                         <a href="<?php echo $aurl;?>"  >
                                        <img src="<?php echo $url;?>"  onerror="this.src='https://www.cherrymenu.com/login/public/webmenu/default_img.png';" width="100%" />
                                    </a>
                                    </div>

                                    <div class="col-lg-12 col-sm-12 col-xs-12">
                                        <h4><?php echo $value3['arabian_title'];?></h4>
                                        <h4><?php echo $value3['title'];?></h4>
                                        <h6><?php echo $currency;?> <?php echo number_format((float)$value3['price'], 2, '.', '');?></h6>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> 
                    <?php }?>
                </div><?php } }?>
            </div>
        </section>
<?php 
   $i++;  
   if($key==$last_key){
     echo "<br><br>";
   }
 
 }
?> 




      
      

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://www.cherrymenu.com/login/public/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bxslider/4.2.15/jquery.bxslider.min.js"></script>
        <script>
        // Cache selectors
var lastId,
    topMenu = $("#top-menu"),
    topMenuHeight = topMenu.outerHeight()+15,
    // All list items
    menuItems = topMenu.find("a"),
    // Anchors corresponding to menu items
    scrollItems = menuItems.map(function(){
      var item = $($(this).attr("href"));
      if (item.length) { return item; }
    });

// Bind click handler to menu items
// so we can get a fancy scroll animation
menuItems.click(function(e){
  var href = $(this).attr("href"),
      offsetTop = href === "#" ? 0 : $(href).offset().top-topMenuHeight+1;
  $('html, body').stop().animate({ 
      scrollTop: offsetTop
  }, 300);
  e.preventDefault();
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
});
        </script>
        <script type="text/javascript">
            $(window).scroll(function() {    
                        var scroll = $(window).scrollTop();

                        if (scroll >= 290) {
                            $("#slider").addClass("stiky");
                        } else {
                            $("#slider").removeClass("stiky");
                        }
                    });
        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                $(".bxslider").bxSlider({
                    auto: false,
                    controls: true,
                    pager: false,
                    infiniteLoop: false,
                    hideControlOnEnd: true,
                    mouseDrag: true,
                    slideWidth: 250,
                    minSlides: 6,
                    maxSlides: 6,
                    moveSlides: 1,
                    slideMargin: 10,
                    speed: 450,
                });
            });
        </script>
    </body>
</html>
