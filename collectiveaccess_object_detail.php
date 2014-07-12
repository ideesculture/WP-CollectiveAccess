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

    $url_base = empty( $options["url_base"] ) ? 'localhost' : $options["url_base"];
    $login = empty($options["login"]) ? 'admin' : $options["login"];
    $password = empty($options["password"]) ? 'admin' : $options["password"];
    $cache_duration = empty($options["cache_duration"]) ? 3600 : $options["cache_duration"];

    if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };

    // TODO : do not show anything if no password, send an error message on screen

    if ( $url_base && ($id > 0)) {
        $cache_duration=0;
        $client = new ItemServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,$ca_table,"GET",$id);
        $result = $client->request();
        $record = $result->getRawData();
        if(!isset($record["errors"])) {
            $v->title = $record["preferred_labels"]["fr_FR"][0];

            $template = $options[$name_singular."_template"];
            // matching all bundle placements inside the template
            preg_match_all("/\^([a-z0-9\_\.]*)/i",$template,$matches);
            // replacing all bundle placements depending of their types
            foreach($matches[1] as $bundle) {
                // create a dummy var & separator to store temp data for agregation (when multiple values or relations)
                $bundle_value ="";
                $separator = " ; ";

                $bundle_parts = explode(".",$bundle);

                switch($bundle_parts[0]) {
                    //for representations, we have two allowed types : primary & nonprimary, we need to run all the representations to filter
                    case "representations":
                        if (is_array($record["representations"])) {
                            foreach($record["representations"] as $representation) {
                                if (($bundle_parts[1] == "primary" ) && ($representation["is_primary"] == true)) {
                                    if ($bundle_parts[2] == "urls")
                                        $template = str_replace("^".$bundle,$representation["urls"]["preview170"],$template);
                                }
                            }
                        }
                        break;
                    case "related" :
                        switch($bundle_parts[1]) {
                            case "ca_entities" :
                                if (isset($record["related"]["ca_entities"]))
                                foreach($record["related"]["ca_entities"] as $entity) {
                                    if ($bundle_value) $bundle_value .= $separator;
                                    $bundle_value .= "<a href=\"/collections/entity/detail/".$entity["entity_id"]."\">".$entity["displayname"]."</a>";
                                }
                                $template = str_replace("^".$bundle,$bundle_value,$template);
                                break;
                            case "ca_objects" :
                                if (isset($record["related"]["ca_objects"]))
                                    //var_dump($record["related"]["ca_objects"]);die();
                                    foreach($record["related"]["ca_objects"] as $object) {
                                        if ($bundle_value) $bundle_value .= $separator;
                                        $bundle_value .= "<a href=\"/collections/object/detail/".$object["object_id"]."\">".$object["name"]."</a>";
                                    }
                                $template = str_replace("^".$bundle,$bundle_value,$template);
                                break;
                        }
                        break;
                    case "ca_objects" :
                        // next line : error protection when the bundle code doesn't give anything back
                        if($record[$bundle_parts[0].".".$bundle_parts[1]]) {
                            foreach($record[$bundle_parts[0].".".$bundle_parts[1]] as $bundle_content) {
                                if ($bundle_value) $bundle_value .= $separator;
                                if(isset($bundle_content["fr_FR"])) $bundle_value .= $bundle_content["fr_FR"][$bundle_parts[1]];
                                if(isset($bundle_content["none"])) $bundle_value .= $bundle_content["none"][$bundle_parts[1]];
                            }
                        }
                        $template = str_replace("^".$bundle,$bundle_value,$template);
                        break;
                    case "idno" :
                        $template = str_replace("^".$bundle,$record["idno"]["value"],$template);
                        break;
                }
            }
            //var_dump($record);
            //die();
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
                //$v->body .= "<p><img src=\"".$representation[urls][preview170]."\"></p>";
                $wp_ca_thumbnail = "<div style=\"max-height:600px;min-height:400px;width:100%;position:relative;overflow:hidden;\"><img style=\"position:absolute;width:100%;\" src=\"".$r_large_url."\"></div>";
                add_filter('post_thumbnail_html',
                    function($html, $post_id, $post_thumbnail_id, $size, $attr) use ($wp_ca_thumbnail) {
                        if ($wp_ca_thumbnail) return $wp_ca_thumbnail;
                        return $html;
                    },
                    99, 5);
            }
            // template insertion
            $v->body .= $template;
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

