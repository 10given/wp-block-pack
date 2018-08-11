<?php

/**
 * For Additional Action Links.
 *
 * Add Setting Links, and maybe pro link in the future.
 */

add_filter( 'plugin_action_links_'. WPBLOCKPACK_PLUGIN_BASE, 'wpblockpack_action_links' );

function wpblockpack_action_links ( $links ) {
 $actionlinks = array(
 	'<a href="' . admin_url( 'options-general.php?page=wp-block-pack' ) . '">Settings</a>',
 	// '<a href="https://wpblockpack.com/pro" class="go-pro-link">Pro</a>',
 );
return array_merge( $links, $actionlinks );
}


/**
 * For Additional Meta Links.
 *
 * Add Support and FAQ link in the future.
 */

add_filter( 'plugin_row_meta', 'wpblockpack_meta_links', 10, 4 );
 
function wpblockpack_meta_links( $links_array, $plugin_file_name, $plugin_data, $status ){
	if( strpos( $plugin_file_name, basename( WPBLOCKPACK_FILE_ ) ) ) {
		// $links_array[] = '<a href="https://wpblockpack.com/faq">FAQ</a>';
		// $links_array[] = '<a href="https://wpblockpack.com/forum">Support</a>';
	}
 	return $links_array;
}


/**
 * Add Custom CSS on admin head.
 */

add_action('admin_head', 'wpblockpack_admin_style');

function wpblockpack_admin_style() {
  echo '<style>.go-pro-link {color: #b5b239;font-weight: 700;} </style>';
}


/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */
 
/**
 * custom option and settings
 */
function wpblockpack_settings_init() {
	// register a new setting for "wpblockpack" page
	register_setting( 'wpblockpack', 'wpblockpack_options' );

	// register a new section in the "wpblockpack" page
	add_settings_section(
		'wpblockpack_section_developers',
		__( 'Hide all unnecessary blocks.', 'wpblockpack' ),
		'wpblockpack_section_developers_cb',
		'wpblockpack'
	);

	// register a new field in the "wpblockpack_section_developers" section, inside the "wpblockpack" page
	add_settings_field(
		'wpblockpack_field_pill', 
		// use $args' label_for to populate the id inside the callback
		__( 'Pill', 'wpblockpack' ),
		'wpblockpack_field_pill_cb',
		'wpblockpack',
		'wpblockpack_section_developers',
		[
			'label_for' => 'wpblockpack_field_pill',
			'class' => 'wpblockpack_row',
			'wpblockpack_custom_data' => 'custom',
		]
	);
}
 

/**
 * register our wpblockpack_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'wpblockpack_settings_init' );
 
/**
 * custom option and settings:
 * callback functions
 */
 
// developers section cb
 
// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function wpblockpack_section_developers_cb( $args ) {
 ?>
 	<div id="<?php echo esc_attr( $args['id'] ); ?>">
	 	<?php esc_html_e( 'Whatever \'add blocks\' buttons setting you don\'t wanna see, just hide it.', 'wpblockpack' ); ?><br/>
	 	<?php esc_html_e( 'It wouldn\'t break any block that you already use. We just hide the \'add\' buttons so you keep feeling simple.', 'wpblockpack' ); ?><br/>
	 	<?php esc_html_e( 'These block always work and ready to use any time. Simple yet Powerful.', 'wpblockpack' ); ?>
 	</div>
 <?php
}
 
// pill field cb
 
// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function wpblockpack_field_pill_cb( $args ) {
	// get the value of the setting we've registered with register_setting()
	$options = get_option( 'wpblockpack_options' );
	// output the field
?>
	<select id="<?php echo esc_attr( $args['label_for'] ); ?>" data-custom="<?php echo esc_attr( $args['wpblockpack_custom_data'] ); ?>" name="wpblockpack_options[<?php echo esc_attr( $args['label_for'] ); ?>]" >
		<option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>><?php esc_html_e( 'red pill', 'wpblockpack' ); ?></option>
		<option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>><?php esc_html_e( 'blue pill', 'wpblockpack' ); ?></option>
	</select>
	<p class="description"><?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wpblockpack' ); ?></p>
	<p class="description"><?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wpblockpack' ); ?></p>
	<?php
}
 
/**
 * top level menu
 */
function wpblockpack_options_page() {
	// add submenu page
	add_submenu_page(
		'options-general.php',
		'WP Block Pack',
		'WP Block Pack',
		'manage_options',
		'wp-block-pack',
		'wpblockpack_options_page_html'
	);
}
 
/**
 * register our wpblockpack_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'wpblockpack_options_page' );
 
/**
 * submenu menu:
 * callback functions
 */
function wpblockpack_options_page_html() {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
 ?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
<?php
			// output security fields for the registered setting "wpblockpack"
			settings_fields( 'wpblockpack' );
			// output setting sections and their fields
			// (sections are registered for "wpblockpack", each field is registered to a specific section)
			do_settings_sections( 'wpblockpack' );
			// output save settings button
			submit_button( 'Save Settings' );
?>
		</form>
	</div>
<?php
}