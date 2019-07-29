<?php
/**
 * Marketify child theme.
 */
function marketify_child_styles() {
    wp_enqueue_style( 'marketify-child', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'marketify_child_styles', 999 );

/** Place any new code below this line */

function slider_buttons(){
if(!is_user_logged_in()){
    $link = site_url().'/register';
}else{
    $user_id = get_current_user_id();
    $is_vendor = EDD_FES()->vendors->user_is_status( 'approved', $user_id );
    if($is_vendor){
        $link = site_url().'/profile';
    }else{
        $link = site_url().'/register';
    }
}    
?>
<p><a class="button" style="border-color: #1176ef;" href="/downloads">Shop Now</a> <a class="button" style="border-color: #1176ef;" href="<?php echo $link; ?>" title="Start Selling">Start Selling</a></p>
<?php
}
add_shortcode('slider_buttons','slider_buttons');


function mytheme_tinymce_settings( $settings ) {
    $settings['plugins'] .= ",codesample";
    $settings['toolbar1'] .= ",codesample";

    return $settings;
}
//add_filter( 'tiny_mce_before_init', 'mytheme_tinymce_settings' );

//wp_enqueue_style('prism','/wp-includes/js/tinymce/plugins/codesample/css/prism.css');
//wp_enqueue_script('prism_js',plugins_url( 'js/run_code_highlighting.js', __FILE__ ), array('jquery'));


function product_listing_view(){
     ob_start();
    ?>
    <div class="container">
    <div class="site-content row">

    <div role="main" id="primary" class="col-xs-12 col-md-8 col-md-offset-2">

<?php
 $the_query = new WP_Query( array('posts_per_page'=>15,
                                 'post_type'=>'download',
                                 'paged' => get_query_var('paged') ? get_query_var('paged') : 1) 
                            ); 
                            ?>
<?php while ($the_query -> have_posts()) : $the_query -> the_post(); 
$content = get_the_content(); 
$fname = get_the_author_meta('first_name');
$lname = get_the_author_meta('last_name');
$full_name = '';

if( empty($fname)){
    $full_name = $lname;
} elseif( empty( $lname )){
    $full_name = $fname;
} else {
    //both first name and last name are present
    $full_name = "{$fname} {$lname}";
}
$site_url = site_url();
$download_id = get_the_ID();
$download_price = edd_get_download_price($download_id);
$string = edd_get_currency();

?>
<article id="post-<?php the_ID(); ?>" class="post-<?php the_ID(); ?> post type-post format-standard hentry">

            <header class="entry-header entry-header--hentry">
                <h3 class="entry-title entry-title--hentry"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>

                <div class="entry-meta entry-meta--hentry">

                    <span class="byline"><span class="author vcard"><a class="url fn n" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>" title="View all posts by <?php echo $full_name; ?>">
                         <?php 
                         echo get_avatar( get_the_author_meta( 'ID' ) , 32 );
                         ?>
                         <?php echo $full_name; ?>
                        </a></span></span>
                    <span class="entry-date"><!--<a href="<?php the_permalink(); ?>" rel="bookmark">--><?php echo get_the_date(); ?><!--</a>--></span>

                    <span class="price-link"><i class="fa fa-tag" aria-hidden="true"></i><?php echo edd_currency_filter( edd_format_amount( edd_get_download_price( $download_id ) ) ); ?></span>

                </div>
                <!-- .entry-meta -->
            </header>
            <!-- .entry-header -->

            <div class="entry-summary">
                <p><?php echo mb_strimwidth($content, 0, 200, '...'); ?></p>
            </div>
            <!-- .entry-summary -->
        </article>
<?php
endwhile;

// $big = 999999999; // need an unlikely integer
//  echo paginate_links( array(
//     'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
//     'format' => '?paged=%#%',
//     'current' => max( 1, get_query_var('paged') ),
//     'total' => $the_query->max_num_pages
// ) );

wp_reset_postdata();
 ?>
        <!-- #post-## -->
<!-- 
        <nav class="navigation pagination" role="navigation">
            <h2 class="screen-reader-text">Posts navigation</h2>
            <div class="nav-links"><span aria-current="page" class="page-numbers current">1</span>
                <a class="page-numbers" href="https://marketify-demos.astoundify.com/classic/blog/page/2/">2</a>
                <a class="next page-numbers" href="https://marketify-demos.astoundify.com/classic/blog/page/2/">Next</a></div>
        </nav> -->
        <div class="see-more-buttons"><p style="text-align: center;"><a class="button" href="<?php echo site_url(); ?>/downloads/">See more usefull codes</a></p></div>

    </div>
    <!-- #primary -->

</div>

</div>
    <?php
    return ob_get_clean();
}

add_shortcode('product_listing_view','product_listing_view');