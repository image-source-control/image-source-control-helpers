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

	$message = "ISC changes meta value `$key` for ID $post_id from `" . print_r( $old_value, true ) . "` into `$value`";

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