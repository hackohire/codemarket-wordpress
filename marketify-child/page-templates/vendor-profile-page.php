<?php
/**
 * Template Name: Custom Vendor Profile
 * @package Child Marketify
 */
if(!is_user_logged_in()){
    $link = site_url().'/vendor-login-registration';
	wp_redirect($link);
} 
 
$author = get_current_user_id();
$author = new WP_User($author);
$id = $author->ID;

$myrows = $wpdb->get_results( "SELECT sales_value FROM wp_fes_vendors where user_id='".$id."'"); 
 
$sell_info = $wpdb->get_results( "select ID, (select meta_value from wp_postmeta where meta_key='_download_id' and post_id = a.ID) as download_id, (select meta_value from wp_postmeta where meta_key='_edd_commission_info' and post_id = a.ID) as download_info from wp_posts a join wp_postmeta b on a.post_type='edd_commission' and  a.post_status = 'publish' and b.post_id = a.ID and meta_key = '_user_id' and meta_value = $id", ARRAY_A);

//$sell_info = $wpdb->get_results( "select ID,(select meta_value from wp_postmeta where meta_key='_edd_commission_info' and post_id = a.ID) as download_info from wp_posts a join wp_postmeta b on a.post_type='edd_commission' and  a.post_status = 'publish' and b.post_id = a.ID and meta_key = '_user_id' and meta_value = $id", ARRAY_A);

$buy_info = $wpdb->get_results( "select ID, ( select post_id from wp_postmeta where meta_key='_edd_commission_payment_id' and meta_value=a.ID ) as commision_id from wp_posts a join wp_postmeta b on a.post_type='edd_payment' and  a.post_status = 'publish' and b.post_id = a.ID and meta_key = '_edd_payment_user_id' and meta_value = $id", ARRAY_A); 
 
get_header(); ?>

<?php do_action( 'marketify_entry_before' ); ?>

<?php
   global $wpdb;
     
    $query = sprintf("SELECT * FROM `".$wpdb->prefix."cf7_data_entry`");
   $data = $wpdb->get_results($query);
        $data_sorted = cf7d_sortdata($data);
//print_r($data_sorted);
$args = array(
  'post_type'=>'download','author'=>get_current_user_id(),
   'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')
  
);
 
// Custom query.
$query = new WP_Query( $args );
 ?>
	<div class="container">
		<div id="content" class="site-content row">
			<section id="primary" class="content-area col-md-9 col-sm-7 col-xs-12">
				<main id="main" class="site-main" role="main">
				
					<div class="my-add-product">
					<a href="/help/">Help</a>
				</div> 
				<div class="my-add-product">
					<a href="/vendor-dashboard/?task=new-product">Add Product</a>
				</div> 
				<div class="my-sales-amount">
					<strong class="widget-detail__title"><?php 
					if( $myrows ){
						foreach($myrows as $myrows1){ echo edd_currency_filter( edd_format_amount($myrows1->sales_value));}
					}
					else{
						echo edd_currency_filter( edd_format_amount(0.00));
					}
					?></strong> 
					<span class="widget-detail__info">Sales Amount</span>
				</div>
				<div class="clear"></div>
				<div class="my-buy-sales-products">
					<ul class="my-tab-menu">
						<li class="active" data-tab-id="my-tab-sell">Sell</li>
						<li data-tab-id="my-tab-buy">Buy</li>
						<li data-tab-id="my-tab-help">Help</li>
					</ul>
					<div class="my-tab-panel" id="my-tab-sell">
						  <?php //echo do_shortcode('[fes_vendor_dashboard]');?>
						<table class="table fes-table table-condensed  table-striped" id="fes-order-list">
							<thead>
								<tr>
									<th>Product Name</th>
									<th>Price</th>
								</tr>
							</thead>
							<tbody>

						<?php 
				
						// Check that we have query results.
if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
        $query->the_post();
        global $post;
        
      ?>
       
      	<tr>
								<td class="fes-order-list-td widget">		
								<a href="<?php the_permalink(); ?>" title="View" class="view-order-fes"><?php the_title(); ?></a>
								</td>
								<td class="fes-order-list-td"><?php echo edd_price($post->ID); ?></td>					
							</tr>  
 <?php
    }
 
}
 

 
?>
							
						<?php	
						/*	foreach( $sell_info as $sell){ 
							$my_download = new EDD_Download( $sell['download_id'] );
							$product_name	=	get_the_title($sell['download_id']);
							$product_link	=	get_the_permalink($sell['download_id']);*/
							?>
						<!--<tr>
								<td class="fes-order-list-td widget">		
								<a href="<?php //echo $product_link; ?>" title="View" class="view-order-fes"><?php // echo $product_name; ?></a>
								</td>
								<td class="fes-order-list-td"><?php //echo edd_currency_filter( edd_format_amount($my_download->get_price())); ?></td>					
							</tr>	-->	
						<?php /*	}*/
						?>
							</tbody>
						</table>
						<?php wp_reset_postdata();?>
					</div>
					<div class="my-tab-panel" id="my-tab-buy">
					<table class="table fes-table table-condensed  table-striped" id="fes-order-list">
							<thead>
								<tr>
									<th>Product Name</th>
									<th>Price</th>
								</tr>
							</thead>
							<tbody>
							   
							    
						<?php //$payments = edd_get_users_purchases( 9, 20, true, 'any' );
							
							foreach( $buy_info as $buy){ 
							if( empty( $buy['commision_id'] ) ){
								continue;
							}
							$down_id=get_post_meta($buy['commision_id'], '_download_id', true );
							$product_name	=	get_the_title($down_id);
							$product_link	=	get_the_permalink($down_id);
							$my_download = new EDD_Download( $down_id );
							?>
							<tr>
								<td class="fes-order-list-td widget"><a href="<?php echo $product_link; ?>" title="View" class="view-order-fes"><?php echo $product_name; ?></a>
								</td>
								<td class="fes-order-list-td"><?php echo edd_currency_filter( edd_format_amount($my_download->get_price())); ?></td>					
							</tr>	
						<?php } ?>
						</tbody>
						</table>
					</div>
					
						<div class="my-tab-panel" id="my-tab-help">
					<table class="table fes-table table-condensed  table-striped" id="fes-order-list">
							<thead>
								<tr>
									<th>Question Name</th>
									<th>Price</th>
								</tr>
							</thead>
							<tbody>
							   
							    
						<?php //$payments = edd_get_users_purchases( 9, 20, true, 'any' );
							 foreach ($data_sorted as $k) { //echo "<pre>"; print_r($k);
							 ?>
                         	<tr>
								<td class="fes-order-list-td widget"><?php echo $k['text-692'];?>	</td>
								<td class="fes-order-list-td"><?php echo $k['text-354'];?></td>					
							</tr>
                    <?php }
						?>
							
						
						</tbody>
						</table>
					</div>
				</div>

				</main><!-- #main -->
			</section><!-- #primary -->

			<div id="secondary" class="author-widget-area col-md-3 col-sm-5 col-xs-12" role="complementary">
				<div class="vendor-widget-area">
					<aside class="widget widget--vendor-profile widget-detail">        
				<div class="download-author widget-detail--author">
				<a class="author-avatar" href="#" rel="author"><img alt="" src="https://secure.gravatar.com/avatar/d5dd2d715cb169e6bd1a47bff5f46206?s=130&amp;d=mm&amp;r=g" srcset="https://secure.gravatar.com/avatar/d5dd2d715cb169e6bd1a47bff5f46206?s=260&amp;d=mm&amp;r=g 2x" class="avatar avatar-130 photo" width="130" height="130"></a>            
				<a class="author-link" href="#" rel="author"><?php echo ucwords($author->display_name);$_SESSION['email']= $author->user_email;?></a>
			</div>
    </aside>
	
				
				</div>
			</div><!-- #secondary -->

		</div><!-- #content -->
	</div>
<script>
	jQuery(document).ready(function(){
		jQuery('.my-tab-menu li').on('click', function(){
			jQuery('.my-tab-menu li').removeClass('active');
			jQuery(this).addClass('active');
			jQuery('.my-tab-panel').hide();
			jQuery('#'+jQuery(this).attr('data-tab-id')).show();
		});
	});
</script>

<?php get_footer(); ?>