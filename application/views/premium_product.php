
<link href="<?php echo MOD_URL . 'web/views/themes/zaima/assets/css/mint.css'; ?>" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" ></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">


    <!-- kit.fontawesome -->
    <script src="https://kit.fontawesome.com/b79e598a14.js" crossorigin="anonymous"></script>

<style>

a {
    text-decoration:none !important;
}

    </style>


<div class="feature-product py-4">
    <div class="container">

    <h3 class="category-headding ">Premium Products</h3>
	
		<div class="row form-group">
<div class="col-md-12 justify-content-center">
	<div class="col-md-offset-2">
	<div class="col-md-8">
		<select name="multi_search_filter" id="multi_search_filter" multiple class="form-control selectpicker">
			 <?php
			foreach($this->db->from('filter_types')->get()->result_array() as $row)
			{
				echo '<option value="'.$row["fil_type_id"].'">'.$row["fil_type_name"].'</option>';    
			}
			?>
			</select>
			   <input type="hidden" name="hidden_country" id="hidden_country" />

		</div>
	</div>
</div>
</div>
                <div class="headding-border"></div>
        <!-- <div class="headding-border position-relative mb-4 d-none d-sm-block"></div> -->
        <div class="row" id="product_data">
            <?php
$all_products = $this->db->limit(8)->from('product_information')->get()->result();
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

//d($all_products);die;
             foreach ($all_products as $product) { 
                ?>
                
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
                
                
     

            <?php } ?>
            
        
        </div>
            
            <button type="button" class="btn btn-outline-success float-end mt-4">View All Premium Product <i
                    class="fa-solid fa-angle-right"></i></button>
                    
                    
                    
                     <div class="" id="poster_img">
        <img src="   <?php echo base_url(). '/assets/img/slid1.webp'; ?>" alt="">
    </div>
    </div>
</div>



  <div class="container-fluid" id="main_rounder">
        <div class="col-md-12">
            <h2>infant's corner</h2>
            <h5>a perfect blend of cool & chin</h5>

            <div class="row pt-5">
                <div class="col-md-2">
                    <div class="col-md-12" id="rouder_session">
                        <img src=" <?php echo base_url(). '/assets/img/slid1.webp'; ?>" alt="">
                        <a href="">onesies <i class="fa-solid fa-angle-right"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="col-md-12" id="rouder_session">
                        <img src=" <?php echo base_url(). '/assets/img/slid1.webp'; ?>" alt="">
                        <a href="">Multi Packs <i class="fa-solid fa-angle-right"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="col-md-12" id="rouder_session">
                        <img src=" <?php echo base_url(). '/assets/img/slid1.webp'; ?>" alt="">
                        <a href="">dungarees <i class="fa-solid fa-angle-right"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="col-md-12" id="rouder_session">
                        <img src=" <?php echo base_url(). '/assets/img/slid1.webp'; ?>" alt="">
                        <a href="">Rompers <i class="fa-solid fa-angle-right"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="col-md-12" id="rouder_session">
                        <img src=" <?php echo base_url(). '/assets/img/slid1.webp'; ?>" alt="">
                        <a href="">bodysuits <i class="fa-solid fa-angle-right"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="col-md-12" id="rouder_session">
                        <img src=" <?php echo base_url(). '/assets/img/slid1.webp'; ?>" alt="">
                        <a href="">onesies <i class="fa-solid fa-angle-right"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    
    


    <div class="container-fluid">
        <div class="col-md-12">
            <div class="row " id="main_marqee">
               <div class="inner-marq">
                <div class="col-md-2">
                    <h2>Top Product</h2>
                </div>

                <div class="col-md-10">
                     <div id="main_marquee"> 
                                            <div class="slider-animation">

                             <div class="slide-animation">
                  
                            </div>

                            <div class="slide-animation">
                        <img src=" <?php echo base_url(). '/assets/img/slid1.webp'; ?>" alt="">
                        <img src=" <?php echo base_url(). '/assets/img/slid2.webp'; ?>" alt="">
                        <img src=" <?php echo base_url(). '/assets/img/slid3.webp'; ?>" alt="">
                        <img src=" <?php echo base_url(). '/assets/img/slid1.webp'; ?>" alt="">
                        <img src=" <?php echo base_url(). '/assets/img/poster.jpg'; ?>" alt="">
                            </div>
                        </div>
                     </div> 

                </div>

               </div>
                 </marquee> 
            </div>
        </div>
        </div>







