<?php
/**
 * Created by PhpStorm.
 * User: gautier
 * Date: 05/07/2014
 * Time: 15:32
 */
require_once(plugin_dir_path( __FILE__ ) ."lib/virtualthemedpages/Virtual_Themed_Pages_BC.php");
$vp =  new Virtual_Themed_Pages_BC();

require_once(plugin_dir_path( __FILE__ ) ."lib/cawrappercache/SearchServiceCache.php");


$vp->add('#/collections/objects/search#i', 'collectiveaccess_search_results');

function collectiveaccess_search_results($v, $url)
{
    global $wpdb;

    // for more comprehensive readability, CSS & JS insertion are included here, PHP function code is after
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
</style>
<script type='text/javascript'>
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

    // getting query from post or get
    if (!($query=$_POST["query"])) $query=$_GET["query"];
    if (!($page=$_POST["page"])) $page=1;

    $v->template = 'page'; // optional
    $v->subtemplate = 'collections'; // optional

    $options = get_option('collectiveaccess_options');
    //var_dump($options);die();

    $url_base = empty( $options["url_base"] ) ? 'localhost' : $options["url_base"];
    $login = empty($options["login"]) ? 'admin' : $options["login"];
    $password = empty($options["password"]) ? 'admin' : $options["password"];
    $cache_duration = empty($options["cache_duration"]) ? 3600 : $options["cache_duration"];

    // TODO : do not show anything if no password, send an error message on screen

    if ($url_base && $query) {
        // Disabling Wordpress HTML sanitization to avoid having <p></p> coming everywhere

        remove_filter( 'the_content', 'wpautop' );

        $client = new SearchServiceCache($wpdb,$cache_duration,"http://".$login.":".$password."@".$url_base,"ca_objects",$query);
        $request = $client->request();
        $result_data = $request->getRawData();
        $result_data = $result_data["results"];
        //var_dump($result_data);die();

        $num_results = (int) count($result_data);
        $num_per_page = 12 ;
        $pages = ceil($num_results / $num_per_page);
        //var_dump($page);die();
        $vignettes .= "<p>".$num_results." results</p>";
        $i = 1;
        foreach($result_data as $result) {
            if (ceil($i/$num_per_page) == $page) {
                $vignettes .= "<figure class='gallery-item'> <div class='gallery-icon landscape'> ";
                $vignettes .= "<a href=\"".get_site_url()."/collections/object/detail/".$result["id"]."\" > \n";
                $vignettes .= "<div class='collectiveaccess-cropping-image'><img class=\"attachment-thumbnail\" data-table=\"ca_objects\" data-id=\"".$result["id"]."\"/> </span>\n";
                $vignettes .= "</a> </div><figcaption class='wp-caption-text gallery-caption'>";
                $vignettes .= $result["display_label"].($result["idno"]? " <small>(".$result["idno"].")</small>":"");
                $vignettes .= "</figcaption></figure>";
            }
            $i++;
        }

        // Inserting encapsulation div
        $vignettes ="<div id='gallery-1' class='gallery galleryid-555 gallery-columns-3 gallery-size-thumbnail'>".$vignettes."</div>\n";

        // Page navigation
        $vignettes .= "<div class=\"page-links\"><span class=\"page-links-title\">Pages:</span>\n";
        // Inserting hidden form for page navigation, keeping query
        $vignettes .= "<FORM name=\"page\" action=\"".get_site_url()."/collections/objects/search\" method=\"post\">\n".
            "<input type=\"hidden\" name=\"query\" value=\"".$query."\">".
            "<input type=\"hidden\" name=\"page\" id=\"page\" value=\"1\">".
            "</FORM>";
        // Inserting page links
        for ($i = 1; $i <= $pages; $i++) {
            $vignettes .= "<a href='#' onclick=\"document.forms['page'].page.value = $i;document.forms['page'].submit();\"><span>".$i."</span></a>\n";
        }
        $vignettes .= "</div>";

        $title = "Results for ".$query;
        $v->title = $title;
        $v->body = $cssAndScript.$vignettes; //   $body.

    } elseif (!$url_base) {
        $v->title = "Error";
        $v->body = "Configuration error, no url_base defined. Please check your settings.";
    } else {
        $v->title = "Error";
        $v->body = "No query sent.";
    }




    //var_dump($query);die();
}

