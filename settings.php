<?php
/**
 * Settings page.
 *
 * @package wp-video-capture
 */

if ( ! class_exists( 'WP_Video_Capture_Settings' ) ) {

	/**
	 * WP_Video_Capture_Settings class.
	 */
	class WP_Video_Capture_Settings {

		/**
		 * Constructor.
		 */
		public function __construct() {
			// Initialize Mailer.
			$site_url = wp_parse_url( site_url() );
			$this->hostname = $site_url['host'];
			require_once plugin_dir_path( __FILE__ ) . 'inc/class.video-capture-email.php';
			$this->video_capture_email = new Video_Capture_Email( $this->hostname );

			// Register actions.
			add_action( 'admin_init', array( &$this, 'hide_registration_notice' ) );
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_menu', array( &$this, 'add_menu' ) );
		}

		/**
		 * Validate email.
		 *
		 * @param string $email email.
		 * @return string $email, if correct $email param.
		 */
		public function validate_email( $email ) {
			if ( ! is_email( $email ) && '' !== $email ) {
				add_settings_error( 'vidrack_registration_email', 'video-capture-invalid-email', __('Please enter a correct email','video-capture') );
			} else {
				// Register user.
				$this->video_capture_email->register_user( $email );
				return $email;
			}
		}

		/**
		 * Send email upon registration.
		 */
		public function registration_email_notice() {
			printf(
				'<div class="update-nag"><p>%1$s <input type="button" class="button" value="%3$s" onclick="document.location.href=\'%2$s\';" /></div>',
				__('Please enter your email to get notifications about newly uploaded videos', 'video-capture'),
				esc_url( add_query_arg( 'wp-video-capture-nag', wp_create_nonce( 'wp-video-capture-nag' ) ) ),
				'Dismiss'
			);
		}

		/**
		 * Hide registration notice upon click.
		 */
		public function hide_registration_notice() {
			if ( ! isset( $_GET['wp-video-capture-nag'] ) ) {  // Input var "wp-video-capture-nag" is not set.
				return;
			}

			// Check nonce.
			check_admin_referer( 'wp-video-capture-nag', 'wp-video-capture-nag' );

			// Update user meta to indicate dismissed notice.
			update_user_meta( get_current_user_id(), '_wp-video-capture_hide_registration_notice', true );
		}

		/**
		 * Main settings init function.
		 */
		public function admin_init() {
			global $pagenow;

			// Display notification if not registered.
			if ( ! get_option( 'vidrack_registration_email' && isset( $_GET['page'] ) )  // Input var "page" is set.
				&& 'edit.php' === $pagenow
				&& 'wp_video_capture_settings' === $_GET['page'] // Input var "page" is set.
				&& ! get_user_meta( get_current_user_id(), '_wp-video-capture_hide_registration_notice', true ) ) {
				add_action( 'admin_notices', array( &$this, 'registration_email_notice' ) );
			}

			// Register and validate options.
			register_setting( 'wp_video_capture-group', 'vidrack_registration_email', array( &$this, 'validate_email' ) );
			register_setting( 'wp_video_capture-group', 'vidrack_js_callback' );
			register_setting( 'wp_video_capture-group', 'vidrack_display_branding' );
			register_setting( 'wp_video_capture-group', 'vidrack_window_modal' );

			// Add your settings section.
			add_settings_section(
				'wp_video_capture-section',
				'Settings',
				array( &$this, 'settings_section_wp_video_capture' ),
				'wp_video_capture'
			);

			// Add email setting.
			add_settings_field(
				'wp_video_capture-registration_email',
				'Notifications email',
				array( &$this, 'settings_field_input_text' ),
				'wp_video_capture',
				'wp_video_capture-section',
				array(
					'field' => 'vidrack_registration_email',
				)
			);

			// Add JS callback setting.
			add_settings_field(
				'wp_video_capture-js_callback',
				'JavaScript Callback Function',
				array( &$this, 'settings_field_input_text' ),
				'wp_video_capture',
				'wp_video_capture-section',
				array(
					'field' => 'vidrack_js_callback',
				)
			);

			// Add branding checkbox.
			add_settings_field(
				'wp_video_capture-display_branding',
				'Display branding',
				array( &$this, 'settings_field_input_checkbox' ),
				'wp_video_capture',
				'wp_video_capture-section',
				array(
					'field' => 'vidrack_display_branding',
				)
			);

			// Add window format checkbox.
			add_settings_field(
				'wp_video_capture-window_modal',
				'Display recorder in a modal window',
				array( &$this, 'settings_field_input_checkbox' ),
				'wp_video_capture',
				'wp_video_capture-section',
				array(
					'field' => 'vidrack_window_modal',
				)
			);
		}

		/**
		 * Notification settings popup text.
		 */
		public function settings_section_wp_video_capture() {
			echo __('Please enter your email to get notifications about newly uploaded videos', 'video-capture').'.<br/>'.__('By entering your email you automatically agree to the', 'video-capture').' <a class="wp-video-capture-tnc-link" target="_blank" href="http://vidrack.com/terms-conditions/">'.__('Terms and Conditions', 'video-capture').'</a>.';
		}

		/**
		 * <input> template for the Settings.
		 *
		 * @param Array $args arguments.
		 */
		public function settings_field_input_text( $args ) {
			$field = $args['field'];
			$value = get_option( $field );
			echo sprintf( '<input type="text" name="%s" id="%s" value="%s" />', esc_html( $field ), esc_html( $field ), esc_html( $value ) );
		}

		/**
		 * <input type="checkbox"> template for Settings.
		 *
		 * @param Array $args arguments.
		 */
		public function settings_field_input_checkbox( $args ) {
			$field = $args['field'];
			$value = get_option( $field );
			echo sprintf( '<input type="checkbox" name="%s" id="%s" value="1" %s/>', esc_html( $field ), esc_html( $field ), checked( $value, 1, '' ) );
		}

		/**
		 * Add menu items.
		 */
		public function add_menu() {
			// Settings.
			add_submenu_page(
				'edit.php?post_type=vidrack_video',
				'Vidrack - Settings',
				'Settings',
				'manage_options',
				'wp_video_capture_settings',
				array( &$this, 'plugin_settings_page' )
			);
		}

		/**
		 * Settings page init.
		 */
		public function plugin_settings_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html( __( 'You do not have sufficient permissions to access this page.', 'video-capture' ) ) );
			}

			// Add helper JS.
			wp_enqueue_script( 'record_video_admin_settings' );

			// Render the settings template.
			include plugin_dir_path( __FILE__ ) . 'templates/settings.php';
		}

		/**
		 * Videos page init.
		 */
		public function plugin_videos_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html( __( 'You do not have sufficient permissions to access this page.', 'video-capture' ) ) );
			}

			// Include WP table class.
			include plugin_dir_path( __FILE__ ) . 'inc/class.video-list-table.php';
			$video_list_table = new Video_List_Table();
			$video_list_table->prepare_items();

			// Render the videos template.
			include plugin_dir_path( __FILE__ ) . 'templates/videos.php';
		}
	}
}

?>
