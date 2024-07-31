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

        <section id="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-xs-12">
                                    <a href="index.html"><span><i class="fa fa-chevron-left" aria-hidden="true"></i></span></a>
                                    <img src="https://www.cherrymenu.com/login/public/image/img2.jpeg" />
                                </div>
                                <div class="col-lg-6 col-sm-6 col-xs-12">
                                    <ul> 
                                        <li><img src="https://www.cherrymenu.com/login/public/image/img3.jpeg"></li>
                                        <li><img src="https://www.cherrymenu.com/login/public/image/img4.jpeg"></li>
                                    </ul>
                                    <h4>The Waresh Breakfast Meal</h4>
                                    <h6>AED 25.00</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://www.cherrymenu.com/login/public/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bxslider/4.2.15/jquery.bxslider.min.js"></script>
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
				<script>
				$(document).ready(function(){
				  // Add smooth scrolling to all links
				  $("a").on('click', function(event) {

				    // Make sure this.hash has a value before overriding default behavior
				    if (this.hash !== "") {
				      // Prevent default anchor click behavior
				      event.preventDefault();

				      // Store hash
				      var hash = this.hash;

				      // Using jQuery's animate() method to add smooth page scroll
				      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
				      $('html, body').animate({
				        scrollTop: $(hash).offset().top
				      }, 800, function(){

				        // Add hash (#) to URL when done scrolling (default click behavior)
				        window.location.hash = hash;
				      });
				    } // End if
				  });
				});
				</script>
        <script type="text/javascript">
            $(document).ready(function () {
                $(".bxslider").bxSlider({
                    auto: false,
                    controls: true,
                    pager: false,
                    infiniteLoop: false,
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
