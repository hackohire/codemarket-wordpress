<?php

/**
 * Marketify child theme.
 */
function marketify_child_styles()
{
    wp_enqueue_style('marketify-child', get_stylesheet_uri());
}

add_action('wp_enqueue_scripts', 'marketify_child_styles', 999);

function help_request_list_callback($atts)
{
    global $wpdb;

    $atts = shortcode_atts(
	    [
	'title'	 => 'Recent Requests',
	'limit'	 => '10',
	    ], $atts, 'help_request_list');

    ob_start();

    $args = [
		'post_type'	 => 'download',
		'post_status'	 => 'publish',
		'tax_query'	 => array(
			array(
			'taxonomy'	 => 'types',
			'field'		 => 'slug',
			'terms'		 => ['help-request',],
			),
		),
	];
	
	$arrDownload = new WP_Query($args);
	
	wp_reset_query();

    if ($arrDownload->found_posts > 0)
    {
		$site_url = site_url();
		?>
		<h3 class="widget-title widget-title--home section-title"><span><?php echo $atts['title']; ?></span></h3>

		<table class="table fes-table table-condensed table-striped" id="help_request_list">

			<thead>
			<tr>
				<th>Title</th>
				<th>Price</th>
				<th>Image</th>
				<th>User</th>
				<th>Date</th>
			</tr>
			</thead>

			<tbody>								
			<?php
			foreach ($arrDownload->posts as $data)
			{
				$product_link = get_the_permalink($data->ID);
				$img_url	 = get_the_post_thumbnail_url($data->ID);
				$user_name = get_userdata($data->post_author);
				$date = date_create($data->post_date);				
				?>
				<td class="fes-order-list-td widget">
					<a href="<?php echo $product_link ?>" title="View" class="view-order-fes"><?php echo $data->post_title; ?></a>
				</td>				
				
				<td class="fes-order-list-td"><?php echo edd_price($data->ID); ?></td>
				
				<?php
				if($img_url != '')
				{
					echo '<td><img width="50" src="'. $img_url .'" ></td>';
				}
				else
				{
					echo '<td></td>';
				}
				?>
				
				<td><?php echo $user_name->data->display_name; ?></td>
				
				<td><?php echo date_format($date, "Y-m-d"); ?></td>
				
				</tr>
				<?php
			}
			?>							
			</tbody>
		</table>
		<?php
    }

    return ob_get_clean();
}

add_shortcode('help_request_list', 'help_request_list_callback');

function delete_product()
{
    $message = [];

    if ($_POST['delete_id'] != '')
    {
	$my_product = [
	    'ID'		 => $_POST['delete_id'],
	    'post_status'	 => 'trash',
	];

	wp_update_post($my_product);

	$message['code']	 = 301;
	$message['messgae']	 = 'Request has been deleted';
    }
    else
    {
	$message['code']	 = 404;
	$message['messgae']	 = 'Somthing wrong';
    }

    echo json_encode($message);
    wp_die();
}

add_action('wp_ajax_nopriv_delete_product', 'delete_product');
add_action('wp_ajax_delete_product', 'delete_product');


add_action('init', 'add_types_in_download_post_type');

function add_types_in_download_post_type()
{
    $labels = array(
	'name'			 => _x('Types', 'taxonomy general name'),
	'singular_name'		 => _x('Type', 'taxonomy singular name'),
	'search_items'		 => __('Search Types'),
	'all_items'		 => __('All Types'),
	'parent_item'		 => __('Parent Type'),
	'parent_item_colon'	 => __('Parent Type:'),
	'edit_item'		 => __('Edit Type'),
	'update_item'		 => __('Update Type'),
	'add_new_item'		 => __('Add New Type'),
	'new_item_name'		 => __('New Type Name'),
	'menu_name'		 => __('Types'),
    );

    register_taxonomy('types', array('download'), array(
	'hierarchical'		 => true,
	'labels'		 => $labels,
	'show_ui'		 => true,
	'show_admin_column'	 => true,
	'query_var'		 => true,
	'rewrite'		 => array('slug' => 'type'),
    ));
}




add_action( 'init', 'meetups_post_type' );
function meetups_post_type() {
    $labels = array(
        'name' => 'Meetups',
        'singular_name' => 'Meetup',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Meetup',
        'edit_item' => 'Edit Meetup',
        'new_item' => 'New Meetup',
        'view_item' => 'View Meetup',
        'search_items' => 'Search Meetups',
        'not_found' =>  'No Meetups found',
        'not_found_in_trash' => 'No Meetups in the trash',
        'parent_item_colon' => '',
    );
 
    register_post_type( 'meetups', array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'exclude_from_search' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 10,
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
  'register_meta_box_cb' => 'meetups_meta_boxes', // Callback function for custom metaboxes
    ) );
}



function meetups_meta_boxes() {
    add_meta_box( 'meetups_form', 'Meetups Details', 'meetups_form', 'meetups', 'normal', 'high' );
}
function meetups_form() {
    $post_id = get_the_ID();
    $testimonial_data = get_post_meta( $post_id, '_meetup', true );
    $client_name = ( empty( $testimonial_data['client_name'] ) ) ? '' : $testimonial_data['client_name'];
    $source = ( empty( $testimonial_data['source'] ) ) ? '' : $testimonial_data['source'];
    $link = ( empty( $testimonial_data['link'] ) ) ? '' : $testimonial_data['link'];
 
    wp_nonce_field( 'meetups', 'meetups' );
    ?>
    <p>
        <label>City Name</label><br />
        <input type="text" value="<?php echo $client_name; ?>" name="meetup[client_name]" size="40" />
    </p>
    <p>
        <label>Email</label><br />
        <input type="text" value="<?php echo $source; ?>" name="meetup[source]" size="40" />
    </p>
    <p>
        <label>Link (optional)</label><br />
        <input type="text" value="<?php echo $link; ?>" name="meetup[link]" size="40" />
    </p>
    <?php
}
add_action( 'save_post', 'meetups_save_post' );
function meetups_save_post( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;
 
    if ( ! empty( $_POST['meetups'] ) && ! wp_verify_nonce( $_POST['meetups'], 'meetups' ) )
        return;
 
    if ( ! empty( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) )
            return;
    } else {
        if ( ! current_user_can( 'edit_post', $post_id ) )
            return;
    }
 
    if ( ! wp_is_post_revision( $post_id ) && 'meetups' == get_post_type( $post_id ) ) {
        remove_action( 'save_post', 'meetups_save_post' );
 
        wp_update_post( array(
            'ID' => $post_id,
          //'post_title' => 'Meetup - ' . $post_id
        ) );
 
        add_action( 'save_post', 'meetups_save_post' );
    }
 
    if ( ! empty( $_POST['meetup'] ) ) {
        $testimonial_data['client_name'] = ( empty( $_POST['meetup']['client_name'] ) ) ? '' : sanitize_text_field( $_POST['meetup']['client_name'] );
        $testimonial_data['source'] = ( empty( $_POST['meetup']['source'] ) ) ? '' : sanitize_text_field( $_POST['meetup']['source'] );
        $testimonial_data['link'] = ( empty( $_POST['meetup']['link'] ) ) ? '' : esc_url( $_POST['meetup']['link'] );
 
        update_post_meta( $post_id, '_meetup', $testimonial_data );
    } else {
        delete_post_meta( $post_id, '_meetup' );
    }
}