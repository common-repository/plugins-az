<?php
/*
Plugin Name: Plugins A to Z
Plugin URI: http://www.wordpress.org/plugins-az
Description: Quick access to entries in the plugins list.
Author: Lutz Schr&ouml;er
Version: 1.1
Author URI: http://elektroelch.de/
Text Domain: plugins-az
Domain Path: /lang
*/

function az_order_callback( $a, $b ) {
	global $wp_version;

	if ( $a['Name'] == $b['Name'] )
		return 0;

	$version = explode('-', $wp_version)[0]; // split in case of dev snapshots
	if ( version_compare( $version, '4.3', '>=' ) ) { // new version
		return strcasecmp( $a['Name'], $b['Name'] );
	} //if
	else
		return ( $a['Name'] > $b['Name'] ) ? 1 : - 1;
} //az_order_callback
// ---------------------------------------------------------------------------------------------------------------------
function az_chars_callback( $a, $b ) {

	if ( $a == $b )
		return 0;

	return strcasecmp( $a, $b );
} //az_char_callback
// ---------------------------------------------------------------------------------------------------------------------
function az_unique($letter, $az) {
	foreach ($az as $item) {
		if ( in_array($letter, $item))
		  return false;
	} //foreach
	return true;
} //az_unique

// ---------------------------------------------------------------------------------------------------------------------
add_action( 'admin_print_scripts-plugins.php', function() {
	wp_enqueue_script( 'plugins_az', plugins_url( 'plugins_az.js', __FILE__ ));
}); //anonymous
// ---------------------------------------------------------------------------------------------------------------------
add_action('admin_print_styles', function() {
	wp_register_style('plugins_az', plugins_url( 'plugins_az.css', __FILE__ ));
	wp_enqueue_style('plugins_az');
}); //anonymous
// ---------------------------------------------------------------------------------------------------------------------

add_action('admin_menu', 'plugins_az_admin_menu');
function plugins_az_admin_menu() {
	$page = add_options_page('Plugins AZ', 'Plugins AZ', 'manage_options', 'pluginsaz', 'pluginsaz_options');
}
// ---------------------------------------------------------------------------------------------------------------------
function pluginsaz_options() {
	require_once('plugins_az_options.php');
	$pluginsz_az_options = new PluginsAZOptions;
}
// ---------------------------------------------------------------------------------------------------------------------
add_action('pre_current_active_plugins', 'plugins_az');
function plugins_az() {
	global $wp_list_table;

	$options = get_option('pluginsaz');

	$plugins = $wp_list_table->items;
	usort($plugins, 'az_order_callback');

	$az = array();
	foreach ($plugins as $plugin) {
		$first_letter = $plugin['Name'][0];
		if (preg_match('/[A-Z]/i', $first_letter))
			// only uppercase letters
			$first_letter = strtoupper($first_letter);
		if (az_unique($first_letter, $az)) {
            $link = isset ($plugin['slug']) ? $plugin['slug'] : sanitize_title($plugin['Name']);
            $az[] = array('letter' => $first_letter, 'id' => 'id="' . $link . '-link" class="active"');
        }
	} //foreach

	if ($options['length'] == 'long') {
		// find letter first "real" letter if there are other characters in front
		$i   = 0;
		$len = sizeof( $az );
		while ( ( strcasecmp( 'A', $az[ $i ]['letter'] ) > 0 ) && ( $i <= $len ) ) {
			$i ++;
		}

		// insert missing letters
		foreach ( range( 'A', 'Z' ) as $char ) {
			if ( ! isset( $az[ $i ] ) || $az[ $i ]['letter'] != $char ) {
				$ch = array( array( 'letter' => $char, 'name' => '#', 'id' => 'id="inactive-link" class="inactive"' ) );
				array_splice( $az, $i, 0, $ch );
			} //if
			$i ++;
		} //foreach
	} //if

	print '<div id="pluginsaz">';
	foreach ($az as $char)
 		print sprintf('<span %s>%s</span>', $char['id'], $char['letter']);
    print '</div>';

} //plugins_az