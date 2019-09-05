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

    $('.cm_share_product').click(function (e)
    {
        e.preventDefault();
        window.open($(this).attr('href'), 'fbShareWindow', 'height=600, width=700, top=' + ($(window).height() / 2 - 275) + ', left=' + ($(window).width() / 2 - 225) + ', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
        return false;
    });

    if ($('.cm_select_2').length > 0)
    {
        $('.cm_select_2 select').select2({
            tags: true
        });
    }

    //https://select2.org/tagging
    //https://select2.org/programmatic-control/add-select-clear-items
    if ($('.cm_select2').length > 0)
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


    $('.offer_help_action').on('click', function (event)
    {
        event.preventDefault();
        var ajax_url = window.location.protocol + "//" + window.location.host;
        var request_id = $(this).attr('data-id');
        var request_status = $(this).attr('data-status');
        var user_id = $(this).attr('data-user-id');

        $.ajax({
            url: ajax_url + "/wp-admin/admin-ajax.php",
            type: 'post',
            dataType: 'JSON',
            data: {
                'action': 'offer_help',
                'request_id': request_id,
                'request_status': request_status,
                'user_id': user_id
            },
            success: function (data)
            {
                if (data.code == 301)
                {
                    location.reload();
                }
            }
        });
    });


    $('body').on('click', '.assign_offer_help .btn_assign_offer_help', function (event)
    {
        event.preventDefault();

        $('.assign_offer_help .cm_error').addClass('d-none');

        if($('.assign_offer_help .assign_user_id').val() != '')
        {
            $(this).off(event);

            var formData = $('.assign_offer_help').serialize();

            var ajax_url = window.location.protocol + "//" + window.location.host;

            $.ajax({
                url: ajax_url + "/wp-admin/admin-ajax.php",
                type: 'post',
                dataType: 'JSON',
                data: {
                    'action': 'assign_offer_help',
                    'form_data': formData
                },
                success: function (data)
                {
                    if (data.code == 301)
                    {
                        location.reload();
                    }
                    $(this).on(event);
                }
            });
        }
        else
        {
            $('.assign_offer_help .cm_error').removeClass('d-none');
        }
    });
    $('.comment_action').on('click', function (event)
    {
        event.preventDefault();
        var ajax_url = window.location.protocol + "//" + window.location.host;
        var comment_id = $(this).attr('data-id');
        var comment_action = $(this).attr('data-action');
        $('.cm_hide_status').hide();
        $('.cm_status_'+comment_id).show().html('Wait...');


        $.ajax({
            url: ajax_url + "/wp-admin/admin-ajax.php",
            type: 'post',
            dataType: 'JSON',
            data: {
                'action': 'comment_action',
                'comment_id': comment_id,
                'comment_action': comment_action,
            },
            success: function (data)
            {
                if(data.code == 200){
                    if(data.comment_action =='approve'){
                        $('.cm_approve_'+comment_id).hide();
                        $('.cm_disapprove_'+comment_id).show();
                        $('.cm_reply_'+comment_id).show();
                    }
                    else if(data.comment_action =='hold'){
                        $('.cm_approve_'+comment_id).show();
                        $('.cm_disapprove_'+comment_id).hide();
                        $('.cm_reply_'+comment_id).hide();
                    }
                    $('.cm_status_'+comment_id).show().html('Comment is '+data.comment_action);

                }
                else{
                    $('.cm_status_'+comment_id).show().html('Something going wrong...');
                }


            }
        });
    });

});