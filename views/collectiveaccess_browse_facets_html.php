<?php
	$facets = $this->getVar("facets");
	$have_criterias = $this->getVar("have_criterias");
	$json_uncleaned_criterias = $this->getVar("json_uncleaned_criterias");
	$url = $this->getVar("url");
	$page = $this->getVar("page");
?>
<form name='browse_facets' action='<?php print $url; ?>' method='POST'>
<?php 
	if($have_criterias): 
?>
	<input type=hidden size=80 name=query value="<?php print $json_uncleaned_criterias; ?>" />
	<input type=hidden size=80 name=page value="<?php print $page; ?>" />
	<input type=hidden size=80 name=removecriteria />
<?php 
	endif; 

	foreach($facets as $facet_type => $facet_options) :
?>
    	<p style='text-transform: uppercase;' class='facetname'><?php print $facet_options['label_singular']; ?></p>
    	<div class='facetcontent collapsed' style='display: none;'>
<?php
    	foreach($facet_options["content"] as $label => $content) :
	        if(isset($content["id"])) :
?>
	            <input type='checkbox' name='<?php print $facet_type; ?>[]' value='<?php print $content["id"]."__".$content["label"]; ?>'>
	            	<?php print $content["label"] ?><br/>
<?php	        
			else :
				// No ID defined, we have an intermediate grouping array
	            $content_id = $content["id"];
?>
				<span class='subfacet subfacetname collapsed' data-facetname='<?php print $label; ?>' data-facettype='<?php print $facet_type; ?>'>
					<b><?php print $label; ?></b>
				</span>
	            <p class='subfacetcontent <?php print $facet_type."-".$label; ?> collapsed' style='display: none;'>
<?php
	            foreach($content as $subcontent) :
?>
					<input style='display:inline-box;border:1px solid black;' type='checkbox' name='<?php print $facet_type; ?>[]' value='<?php print $subcontent["id"]."__".$subcontent["label"]; ?>' /><?php print $subcontent["label"] ?><br/>
<?php
				endforeach;
?>
	            </p>
<?php
			endif;
        endforeach;
?>
		</div>
<?php
    endforeach;
	// Display Browse as a title for button only if we still have facets to display, else call it Filter
?>
		<input type='submit' value='<?php print ($have_criterias ? "Filter" : "Browse" ); ?>'/><br/>