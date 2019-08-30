<?php

/**
 * Marketify child theme.
 */
function marketify_child_styles()
{
    wp_enqueue_style('marketify-child', get_stylesheet_uri());
}

/*add_filter( 'comment_form_defaults', 'rich_text_comment_form' );
function rich_text_comment_form( $args ) {
	ob_start();
	wp_editor( '', 'comment', array(
		'media_buttons' => true, // show insert/upload button(s) to users with permission
		'textarea_rows' => '10', // re-size text area
		'dfw' => false, // replace the default full screen with DFW (WordPress 3.4+)
		'tinymce' => array(
        	'theme_advanced_buttons1' => 'bold,italic,underline,strikethrough,bullist,numlist,code,blockquote,link,unlink,outdent,indent,|,undo,redo,fullscreen',
	        'theme_advanced_buttons2' => '', // 2nd row, if needed
        	'theme_advanced_buttons3' => '', // 3rd row, if needed
        	'theme_advanced_buttons4' => '' // 4th row, if needed
  	  	),
		'quicktags' => array(
 	       'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,close'
	    )
	) );
	$args['comment_field'] = ob_get_clean();
	return $args;
}
*/
add_action('wp_enqueue_scripts', 'marketify_child_styles', 999);

/** Place any new code below this line */
function add_help_request_table()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'help_request';

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
    {
	$sql = "CREATE TABLE " . $table_name . " (
		    id int(11) NOT NULL AUTO_INCREMENT,				
		    user_id int(20) DEFAULT NULL,
		    request_title varchar(255) NOT NULL DEFAULT '',
		    request_description text NOT NULL,
		    request_image_id int(20) DEFAULT NULL,
		    request_price int(5) DEFAULT NULL,
		    created_at datetime NOT NULL,
		    updated_at datetime NOT NULL,
		    PRIMARY KEY  (id)
	      );";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
    }
}

add_action('wp_enqueue_scripts', 'cm_help_request_style_script');

function cm_help_request_style_script()
{
    wp_register_style('help_request_style', get_stylesheet_directory_uri() . '/assets/css/help_request.css');

    wp_register_script('help_request_script', get_stylesheet_directory_uri() . '/assets/js/help_request.js', '', '', true);
}

function save_help_request()
{

    global $wpdb;

    add_help_request_table();

    $user_id = get_current_user_id();

    parse_str($_POST['form_data'], $formdata);

    $message = [];

    if ($formdata['request_title'] == '')
    {
	$message['code']	 = 404;
	$message['field']	 = 'request_title';
	$message['messgae']	 = 'Title is required';

	echo json_encode($message);
	wp_die();
    }

    if ($formdata['request_description'] == '')
    {
	$message['code']	 = 404;
	$message['field']	 = 'request_description';
	$message['messgae']	 = 'Description is required';

	echo json_encode($message);
	wp_die();
    }

    if ($formdata['request_image_id'] == '')
    {
	$message['code']	 = 404;
	$message['field']	 = 'request_image_id';
	$message['messgae']	 = 'Image is required';

	echo json_encode($message);
	wp_die();
    }

    if ($formdata['request_price'] == '')
    {
	$message['code']	 = 404;
	$message['field']	 = 'request_price';
	$message['messgae']	 = 'Price is required';

	echo json_encode($message);
	wp_die();
    }

    if ($formdata['request_title'] != '' && $formdata['request_description'] != '')
    {


	if ($formdata['id'] != '')
	{
	    $params = [];

	    $params['user_id']		 = $user_id;
	    $params['request_title']	 = $formdata['request_title'];
	    $params['request_description']	 = $formdata['request_description'];
	    $params['request_image_id']	 = isset($formdata['request_image_id']) ? $formdata['request_image_id'] : '';
	    $params['request_price']	 = $formdata['request_price'];
	    $params['updated_at']		 = date('Y-m-d H:i:s');

	    $wpdb->update($wpdb->prefix . 'help_request', $params, ['id' => $formdata['id']]);

	    $request_id = $formdata['id'];

	    $message['code']	 = 301;
	    $message['id']		 = $request_id;
	    $message['messgae']	 = 'Successfully update your request';
	}
	else
	{
	    $params = [];

	    $params['user_id']		 = $user_id;
	    $params['request_title']	 = $formdata['request_title'];
	    $params['request_description']	 = $formdata['request_description'];
	    $params['request_image_id']	 = isset($formdata['request_image_id']) ? $formdata['request_image_id'] : '';
	    $params['request_price']	 = $formdata['request_price'];
	    $params['created_at']		 = date('Y-m-d H:i:s');
	    $params['updated_at']		 = date('Y-m-d H:i:s');

	    $wpdb->insert($wpdb->prefix . 'help_request', $params);

	    $request_id = $wpdb->insert_id;

	    $message['code']	 = 301;
	    $message['id']		 = $request_id;
	    $message['messgae']	 = 'Successfully added new request';
	}
    }
    else
    {
	$message['code']	 = 404;
	$message['messgae']	 = 'Something Wrong';
    }

    /*
      // Print last SQL query string
      echo $wpdb->last_query;
      // Print last SQL query result
      echo $wpdb->last_result;
      // Print last SQL query Error
      echo $wpdb->last_error;

      echo "<pre>";
      print_r($formdata);
      echo "</pre>" . __FILE__ . ' ( Line Number ' . __LINE__ . ')';
      die();
     */

    echo json_encode($message);
    wp_die();
}

add_action('wp_ajax_nopriv_save_help_request', 'save_help_request');
add_action('wp_ajax_save_help_request', 'save_help_request');

function delete_help_request()
{

    global $wpdb;

    $user_id = get_current_user_id();

    $wpdb->delete($wpdb->prefix . 'help_request', ['id' => $_POST['delete_id'], 'user_id' => $user_id]);

    $message		 = [];
    $message['code']	 = 301;
    $message['messgae']	 = 'Request has been deleted';

    echo json_encode($message);
    wp_die();
}

add_action('wp_ajax_nopriv_delete_help_request', 'delete_help_request');
add_action('wp_ajax_delete_help_request', 'delete_help_request');

function help_request_list_callback($atts)
{
    global $wpdb;

    wp_enqueue_style('help_request_style');

    $atts = shortcode_atts(
	    [
	'title'	 => 'Recent Requests',
	'limit'	 => '10',
	    ], $atts, 'help_request_list');

    ob_start();

    $query	 = "SELECT * FROM `" . $wpdb->prefix . "help_request` ORDER BY `id` DESC LIMIT " . $atts['limit'];
    $arrData = $wpdb->get_results($query, ARRAY_A);

    if (!empty($arrData))
    {
	$site_url = site_url();
	?>
	<h3 class="widget-title widget-title--home section-title"><span><?php echo $atts['title']; ?></span></h3>

	<table class="table fes-table table-condensed table-striped" id="help_request_list">

	    <thead>
		<tr>
		    <th width="20%">Title</th>
		    <th width="30%">Description</th>
		    <th>Price</th>
		    <th>Image</th>
		    <th>User</th>
		    <th>Date</th>
		</tr>
	    </thead>

	    <tbody>								
		<?php
		foreach ($arrData as $data)
		{
		    echo '<tr>';

		    echo '<td><a style="text-decoration: none;" href="' . $site_url . '/vendor-dashboard/?task=view-help-request&id=' . $data['id'] . '">' . $data['request_title'] . '</a></td>';

		    $content = stripslashes($data['request_description']);
		    echo '<td>' . wp_trim_words($content, 20) . '</td>';

		    echo '<td>' . $data['request_price'] . '</td>';

		    $img_url = wp_get_attachment_url($data['request_image_id']);
		    echo '<td><img width="50" src="' . $img_url . '"></td>';

		    $user_name = get_userdata($data['user_id']);
		    echo '<td>' . $user_name->data->display_name . '</td>';

		    $date = date_create($data['created_at']);
		    echo '<td>' . date_format($date, "Y-m-d") . '</td>';

		    echo '</tr>';
		}
		?>							
	    </tbody>
	</table>
	<?php
    }

    return ob_get_clean();
}

add_shortcode('help_request_list', 'help_request_list_callback');
