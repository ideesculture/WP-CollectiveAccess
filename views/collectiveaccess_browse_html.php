<?php
	$removecriterias = $this->getVar("removecriterias");
	$thumbnails = $this->getVar("thumbnails");
	$pagination = $this->getVar("pagination");
	$facets_content = $this->getVar("facets_content");
	$have_results = $this->getVar("have_results");
?>
<?php print $removecriterias; ?>
<?php print $facets_content; ?>
<?php 
	if($have_results) print $thumbnails;		
?>
<?php print $pagination; ?>