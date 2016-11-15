<?php
/**
 * Email library.
 *
 * @package wp-video-capture
 */

/**
 * Video_Capture_Email class.
 */
class Video_Capture_Email {

	/**
	 * Constructor.
	 *
	 * @param string $hostname WP installation host name.
	 */
	public function __construct( $hostname ) {

		$this->hostname = $hostname;

		// Email headers.
		$this->headers[] = 'MIME-Version: 1.0';
		$this->headers[] = 'Content-type: text/html; charset=utf-8';
		$this->headers[] = 'From: Video Recorder Plugin <vidrack@' . preg_replace( '/^www\./', '', $hostname ) . '>';
		$this->headers[] = 'Reply-to: Vidrack <info@vidrack.com>';

	}

	/**
	 * Registers user in Sendly.
	 *
	 * @param string $registration_email user email.
	 */
	public function register_user( $registration_email ) {
		$sendy_url = 'http://newsletter.vidrack.net/subscribe';
		$sendy_list_id = 'ze38TC4UFzcvn59eBaV1Xg';
		$sendy_data = array(
			'email' => $registration_email,
			'list'  => $sendy_list_id,
		);

		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query( $sendy_data ),
			),
		);
		$context  = stream_context_create( $options );
		$result = file_get_contents( $sendy_url, false, $context );
	}

	/**
	 * Notifies user about newly uploaded video.
	 *
	 * @param string $to user email.
	 * @param string $filename video name.
	 */
	public function send_new_video_email( $to, $filename ) {
		wp_mail(
			$to,
			'New video recorded at ' . $this->hostname . ' website',
			'
			<p>Hello,<br/>
			<br/>
			You have a new video at ' . $this->hostname . '!<br/>
			<a href="http://vidrack-media.s3.amazonaws.com/' . $filename . '" download>Click here to download</a><br/>
			<br/>
			<p>Have trouble playing videos? Download <a href="http://www.videolan.org/" target="_blank">VLC media player</a>!</p>
			<br/>
			Kind regards,<br/>
			Vidrack Team<br/>
			<br/>
			<a href="http://vidrack.com" target="_blank">vidrack.com</a>
			',
			$this->headers
		);
	}
}

?>
