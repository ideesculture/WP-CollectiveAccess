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

    // extract an id from the URL
    $options = get_option('collectiveaccess_options');

    $url_base = empty($options["url_base"]) ? 'localhost' : $options["url_base"];
    $login = empty($options["login"]) ? 'admin' : $options["login"];
    $password = empty($options["password"]) ? 'admin' : $options["password"];
    $cache_duration = $options["cache_duration"];

    if ($url_base && ($id > 0)) {
        $cache_duration = 0;
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

