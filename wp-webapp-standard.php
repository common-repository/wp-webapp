<?php

/*
Plugin Name: WP Webapp Standard
Plugin URI: http://wp-webapp.com/
Description: Turn ANY Wordpress site into a mobile Web App!
Author: WP Webapp
Author URI: http://wp-webapp.com/
Version: 1.1
*/

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'WPWA_STORE_URL', 'http://wp-webapp.com' ); // IMPORTANT: change the name of this constant to something unique to prevent conflicts with other plugins using this system
 
// the name of your product. This is the title of your product in EDD and should match the download title in EDD exactly
define( 'WPWA_ITEM_NAME', 'WP Webapp Standard' ); // IMPORTANT: change the name of this constant to something unique to prevent conflicts with other plugins using this system

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater if it doesn't already exist
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}

// retrieve our license key from the DB
$license_key = trim( get_option( 'edd_sample_license_key' ) );
 
// setup the updater
$edd_updater = new EDD_SL_Plugin_Updater( WPWA_STORE_URL, __FILE__, array( 
		'version' 	=> '1.0', 		// current version number
		'license' 	=> $license_key, 	// license key (used get_option above to retrieve from DB)
		'item_name' => WPWA_ITEM_NAME, 	// name of this plugin
		'author' 	=> 'WP Webapp'  // author of this plugin
	)
);


register_activation_hook(__FILE__, 'wpwebapp_add_defaults');
register_uninstall_hook(__FILE__, 'wpwebapp_delete_plugin_options');
add_action('admin_init', 'wpwebapp_init');
add_action('admin_menu', 'wpwebapp_add_options_page');

// Uninstall 
function wpwebapp_delete_plugin_options() {
	delete_option('wpwebapp_options');
	delete_option('edd_sample_license');
	delete_option('edd_sample_license_key');
	delete_option('edd_sanitize_license');
	delete_option('edd_sample_license_status');
}

// Define options settings
function wpwebapp_add_defaults() {
	$tmp = get_option('wpwebapp_options');
		$arr = array("webappbubble" => "yes"					
		);
		update_option('wpwebapp_options', $arr);
}

// Init options
function wpwebapp_init() {
	register_setting( 'wpwebapp_plugin_options', 'wpwebapp_options' );
}

// Add menu page
function wpwebapp_add_options_page() {
	add_options_page('WP Webapp Page', 'WP Webapp Settings', 'manage_options', __FILE__, 'wpwebapp_render_form');
}

// Render options
function wpwebapp_render_form() {
	$license 	= get_option( 'edd_sample_license_key' );
	$status 	= get_option( 'edd_sample_license_status' );
	?> 
    <div class="wrap">
    <center><h1 style="text-align:center;"><a href="http://wp-webapp.com/" target="_blank"><img src="<?php echo plugins_url('wpwebapp_logo-280.png', __FILE__) ?>" /></a></h1></center>
    <h1>WP Webapp Settings</h1>
	<h3>To learn more visit us at <a href="http://wp-webapp.com/" target="_blank">WP Webapp</a>! Upgrade to <a href="http://wp-webapp.com/get-it-now/" target="_blank">WP Webapp Pro</a> or <a href="http://wp-webapp.com/get-it-now/" target="_blank">WP Webapp Enterprise</a> to obtain a license key and receive additional support and updates!</h3>
    <h3 style="color:#EF7F2C;"><a href="http://wp-webapp.com/get-it-now/" target="_blank">Pro</a> and <a href="http://wp-webapp.com/get-it-now/" target="_blank">Enterprise</a> versions now have MEDIA UPLOADER so you can add splash and touch screen images right from this settings page!</h3>
    <p>1. Enter your license key from your purchase receipt and click "Save Changes"</p>
    <p>2. After you save your license key in the settings click "Activate License." This must be activated to receive plugin updates.</p>
    <form method="post" action="options.php">
			<?php settings_fields('wpwebapp_plugin_options'); ?>
			<?php $options = get_option('wpwebapp_options'); ?>
            <table class="form-table">
    <tr>
    <th scope="row">Display homepage balloon?</th>
					<td>
		<select name='wpwebapp_options[webappbubble]'>
						<option value='yes' <?php selected('yes', $options['webappbubble']); ?>>Yes</option>
						<option value='no' <?php selected('no', $options['webappbubble']); ?>>No</option>
					</select>
                    </td>
				</tr>
                                                        
               </table>
               <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                </p>
                </form> 
                
                <h2>Installation Instructions</h2>
                <p>WP Webapp allows you to easily add Web App functionality to ANY WordPress Website.<p>

<p>All you have to do is replace the HOMESCREEN ICON image and the SPLASH SCREEN images included in the "wp-webapp/homeicon/" and "wp-webapp/splash/" folders of this plugin. Follow these simple steps to give your WordPress Website Web App functionality!</p>

<h4>1. HOMESCREEN ICON</h4>
<p>In the "wp-webapp/homeicon/" folder replace the "wp-webapp_icon.png" graphic with YOUR homescreen icon. Your icon MUST be sized (144x144px) and named (wp-webapp_icon.png) EXACTLY the same as the icon it is replacing in that folder.</p>

<h4>2. SPLASH SCREENS</h4>
<p>In the "wp-webapp/splash/" folder there are splash screen images sized for use on various mobile devices. </p>

<p>Replace these splash screen images with YOUR images sized and named EXACTLY the same. (If you do not want to use a particular size delete that image from the "images" folder). </p>

<p><ul>
<li><strong>Sizes are as follows:</strong></li>
<li>iPhone 3: 320x460px</li>
<li>iPhone 4: 640x920px</li>
<li>iPhone 5: 640x1096px</li>
<li>Landscape Tablets: 748x1024</li>
<li>Portrait Tablets: 768x1004</li>
<li>Landscape Retina Tablets: 1496x2048</li>
<li>Portrait Retina Tablets: 1536x2008</li>
</ul>
</p>
<p><em>NOTE: All image names are CASE SENSITIVE. Images must be NAMED and SIZED exactly the same to work.</em></p>         
	</div>
<?
}

function edd_sample_register_option() {
	// creates our settings in the options table
	register_setting('edd_sample_license', 'edd_sample_license_key', 'edd_sanitize_license' );
}
add_action('admin_init', 'edd_sample_register_option');

function edd_sanitize_license( $new ) {
	$old = get_option( 'edd_sample_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'edd_sample_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}



function addwebappcapable() { ?>
    <meta name="apple-mobile-web-app-capable" content="yes" />
	<? }
	add_action('wp_head', 'addwebappcapable', 3);

function addwebapp_title() { ?>
    <meta name="apple-mobile-web-app-title" content="<?php {echo wp_title(''); } ?>" />
	<? }
	add_action('wp_head', 'addwebapp_title', 4);
	
function addmetawebstatusbar() { ?>
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<? }
	add_action('wp_head', 'addmetawebstatusbar', 5);
	
function addwebapptouchicon_url() { ?>
	<link rel="apple-touch-icon-precomposed" href="<?php echo plugins_url('homeicon/wp-webapp_icon.png', __FILE__) ?>">
     <? }
	add_action('wp_head', 'addwebapptouchicon_url', 6);


/* BEGIN ADD TO HOME */

/*
Utilizes Add to home code by Matteo Spinelli.
Official homepage: (http://cubiq.org/add-to-home-screen)
License

Add to home code is released under the MIT License.

Copyright (c) 2013 Matteo Spinelli, http://cubiq.org/

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
*/

add_action( 'wp_enqueue_scripts', 'wpwebappaddtohomecss' );
function wpwebappaddtohomecss() {
	wp_register_style( 'addtohomecss', plugins_url('css/add2home.css', __FILE__) );
	wp_enqueue_style( 'addtohomecss' );
	wp_register_script( 'addtohomejs', plugins_url('js/add2home.js', __FILE__) );
	wp_enqueue_script( 'addtohomejs' );
	} 	

function wpwebappdequeue() {
$options = get_option('wpwebapp_options');
	$select = $options['webappbubble'];
	if ($select == 'no') {
		remove_action('wp_head', 'addtohomecss');
     	remove_action('wp_head', 'addtohomejs');
		wp_dequeue_script( 'addtohomejs' );
		wp_deregister_script( 'addtohomejs' );
	}
	else {}
}
add_action( 'wp_head', 'wpwebappdequeue', 2 );
/* END ADD TO HOME */

function addiossplash() { ?>
<!-- iPhone -->
<link href="<?php echo plugins_url('splash/splash-320x460.png', __FILE__) ?>"
      media="(device-width: 320px) and (device-height: 480px)
         and (-webkit-device-pixel-ratio: 1)"
      rel="apple-touch-startup-image">
<!-- iPhone (Retina) -->
<link href="<?php echo plugins_url('splash/splash-640x920.png', __FILE__) ?>"
      media="(device-width: 320px) and (device-height: 480px)
         and (-webkit-device-pixel-ratio: 2)"
      rel="apple-touch-startup-image">
<!-- iPhone 5 -->
<link href="<?php echo plugins_url('splash/splash-640x1096.png', __FILE__) ?>"
      media="(device-width: 320px) and (device-height: 568px)
         and (-webkit-device-pixel-ratio: 2)"
      rel="apple-touch-startup-image">
<!-- iPad (portrait) -->
<link href="<?php echo plugins_url('splash/splash-768x1004.png', __FILE__) ?>"
      media="(device-width: 768px) and (device-height: 1024px)
         and (orientation: portrait)
         and (-webkit-device-pixel-ratio: 1)"
      rel="apple-touch-startup-image">
<!-- iPad (landscape) -->
<link href="<?php echo plugins_url('splash/splash-748x1024.png', __FILE__) ?>"
      media="(device-width: 768px) and (device-height: 1024px)
         and (orientation: landscape)
         and (-webkit-device-pixel-ratio: 1)"
      rel="apple-touch-startup-image">
<!-- iPad (Retina, portrait) -->
<link href="<?php echo plugins_url('splash/splash-1536x2008.png', __FILE__) ?>"
      media="(device-width: 768px) and (device-height: 1024px)
         and (orientation: portrait)
         and (-webkit-device-pixel-ratio: 2)"
      rel="apple-touch-startup-image">
<!-- iPad (Retina, landscape) -->
<link href="<?php echo plugins_url('splash/splash-1496x2048.png', __FILE__) ?>"
      media="(device-width: 768px) and (device-height: 1024px)
         and (orientation: landscape)
         and (-webkit-device-pixel-ratio: 2)"
      rel="apple-touch-startup-image"> <?
}
add_action('wp_head', 'addiossplash', 7);
	function addmetawebcapablelinks() { ?>
		<script type="text/javascript">
		(function(document,navigator,standalone) {
			// prevents links from apps from oppening in mobile safari
			// this javascript must be the first script in your <head>
			if ((standalone in navigator) && navigator[standalone]) {
				var curnode, location=document.location, stop=/^(a|html)$/i;
				document.addEventListener('click', function(e) {
					curnode=e.target;
					while (!(stop).test(curnode.nodeName)) {
						curnode=curnode.parentNode;
					}
					// Conditions to do this only on links to your own app
					// if you want all links, use if('href' in curnode) instead.
					if('href' in curnode && ( curnode.href.indexOf('http') || ~curnode.href.indexOf(location.host) ) ) {
						e.preventDefault();
						location.href = curnode.href;
					}
				},false);
			}
		})(document,window.navigator,'standalone');
	</script>
	<?php }
		add_action('wp_head', 'addmetawebcapablelinks', 1);
		
		// Add settings link on plugin page
  function wpwebapp_settings_link($links) { 
  $settings_link = '<a href="'.get_admin_url().'options-general.php?page=wp-webapp/wp-webapp-standard.php">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
 }
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'wpwebapp_settings_link' );



/************************************
* this illustrates how to activate 
* a license key
*************************************/

function edd_sample_activate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['edd_license_activate'] ) ) {

		// run a quick security check 
	 	if( ! check_admin_referer( 'edd_sample_nonce', 'edd_sample_nonce' ) ) 	
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'edd_sample_license_key' ) );
			

		// data to send in our API request
		$api_params = array( 
			'edd_action'=> 'activate_license', 
			'license' 	=> $license, 
			'item_name' => urlencode( WPWA_ITEM_NAME ) // the name of our product in EDD
		);
		
		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, WPWA_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "active" or "inactive"

		update_option( 'edd_sample_license_status', $license_data->license );

	}
}
add_action('admin_init', 'edd_sample_activate_license');


/***********************************************
* Illustrates how to deactivate a license key.
* This will descrease the site count
***********************************************/

function edd_sample_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['edd_license_deactivate'] ) ) {

		// run a quick security check 
	 	if( ! check_admin_referer( 'edd_sample_nonce', 'edd_sample_nonce' ) ) 	
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'edd_sample_license_key' ) );
			

		// data to send in our API request
		$api_params = array( 
			'edd_action'=> 'deactivate_license', 
			'license' 	=> $license, 
			'item_name' => urlencode( WPWA_ITEM_NAME ) // the name of our product in EDD
		);
		
		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, WPWA_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' )
			delete_option( 'edd_sample_license_status' );

	}
}
add_action('admin_init', 'edd_sample_deactivate_license');

		
		
?>