<?php
wp_enqueue_style('help_request_style');

if ($_GET['id'] != '')
{
    global $wpdb;

    $user_id = get_current_user_id();
    $id	 = $_GET['id'];

    $query	 = "SELECT * FROM `" . $wpdb->prefix . "help_request` WHERE id= " . $id;
    $arrData = $wpdb->get_row($query, ARRAY_A);
    ?>

    <div class="single_help_request_detail">

        <h1 class="page-title help_request_title"><span itemprop="name"><?php echo $arrData['request_title']; ?></span></h1>

        <div class="help_request_content">
	    <?php
	    echo $content = stripslashes($arrData['request_description']);
	    //echo strip_tags($content, '<br>');

	    if ($arrData['request_price'] != '')
	    {
		echo '<p><b>Price :</b> $' . $arrData['request_price'] . ' </p>';
	    }
	    ?>
        </div>

        <div class="help_request_image">
	    <?php
	    $img_url = wp_get_attachment_url($arrData['request_image_id']);
	    echo '<img src="' . $img_url . '">';
	    ?>
        </div>

    </div>

    <?php
}
?>