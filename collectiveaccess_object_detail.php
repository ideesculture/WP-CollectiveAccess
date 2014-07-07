<?php
/**
 * Created by PhpStorm.
 * User: gautier
 * Date: 05/07/2014
 * Time: 15:31
 */

require_once(plugin_dir_path( __FILE__ ) ."lib/cawrappercache/ItemServiceCache.php");

$vp->add('#/collections/object/detail#i', 'collectiveaccess_object_detail');
$vp->add('#/collections/entity/detail#i', 'collectiveaccess_entity_detail');
$vp->add('#/collections/place/detail#i', 'collectiveaccess_place_detail');
$vp->add('#/collections/collection/detail#i', 'collectiveaccess_collection_detail');

function collectiveaccess_object_detail($v, $url){
    collectiveaccess_detail("object","ca_objects",$v, $url);
}
function collectiveaccess_entity_detail($v, $url){
    collectiveaccess_detail("entity","ca_entities",$v, $url);
}
function collectiveaccess_place_detail($v, $url){
    collectiveaccess_detail("place","ca_places",$v, $url);
}
function collectiveaccess_collection_detail($v, $url){
    collectiveaccess_detail("collection","ca_collections",$v, $url);
}

function collectiveaccess_detail($name_singular,$ca_table,$v, $url)
{
	global $wpdb;

    // extract an id from the URL
    $id = 'none';
    if (preg_match('#'.$name_singular.'/detail/(\d+)#', $url, $m))
        $id = $m[1];
    // could wp_die() if id not extracted successfully...
    if($id=="none") $id=0;

    $v->template = 'page'; // optional
    $v->subtemplate = 'collections'; // optional

    $options = get_option('collectiveaccess_options');
    //var_dump($options);die();

    $url_base = empty( $options[url_base] ) ? 'localhost' : $options[url_base];
    $login = empty($options[login]) ? 'admin' : $options[login];
    $password = empty($options[password]) ? 'admin' : $options[password];
    $cache_duration = empty($options[cache_duration]) ? 3600 : $options[cache_duration];

    if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };

    // TODO : do not show anything if no password, send an error message on screen

    if ( $url_base && ($id > 0)) {
        $client = new ItemServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,$ca_table,"GET",$id);
        $result = $client->request();
        $record = $result->getRawData();
        if(!isset($record["errors"])) {
            $v->title = $record["preferred_labels"]["fr_FR"][0];
            $v->body = "<p><img src=\"".$record["representations"][1][urls][preview170]."\"></b></p>";
        } else {
            $v->title = "Error";
            foreach($record["errors"] as $error) {
                if ($v->body) $v->body .= "<br/>";
                $v->body .= $error;
            }
        }
    } elseif(!$id) {
        $v->title = "Error";
        $v->body = "No ID provided";
    } elseif(!$url_base) {
        $v->title = "Error";
        $v->body = "Configuration error";
    }

}
