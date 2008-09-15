<?php

/*
Plugin Name: Email Spam Protection
Plugin URI: http://blueberryware.net
Description: Converts emails provided in the shortcode [escapeemail email="email@address.com"] to urlencoded javascript.  Based on email spam protection on Twitter's contact page. If you found the plugin helpful please rate it.  

Author: Adam Hunter
Version: 1.0
Author URI: http://blueberryware.net
*/

function write_email_js($atts) {
	extract($atts);
	if ( empty($email) ) {
		return;
	}
	$text = str_replace('@', ' at ', $email);
	$text = str_replace('.', ' dot ', $text);
	$string = 'document.write(\'<a href="mailto:' . $email . '">' . $text . '</a>\')';
	$split = preg_split('||', $string);
	
	$out =  '<script type="text/javascript">';
	$out .= 'eval(unescape(\'';
	foreach ( $split as $c ) {
		if ( !empty($c) ) {
			$out .= '%' . dechex(ord($c));
		}
	}
	$out .= '\'))</script>';
	return $out;
}

add_shortcode('escapeemail', 'write_email_js');