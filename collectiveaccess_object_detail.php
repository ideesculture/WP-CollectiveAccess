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
    global $wp_ca_thumbnail;

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
            $v->body = "<p>contenu</p>";
            if ($record["representations"]) {
                // Extracting representation info from CA
                $representation = reset($record["representations"]);
                $representation_id = $representation["representation_id"];
                $r_client = new ItemServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,"ca_object_representations","GET",$representation_id);
                $r_record = $r_client->request()->getRawData();
                $r_large_infos = $r_record["media"]["value"]["large"];
                $r_large_url = "http://".$url_base."/media/musee/".$r_large_infos["VOLUME"]."/".$r_large_infos["HASH"]."/".$r_large_infos["MAGIC"]."_".$r_large_infos["FILENAME"];
                //var_dump($r_large_url);
                //die();
                //
                // Generating body
                $v->body .= "<p><img src=\"".$representation[urls][preview170]."\"></p>";
                $wp_ca_thumbnail = "<div style=\"max-height:600px;min-height:400px;position:relative;overflow:hidden;\"><img style=\"position:absolute;margin-top:-50%\" src=\"".$r_large_url."\"></div>";
                add_filter('post_thumbnail_html',
                    function($html, $post_id, $post_thumbnail_id, $size, $attr) use ($wp_ca_thumbnail) {
                        if ($wp_ca_thumbnail) return $wp_ca_thumbnail;
                        return $html;
                    },
                    99, 5);
            }
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
    //var_dump($v);
}

