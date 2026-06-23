<?php
/**
 * Rendering helpers for the Featured Case Study block.
 *
 * @package MadeoFeaturedCaseStudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return a safe layout value.
 *
 * @param mixed $layout Raw layout value from ACF.
 * @return string
 */
function madeo_fcs_normalize_layout( $layout ) {
	$layout          = is_string( $layout ) ? trim( $layout ) : '';
	$allowed_layouts = array( 'image-left', 'image-top' );

	return in_array( $layout, $allowed_layouts, true ) ? $layout : 'image-left';
}

/**
 * Resolve the heading displayed by the block.
 *
 * @param mixed  $custom_heading Optional editor-provided heading.
 * @param string $post_title Selected post title fallback.
 * @return string
 */
function madeo_fcs_get_display_heading( $custom_heading, $post_title ) {
	$custom_heading = is_string( $custom_heading ) ? trim( $custom_heading ) : '';

	return '' !== $custom_heading ? $custom_heading : $post_title;
}
