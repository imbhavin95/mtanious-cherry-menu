<main class="page-content space-top p-b50">
    <div class="container">
        <form action="<?php echo base_url('submit-review'); ?>" method="post">
            <input type="hidden" value="<?php echo $data['restid']; ?>" name="rid">
            <div class="write-reviews-box">
                <div class="rating-content">
                    <h5 class="title fs-2">Write a review for <?php echo $data['name']; ?></h5>
                </div>
                <div class="rating-info">
                    <h3 class="rating-text fs-3" id="ratingText">Choose Rating</h3>
                    <ul class="dz-star-rating">
                        <div class="feedback">
                            <div class="rating">
                                <input type="radio" name="rating" required class="rating-star" value="5" id="rating-5">
                                <label for="rating-5"></label>
                                <input type="radio" name="rating" required class="rating-star" value="4" id="rating-4">
                                <label for="rating-4"></label>
                                <input type="radio" name="rating" required class="rating-star" value="3" id="rating-3">
                                <label for="rating-3"></label>
                                <input type="radio" name="rating" required class="rating-star" value="2" id="rating-2">
                                <label for="rating-2"></label>
                                <input type="radio" name="rating" required class="rating-star" value="1" id="rating-1">
                                <label for="rating-1"></label>
                            </div>
                        </div>
                    </ul>
                </div>
                <div class="mb-3">
                    <?php
                    if($this->session->flashdata('review_success'))
                    {
                        ?>
                        <div class="alert alert-success mb-3" role="alert">
                            <?php echo $this->session->flashdata('review_success'); ?>
                        </div>
                        <?php

                    }
                    ?>
                    <input class="form-control bg-white mb-3" required name="name" id="name" placeholder="Your Name" />
                    <textarea class="form-control bg-white mb-3" required name="feedback" id="feedback" placeholder="Write your review here"></textarea>
                    <button type="submit" class="btn btn-primary rounded-xl btn-lg btn-thin w-100 gap-2">Send</button>
                </div>
            </div>
        </form>
    </div>
</main>