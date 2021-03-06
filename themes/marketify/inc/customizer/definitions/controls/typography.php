<?php
/**
 * Global Typopgraphy
 *
 * @uses $wp_customize
 * @since 2.11.0
 */

if ( ! defined( 'ABSPATH' ) || ! $wp_customize instanceof WP_Customize_Manager ) {
	exit; // Exit if accessed directly.
}

$elements = marketify_themecustomizer_get_typography_elements();

foreach ( $elements as $element => $label ) {

	$wp_customize->add_control( new Astoundify_ThemeCustomizer_Control_Typography( $wp_customize, array(
		'selector' => $element,
		'source' => 'googlefonts',
		'controls' => marketify_themecustomizer_get_default_typography_controls(),
		'section' => 'typography-' . $element,
	) ) );

}
