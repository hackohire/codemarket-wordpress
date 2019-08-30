<?php
/**
 * Template Name: Layout: Full Width
 *
 * @package Marketify
 */

get_header(); ?>

	<?php do_action( 'marketify_entry_before' ); ?>
<style>
    .styles li
    {
        list-style:none !important;
    }
</style>
	<div class="container">
		<div id="content" class="site-content row">
 <?php if (is_user_logged_in()) { 
 if(is_page('Buy')) { ?> 
 <div class="col-md-12"><div class="my-add-product">
				<a class="show_product_form" href="/profile/?req_id=1"><i class="fas fa-plus-square"></i> Add</a>
			    </div></div>
			    <?php } 
			     else if(is_page('Sell')) {
			     ?>
			     <div class="col-md-12"><div class="my-add-product">
				<a class="show_product_form" href="/profile/?sell=2"><i class="fas fa-plus-square"></i> Add</a>
			    </div></div>
			     <?php } 
			     else if(is_page('Interview')) {?>
			       <div class="col-md-12"><div class="my-add-product">
				<a class="show_product_form" href="/profile/?interview=3"><i class="fas fa-plus-square"></i> Add</a>
			    </div></div><?php }
			    }?>
			<div id="primary" class="content-area col-sm-12">
				<main id="main" class="site-main" role="main">

				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'content', 'page' ); ?>

						<?php
							// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() ) {
							comments_template();
						}
						?>

					<?php endwhile; ?>

					<?php do_action( 'marketify_loop_after' ); ?>

				<?php else : ?>

					<?php get_template_part( 'no-results', 'index' ); ?>

				<?php endif; ?>

				</main><!-- #main -->
			</div><!-- #primary -->

		</div>
	</div>
	
<?php get_footer(); ?>