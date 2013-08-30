<?php
/* 
Plugin Name: WP Cookie Banner
Plugin URI: https://www.creare.co.uk/services/wp-cookie-banner
Description: Customise and display a subtle, implied consent EU Cookie Law banner on your Wordpress site.
Author: Creare
Version: 0.1
Author URI: https://www.creare.co.uk/
*/   


/*  Copyright 2013  CREAREGROUP  (email : rob.kent@creare.co.uk)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('CREARE_EU_COOKIE_DIR',plugin_dir_path( __FILE__ ));
define('CREARE_EU_COOKIE_URL',plugin_dir_url( __FILE__ ));

if ( is_admin() ){ 
	add_action('admin_menu', 'wp_cookie_banner_admin_actions');   
	add_action('admin_init', 'wp_cookie_banner_theme_options_init' );  
}

function wp_cookie_banner_admin_actions() { 
    $page = add_options_page("EU Cookie Banner Options", "WP Cookie Banner", "administrator", "wp_cookie_banner", "wp_cookie_banner_admin"); 

}

function wp_cookie_banner_settings_link($links, $file) {
    $plugin_file = basename(__FILE__);
    if (basename($file) == $plugin_file) {
        $settings_link = '<a href="options-general.php?page=wp_cookie_banner">Settings</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}

add_filter('plugin_action_links', 'wp_cookie_banner_settings_link', 10, 2);


function wp_cookie_banner_theme_options_init(){
    register_setting( 'wp_cookie_banner_option_group', 'wp_cookie_banner_test_mode' );
    register_setting( 'wp_cookie_banner_option_group', 'wp_cookie_banner_duration' );
    register_setting( 'wp_cookie_banner_option_group', 'wp_cookie_banner_seconds' );
    register_setting( 'wp_cookie_banner_option_group', 'wp_cookie_banner_privacy_message' );
    register_setting( 'wp_cookie_banner_option_group', 'wp_cookie_banner_default_css' );
    register_setting( 'wp_cookie_banner_option_group', 'wp_cookie_banner_linked_page' );

    $plugindirectory = CREARE_EU_COOKIE_URL;
    
    $css_data = "#cookie-law {  
  position: fixed;
  bottom: 0px;
  left: 0;
  right: 0;
  text-align: center;
  z-index:9999; 
}

#cookie-law > div {  
  background:#fff; 
  opacity:0.95; 
  width:75% !important;
  padding:20px;
  max-width: 600px;
  margin:auto;
  display: inline-block;
  text-align: left !important;
  border-radius:5px 5px 0 0;
  -moz-border-radius:5px 5px 0 0;
  -webkit-border-radius:5px 5px 0 0;
  -o-border-radius:5px 5px 0 0;
  box-shadow: 0px 0px 20px #A1A1A1;
  -webkit-box-shadow: 0px 0px 20px #A1A1A1;
  -moz-box-shadow: 0px 0px 20px #A1A1A1;
  -o-box-shadow: 0px 0px 20px #A1A1A1; 
  position:relative;
}

#cookie-law h4 { padding: 0 !important; margin:0 0 8px !important; text-align:left !important; font-size:13px !important; color:#444; 
}
#cookie-law p { padding: 0 !important; margin:0 !important; text-align:left !important; font-size:12px !important; line-height: 18px !important; color:#888;
}

a.close-cookie-banner {
  position: absolute;
  top:0px;
  right:0px;
  margin:10px;
  display:block;
  width:20px;
  height:20px;
  background:url(".$plugindirectory."images/close.png) no-repeat;
  background-size: 20px !important;
}

a.close-cookie-banner span {
  display:none !important;
}";

    $sitename = get_bloginfo('name');
    $defaultmessage = "<h4>$sitename Cookies Policy</h4>
<p>Our Website uses cookies to improve your experience.  Please visit our [cookiepage] page for more information about cookies and how we use them.</p>";

    add_option('wp_cookie_banner_duration','14');
    add_option('wp_cookie_banner_seconds','5000');
    add_option('wp_cookie_banner_privacy_message',$defaultmessage);
    add_option('wp_cookie_banner_default_css',$css_data);

}

function wp_cookie_banner_admin() {  
    include('wp-cookie-banner-settings.php'); 
}

function wp_cookie_banner_script() {

    $linkedPageID = get_option('wp_cookie_banner_linked_page'); 
    
    if(get_option('wp_cookie_banner_duration') && get_option('wp_cookie_banner_privacy_message') && $linkedPageID !=="0"):
?>
<script type="text/javascript">
  var dropCookie = true;
  var cookieDuration = <?php echo get_option('wp_cookie_banner_duration') ?>; 
  var cookieName = 'complianceCookie';
  var cookieValue = 'on';
  <?php
  $message = get_option('wp_cookie_banner_privacy_message');
  // now replace link with our page and text
  $message = str_replace("[cookiepage]",'<a rel="nofollow" href="'.get_page_link("$linkedPageID").'">'.get_the_title("$linkedPageID").'</a>',$message);
  ?>
  var privacyMessage = "";

  jQuery(document).ready(function($) {
    privacyMessage = jQuery('#hidden-cookie-message').html();
    <?php if (get_option('wp_cookie_banner_test_mode') == 1) { ?>eraseCookie('complianceCookie');<?php } ?>
  	if(checkCookie(window.cookieName) != window.cookieValue){
  		createDiv(<?php if (get_option('wp_cookie_banner_test_mode') == 1 ) { echo "false"; } else { echo "true" ; } ?>); 
  		window.setTimeout(function() {
      		$('#cookie-law').fadeOut();
  		}, <?php echo get_option('wp_cookie_banner_seconds'); ?>);
  	}
  });
</script>
<div id="hidden-cookie-message" style="display:none;">
  <div>
    <?php echo $message; ?>
    <a class="close-cookie-banner" href="javascript:void(0);" onclick="jQuery(this).parent().parent().hide();"><span>Close</span></a>
  </div>
</div>
<?php endif; }


add_action( 'wp_footer', 'wp_cookie_banner_script' );

function wp_cookie_banner_jquery() {
    wp_enqueue_script('eu-cookie-law', CREARE_EU_COOKIE_URL.'js/eu-cookie-law.js', array('jquery'), null, true);
}

add_action( 'wp_enqueue_scripts', 'wp_cookie_banner_jquery' );

if (get_option('wp_cookie_banner_default_css') !="" && !isset($_COOKIE['complianceCookie'])) { 
    add_action('wp_head', 'add_css_to_head', '10'); 
 }

function add_css_to_head() { ?>
<style type="text/css" media="screen">
  <?php echo get_option('wp_cookie_banner_default_css'); ?>
</style>
 <?php } 






