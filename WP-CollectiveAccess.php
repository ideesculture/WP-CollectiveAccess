<?php
/**
 * Plugin Name: WP-CollectiveAccess
 * Plugin URI: http://github.com/ideesculture/wp-collectiveaccess
 * Description: Display CollectiveAccess data & medias in Wordpress
 * Version: 0.5.0
 * Author: Gautier Michelin, idéesculture.
 * Funding : Project originally funded by Pro-Memoria
 * Author URI: http://www.ideesculture.com
 * License: GPLv3
 *
 * Copyright 2014 Gautier Michelin, idéesculture  (email : gm@ideesculture.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Requiring common handlers & functions
require_once(plugin_dir_path( __FILE__ ) ."collectiveaccess_common_functions.php");

// Requiring admin page file
require_once(plugin_dir_path( __FILE__ ) ."collectiveaccess_settings.php");

// Add a link on the plugin page to the setting
add_filter('plugin_action_links', 'collectiveaccess_add_settings_link', 10, 2 );

// Requiring idéesculture simpleview class
require_once(plugin_dir_path( __FILE__ ) ."lib/simpleview/simpleview_idc.php");
// The idéesculture simpleview class requires a place where the views are stored, if empty 
// or not defined, they will be load from current dir.
define("SIMPLEVIEW_IDC_DIR",plugin_dir_path( __FILE__ )."views/");

// Requiring details & search functions
require_once(plugin_dir_path( __FILE__ ) ."collectiveaccess_object_detail.php");
require_once(plugin_dir_path( __FILE__ ) ."collectiveaccess_objects_search.php");
require_once(plugin_dir_path( __FILE__ ) ."collectiveaccess_browse.php");
require_once(plugin_dir_path( __FILE__ ) ."collectiveaccess_objects_hierarchy.php");

// Requiring widget functions
// require_once(plugin_dir_path( __FILE__ ) ."widget_random_object.php");
require_once(plugin_dir_path( __FILE__ ) ."widget_search_form.php");
require_once(plugin_dir_path( __FILE__ ) ."widget_browse_links.php");

// Install & update functions
register_activation_hook( __FILE__, "collectiveaccess_install" );
//add_action( 'plugins_loaded', 'collectiveaccess_update_db_check' );

// i18n
add_action('init', 'collectiveaccess_i18n_init');

global $wpca_version;
$wpca_version = "0.5.1";

define("WP_CA_MAIN_FILE",__FILE__);
define("WP_CA_DIR",__DIR__."/");
define("WP_CA_VIEWS_DIR",__DIR__."/views/");
define("WP_CA_CONF_DIR",__DIR__."/conf/");
define("WP_CA_ASSETS_DIR",__DIR__."/assets/");
define("WP_CA_LIB_DIR",__DIR__."/lib/");
define("WP_CA_JS_DIR",__DIR__."/js/");
/**
 *
 */
function collectiveaccess_install() {
	global $wpca_version, $wpdb;
	// Force DB install
	update_option( "wpca_version", "" );
	$installed_version = get_option( "wpca_version" );

	// if version < 3.9, deactivate our plugin (not tested with a version under 3.9)
    if( version_compare( get_bloginfo( "version" ), "3.7", "<" ) ) {
        deactivate_plugins( basename( __FILE__ ) );
    }
	if($installed_version != $wpca_version) {

        $table_name = $wpdb->prefix . "collectiveaccess_cache";

        // for easier reading & maintenance, $sql var is written in conf/db_table_creation.php
        include(plugin_dir_path( __FILE__ )."conf/db_table_creation.php");

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		update_option( "wpca_version", $wpca_version );
	}

    // for easier reading & maintenance, default options values are written in conf/default_options_values.php
    include(plugin_dir_path( __FILE__ )."conf/default_options_values.php");

    update_option("collectiveaccess_options", $collectiveaccess_options);
}

function collectiveaccess_uninstall() {
	global $wpca_version;
	if (get_site_option( 'wpca_version' ) != $wpca_version) {
		collectiveaccess_install();
	}
}

function collectiveaccess_i18n_init() {
	// Textdomain
	$domain = 'collectiveaccess';

    $path = dirname(plugin_basename( __FILE__ )) . '/lang/';
    $loaded = load_plugin_textdomain( 'collectiveaccess', false, $path);
    if ($_GET['page'] == basename(__FILE__) && !$loaded) {          
        echo '<div class="error">'.__('Problem with localization files : ','collectiveaccess') . __('Could not load the localization file: ' . $path, $domain) . '</div>';
        return;
    } 
} 

/**
 * add a settings link to the the plugin on the plugin page
 * @param array $links
 * @param string $file
 * @return array
 */
function collectiveaccess_add_settings_link( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$settings_link = '<a href="options-general.php?page=collectiveaccess">' . __('Settings' )/*get this from WP core*/ . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
