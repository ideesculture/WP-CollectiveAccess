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

function collectiveaccess_objects_search($v, $url){
    collectiveaccess_search("objects","ca_objects",$v, $url);
}

function collectiveaccess_search($name_plural,$ca_table,$v, $url)
{
    global $wpdb;

    // getting query from post or get
    if (!($query=$_POST["query"])) $query=$_GET["query"];
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

        $client = new SearchServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,"ca_objects",$query);
        $request = $client->request();
        $result_data = $request->getRawData();
        $result_data = $result_data["results"];

        $num_results = (int) count($result_data);
        $num_per_page = 12 ;
        $pages = ceil($num_results / $num_per_page);
        //var_dump($page);die();
        $i = 1;
        foreach($result_data as $result) {
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

        // Page navigation
        $pagination_subview = new simpleview_idc("collectiveaccess_pagination", $wordpress_theme);
        $pagination_subview->setVar("pages",$pages);
        $pagination_subview->setVar("formname","page");
        $pagination = $pagination_subview->render();

        $v->title = "Results for ".$query;

        $content_view = new simpleview_idc("collectiveaccess_search", $wordpress_theme);
        $content_view->setVar("num_results",$num_results);
        $content_view->setVar("thumbnails",$thumbnails);
        $content_view->setVar("pagination",$pagination);
        $v->body = $content_view->render();

    } elseif (!$url_base) {
        $v->title = "Error";
        $v->body = "Configuration error, no url_base defined. Please check your settings.";
    } else {
        $v->title = "Error";
        $v->body = "No query sent.";
    }




    //var_dump($query);die();
}

