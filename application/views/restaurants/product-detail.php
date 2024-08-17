<div class="container p-0">
<!--    --><?php //echo "<pre>";
//    print_r($data);
//    exit; ?>
    <div class="dz-product-preview bg-primary">
        <div class="swiper product-detail-swiper">
            <div class="swiper-wrapper">
                <?php
                if (count($data['singleitem']['images']['new']) > 0) {
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
                } else {
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
                <h4 class="title d-flex justify-content-between">
                    <?php echo $item['title']; ?>

                    <?php if(!empty($item['time'])) { ?><span class="font-12"><?php echo $item['time']; ?> Mins</span> <?php } ?>
                </h4>
            </div>
            <!--            <div class="dz-item-rating">4.5</div>-->
            <div class="item-wrapper">
                <div class="dz-meta-items">
                    <div class="dz-price flex-1">
                        <div class="price"><sub>AED</sub><?php echo $item['price']; ?></div>
                    </div>
                    <div class="dz-quantity">
                        <div class="dz-stepper style-3">
                            <?php
                             if(!empty($item['type'])){
                                 $data = explode(',',$item['type']);
                             }
                            ?>
                            <?php if (in_array('Peanuts',$data)) { ?>
                                <a><img src="<?php echo base_url('/login/public/webmenu/peanut.svg') ?>" /></a>
                            <?php } ?>
                            <?php if (in_array('Spicy',$data)) { ?>
                            <a><img src="<?php echo base_url('/login/public/webmenu/chilly.svg') ?>" /></a>
                            <?php } ?>
                            <?php if (in_array('Veg',$data)) { ?>
                            <a><img src="<?php echo base_url('/login/public/webmenu/leaf.svg') ?>" /></a>
                            <?php } ?>
                            <?php if (in_array('Shell Fish',$data)) { ?>
                                <a><img src="<?php echo base_url('/login/public/webmenu/Shellfish.svg') ?>"></a>
                            <?php } ?>
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
