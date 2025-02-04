<?php
/*
Plugin Name: Bing Site Verification plugin using Meta Tag
Plugin URI: http://twitter.com/himanshumaker
Description: Simply insert your bing meta tag verification code using this helpful plugin.
Version: 1.0
Author: Himanshu
Author URI: http://twitter.com/himanshumaker
License: GPLv3
	
*/

// FUNCTIONS

function bing_head_tag_verification_get_defaults() { 
	$defaults = array( 
		'account' => ''
	);
	return $defaults;
}

function bing_head_tag_verification_set_plugin_meta( $links, $file ) { 
/*	short desc: define additional plugin meta links (appearing under plugin on Plugins page)
	parameters:
		$links = (array) passed from wp
		$file = (array) passed from wp*/
	$plugin = plugin_basename( __FILE__ ); // '/nofollow/nofollow.php' by default
    if ( $file == $plugin ) { // if called for THIS plugin then:
		$newlinks = array( '<a href="options-general.php?page=bing-meta-tag-verification">' . __( 'Settings' ) . '</a>'	); // array of links to add
		return array_merge( $links, $newlinks ); // merge new links into existing $links
	}
return $links; // return the $links (merged or otherwise)
}

function bing_head_tag_verification_options_init() { 
// short desc: add plugin's options to white list
	register_setting( 'bing_head_tag_options_options', 'bing_head_tag_verification_item', 'bing_head_tag_verification_options_validate' );
}

function bing_head_tag_verification_options_add_page() { 
// add link to plugin's settings page under 'settings' on the admin menu 
	add_options_page( __( 'bing Site Verification Settings' ), __( 'bing Site Verification'), 'manage_options', 'bing-meta-tag-verification', 'bing_head_tag_verification_options_do_page');
}

function bing_head_tag_verification_options_validate( $input ) { 
/* 	short desc: sanitize and validate input. accepts an array, returns a sanitized array.
	parameters: $input = (array) option input to validate
	return: (array) sanitized option input */

	// sanatize inputs:
	$input['account'] =  wp_filter_nohtml_kses( $input['account'] ); // (textbox) safe text, no html
	return $input;
}

function bing_head_tag_verification_options_do_page() { 
// short desc: draw the html/css for the settings page
	
	?>
	<div class="wrap">
    <div class="icon32" id="icon-options-general"><br /></div>
		<h2><?php _e( 'bing Site Verification Meta Tag Settings' ); ?></h2>
		<form name="form1" id="form1" method="post" action="options.php">
			<?php settings_fields( 'bing_head_tag_options_options' ); // nonce settings page ?>
			<?php $options = get_option( 'bing_head_tag_verification_item', bing_head_tag_verification_get_defaults() ); // populate $options array from database ?>
			
			<!-- Description -->
			<p style="font-size:0.95em"><?php 
				_e( sprintf( 'You may post a comment on this plugin\'s %1$shomepage%2$s if you have any questions, bug reports, or feature suggestions.', '<a target="_blank" href="http://twitter.com/himanshumaker" rel="help">', '</a>' ) ); ?></p>
			
			<div>
			 1. Login to <a target="_blank" href="http://bing.com/webmasters">bing Webmaster</a> to add your site. Copy your verification tag in below box.
			</div>
			
			<div style="margin-top:20px;">
			2. Enter Verification tag
			
			<table class="form-table" style="margin-left:30px;">

				<tr>
					<th scope="row"><label for="">Example:</label></th>
					<td>
						<p>"meta name=bing-site-verification content="<span style="color:red">XXXXXXXXXXXXXXXXX</span>"</p> (Copy the content from bing verification tag.)
					</td>
				
				</tr>
            	<tr valign="top"><th scope="row"><label for="bing_head_tag_verification_item[account]"><?php _e( 'bing Meta Tag Content' ); ?>: </label></th>
					<td>
						<input type="text" name="bing_head_tag_verification_item[account]" value="<?php echo $options['account']; ?>" style="width:200px;" maxlength="400"/>
	            	</td>
				</tr>
				
               
				
			</table>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
			</p>
			</div>
		</form>
	</div>
    
	<?php
}

function bing_head_tag_verification_print_code() { 

	
	$options = get_option( 'bing_head_tag_verification_item', bing_head_tag_verification_get_defaults() ); 


$code = '<!--
Plugin: bing meta tag Site Verification Plugin
Tracking Code.

-->

<meta name="msvalidate.01" content="' . $options['account'] . '"/>';


	echo $code;
	return; 

}

// HOOKS AND FILTERS
add_filter( 'plugin_row_meta', 'bing_head_tag_verification_set_plugin_meta', 10, 2 ); // add plugin page meta links
add_action( 'admin_init', 'bing_head_tag_verification_options_init' ); // whitelist options page
add_action( 'admin_menu', 'bing_head_tag_verification_options_add_page' ); // add link to plugin's settings page in 'settings' menu on admin menu initilization

// insert html code on page head initilization 
$options = get_option( 'bing_head_tag_verification_item', bing_head_tag_verification_get_defaults() );
if( $options['account']!= '' ) 
	add_action( 'wp_head', 'bing_head_tag_verification_print_code', 99999 ); 

?>