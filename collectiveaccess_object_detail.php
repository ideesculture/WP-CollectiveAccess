<?php
/**
 * Created by PhpStorm.
 * User: gautier
 * Date: 05/07/2014
 * Time: 15:31
 */

require_once(plugin_dir_path( __FILE__ ) ."lib/cawrapper/ItemService.php");

$vp->add('#/collections/object/detail/#i', 'collectiveaccess_object_detail');

function collectiveaccess_object_detail($v, $url)
{
    // extract an id from the URL
    $id = 'none';
    if (preg_match('#object/detail/(\d+)#', $url, $m))
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

    if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };

    // TODO : do not show anything if no password, send an error message on screen

    if ( $url_base && ($id > 0)) {
        $client = new ItemService("http://".$login.":".$password."@".$url_base,"ca_objects","GET",$id);
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
