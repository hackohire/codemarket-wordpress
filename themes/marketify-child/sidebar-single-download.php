<?php
/**
 *
 * @package Marketify
 */
if (!is_active_sidebar('sidebar-download-single'))
{
    return;
}

wp_enqueue_script('custom_script');

$post_id = get_the_ID();
$author_id = get_post_field( 'post_author', $post_id );
$user_id = get_current_user_id();
?>

<div class="widget-area widget-area--single-download col-xs-12 col-md-4" role="complementary">

    <?php
    if ($user_id > 0 && $author_id != $user_id)
    {
	$key		 = 'offer_help_' . $user_id;
	$is_exist	 = get_post_meta($post_id, $key, true);

	echo '<div class="cm_offer_help">';
	if (!in_array($is_exist, ['Pending', 'Active', 'Reject']))
	{
	    echo '<a href="JavaScript:Void(0);" class="cm_button offer_help_action" data-status="Pending" data-id="' . $post_id . '" data-user-id="' . $user_id . '">Offer Help</a>';
	}
	else
	{
	    echo '<h3>Your offer status is :: <span class="offer_help_status">' . $is_exist . '</span></h3>';
	}
	echo '</div>';
    }
    ?>

    <?php dynamic_sidebar('sidebar-download-single'); ?>
	
	<?php
    echo '<div class="cm_share">';
	$share_data = cm_share_product($post_id);
	echo $share_data;
	echo '</div>';
    ?>

</div><!-- #secondary -->
