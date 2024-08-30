<!--<div class="menubar-area footer-fixed">-->
<!--    <div class="toolbar-inner menubar-nav">-->
<!--        <a href="#" class="nav-link active">-->
<!--            <i class="fi fi-rr-home"></i>-->
<!--        </a>-->
<!--        <a href="wishlist.html" class="nav-link">-->
<!--            <i class="fi fi-rr-heart"></i>-->
<!--        </a>-->
<!--        <a href="cart.html" class="nav-link">-->
<!--            <i class="fi fi-rr-shopping-cart"></i>-->
<!--        </a>-->
<!--        <a href="#" class="nav-link">-->
<!--            <i class="fi fi-rr-user"></i>-->
<!--        </a>-->
<!--    </div>-->
<!--</div>-->

<div class="menubar-area footer-fixed">
    <div class="toolbar-inner menubar-nav justify-content-center">
        <a href="<?php echo $this->uri->segment(2) == 'r' || $this->uri->segment(1) == 'restaurant' ? base_url('/'.$data['name']) : '#'; ?>" class="nav-link <?php echo $this->uri->segment(1) == 'r' && $this->uri->segment(2) !== 'item_detail' ? 'active' : ''; ?>">
            <i class="fi fi-rr-home"></i>
        </a>
        <a href="<?php echo base_url('/restaurant/review?rid='.$data['restid']) ?>" class="nav-link <?php echo $this->uri->segment(1) == 'review' ? 'active' : ''; ?>">
            <i class="fi fi-rr-note"></i>
        </a>
    </div>
</div>

</div>

<?php
function current_url_custom()
{
    $CI =& get_instance();
    $url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
}
?>
<!-- PWA End -->
<!--**********************************
    Scripts
***********************************-->
<script src="<?= base_url('frontend/assets/js/jquery.js') ?>"></script>
<script src="<?= base_url('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('frontend/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js') ?>"></script>
<script src="<?= base_url('frontend/assets/vendor/swiper/swiper-bundle.min.js') ?>"></script>
<script src="<?= base_url('frontend/assets/vendor/wnumb/wNumb.js') ?>"></script><!-- WNUMB -->
<script src="<?= base_url('frontend/assets/vendor/nouislider/nouislider.min.js') ?>"></script><!-- NOUSLIDER MIN JS-->
<script src="<?= base_url('frontend/assets/js/noui-slider.init.js') ?>"></script><!-- NOUSLIDER MIN JS-->
<script src="<?= base_url('frontend/assets/js/dz.carousel.js') ?>"></script>
<script src="<?= base_url('frontend/assets/js/settings.js') ?>"></script>
<script src="<?= base_url('frontend/assets/js/custom.js') ?>"></script>
<script>
    // self.addEventListener('install', (e) => {
    //     e.waitUntil(
    //         caches.open('app-store').then((cache) => cache.addAll([
    //             '/kibunCafe'
    //         ])),
    //     );
    // });
    //
    // self.addEventListener('fetch', (e) => {
    //     e.respondWith(
    //         caches.match(e.request).then(function (response) {
    //             return response || fetch(e.request);
    //         })
    //     );
    // });

    var myDynamicManifest = {
        "name": "CherryMenu",
        "short_name": "CherryMenu",
        "start_url": "<?php echo current_url_custom(); ?>",
        "background_color": "#FFF",
        "display": "standalone",
        "icons": [{
            "src": "<?php echo base_url('frontend/assets/images/app-logo/pwa150.png'); ?>",
            "sizes": "150x150",
            "type": "image/png"
        }]
    }

    const stringManifest = JSON.stringify(myDynamicManifest);
    const blob = new Blob([stringManifest], {type: 'application/json'});
    const manifestURL = URL.createObjectURL(blob);
    document.querySelector('#my-manifest-cherry-menu').setAttribute('href', manifestURL);

    var link = document.createElement('Link');
    link.rel = "manifest";
    link.setAttribute('href', 'data:application/json;charset=8' + stringManifest)


    $(document).on('change', '.rating-star', function (){
        let val = $(this).val();
        $("#ratingText").html(val + '.0');
    });

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register("<?php echo base_url('service-worker.js'); ?>").then(registration => {
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, err => {
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }

    // self.addEventListener('fetch', event => {
    //     event.respondWith(
    //         caches.open(CACHE_NAME).then(cache => {
    //             return cache.match(event.request).then(response => {
    //                 const fetchPromise = fetch(event.request).then(networkResponse => {
    //                     if (networkResponse) {
    //                         cache.put(event.request, networkResponse.clone());
    //                     }
    //                     return networkResponse;
    //                 });
    //                 return response || fetchPromise;
    //             });
    //         })
    //     );
    // });
</script>
</body>
</html>