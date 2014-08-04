=== WP-CollectiveAccess ===
Contributors: gautiermichelin
Donate link: http://www.ideesculture.com/
Tags: CollectiveAccess, museum, digital archives, media, tilepic, webservices
Requires at least: 3.9
Tested up to: 3.9
Stable tag: 0.5.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Display your CollectiveAccess collections inside your Wordpress website.

== Description ==

= About WP-CollectiveAccess =
WP-CollectiveAccess is a wordpress plugin to display collections informations & media from museum or digital archives
inside Wordpress, using web services.

WP-CollectiveAccess is a project lead by idéesculture, a small french company, involved in CollectiveAccess development
through french translation & a dedicated module for Musées de France museums, tiny devs... We provide CollectiveAccess
services to museum, tourism offices in France & french-speaking countries.
This project was funded by Pro-Memoria, an italian company providing CollectiveAccess services.

= About CollectiveAccess =
CollectiveAccess is an opensource web-based suite of applications providing a framework for management, description, and discovery
of complex digital and physical collections.
CollectiveAccess is a registered trademark by Whirl-i-Gig in the USA. It has been brought by a collaboration between and
partner institutions in North America and Europe with projects in 5 continents.

== Installation ==

1. Unzip WP-CollectiveAccess.zip inside the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Adapt configuration through Settings > CollectiveAccess

== Frequently Asked Questions ==

= How to test WP-CollectiveAccess with a default & empty wordpress installation ? =

WP-CollectiveAccess has been tested with Wordpress 3.9, using default theme (aka Twenty Fourteen).

To test this plugin inside a fast-to-deploy setup :

1. Install WP-CollectiveAccess plugin and activate it
1. Install [wordpress-importer plugin](https://wordpress.org/plugins/wordpress-importer/) and activate it
1. (optional) Read [this blog post](http://pixelthemes.com/twenty-fourteen-wordpress-theme-demo-sample-data-download/)
about the sample wordpress data we'll deploy
1. Download [the zip of the XML & the WIE files](http://pixelthemes.com/?ddownload=343) we will import
1. Go to Tools -> Import -> Wordpress
1. Select the file that has the "XML" extension.
1. Install [Widget Importer & Exporter plugin](https://wordpress.org/plugins/widget-importer-exporter/) and activate
it.  It is used to import widgets data similar to original demo.
1. Go to Tools -> Widget Importer & Exporter
1. Click on “Select file” or similar button there.
1. Select the file that has the “wie” extension (demos.pixelthemes.com-twentyfourteen-demo-setup-widgets.wie)
1. Go to Appearance -> Menu section.
1. In "Select a menu to edit", choose "Testing menu" and click Select
1. Bottom of the page, tick "Top primary menu" and click Save menu

= Where can I have more information ? =

Please take a look at README.md in the same directory. Don't hesitate to contact me (gautiermichelin) at gm@ideesculture.com

== Screenshots ==

1. Detail view

2. Search View

3. Browse View

== Upgrade Notice ==

1. Replace older version with the new one inside wp-content/plugins. Former parameters value will be kept.
1. Take a look at Settings > CollectiveAccess to verify the presence of eventual new parameters.

== Changelog ==

= 0.5.1 =

- [x] Improved linked object : display, with nice thumbnail & a subview

= 0.5 =

- [x] Customization : page title (for search, browse & detail view) ; 
- [x] Customization : override the default view by a custom view for a single request (post or get $view with a string, for exemple allow to display with an intro the content of a set) ;
- [x] Customization : through templates via admin settings ;
- [x] Customization : create customized views inside views/local
- [x] Customization : page header image through CSS
- [x] Records displayed : objects, entities, places, events, collections
- [x] Internationalization & translations : english (default), french (fr_FR), italian (it_IT)
- [x] TilePic viewer
- [x] Hierarchy viewer

= 0.4.9 =

- development phase for 0.5, planned for end of july

= 0.1.2 =

- expanding views to each part of the plugin

= 0.1.1 =

- supporting views & subviews for easier customisation (take a look at /views & /views/local)

= 0.1 =

- first working version
