<?php
/**
 * Uninstall script.
 *
 * @package wp-video-capture
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Remove custom options.
delete_option( 'vidrack_registration_email' );
delete_option( 'vidrack_display_branding' );
delete_option( 'vidrack_window_modal' );
delete_option( 'vidrack_js_callback' );
delete_option( 'vidrack_version' );

// Remove hide notice setting.
delete_user_meta( get_current_user_id(), '_wp-video-capture_hide_registration_notice' );

?>
