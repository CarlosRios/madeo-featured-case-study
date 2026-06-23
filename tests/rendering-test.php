<?php
/**
 * Lightweight regression tests for rendering helpers.
 *
 * Run from the plugin root with:
 * php tests/rendering-test.php
 */

define( 'ABSPATH', dirname( __DIR__, 5 ) . '/' );

require_once __DIR__ . '/../inc/rendering.php';

function madeo_assert_same( $expected, $actual, $message ) {
	if ( $expected !== $actual ) {
		fwrite( STDERR, "FAIL: {$message}\nExpected: " . var_export( $expected, true ) . "\nActual: " . var_export( $actual, true ) . "\n" );
		exit( 1 );
	}
}

madeo_assert_same( 'image-left', madeo_fcs_normalize_layout( 'image-left' ), 'accepts image-left layout' );
madeo_assert_same( 'image-top', madeo_fcs_normalize_layout( 'image-top' ), 'accepts image-top layout' );
madeo_assert_same( 'image-left', madeo_fcs_normalize_layout( 'unexpected-value' ), 'falls back to image-left for invalid layout' );
madeo_assert_same( 'image-left', madeo_fcs_normalize_layout( '' ), 'falls back to image-left for empty layout' );

madeo_assert_same(
	'Featured Case Study',
	madeo_fcs_get_display_heading( '  Featured Case Study  ', 'Original Post Title' ),
	'uses trimmed custom heading when provided'
);
madeo_assert_same(
	'Original Post Title',
	madeo_fcs_get_display_heading( '', 'Original Post Title' ),
	'falls back to post title when custom heading is empty'
);

echo "All rendering helper tests passed.\n";
