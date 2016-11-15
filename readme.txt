=== Video Recorder ===
Contributors: vincent-stark
Author URI: http://vidrack.com
Plugin URI: http://vidrack.com
Tags: crowdsourced video, fan video, video recorder, user generated video, video comments, video interview, video testimonial, video review, YouTube, webcam, webcam recorder, video submission, collect video, video post, video, recorder, user generated content, record video, video widget, video content, content, video hosting, embed video, crowdsource, crowdsourced content, video posts, video pages, video blog, vlog, user video
Requires at least: 3.4
Tested up to: 4.5.2
Stable tag: 1.7.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Build Your Community with Fan Videos. Collect and share crowdsourced video with Vidrack web-based
video recorder.

== Description ==
Your biggest fans can record and submit videos on your website.

Our video recorder will allow you to put a video record button anywhere on your website. Whenever
someone clicks the record button it will activate a webcam or mobile camera. Site visitors can
record any type of fan videos, crowdsourced videos or user generated videos. These could be video
testimonials, video interviews, video contests, video auditions, video reviews, video feedback,
YouTube fan videos and much more.

* Video submissions are collected privately.
* Video submissions are accessed in your websites WordPress Admin Panel.
* You can download the original video files from your dashboard.
* Videos can be shared on social media, YouTube or used internally.

For large commercial campaigns, enterprise or customization of our product please go to [Vidrack
Enterprise](http://vidrack.com/enterprise).

For more information, please check out the [User Guide](http://vidrack.com/WordPress-guide/) or
[contact us](http://vidrack.com/contact/). Troubleshooting at (http://vidrack.com/fix).

= Using Vidrack =

Install Vidrack Video Recorder as you would any other plugin.

Just add one of the following shortcodes to a page or post:
`[vidrack align="left"]`
`[vidrack align="right"]`
`[vidrack align="center"]`
or just
`[vidrack]`

All your fans have to do is click on the "video record" button and the video recorder will open
their webcam or mobile camera.

Its important to put clear instructions to your website visitors on what kind of video you want
them to record. If your page doesn't specify what you want from people then no one will record
video.

== 3rd Party Integration ==

Use `ext_id` parameter, which is passed all the way to the DB:
`[vidrack ext_id="123"]`

Use `JavaScript Callback Function` option to send custom callbacks. For example,
if you enter `test_func`, then plugin calls `test_func(filename, ip, ext_id)` upon
successful video upload.

= Downloading Videos =

Downloading videos is quick and easy.

Simply login into your admin area of your WordPress site. Click on the tab in the left hand menu,
called 'Vidrack'. This is where you will access your videos. On this page you see a list of all the
videos that have been submitted to you. Depending on your browser you can either click on the link
to download the video or right click on the link to download it. Once you have downloaded the video
you can delete the video, off the server, by clicking the box next to the link and clicking the
delete button on the bottom.

== Installation ==

Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Why should I use Vidrack? =

Crowdsourced Video is some of the most powerful and engaging content. Vidrack increases website
engagement and/or video interviewing/reporting efficiency within any organization. Video
testimonials, video reviews, video opinions, video feedback, video contests, video interviews and
fan videos are all great content for your website. The problem is its hard to collect unless you
use a web-based video recorder. And we believe Vidrack is the easiest and most economical of any
browser-based video recorder.

= Does Vidrack Video Recorder work on all WordPress Sites? =

Yes it works on all WordPress sites that meet WordPress best practices. Sometimes Vidrack has
trouble when a site is improperly configured.

= Does Vidrack work on all devices? =

Vidrack works on all devices that are newer that 2 years old using a good Internet or mobile
connections. For desktop devices Adobe Flash must be installed.

= Why does Vidrack use Adobe Flash? =

Due to the nature of desktop browser recording we must use flash for desktop recordings. Mobile
submissions are done using HTML5. As time goes on, we hope to eventually remove the need for Flash.

= Does Vidrack work on all browsers? =

Vidrack works on Chrome, Firefox, Edge and other mainstream browsers that follow common browser
standards. For lesser known browsers we sometimes experience issues beyond our control. If your
browser isn't following web standards then it won't work.

= Why is Vidrack free? =

We think web-based video recording should be free. Just like water, food and communication should
be free. But we do have bills to pay. Soon we will be asking larger organizations to pay for their
video campaigns. We're also working hard to release a premium paid video recorder with value added
features

= Where are the videos stored? =

Vidrack has a secure Amazon server where videos are stored. This prevents strain on your website
hosting resources. Most WordPress hosting services aren't built for storing videos.

= What should I do if the plugin doesn't work with my theme or conflicts with other plugins? =

The reality of WordPress plugins is sometimes they have plugin conflicts or theme conflicts.

The only two solutions to this are change themes, find the offending plugin by deactivating each
one individually or you can try our web-application available at http://vidrack.me and embed our
recorder on your WordPress site. For more info on embedding see http://vidrack.com/embed. Note that
if your use our web app you must access your videos at http://vidrack.me

= What if I want to use Vidrack but don't want it on my website? =

You can make a Video Recorder Landing Page with our web application. It will give you a branded
Vidrack URL you can submit videos through. For more info see http://vidrack.com/pages

= What are the limitations of web-based video recording? =

Users submitting video with mobile devices that are over 2 years old, have poor Internet
connections and/or uncommon browsers may experience video submission issues. The best solution is
try a different device, different Internet connection and different browser. Read
http://vidrack.com/fix

== Screenshots ==

1. Video Recorder in action (button)
2. Video Recorder in action (capture)
3. List of captured videos
4. Settings

== Changelog ==

= 1.7.5 =
* Fix JS callback filename issue.
* Show a nicer message if download link is invalid.
* Code refactoring and cleanup. Improved security.
* UI is more consistent across different themes.

= 1.7.4 =
* Better error messages.
* Compatibility with FastClick JS plugin.

= 1.7.3 =
* SSL support for video uploading.

= 1.7.2 =
* Improve DB schema migration.

= 1.7.1 =
* External ID parameter for 3rd party integration.
* Javascript callback function.
* Change data schema to use standard WP tables instead of custom ones.

= 1.7 =
* Brand new version of Flash recorder.
* Troubleshooting link.
* Lots of code cleanup and improvements.

= 1.6.3 =
* Improve record button appearance.
* Remove T&C link.
* Improve compatibility with the latest WP.
* Improve mobile flow.
* Improve progress bar.

= 1.6.2 =
* Improve mobile detection.
* Player pops up in a modal window by default.
* Small fixes and improvements.

= 1.6.1 =
* Fix jQuery-related bug.
* Rename plugin to 'Video Capture'

= 1.6 =
* Improve captured video quality.
* Improve Flash recorder.
* Move to a much better video hosting infrastructure.

= 1.5.4 =
* Rollback to F4V format for better browser compatibility.

= 1.5.3 =
* Solve a minor video naming problem.

= 1.5.2 =
* Improve compatibility with 32 bit PHP.

= 1.5.1 =
* Improve filename handling.

= 1.5 =
* Update the branding.
* Minor fix to filename handling.

= 1.4.2 =
* Minor fix to the video handling code for high-traffic websites.
* WordPress 4.1 support.

= 1.4.1 =
* Improved video recording quality.
* WordPress 4.0 support.

= 1.4 =
* Improved Flash Recorder.
* Stability fixes.

= 1.3.3 =
* Minor fixes & improvements.

= 1.3.2 =
* Fixed bug that caused Record button be not visible sometimes.

= 1.3.1 =
* Stability & compatibility improvements.

= 1.3 =
* Fully reworked code for better stability & performance.

= 1.2 =
* Added mobile support.
* Implemented better CDN integration.

= 1.0 =
* First official release. Got everyone excited!

== Upgrade Notice ==

= 1.7.5 =
UI improvements and fixes.

= 1.7.4 =
Better error messages. Several small fixes.

= 1.7.3 =
SSL support for video uploading.

= 1.7.2 =
Improve DB schema migration.

= 1.7.1 =
External ID parameter and JS callback support for 3rd party integration.

= 1.7 =
Brand new version of Flash recorder. Lots of internal improvements.

= 1.6.3 =
Improved Record button appearance.

= 1.6.2 =
Player pops up in a modal window by default. Some other small fixes and improvements.

= 1.6.1 =
Fix the problem with jQuery on some of the websites.

= 1.6 =
Much better video quality and tons of other great changes. Update is highly recommended.

= 1.5.4 =
Rollback to F4V format for better browser compatibility.

= 1.5.3 =
Solve a minor video naming problem.

= 1.5.2 =
Update is highly recommended if you run PHP on 32 bit OS.

= 1.5.1 =
Improve filename handling.

= 1.5 =
Branding update. Minor fix to the filename handling.

= 1.4.2 =
Minor fix to the video handling code for high-traffic websites.

= 1.4.1 =
Improved video recording quality. WordPress 4.0 support. Upgrade is highly recommended.

= 1.4 =
Improved Flash Recorder. Upgrade is highly recommended.

= 1.3.3 =
Minor fixes. Upgrade is recommended.

= 1.3.2 =
Minor layout fixes. Upgrade is recommended.

= 1.3.1 =
Multiple fixes & improvements. Upgrade is recommended.

= 1.3 =
Completely revamped version. Upgrade is highly recommended for better
stability & compatibility.

= 1.2 =
Mobile support is finally here!

= 1.0 =
The first official release.
