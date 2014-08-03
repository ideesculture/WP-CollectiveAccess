<?php
/**
 * WP-CollectiveAccess
 * User: gautier
 * Date: 05/07/2014
 * Time: 15:31
 */
function collectiveaccess_getpreviewimage($ca_table,$id)
{
    global $wpdb;

    $options = get_option('collectiveaccess_options');

    $url_base = empty($options["url_base"]) ? 'localhost' : $options["url_base"];
    $login = empty($options["login"]) ? 'admin' : $options["login"];
    $password = empty($options["password"]) ? 'admin' : $options["password"];
    $cache_duration = $options["cache_duration"];

    if ($url_base && ($id > 0)) {
        $client = new ItemServiceCache($wpdb, $cache_duration, "http://" . $login . ":" . $password . "@" . $url_base, $ca_table, "GET", $id);
        $result = $client->request();
        $record = $result->getRawData();
        if (!isset($record["errors"])) {
            if (is_array($record["representations"])) {
                foreach ($record["representations"] as $representation) {
                    if ($representation["is_primary"] == true) return $representation["urls"]["preview170"];
                }
            }
        }
    }
    return false;
}

function collectiveaccess_getchildrenrecords($ca_table,$id)
{
    global $wpdb;

    $options = get_option('collectiveaccess_options');

    $url_base = empty($options["url_base"]) ? 'localhost' : $options["url_base"];
    $login = empty($options["login"]) ? 'admin' : $options["login"];
    $password = empty($options["password"]) ? 'admin' : $options["password"];
    $cache_duration = $options["cache_duration"];

    if ($url_base && ($id > 0)) {
        $query = "parent_id:".$id;
        $r_client = new SearchServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,$ca_table,$query);
        $r_result = $r_client->request();
        $r_record = $r_result->getRawData();
        if(isset($r_record["results"])) {
            foreach($r_record["results"] as $result) {
                    $subcontent_view = new simpleview_idc("collectiveaccess_hierarchy_item", $wordpress_theme);
                    $subcontent_view->setVar("id",$result["id"]);
                    $subcontent_view->setVar("label",$result["display_label"]);
                    $subcontent_view->setVar("table",$ca_table);
                    $results .= $subcontent_view->render();
                }
            return $results;
        } else {
            return false;
        }
    }
    return false;
}

function collectiveaccess_empty_cache() {
    global $wpdb;
    $prefix=$wpdb->prefix;
        
    $db_query = "TRUNCATE TABLE {$prefix}collectiveaccess_cache;";
    $db_results = $wpdb->query($db_query);
    return true;
}
