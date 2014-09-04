<?php get_header(); ?>

<!--Content-->

<?php echo get_option('inn_temp_head'); ?>	 
	<?php echo do_shortcode('[wp-catalogue]'); ?>
<div class="clear"></div>
<?php echo get_option('inn_temp_foot'); ?>	

<!--/Content-->

<?php get_footer(); ?>
