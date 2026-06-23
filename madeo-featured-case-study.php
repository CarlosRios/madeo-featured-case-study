<?php
/**
 * Plugin Name: Madeo Featured Case Study
 * Description: Registers a clean ACF-powered Gutenberg block for featuring a selected case study or post.
 * Version: 0.1.0
 * Author: Carlos Rios
 * Text Domain: madeo-featured-case-study
 * Requires at least: 6.5
 * Requires PHP: 8.1
 *
 * @package MadeoFeaturedCaseStudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MADEO_FCS_PLUGIN_FILE', __FILE__ );
define( 'MADEO_FCS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MADEO_FCS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once MADEO_FCS_PLUGIN_DIR . 'inc/rendering.php';

/**
 * Register the Case Study post type in PHP.
 */
function madeo_fcs_register_case_study_cpt() {
	$labels = array(
		'name'               => __( 'Case Studies', 'madeo-featured-case-study' ),
		'singular_name'      => __( 'Case Study', 'madeo-featured-case-study' ),
		'menu_name'          => __( 'Case Studies', 'madeo-featured-case-study' ),
		'name_admin_bar'     => __( 'Case Study', 'madeo-featured-case-study' ),
		'add_new_item'       => __( 'Add New Case Study', 'madeo-featured-case-study' ),
		'edit_item'          => __( 'Edit Case Study', 'madeo-featured-case-study' ),
		'new_item'           => __( 'New Case Study', 'madeo-featured-case-study' ),
		'view_item'          => __( 'View Case Study', 'madeo-featured-case-study' ),
		'search_items'       => __( 'Search Case Studies', 'madeo-featured-case-study' ),
		'not_found'          => __( 'No case studies found.', 'madeo-featured-case-study' ),
		'not_found_in_trash' => __( 'No case studies found in Trash.', 'madeo-featured-case-study' ),
	);

	register_post_type(
		'case_study',
		array(
			'labels'       => $labels,
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-portfolio',
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'case-studies' ),
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		)
	);
}
add_action( 'init', 'madeo_fcs_register_case_study_cpt' );

/**
 * Flush rewrite rules when activating the plugin.
 */
function madeo_fcs_activate() {
	madeo_fcs_register_case_study_cpt();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'madeo_fcs_activate' );

/**
 * Flush rewrite rules when deactivating the plugin.
 */
function madeo_fcs_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'madeo_fcs_deactivate' );

/**
 * Show a clear notice when ACF Pro is not active.
 */
function madeo_fcs_acf_missing_notice() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html__( 'Madeo Featured Case Study requires ACF Pro to register its Gutenberg block. Activate ACF Pro to use the block.', 'madeo-featured-case-study' )
	);
}
add_action( 'admin_notices', 'madeo_fcs_acf_missing_notice' );

/**
 * Register the ACF block and local fields.
 */
function madeo_fcs_register_acf_block() {
	if ( ! function_exists( 'acf_register_block_type' ) || ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	register_block_type( MADEO_FCS_PLUGIN_DIR . 'blocks/featured-case-study' );

	acf_add_local_field_group(
		array(
			'key'      => 'group_madeo_featured_case_study',
			'title'    => __( 'Featured Case Study', 'madeo-featured-case-study' ),
			'fields'   => array(
				array(
					'key'           => 'field_madeo_fcs_selected_item',
					'label'         => __( 'Case Study or Post', 'madeo-featured-case-study' ),
					'name'          => 'madeo_fcs_selected_item',
					'type'          => 'post_object',
					'post_type'     => array( 'case_study', 'post' ),
					'post_status'   => array( 'publish' ),
					'return_format' => 'object',
					'ui'            => 1,
					'required'      => 1,
				),
				array(
					'key'           => 'field_madeo_fcs_layout',
					'label'         => __( 'Layout', 'madeo-featured-case-study' ),
					'name'          => 'madeo_fcs_layout',
					'type'          => 'button_group',
					'choices'       => array(
						'image-left' => __( 'Image left', 'madeo-featured-case-study' ),
						'image-top'  => __( 'Image top', 'madeo-featured-case-study' ),
					),
					'default_value' => 'image-left',
					'return_format' => 'value',
				),
				array(
					'key'           => 'field_madeo_fcs_custom_heading',
					'label'         => __( 'Custom Heading', 'madeo-featured-case-study' ),
					'name'          => 'madeo_fcs_custom_heading',
					'type'          => 'text',
					'instructions'  => __( 'Optional. Leave empty to use the selected item title.', 'madeo-featured-case-study' ),
					'required'      => 0,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/madeo-featured-case-study',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'madeo_fcs_register_acf_block' );
