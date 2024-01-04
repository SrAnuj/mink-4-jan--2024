<div class="col-md-3 col-12 col-12" >
                    <div class="col-md-12"  id="inner_card_set">
                        <div class="card"  id="first_card">
 <?php $prodlink =  base_url('/product/' . remove_space($product->product_name) . '/' . $product->product_id) ?>
                        <a href="<?php echo $prodlink; ?>"  class="">
                            <?php if (@getimagesize($product->image_thumb) === false) { ?>
                                <img src="<?php echo base_url() . '/my-assets/image/no-image.jpg' ?>" class="media-object img-fluid"
                                     alt="image">
                            <?php } else { ?>
                                <img class="img-fluid " src="<?php echo base_url() . $product->image_thumb ?>" alt="image">
                            <?php } ?>
                        </a>
                        <div class="card-body">
                                <h5 class="card-title">                            <a href="<?php echo $prodlink; ?>"  class="text-black"><?php echo html_escape($product->product_name) ?></a>
</h5>
                                <!--<p class="card-text">Some quick example text to build on the card title and make up-->
                                <!--    the-->
                                <!--    bulk of the card's content.-->
                                <!--</p>-->
                                <!--<div id="card_rating">-->
                                <!--    <i class="fa-regular fa-star-half-stroke"></i>-->
                                <!--    <i class="fa-regular fa-star-half-stroke"></i>-->
                                <!--    <i class="fa-regular fa-star-half-stroke"></i>-->
                                <!--    <i class="fa-regular fa-star-half-stroke"></i>-->
                                <!--    <i class="fa-regular fa-star-half-stroke"></i>-->
                                <!--    <span style="color: black;">(11)</span>-->
                                <!--</div>-->
                                
                                  <div class="star-rating">
                                <?php
                                 $result = $this->db->select('IFNULL(SUM(rate),0) as t_rates, count(rate) as t_reviewer')
                                         ->from('product_review')
                                        ->where('product_id', $product->product_id)
                                        ->where('status', 1)
                                        ->get()
                                        ->row();
                                        $p_review = (!empty($result->t_reviewer)?$result->t_rates / $result->t_reviewer:0);
                                        for($s=1; $s<=5; $s++){

                                            if($s <= floor($p_review)) {
                                                echo '<i class="fas fa-star"></i>';
                                            } else if($s == ceil($p_review)) {
                                                echo '<i class="fas fa-star-half-alt"></i>';
                                            }else{
                                                echo '<i class="far fa-star"></i>';
                                            }
                                        }
                                    ?>
                            </div>
                             <p class="entry-meta">
                                <?php
                                if ($product->onsale == 1 && !empty($product->onsale_price)) {
                                    $price_val = $product->onsale_price * $target_con_rate;
                                }else{
                                    $price_val = $product->price * $target_con_rate;
                                }

                                echo  (($position1 == 0) ? $currency1 . number_format($price_val, 2, '.', ',') : number_format($price_val, 2, '.', ',') . $currency1); ?>

                            </p>
                                <a href="<?php echo $prodlink; ?>" class="btn btn-outline-success px-5">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>