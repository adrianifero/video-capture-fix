jQuery(function() {

  // Show popup with Terms and Conditions
  jQuery('.wp-video-capture-tnc-link').click(function() {
    window.open(
      'http://vidrack.com/terms-conditions/',
      'wp-video-capture-terms-and-conditions',
      'height=700,width=700,center=true,scrollbars=1,HorizontalAlignment=Center,VerticalAlignment=Center'
    );
  });

});
