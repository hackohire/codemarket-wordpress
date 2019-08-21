<?php
/**
 * Template Name:Meetup Page
 * @package Child Marketify
 */
 get_header(); ?>

<?php 
$args = array(
  'post_type'=>'download','post_status' =>'publish','posts_per_page' => -1,'order'=>'desc'
  ); 
$query = new WP_Query( $args );
//print_r($query);exit;
 ?>
	
	<div id="content" class="site-content site-content--home" role="main">
<div class="container"><h3 class="widget-title widget-title--home section-title"><span>Product List</span></h3>
<table 	<table class="table fes-table table-condensed table-striped  tablesorter {sortlist: [[2,0]]}" id="myTable help_request_list" style="width:100%">
							<thead>
								<tr>
									<th>Product Name</th>
									<th>Price</th>
<th>Seller Name</th>
<th>Date</th>
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
<td class="fes-order-list-td"><?php echo get_the_author();  ?></td>	
<td class="fes-order-list-td"><?php echo get_the_date( 'Y-m-d' ); ?></td>				
							</tr>  
 <?php
    }
 
}
 

 
?></tbody>
						</table>
						<?php wp_reset_postdata();?>
</div>
	
	
		
	</div><!-- #content -->
 <?php get_footer();?>