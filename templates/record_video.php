<div style="text-align: <?php echo $align ?>;">

  <!-- Mobile Version -->
  <div class="wp-video-capture-mobile">
    <form class="wp-video-capture-mobile-form" method="post" action="http://storage.vidrack.com/video">
      <div class="wp-video-capture-ajax-success-store"></div>
      <div class="wp-video-capture-ajax-success-upload"></div>
      <div class="wp-video-capture-ajax-error-store"></div>
      <div class="wp-video-capture-ajax-error-upload"></div>
      <div class="wp-video-capture-progress-container">
        <p>Uploading...</p>
        <progress class="wp-video-capture-progress" value="0" max="100"></progress>
        <div class="wp-video-capture-progress-text">
          <span>0</span>%
        </div>
      </div>
      <a href class="wp-video-capture-record-button-mobile"></a>
      <input class="wp-video-capture-file-selector" type="file" accept="video/*" capture="camera" />
    </form>
  </div>

  <!-- Desktop Version -->
  <div class="wp-video-capture-desktop">
    <div class="wp-video-capture-flash-container" id="wp-video-capture-flash-block">
      <div id="wp-video-capture-flash">
        <p>Your browser doesn't support Adobe Flash, sorry...</p>
      </div>
    </div>
    <a href data-mfp-src="#wp-video-capture-flash-block" class="wp-video-capture-record-button-desktop"></a>
  </div>

</div>
