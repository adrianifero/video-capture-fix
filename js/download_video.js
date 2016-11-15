/* global jQuery, console */
jQuery(function () {
    'use strict';

    var ajaxurl = VideoDownload.ajaxurl;
    var nonce = VideoDownload.nonce;

    // Add event listener of clicking download video link
    jQuery(".download-video-link").on("click", function(e){
        e.preventDefault();
        e.stopPropagation();

        var video_lick = jQuery(this).attr('href');

        // Is video on Amazon server?
        var is_videoSet = videoIsset(video_lick);
        if (video_lick && is_videoSet) {
            location.href = video_lick;
        }
        else {
            alert("An error occurred, please refresh the page and try again!");
        }
        return ;
    });


    function videoIsset(video_link) {
        var result;

        // Send AJAX request for checking being video
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            dataType: "json",
            async:false,
            data: {
                action: 'validate_video_download_link',
                video_link: video_link,
                nonce: nonce,
            },
            error: function () {
                result = false;
            },
            success: function (data) {
                if (data.status === 'success') {
                    result = true;
                } else {
                    result = false;
                }
            }
        });

        return result;
    }
});




