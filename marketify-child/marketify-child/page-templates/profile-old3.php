<?php
/**
 * Template Name:  Profile Page
 * @package Child Marketify
 */
if(isset($_GET['request']))
{
   $author1 = $_REQUEST['request'];
   ?>
   <style>.my-add-product{display:none}</style>
   <?php
}
else
{
if(!is_user_logged_in()){
    $link = site_url().'/vendor-login-registration';
	wp_redirect($link);
} 
$author1 = get_current_user_id();
}

$author = new WP_User($author1);

global $wpdb;

$id	 = $author->ID;
$myrows	 = $wpdb->get_results("SELECT sales_value FROM wp_fes_vendors where user_id='" . $id . "'");

$sell_info = $wpdb->get_results("select ID, (select meta_value from wp_postmeta where meta_key='_download_id' and post_id = a.ID) as download_id, (select meta_value from wp_postmeta where meta_key='_edd_commission_info' and post_id = a.ID) as download_info from wp_posts a join wp_postmeta b on a.post_type='edd_commission' and  a.post_status = 'publish' and b.post_id = a.ID and meta_key = '_user_id' and meta_value = $id", ARRAY_A);

$buy_info = $wpdb->get_results("select ID, ( select post_id from wp_postmeta where meta_key='_edd_commission_payment_id' and meta_value=a.ID ) as commision_id from wp_posts a join wp_postmeta b on a.post_type='edd_payment' and  a.post_status = 'publish' and b.post_id = a.ID and meta_key = '_edd_payment_user_id' and meta_value = $id", ARRAY_A);

get_header();
?>

<?php do_action('marketify_entry_before'); ?>

<div class="container">
    <div id="content" class="site-content row">

	<section id="primary" class="content-area col-md-9 col-sm-7 col-xs-12">
	    <main id="main" class="site-main" role="main">

		<div class="row">
		    <div class="col-md-2">
			<div class="my-sales-amount">
			    <strong class="widget-detail__title"><?php
				if ($myrows)
				{
				    foreach ($myrows as $myrows1)
				    {
					echo edd_currency_filter(edd_format_amount($myrows1->sales_value));
				    }
				}
				else
				{
				    echo edd_currency_filter(edd_format_amount(0.00));
				}
				?></strong> 
			    <span class="widget-detail__info">Sales Amount</span>
			</div>
		    </div>

		    <div class="col-md-10">
			<div class="my-buy-sales-products">

			    <ul class="my-tab-menu">
				<li class="active" data-tab-id="my-tab-sell">Sell</li>
				<li data-tab-id="my-tab-help-request">Request Help</li>
				<li data-tab-id="my-tab-buy">Buy</li>				
			    </ul>

			    <div class="clear"></div>

			    <?php
			    $current_user_id = $author1;

			    $args = [
				'post_type'	 => 'download',
				'author'	 => $current_user_id,
				'post_status'	 => 'publish',
			    ];

			    $arrDownload = new WP_Query($args);
			    wp_reset_query();

			    $arrSell	 = [];
			    $arrBugsFix	 = [];
			    $arrHelpRequest	 = [];
			    $arrInterview	 = [];
			    $arrRequirment	 = [];

			    if ($arrDownload->found_posts > 0)
			    {
				foreach ($arrDownload->posts as $product_data)
				{
				    $type = get_the_terms($product_data->ID, 'types');

				    if ($type[0]->slug == 'bug-fix')
				    {
					//$arrBugsFix[] = $product_data;
					$arrSell[] = $product_data;
				    }
				    else if ($type[0]->slug == 'help-request')
				    {
					$arrHelpRequest[] = $product_data;
				    }
				    else if ($type[0]->slug == 'interview')
				    {
					$arrInterview[] = $product_data;
				    }
				    else if ($type[0]->slug == 'requirment')
				    {
					$arrRequirment[] = $product_data;
				    }
				    else
				    {
					$arrSell[] = $product_data;
				    }
				}
			    }

			    $site_url = get_site_url();
			    ?>
				
				<div class="my-add-product">
				    <a class="show_product_form" href="JavaScript:Void(0);"><i class="fas fa-plus-square"></i> Add</a>
				</div>
				<div class="clear"></div>
				<div class="add_product_form" style="display:none">
				    <?php echo EDD_FES()->forms->render_submission_form(); ?>
				</div>

			    <div class="my-tab-panel" id="my-tab-sell">				

				<table class="table fes-table table-condensed  table-striped tablesorter {sortlist: [[2,0]]}" id="myTable fes-order-list">
				    <thead>
					<tr>
					    <th>Title</th>
					    <th>Image</th>
					    <th>Price</th>
					    <?php if(isset($_GET['request']))
{}else{?>
					    <th>Edit</th>
					    <th>Delete</th><?php }?>
					</tr>
				    </thead>
				    <tbody>

					<?php
					if (count($arrSell) > 0)
					{
					    foreach ($arrSell as $data)
					    {
						?>
						<tr>

						    <td class="fes-order-list-td widget">
							<?php
							if ($data->post_status == 'publish')
							{
							    $product_link = get_the_permalink($data->ID);
							}
							else
							{
							    $product_link = '#';
							}
							?>
							<a href="<?php echo $product_link ?>" title="View" class="view-order-fes"><?php echo $data->post_title; ?></a>
						    </td>

						    <?php
						    $img_url	 = get_the_post_thumbnail_url($data->ID);
						    ?>
						    <td><img width="50" src="<?php echo $img_url; ?>" ></td>

						    <td class="fes-order-list-td"><?php echo edd_price($data->ID); ?></td>

						    <?php
						    $edit_url	 = $site_url . '/vendor-dashboard/?task=edit-product&post_id=' . $data->ID;
						    ?>
						    <?php if(isset($_GET['request']))
{}else{?>
						    <td class="cm_edit_product_btn"><a href="<?php echo $edit_url; ?>">Edit</a></td>

						    <td class="cm_delete_product_btn" data-id="<?php echo $data->ID; ?>">Delete</td>
						    <?php }?>
						</tr>  
						<?php
					    }
					}
					else
					{
					    echo '<tr><td colspan="5">No Product Found</td></tr>';
					}
					?>

				    </tbody>
				</table>
			    </div>

			    <div class="my-tab-panel" id="my-tab-buy" style="display:none">				

				<table class="table fes-table table-condensed  table-striped tablesorter {sortlist: [[2,0]]}" id="myTable fes-order-list">
				    <thead>
					<tr>
					    <th>Title</th>
					    <th>Price</th>
					</tr>
				    </thead>
				    <tbody>
					<?php
					foreach ($buy_info as $buy)
					{
					    if (empty($buy['commision_id']))
					    {
						continue;
					    }
					    $down_id = get_post_meta($buy['commision_id'], '_download_id', true);

					    $product_name	 = get_the_title($down_id);
					    $product_link	 = get_the_permalink($down_id);
					    $my_download	 = new EDD_Download($down_id);
					    ?>
    					<tr>
    					    <td class="fes-order-list-td widget">		<a href="<?php echo $product_link; ?>" title="View" class="view-order-fes"><?php echo $product_name; ?></a>
    					    </td>
    					    <td class="fes-order-list-td"><?php echo edd_currency_filter(edd_format_amount($my_download->get_price())); ?></td>					
    					</tr>	
					<?php } ?>
				    </tbody>
				</table>
			    </div>

			    <div class="my-tab-panel" id="my-tab-help-request"  style="display:none">

				<table class="table fes-table table-condensed  table-striped  tablesorter {sortlist: [[2,0]]}" id="myTable fes-order-list">
				    <thead>
					<tr>
					    <th>Title</th>
					    <th>Image</th>
					    <th>Price</th>
					     <?php if(isset($_GET['request']))
{}else{?>
					    <th>Edit</th>
					    <th>Delete</th>
					    <?php }?>
					</tr>
				    </thead>
				    <tbody>

					<?php
					if (count($arrHelpRequest) > 0)
					{
					    foreach ($arrHelpRequest as $data)
					    {
						?>
						<tr>

						    <td class="fes-order-list-td widget">
							<?php
							if ($data->post_status == 'publish')
							{
							    $product_link = get_the_permalink($data->ID);
							}
							else
							{
							    $product_link = '#';
							}
							?>
							<a href="<?php echo $product_link ?>" title="View" class="view-order-fes"><?php echo $data->post_title; ?></a>
						    </td>

						    <?php
						    $img_url	 = get_the_post_thumbnail_url($data->ID);
						    ?>
						    <td><img width="50" src="<?php echo $img_url; ?>" ></td>

						    <td class="fes-order-list-td"><?php echo edd_price($data->ID); ?></td>

						    <?php
						    $edit_url	 = $site_url . '/vendor-dashboard/?task=edit-product&post_id=' . $data->ID;
						    ?>
						     <?php if(isset($_GET['request']))
{}else{?>
						    <td class="cm_edit_product_btn"><a href="<?php echo $edit_url; ?>">Edit</a></td>

						    <td class="cm_delete_product_btn" data-id="<?php echo $data->ID; ?>">Delete</td>
						    <?php }?>
						</tr>  
						<?php
					    }
					}
					else
					{
					    echo '<tr><td colspan="5">No Help Request Found</td></tr>';
					}
					?>

				    </tbody>
				</table>

			    </div>

			</div>
		    </div>
		</div>

	    </main><!-- #main -->
	</section><!-- #primary -->

	<div id="secondary" class="author-widget-area col-md-3 col-sm-5 col-xs-12" role="complementary">
	    <div class="vendor-widget-area">
		<aside class="widget widget--vendor-profile widget-detail">        
		    <div class="download-author widget-detail--author">
			<a class="author-avatar" href="#" rel="author"><img alt="" src="https://secure.gravatar.com/avatar/d5dd2d715cb169e6bd1a47bff5f46206?s=130&amp;d=mm&amp;r=g" srcset="https://secure.gravatar.com/avatar/d5dd2d715cb169e6bd1a47bff5f46206?s=260&amp;d=mm&amp;r=g 2x" class="avatar avatar-130 photo" width="130" height="130"></a>            
			<a class="author-link" href="#" rel="author"><?php
			    echo ucwords($author->display_name);
			    $_SESSION['email'] = $author->user_email;
			    ?></a>
		    </div>
		</aside>


	    </div>
	</div><!-- #secondary -->

    </div><!-- #content -->
</div>

<script>
    jQuery(document).ready(function ()
    {
	jQuery('.my-tab-menu li').on('click', function ()
	{
	    jQuery('.my-tab-menu li').removeClass('active');
	    jQuery(this).addClass('active');
	    jQuery('.my-tab-panel').hide();
	    jQuery('#' + jQuery(this).attr('data-tab-id')).show();
		
		$('.add_product_form').hide();
	});
    });
</script>

<script>
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
	
	

    });
</script>

<?php get_footer(); ?>