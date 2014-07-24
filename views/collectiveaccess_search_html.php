<?php
	$num_results = $this->getVar("num_results");
	$thumbnails = $this->getVar("thumbnails");
	$pagination = $this->getVar("pagination");
?>
<p>
	<?php print $num_results; ?> results
</p>
<div id='gallery-1' class='gallery galleryid-555 gallery-columns-3 gallery-size-thumbnail'>
	<?php print $thumbnails; ?>
</div>
<?php print $pagination; ?>