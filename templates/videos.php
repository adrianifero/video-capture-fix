<div class="wrap">

  <h2>Video Recorder</h2>
  <h4>Brought to you by <a href="http://vidrack.com" target="_blank">vidrack.com</a></h4>
  <h3>Recorded Videos</h3>

  <h4>Have trouble playing videos? Download <a href="http://www.videolan.org/" target="_blank">VLC media player</a>!</h4>
  <h4>Found a bug? <a href="mailto:info@vidrack.com" target="_blank">Report it</a>!</h4>

  <form id="videos-filter" method="get">
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
    <?php $video_list_table->display() ?>
  </form>

</div>
