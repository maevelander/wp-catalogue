<?php get_header(); ?>

<!--Content-->
<?php echo get_option('inn_temp_head'); ?>	
		
<?php 
	$catalogue_page_url	=	get_option('catalogue_page_url');
	$terms	=	get_terms('wpccategories');
	
	global $post;
	
	$terms1 = get_the_terms($post->id, 'wpccategories');
                
	if($terms1){
		foreach( $terms1 as $term1 ){
			$slug	= $term1->slug;
			$tname	=	$term1->name;
			$cat_url	=	get_bloginfo('siteurl').'/?wpccategories=/'.$slug;
		};
	}

	if(is_single()){
		$pname	=	'&gt;&gt;'.get_the_title();	
	}
                
	echo '<div class="wp-catalogue-breadcrumb"> <a href="'.$catalogue_page_url.'">All Products</a> &gt;&gt; <a href="'.$cat_url.'">'.$tname.'</a>  ' . $pname . '</div>';
?>
    <div id="wpc-catalogue-wrapper">
    <?php
		global $post;
		$terms1 = get_the_terms($post->id, 'wpccategories');
		
		if($terms1 !=null || $term1 !=null){
			foreach( $terms1 as $term1 ){
				$slug		= $term1->slug;
				$term_id	= $term1->term_id;
			};
		}
		global $wpdb;	
		
		$args = array(
			'orderby' => 'term_order',
			'order' => 'ASC',
			'hide_empty' => true,
		);
		
		$terms	=	get_terms('wpccategories',$args);
		$count	=	count($terms);
	echo '<div id="wpc-col-1">
			<ul class="wpc-categories">';
			if($count>0){
				echo '<li class="wpc-category"><a href="'. get_option('catalogue_page_url') .'">All Products</a></li>';
				
				foreach($terms as $term){
					if($term->slug==$slug){
						$class	=	'active-wpc-cat';
					}else{
						$class	=	'';
					}
					
					echo '<li  class="wpc-category ' . $class . '"><a href="'.get_term_link($term->slug, 'wpccategories').'">'. $term->name .'</a></li>'; 	
				}
			}else{
				echo '<li  class="wpc-category"><a href="#">No category</a></li>';	
			}
		echo '</ul>
		</div>';
	?>
    <!--/Left-menu-->
    	<!--col-2-->

        <div id="wpc-col-2">
        <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
            
                    $img1		=	get_post_meta($post->ID,'product_img1',true);
                    $img2		=	get_post_meta($post->ID,'product_img2',true);
                    $img3		=	get_post_meta($post->ID,'product_img3',true);
        ?>	 
                    <div id="wpc-product-gallery">
                    <?php 
                        if(get_option('image_height')){
                            $img_height	=	get_option('image_height');
                        }else{
                            $img_height	=	348;
                        }
                        if(get_option('image_width')){
                            $img_width	=	get_option('image_width'); 
                        }else{
                            $img_width	=	490;
                        }
                        $icroping	=	get_option('croping');
                    ?>
                    
                    	<div class="product-img-view" style="width:<?php echo $img_width; ?>px; height:<?php echo $img_height; ?>px;">
                            <img src="<?php echo $img1; ?>" alt="" id="img1" height="<?php echo $img_height; ?>" <?php if($icroping == 'image_scale_fit'){ echo 'width="'. $img_width .'"';} ?> />
                            <img src="<?php echo $img2; ?>" alt="" id="img2" height="<?php echo $img_height; ?>" <?php if($icroping == 'image_scale_fit'){ echo 'width="'. $img_width .'"';} ?> />
                            <img src="<?php echo $img3; ?>" alt="" id="img3" height="<?php echo $img_height; ?>" <?php if($icroping == 'image_scale_fit'){ echo 'width="'. $img_width .'"';} ?> />
                    	</div>
                        
                        <div class="wpc-product-img">
                        <?php if($img1): ?>
                        	<div class="new-prdct-img"><img src="<?php echo $img1; ?>" alt="" width="151" id="img1" /></div>
                        <?php endif; if($img2): ?>
                        	<div class="new-prdct-img"><img src="<?php echo $img2; ?>" alt="" width="151" id="img2"/></div>
                        <?php endif; if($img3):?>
                        	<div class="new-prdct-img"><img src="<?php echo $img3; ?>" alt="" width="151" id="img3"/></div>
                        <?php endif; ?>
                        </div>
                        <div class="clear"></div>
                    </div>
        
                    <?php $product_price = get_post_meta($post->ID, 'product_price', true); ?>
                    <h4>Product Details
                    <?php
                        if($product_price):
                    ?>
                            <span class="product-price">Price: <span><?php echo $product_price; ?></span></span>
                    <?php endif; ?>
                    </h4>
                    
                    <article class="post">
                        <div class="entry-content"> 
                            <?php
                            the_content();
                            
                            if(get_option('next_prev')==1){
                                echo '<p class="wpc-next-prev">';
                                previous_post_link('%link', 'Previous', TRUE, ' ', 'wpccategories');
                                next_post_link('%link', 'Next', TRUE, ' ', 'wpccategories');
                                echo '</p>';
                            }
                        ?>
                        </div>
                    </article>
        <?php
                endwhile;
            endif;
        ?>
        </div>
        <!--/col-2-->
        <div class="clear"></div>    
    </div>

<?php echo get_option('inn_temp_foot'); ?>
  <!--/Content-->
<?php get_footer();