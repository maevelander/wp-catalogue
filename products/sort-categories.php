<?php
/**
 * This file displays the Orderly Admin Page, rendering
 * the list of sortable post types and allowing simple
 * ordering by menu_order parameter.
 */
$post_type = trim($_REQUEST['post_type']);
if (empty($post_type)) $post_type = 'post';
$post_type_object = get_post_type_object($post_type);
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $message = "Custom order saved for <em>{$post_type_object->labels->name}</em>";
    $values = (array)$_POST['orderly_values'];
    if (!empty($values))
    {
        global $wpdb;
        for ($i = 0; $i < count($values); $i++)
        {
            $post_id = (int)$values[$i];
            $sql = $wpdb->prepare(
			"UPDATE `{$wpdb->post}` SET `menu_order` = %d WHERE ID = %d",
                $i,
                $post_id
            );
            $wpdb->query($sql);
        }
    }
}
$args = array(
			'orderby' => 'term_order',
			'order' => 'ASC',
			'hide_empty' => false,
);
$terms	=	get_terms('wpccategories', $args);
$count	=	count($terms);
?>
<div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php echo esc_html(__("Ordering " . esc_html($post_type_object->labels->name), WPC_ORDER)); ?></h2>
    <?php
    if (!empty($message)):
    ?>
    <div class="updated">
        <p>
            <strong><?php _e($message, WPC_ORDER); ?></strong>
        </p>
    </div>
    <?php
    endif;
    ?>
    <?php if ($count>0): ?>
    <form name="orderly-order-form" method="post" action="">
        <p>
            <?php _e("Drag and drop items to set the custom order.", WPC_ORDER); ?>
        </p>
        <p>
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e("Save Order", WPC_ORDER); ?>"/>
        </p>
        <ul class="orderly-items orderly-sortable">
        <?php
        $i = 1;
        foreach($terms as $term): ?>
            <li id="orderly-item-<?php echo $term->term_id; ?>" class="<?php echo ($i % 2 == 0 ? 'alternate ' : ''); ?>ui-state-default">
                <span class="orderly-index"><?php echo $i; ?>.</span>
                <?php echo $term->name; ?>
                <input type="hidden" value="<?php echo $term->term_id; ?>" name="orderly_values[]" id="orderly_values_<?php echo $i; ?>"/>
            </li>
        <?php
            $i++;
       endforeach;
        ?>
        </ul>
        <p>
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e("Save Order", WPC_ORDER); ?>"/>
        </p>
    </form>
    <?php else: ?>
    <p>
        <?php $label = strtolower($post_type_object->labels->name); ?>
        <?php _e("There doesn't seem to be any {$label} yet. Click below to add one.", WPC_ORDER); ?>
    </p>
    <p>
        <a href="<?php echo admin_url("post-new.php?post_type={$post_type}"); ?>" class="button-primary"><?php _e("Add {$post_type_object->labels->singular_name}", WPC_ORDER); ?></a>
    </p>
    <?php endif; ?>

</div>