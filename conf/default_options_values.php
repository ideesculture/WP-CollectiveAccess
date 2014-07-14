<?php
/**
 * WP-CollectiveAccess
 *
 * This conf file provides default bundles & templates to display.
 * Best is not to modify those here, but inside Wordpress admin after installation.
 *
 * User: gautier
 * Date: 14/07/2014
 * Time: 10:48
 */

$collectiveaccess_options = array(
    "login" => "administrator" ,
    "password" => "",
    "url_base" => "localhost/providence",
    "cache_duration" => "3600",
    "object_template" =>
'[caption
align="alignright" width="150"]<a href="^representations.primary.urls.original"><img class="size-full" title="Zoom sur l&apos;oeuvre" src="^representations.primary.urls.preview170" style="width:150px" /> Zoom sur l&apos;oeuvre</a>[/caption]

<p><b>Numéro d&apos;inventaire</b> : ^idno</p>
<p><b>Domaine :</b> ^ca_objects.domaine</p>
<p><b>Mode d&apos;acquisition :</b> ^ca_objects.AcquisitionMode </p>
<p><b>Dimensions : </b> ^ca_objects.dimensions</p>
<p><b>Auteur : ^related.ca_entities</b></p>
<p><b>Datation :</b> ^ca_objects.objectProductionDate</p>
<p><b>Description :</b> ^ca_objects.description</p>
<p><b>Bibliographie :</b> ^ca_objects.bibliography</p>',
    "object_bundles" =>
'{
	"bundles" : {
		"access" : { "convertCodesToDisplayText" : true },
		"status" : { "convertCodesToDisplayText" : true },
		"ca_entities.entity_id" : {"returnAsArray" : true }
	}
}',
    "entity_template" =>
'<p><b>Oeuvres : </b> ^related.ca_objects</p>
<p><b>Datation :</b></p>
<p><b>Biographie :</b></p>'
);
