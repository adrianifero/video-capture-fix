<?php
/**
 * [vidrack] tag template.
 *
 * @package wp-video-capture
 */

?>

<div class="wp-video-capture"
		 style="text-align: <?php echo esc_html( $align ) ?>;"
		 data-external-id="<?php echo esc_html( $ext_id ) ?>">

	<!-- Mobile Version -->
	<div class="wp-video-capture-mobile">
		<form class="wp-video-capture-mobile-form" method="post" action="https://storage.vidrack.com/video">
			<div class="wp-video-capture-progress-indicator-container">
				<div class="wp-video-capture-ajax-success-store"></div>
				<div class="wp-video-capture-ajax-success-upload"></div>
				<div class="wp-video-capture-ajax-error-store"></div>
				<div class="wp-video-capture-ajax-error-upload"></div>
				<div class="wp-video-capture-progress-container">
					<p><?php _e('Uploading...','video-capture');?></p>
					<progress class="wp-video-capture-progress" value="0" max="100"></progress>
					<div class="wp-video-capture-progress-text">
						<span>0</span>%
					</div>
				</div>
			</div>
			<div class="wp-video-capture-button-container">
				<div class="wp-video-capture-powered-by">
					<?php _e('Powered by','video-capture');?> <a href="http://vidrack.com" target="_blank">Vidrack</a>
				</div>
				<a href class="wp-video-capture-record-button-mobile needsclick"></a>
				<input class="wp-video-capture-file-selector" type="file" accept="video/*" capture="camcoder" />
				<a class="wp-video-capture-troubleshooting" href="http://vidrack.com/fix" target="_blank">
					<?php _e('Problems recording a video?','video-capture');?>
				</a>
			</div>
		</form>
	</div>

	<!-- Desktop Version -->
	<div class="wp-video-capture-desktop">
		<div class="wp-video-capture-flash-container" id="wp-video-capture-flash-block">
			<div id="wp-video-capture-flash">
				<p><?php _e('Your browser doesn\'t support Adobe Flash, sorry','video-capture');?>.</p>
			</div>
		</div>
		<div class="wp-video-capture-button-container">
			<a href data-mfp-src="#wp-video-capture-flash-block" class="wp-video-capture-record-button-desktop"><?php _e('Record Video','video-capture');?></a>
			<a class="wp-video-capture-troubleshooting" href="http://vidrack.com/fix/" target="_blank">
				<?php _e('Problems recording a video?','video-capture');?>
			</a>
		</div>
	</div>

</div>
