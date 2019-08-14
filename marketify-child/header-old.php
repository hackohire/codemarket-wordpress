<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <main id="main">
 *
 * @package Marketify
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<style>.header-outer, .minimal, .custom-background.minimal, .wp-playlist .mejs-controls .mejs-time-rail .mejs-time-current {
    background: #fff !important;
}</style>
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/1.1.1/typed.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
  $('#example').dataTable( {
  "pageLength": 20,
"searching": false
} );
} );
</script>
<script>
$(function(){
	$(".typed").typed({
		strings: ["Java.", "C", "C#","Kotlin", "Docker", "GraphQL","MongoDB"],
		// Optionally use an HTML element to grab strings from (must wrap each string in a <p>)
		stringsElement: null,
		// typing speed
		typeSpeed: 40,
		// time before typing starts
		startDelay: 1200,
		// backspacing speed
		backSpeed: 40,
		// time before backspacing
		backDelay: 5000,
		// loop
		loop: true,
		// false = infinite
		loopCount: 10,
		// show cursor
		showCursor: false,
		// character for cursor
		cursorChar: "|",
		// attribute to type (null == text)
		attr: null,
		// either html or text
		contentType: 'html',
		// call when done callback function
		callback: function() {},
		// starting callback function before each string
		preStringTyped: function() {},
		//callback for every typed string
		onStringTyped: function() {},
		// callback for reset
		resetCallback: function() {}
	});
});
</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
 <?php if (is_user_logged_in()) {?>
   
      <script type="text/javascript">
$(document).ready(function() {
 $('#number').keydown(function (e) {
if (e.shiftKey || e.ctrlKey || e.altKey) {
e.preventDefault();
} else {
var key = e.keyCode;
if (!((key == 8) || (key == 46) || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105))) {
e.preventDefault();
}
}
});
});
</script>
 <script>
 $(document).ready(function() {
     $('.wpcf7-validates-as-email').val('<?php echo $_SESSION['email'];?>');
 });
 </script>
 <?php
      if(is_page('Login / Registration')){?>  <script type = "text/javascript" 
         src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
      </script>
 <script>
 $(document).ready(function() { //alert();
     $('.wpcf7-validates-as-email').val('this is some example text');
 });
 </script>
      <script>
     window.location.href="https://girish3.codemarket.io/profile/";
 </script>
 <?php }
 if(is_page( 'Login' )){ ?>
 <script>
     window.location.href="https://girish3.codemarket.io/profile/";
 </script>
    // header("location:https://girish3.codemarket.io/profile/");
    
 <?php } } ?> 
	<header id="masthead" class="site-header" role="banner">
			<div class="container">

				<div class="site-header-inner">

					<div class="site-branding">
						<?php $header_image = get_header_image(); ?>
						<?php if ( ! empty( $header_image ) ) : ?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class="custom-header"><img src="<?php echo esc_url( $header_image ); ?>" alt=""></a>
						<?php endif; ?>

						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<h2 class="site-description screen-reader-text"><?php bloginfo( 'description' ); ?></h2>
					</div>

					<button class="js-toggle-nav-menu--primary nav-menu--primary-toggle"><span class="screen-reader-text"><?php _e( 'Menu', 'marketify' ); ?></span></button>

					<?php
						$args = array(
							'theme_location' => 'primary',
						);

						if ( has_nav_menu( 'primary' ) ) {
							$args['container_class'] = 'nav-menu nav-menu--primary';
						} else {
							$args['menu_class'] = 'nav-menu nav-menu--primary';
						}

						wp_nav_menu( $args );
					?>

				</div>

			</div>
		</header><!-- #masthead -->
<div id="page" class="hfeed site">

	<div <?php echo apply_filters( 'marketify_page_header', array() ); ?>>

	

		<div class="search-form-overlay">
			<?php
				add_filter( 'get_search_form', array( marketify()->template->header, 'search_form' ) );
				get_search_form();
				remove_filter( 'get_search_form', array( marketify()->template->header, 'search_form' ) );
			?>
		</div>