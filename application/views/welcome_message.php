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
   print file_get_contents('https://www.cherrymenu.com/login/v1/'.$segments[0].'',false,stream_context_create($arrContextOptions));
}else{
?>  <!DOCTYPE html>
<html lang="en" >
   <head>
      <!-- Metas -->
      <meta charset="utf-8">
       <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
      <title>CherryMenu Offers Digital Menu Restaurant Solutions in UAE</title>

      <meta name="keywords" content="Digital Menu Restaurant UAE, Digital Menus for Restaurants, Restaurant Menu Board Dubai, Digital Menu Boards, Restaurant Management Software UAE, Best Digital Menu Restaurant UAE, Tablet Menu for Restaurants UAE, Digital Restaurant Menu App, Restaurant Menu Application, Electronic Menu Boards for Restaurants, Restaurant Pos Systems UAE,
      Restaurant Food Software UAE, Digital Menu Boards for Restaurants, Best Restaurant Apps, Restaurant Menu Template">
      
      <meta name="description" content="Build the best digital menu for your restaurant in UAE. CherryMenu offers cost-effective restaurant management software & electronic menu application. Call now!">
      

      <meta name="viewport" content="width=device-width, initial-scale=1">

      <link rel="canonical" href="https://www.cherrymenu.com/" />
      <!-- Css -->
      <link rel="icon" href="<?php echo base_url();?>/img/faviconcherry.jpg" type="image/gif" sizes="16x16">
     
      <link href="<?php echo base_url();?>/css/main.css" rel="stylesheet" type="text/css" media="all"/>
      <link href="<?php echo base_url();?>/css/owl-carousel/owl.carousel.css" rel="stylesheet"  media="all" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      
      <script async src="https://cdn.ampproject.org/v0.js"></script>
       <!-- Fresh Chat Head start--> 
      <!-- <script src="https://wchat.freshchat.com/js/widget.js"></script> -->
       <!-- Fresh Chat Head End-->

      <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=UA-136498729-1"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-136498729-1');
      </script>
      <style>
        
        .fa-2x {
    font-size: 1em;
}
.whatsapp {
    position: fixed;
    right: 0px;
top: 90%;
margin-right: 20px;
}
.whatsapp h5 {
    color: white;
    background: #20b20f;
    padding: 5px 8px;
    border-radius: 10px;
}

        h2.h2-title{
          font-size: 35px;
          margin-bottom: 20px;
          margin-top: 0px;
        }
        h2.light{
              color: #fff;
    margin-bottom: 0;
    margin-top: 8px;
        }
         #btn-explore{
          background: none !important;
        color: #ffffff  !important;
        font-weight: 500 !important;
        padding: 18px 34px !important;
        border: 2px solid #ffffff   !important;
        }

        #btn-explore:active, #btn-explore:hover {
          padding: 20px 35px !important;
          font-size: 16px !important;
          border: 0px solid #f93d66 !important;
          margin-top: 20px !important;
          display: inline-block !important;
          border-radius: 3px !important;
          -webkit-border-radius: 3px !important;
          -moz-border-radius: 3px !important;
          -ms-border-radius: 3px !important;
          -o-border-radius: 3px !important;
          position: relative !important;
          background: #f93d66 !important;
          color: #ffffff !important;
        }

       .recaptcha{
        margin-left: 210px;
       }

      .dropbtn {
        color: #757575;
        padding: 5px;
        font-size: 16px;
        border: none;
        text-decoration: underline;
      }

      .dropdown {
        position: relative;
        display: inline-block;
      }

      .dropdown-content {
        display: none;
        position: absolute;
        background-color: #000;
        color:#fff;
        min-width: 350px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        padding:15px;
        border-radius:6px;
      }
      .dropdown-content:after {
       
          position: relative;
          top: 0px;
          left:0px;
          width: 0;
          height: 0;
          border-left: 10px solid transparent;
          border-right: 10px solid transparent;
          border-bottom: 10px solid #fff
      }

      .sm_bold{
        font-weight: 600;
      }

      .dropdown-content a {
        color: black;
        text-decoration: none;
        display: block;
      }

      .dropdown-content a:hover {background-color: #fff;}

      .dropdown:hover .dropdown-content {display: block;}

      .dropdown:hover .dropbtn {background-color: #fff;}

      .pt-120 {
          padding-top: 90px !important;
      }

      .mt-60{

      }
      #newtag{
          background-color: #4a8fe2;
    color: #fff;
    font-size: 12px;
    margin-left: 7px;
    top: 80px;
    line-height: 12px;
    position: absolute;
    font-weight: 500;
    padding: 5px 7px;
  }
   #btn-apple{
        margin-right: 15px;
        background: #4672D9; 
        font-size: 14px;
        width: 289px;
        padding: 14px 24px !important;
       }
       #btn-andriod{
        margin-left: 15px;
        background: #4672D9; 
        font-size: 14px;
        width: 289px;
        padding: 14px 24px !important; 
       }
       #btn-andriod img{
           float: left;
         }
          #btn-apple img{
           float: left;
         }

         .currency_dubai{
          font-size: 18px;
         }
         .start-price{
          font-size: 35px !important;
          padding: 0px 5px !important;
          margin-top: 0px !important;
          margin-bottom: 15px !important;
         }
         .proud-emirati{
          font-size: 12px;
          margin-top: 20px;
          margin-bottom: 0px;
         }
         .cherry-clients-sec li{
              
          display: inline-block;
              padding: 0px 18px;
         }
         .cherry-clients{
          font-size: 23px;
          font-weight: 600;
          color: #f93d66;
              padding-top: 50px;
    padding-bottom: 35px;
          margin-top: 60px;
          margin-bottom: 45px;
          border-top: 1px solid #eee;
         }
         .Pictures-are-worth{
          margin-bottom:10px !important;
         }
           
           .cl-logos2{
            margin-bottom:20px !important;
           }
         .cl-logos{
          margin-bottom:25px !important;
          padding-bottom: 50px;
          border-bottom: 1px solid #eee;
         }

  .Crepeaholic{width: 90%;}.The-Waresh{width: 78%;}.Deli-sushi{width: 71%; margin-top: 2px;}.Mini-house{width: 70%; margin-top: -19px;}.Masti{width:100%;    margin-top: 12px;}.DarSaif{width: 75%;
    }.micro{width: 65%}.Chateau{width:65%;}
 
 @media only screen and (max-width: 768px){ 
   .DarSaif{width: 100%;
    }
    .Chateau{
      width: 100%;
    }
 }
   @media only screen and (max-width: 480px){
   .Crepeaholic{width: 80%;    margin-bottom: 30px;}.The-Waresh{width: 80%;    margin-bottom: 30px;}.Deli-sushi{width: 50%;    margin-bottom: 30px;}.Mini-house{width: 50%;    margin-bottom: 30px;}.Masti{width: 80%;    margin-bottom: 30px;}.DarSaif{width: 100%;
   }.Chateau{
    width: 100%;
   }
   .cherry-clients{
          font-size: 23px;
          font-weight: 600;
          color: #f93d66;
              padding-top: 50px;
    padding-bottom: 35px;
          margin-top: 85px;
          margin-bottom: 25px;
          border-top: 1px solid #eee;
         }
         .pb-120 {
    padding-bottom: 0px !important;
}
#newtag {
    top: 60px;
}
#btn-apple {
    margin-right: 0px;
  }
  #btn-andriod {
    margin-left: 0px;
}
   }
   @media only screen and (max-width: 360px){
       .cherry-clients{
          font-size: 23px;
          font-weight: 600;
          color: #f93d66;
          padding-top: 50px;
          padding-bottom: 35px;
          margin-top: 120px;
          margin-bottom: 10px;
          border-top: 1px solid #eee;
         }
    }

    @media only screen and (max-width: 320px){
       .cherry-clients{
          font-size: 23px;
          font-weight: 600;
          color: #f93d66;
          padding-top: 50px;
          padding-bottom: 35px;
          margin-top: 45px;
          margin-bottom: 10px;
          border-top: 1px solid #eee;
         }
    }

      </style>
   </head>
   <body>
      <!-- Preloader -->
      <div class="loader">
         <!-- Preloader inner -->
         <div class="loader-inner">
            <svg width="120" height="220" viewbox="0 0 100 100" class="loading-spinner" version="1.1" xmlns="http://www.w3.org/2000/svg">
               <circle class="spinner" cx="50" cy="50" r="21" fill="#ffffff" stroke-width="3"/>
            </svg>
         </div>
         <!-- End preloader inner -->
      </div>
      <!-- End preloader-->
      <!--Wrapper-->
      <div class="wrapper">
         <!--Hero section-->
         <section class="hero">
            <!--Header-->
            <!-- <div class="ss1" >
            <div class="ss2" ><span class="ss3" >Season's discount,<b> 50% OFF</b> on your first year subscription, valid up to 15th December </span></div>
            </div> -->
            <header class="header default">
               <div class=" left-part">
                  <a class="logo scroll hidden-xs hidden-sm" href="#hero">
                     <img src="<?php echo base_url();?>/img/white-logo.svg" alt="white_logo"/>
                  </a>
                  <a class="logo scroll gray" href="#hero">
                     <img src="<?php echo base_url();?>/img/gray-logo.svg" alt="gray_logo"/>
                  </a>
               </div>
               <div class="right-part">
                  <nav class="main-nav">
                     <div class="toggle-mobile-but">
                        <a href="#" class="mobile-but" >
                           <div class="lines"></div>
                        </a>
                     </div>
                     <ul>
                        <li><a class="scroll" href="#about">about</a></li>
                        <li><a class="scroll" href="<?php echo base_url();?>/features.php">features</a></li>
                        <!-- <li><a class="scroll" href="#gallery">gallery</a></li> -->
                        <li><a class="scroll" href="<?php echo base_url();?>/pricing.php">pricing</a></li>
                        <li><a class="scroll" href="#faq">faq</a></li>
                        <li><a class="but login scroll" href="<?php echo base_url();?>/login">login<i class="icon-lock"></i></a></li>
                        <li><a class="scroll" href="<?php echo base_url();?>/contact.php">contact us</a>
                        </li>
                     </ul>
                  </nav>
               </div>
            </header>
            <!--End header-->
            <!--Inner hero-->
            <div class="inner-hero">
               <!--Container-->
               <div class="container hero-content">
                  <!--Row-->
                  <div class="row">
                     <div class="col-sm-10 col-sm-offset-1 text-center">
                        <div class="block-teaser">
                           <h1>
                             A Contactless QR based and Digital <br>Menu for Restaurants and Cafes
                           </h1>
                           <h4 class="but medium mt-20 start-price"  >Start with Free-For-Life acount</h4>
                           <p class="lead">
                            Customise and easily update your Restaurant / Cafe Contactless QR Digital menu for Web, Android tablets and iPad 
                           </p>
                           <!-- <p><b>Season's discount,<b> 50% OFF</b> on your first year subscription</b></p> -->
                           <a class="but medium mt-20" href="https://www.cherrymenu.com/login/registration"> Sign up for free</a>

                           <a class="but medium mt-20" id="btn-explore" href="https://www.cherrymenu.com/features.php">Explore features</a>

                           <p class="proud-emirati">
                           <img src="<?php echo base_url();?>/img/Emirati.png" alt="A proud Emirati product">&nbsp;&nbsp; A proud Emirati product, Coded with passion in Dubai.
                           </p>
                        </div>
                     </div>
                  </div>
                  <!--End row-->
                  <!--Row-->
                  <div class="row">
                     <div class="col-md-8 col-md-offset-2 ">
                        <div class="block-media mt-60 page">
                           <div class="block-shot">
                              <img src="<?php echo base_url();?>/img/cherry-menu-main.png" alt="cherrymenu main menu">
                           </div>

                          
                        </div>
                     </div>
                  </div>
                  <!--End row-->
               </div>
               <!--End container-->
            </div>
            <!--End inner hero-->
         </section>
         <!--End hero section-->
         <!--About section-->
         <section id="about" class="about pt-120 pb-120">
            <!--Container-->
            <div class="container">
               <!--Row-->
               <div class="row mb-60 cl-logos2">
                  <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">
                    <div class="block-content text-center cl-logos" >
                     <h2 class="cherry-clients">Some of the amazing places currently running Cherry Menu</h2>
                           <!-- <a data-type="youtube" href="https://youtu.be/Mk6aW39bMtg" class="play-btn venobox"></a> -->
                          
                       <div class="row">
                        <div class="col-sm-3 col-md-3"><img src="<?php echo base_url();?>/img/clients/Crepeaholic.png" class="Crepeaholic"  alt="cherrymenu" ></div>
                        <div class="col-sm-3 col-md-3"><img src="<?php echo base_url();?>/img/clients/The-Waresh.png" class="The-Waresh" alt="cherrymenu" ></div>
                        <div class="col-sm-3 col-md-2"><img src="<?php echo base_url();?>/img/clients/Deli-sushi.png" class="Deli-sushi" alt="cherrymenu" ></div>
                        <div class="col-sm-3 col-md-2"><img src="<?php echo base_url();?>/img/clients/Mini-house.png" class="Mini-house" alt="cherrymenu"  ></div>
                        <div class="col-sm-3 col-md-2"><img src="<?php echo base_url();?>/img/clients/Masti.png" class="Masti" alt="cherrymenu" ></div>
                        
                        
                       </div><br><br>
                      <div class="row">
                        <div class="col-sm-3 col-md-2.5"><img src="<?php echo base_url();?>/img/clients/DarSaif-01.png" class="DarSaif" alt="cherrymenu" ></div>
                        <div class="col-sm-3 col-md-2"><img src="<?php echo base_url();?>/img/clients/micro.jpg" class="micro"  alt="cherrymenu" ></div>
                      <div class="col-sm-3 col-md-2"><br><img src="<?php echo base_url();?>/img/clients/342-North.png" class="342-North"  alt="cherrymenu" ></div>
                      <div class="col-sm-3 col-md-2"><br><img src="<?php echo base_url();?>/img/clients/Chateau Blancc.png" class="Chateau"   alt="cherrymenu" ></div>

                      <div class="col-sm-3 col-md-3"><br><img src="<?php echo base_url();?>/img/clients/Cookies-Break.jpg" class="Cookies-Break"   alt="cherrymenu" ></div>
                       </div>
                       <br><br>
                       <div class="row">
                        <div class="col-sm-3 col-md-3">
                          <img src="<?php echo base_url();?>/img/clients/chimnos.jpeg" class="DarSaif" alt="cherrymenu" />
                        </div>
                       </div>
                      </div>  
                  </div>
                
               </div>
               <!--End row-->

               <!--Row-->
               <div class="row mb-60 Pictures-are-worth">
                  <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
                   
                     <div class="block-content text-center">
                        <h2 class="title h2-title">Pictures are Worth a Thousand Words
                        </h2>
                        <p class="lead">Setup a Professional Digital Menu Within Minutes</p>
                     </div>
                  </div>
               </div>
               <!--End row-->
               <!--Row-->
               <div class="row mb-60">
                  <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
                     <div class="block-content text-center">
                     <p class="lead" style="text-align: left;">
                      <b style="font-size: 17px;">
                        We've added a contactless, QR Based menu as new features that comes as standard with all accounts to help you server your customers at these time of the COVID-19 Pandemic, A copy of the menu you upload to your system will be available on a URL that is automatically linked to a QR Code, this allows you to fully go digital and contactless within you restaurant or cafe and, you can send short-links via Whatsapp or any social media of these menus, all this while keeping the advanced features that Cherry Menu brings you like adding images, updates in less than a min, up-selling items...etc
                      </b><br/><br/>
                      <!-- CherryMenu is an innovative solution which is easy to use and customizable according to the requirements. A Digital restaurant menu will allow your restaurant or cafe to present menu in an attractive and interactive manner. --> CherryMenu offers an innovative, professional, and simple Digital Menu solution for your restaurant. We build applications that can cater to your unique requirements. A digital restaurant menu will allow your restaurant or café to present the menu in an appealing way and interactive manner.<br/>
                     <!-- Our mission at CherryMenu is to create more interactive and engaging customer experiences that will generate higher revenues and increase margins. The integration of the CherryMenu application will enhance server and guest satisfaction immediately. -->
                   At CherryMenu, our main aim is to create more interactive and engaging customer experiences that will generate higher revenues and increase margins. The integration of the CherryMenu application will enhance server and guest satisfaction immediately. With the best restaurant menu, and apps, you will be able to manage, report, and grow your business with ease.</p>

                   <p style="text-align: left;"><b>What makes us unrivaled?</b><br>
                   We are committed to providing you with flexible and easy to use technologies. We are always striving to adopt new trends. With our restaurant management applications and software, you will be able to provide your guests with some happy moments. </p>

                   <p style="text-align: left;"><b>Key Benefits of our Digital Menu Boards and POS System for your restaurant:</b><br></p>
                   <ul style="text-align: left; padding-left: 15px; list-style-type: initial;">
                     <li>Easily update the digital menu boards with new content</li>
                     <li>Catch most of your customers’ attention with highlighted promotions and up sell</li>
                     <li>Reduce the perceived wait times</li>
                     <li>Ensure long-term cost savings</li>
                   </ul>
                     </div>
                  </div>
               </div>
               <!--End row-->
             
               <!--End row-->
            </div>
            <!--End container-->
         </section>
         <!--End about section-->

         <!--Features section-->
         <section id="features" class="features bg-grey pt-120 pb-120">
            <!--Container-->
            <div class="container">
               <!--Row-->
               <div class="row vertical-align">
                  <div class="col-md-6 col-sm-5">
                     <!-- <div class="block-shot"> -->
                        <div >
                      
                      <img src="<?php echo base_url();?>/img/cherrymenu-screens3.png" alt="cherrymenu screens">
                      <!--   <img src="<?php echo base_url();?>/img/cherrymenu-screens_1.png" alt="">
                        <img src="<?php echo base_url();?>/img/cherrymenu-screens_2.png" alt=""> -->
                     </div>
                  </div>
                  <div class="col-md-5 col-sm-8 col-sm-offset-1">
                     <h3 class="title">Main Features
                     </h3>
                     <ul class="features-list">
                      <li>Contactless menu <span id="newtag">New</span></li>
                      <li>Self order </li>
                        <li>Multiple language: Arabic and English</li>
                      
                        <li class="dropdown">iPad 

                        </li>
                        <li>Android</li>
                        <li>Less than a minute to update items</li>
                        <li>Analytics and reports</li>
                        <li>Multi user</li>
                        <li>Items gallery: photos and videos</li>
                        <li>Offline, you don’t need internet to operate</li>
                        <li>Item features and description</li>
                        <li>Digital feedback form </li>
                        <li>Multiple ways to categories menus and items</li>
                        <li>Customization: your logo and background </li>
                     </ul>
                     <a class="but mauve mt-20 scroll" href="https://www.cherrymenu.com/features.php">Explore features</a>
                  </div>
               </div>
               <!--End row-->
            </div>
            <!--End container-->

      
         </section>
         <!--End features section-->

         <section class="trial bg-rose pt-60 pb-60">
            <!--Container-->
            <div class="container">
               <!--Row-->
               <div class="row">
                  <div class="col-md-5 col-md-offset-1">
                     <h2 class="title light h2-title">Start your 30 day free trial</h2>
                  </div>
                  <div class="col-md-6 text-center">
                     <div class="form">
                        <form method="post" class="trial-form" action="https://www.cherrymenu.com/login/registration">
                           <input value="Sign up now" class="but  submit" style="    font-weight: 600;" type="submit">
                        </form>
                     </div>
                  </div>
               </div>
               <!--End row-->
            </div>
            <!--End container-->
         </section>
         
         <!--Gallery section-->
         <section id="gallery" class="gallery bg-grey pt-120 pb-120 text-center">
            <!--Container-->
            <div class="container">
               <!--Row-->
               <div class="row">
                  <div class="col-md-8 col-md-offset-2">
                     <h2 class="title h2-title">How does CherryMenu look like? Here are few screen shots of the admin panel and App
                     </h2>
                  </div>
                  <div class="col-md-8 col-md-offset-2 mt-60 ">
                     <ul id="carousel">
                      <li><img  src="<?php echo base_url();?>/img/s3.jpg" width="250" alt="ContactlessMenu"></li>
                      <li><img  src="<?php echo base_url();?>/img/s2.jpg" width="250" alt="ContactlessMenu"></li>
                      <li><img  src="<?php echo base_url();?>/img/s1.jpg" width="250" alt="ContactlessMenu"></li>
                       <li><img  src="<?php echo base_url();?>/img/Cherry-menu-self-order.jpg" alt="Self order"></li>
                        <li><img  src="<?php echo base_url();?>/img/Dish-page.jpg" alt="dish page"></li>
                        <li><img  src="<?php echo base_url();?>/img/Feedback-form.jpg" alt="feedback form"></li>
                        <li><img  src="<?php echo base_url();?>/img/Login.jpg" alt="login"></li>
                        <li><img  src="<?php echo base_url();?>/img/Select-category.jpg" alt="select category"></li>
                        <li><img  src="<?php echo base_url();?>/img/select-dish.jpg" alt="select dish"></li>
                        <li><img  src="<?php echo base_url();?>/img/Select-user.jpg" alt="select user"></li>

                     </ul>
                  </div>
               </div>
               <!--End row-->
            </div>
            <!--End container-->
         </section>
         <!--End gallery section-->
       
         <!--Faq section-->
         <section id="faq" class="faq bg-grey pt-120 pb-120 ">
            <!--Container-->
            <div class="container">
               <!--Row-->
               <div class="row">
                 
                  <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center mt-50">
                     <h3 class="title mb-5">Want to know more about Cherry Menu and how it works?</h3>
                     <p> Visit our support center, it has all the FAQ and information you're looking for.</p>
                     <a class="but medium mt-40 scroll" href="https://www.cherrymenu.com/support/FAQ/" target="_blank">FAQ</a>

                     <a class="but medium mt-40 scroll" href="https://www.cherrymenu.com/support/" target="_blank">Knowledge base</a>
                  </div>
               </div>
               <!--End row-->
            </div>
            <!--End container-->
         </section>
         <!--End faq section-->
         <!--Login section-->
         <section id="login" class="login bg-dark-grey pt-70 pb-20 text-center">
            <!--Container-->
            <div class="container">
               <!--Row-->
               <div class="row">
                  <div class="col-md-12 text-center mb-30">
                     <h2 class="title light h2-title" style="font-size: 34px;    font-weight: 600;">Download Cherry Menu</h2>
                  </div>
               </div>
               <!--End row-->
               <!--Row-->
              
               <div class="row">
                  <div class="col-md-12">
                     <div class="form">
                        <a class="but medium mt-10 scroll" id="btn-apple" target="_blank" href="https://itunes.apple.com/ae/app/cherry-menu/id1441509800?mt=8t"><img  src="<?php echo base_url();?>/img/apple.svg" alt="select user"><div style="padding-top: 12px;font-weight: bold;">iOS</div></a>
                         <a class="but medium mt-10 scroll" id="btn-andriod" target="_blank" href="https://play.google.com/store/apps/details?id=com.virtualdusk.emenu"><img  src="<?php echo base_url();?>/img/android.svg" alt="select user"><div style="padding-top: 12px;font-weight: bold;">ANDROID</div></a>
                        <!-- <form method="post" class="login-form" action="https://www.cherrymenu.com/login/">
                           <input value="Login here" class="but  submit" type="submit">
                        </form> -->
                     </div>
                  </div>
               </div>
                <div class="row">
                  <div class="col-md-8 col-md-offset-3 pt-50" style="padding:0px;">

                        <p style="    float: left; font-size: 16px; color: #fff;">For more info on device capabilities <a target="_blank" href="https://www.cherrymenu.com/support/devices-capabilities/" style="color:#4672D9;    text-decoration: underline;">Click Here</a></p>
                  </div>
               </div>
               <!--End row-->
            </div>
            <!--End container-->
         </section>
         <!--End login section-->
       
        <div class="whatsapp">
        <a href="https://api.whatsapp.com/send?phone=9710503936829" target="_blank">    
        <h5 style="font-size: 28px;"><i class="fa fa-whatsapp"></i></h5></a>
        </div>

         <!--End socials section-->
         <footer class="footer pt-90 pb-90 text-center">
            <!--Container-->
            <div class="container">
               <!--Row-->
               <div class="row">
                  <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center ">
                     <a class="logo scroll" href="#hero">
                        <img src="<?php echo base_url();?>/img/gray-logo.svg" alt="gray logo" />
                     </a>
                     <p>&copy; <script>document.write(new Date().getFullYear())</script> all rights reserved - a product of <a href="https://virtualdusk.com/" target="_blank">Virtual Dusk</a>.</p>
                     <ul class="mt-10">
                      <li><a href="terms-of-services.php" target="_blank">Terms of Services</a></li>
                        <li><a href="Privacy-Policy.php" target="_blank">Privacy Policy</a></li>
                        <li><a href="https://www.cherrymenu.com/support" target="_blank">Support</a></li>
                     </ul>
                  </div>
               </div>
               <!--End row-->
            </div>
            <!--End container-->
         </footer>
      </div>
      <!-- End wrapper-->
      <!--Javascript--> 
      <script src="<?php echo base_url();?>/js/jquery-1.12.4.min.js" ></script>
      <script src="<?php echo base_url();?>/js/owl.carousel.js" ></script>
      <script src="<?php echo base_url();?>/js/venobox.min.js" ></script>
      <script src="<?php echo base_url();?>/js/smooth-scroll.js" ></script>
      <script src="<?php echo base_url();?>/js/placeholders.min.js" ></script>
      <script src="<?php echo base_url();?>/js/script.js" ></script>
      <!-- Google analytics -->
      <!-- End google analytics -->
    <script>
    function myFunction() {
      var x = document.getElementById("phone_in");
      if (x.style.display === "none") {
        x.style.display = "block";
      } else {
        x.style.display = "none";
      }
    }
    </script>

   
   

<!-- Fresh Chat Body start--> 
        <!-- <script>
            window.fcWidget.init({
                token: "ec562bd8-2b8d-47e8-b403-ea0c9dcf8530",
                host: "https://wchat.freshchat.com",
                config: {
                    headerProperty: {
                        appName: 'Cherrymenu',
                        appLogo: 'https://www.cherrymenu.com/img/faviconcherry.jpg',
                        backgroundColor: '#f93d66',
                        foregroundColor: '#ffffff',
                        hidePoweredBy:true,
                    },
                },    
                siteId: "Cherrymenu" 
            });
        </script> -->
        <!-- Fresh Chat Body END--> 
   </body>
</html>
<?php }?>