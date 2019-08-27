<?php
/**
 * Template Name:Help Request Page
 * @package Child Marketify
 */
 get_header(); ?>

<?php 
$args = array(
  'post_type'=>'download','post_status' =>'publish','posts_per_page' => -1); 
$query = new WP_Query( $args );

 ?>
	
	<div id="content" class="site-content site-content--home" role="main">
<div class="container"><h3 class="widget-title widget-title--home section-title"><span>Help List</span></h3>

fdfdfds

<table id="example"  class="table table-striped table-bordered" style="width:100%">
							<thead>
								<tr>
								<th>Question Name</th>
									<th>Price</th>
								</tr>
							</thead>
							<tbody>

							<?php    global $wpdb;
     
    $query = sprintf("SELECT * FROM `".$wpdb->prefix."cf7_data_entry`");
   $data = $wpdb->get_results($query);
        $data_sorted = cf7d_sortdata($data);
							 foreach ($data_sorted as $k) { //echo "<pre>"; print_r($k);
							 ?>
                         	<tr>
								<td><?php echo $k['text-692'];?>	</td>
								<td><?php echo $k['text-354'];?></td>					
							</tr>
                    <?php }
						?>
							</tbody>
						</table>
						<?php wp_reset_postdata();?>
</div>
	
	
		
	</div><!-- #content -->
 <?php get_footer();?>