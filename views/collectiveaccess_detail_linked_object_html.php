<?php
	$linked_representation_object = $this->getVar("linked_representation_object");
	$name = $this->getVar("name");
	$link = $this->getVar("link");
?>
<div class="linked_object">
	<div class="linked_representation_object">
		<a href="<?php print $link; ?>"><img src="<?php print $linked_representation_object; ?>" /></a>
	</div>
	<div class="linked_object_name">
		<a href="<?php print $link; ?>"><?php print $name; ?></a>
	</div>
</div>
