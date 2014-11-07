<?php

function catalogue() {
	
	global $post;
	$post_data = get_post($post->ID, ARRAY_A);
	if(get_queried_object()->taxonomy){
		$slug	=	get_queried_object()->taxonomy.'/'.get_queried_object()->slug;
	}else{
		$slug = $post_data['post_name'];
	}
	$crrurl	=	get_bloginfo('wpurl').'/'.$slug;
	if(get_query_var('paged')){
		$paged	=	get_query_var('paged');
	}
	elseif ( get_query_var('page') ) {

    	$paged = get_query_var('page');

		} 
	else{
		 $paged	=	1;	
	}
	 
	$args = array(
			'orderby' => 'term_order',
			'order' => 'ASC',
			'hide_empty' => false,
);
$termsCatSort	=	get_terms('wpccategories', $args);
	$count	=	count($termsCatSort);
	$post_content	=	get_queried_object()->post_content;
	
		if(strpos($post_content,'[wp-catalogue]')!==false){
		
		
		 $siteurl	=	get_bloginfo('siteurl');
		 global $post;
		 $pid	= $post->ID;
		 $guid	=	 $siteurl.'/?page_id='.$pid;
		 if(get_option('catalogue_page_url')){
			update_option( 'catalogue_page_url', $guid );	 
		}else{
			add_option( 'catalogue_page_url', $guid );	
		}
	}
	$term_slug	=	get_queried_object()->slug;
	if(!$term_slug){
		$class	=	"active-wpc-cat";	
	}
	
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
			$pname	=	'>> '.get_the_title();	
		}
		
                $page_slug = get_queried_object()->slug;
                $page_name = get_queried_object()->name;
                $page_id = get_queried_object()->term_id;
                
                $page_url	=	get_bloginfo('siteurl').'/?wpccategories=/'.$page_slug;
                
		$return_string = '<div id="wpc-catalogue-wrapper">';
		$return_string .= '<div class="wp-catalogue-breadcrumb"> <a href="'.$catalogue_page_url.'">All Products</a> &gt;&gt; <a href="'.$page_url.'">'.$page_name.'</a>  ' . $pname . '</div>';
		$return_string .= '<div id="wpc-col-1">';
        $return_string .= '<ul class="wpc-categories">';
		
		// generating sidebar
		if($count>0){
			$return_string .= '<li class="wpc-category ' . $class . '"><a href="'. get_option('catalogue_page_url') .'">All Products</a></li>';	
       		foreach($termsCatSort as $term){
				if($term_slug==$term->slug){
				$class	=	'active-wpc-cat';
			}else{
				$class	=	'';
			}
			$return_string .=  '<li class="wpc-category '. $class .'"><a href="'.get_term_link($term->slug, 'wpccategories').'">'. $term->name .'</a></li>'; 	
			}
		}else{
			$return_string .=  '<li class="wpc-category"><a href="#">No category</a></li>';	
		}
		
		$return_string .= '</ul>';
        $return_string .=' </div>';
		
		// products area
		$per_page	=	get_option('pagination');
		if($per_page==0){
			$per_page	=	"-1";
		}
		
		// 
		$term_slug	=	get_queried_object()->slug;
		if($term_slug){
		$args = array(
			'post_type'=> 'wpcproduct',
			'order'     => 'ASC',
			'orderby'   => 'menu_order',
			'posts_per_page'	=> $per_page,
			'paged'	=> $paged,
			'tax_query' => array(
				array(
					'taxonomy' => 'wpccategories',
					'field' => 'slug',
					'terms' => get_queried_object()->slug
				)
		));
		}else{
			$args = array(
			'post_type'=> 'wpcproduct',
			'order'     => 'ASC',
			'orderby'   => 'menu_order',
			'posts_per_page'	=> $per_page,
			'paged'	=> $paged,
			);
		}
		
		// products listing
		$products	=	new WP_Query($args); 
		if($products->have_posts()){
			$tcropping	=	get_option('tcroping');
			if(get_option('thumb_height')){
			$theight	=	get_option('thumb_height');
			}else{
				$theight	=	142;
			}
			if(get_option('thumb_width')){
				$twidth		=	get_option('thumb_width');
			}else{
				$twidth		=	205;
			}
			$i = 1;
			$return_string .= '  <!--col-2-->
						<div id="wpc-col-2">
						<div id="wpc-products">';
				while($products->have_posts()): $products->the_post();
				$title		=	get_the_title(); 
				$permalink	=	get_permalink(); 
				$img		=	get_post_meta(get_the_id(),'product_img1',true);
				$price		=	get_post_meta(get_the_id(),'product_price',true); 
				 $return_string .= '<!--wpc product-->';
				 $return_string .= '<div class="wpc-product">';
				 $return_string .= '<div class="wpc-img" style="width:' . $twidth . 'px; height:' . $theight . 'px; overflow:hidden"><a href="'. $permalink .'" class="wpc-product-link"><img src="'. $img .'" alt="" height="' . $theight . '" ';
				  if(!get_option('tcroping')){
					  $return_string .=  '" width="' .$img_width. '"'; }
				 $return_string .= '" /></a></div>';
				 $return_string .= '<p class="wpc-title"><a href="'.$permalink.'">' . $title . '</a></p>';
				 $return_string .= '</div>';
;
				 $return_string .= '<!--/wpc-product-->';
                                 
				if($i == get_option('grid_rows'))
			{
				$return_string .= '<br clear="all" />';
				$i = 0; // reset counter
			}
				$i++;
				endwhile; wp_reset_postdata;
				$return_string .= '</div>';
				if(get_option('pagination')!=0){
				$pages	=	ceil($products->found_posts/get_option('pagination'));
				}
		
			if($pages>1){
			$return_string .= '<div class="wpc-paginations">';
			 if(get_query_var('paged')){
			 $paged = get_query_var('paged');
			 } 
			 else if (get_query_var('page')){
			 $paged = get_query_var('paged');
			 }
			 else {
			 $paged = 1; }
			for($p=1; $p<=$pages; $p++){
				$cpage	=	'active-wpc-page';
				if (is_front_page()) {
					  if($paged==$p){
				  
						  $return_string .=  '<a href="?paged='. $p .'" class="pagination-number '. $cpage .'">'. $p .'</a>';
						  }
					  else{
						 $return_string .=   '<a href="?paged='. $p .'" class="pagination-number">'. $p .'</a>';	
						  }
					  } // end is_home condition
					   else{ // else of is_home
					  if($paged==$p){
				  
						  $return_string .= '<a href="?page_id='.$page_id.'&paged='. $p .'" class="pagination-number '. $cpage .'">'. $p .'</a>';
						  }
					  else{
						  $return_string .=    '<a href="?page_id='.$page_id.'&paged='. $p .'" class="pagination-number">'. $p .'</a>';	
						  } 
					  } // end is_home condition
		}
		 $return_string .= '</div>'; 
		}
		}else{
		echo 'No Products';
		}
		
		$return_string .= '</div><div class="clear"></div></div>';
		
		
		return $return_string;
	
}




add_shortcode('wp-catalogue','catalogue');