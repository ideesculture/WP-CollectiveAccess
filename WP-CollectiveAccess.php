<?php
/**
 * Plugin Name: WP-CollectiveAccess
 * Plugin URI: http://github.com/ideesculture/wp-collectiveaccess
 * Description: WordPress connector to display CollectiveAccess data & medias in Wordpress
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

// Including details functions
require_once(plugin_dir_path( __FILE__ ) ."collectiveaccess_object_detail.php");

// Including search functions
require_once(plugin_dir_path( __FILE__ ) ."collectiveaccess_objects_search.php");

// Requiring widget functions
require_once(plugin_dir_path( __FILE__ ) ."widget_random_object.php");
require_once(plugin_dir_path( __FILE__ ) ."widget_search_form.php");
require_once(plugin_dir_path( __FILE__ ) ."widget_browse_links.php");

// Install & uninstall functions
register_activation_hook( __FILE__, "collectiveacces_install" );
function collectiveaccess_install() {
    // if version < 3.9, deactivate our plugin (not tested with a version under 3.9)
    if( version_compare( get_bloginfo( "version" ), "3.9", "<" ) ) {
        deactivate_plugins( basename( __FILE__ ) );
    }

    $collectiveaccess_options = array(
        "view" => "grid" ,
        "food" => "bacon",
        "mode" => "zombie"
    );
    update_option("collectiveaccess_options", $collectiveaccess_options);

	/* CREATE wp_ca_cache :
	CREATE TABLE `wp_ca_cache` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `service` text,
	  `table` text,
	  `target` text,
	  `json` longblob,
	  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	*/
}

function collectiveaccess_uninstall() {

}