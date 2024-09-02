<!-- Main Content Start -->
<main class="page-content space-top p-b60">
    <div class="container">
        <!-- SearchBox -->
        <!--        <div class="search-box">-->
        <!--            <div class="input-group input-radius input-rounded input-lg">-->
        <!--                <input type="search" placeholder="Search beverages or foods" class="form-control">-->
        <!--                <span class="input-group-text">-->
        <!--						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">-->
        <!--							<path d="M9.65925 19.3102C11.8044 19.3103 13.8882 18.5946 15.5806 17.2764L21.9653 23.6612C22.4423 24.1218 23.2023 24.1086 23.663 23.6316C24.1123 23.1664 24.1123 22.4288 23.663 21.9635L17.2782 15.5788C20.5491 11.3682 19.7874 5.30333 15.5769 2.03243C11.3663 -1.23848 5.30149 -0.476799 2.03058 3.73374C-1.24033 7.94428 -0.478646 14.0092 3.73189 17.2801C5.42702 18.5969 7.51269 19.3113 9.65925 19.3102ZM4.52915 4.5273C7.36245 1.69394 11.9561 1.69389 14.7895 4.5272C17.6229 7.3605 17.6229 11.9542 14.7896 14.7876C11.9563 17.6209 7.36261 17.621 4.52925 14.7877C4.5292 14.7876 4.5292 14.7876 4.52915 14.7876C1.69584 11.9749 1.67915 7.39794 4.49181 4.56464C4.50424 4.55216 4.51667 4.53973 4.52915 4.5273Z" fill="#C9C9C9"/>-->
        <!--						</svg>-->
        <!--					</span>-->
        <!--            </div>-->
        <!--        </div>-->
        <!-- SearchBox -->
<!--                                    --><?php // echo "<pre>"; print_r($data); exit; ?>
        <!-- Products Area -->
        <div class="dz-custom-swiper">
            <div thumbsSlider="" class="swiper mySwiper dz-tabs-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($data['item_data'] as $row) { ?>
                        <div class="swiper-slide">
                            <h5 class="title"><?php echo $row['name']; ?></h5>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!--            https://www.cherrymenu.com/login/public/restaurants/24404/items/75220/thumbnail/ -->

            <div class="swiper mySwiper2 dz-tabs-swiper2">
                <div class="swiper-wrapper">
                    <?php foreach ($data['item_data'] as $row) { ?>
                        <?php foreach ($row as $key => $item) { ?>
                            <?php if (gettype($item) == 'array') { ?>
                                <div class="swiper-slide">
                                    <?php foreach ($item as $subItem) { ?>
                                        <ul class="featured-list">
                                            <li>
                                                <div class="dz-card list">
                                                    <div class="dz-media">
                                                        <?php
                                                        $itemlink = base_url("/r/item_detail?cid=" . $subItem["category_id"] . "&itid=" . $subItem["id"] . "&rid=" . $data['restid'] . "&sid=1");
                                                        ?>
                                                        <?php $image = base_url('')."/login/public/restaurants/" . $data['restid'] . "/items/" . $subItem['id'] . "/thumbnail/" . $subItem['thumbnail']; ?>
                                                        <a href="<?php echo $itemlink ?>">
                                                            <img src="<?php echo $subItem['thumbnail'] ? $image : base_url('frontend/assets/images/no-image.jpg'); ?>"></a>
<!--                                                        <div class="dz-rating"><i class="fa fa-star"></i> 3.8</div>-->
                                                    </div>
                                                    <div class="dz-content">
                                                        <div class="dz-head">
                                                            <h6 class="title"><a
                                                                        href="<?php echo $itemlink ?>"><?php echo $subItem['arabian_title']; ?></a>
                                                            </h6>
                                                            <h6 class="title"><a
                                                                        href="<?php echo $itemlink ?>"><?php echo $subItem['title']; ?></a>
                                                            </h6>
                                                        </div>
                                                        <ul class="dz-meta">
                                                            <li class="dz-price flex-1"><?php echo 'AED ' . $subItem['price'] ?></li>
                                                            <li>
                                                                <a href="<?php echo $itemlink; ?>"
                                                                   class=" btn rounded-xl dz-buy-btn">
                                                                view
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    <?php } ?>
                                </div>
                            <?php }
                        }
                    } ?>
                </div>
            </div>
        </div>
        <!-- Products Area -->
    </div>
</main>
<!-- Main Content End -->