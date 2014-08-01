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
$vp->add('#/collections/entities/browse#i', 'collectiveaccess_entities_browse');
$vp->add('#/collections/places/browse#i', 'collectiveaccess_places_browse');
$vp->add('#/collections/occurrences/browse#i', 'collectiveaccess_occurrences_browse');
$vp->add('#/collections/collections/browse#i', 'collectiveaccess_collections_browse');

function collectiveaccess_objects_browse($v, $url){
    collectiveaccess_browse("objects","ca_objects",$v, $url);
}
function collectiveaccess_entities_browse($v, $url){
    collectiveaccess_browse("entities","ca_entities",$v, $url);
}
function collectiveaccess_places_browse($v, $url){
    collectiveaccess_browse("places","ca_places",$v, $url);
}
function collectiveaccess_occurrences_browse($v, $url){
    collectiveaccess_browse("occurrences","ca_occurrences",$v, $url);
}
function collectiveaccess_collections_browse($v, $url){
    collectiveaccess_browse("collections","ca_collections",$v, $url);
}


function collectiveaccess_browse($name_plural,$ca_table,$v, $url)
{
	global $wpdb;

    $v->template = 'page'; // optional
    $v->subtemplate = 'collections'; // optional

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

    $wordpress_theme = basename(get_template_directory());
    $options = get_option('collectiveaccess_options');

    $url_base = empty( $options[url_base] ) ? 'localhost' : $options[url_base];
    $login = empty($options[login]) ? 'admin' : $options[login];
    $password = empty($options[password]) ? 'admin' : $options[password];
    $cache_duration = empty($options[cache_duration]) ? 3600 : $options[cache_duration];

    if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };

    // TODO : do not show anything if no password, send an error message on screen

    if ( $url_base ) {

        // Defaulting to display page 1
        $page = 1;
        $body = "";

        // Disabling Wordpress HTML sanitization to avoid having <p></p> coming everywhere
        remove_filter( 'the_content', 'wpautop' );

        // If we have a post, we have some criterias passed
        if(!empty($_POST)) {
            // We may have some former criterias passed (they are json stringified inside a hidden input in the page, no cookie needed for now)
            $uncleaned_criterias = $_POST;
            
            if(isset($uncleaned_criterias["page"])) {
                $page = (int) $uncleaned_criterias["page"];
                unset($uncleaned_criterias["page"]);
            }
            
            if($uncleaned_criterias["removecriteria"] !== "") {
                $removecriteria = explode('__',$uncleaned_criterias["removecriteria"]);
                unset($uncleaned_criterias["removecriteria"]);
            }
            // Insure to have a non empty former query
            if($uncleaned_criterias["query"] !== "" && $uncleaned_criterias["query"] !== "null") {
                $previous_query = json_decode(stripslashes($uncleaned_criterias["query"]),true);
            }
            // Removing instruction to remove criteria from the post
            unset($uncleaned_criterias["query"]);
            unset($uncleaned_criterias["removecriteria"]);

            // reinjecting former criterias to last added criterias
            
            //var_dump($uncleaned_criterias);die();
            if (is_array($previous_query)) 
                $uncleaned_criterias = array_merge($uncleaned_criterias,$previous_query);


            //$criterias = $_POST;
            if(isset($removecriteria)) {
                // Removing criteria
                unset($uncleaned_criterias[$removecriteria[0]]);
            }

            foreach($uncleaned_criterias as $ref => $uncleaned_criteria) {
                foreach($uncleaned_criteria as $uncleaned_criteria_value) {
                    $cleaned_criteria_value = explode("__",$uncleaned_criteria_value);
                    $criterias[$ref][] = $cleaned_criteria_value[0];
                    $criterias_names[$ref][] = $cleaned_criteria_value[1];
                }
            }
            $json_uncleaned_criterias = htmlentities(json_encode($uncleaned_criterias));
        }

        if(($criterias)) {
            $i=0;
            foreach ($criterias as $criteria_key => $criteria) {
                foreach($criteria as $criteria_value_key => $criteria_value) {
                    $criterias_for_subview[$i]["key"] = $criteria_key;
                    $criterias_for_subview[$i]["value"] = $criteria_value;
                    $criterias_for_subview[$i]["name"] = $criterias_names[$criteria_key][$criteria_value_key];
                    $i++;
                }
            }
            // Remove criterias subview
            $removecriterias_subview = new simpleview_idc("collectiveaccess_browse_removecriterias", basename(get_template_directory()));
            $removecriterias_subview->setVar("criterias",$criterias_for_subview);
            $removecriterias = $removecriterias_subview->render();
        }


        $client = new BrowseServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,$ca_table,"OPTIONS");

        // if we have a POST, then we have criterias, so filter facets
        if(!empty($criterias)) {
            $client->setRequestBody(array("criteria" => $criterias));
        }

        $client_result = $client->request();
        $facets = $client_result->getRawData();

        // Facets subview
        $facets_subview = new simpleview_idc("collectiveaccess_browse_facets", $wordpress_theme);
        $facets_subview->setVar("facets",$facets);
        $facets_subview->setVar("have_criterias",(count($criterias)>0));
        $facets_subview->setVar("json_uncleaned_criterias",$json_uncleaned_criterias);
        $facets_subview->setVar("url",$url);
        $facets_subview->setVar("page",$page);
        $facets_content = $facets_subview->render();

        //$v->body = "<b>facets</b>\n".implode(" - ",$facets);


        // if we have a POST, then we have criterias, so display results
        if(!empty($criterias)) {
            $client = new BrowseServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,$ca_table,"GET");
            $client->setRequestBody(array("criteria" => $criterias));

            $client_result = $client->request();
            $results = $client_result->getRawData();

            if(isset($results["results"])) {
                $num_results = (int) count($results["results"]);
                $num_per_page = 12 ;
                $pages = ceil($num_results / $num_per_page);

                $i=1;
                foreach($results["results"] as $result) {
                    if (ceil($i/$num_per_page) == $page) {
                        // Creating subviews for each thumbnail
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
            $pagination_subview->setVar("formname","browse_facets");
            $pagination = $pagination_subview->render();
        }
        if (isset($title)) {
            $v->title = $title;
        } else {
            $v->title = __("Browse ", 'collectiveaccess').$name_plural;
        }

        // Creating the view, the theme directory name is used as a prefix to allow theme-specific subviews
        $content_view = new simpleview_idc(($view ? $view : "collectiveaccess_browse"), $wordpress_theme);
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

        $content_view->setVar("thumbnails",$thumbnails);
        $content_view->setVar("pagination",$pagination);
        $content_view->setVar("removecriterias",$removecriterias);        
        $content_view->setVar("facets_content",$facets_content);
        $content_view->setVar("have_results",(count($results)>0));        
        $v->body = $body.$vignettes.$content_view->render();

    } else  {
        $v->title = "Error";
        $v->body = "Configuration error";
    }

}
