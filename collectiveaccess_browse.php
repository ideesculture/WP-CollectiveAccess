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
    $cssAndScript =
"<style>
figure.gallery-item p {display:none;}

.collectiveaccess-cropping-image {
    height:170px;
    width:150px;
    overflow:hidden;
    background-color: black;
    text-align: center;
    vertical-align: middle;
    display:table-cell;
}
.collapsed {
}
p.facetname {
    border-top:1px solid lightgray;
    margin-bottom:10px;
    margin-top:20px;
}
p.facetname:first-child {
    border:none;
}
.facetname, .subfacetname {
    cursor:pointer;    
}
.subfacet {
    margin-right:10px;
}
.subfacet.collapsed:before {
    content : '+';
}
.subfacet:before {
    content : '-';
    background-color: #24890d;
    border: 0;
    border-radius: 2px;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    padding: 2px 4px 2px 4px;
    margin-right:3px;
    text-transform: uppercase;
    width:25px;
    display:inline-box;
    min-width:25px;
}

</style>
<script type='text/javascript'>
    jQuery(document).ready(function(){
        jQuery('p.facetname').click(function(){
           jQuery(this).next().slideToggle();
           jQuery(this).toggleClass('collapsed');
           return true;
        });
        jQuery('span.subfacetname').click(function(){
            var facetref = jQuery(this).data().facettype + '-' + jQuery(this).data().facetname;
            console.log(facetref);
            jQuery('p.subfacetcontent.' + facetref).slideToggle();
            jQuery(this).toggleClass('collapsed');
            return true;
        });        
    });
    jQuery(document).ready(function() {
        jQuery('img.attachment-thumbnail').each(function() {
            var getimage_table = jQuery(this).data(\"table\");
            var getimage_id = jQuery(this).data(\"id\");
            var jquery_this = jQuery(this);
            jQuery.ajax({
                type:'POST',
                dataType: 'text',
                url:'" . get_site_url() . "/wp-content/plugins/WP-CollectiveAccess/collectiveaccess_ajax_handler.php',
                data: {
                    action:'getimage',
                    table:getimage_table,
                    id:getimage_id
                },
                success:function(response){
                    if(response != '-1') {
                        //console.log(response);
                        jquery_this.attr('src',JSON.parse(response));
                    } else {
                        jquery_this.removeAttr('src');
                    }
                }
            });
        });
    });

</script>";

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
        }

        if(($criterias)) {
            $body .= '<div class="entry-meta"><span class="tag-links">';
            foreach ($criterias as $criteria_key => $criteria) {
                foreach($criteria as $criteria_value_key => $criteria_value) {
                    $body .= "<a style='text-decoration:none;' onclick=\"document.forms['browse_facets'].removecriteria.value = '{$criteria_key}__{$criteria_value}';\" >".
                        $criterias_names[$criteria_key][$criteria_value_key].
                        " <u>x</u>".
                        "</a>";
                }
            }
            $body .= '</span></div>';
            $body .= "<div><a class=button href={$url}>Remove all criterias</a></div>";
            //die();
        }


        $client = new BrowseServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,$ca_table,"OPTIONS");

        // if we have a POST, then we have criterias, so filter facets
        if(!empty($criterias)) {
            $client->setRequestBody(array("criteria" => $criterias));
        }

        $client_result = $client->request();
        $results = $client_result->getRawData();
        $body .= "<form name='browse_facets' action='{$url}' method='POST'>";
        if(!empty($criterias)) {
            $body .= "<input type=hidden size=80 name=query value=\"".htmlentities(json_encode($uncleaned_criterias))."\" />\n";
            $body .= "<input type=hidden size=80 name=page value=\"{$page}\" />\n";
            $body .= "<input type=hidden size=80 name=removecriteria />\n";
        }
        //var_dump($results);die();
        foreach($results as $facet_type => $facet_options) {
            $body .= "<p style='text-transform: uppercase;' class='facetname'>".$facet_options['label_singular']."</p>\n";
            $body .= "<div class='facetcontent collapsed' style='display: none;'>";
            $subfacets = "";
            foreach($facet_options["content"] as $label => $content) {
                if(isset($content["id"])) {
                    $body .= "<input type='checkbox' name='{$facet_type}[]' value='{$content["id"]}__{$content["label"]}'> {$content["label"]}<br/>\n";    
                } else {
                    // No ID defined, we have an intermediate grouping array
                    $content_id = $content["id"];
                    $body .= "<span class='subfacet subfacetname collapsed' data-facetname='{$label}' data-facettype='{$facet_type}'><b>{$label}</b></span> ";
                    $subfacets .= "<p class='subfacetcontent {$facet_type}-{$label} collapsed' style='display: none;'>";
                    foreach($content as $subcontent) {
//                        var_dump($subcontent["label"]);die();
                        $subfacets .= "<input style='display:inline-box;border:1px solid black;' type='checkbox' name='{$facet_type}[]' value='". $subcontent["id"]."__".$subcontent["label"]."'> ". $subcontent["label"] ."<br/>\n";    
                    }
                    $subfacets .= "</p>";
                }
            }
            $body .= $subfacets;
            $body .= "</div>";
            //$facets[] = "<a href=#>".$facet_options['label_singular']."</a>";
        }
        // Display Browse button only if we still have facets to display
        if ($results && !$criterias) 
            $body .= "<input type='submit' value='Browse'/><br/>";
        elseif ($results)
            $body .= "<input type='submit' value='Filter'/><br/>";

        //$v->body = "<b>facets</b>\n".implode(" - ",$facets);


        // if we have a POST, then we have criterias, so display results
        if(!empty($criterias)) {
            $client = new BrowseServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,$ca_table,"GET");
            $client->setRequestBody(array("criteria" => $criterias));

            $client_result = $client->request();
            $results = $client_result->getRawData();

            if(!isset($results["results"])) {
                $body .= "<p>No result</p>\n";
            } else {

                $num_results = (int) count($results["results"]);
                $num_per_page = 12 ;
                $pages = ceil($num_results / $num_per_page);

                $body .= "<ul>";
                $i=1;
                foreach($results["results"] as $result) {
                    if (ceil($i/$num_per_page) == $page) {
                        $vignettes .= "<figure class='gallery-item'> <div class='gallery-icon landscape'> ";
                        $vignettes .= "<a href=\"".get_site_url()."/collections/object/detail/".$result["id"]."\" > \n";
                        $vignettes .= "<div class='collectiveaccess-cropping-image'><img class=\"attachment-thumbnail\" data-table=\"ca_objects\" data-id=\"".$result["id"]."\"/> </span>\n";
                        $vignettes .= "</a> </div><figcaption class='wp-caption-text gallery-caption'>";
                        $vignettes .= $result["display_label"].($result["idno"]? " <small>(".$result["idno"].")</small>":"");
                        $vignettes .= "</figcaption></figure>";

                        //$body .= "<li>" . $result["display_label"] . "</li>";
                    }
                    $i++;
                }
                $body .= "</ul>";
            }

            // Page navigation
            $vignettes .= "<div class=\"page-links\"><span class=\"page-links-title\">Pages:</span>\n";
            // Inserting hidden form for page navigation, keeping query
            $vignettes .=
                "</FORM>";
            // Inserting page links
            for ($i = 1; $i <= $pages; $i++) {
                $vignettes .= "<a href='#' onclick=\"document.forms['browse_facets'].page.value = $i;document.forms['browse_facets'].submit();\"><span>".$i."</span></a>\n";
            }
            $vignettes .= "</div>";
        }

        $v->title = "Browse ".$name_plural;
        $v->body = $cssAndScript.$body.$vignettes;

    } else  {
        $v->title = "Error";
        $v->body = "Configuration error";
    }

}
