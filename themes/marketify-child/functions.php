<?php

/**
 * Marketify child theme.
 */
function marketify_child_styles()
{
    wp_enqueue_style('marketify-child', get_stylesheet_uri());
}

add_action('wp_enqueue_scripts', 'marketify_child_styles', 999);

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
	'post_type'		 => 'download',
	'post_status'		 => 'publish',
	'download_category'	 => '',
	'tax_query'		 => array(
	    array(
		'taxonomy'	 => 'types',
		'field'		 => 'slug',
		'terms'		 => ['help-request',],
	    ),
	),
    ];

    $arrDownload = new WP_Query($args);
//print_r($arrDownload);
    wp_reset_query();

    if ($arrDownload->found_posts > 0)
    {
	$site_url = site_url();
	?>
	<h3 class="widget-title widget-title--home section-title"><span><?php echo $atts['title']; ?></span></h3>

	<div class="table-responsive">	<table class="table fes-table table-condensed table-striped tablesorter {sortlist: [[2,0]]}" id="myTable  help_request_list">

		<thead>
		    <tr>
			<th>Title</th>
			<th>Price</th>
			<th>Image</th>
			<th>User</th>
			<th>Category</th>
			<th>Date</th>
		    </tr>
		</thead>

		<tbody>								
		    <?php
		    foreach ($arrDownload->posts as $data)
		    {
			//	print_r($arrDownload);
			$product_link	 = get_the_permalink($data->ID);
			$img_url	 = get_the_post_thumbnail_url($data->ID);
			$user_name	 = get_userdata($data->post_author);
			$date		 = date_create($data->post_date);

			$cateogry = get_the_term_list($data->ID, 'download_category', '<ul class="styles"><li>', ',</li><li>', '</li></ul>');
			?>
	    	<td class="fes-order-list-td widget">
	    	    <a href="<?php echo $product_link ?>" title="View" class="view-order-fes"><?php echo $data->post_title; ?></a>
	    	</td>				

	    	<td class="fes-order-list-td"><?php echo edd_price($data->ID); ?></td>

		    <?php
		    if ($img_url != '')
		    {
			echo '<td><img width="50" src="' . $img_url . '" ></td>';
		    }
		    else
		    {
			echo '<td></td>';
		    }
		    ?>

	    	<td><?php echo $user_name->data->display_name; ?></td>
	    	<td><?php echo $cateogry ?></td>

	    	<td><?php echo date_format($date, "Y-m-d"); ?></td>

	    	</tr>
		    <?php
		}
		?>							
		</tbody>
	    </table>
	</div>
	<?php
    }

    return ob_get_clean();
}

add_shortcode('help_request_list', 'help_request_list_callback');

function product_list_callback($atts)
{
    $atts = shortcode_atts(
	    [
	'title'		 => '',
	'limit'		 => '10',
	'types'		 => '',
	'pagination'	 => 'no',
	    ], $atts, 'product_list');

    ob_start();

    $args = [
	'post_type'	 => 'download',
	'post_status'	 => 'publish',
	'orderby'	 => 'ID',
	'order'		 => 'DESC',
    ];

    if ($atts['types'] != '')
    {
	$types = explode('|', $atts['types']);

	$args['tax_query'][] = [
	    'taxonomy'	 => 'types',
	    'field'		 => 'slug',
	    'terms'		 => $types,
	];
    }

    if ($atts['limit'] != '')
    {
	$args['posts_per_page'] = $atts['limit'];
    }

    $args['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $arrProduct = new WP_Query($args);

    wp_reset_query();



    if ($arrProduct->found_posts > 0)
    {
	$site_url = site_url();

	if ($atts['title'] != '')
	{
	    echo '<h3 class="widget-title widget-title--home section-title"><span>' . $atts['title'] . '</span></h3>';
	}
	?>

	<div class="table-responsive"><table class="table fes-table table-condensed table-striped  tablesorter {sortlist: [[2,0]]}" id="myTable help_request_list">

		<thead>
		    <tr>
			<th width="40%">Title</th>
			<th>Price</th>
			<th>Image</th>
			<th>User</th>
			<th>Category</th>
			<th>Date</th>
		    </tr>
		</thead>

		<tbody>								
		    <?php
		    foreach ($arrProduct->posts as $product)
		    {
			$product_link	 = get_the_permalink($product->ID);
			$img_url	 = get_the_post_thumbnail_url($product->ID);
			$user_name	 = get_userdata($product->post_author);
			$date		 = date_create($product->post_date);
			$cateogry	 = get_the_term_list($product->ID, 'download_category', '<ul class="styles"><li>', ',</li><li>', '</li></ul>');
			?>
	    	<td class="fes-order-list-td widget">
	    	    <a href="<?php echo $product_link ?>" title="View" class="view-order-fes"><?php echo $product->post_title; ?></a>
	    	</td>				

	    	<td class="fes-order-list-td"><?php echo edd_price($product->ID); ?></td>

		    <?php
		    if ($img_url != '')
		    {
			echo '<td><img width="50" src="' . $img_url . '" ></td>';
		    }
		    else
		    {
			echo '<td></td>';
		    }
		    ?>

	    	<td><?php echo $user_name->data->display_name; ?></td>
	    	<td><?php echo $cateogry ?></td>

	    	<td><?php echo date_format($date, "Y-m-d"); ?></td>

	    	</tr>
		    <?php
		}
		?>							
		</tbody>
	    </table>
	</div>
	<?php
	if ($atts['pagination'] == 'yes')
	{
	    if ($arrProduct->max_num_pages > 1)
	    {
		echo '<div class="product_pagination">';
		cm_pagination($arrProduct->max_num_pages, 2);
		echo '</div>';
	    }
	}
    }

    return ob_get_clean();
}

add_shortcode('product_list', 'product_list_callback');

function cm_pagination($pages = '', $range = 2)
{
    $showitems = ($range * 2) + 1;

    global $paged;

    if (empty($paged))
    {
	$paged = 1;
    }

    if ($pages > 1)
    {
	echo '<ul class="pagination">';

	echo "<li class=\"page-item\"><a class='page-link' href='" . get_pagenum_link($paged - 1) . "'>Previous</a></li>";

	for ($i = 1; $i <= $pages; $i++)
	{
	    if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems))
	    {
		if ($paged == $i)
		{
		    print '<li class="page-item active"><a class="page-link" href="' . get_pagenum_link($i) . '" >' . $i . '</a></li>';
		}
		else
		{
		    print '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($i) . '" >' . $i . '</a></li>';
		}
	    }
	}

	$prev_page = get_pagenum_link($paged + 1);
	if (($paged + 1) > $pages)
	{
	    $prev_page = get_pagenum_link($paged);
	}
	else
	{
	    $prev_page = get_pagenum_link($paged + 1);
	}

	echo '<li class="page-item"><a class="page-link" href="' . $prev_page . '">Next</a></li>';
	echo '</ul>';
    }
}

add_action('init', 'meetups_post_type');

function meetups_post_type()
{
    $labels = array(
	'name'			 => 'Meetups',
	'singular_name'		 => 'Meetup',
	'add_new'		 => 'Add New',
	'add_new_item'		 => 'Add New Meetup',
	'edit_item'		 => 'Edit Meetup',
	'new_item'		 => 'New Meetup',
	'view_item'		 => 'View Meetup',
	'search_items'		 => 'Search Meetups',
	'not_found'		 => 'No Meetups found',
	'not_found_in_trash'	 => 'No Meetups in the trash',
	'parent_item_colon'	 => '',
    );

    register_post_type('meetups', array(
	'labels'		 => $labels,
	'public'		 => true,
	'publicly_queryable'	 => true,
	'show_ui'		 => true,
	'exclude_from_search'	 => true,
	'query_var'		 => true,
	'rewrite'		 => true,
	'capability_type'	 => 'post',
	'has_archive'		 => true,
	'hierarchical'		 => false,
	'menu_position'		 => 10,
	'supports'		 => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields',),
	'register_meta_box_cb'	 => 'meetups_meta_boxes', // Callback function for custom metaboxes
    ));
}

function meetups_meta_boxes()
{
    add_meta_box('meetups_form', 'Meetups Details', 'meetups_form', 'meetups', 'normal', 'high');
}

function meetups_form()
{
    $post_id		 = get_the_ID();
    $testimonial_data	 = get_post_meta($post_id, '_meetup', true);
    $client_name		 = ( empty($testimonial_data['client_name']) ) ? '' : $testimonial_data['client_name'];
    $source			 = ( empty($testimonial_data['source']) ) ? '' : $testimonial_data['source'];
    $link			 = ( empty($testimonial_data['link']) ) ? '' : $testimonial_data['link'];

    wp_nonce_field('meetups', 'meetups');
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

add_action('save_post', 'meetups_save_post');

function meetups_save_post($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
	return;

    if (!empty($_POST['meetups']) && !wp_verify_nonce($_POST['meetups'], 'meetups'))
	return;

    if (!empty($_POST['post_type']) && 'page' == $_POST['post_type'])
    {
	if (!current_user_can('edit_page', $post_id))
	    return;
    } else
    {
	if (!current_user_can('edit_post', $post_id))
	    return;
    }

    if (!wp_is_post_revision($post_id) && 'meetups' == get_post_type($post_id))
    {
	remove_action('save_post', 'meetups_save_post');

	wp_update_post(array(
	    'ID' => $post_id,
		//'post_title' => 'Meetup - ' . $post_id
	));

	add_action('save_post', 'meetups_save_post');
    }

    if (!empty($_POST['meetup']))
    {
	$testimonial_data['client_name'] = ( empty($_POST['meetup']['client_name']) ) ? '' : sanitize_text_field($_POST['meetup']['client_name']);
	$testimonial_data['source']	 = ( empty($_POST['meetup']['source']) ) ? '' : sanitize_text_field($_POST['meetup']['source']);
	$testimonial_data['link']	 = ( empty($_POST['meetup']['link']) ) ? '' : esc_url($_POST['meetup']['link']);

	update_post_meta($post_id, '_meetup', $testimonial_data);
    }
    else
    {
	delete_post_meta($post_id, '_meetup');
    }
}

add_action('wp_enqueue_scripts', 'cm_enqueue_style_script');

function cm_enqueue_style_script()
{
    wp_register_style('select2_style', get_stylesheet_directory_uri() . '/assets/css/select2.min.css');

    wp_register_script('select2_script', get_stylesheet_directory_uri() . '/assets/js/select2.full.min.js', '', '', true);
    wp_register_script('custom_script', get_stylesheet_directory_uri() . '/assets/js/custom_script.js', '', '', true);
}

function check_download_category()
{
    $arrCategory = $_REQUEST['selectData'];

    $newCategory = [];

    foreach ($arrCategory as $key => $value)
    {
	$term = get_term($value);

	if (empty($term))
	{
	    $value = ucfirst($value);

	    $data = wp_insert_term(
		    $value, // the term 
		    'download_category');

	    $newCategory[$key] = $data['term_id'];
	}
	else
	{
	    $newCategory[$key] = $value;
	}
    }

    echo json_encode($newCategory);
    wp_die();
}

add_action('wp_ajax_nopriv_check_download_category', 'check_download_category');
add_action('wp_ajax_check_download_category', 'check_download_category');

function user_list_callback($atts)
{
    global $wpdb;

    $atts = shortcode_atts(
	    [
	'title'	 => 'List Of Users',
	'limit'	 => '10',
	    ], $atts, 'user_list');

    ob_start();

    $blogusers = get_users(array('fields' => array('display_name', 'ID')));
//print_r($blogusers);
    ?>
    <h3 class="widget-title widget-title--home section-title"><span><?php echo $atts['title']; ?></span></h3>
    <div class="container">
        <div class="table-responsive">	<table class="table fes-table table-condensed table-striped tablesorter {sortlist: [[2,0]]}" id="myTable user_list">

    	    <thead>
    		<tr>
    		    <th>User</th>
    		   <!-- <th>Category</th>-->
    		    <th>No. of Bug Fix</th>
    		</tr>
    	    </thead>

    	    <tbody>								
		    <?php
		    foreach ($blogusers as $user_id)
		    {

			$current_user_id = $user_id->ID;
			?>
			<tr>
			    <td><a href="profile?request=<?php echo $current_user_id; ?>"><?php echo esc_html($user_id->display_name) ?></a></td>
			    <!-- <td><?php //echo $cateogry           ?></td>-->
			    <td class="fes-order-list-td widget">
				<?php echo count_user_posts($current_user_id, 'download'); ?>
			    </td>	
			</tr>
			<?php
		    }
		    ?>							
    	    </tbody>
    	</table></div>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('user_list', 'user_list_callback');

function offer_help()
{
    $post_id = $_REQUEST['request_id'];
    $user_id = $_REQUEST['user_id'];
    $request_status = $_REQUEST['request_status'];

    $message = [];

    if ($post_id != '' && $user_id > 0)
    {
	$key	 = 'offer_help_' . $user_id;
	update_post_meta($post_id, $key, $request_status);

	$message['code']	 = 301;
	$message['message']	 = 'Your request has been add';
    }
    else
    {
	$message['code']	 = 404;
	$message['message']	 = 'Something wrong';
    }

    echo json_encode($message);
    wp_die();
}

add_action('wp_ajax_nopriv_offer_help', 'offer_help');
add_action('wp_ajax_offer_help', 'offer_help');

function cm_share_product($post_id = 0)
{
	$arrShareLink = '';
	
	if($post_id != '' && $post_id > 0)
	{
		$product_link  = get_the_permalink($post_id);
		$product_title = get_the_title($post_id);
		
		$facebookLink = '//www.facebook.com/sharer.php?u=' . $product_link . '&t=' . urlencode($product_title) . '';
		$arrShareLink .= '<a href="' . $facebookLink . '" class="cm_share_product text-center d-inline-block p-2 border mr-1" data-toggle="tooltip" data-placement="top" title="Facebook"> <i class="fa fa-facebook"></i></a>';		
		
		$a_twitter               = urlencode($product_title) . '&url=' . $product_link;
		$twitterLink = '//twitter.com/intent/tweet?text=' . str_replace(' ', '+', $a_twitter);
		$arrShareLink .= '<a href="' . $twitterLink . '" class="cm_share_product text-center d-inline-block p-2 border mr-1" data-toggle="tooltip" data-placement="top" title="Twitter"> <i class="fa fa-twitter"></i></a>';
		
		$linkedinLink = '//www.linkedin.com/shareArticle?mini=true&url=' . $product_link . '&title=' . urlencode($product_title) . '';
		$arrShareLink .= '<a href="' . $linkedinLink . '" class="cm_share_product text-center d-inline-block p-2 border mr-1" data-toggle="tooltip" data-placement="top" title="LinkedIn"> <i class="fa fa-linkedin"></i></a>';
	}
	
	return $arrShareLink;
}
