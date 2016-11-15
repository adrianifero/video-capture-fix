<?php
/**
 * Plugin Name: Video Recorder
 * Plugin URI: http://vidrack.com
 * Description: Add a video camera to your website!
 * Version: 1.7.51a
 * Author: Vidrack.com
 * Author URI: http://vidrack.com
 * License: GPLv2 or later
 *
 * @package wp-video-capture
 */

if ( ! class_exists( 'WP_Video_Capture' ) ) {

	/**
	 * WP_Video_Capture main class.
	 */
	class WP_Video_Capture {

		/**
		 * Current plugin version.
		 * Changes manually with every upgrade.
		 *
		 * @var string $vidrack_version
		 */
		private static $vidrack_version = '1.7.5';

		/**
		 * Constructor.
		 */
		public function __construct() {
			// Initialize Settings.
			require_once plugin_dir_path( __FILE__ ) . 'settings.php';
			$wp_video_capture_settings = new WP_Video_Capture_Settings();

			// Initialize Mailer class.
			$site_url = wp_parse_url( site_url() );
			$this->hostname = $site_url['host'];
			require_once plugin_dir_path( __FILE__ ) . 'inc/class.video-capture-email.php';
			$this->video_capture_email = new Video_Capture_Email( $this->hostname );

			// Initialize Mobile Detect class.
			require_once plugin_dir_path( __FILE__ ) . 'inc/class.mobile-detect.php';
			$this->mobile_detect = new Mobile_Detect;

			// Initialize JS and CSS resources.
			add_action( 'wp_enqueue_scripts', array( &$this, 'register_resources' ) );

			// Initialize AJAX actions.
			add_action( 'wp_ajax_store_video_file', array( &$this, 'store_video_file' ) );
			add_action( 'wp_ajax_nopriv_store_video_file', array( &$this, 'store_video_file' ) );
			add_action( 'wp_ajax_validate_video_download_link', array( &$this, 'validate_video_download_link' ) );

			// On plugin init.
			add_action( 'init', array( &$this, 'plugin_init' ) );

			// Initialize shortcode.
			add_shortcode( 'vidrack', array( &$this, 'record_video' ) );
			// [record_video] is added for compatibility with previous versions.
			add_shortcode( 'record_video', array( &$this, 'record_video' ) );
		}

		/**
		 * Plugin initialization functions.
		 */
		public function plugin_init() {
			$this->create_post_type();
			$this->add_options();
			$this->update_check();
		}

		/**
		 * Create custom post type to store video information.
		 */
		private function create_post_type() {
			register_post_type( 'vidrack_video',
				array(
					'labels' => array(
						'name' => __( 'Videos', 'video-capture' ),
						'singular_name' => __( 'Video', 'video-capture' ),
						'menu_name' => __( 'Vidrack', 'video-capture' ),
						'name_admin_bar' => __( 'Vidrack', 'video-capture' ),
						'search_items' => __( 'Search Videos', 'video-capture' ),
						'not_found' => __( 'No videos found.', 'video-capture' ),
					),
					'capability_type' => 'post',
					'capabilities' => array(
						'create_posts' => false,
						'edit_published_posts' => false,
					),
					'map_meta_cap' => true,
					'public' => true,
					'supports' => false,
					'menu_position' => 13,
					'menu_icon' => plugins_url( 'images/icon_vidrack.png', __FILE__ ),
				)
			);

			add_filter( 'manage_edit-vidrack_video_columns', array( &$this, 'custom_columns' ) );
			add_action( 'manage_posts_custom_column', array( &$this, 'populate_custom_columns' ) );
			add_filter( 'post_row_actions', array( &$this, 'custom_row_actions' ) );
			add_action( 'before_delete_post', array( &$this, 'delete_video' ) );
		}

		/**
		 * Add options on init.
		 */
		private function add_options() {
			// Add settings options.
			// 'add_option' does nothing if option already exists.
			add_option( 'vidrack_registration_email' );
			add_option( 'vidrack_display_branding', 1 );
			add_option( 'vidrack_window_modal', 1 );
			add_option( 'vidrack_js_callback' );
			add_option( 'vidrack_version', self::$vidrack_version );

			// Add user config.
			add_user_meta( get_current_user_id(), '_wp-video-capture_hide_registration_notice', false, true );
		}

		/**
		 * Check for version update.
		 */
		private function update_check() {
			$installed_ver = get_option( 'vidrack_version' );

			if ( $installed_ver === self::$vidrack_version ) {
				return;
			}

			// [1.6] Remove old options.
			if ( version_compare( $installed_ver, '1.6', '<' ) ) {
				delete_option( 'registration_email' );
				delete_option( 'display_branding' );
			}

			// [1.7.1] Migrate videos table to custom posts and add JS callback option.
			if ( version_compare( $installed_ver, '1.7.1', '<' ) ) {
				global $wpdb;
				$table_name = $wpdb->prefix . 'video_capture';

				// Migrate data.
				$items = $wpdb->get_results( $wpdb->prepare( 'SELECT filename, ip, uploaded_at FROM  %s', $table_name ) ); // Db call ok.
				foreach ( $items as $item ) {
					$video = array(
						'post_type' => 'vidrack_video',
						'post_title' => $item->filename,
						'post_status' => 'publish',
						'post_date' => $item->uploaded_at,
					);
					$post_id = wp_insert_post( $video, true );
					add_post_meta( $post_id, '_vidrack_ip', $item->ip, true );
				}

				// Remove old database table.
				$wpdb->query( $wpdb->prepare( 'DROP TABLE IF EXISTS %s', $table_name ) ); // Db call ok.
			}

			// Bump up the version after successful update.
			update_option( 'vidrack_version', self::$vidrack_version );
		}

		/**
		 * Create custom columns.
		 *
		 * @param Array $columns existing columns.
		 * @return Array custom columns data list.
		 */
		public function custom_columns( $columns ) {
			return array(
				'cb' => $columns['cb'],
				'title' => __( 'Filename', 'video-capture' ),
				'vidrack_video_ip' => __( 'IP', 'video-capture' ),
				'vidrack_video_external_id' => __( 'External ID', 'video-capture' ),
				'date' => $columns['date'],
			);
		}

		/**
		 * Populate custom columns with metadata.
		 *
		 * @param string $column column.
		 */
		public function populate_custom_columns( $column ) {
			if ( 'vidrack_video_ip' === $column ) {
				echo esc_html( get_post_meta( get_the_ID(), '_vidrack_ip', true ) );
			} elseif ( 'vidrack_video_external_id' === $column ) {
				echo esc_html( get_post_meta( get_the_ID(), '_vidrack_external_id', true ) );
			}
		}

		/**
		 * Customize row actions from vidrack_video post type.
		 *
		 * @param Array $actions current actions.
		 * @return Array $actions updated current actions.
		 */
		public function custom_row_actions( $actions ) {
			global $current_screen;

			if ( 'vidrack_video' === $current_screen->post_type ) {
				unset( $actions['edit'] );
				unset( $actions['view'] );
				unset( $actions['inline hide-if-no-js'] );
				$actions['download'] =
					'<a href="http://vidrack-media.s3.amazonaws.com/' .
					get_post( get_the_ID() )->post_title .
					'" title="Download" class="download-video-link" rel="permalink" download>'.__('Download', 'video-capture').'</a>';
			}

			return $actions;
		}

		/**
		 * Remove video from S3 once it's deleted from Trash.
		 *
		 * @param string $post_id post id.
		 */
		public function delete_video( $post_id ) {
			global $post_type;
			if ( 'vidrack_video' !== $post_type ) {
				return;
			}

			$video = get_post( $post_id );
			$url = 'https://storage.vidrack.com/video/' . $video->post_title;
			$options = array( 'http' => array( 'method' => 'DELETE' ) );
			$context  = stream_context_create( $options );
			$result = file_get_contents( $url, false, $context );
		}

		/**
		 * Register JS and CSS resources.
		 */
		public function register_resources() {
			// JS.
			wp_register_script(
				'magnific-popup',
				plugin_dir_url( __FILE__ ) . 'lib/js/magnific-popup.min.js',
				array( 'jquery' ), '1.0.0'
			);
			wp_register_script(
				'swfobject',
				plugin_dir_url( __FILE__ ) . 'lib/js/swfobject.js',
				array(), '2.2'
			);
			wp_register_script(
				'record_video',
				plugin_dir_url( __FILE__ ) . 'js/record_video.js', array( 'jquery', 'magnific-popup', 'swfobject' ),
				self::$vidrack_version
			);

			// CSS.
			wp_register_style(
				'magnific-popup',
				plugin_dir_url( __FILE__ ) . 'lib/css/magnific-popup.css',
				array(), '1.0.0', 'screen'
			);
			wp_register_style(
				'record_video',
				plugin_dir_url( __FILE__ ) . 'css/record_video.css',
				array( 'magnific-popup' ), self::$vidrack_version
			);

			if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
				// Pass variables to the frontend.
				wp_localize_script(
					'record_video',
					'VideoCapture',
					array(
						'ajaxurl' => admin_url( 'admin-ajax.php' ),
						'ip' => esc_html( sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) ), // Input var OK.
						'site_name' => $this->hostname,
						'plugin_url' => plugin_dir_url( __FILE__ ),
						'display_branding' => get_option( 'vidrack_display_branding' ),
						'window_modal' => get_option( 'vidrack_window_modal' ),
						'mobile' => $this->mobile_detect->isMobile(),
						'js_callback' => get_option( 'vidrack_js_callback' ),
						'nonce' => wp_create_nonce( 'vidrack_nonce_secret' ),
					)
				);
			}
		}

		/**
		 * [vidrack] tag implementation.
		 *
		 * @param String $atts tag attributes (left, right, etc).
		 * @param String $content tag content (empty).
		 * @return Source $record_video_contents data buffer.
		 */
		public function record_video( $atts, $content = null ) {
			// Extract attributes.
			$atts = shortcode_atts( array( 'align' => 'left', 'ext_id' => null ), $atts );
			$align = $atts['align'];
			$ext_id = $atts['ext_id'];

			// Enable output buffering.
			ob_start();

			// Render template.
			wp_enqueue_style( 'record_video' );
			wp_enqueue_script( 'record_video' );
			include plugin_dir_path( __FILE__ ) . 'templates/record-video.php';

			// Return buffer.
			$record_video_contents = ob_get_contents();
			ob_end_clean();
			return $record_video_contents;
		}

		/**
		 * Process file uploading for mobile.
		 */
		public function store_video_file() {
			header( 'Content-Type: application/json' );

			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'vidrack_nonce_secret' ) ) { // Input var "nonce" is set?
				echo wp_json_encode( array( 'status' => 'error', 'message' => __('An error occurred.', 'video-capture') ) );
				die();
			}

			if ( ! isset( $_POST['filename'] ) ) {
				echo wp_json_encode( array( 'status' => 'error', 'message' => __('Filename is not set.', 'video-capture') ) );
				die();
			}

			if ( ! isset( $_POST['ip'] ) or ! filter_var( wp_unslash( $_POST['ip'] ), FILTER_VALIDATE_IP ) ) {
				echo wp_json_encode( array( 'status' => 'error', 'message' => __('IP address is not set.', 'video-capture') ) );
				die();
			}

			// Insert new video info into the DB.
			$video = array(
				'post_type' => 'vidrack_video',
				'post_title' => sanitize_text_field( wp_unslash( $_POST['filename'] ) ),
				'post_status' => 'publish',
			);
			$post_id = wp_insert_post( $video, true );
			if ( is_wp_error( $post_id ) ) {
				echo wp_json_encode( array( 'status' => 'error', 'message' => $post_id->get_error_message() ) );
			}
			$r1 = add_post_meta( $post_id, '_vidrack_ip', sanitize_text_field( wp_unslash( $_POST['ip'] ), true ) ); // Input var "ip" is set.
			if ( isset( $_POST['external_id'] ) ) {
				$r2 = add_post_meta( $post_id, '_vidrack_external_id', sanitize_text_field( wp_unslash( $_POST['external_id'] ), true ) ); // Input var "external_id" is set.
			} else {
				$r2 = true;
			}

			if ( ! $r1 || ! $r2 ) {
				echo wp_json_encode( array( 'status' => 'error', 'message' => __('Cannot add post attributes.', 'video-capture') ) );
			} else {
				// Send email notification.
				if ( $to = get_option( 'vidrack_registration_email' ) ) {
					$this->video_capture_email->send_new_video_email( $to,  sanitize_text_field( wp_unslash( $_POST['filename'] ) ) ); // Input var "filename" is set.
				}

				echo wp_json_encode( array( 'status' => 'success', 'message' => __('Done!', 'video-capture') ) );
			}

			die();
		}

		/**
		 * Check if the video is actually on S3.
		 */
		public function validate_video_download_link() {

			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'vidrack_nonce_secret' ) ) { // Input var "nonce" is set?
				echo wp_json_encode( array( 'status' => 'error' ) );
				die;
			}

			if ( isset( $_POST['video_link'] ) ) {
				$video_link = esc_url_raw( wp_unslash( $_POST['video_link'] ) );
				$headers_response = get_headers( $video_link, 1 );

				if ( 'HTTP/1.1 200 OK' === $headers_response[0]  ) {
					echo wp_json_encode( array( 'status' => 'success' ) );
					die;
				} else {
					echo wp_json_encode( array( 'status' => 'error' ) );
					die;
				}
			} else {
				return;
			}
		}
	}
}

if ( class_exists( 'WP_Video_Capture' ) ) {
	// Instantiate the plugin class.
	$wp_video_capture = new WP_Video_Capture();

	// Add a link to the settings page onto the plugin page.
	if ( isset( $wp_video_capture ) ) {
		/**
		 * Add Settings link to the Plugins page.
		 *
		 * @param Array $links list of current links on the Plugins page.
		 * @return Array $links updated links.
		 */
		function plugin_settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=wp_video_capture_settings">'.__('Settings','video-capture').'</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}

		$plugin = plugin_basename( __FILE__ );
		add_filter( "plugin_action_links_$plugin", 'plugin_settings_link' );

		function plugin_localization () {
			load_plugin_textdomain( 'video-capture', false, dirname( plugin_basename( __FILE__ ) ).'/language' ); 
			
		}
		add_action( "plugins_loaded", 'plugin_localization' );

		/**
		 * Add additional links to the Plugins page.
		 *
		 * @param Array  $links list of current links on the Plugins list page.
		 * @param String $plugin_file current modified.
		 * @return Array $links updated links.
		 */
		function plugin_row_additional_links( $links, $plugin_file ) {
			$file = plugin_basename( __FILE__ );
			if ( $plugin_file === $file ) {
					$additional_links = array(
							'install' => '<a href="http://vidrack.com/product/install/" target="_blank">'.__('Pro Installation', 'video-capture').'</a>',
							'webapp'  => '<a href="http://vidrack.me/account/signup/" target="_blank">'.__('Try Vidrack Web App', 'video-capture').'</a>',
							'shop'    => '<a href="http://vidrack.com/shop/" target="_blank">'.__('Shop', 'video-capture').'</a>',
							'invest'  => '<a href="http://vidrack.com/invest/" target="_blank">'.__('Invest', 'video-capture').'</a>',
							'donate'  => '<a href="http://vidrack.com/donate/" target="_blank">'.__('Donate', 'video-capture').'</a>',
					);
					$new_links = array_merge( $links, $additional_links );
					return $new_links;
			}
			return $links;
		}
		add_filter( 'plugin_row_meta', 'plugin_row_additional_links', 10, 2 );


		// Add resource data for admin page.
		add_action( 'admin_enqueue_scripts', 'register_resource_video_page' );

		/**
		 * Add the resource to video list page.
		 */
		function register_resource_video_page() {
			global $post;
			if ( 'vidrack_video' === $post->post_type ) {
				wp_enqueue_script( 'download_video', plugin_dir_url( __FILE__ ) . 'js/download_video.js' );
				wp_localize_script(
					'download_video',
					'VideoDownload',
					array(
						'ajaxurl' => admin_url( 'admin-ajax.php' ),
						'nonce' => wp_create_nonce( 'vidrack_nonce_secret' ),
					)
				);
			}
		}
	}
}

?>
