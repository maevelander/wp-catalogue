<?php

function catalogue() {
    ob_start();
	
    global $post,$wpdb;
    $post_data = get_post($post->ID, ARRAY_A);
    
    if(get_queried_object()->taxonomy){
        $slug = get_queried_object()->taxonomy.'/'.get_queried_object()->slug;
    } else {
        $slug = $post_data['post_name'];
    }
    
    $crrurl = get_site_url('wpurl').'/'.$slug;
    if(get_query_var('paged')){
        $paged = get_query_var('paged');
    } elseif ( get_query_var('page') ) {
        $paged = get_query_var('page');
    } else {
        $paged = 1;	
    }

    $args = array(
                'orderby' => 'term_order',
                'order' => 'ASC',
                'hide_empty' => false,
            );
    
    $termsCatSort = get_terms('wpccategories', $args);
    $count = count($termsCatSort);
    $post_content = get_queried_object()->post_content;

    if(strpos($post_content,'[wp-catalogue]')!==false){
        $siteurl = get_site_url();
        global $post;
        $pid	= $post->ID;
        $guid	=	 $siteurl.'/?page_id='.$pid;

        if(get_option('catalogue_page_url')){
            update_option( 'catalogue_page_url', $guid );	 
        } else {
            add_option( 'catalogue_page_url', $guid );	
        }
    }
    
    $term_slug = get_queried_object()->slug;
    if(!$term_slug){
        $class = "active-wpc-cat";	
    }

    $catalogue_page_url = get_option('catalogue_page_url');
    $terms = get_terms('wpccategories');
    
    global $post;
    
    $terms1 = get_the_terms($post->id, 'wpccategories');
    if($terms1){
        foreach( $terms1 as $term1 ){
            $slug = $term1->slug;
            $tname = $term1->name;
            $cat_url = get_site_url().'/?wpccategories=/'.$slug;
        }
    }
    
    $pname = '';
    if(is_single()){
        $pname = '>> '.get_the_title();	
    }

    $page_slug = get_queried_object()->slug;
    $page_name = get_queried_object()->name;
    $page_id = get_queried_object()->term_id;
	
    $page_url = get_site_url().'/?wpccategories=/'.$page_slug;

    $return_string = '<div id="wpc-catalogue-wrapper">';
    echo  '<div class="wp-catalogue-breadcrumb"> <a href="'.$catalogue_page_url.'">All Products</a> &gt;&gt; <a href="'.$page_url.'">'.$page_name.'</a>  ' . $pname . '</div>';
    
    echo  '<div id="wpc-col-1">';
    echo  '<ul class="wpc-categories">';

        // generating sidebar
        if($count>0){
            echo  '<li class="wpc-category ' . $class . '"><a href="'. get_option('catalogue_page_url') .'">All Products</a></li>';	
            
            foreach($termsCatSort as $term){
                if($term_slug==$term->slug){
                    $class = 'active-wpc-cat';
                }else{
                    $class = '';
                }
                
                echo   '<li class="wpc-category '. $class .'"><a href="'.get_term_link($term->slug, 'wpccategories').'">'. $term->name .'</a></li>';       }
        }else{
            echo   '<li class="wpc-category"><a href="#">No category</a></li>';	
        }

    echo  '</ul>';
    echo ' </div>';

    // products area
    $per_page = get_option('pagination');
    if($per_page==0){
        $per_page = "-1";
    }

    // 
    $term_slug = get_queried_object()->slug;
    if($term_slug){
        $args = array(
                    'post_type' => 'wpcproduct',
                    'order' => 'ASC',
                    'orderby' => 'menu_order',
                    'posts_per_page' => $per_page,
                    'paged' => $paged,
                    'tax_query' => array(
                            array(
                                    'taxonomy' => 'wpccategories',
                                    'field' => 'slug',
                                    'terms' => get_queried_object()->slug
                                )
                        )                   
                );
    }else{
        $args = array(
                    'post_type' => 'wpcproduct',
                    'order' => 'ASC',
                    'orderby' => 'menu_order',
                    'posts_per_page' => $per_page,
                    'paged' => $paged,
                );
    }

    // products listing
    $products = new WP_Query($args); 
    if($products->have_posts()){
	
        $tcropping = get_option('tcroping');
        if(get_option('thumb_height')){
            $theight = get_option('thumb_height');
        }else{
            $theight = 142;
        }
        
        if(get_option('thumb_width')){
            $twidth = get_option('thumb_width');
        }else{
            $twidth = 205;
        }
        
        $i = 1;
        echo  '  <!--col-2-->
            <div id="wpc-col-2">
                <div id="wpc-products">';
                while($products->have_posts()): $products->the_post();
                    $title = get_the_title();
                    $permalink = get_permalink();
                    $img = get_post_meta(get_the_id(),'product_img1',true);
                    $price = get_post_meta(get_the_id(),'product_price',true);
					echo  '<!--wpc product-->';
                    echo  '<div class="wpc-product">';
							
                            $wpc_thumb_big_check = $wpdb->get_results("SELECT *
                                                FROM ".$wpdb->postmeta."
                                                WHERE post_id = ".$post->ID."
                                                And meta_key in ('product_img1_thumb','product_img2_thumb','product_img3_thumb','product_img1_big','product_img2_thumb','product_img3_thumb')");
										
                        if(!$wpc_thumb_big_check) {
                            $upload_dir = wp_upload_dir();
                            
                            $wpc_image_width = get_option('image_width');
                            $wpc_image_height = get_option('image_height');
                            $wpc_thumb_width = get_option('thumb_width');
                            $wpc_thumb_height = get_option('thumb_height');

                            $wpc_product_images_1 = get_post_meta($post->ID, 'product_img1', true);
                            $wpc_product_images_2 = get_post_meta($post->ID, 'product_img2', true);
                            $wpc_product_images_3 = get_post_meta($post->ID, 'product_img3', true);

                            /// For Big 1
                            $big_resize_img_1 = wp_get_image_editor($wpc_product_images_1);
                            if (!is_wp_error($big_resize_img_1)) {
                                $product_big_img_1 = $wpc_product_images_1;
                                $product_img_explode_1 = explode('/', $product_big_img_1);
                                $product_img_name_1 = end($product_img_explode_1);
                                $product_img_name_explode_1 = explode('.', $product_img_name_1);

                                $product_img_name_1 = $product_img_name_explode_1[0];
                                $product_img_ext_1 = $product_img_name_explode_1[1];

                                $big_crop_1 = array('center', 'center');
                                $big_resize_img_1->resize($wpc_image_width, $wpc_image_height, $big_crop_1);
                                $big_filename_1 = $big_resize_img_1->generate_filename('big-' . $wpc_image_width . 'x' . $wpc_image_height, $upload_dir['path'], NULL);
                                $big_resize_img_1->save($big_filename_1);

                                $big_img_name_1 = $upload_dir['url'] . '/' . $product_img_name_1 . '-big-' . $wpc_image_width . 'x' . $wpc_image_height . '.' . $product_img_ext_1;
                            }
                            update_post_meta($post->ID, 'product_img1_big', $big_img_name_1);
                            
                            /// For Big 2
                            $big_resize_img_2 = wp_get_image_editor($wpc_product_images_2);
                            if (!is_wp_error($big_resize_img_2)) {
                                $product_big_img_2 = $wpc_product_images_2;
                                $product_img_explode_2 = explode('/', $product_big_img_2);
                                $product_img_name_2 = end($product_img_explode_2);
                                $product_img_name_explode_2 = explode('.', $product_img_name_2);

                                $product_img_name_2 = $product_img_name_explode_2[0];
                                $product_img_ext_2 = $product_img_name_explode_2[1];

                                $big_crop_2 = array('center', 'center');
                                $big_resize_img_2->resize($wpc_image_width, $wpc_image_height, $big_crop_2);
                                $big_filename_2 = $big_resize_img_2->generate_filename('big-' . $wpc_image_width . 'x' . $wpc_image_height, $upload_dir['path'], NULL);
                                $big_resize_img_2->save($big_filename_2);

                                $big_img_name_2 = $upload_dir['url'] . '/' . $product_img_name_2 . '-big-' . $wpc_image_width . 'x' . $wpc_image_height . '.' . $product_img_ext_2;
                            }
                            update_post_meta($post->ID, 'product_img2_big', $big_img_name_2);
									
                            /// For Big 3
                            $big_resize_img_3 = wp_get_image_editor($wpc_product_images_3);
                            if (!is_wp_error($big_resize_img_3)) {
                                $product_big_img_3 = $wpc_product_images_3;
                                $product_img_explode_3 = explode('/', $product_big_img_3);
                                $product_img_name_3 = end($product_img_explode_3);
                                $product_img_name_explode_3 = explode('.', $product_img_name_3);

                                $product_img_name_3 = $product_img_name_explode_3[0];
                                $product_img_ext_3 = $product_img_name_explode_3[1];

                                $big_crop_3 = array('center', 'center');
                                $big_resize_img_3->resize($wpc_image_width, $wpc_image_height, $big_crop_3);
                                $big_filename_3 = $big_resize_img_3->generate_filename('big-' . $wpc_image_width . 'x' . $wpc_image_height, $upload_dir['path'], NULL);
                                $big_resize_img_3->save($big_filename_3);

                                $big_img_name_3 = $upload_dir['url'] . '/' . $product_img_name_3 . '-big-' . $wpc_image_width . 'x' . $wpc_image_height . '.' . $product_img_ext_3;
                            }
                            update_post_meta($post->ID, 'product_img3_big', $big_img_name_3);
								
                            // For Thumb 1
                            $wpc_product_images_thumb_1 = get_post_meta($post->ID, 'product_img1', true);
                            $wpc_product_images_thumb_2 = get_post_meta($post->ID, 'product_img2', true);
                            $wpc_product_images_thumb_3 = get_post_meta($post->ID, 'product_img3', true);

                            $thumb_resize_img_1 = wp_get_image_editor($wpc_product_images_thumb_1);
                            if (!is_wp_error($thumb_resize_img_1)) {
				$product_thumb_img_explode_1 = explode('/', $wpc_product_images_thumb_1);
                                $product_thumb_img_name_1 = end($product_thumb_img_explode_1);
                                $product_thumb_img_name_explode_1 = explode('.', $product_thumb_img_name_1);
							
                                $product_thumb_img_name_1 = $product_thumb_img_name_explode_1[0];
                                $product_thumb_img_ext_1 = $product_thumb_img_name_explode_1[1];

                                $thumb_crop_1 = array('center', 'center');
                                $thumb_resize_img_1->resize($wpc_thumb_width, $wpc_thumb_height, $thumb_crop_1);

                                $thumb_filename_1 = $thumb_resize_img_1->generate_filename('thumb-' . $wpc_thumb_width . 'x' . $wpc_thumb_height, $upload_dir['path'], NULL);
                                $thumb_resize_img_1->save($thumb_filename_1);

                                $thumb_img_name_1  = $upload_dir['url'] . '/' . $product_thumb_img_name_1 . '-thumb-' . $wpc_thumb_width . 'x' . $wpc_thumb_height . '.' . $product_thumb_img_ext_1 ;
                               
                            }
                            update_post_meta($post->ID, 'product_img1_thumb', $thumb_img_name_1);
							
                            // For Thumbs 2
                            $thumb_resize_img_2 = wp_get_image_editor($wpc_product_images_thumb_2);
                            if (!is_wp_error($thumb_resize_img_2)) {
                                $product_thumb_img_explode_2 = explode('/', $wpc_product_images_thumb_2);
                                $product_thumb_img_name_2 = end($product_thumb_img_explode_2);
                                $product_thumb_img_name_explode_2 = explode('.', $product_thumb_img_name_2);

                                $product_thumb_img_name_2 = $product_thumb_img_name_explode_2[0];
                                $product_thumb_img_ext_2 = $product_thumb_img_name_explode_2[1];

                                $thumb_crop_2 = array('center', 'center');
                                $thumb_resize_img_2->resize($wpc_thumb_width, $wpc_thumb_height, $thumb_crop_2);

                                $thumb_filename_2 = $thumb_resize_img_2->generate_filename('thumb-' . $wpc_thumb_width . 'x' . $wpc_thumb_height, $upload_dir['path'], NULL);
                                $thumb_resize_img_2->save($thumb_filename_2);

                                $thumb_img_name_2  = $upload_dir['url'] . '/' . $product_thumb_img_name_2 . '-thumb-' . $wpc_thumb_width . 'x' . $wpc_thumb_height . '.' . $product_thumb_img_ext_2;
                            }
                            update_post_meta($post->ID, 'product_img2_thumb', $thumb_img_name_2);
							
                            // For Thumbs 3
                            $thumb_resize_img_3 = wp_get_image_editor($wpc_product_images_thumb_3);
                            if (!is_wp_error($thumb_resize_img_3)) {
                                $product_thumb_img_explode_3 = explode('/', $wpc_product_images_thumb_3);
                                $product_thumb_img_name_3 = end($product_thumb_img_explode_3);
                                $product_thumb_img_name_explode_3 = explode('.', $product_thumb_img_name_3);

                                $product_thumb_img_name_3 = $product_thumb_img_name_explode_3[0];
                                $product_thumb_img_ext_3 = $product_thumb_img_name_explode_3[1];

                                $thumb_crop_3 = array('center', 'center');
                                $thumb_resize_img_3->resize($wpc_thumb_width, $wpc_thumb_height, $thumb_crop_3);

                                $thumb_filename_3 = $thumb_resize_img_3->generate_filename('thumb-' . $wpc_thumb_width . 'x' . $wpc_thumb_height, $upload_dir['path'], NULL);
                                $thumb_resize_img_3->save($thumb_filename_3);

                                $thumb_img_name_3  = $upload_dir['url'] . '/' . $product_thumb_img_name_3 . '-thumb-' . $wpc_thumb_width . 'x' . $wpc_thumb_height . '.' . $product_thumb_img_ext_3;
                            }
                            update_post_meta($post->ID, 'product_img3_thumb', $thumb_img_name_3);
							
			}
                        
                        $wpc_thumb_width = get_option('thumb_width');
                        $wpc_thumb_height = get_option('thumb_height');
			$image = get_post_meta($post->ID,'product_img1_thumb',true);
					
                        echo  '<div class="wpc-img" style="width:' . $wpc_thumb_width . 'px; height:' . $wpc_thumb_height . 'px; overflow:hidden"><a href="'. $permalink .'" class="wpc-product-link"><img src="'. $image .'" alt="" /></a></div>';

                        echo  '<p class="wpc-title"><a href="'.$permalink.'">' . $title . '</a></p>';
                        echo  '</div>';

                        echo  '<!--/wpc-product-->';

                        if($i == get_option('grid_rows')){
                            echo  '<br clear="all" />';
                            $i = 0; // reset counter
                        }
					

                        $i++;
                    endwhile;
                'wp_reset_postdata';

            echo  '</div>';

            $wpc_last_page = '';
            if(get_option('pagination')!=0){
                $wpc_last_page = ceil($products->found_posts/get_option('pagination'));	
            }

            $wpc_second_last = $wpc_last_page - 1;
            if (get_query_var('page')) {
                $wpc_paged = get_query_var('page');
            } else {
                $wpc_paged = 1;
            }
            
            $wpc_path = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $wpc_permalink = get_option('permalink_structure');
            $wpc_page_id = get_queried_object_id();
            $wpc_term_slug = get_queried_object()->slug;
                    
            $wpc_adjacents = 2;
            $wpc_previous_page = $wpc_paged - 1;
            $wpc_next_page = $wpc_paged + 1;
					
            if($wpc_last_page > 1){
            echo '<div class="wpc-paginations">';
                    if ($wpc_paged > 1) {
                        if(!empty($wpc_permalink)) {
                            echo "<a href='?page=$wpc_previous_page' class='wpc_page_link_previous'>previous</a>";
                        } elseif(strpos($wpc_path, "wpccategories")) {
                            echo "<a href='?wpccategories=$wpc_term_slug&page=$wpc_previous_page' class='wpc_page_link_previous'>previous</a>";
                        } else {
                            echo "<a href='?page_id=$wpc_page_id&page=$wpc_previous_page' class='wpc_page_link_previous'>previous</a>";
                        }

                    }
						
                    if ($wpc_last_page < 7 + ($wpc_adjacents * 2)) {	//not enough pages to bother breaking it up
                        for ($wpc_prod_counter = 1; $wpc_prod_counter <= $wpc_last_page; $wpc_prod_counter++) {
                            if ($wpc_prod_counter == $wpc_paged) {
                                echo "<span class='wpc_page_link_disabled'>$wpc_prod_counter</span>";
                            } else {
                                if(!empty($wpc_permalink)) {
                                    echo "<a href='?page=$wpc_prod_counter'>$wpc_prod_counter</a>";
                                } elseif(strpos($wpc_path, "wpccategories")) {
                                    echo "<a href='?wpccategories=$wpc_term_slug&page=$wpc_prod_counter'>$wpc_prod_counter</a>";
                                } else {
                                    echo "<a href='?page_id=$wpc_page_id&page=$wpc_prod_counter'>$wpc_prod_counter</a>";
                                }
                            }
                        }
                    } elseif($wpc_last_page > 5 + ($wpc_adjacents * 2)) {	//enough pages to hide some
                        //close to beginning; only hide later pages
                        if($wpc_paged < 1 + ($wpc_adjacents * 2)) {
                            for ($wpc_prod_counter = 1; $wpc_prod_counter < 3 + ($wpc_adjacents * 2); $wpc_prod_counter++) {
                                if ($wpc_prod_counter == $wpc_paged) {
                                    echo "<span class='wpc_page_link_disabled'>$wpc_prod_counter</span>";
                                } else {
                                    if(!empty($wpc_permalink)) {
                                        echo "<a href='?page=$wpc_prod_counter'>$wpc_prod_counter</a>";
                                    } elseif(strpos($wpc_path, "wpccategories")) {
                                        echo "<a href='?wpccategories=$wpc_term_slug&page=$wpc_prod_counter'>$wpc_prod_counter</a>";
                                    } else {
                                        echo "<a href='?page_id=$wpc_page_id&page=$wpc_prod_counter'>$wpc_prod_counter</a>";
                                    }
                                }
                            }
                            echo "<span class='wpc_page_last_dot'>...</span>";
                            if(!empty($wpc_permalink)) {
                                echo "<a href='?page=$wpc_second_last'>$wpc_second_last</a>";
                                echo "<a href='?page=$wpc_last_page'>$wpc_last_page</a>";
                            } elseif(strpos($wpc_path, "wpccategories")) {
                                echo "<a href='?wpccategories=$wpc_term_slug&page=$wpc_second_last'>$wpc_second_last</a>";
                                echo "<a href='?wpccategories=$wpc_term_slug&page=$wpc_last_page'>$wpc_last_page</a>";
                            } else {
                                echo "<a href='?page_id=$wpc_page_id&page=$wpc_second_last'>$wpc_second_last</a>";
                                echo "<a href='?page_id=$wpc_page_id&page=$wpc_last_page'>$wpc_last_page</a>";
                            }
                        } elseif($wpc_last_page - ($wpc_adjacents * 2) > $wpc_paged && $wpc_paged > ($wpc_adjacents * 2)) {
                            //in middle; hide some front and some back
                            if(!empty($wpc_permalink)) {
                                echo "<a href='?page=1'>1</a>";
                                echo "<a href='?page=2'>2</a>";
                            } elseif(strpos($wpc_path, "wpccategories")) {
                                echo "<a href='?wpccategories=$wpc_term_slug&page=1'>1</a>";
                                echo "<a href='?wpccategories=$wpc_term_slug&page=2'>2</a>";
                            } else {
                                echo "<a href='?page_id=$wpc_page_id&page=1'>1</a>";
                                echo "<a href='?page_id=$wpc_page_id&page=2'>2</a>";
                            }
                            echo "<span class='wpc_page_last_dot'>...</span>";
                            for ($wpc_prod_counter = $wpc_paged - $wpc_adjacents; $wpc_prod_counter <= $wpc_paged + $wpc_adjacents; $wpc_prod_counter++) {
                                if ($wpc_prod_counter == $wpc_paged) {
                                    echo "<span class='wpc_page_link_disabled'>$wpc_prod_counter</span>";
                                } else {
                                    if(!empty($wpc_permalink)) {
                                        echo "<a href='?page=$wpc_prod_counter'>$wpc_prod_counter</a>";
                                    } elseif(strpos($wpc_path, "wpccategories")) {
                                        echo "<a href='?wpccategories=$wpc_term_slug&page=$wpc_prod_counter'>$wpc_prod_counter</a>";
                                    } else {
                                        echo "<a href='?page_id=$wpc_page_id&page=$wpc_prod_counter'>$wpc_prod_counter</a>";
                                    }
                                }
                            }
                            echo "<span class='wpc_page_last_dot'>...</span>";
                            if(!empty($wpc_permalink)) {
                                echo "<a href='?page=$wpc_second_last'>$wpc_second_last</a>";
                                echo "<a href='?page=$wpc_last_page'>$wpc_last_page</a>";
                            } elseif(strpos($wpc_path, "wpccategories")) {
                                echo "<a href='?wpccategories=$wpc_term_slug&page=$wpc_second_last'>$wpc_second_last</a>";
                                echo "<a href='?wpccategories=$wpc_term_slug&page=$wpc_last_page'>$wpc_last_page</a>";
                            } else {
                                echo "<a href='?page_id=$wpc_page_id&page=$wpc_second_last'>$wpc_second_last</a>";
                                echo "<a href='?page_id=$wpc_page_id&page=$wpc_last_page'>$wpc_last_page</a>";
                            }
                        } else {
                            //close to end; only hide early pages
                            if(!empty($wpc_permalink)) {
                                echo "<a href='?page=1'>1</a>";
                                echo "<a href='?page=2'>2</a>";
                            } elseif(strpos($wpc_path, "wpccategories")) {
                                echo "<a href='?wpccategories=$wpc_term_slug&page=1'>1</a>";
                                echo "<a href='?wpccategories=$wpc_term_slug&page=2'>2</a>";
                            } else {
                                echo "<a href='?page_id=$wpc_page_id&page=1'>1</a>";
                                echo "<a href='?page_id=$wpc_page_id&page=2'>2</a>";
                            }
                            echo "<span class='wpc_page_last_dot'>...</span>";
                            for ($wpc_prod_counter = $wpc_last_page - (2 + ($wpc_adjacents * 2)); $wpc_prod_counter <= $wpc_last_page; $wpc_prod_counter++) {
                                if ($wpc_prod_counter == $wpc_paged) {
                                    echo "<span class='wpc_page_link_disabled'>$wpc_prod_counter</span>";
                                } else {
                                    if(!empty($wpc_permalink)) {
                                        echo "<a href='?page=$wpc_prod_counter'>$wpc_prod_counter</a>";
                                    } elseif(strpos($wpc_path, "wpccategories")) {
                                        echo "<a href='?wpccategories=$wpc_term_slug&page=$wpc_prod_counter'>$wpc_prod_counter</a>";
                                    } else {
                                        echo "<a href='?page_id=$wpc_page_id&page=$wpc_prod_counter'>$wpc_prod_counter</a>";
                                    }
                                }
                            }
                        }
                    }
						
                    if ($wpc_paged < $wpc_prod_counter - 1) {
                        if(!empty($wpc_permalink)) {
                            echo "<a href='?page=$wpc_next_page' class='wpc_page_link_next'>next</a>";
                        } elseif(strpos($wpc_path, "wpccategories")) {
                            echo "<a href='?wpccategories=$wpc_term_slug&page=$wpc_next_page' class='wpc_page_link_next'>next</a>";
                        } else {
                            echo "<a href='?page_id=$wpc_page_id&page=$wpc_next_page' class='wpc_page_link_next'>next</a>";
                        }
                    }
            echo '</div>';
            }
    } else {
        echo 'No Products';
    }
	
    echo '<div class="clear"></div></div>';

    //return $return_string;
    return ob_get_clean();
}
add_shortcode('wp-catalogue','catalogue');