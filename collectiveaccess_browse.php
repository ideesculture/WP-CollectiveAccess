<?php
/**
 * Created by PhpStorm.
 * User: gautier
 * Date: 05/07/2014
 * Time: 15:31
 */

require_once(plugin_dir_path( __FILE__ ) ."lib/cawrappercache/BrowseServiceCache.php");

$vp->add('#/collections/objects/browse#i', 'collectiveaccess_objects_browse');

function collectiveaccess_objects_browse($v, $url){
    collectiveaccess_browse("objects","ca_objects",$v, $url);
}

function collectiveaccess_browse($name_plural,$ca_table,$v, $url)
{
	global $wpdb;

    $v->template = 'page'; // optional
    $v->subtemplate = 'collections'; // optional

    $options = get_option('collectiveaccess_options');

    $url_base = empty( $options[url_base] ) ? 'localhost' : $options[url_base];
    $login = empty($options[login]) ? 'admin' : $options[login];
    $password = empty($options[password]) ? 'admin' : $options[password];
    $cache_duration = empty($options[cache_duration]) ? 3600 : $options[cache_duration];

    if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };

    // TODO : do not show anything if no password, send an error message on screen

    if ( $url_base ) {
        $client = new BrowseServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,$ca_table,"OPTIONS");
        $options = $client->request();
        $va_options = $options->getRawData();
        foreach($va_options as $va_option) {
            //var_dump($va_option);
            $facets[] = "<a href=#>".$va_option['label_singular']."</a>";
        }
        $v->title = "Browse ".$name_plural;
        $v->body = "<b>facets</b>\n".implode(" - ",$facets);
    } else  {
        $v->title = "Error";
        $v->body = "Configuration error";
    }

}
