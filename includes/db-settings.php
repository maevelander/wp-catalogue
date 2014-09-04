<?php

//Create cat table
function wpc_cat_table(){
    //Get the table name with the WP database prefix
    global $wpdb;
    $wpc_cat_table = $wpdb->prefix . "wpc_categories";
    $wpc_cat_table_version = "1.0";
    $installed_ver = get_option( "wpc_cat_table_version" );
     //Check if the table already exists and if the table is up to date, if not create it
    if($wpdb->get_var("SHOW TABLES LIKE '$wpc_cat_table'") != $wpc_cat_table
            ||  $installed_ver != $wpc_cat_table_version ) {
        $sql = "CREATE TABLE " . $wpc_cat_table . " (
              `id` INT( 9 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			   `list_order` INT( 9 ) NOT NULL,
			  `cat_name` VARCHAR( 255 ) NOT NULL,
			  `cat_slug` VARCHAR( 255 ) NOT NULL,
               UNIQUE KEY id (id)
            );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        update_option( "wpc_cat_table_version", $wpc_cat_table_version );
}
    //Add database table versions to options
    add_option("wpc_cat_table_version", $wpc_cat_table_version);
}

function wpc_product_table(){
    //Get the table name with the WP database prefix
    global $wpdb;
    $wpc_product_table = $wpdb->prefix . "wpc_products";
    $wpc_product_table_version = "1.0";
    $installed_ver = get_option( "wpc_product_table_version" );
     //Check if the table already exists and if the table is up to date, if not create it
    if($wpdb->get_var("SHOW TABLES LIKE '$wpc_cat_table'") != $wpc_product_table
            ||  $installed_ver != $wpc_product_table_version ) {
        $sql = "CREATE TABLE " . $wpc_product_table . " (
              `id` INT( 9 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			  `list_order` INT( 9 ) NOT NULL,
              `product_title` TEXT NOT NULL,
			  `product_desc` TEXT NOT NULL,
			  `product_summary` TEXT NOT NULL,
			  `product_featured` TEXT NOT NULL,
			  `product_cats` TEXT NOT NULL,
			  `product_img1` TEXT NOT NULL,
			  `product_img2` TEXT NOT NULL,
			  `product_img3` TEXT NOT NULL,
			  `product_price` TEXT NOT NULL,
			  `product_date` TEXT NOT NULL,
              UNIQUE KEY id (id)
            );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        update_option( "wpc_product_table_version", $wpc_product_table_version );
}
    //Add database table versions to options
    add_option("wpc_product_table_version", $wpc_product_table_version);
}