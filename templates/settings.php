<?php
/**
 * Settings page template.
 *
 * @package wp-video-capture
 */

?>

<div class="wrap">
	<h2>Video Recorder</h2>
	<h4><?php _e('Brought to you by', 'video-capture');?> <a href="http://vidrack.com" target="_blank">vidrack.com</a></h4>
	<h4><?php _e('Have trouble playing videos? Download', 'video-capture');?> <a href="http://www.videolan.org/" target="_blank">VLC media player</a>!</h4>
	<h4><?php _e('Loved our plugin? Please', 'video-capture');?> <a href="http://vidrack.com/donate/" target="_blank"><?php _e('donate', 'video-capture');?></a>!</h4>
	<form method="post" action="options.php">
		<?php settings_errors( 'registration_email' ) ?>
		<?php settings_fields( 'wp_video_capture-group' ); ?>
		<?php do_settings_fields( 'wp_video_capture-group', 'wp_video_capture-section' ); ?>
		<?php do_settings_sections( 'wp_video_capture' ); ?>
		<?php submit_button(); ?>
	</form>

	<h2><?php _e('How to use', 'video-capture');?></h2>
	<p><?php _e('Add shortcode', 'video-capture');?> <strong>[vidrack]</strong> <?php _e('anywhere on the page', 'video-capture');?>.</p>
	<p><?php _e('It accepts the following parameters', 'video-capture');?>:</p>
	<ul>
		<li><?php _e('Align to the right', 'video-capture');?>: <strong>[vidrack align="right"]</strong></li>
		<li><?php _e('Align to the center', 'video-capture');?>: <strong>[vidrack align="center"]</strong></li>
		<li><?php _e('Align to the left', 'video-capture');?>: <strong>[vidrack align="left"]</strong></li>
		<li><?php _e('External ID for 3rd party integration', 'video-capture');?>: <strong>[vidrack ext_id="123"]</strong></li>
	</ul>

</div>
