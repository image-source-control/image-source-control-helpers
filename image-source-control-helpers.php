<?php
/**
 * Image Source Control – Helper Functions
 *
 * Plugin Name: Image Source Control – Helper Functions
 * Version:     1.0
 * Plugin URI:  https://imagesourcecontrol.com/
 * Description: Helper functions for the Image Source Control plugins
 * Author:      Team Image Source Control
 * Author URI:  https://imagesourcecontrol.com/
 * Text Domain: image-source-control-helper
 * License:     GPL v3
 * Requires at least: 6.0
 * Requires PHP: 7.2
 *
 * zip this file and then install it as a normal WordPress plugin.
 * Uncomment the hooks you want to use.
 */

/**
 * Log changes to post meta values made by ISC
 */
function isc_helpers_log_update_post_meta( $post_id, $key, $value ) {

	// only log the `isc_image_source` meta key
	if ( $key !== 'isc_image_source' ) {
		return;
	}

	$old_value = get_post_meta( $post_id, $key, true );

	// only log when a value changes
	if ( $value === $old_value ) {
		return;
	}

	$message = "ISC changes meta value `$key` for ID $post_id from `" . print_r( $old_value, true ) . "` into `$value`\n";

	// if the new value is empty, log the backtrace. This can be a lot of information and timeout when sending emails
	/*if ( $value == "" ) {
		$message .= print_r( debug_backtrace(), true );
	}*/

	// uncomment to send an email
	wp_mail( 'me@example.com', 'ISC data change: ' . $key, $message );

	// uncomment to log to `wp-content/debug.log`. WP_DEBUG_LOG needs to be true
	error_log( $message );
};
add_action( 'isc_update_post_meta', 'isc_helpers_log_update_post_meta', 10, 3 );

/**
 * Log changes to isc_image_source meta value made by any plugin
 *
 * @param int    $meta_id ID of the metadata entry to update.
 * @param int    $post_id ID of the object metadata is for.
 * @param string $key     Metadata key.
 * @param mixed  $value   Metadata value.
 */
function isc_helpers_log_update_isc_image_source_post_meta( $meta_id, $post_id, $key, $value ) {

	// only log the `isc_image_source` meta key
	if ( $key !== 'isc_image_source' ) {
		return;
	}

	$old_value = get_post_meta( $post_id, $key, true );

	// only log when a value changes
	if ( $value === $old_value ) {
		return;
	}

	$message = "Some code changes meta value `$key` for ID $post_id from `" . print_r( $old_value, true ) . "` into `$value`";

	// uncomment to send an email
	wp_mail( 'me@example.com', 'Meta data change: ' . $key, $message );

	// uncomment to log to `wp-content/debug.log`. WP_DEBUG_LOG needs to be true
	error_log( $message );
};
add_action( 'update_post_meta', 'isc_helpers_log_update_isc_image_source_post_meta', 10, 4 );

/**
 * Change the caption markup into an icon that extends on hover
 *
 * @param string $source_html HTML of the image source overlay.
 *
 * @return string
 */
function isc_helpers_change_caption_markup( string $source_html ): string {

	return sprintf(
		'<span class="isc-source-text"><i class="isc-source-text-icon">©</i><span>%s</span></span>',
		// remove existing HTML wrapper and only keep links
		wp_kses(
			$source_html,
			[
				'a' => [
					'href'   => [],
					'target' => [],
					'rel'    => [],
				],
			]
		)
	);
}
add_filter( 'isc_overlay_html_source', 'isc_helpers_change_caption_markup' );

/**
 * Add custom CSS to site header to show the caption when hovering over the icon
 */
function isc_helpers_change_caption_markup_header_css() {
	echo '<style>
        .isc-source-text {
            position: relative;
            display: inline-block;
            cursor: help;
        }

        .isc-source-text .isc-source-text-icon {
            font-size: 2em;
        }

        .isc-source-text > span {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            padding: 5px;
            border: 1px solid #ccc;
        }

        .isc-source-text:hover > span {
            visibility: visible;
            opacity: 1;
        }
    </style>';
}
add_action('wp_head', 'isc_helpers_change_caption_markup_header_css');

/**
 * Disable all ISC functionality for non-post pages
 */
function isc_helpers_disable_isc_on_non_posts( $post_ids ) {
	if ( ! is_single() ) {
		$post_ids[] = get_the_ID();
	}
	return $post_ids;
}
add_filter( 'isc_public_excluded_post_ids', 'isc_helpers_disable_isc_on_non_posts' );