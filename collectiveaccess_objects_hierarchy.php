<?php
/**
 * Created by PhpStorm.
 * User: gautier
 * Date: 05/07/2014
 * Time: 15:32
 */
require_once(plugin_dir_path( __FILE__ ) ."lib/virtualthemedpages/Virtual_Themed_Pages_BC.php");
$vp =  new Virtual_Themed_Pages_BC();

require_once(plugin_dir_path( __FILE__ ) ."lib/cawrappercache/SearchServiceCache.php");
require_once(plugin_dir_path( __FILE__ ) ."lib/cawrappercache/ItemServiceCache.php");

$vp->add('#/collections/objects/hierarchy#i', 'collectiveaccess_objects_hierarchy');
$vp->add('#/collections/entities/hierarchy#i', 'collectiveaccess_entities_hierarchy');
$vp->add('#/collections/places/hierarchy#i', 'collectiveaccess_places_hierarchy');
$vp->add('#/collections/occurrences/hierarchy#i', 'collectiveaccess_occurrences_hierarchy');
$vp->add('#/collections/collections/hierarchy#i', 'collectiveaccess_collections_hierarchy');

function collectiveaccess_objects_hierarchy($v, $url){
    collectiveaccess_hierarchy("objects","ca_objects",$v, $url);
}
function collectiveaccess_entities_hierarchy($v, $url){
    collectiveaccess_hierarchy("entities","ca_entities",$v, $url);
}
function collectiveaccess_places_hierarchy($v, $url){
    collectiveaccess_hierarchy("places","ca_places",$v, $url);
}
function collectiveaccess_occurrences_hierarchy($v, $url){
    collectiveaccess_hierarchy("occurrences","ca_occurrences",$v, $url);
}
function collectiveaccess_collections_hierarchy($v, $url){
    collectiveaccess_hierarchy("collections","ca_collections",$v, $url);
}

function collectiveaccess_hierarchy($name_plural,$ca_table,$v, $url)
{
    global $wpdb;

    // getting type from post or get
    if (!($type=$_POST["type"])) {
        $type=$_GET["type"];
    }
    // getting type from post or get
    if (!($root=$_POST["root"])) {
        $root=$_GET["root"];
    }
    // getting title to overwrite default by post or get
    if (!($title=$_POST["title"])) {
        $title=$_GET["title"];
    }
    // getting view name to replace collectiveaccess_search
    if (!($view=$_POST["view"])) {
        if (!($view=$_GET["view"])) $view = null;
    }
    // getting header image to replace default-featured-image
    if (!($headerimage=$_POST["headerimage"])) {
        if (!($headerimage=$_GET["headerimage"])) $headerimage = null;
    }
    
    if (!($page=$_POST["page"])) $page=1;

    $v->template = 'page'; // optional
    $v->subtemplate = 'collections'; // optional

    $options = get_option('collectiveaccess_options');

    $url_base = empty( $options["url_base"] ) ? 'localhost' : $options["url_base"];
    $login = empty($options["login"]) ? 'admin' : $options["login"];
    $password = empty($options["password"]) ? 'admin' : $options["password"];
    $cache_duration = empty($options["cache_duration"]) ? 3600 : $options["cache_duration"];

    // TODO : do not show anything if no password, send an error message on screen

    if ($url_base && ($type || $root)) {
        // Disabling Wordpress HTML sanitization to avoid having <p></p> coming everywhere
        remove_filter( 'the_content', 'wpautop' );

        if($type) {
            $client = new SearchServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,"ca_list_items","idno:".$type);
            $request = $client->request();
            $result_data = $request->getRawData();
            $result_data=$result_data["results"];
            // if we find only one suitable type in list_items
            if((count($result_data) == 1) && (isset(reset($result_data)["id"]))) {
                $type_id = reset($result_data)[id];    
                $client = new SearchServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,$ca_table,"type_id:".$type_id);
                $request = $client->request();
                $result_data = $request->getRawData();
                $result_data = $result_data["results"];
            }
        } elseif($root) {
            $client = new ItemServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,$ca_table,"GET",$root);
            $request = $client->request();
            $result_data = $request->getRawData();
            $results .= "<h2>".reset(reset($result_data["preferred_labels"]))."</h2>";
            $client = new SearchServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,$ca_table,"parent_id:".$root);
            $request = $client->request();
            $result_data = $request->getRawData();
            $result_data = $result_data["results"];
        }

        foreach($result_data as $result) {
            $subcontent_view = new simpleview_idc("collectiveaccess_hierarchy_item", $wordpress_theme);
            $subcontent_view->setVar("id",$result["id"]);
            $subcontent_view->setVar("label",$result["display_label"]);
            $subcontent_view->setVar("table",$ca_table);
            $results .= $subcontent_view->render();
        }

        if (isset($title)) {
            $v->title = $title;
        } else {
            $v->title = __("Explore the tree of the collections", 'collectiveaccess');
        }
        // if a special view name has been defined, use it instead of the default one
        $content_view = new simpleview_idc(($view ? $view : "collectiveaccess_hierarchy"), $wordpress_theme);
        $content_view->setVar("results",$results);
        // if a header image is defined, override default-featured-image
        if ($headerimage) {
            $wp_ca_thumbnail = "<div style=\"max-height:600px;min-height:400px;width:100%;position:relative;overflow:hidden;\"><img style=\"position:absolute;width:100%;\" src=\"".$headerimage."\"></div>";
            add_filter(
                'post_thumbnail_html',
                function($html, $post_id, $post_thumbnail_id, $size, $attr) use ($wp_ca_thumbnail) {
                    if ($wp_ca_thumbnail) return $wp_ca_thumbnail;
                    return $html;
                },
                99, 5);
        }

        $v->body = $content_view->render();

    } elseif (!$url_base) {
        $v->title = __("Error", 'collectiveaccess');
        $v->body = __("Configuration error, no url_base defined. Please check your settings.", 'collectiveaccess');
    } else {
        $v->title = __("Error", 'collectiveaccess');
        $v->body = __("No type sent.", 'collectiveaccess');
    }




    //var_dump($type);die();
}

