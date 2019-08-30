jQuery(document).ready(function ($)
{
    $('.upload_image_button').on('click', function (event)
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

    $('.cm_help_request_btn').on('click', function (event)
    {
	event.preventDefault();

	$(this).off(event);

	$(".wp-editor-tabs #request_description-html").trigger("click");
	$(".wp-editor-tabs #request_description-tmce").trigger("click");

	var ajax_url = window.location.protocol + "//" + window.location.host;

	var formData = $('#cm_help_request').serialize();

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
		if (data.code == 404)
		{
		    if (data.field != '')
		    {
			$('.error_' + data.field).html(data.messgae);
		    } else
		    {
			$('.cm_help_request_responce').html(data.messgae);
		    }
		} else
		{
		    $('.cm_help_request_responce').html(data.messgae);

		    window.location.href = "/vendor-dashboard/?task=view-help-request&id=" + data.id;
		}

		$(this).on(event);
	    }
	});

    });

});