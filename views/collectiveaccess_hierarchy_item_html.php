<?php
	$id = $this->getVar("id");
	$table = $this->getVar("table");
	$label = $this->getVar("label");
	$table_singular = ltrim($table,'ca_');
	$table_singular = rtrim($table_singular,'s');
?>
<div class='wpca-parent-container' id='wpca-parent-container_<?php print $table; ?>_<?php print $id; ?>' data-id='<?php print $id; ?>' data-table='<?php print $table; ?>'>
	<span class="toggler fold"><span class="toggler-icon"></span></span> 
	<span class='wpca-parent-title'>
		<a href='<?php print get_site_url()."/collections/".$table_singular."/detail/".$id; ?>'>
			<?php print $label;?>
		</a>
	</span>
</div>