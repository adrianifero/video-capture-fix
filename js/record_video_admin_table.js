jQuery(function() {

  // Ask if we really want to delete a video
  // Then contact the server and perform actual delete operation
  jQuery('.wp-video-capture-delete-video').click(function() {
    if (!confirm('Do you really want to delete this video? This cannot be undone.')) {
      return false;
    }

    // Don't update DB by default
    var result = false;

    var video = jQuery(this).parent().parent().parent().find('span.vidrack-filename').html();
    console.log('Deleting video "' + video + '"...');
    jQuery.ajax({
      url: 'http://storage.vidrack.com/video/' + video,
      type: 'DELETE',
      async: false,

      error: function(jqXHR, textStatus) {
        alert('Error deleting video "' + video + '": ' + textStatus);
      },

      success: function(data, textStatus, jqXHR) {
        if (data.status === 'success') {
          console.log('Video "' + video + '" has been successfully deleted');
          result = true;
        } else {
          alert('Error deleting video "' + video + '": ' + data.message);
        }
      }
    });

    return result;
  });

  // Show popup with Terms and Conditions
  jQuery('.wp-video-capture-tnc-link').click(function() {
    window.open(
      'http://vidrack.com/terms-conditions/',
      'wp-video-capture-terms-and-conditions',
      'height=700,width=700,center=true,scrollbars=1,HorizontalAlignment=Center,VerticalAlignment=Center'
    );
  });
});
