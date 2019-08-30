<?php
/**
 * Template Name:Meetups Request Page
 * @package Child Marketify
 */
 get_header(); ?>

 <section id="primary" class="site-content">
 
        <div id="content" role="main">
           <div class="container"><h3 class="widget-title widget-title--home section-title"><span>Meetups</span></h3>
<?php
$args = array(
  'post_type'=>'meetups',
   'post_status' => array('publish'));
$query = new WP_Query( $args );
if ( $query->have_posts() ) {
?><div class="row"><?php 
    while ( $query->have_posts() ) {
        $query->the_post();
  $testimonial_data = get_post_meta( get_the_ID(), '_meetup', true );
                $client_name = ( empty( $testimonial_data['client_name'] ) ) ? '' : $testimonial_data['client_name'];
                $source = ( empty( $testimonial_data['source'] ) ) ? '' : ' - ' . $testimonial_data['source'];
                $link = ( empty( $testimonial_data['link'] ) ) ? '' : $testimonial_data['link'];
                $cite = ( $link ) ? '<a href="' . esc_url( $link ) . '" target="_blank">' . $client_name . $source . '</a>' : $client_name . $source;
?>
<div class="col-md-3 col-sm-4 col-xs-12">
<div class="meetup-img">
<img src="<?php echo get_the_post_thumbnail_url(get_the_ID(),'full');?>" alt="<?php the_title();?>" class="img-responsive">
</div>
<h3 class="entry-title entry-title2 entry-title--hentry"><a target="_blank" href="<?php echo esc_url( $link );?>" rel="bookmark"><?php the_title();?></a></h3>
 <!--<p class="testimonial-client-name"><cite><?php echo $cite; ?></cite></p>-->
</div>


<?php
}
?></div>
<?php
}
?>

</div>
 
        </div>
 
    </section><!-- #primary -->
 <?php get_footer();?>