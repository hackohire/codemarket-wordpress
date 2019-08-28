<?php
//echo EDD_FES()->forms->render_submission_form();
?>
<form class="kk_help_request" id="kk_help_request" method="post">

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
			$content = '';
			$editor_id = 'request_description';
			$settings = [
				'textarea_name' => $editor_id,
			];
			wp_editor( $content, $editor_id, $settings); 
			?>
			<span class="error_request_description"></span>
		</div>
	</div>
	
	<div class="fes-fields">		
		<div class="fes-feat-image-upload">
			<div class="instruction-inside">
				<input type="hidden" name="request_image_id" class="upload_image_id" value="">
				<a href="JavaScript:Void(0);" class="upload_image_button"><?php _e( 'Upload Featured Image', 'edd_fes' ); ?></a>
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
	
	<br/><br/><br/>
	<h2><a style="padding: 10px;background: #ddd;color: #000 !important;" href="JavaScript:Void(0);" class="kk_help_request_btn">Submit Request</a></h2>

</form>

<div class="kk_help_request_responce"></div>

<style>
.del_icon{
    position: relative  !important;
    vertical-align: top  !important;
    color: red  !important;
}
.error_request_title, .error_request_description, .error_request_image_id, .error_request_price{
    font-size:18px;
    color: red !important;
	width:100%;
	position:relative;
	display: inline-block;
}
</style>

<script type='text/javascript'>
jQuery(document).ready(function ($)
{
	jQuery('.upload_image_button').on('click', function (event)
	{
		event.preventDefault();

		var upload = wp.media({
			title: 'Choose Image', //Title for Media Box
			multiple: false //For limiting multiple image
		})
		.on('select', function ()
		{
			var attach = upload.state().get('selection').map(
					function (attachment)
					{
						attachment.toJSON();
						return attachment;
					});

			for (i = 0; i < attach.length; ++i)
            {
				var a_id = attach[i].attributes.id;
				
				var img_html = '<img width="100" src="' + attach[i].attributes.url + '">';
				
				$('.upload_image_id').val(a_id);
				$('.upload_image_wrap').html(img_html);
			}			
		})
		.open();
	});
	
	jQuery(document).ready(function ($)
	{
		jQuery('.kk_help_request_btn').on('click', function (event)
		{
			event.preventDefault();
			
			var ajax_url = window.location.protocol + "//" + window.location.host;

			var formData = $('#kk_help_request').serialize();

			$('.fes-fields span').html('');
			
			$.ajax({
				url: ajax_url + "/wp-admin/admin-ajax.php",
				type: 'post',
				dataType: 'JSON',
				data: {
					'action': 'save_help_request',
					'form_data': formData
				},
				success: function (data)
				{
					if(data.code == 404)
					{
						if(data.field != '')
						{
							$('.error_' + data.field).html(data.messgae);
						}
						else
						{
							$('.kk_help_request_responce').html(data.messgae);
						}
					}
					else
					{
						$('.kk_help_request_responce').html(data.messgae);
						window.location.href = "/profile/";
					}
				}
			});
			
		});
	});
	
	
});
</script>