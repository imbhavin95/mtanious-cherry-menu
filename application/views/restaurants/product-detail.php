<div class="container p-0">
<!--                --><?php //echo "<pre>";  print_r($data);  exit;?>
    <div class="dz-product-preview bg-primary">
        <div class="swiper product-detail-swiper">
            <div class="swiper-wrapper">
                <?php
                    if(count($data['singleitem']['images']['new']) > 0){
                        foreach ($data['singleitem']['images']['new'] as $image) {
                            ?>
                                <div class="swiper-slide">
                                    <div class="dz-media">
                                        <?php $imagelink = "https://www.cherrymenu.com/login/public/restaurants/" . $data['restid'] . "/items/" . $image['item_id'] . "/" . $image['image']; ?>
                                        <img src="<?php echo $imagelink; ?>" alt="">
                                    </div>
                                </div>
                            <?php
                        }
                    }else{
                        ?>
                    <div class="swiper-slide">
                        <div class="dz-media">
                            <img src="<?php echo base_url('frontend/assets/images/no-image.jpg'); ?>" alt="">
                        </div>
                    </div>
                    <?php
                }

                ?>
            </div>
        </div>
    </div>
    <?php foreach ($data['singleitem']['Item'] as $item) {
        ?>
        <div class="dz-product-detail">
            <div class="dz-handle"></div>
            <div class="detail-content">
                <h4 class="title"><?php echo $item['arabian_title']; ?></h4>
                <h4 class="title"><?php echo $item['title']; ?></h4>
            </div>
<!--            <div class="dz-item-rating">4.5</div>-->
            <div class="item-wrapper">
                <div class="dz-meta-items">
                    <div class="dz-price flex-1">
                        <div class="price"><sub>AED</sub><?php echo $item['price']; ?></div>
                    </div>
                    <div class="dz-quantity">
                        <div class="dz-stepper style-3">
                            <a><img src="https://www.cherrymenu.com/login/public/webmenu/Peanut.png"></a>
                            <a><img src="https://www.cherrymenu.com/login/public/webmenu/Chilly.png"></a>
                            <a><img src="https://www.cherrymenu.com/login/public/webmenu/Leaf.png"></a>
                        </div>
                    </div>
                </div>
                <div class="description">
                    <p class="text-light"><?php echo $item['arabian_description']; ?></p>
                    <p class="text-light"><?php echo $item['description']; ?></p>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
