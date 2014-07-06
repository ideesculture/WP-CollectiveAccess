# WP-CollectiveAccess

Display your CollectiveAccess collections inside your Wordpress website.

## About

### About WP-CollectiveAccess
WP-CollectiveAccess is a wordpress plugin to display collections informations & media from museum or digital archives 
inside Wordpress, using web services.

WP-CollectiveAccess is a project lead by idéesculture, a small french company, involved in CollectiveAccess development 
through french translation & a dedicated module for Musées de France museums, tiny devs... We provide CollectiveAccess 
services to museum, tourism offices in France & french-speaking countries. 
This project was funded by Pro-Memoria, an italian company providing CollectiveAccess services.

### About CollectiveAccess
CollectiveAccess is an opensource web-based suite of applications providing a framework for management, description, and discovery 
of complex digital and physical collections.
CollectiveAccess is a registered trademark by Whirl-i-Gig in the USA. It has been brought by a collaboration between and 
partner institutions in North America and Europe with projects in 5 continents.

## Installation

### Installing WP-CollectiveAccess

Download WP-CollectiveAccess from github & uncompress it inside wp-content/plugins

### Setup

1. Unzip WP-CollectiveAccess.zip inside the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Adapt configuration through Settings > CollectiveAccess


## Testing

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

## Thanks

### Project team
Our small idéesculture team
Promemoria for this project impulsion & funding

### Many thanks to...
We would like to cite the following projects whose code, fonts or graphics are included or used by this WP-CollectiveAccess plugin :

- [ca-service-wrapper](https://github.com/skeidel/ca-service-wrapper) by Stefan Keidel
- [Virtual Themed Page class](https://gist.github.com/brianoz/9105004) by Brian Coogan
- [Wordpress](http://wordpress.org/) community
- [CollectiveAccess](http://www.collectiveaccess.org/) development team & Whirl-i-Gig