<?php
/**
 * Template Name: Page: Profile
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */
$author = get_current_user_id();
$author = new WP_User($author);
$id = $author->ID;
$myrows = $wpdb->get_results( "SELECT * FROM wp_fes_vendors where user_id='".$id."'");

get_header(); ?>
	<?php do_action( 'marketify_entry_before' ); ?>
	<div class="container">
		<div id="content" class="site-content row">
			<section id="primary" class="content-area col-md-9 col-sm-7 col-xs-12">
				<main id="main" class="site-main" role="main">

					<?php //do_action( 'marketify_downloads_before' ); ?>

					<?php// do_action( 'marketify_downloads' ); ?>

					<?php //do_action( 'marketify_downloads_after' ); ?>

				</main><!-- #main -->
			</section><!-- #primary -->

			<div id="secondary" class="author-widget-area col-md-3 col-sm-5 col-xs-12" role="complementary">
				<div class="vendor-widget-area">
				  
<aside class="widget widget--vendor-profile widget-detail">        
<div class="download-author widget-detail--author">
            <a class="author-avatar" href="#" rel="author"><img alt="" src="https://secure.gravatar.com/avatar/d5dd2d715cb169e6bd1a47bff5f46206?s=130&amp;d=mm&amp;r=g" srcset="https://secure.gravatar.com/avatar/d5dd2d715cb169e6bd1a47bff5f46206?s=260&amp;d=mm&amp;r=g 2x" class="avatar avatar-130 photo" width="130" height="130"></a>            
            <a class="author-link" href="#" rel="author"><?php echo $author->display_name;?></a>
            <span class="widget-detail__info">Author since: <?php echo date('M,d-Y',strtotime($author->user_registered));?></span>
    </div>
        <div class="widget-detail widget-detail--pull widget-detail--top">
            <strong class="widget-detail__title">$<?php foreach($myrows as $myrows1){ echo $myrows1->sales_value;}?></strong>
            <span class="widget-detail__info">Sales Amaunt</span>
        </div>
    </aside>
				    
				
				</div>
			</div><!-- #secondary -->

		</div><!-- #content -->
	</div>

<?php get_footer(); ?>
