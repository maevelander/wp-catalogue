<?php 
header("Content-type:text/css");
?>

<style type="text/css">
.wpc-img:hover {
	border: 5px solid <?php echo get_option('templateColorforProducts'); ?> !important;
}

.wpc-title {
	color: <?php echo get_option('templateColorforProducts'); ?> !important;
    }

.wpc-title a:hover {
	color: <?php echo get_option('templateColorforProducts'); ?> !important;
}

#wpc-col-1 ul li a:hover, #wpc-col-1 ul li.active-wpc-cat a {
	border-right:none;
	background:<?php echo get_option('templateColorforProducts'); ?> no-repeat left top !important;
}

</style>