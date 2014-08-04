<?php
	$linked_objects = $this->getVar("linked_objects");
?>
<div class="linked_objects_title">
	<?php print __("Related objects","collectiveaccess"); ?>
</div>
<?php
	print $linked_objects;
?>
