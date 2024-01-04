
<style>

.product-body{
    width:10% !important;
}

    </style>


<div class="feature-product py-4">
    <div class="container">
    <h3 class="category-headding ">All Products</h3>
                <div class="headding-border"></div>
        <!-- <div class="headding-border position-relative mb-4 d-none d-sm-block"></div> -->
        <div class="row">
            <?php

$all_products = $this->db->limit('20')->from('product_information')->get()->result();
$user_type = $this->session->userdata('user_type');

//6 is retailer 

if ($user_type == 6) {
    if ($all_products) {
        foreach ($all_products as $key => $retail_product) {
            $all_products[$key]->price = $retail_product->retail_price;
        }
    }
}
//7 is wholesaler 
if ($user_type == 7) {
    if ($all_products) {
        foreach ($all_products as $key => $whole_product) {
            $all_products[$key]->price = $whole_product->wholesale_price;
        }
    }
}
             foreach ($all_products as $product) { 
                ?>
            <div class=" product-body mb-3 border-0  " style="width:10% !important;">
                <!-- <div class="feature-card card border-0 border"> -->
                <div class="feature-card card border-0 ">

                    <div class="card-body">
                        <?php $prodlink =  base_url('/product/' . remove_space($product->product_name) . '/' . $product->product_id) ?>
                        <a href="<?php echo $prodlink; ?>"  class="product-img d-block">
                            <?php if (@getimagesize($product->image_thumb) === false) { ?>
                                <img src="<?php echo base_url() . '/my-assets/image/no-image.jpg' ?>" class="media-object img-fluid"
                                     alt="image">
                            <?php } else { ?>
                                <img class="img-fluid " src="<?php echo base_url() . $product->image_thumb ?>" alt="image">
                            <?php } ?>
                        </a>
                        <h3 class="product-name fs-13 font-weight-600 overflow-hidden mt-4 text-center">
                            <a href="<?php echo $prodlink; ?>"  class="text-black"><?php echo html_escape($product->product_name) ?></a>
                        </h3>

                        <!-- <div class="star-rating">
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
                        </div> -->
                        <!-- <div class="product-price font-weight-bolder font-italic text-center">
                        <?php

                        if ($product->onsale == 1 && !empty($product->onsale_price)) {
                            $price_val = $product->onsale_price * $target_con_rate;
                        }else{
                            $price_val = $product->price * $target_con_rate;
                        }

                         echo  (($position1 == 0) ? $currency1 . number_format($price_val, 2, '.', ',') : number_format($price_val, 2, '.', ',') . $currency1); ?> / <?php echo display('unit') ?> 
                         </div> -->
                         
                         <!-- <div class="text-center">
                                        <a href="javascript:void(0)" class="btn btn-soft-primary btn-pill add-to-cart font-weight-500 d-inline-flex align-items-center mt-2 color412" onclick="add_to_cart_item('<?php echo $product->product_id;?>', '<?php echo remove_space($product->product_name);?>', '<?php echo $product->default_variant; ?>', <?php echo $product->variant_price; ?>)">
                                            <i data-feather="shopping-cart" class="mr-2"></i><?php echo display('add_to_cart'); ?>
                                        </a>	
                                    </div> -->

                    </div>
                    
                </div>
            </div>

            <?php } ?>
        </div>
    </div>
</div>








