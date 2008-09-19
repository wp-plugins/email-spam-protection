=== Plugin Name ===
Contributors: ah125i
Tags: email, spam
Requires at least: 2.6
Tested up to: 2.6.2
Stable tag: 1.2

This plugin will convert an email address to javascript to prevent spam emails.

== Description ==

The email spam protection will convert the shortcode [escapeemail email="email@address.com"] to 

eval(unescape('%64%6f%63%75%6d%65%6e%74%2e%77%72%69%74%65%28%27%3c%61
%20%68%72%65%66%3d%22%6d%61%69%6c%74%6f%3a%65%6d%61%69%6c%40%61%
64%64%72%65%73%73%2e%63%6f%6d%22%3e%65%6d%61%69%6c%20%61%74%20%61
%64%64%72%65%73%73%20%64%6f%74%20%63%6f%6d%3c
%2f%61%3e%27%29'))

which writes "email at address dot com" into your post or page.  

There is also an option for adding text similar to email [at] address [dot] com if javascript is not
available.

If you want to generate this code with out using this plugin (for a page template for example) 
go to <a href="http://blueberryware.net/email/">Email Spam Protection Code Creator</a>

This will also escape emails used in comments if they use this shortcode. It will ONLY enable this shortcode for comments for security purposes.

== Installation ==

1. Upload `email-escape` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the shortcode [escapeemail email="email@address.com"] in your posts or pages

== Frequently Asked Questions ==

= What if javascript is disabled? =

Nothing will appear, however, it is highly unlikely that someone would have javascript disable.
Except for an email scraping bot of course :)

= What if I REALLY want my email to appear, even without Javascript =

You can now enable graceful Javascript degradation, see the settings page for more information.


== Screenshots ==

email at address dot com

== Arbitrary section ==

1. Version 1.1 adds graceful JavaScript degradation.
1. Version 1.11 fixes bug where JavaScript degradation did not work properly with multiple emails.