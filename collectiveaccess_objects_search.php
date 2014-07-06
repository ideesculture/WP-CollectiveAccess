<?php
/**
 * Created by PhpStorm.
 * User: gautier
 * Date: 05/07/2014
 * Time: 15:32
 */

require_once(plugin_dir_path( __FILE__ ) ."lib/cawrapper/SearchService.php");


$vp->add('#/collections/objects/search#i', 'collectiveaccess_search_results');

function collectiveaccess_search_results($v, $url)
{
    // getting query from post or get
    if (!($query=$_POST["query"])) $query=$_GET["query"];
    // if not GET or POST query, trying to extract from route
    // if ((!$query) && (preg_match('#search/([^\"\r\n]*)#', $url, $m))) $query = $m[1];

    $v->template = 'page'; // optional
    $v->subtemplate = 'collections'; // optional

    $options = get_option('collectiveaccess_options');
    //var_dump($options);die();

    $url_base = empty( $options[url_base] ) ? 'localhost' : $options[url_base];
    $login = empty($options[login]) ? 'admin' : $options[login];
    $password = empty($options[password]) ? 'admin' : $options[password];



    if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };

    // TODO : do not show anything if no password, send an error message on screen

    if ($url_base && $query) {
        $client = new SearchService("http://".$login.":".$password."@".$url_base,"ca_objects",$query);
        $request = $client->request();
        $result_data = $request->getRawData()["results"];
        //var_dump($result_data);die();
        $body .= "<p>".count($result_data)." results</p>";
        $body .= "<ul>";
        foreach($result_data as $result) {
            $body .= "<li><a href=\"".get_site_url()."/collections/object/detail/".$result["id"]."\" >".$result["display_label"].($result["idno"]? " <small>(".$result["idno"].")</small>":"")."</a></li>\n";
        }
        $body .= "</ul>";
    } elseif (!$url_base) {
        $v->title = "Error";
        $v->body = "Configuration error";
    }


    $v->title = "Results for ".$query;
    $v->body = $body;
    //var_dump($query);die();
}