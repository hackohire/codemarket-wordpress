jQuery(document).ready(function ($)
{
	$('body').on('click', '.show_product_form', function (event)
	{
	    event.preventDefault();
	    $('.add_product_form').show();
	});
	
	$('.cm_delete_product_btn').on('click', function (event)
	{
	    event.preventDefault();
	    var ajax_url = window.location.protocol + "//" + window.location.host;
	    var del_id = $(this).attr('data-id');
	    $.ajax({
		url: ajax_url + "/wp-admin/admin-ajax.php",
		type: 'post',
		dataType: 'JSON',
		data: {
		    'action': 'delete_product',
		    'delete_id': del_id
		},
		success: function (data)
		{
		    if (data.code == 301)
		    {
			window.location.href = "/profile/";
		    }
		}
	    });
	});
	
	//https://select2.org/tagging
	//https://select2.org/programmatic-control/add-select-clear-items	
	if( $('.cm_select2').length > 0 )
	{
		$('.cm_select2 select').select2({
			dropdownAutoWidth: true,
			minimumResultsForSearch: Infinity,
			width: '100%',
			tags: true
		});


		$('.cm_select2 select').on('select2:select', function (e)
		{
			var selectData = $(this).val();
			var ajax_url = window.location.protocol + "//" + window.location.host;
			var myObj = $(this);

			$.ajax({
				url: ajax_url + "/wp-admin/admin-ajax.php",
				type: 'post',
				dataType: 'JSON',
				data: {
					'action': 'check_download_category',
					'selectData': selectData
				},
				success: function (data)
				{
					/*
					console.log(selectData);
					console.log(data);
					*/

					$.each(selectData, function (index, value)
					{
						if (value != data[index])
						{
							//Add new category			    
							var newOption = new Option(value, data[index], true, true);
							myObj.append(newOption).trigger('change');
						}
					});

					myObj.val(data);
					myObj.trigger('change');
				}
			});
		});
	}

});