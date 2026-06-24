<?php
/**
 * Render template for the Featured Case Study ACF block.
 *
 * @package MadeoFeaturedCaseStudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$selected_item  = function_exists( 'get_field' ) ? get_field( 'madeo_fcs_selected_item' ) : null;
$layout         = function_exists( 'get_field' ) ? get_field( 'madeo_fcs_layout' ) : 'image-left';
$custom_heading = function_exists( 'get_field' ) ? get_field( 'madeo_fcs_custom_heading' ) : '';

$selected_post = null;

if ( $selected_item instanceof WP_Post ) {
	$selected_post = $selected_item;
} elseif ( is_numeric( $selected_item ) ) {
	$selected_post = get_post( absint( $selected_item ) );
}

$layout      = madeo_fcs_normalize_layout( $layout );
$anchor      = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
$class_names = array(
	'madeo-featured-case-study',
	'madeo-featured-case-study--' . $layout,
);

if ( ! empty( $block['className'] ) ) {
	$class_names[] = sanitize_html_class( $block['className'] );
}

if ( ! empty( $block['align'] ) ) {
	$class_names[] = 'align' . sanitize_html_class( $block['align'] );
}

$is_preview = ! empty( $is_preview );
$is_valid   = $selected_post instanceof WP_Post && 'publish' === get_post_status( $selected_post );
?>
<section<?php echo $anchor; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="<?php echo esc_attr( implode( ' ', array_filter( $class_names ) ) ); ?>">
	<?php if ( ! $is_valid ) : ?>
		<div class="madeo-featured-case-study__inner">
			<p class="madeo-featured-case-study__notice">
				<?php
				echo esc_html(
					$is_preview
						? 'Select a published case study or post to preview the Featured Case Study block.'
						: 'Featured case study unavailable.'
				);
				?>
			</p>
		</div>
	<?php else : ?>
		<?php
		$post_id = $selected_post->ID;
		$title   = get_the_title( $post_id );
		$heading = madeo_fcs_get_display_heading( $custom_heading, $title );
		$link    = get_permalink( $post_id );
		$excerpt = has_excerpt( $post_id ) ? get_the_excerpt( $post_id ) : wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ), 28 );
		?>
		<div class="madeo-featured-case-study__inner">
			<?php if ( has_post_thumbnail( $post_id ) ) : ?>
				<figure class="madeo-featured-case-study__media">
					<a href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr( sprintf( 'Read %s', $title ) ); ?>">
						<?php
						echo get_the_post_thumbnail(
							$post_id,
							'large',
							array(
								'class' => 'madeo-featured-case-study__image',
								'alt'   => esc_attr( $title ),
							)
						);
						?>
					</a>
				</figure>
			<?php endif; ?>

			<div class="madeo-featured-case-study__content">
				<?php
				$post_type_object = get_post_type_object( get_post_type( $post_id ) );
				$post_type_label  = $post_type_object ? $post_type_object->labels->singular_name : 'Case Study';
				?>
				<p class="madeo-featured-case-study__eyebrow"><?php echo esc_html( $post_type_label ); ?></p>
				<h2 class="madeo-featured-case-study__title">
					<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $heading ); ?></a>
				</h2>

				<?php if ( '' !== $excerpt ) : ?>
					<p class="madeo-featured-case-study__excerpt"><?php echo esc_html( $excerpt ); ?></p>
				<?php endif; ?>

				<a class="madeo-featured-case-study__link" href="<?php echo esc_url( $link ); ?>">
					<?php echo esc_html__( 'Read case study', 'madeo-featured-case-study' ); ?>
				</a>
			</div>
		</div>
	<?php endif; ?>
</section>
