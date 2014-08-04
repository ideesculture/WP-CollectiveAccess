<?php
	$num_results = $this->getVar("num_results");
	$thumbnails = $this->getVar("thumbnails");
	$pagination = $this->getVar("pagination");
?>
<p>
	<?php 
	if($num_results>1) {
		sprintf(__('%d results','collectiveaccess'),$num_results); 
	} else {
		sprintf(__('%d result','collectiveaccess'),$num_results); 
	} ?>
</p>
<div id='gallery-1' class='gallery galleryid-555 gallery-columns-3 gallery-size-thumbnail'>
	<?php print $thumbnails; ?>
</div>
<?php print $pagination; ?>