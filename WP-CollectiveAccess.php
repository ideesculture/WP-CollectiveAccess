<?php
/**
 * Plugin Name: WP-CollectiveAccess
 * Plugin URI: http://github.com/ideesculture/wp-collectiveaccess
 * Description: Display CollectiveAccess data & medias in Wordpress
 * Version: 0.1
 * Author: Gautier Michelin, idéesculture.
 * Funding : Project originally funded by Pro-Memoria
 * Author URI: http://www.ideesculture.com
 * License: GPLv3

 * Copyright 2014 Gautier Michelin, idéesculture  (email : gm@ideesculture.com)

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once(plugin_dir_path( __FILE__ ) ."lib/virtualthemedpages/Virtual_Themed_Pages_BC.php");
$vp =  new Virtual_Themed_Pages_BC();

// Including admin page file
require_once(plugin_dir_path( __FILE__ ) ."collectiveaccess_settings.php");

// Including details & search functions
require_once(plugin_dir_path( __FILE__ ) ."collectiveaccess_object_detail.php");
require_once(plugin_dir_path( __FILE__ ) ."collectiveaccess_objects_search.php");
require_once(plugin_dir_path( __FILE__ ) ."collectiveaccess_browse.php");

// Requiring widget functions
require_once(plugin_dir_path( __FILE__ ) ."widget_random_object.php");
require_once(plugin_dir_path( __FILE__ ) ."widget_search_form.php");
require_once(plugin_dir_path( __FILE__ ) ."widget_browse_links.php");

// Install & update functions
register_activation_hook( __FILE__, "collectiveaccess_install" );
//add_action( 'plugins_loaded', 'collectiveaccess_update_db_check' );

global $wpca_version;
$wpca_version = "0.1";


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
		$sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  service tinytext NOT NULL,
  base_url text NOT NULL,
  catable tinytext NOT NULL,
  mode tinytext,
  query text,
  time datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  result blob,
  UNIQUE KEY id (id)
    );";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		update_option( "wpca_version", $wpca_version );
	}
    $collectiveaccess_options = array(
        "view" => "grid" ,
        "food" => "bacon",
        "mode" => "zombie"
    );
    update_option("collectiveaccess_options", $collectiveaccess_options);
}

function collectiveaccess_uninstall() {
	global $wpca_version;
	if (get_site_option( 'wpca_version' ) != $wpca_version) {
		collectiveaccess_install();
	}
}
