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

$vp->add('#/collections/objects/search#i', 'collectiveaccess_objects_search');
$vp->add('#/collections/entities/search#i', 'collectiveaccess_entities_search');
$vp->add('#/collections/places/search#i', 'collectiveaccess_places_search');
$vp->add('#/collections/occurrences/search#i', 'collectiveaccess_occurrences_search');
$vp->add('#/collections/collections/search#i', 'collectiveaccess_collections_search');

function collectiveaccess_objects_search($v, $url){
    collectiveaccess_search("objects","ca_objects",$v, $url);
}
function collectiveaccess_entities_search($v, $url){
    collectiveaccess_search("entities","ca_entities",$v, $url);
}
function collectiveaccess_places_search($v, $url){
    collectiveaccess_search("places","ca_places",$v, $url);
}
function collectiveaccess_occurrences_search($v, $url){
    collectiveaccess_search("occurrences","ca_occurrences",$v, $url);
}
function collectiveaccess_collections_search($v, $url){
    collectiveaccess_search("collections","ca_collections",$v, $url);
}

function collectiveaccess_search($name_plural,$ca_table,$v, $url)
{
    global $wpdb;

    // getting query from post or get
    if (!($query=$_POST["query"])) {
        $query=$_GET["query"];
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

    if ($url_base && $query) {

        // Disabling Wordpress HTML sanitization to avoid having <p></p> coming everywhere
        remove_filter( 'the_content', 'wpautop' );

        $client = new SearchServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,$ca_table,$query);
        $request = $client->request();
        $result_data = $request->getRawData();
        $result_data = $result_data["results"];

        $num_results = (int) count($result_data);
        $num_per_page = 12 ;
        $pages = ceil($num_results / $num_per_page);
        $i = 1;
        // If we have results...
        if(count($result_data)>0) {
            foreach($result_data as $result) {
                // For each results included in the page limit, compute the thumbnail subview
                if (ceil($i/$num_per_page) == $page) {
                    $thumbnail_subview = new simpleview_idc("collectiveaccess_thumbnail", $wordpress_theme);
                    $thumbnail_subview->setVar("id",$result["id"]);
                    $thumbnail_subview->setVar("display_label",$result["display_label"]);
                    $thumbnail_subview->setVar("idno",$result["idno"]);
                    $thumbnail_subview->setVar("ca_table",$ca_table);
                    $thumbnails .= $thumbnail_subview->render();
                }
                $i++;
            }
        }
        // Page navigation
        $pagination_subview = new simpleview_idc("collectiveaccess_pagination", $wordpress_theme);
        $pagination_subview->setVar("pages",$pages);
        $pagination_subview->setVar("formname","page");
        $pagination = $pagination_subview->render();
        if (isset($title)) {
            $v->title = $title;
        } else {
            $v->title = __("Results for ", 'collectiveaccess').$query;
        }
        // if a special view name has been defined, use it instead of the default one
        $content_view = new simpleview_idc(($view ? $view : "collectiveaccess_search"), $wordpress_theme);
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

        $content_view->setVar("num_results",$num_results);
        $content_view->setVar("thumbnails",$thumbnails);
        $content_view->setVar("pagination",$pagination);
        $v->body = $content_view->render();

    } elseif (!$url_base) {
        $v->title = __("Error", 'collectiveaccess');
        $v->body = __("Configuration error, no url_base defined. Please check your settings.", 'collectiveaccess');
    } else {
        $v->title = __("Error", 'collectiveaccess');
        $v->body = __("No query sent.", 'collectiveaccess');
    }




    //var_dump($query);die();
}

