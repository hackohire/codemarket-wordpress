<?php
/**
 * Template Name:  User Profile Page
 * @package Child Marketify
 */
global $wpdb;

$user_profile_id = $_REQUEST['user_id'];
$author		 = new WP_User($user_profile_id);

$myrows = $wpdb->get_results("SELECT sales_value FROM wp_fes_vendors where user_id='" . $user_profile_id . "'");

$sell_info = $wpdb->get_results("select ID, (select meta_value from wp_postmeta where meta_key='_download_id' and post_id = a.ID) as download_id, (select meta_value from wp_postmeta where meta_key='_edd_commission_info' and post_id = a.ID) as download_info from wp_posts a join wp_postmeta b on a.post_type='edd_commission' and  a.post_status = 'publish' and b.post_id = a.ID and meta_key = '_user_id' and meta_value = $user_profile_id", ARRAY_A);

$buy_info = $wpdb->get_results("select ID, ( select post_id from wp_postmeta where meta_key='_edd_commission_payment_id' and meta_value=a.ID ) as commision_id from wp_posts a join wp_postmeta b on a.post_type='edd_payment' and  a.post_status = 'publish' and b.post_id = a.ID and meta_key = '_edd_payment_user_id' and meta_value = $user_profile_id", ARRAY_A);

get_header();

wp_enqueue_style('select2_style');
wp_enqueue_script('select2_script');
wp_enqueue_script('custom_script');
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
				<?php
				if (isset($_REQUEST['req_id']))
				{
				    ?>
    				<script>
    				    $(document).ready(function ()
    				    {

    					$('.types option:eq(2)').prop('selected', true);
    					$('.types option:eq(1)').hide();
    					$('.types option:eq(3)').hide();
    					$('.types option:eq(4)').hide();

    				    });
    				</script>
				    <?php
				}
				else if (isset($_REQUEST['sell']))
				{
				    ?>
    				<script>
    				    $(document).ready(function ()
    				    {

    					$('.types option:eq(2)').hide();
    					$('.types option:eq(1)').prop('selected', true);
    					$('.types option:eq(3)').hide();
    					$('.types option:eq(4)').hide();

    				    });
    				</script>
    				<li class="active" data-tab-id="my-tab-sell">Sell</li>
				    <?php
				}
				else if (isset($_REQUEST['interview']))
				{
				    ?>
    				<li class="active">Interview</li>
    				<script>
    				    $(document).ready(function ()
    				    {

    					$('.types option:eq(2)').hide();
    					$('.types option:eq(1)').hide();
    					$('.types option:eq(3)').prop('selected', true);
    					$('.types option:eq(4)').hide();

    				    });
    				</script>

				    <?php
				}
				else
				{
				    ?>
    				<li class="active" data-tab-id="my-tab-sell">Sell</li><?php }
				?>
				<?php
				if (isset($_REQUEST['req_id']))
				{
				    ?><style>#my-tab-sell{display:none}</style>
    				<li class="active" data-tab-id="my-tab-help-request">Request Help</li>

				    <?php
				}
				elseif (isset($_REQUEST['sell']))
				{
				    ?>
    				<style>.my-tab-panel{display:none}</style><?php
				}
				elseif (isset($_REQUEST['interview']))
				{
				    
				}
				else
				{
				    ?>
    				<li data-tab-id="my-tab-help-request">Request Help</li>
				<?php } ?>
				<?php
				if (isset($_REQUEST['req_id']))
				{
				    ?>
				    <?php
				}
				elseif (isset($_REQUEST['sell']))
				{
				    
				}
				elseif (isset($_REQUEST['interview']))
				{
				    
				}
				else
				{
				    ?>
    				<li data-tab-id="my-tab-buy">Buy</li>
    				<li data-tab-id="my-tab-notification">Notification</li>
				<?php } ?> </ul>

			    <div class="clear"></div>

			    <?php
			    $args = [
				'post_type'	 => 'download',
				'author'	 => $user_profile_id,
				'post_status'	 => 'publish',
			    ];

			    $arrDownload = new WP_Query($args);
			    wp_reset_query();

			    $arrSell	 = [];
			    $arrBugsFix	 = [];
			    $arrHelpRequest	 = [];
			    $arrOfferHelp	 = [];
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

					$arrOfferHelp[] = $product_data->ID;
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
			    <?php
			    if (isset($_REQUEST['req_id']))
			    {
				?>
				<?php
			    }
			    elseif (isset($_REQUEST['sell']))
			    {
				
			    }
			    else if (isset($_REQUEST['interview']))
			    {
				?>
    			    <style>
    				#my-tab-sell{display:none}
    			    </style>
				<?php
			    }
			    else
			    {
				if (isset($_REQUEST['post_id']))
				{
				    
				}
			    }
			    ?>
			    <div class="clear"></div>

			    <?php
			    $edit_product_link = $site_url . '/profile/?task=edit-product&post_id=';
			    ?>

			    <div class="my-tab-panel" id="my-tab-sell">

				<table class="table fes-table table-condensed table-striped tablesorter {sortlist: [[2,0]]}" id="myTable fes-order-list">
				    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Image</th>
                                            <th>Price</th>
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
						    $img_url = get_the_post_thumbnail_url($data->ID);
						    ?>
						    <td><img width="50" src="<?php echo $img_url; ?>" ></td>

						    <td class="fes-order-list-td"><?php echo edd_price($data->ID); ?></td>
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

				<table class="table fes-table table-condensed table-striped tablesorter {sortlist: [[2,0]]}" id="myTable fes-order-list">
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
    					    <td class="fes-order-list-td widget"><a href="<?php echo $product_link; ?>" title="View" class="view-order-fes"><?php echo $product_name; ?></a></td>

    					    <td class="fes-order-list-td"><?php echo edd_currency_filter(edd_format_amount($my_download->get_price())); ?></td>
    					</tr>
					<?php } ?>
				    </tbody>
				</table>
			    </div>

			    <div class="my-tab-panel" id="my-tab-help-request"  style="display:none">

				<table class="table fes-table table-condensed table-striped tablesorter {sortlist: [[2,0]]}" id="myTable fes-order-list">
				    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Image</th>
                                            <th>Price</th>
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
						    $img_url = get_the_post_thumbnail_url($data->ID);
						    ?>
						    <td><img width="50" src="<?php echo $img_url; ?>" ></td>

						    <td class="fes-order-list-td"><?php echo edd_price($data->ID); ?></td>
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

			    <div class="my-tab-panel" id="my-tab-notification" style="display:none">				
				<?php
				if (count($arrOfferHelp) > 0)
				{
				    ?>

    				<h3 class="notification_heading mt-2">Offer Help Request</h3>

				    <?php
				    $offer_help_id = implode(',', $arrOfferHelp);

				    $sql = "SELECT *  FROM `wp_postmeta` WHERE `meta_key` LIKE 'offer_help%' AND post_id IN (" . $offer_help_id . ")";

				    $get_offer_help = $wpdb->get_results($sql, ARRAY_A);

				    if (!empty($get_offer_help))
				    {
					?>
					<table class="table fes-table table-condensed table-striped tablesorter {sortlist: [[2,0]]}" id="myTable fes-order-list">
					    <thead>
						<tr>
						    <th>Title</th>
						    <th>User Name</th>
						    <th>Status</th>
						</tr>
					    </thead>
					    <tbody>
						<?php
						foreach ($get_offer_help as $offer_help)
						{
						    $explode_key	 = explode('_', $offer_help['meta_key']);
						    $user_id	 = $explode_key[2];
						    $user_name	 = get_userdata($user_id);
						    $product_link	 = get_the_permalink($offer_help['post_id']);

						    echo '<tr>';
						    echo '<td><a href="' . $product_link . '">' . get_the_title($offer_help['post_id']) . '</a></td>';
						    echo '<td>' . $user_name->data->display_name . '</td>';
						    echo '<td>' . $offer_help['meta_value'] . '</td>';
						    echo '</tr>';
						}
						?>
					    </tbody>
					</table>
					<?php
				    }
				    else
				    {
					echo 'No any offer help request found';
				    }
				}
				?>

				<div class="ab_author_notification mt-2">
				    <h3 class="notification_heading">Comments</h3>
				    <?php
				    $args		 = [
					'post_type'	 => 'download',
					'author'	 => $user_profile_id,
					'post_status'	 => 'publish',
				    ];
				    $download_post	 = array();
				    $arrDownload	 = new WP_Query($args);
				    wp_reset_query();
				    if ($arrDownload->found_posts > 0)
				    {
					foreach ($arrDownload->posts as $product_data)
					{
					    $download_post[] = $product_data->ID;
					}
				    }

				    if (!empty($download_post))
				    {
					$args = array(
					    'post__in'	 => $download_post,
					    'count'		 => true
					);

					$total_comment_count = get_comments($args);

					if ($total_comment_count != 0)
					{
					    $limit		 = 5;
					    $page		 = (get_query_var('paged')) ? get_query_var('paged') : 1;
					    $offset		 = ($limit * $page) - $limit;
					    $max_num_pages	 = ceil($total_comment_count / $limit);


					    $args = array(
						'post__in'	 => $download_post,
						'number'	 => $limit,
						'offset'	 => $offset,
					    );

					    $comments = get_comments($args);

					    foreach ($comments as $comment) :
						?>
	    				    <article class="row">
	    					<div class="col-md-2 col-sm-2 hidden-xs">
	    					    <figure class="thumbnail">

	    						<img class="img-responsive"
	    						     src="<?php echo esc_url(get_avatar_url($comment->user_id)); ?>"/>
	    						<figcaption
	    						    class="text-center"><?= $comment->comment_author; ?></figcaption>
	    					    </figure>
	    					</div>
	    					<div class="col-md-10 col-sm-10">
	    					    <div class="panel panel-default arrow left">
	    						<div class="panel-body">
	    						    <header class="text-left">
	    							<div class="comment-user pull-left"><i
	    								class="fa fa-bullhorn"></i> <?= get_the_title($comment->comment_post_ID); ?>
	    							</div>
	    							<time class="comment-date pull-right"
	    							      datetime="16-12-2014 01:05"><i
	    								class="fa fa-clock-o"></i> <?= date('dS M Y', strtotime($comment->comment_date)); ?>
	    							</time>
	    						    </header>
	    						    <div class="clear"></div>
	    						    <div class="comment-post">
	    							<p>
									<?php echo wp_trim_words($comment->comment_content, 20); ?>
	    							</p>
	    						    </div>
	    						    <div class="text-right">
								    <?php
								    $approved	 = '';
								    $disapproved	 = '';
								    if ($comment->comment_approved == 0)
								    {
									$approved	 = 'style=display:inline-block';
									$disapproved	 = 'style=display:none';
								    }
								    else
								    {
									$approved	 = 'style=display:none';
									$disapproved	 = 'style=display:inline-block';
								    }
								    ?>
	    							<button <?= $approved; ?> class="btn btn-default btn-sm comment_action cm_approve_<?= $comment->comment_ID; ?>" data-action = "approve" data-id = "<?= $comment->comment_ID; ?>"><i class="fa fa-thumbs-up"></i> Approve</button>
	    							<button <?= $disapproved; ?> class="btn btn-default btn-sm comment_action cm_disapprove_<?= $comment->comment_ID; ?>" data-action = "hold" data-id = "<?= $comment->comment_ID; ?>"><i class="fa fa-thumbs-down"></i> Disapprove</button>
	    							<a <?= $disapproved; ?> href="<?= get_comment_link($comment->comment_ID); ?>" class="btn btn-default btn-sm hide cm_reply_<?= $comment->comment_ID; ?>"><i class="fa fa-reply"></i> Reply</a>
	    							<p style="display:none;" class="cm_hide_status cm_status_<?= $comment->comment_ID; ?>"></p>
	    						    </div>
	    						</div>
	    					    </div>
	    					</div>
	    				    </article>
						<?php
					    endforeach;

					    echo '<div class="product_pagination">';
					    cm_pagination($max_num_pages, 2);
					    echo '</div>';
					}
					else
					{
					    echo '<p> No any comment found</p>';
					}
				    }
				    else
				    {
					echo '<p> No any comment found</p>';
				    }
				    ?>
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
			<a class="author-link" href="#" rel="author">
			    <?php
			    echo ucwords($author->display_name);
			    $_SESSION['email'] = $author->user_email;
			    ?>
			</a>
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

<?php get_footer(); ?>