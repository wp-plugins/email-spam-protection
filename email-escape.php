<?php
/*
Plugin Name: Email Spam Protection
Plugin URI: http://blueberryware.net/2008/09/14/email-spam-protection/
Description: Converts emails provided in the shortcode [escapeemail email="email@address.com"] to urlencoded javascript.  Based on email spam protection on Twitter's contact page. If you found the plugin helpful please rate it <a href="http://wordpress.org/extend/plugins/email-spam-protection/">here</a>.  You may enable graceful Javascript degradation in the <a href="options-general.php?page=email-escape">settings page</a>.
Author: Adam Hunter
Version: 1.1
Author URI: http://blueberryware.net
*/
class emailEscape {

	/**
	 * determines whether or not to degrade gracefull if javascript is not enabled
	 *
	 * @var bool
	 */
	var $degrade;
	var $wraps = array(
		array('[', ']'),
		array('{', '}'),
		array(':', ':'),
		array('&lt;', '&gt;'), 
		array('&laquo;', '&raquo;'),
		array('&lsaquo;', '&rsaquo;')
	);	
	
	/**
	 * sets up options for escaping email, is run at init action hook
	 */
	function init() {
		add_option('email_js_degrade', false);
		$this->degrade = get_option('email_js_degrade');
		add_shortcode('escapeemail', array($this, 'run'));
		add_action('admin_menu', array($this, 'link'));
	}
		/**
	 * Creates url encoded javascript powered link to email address
	 *
	 * @param array $atts
	 * @return string
	 */
	function run($atts) {
		extract($atts);
		if ( empty($email) ) {
			return;
		}
		$text = str_replace('@', ' at ', $email);
		$text = str_replace('.', ' dot ', $text);
		$string = 'document.write(\'<a href="mailto:' . $email . '">' . $text . '</a>\')';
		/* break string into array of characters, we can't use string_split because its php5 only :( */
		$split = preg_split('||', $string);
		$out =  '<script type="text/javascript"> // <!-- ' . PHP_EOL . "eval(unescape('";
		foreach ( $split as $c ) {
			/* preg split will return empty first and last characters, check for them and ignore */
			if ( !empty($c) ) {
				$out .= '%' . dechex(ord($c));
			}
		}
		$out .= "'))" . PHP_EOL .  '// --> </script>';
		/* if degrading is enabled, create span with text and js to remove text */
		if ( $this->degrade ) {
			$wrap = $this->wraps[array_rand($this->wraps)];
			$at = ' ' . $wrap[0] . 'at' . $wrap[1] . ' ';
			$dot = ' ' . $wrap[0] . 'dot' . $wrap[1] . ' ';
			$text = str_replace('@', $at, $email);
			$text = str_replace('.', $dot, $text);
			$html = '<span id="email_text">' . $text . '</span>'
				  . '<script type="text/javascript"> // <!-- ' . PHP_EOL
				  . 'document.getElementById("email_text").innerHTML = ""' . PHP_EOL
				  . ' // --> </script>';
			$out .= $html;
		}
		return $out;
	}
	
	function link() {
		add_options_page(__('Email Spam Protection Options'), 
					 	 __('Email Spam Proction'), 
					 	 'manage_options', 
					     'email-escape', 
					 	 array($this, 'options') );
	}
	
	function options() {
		?>
		<?php if ( $this->save() ) { ?>
		<div class="updated fade">
			<p>You have saved your Email Spam Protection Options :)</p>
		</div>
		<?php } /* if ( $this->save() ) */ ?>
		<div class="wrap">
			<h2>Email Spam Protection Options</h2>
			<p>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
				<?php wp_nonce_field('email_spam_nonce'); ?>
				<ul style="list-style: none;">
				<li>
				<input type="checkbox" name="js_degrade" id="js_degrade" value="1" <?php ($this->degrade) ? print('checked="checked"') : print(''); ?> />
				<label for="js_degrade">Degrade gracefully if javascript isn't available</label>
					<ul style="list-style:none;">
					<li>
					This will write email [at] address [dot] com into the post if enabled (as text) and javascript is not available on the client's browser.  It is not recommended as it is much easier to scrape an email address in this form.  However, it's up to you.  It randomly uses [], {}, (), ::, <>, &laquo;&raquo;, and &lsaquo;&rsaquo; around the <strong>at</strong> and <strong>dot</strong> for better obfuscation.
					</li>
					</ul>
				</li>
				</ul>
				<p class="submit"><input type="submit" value="Save" name="submit" id="submit" /></p>
			</form>
			</p>
		</div>	
		<?php
	}
	
	function save() {
		if ( empty($_POST) ) {
			return false;
		}
		check_admin_referer('email_spam_nonce');
		if ( !empty($_POST['js_degrade']) ) {
			update_option('email_js_degrade', true);
			$this->degrade = true;
		} else {
			update_option('email_js_degrade', false);
			$this->degrade = false;
		}
		return true;
	}
}

$emailescape = new emailEscape();
add_action('init', array($emailescape, 'init'));