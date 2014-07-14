<?php
/**
 * Created by PhpStorm.
 * User: gautier
 * Date: 05/07/2014
 * Time: 15:31
 */
require_once(plugin_dir_path( __FILE__ ) ."lib/virtualthemedpages/Virtual_Themed_Pages_BC.php");
$vp =  new Virtual_Themed_Pages_BC();


require_once(plugin_dir_path( __FILE__ ) ."lib/cawrappercache/BrowseServiceCache.php");

$vp->add('#/collections/objects/browse#i', 'collectiveaccess_objects_browse');

function collectiveaccess_objects_browse($v, $url){
    collectiveaccess_browse("objects","ca_objects",$v, $url);
}

function collectiveaccess_browse($name_plural,$ca_table,$v, $url)
{
	global $wpdb;

    $v->template = 'page'; // optional
    $v->subtemplate = 'collections'; // optional

    $options = get_option('collectiveaccess_options');

    $url_base = empty( $options[url_base] ) ? 'localhost' : $options[url_base];
    $login = empty($options[login]) ? 'admin' : $options[login];
    $password = empty($options[password]) ? 'admin' : $options[password];
    $cache_duration = empty($options[cache_duration]) ? 3600 : $options[cache_duration];

    if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };

    // TODO : do not show anything if no password, send an error message on screen

    if ( $url_base ) {

        // Disabling Wordpress HTML sanitization to avoid having <p></p> coming everywhere
        remove_filter( 'the_content', 'wpautop' );

        $client = new BrowseServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,$ca_table,"OPTIONS");

        // if we have a POST, then we have criterias, so filter facets
        if(!empty($_POST)) {
            $client->setRequestBody(array("criteria" => $_POST));
        }

        $client_result = $client->request();
        $results = $client_result->getRawData();
        $body = "";
        $body =
"<script type='text/javascript'>
jQuery(document).ready(function(){
    jQuery('p.facetname').click(function(){
       jQuery(this).next().slideToggle();
       jQuery(this).toggleClass('collapsed');
       return false;
    });
});
</script>";
        $body .= "<form name='browse_facets' action='{$url}' method='POST'>";
        foreach($results as $facet_type => $facet_options) {
            //var_dump($va_option);
            $body .= "<p style='text-transform: uppercase;' class='facetname' onclick='slideToggle();'>".$facet_options['label_singular']."</p>\n";
            $body .= "<p class='facetcontent' style='display: none;'>";
            foreach($facet_options["content"] as $label => $content) {
                $body .= "<input type='checkbox' name='{$facet_type}[]' value='{$content["id"]}'> {$content["label"]}<br/>\n";
            }
            $body .= "</p>";
            //$facets[] = "<a href=#>".$facet_options['label_singular']."</a>";
        }
        $body .= "<input type='submit' value='Browse'/></form><br/>";
        $v->title = "Browse ".$name_plural;
        //$v->body = "<b>facets</b>\n".implode(" - ",$facets);


        // if we have a POST, then we have criterias, so display results
        if(!empty($_POST)) {
            $client = new BrowseServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,$ca_table,"GET");
            $client->setRequestBody(array("criteria" => $_POST));

            $client_result = $client->request();
            $results = $client_result->getRawData();
            //var_dump($results);die();
            if(!isset($results["results"])) {
                $body .= "<p>No result</p>\n";
            } else {
                $body .= "<ul>";
                foreach($results["results"] as $result) {
                    $body .= "<li>".$result["display_label"]."</li>";
                }
                $body .= "</ul>";
            }
        }

        $v->body = $body;

        // If we have submitted facets, fetch the corresponding CA datas
        if (!empty($_POST)) {

        }
    } else  {
        $v->title = "Error";
        $v->body = "Configuration error";
    }

}
