<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Marketify
 */
?>

	<footer id="colophon" class="site-footer site-footer--<?php echo esc_attr( get_theme_mod( 'footer-style', 'light' ) ); ?>" role="contentinfo">
		<div class="container">
			<?php do_action( 'marketify_footer_above' ); ?>

			<div class="footer-widget-areas">
				<div class="widget widget--site-footer">
					<?php dynamic_sidebar( 'footer-1' ); ?>
				</div>

				<div class="widget widget--site-footer">
					<?php dynamic_sidebar( 'footer-2' ); ?>
				</div>

				<div class="widget widget--site-footer">
					<?php dynamic_sidebar( 'footer-3' ); ?>
				</div>
			</div>

			<?php do_action( 'marketify_footer_site_info' ); ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/1.1.1/typed.min.js"></script>
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
</body>
</html>
