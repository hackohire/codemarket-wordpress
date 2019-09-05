<?php
/**
 *
 * @package Marketify
 */
if (!is_active_sidebar('sidebar-download-single'))
{
    return;
}

wp_enqueue_style('select2_style');
wp_enqueue_script('select2_script');
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

	echo '<div class="cm_offer_help border p-3">';
	if (!in_array($is_exist, ['Pending', 'Active', 'Reject']))
	{
	    echo '<button type="button" class="offer_help_action" data-status="Pending" data-id="' . $post_id . '" data-user-id="' . $user_id . '">Offer Help</button>';
	}
	else
	{
	    echo '<h3>Your offer status is :: <span class="offer_help_status">' . $is_exist . '</span></h3>';
	}
	echo '</div>';
    }
	else if ($author_id == $user_id)
	{
		echo '<div class="cm_offer_help border p-3">';
		
		$sql = "SELECT *  FROM `wp_postmeta` WHERE `meta_key` LIKE 'offer_help%' AND post_id IN (" . $post_id . ")";
		$get_offer_help = $wpdb->get_results($sql, ARRAY_A);

		if (!empty($get_offer_help))
		{
			echo '<h3 class="mb-2">Assign Help Request</h3>';
			
			echo '<form class="assign_offer_help">';
			
			echo '<input type="hidden" name="post_id" value="'. $post_id .'">';
			
			echo '<div class="form-group cm_select_2">';			
			echo '<select name="assign_user_id" class="form-control assign_user_id">';
			echo '<option value="">Select User</option>';
			foreach ($get_offer_help as $offer_help)
			{
				$explode_key	 = explode('_', $offer_help['meta_key']);
				$user_id	 = $explode_key[2];
				$user_data	 = get_userdata($user_id);
				
				if($offer_help['meta_value'] == 'Approve')
				{
					$selected = ' selected ';
				}
				else
				{
					$selected = '';
				}
				
				echo '<option '. $selected .' value="'. $user_data->ID .'">' . $user_data->data->display_name . '</option>';
			}			
			echo '</select>';
			echo '<div class="cm_error text-danger d-none">Select User</div>';
			echo '</div>';
			
			echo '<div class="form-group m-0">';
			echo '<button type="button" class="btn_assign_offer_help">Click Me!</button>';
			echo '</div>';			
			
			echo '</form>';			
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
