<?php
wp_enqueue_media();
wp_enqueue_style('help_request_style');
wp_enqueue_script('help_request_script');

if ($_GET['id'] != '')
{
    global $wpdb;

    $user_id = get_current_user_id();
    $id	 = $_GET['id'];

    $query = "SELECT * FROM `" . $wpdb->prefix . "help_request` WHERE id= " . $id . " and user_id = " . $user_id;

    $arrData	 = $wpdb->get_row($query, ARRAY_A);
    ?>

    <form class="cm_help_request" id="cm_help_request" method="post">

        <input class="request_id" type="hidden" name="id" value="<?php echo $arrData['id']; ?>">

        <div class="fes-el">
    	<div class="fes-label">
    	    <label for="post_title">Title<span class="fes-required-indicator">*</span></label>
    	</div>
    	<div class="fes-fields">
    	    <input class="textfield fes-required-field request_title" type="text" name="request_title" value="<?php echo $arrData['request_title']; ?>">
    	    <span class="error_request_title"></span>
    	</div>
        </div>

        <div class="fes-el">
    	<div class="fes-label">
    	    <label for="post_title">Description<span class="fes-required-indicator">*</span></label>
    	</div>
    	<div class="fes-fields">
		<?php
		$content	 = stripslashes($arrData['request_description']);
		$editor_id	 = 'request_description';
		$settings	 = [
		    'textarea_name' => $editor_id,
		];
		wp_editor($content, $editor_id, $settings);
		?>
    	    <span class="error_request_description"></span>
    	</div>
        </div>

        <div class="fes-fields">
    	<div class="fes-label">
    	    <label for="post_title">Choose Image<span class="fes-required-indicator">*</span></label>
    	</div>
    	<div class="fes-feat-image-upload">
    	    <div class="instruction-inside">
    		<input type="hidden" name="request_image_id" class="upload_image_id" value="<?php echo $arrData['request_image_id']; ?>">
    		<a href="JavaScript:Void(0);" class="upload_image_button"><?php _e('Upload Featured Image', 'edd_fes'); ?></a>
    	    </div>
    	    <span class="error_request_image_id"></span>
    	    <div class="upload_image_wrap">
		    <?php
		    $img_url	 = wp_get_attachment_url($arrData['request_image_id']);
		    echo '<img width="100" src="' . $img_url . '"></td>';
		    ?>
    	    </div>
    	</div>
        </div> <!-- .fes-fields -->

        <div class="fes-el">
    	<div class="fes-label">
    	    <label for="post_title">Price<span class="fes-required-indicator">*</span></label>
    	</div>
    	<div class="fes-fields">
    	    <input class="textfield fes-required-field request_price" type="text" name="request_price" value="<?php echo $arrData['request_price']; ?>">
    	    <span class="error_request_price"></span>
    	</div>
        </div>

        <h2><a href="JavaScript:Void(0);" class="cm_help_request_btn">Update Request</a></h2>

    </form>

    <?php
}
else
{
    ?>

    <form class="cm_help_request" id="cm_help_request" method="post">

        <div class="fes-el">
    	<div class="fes-label">
    	    <label for="post_title">Title<span class="fes-required-indicator">*</span></label>
    	</div>
    	<div class="fes-fields">
    	    <input class="textfield fes-required-field request_title" type="text" name="request_title">
    	    <span class="error_request_title"></span>
    	</div>
        </div>

        <div class="fes-el">
    	<div class="fes-label">
    	    <label for="post_title">Description<span class="fes-required-indicator">*</span></label>
    	</div>
    	<div class="fes-fields">
		<?php
		$content	 = '';
		$editor_id	 = 'request_description';
		$settings	 = [
		    'textarea_name'	 => $editor_id,
		    'media_buttons'	 => false,
		];
		wp_editor($content, $editor_id, $settings);
		?>
    	    <span class="error_request_description"></span>
    	</div>
        </div>

        <div class="fes-fields">
    	<div class="fes-label">
    	    <label for="post_title">Choose Image<span class="fes-required-indicator">*</span></label>
    	</div>
    	<div class="fes-feat-image-upload">
    	    <div class="instruction-inside">
    		<input type="hidden" name="request_image_id" class="upload_image_id" value="">
    		<a href="JavaScript:Void(0);" class="upload_image_button"><?php _e('Upload Featured Image', 'edd_fes'); ?></a>
    	    </div>
    	    <span class="error_request_image_id"></span>
    	    <div class="upload_image_wrap">
    	    </div>
    	</div>
        </div> <!-- .fes-fields -->

        <div class="fes-el">
    	<div class="fes-label">
    	    <label for="post_title">Price<span class="fes-required-indicator">*</span></label>
    	</div>
    	<div class="fes-fields">
    	    <input class="textfield fes-required-field request_price" type="text" name="request_price">
    	    <span class="error_request_price"></span>
    	</div>
        </div>

        <h2><a href="JavaScript:Void(0);" class="cm_help_request_btn">Submit Request</a></h2>

    </form>

    <?php
}
?>

<div class="cm_help_request_responce"></div>