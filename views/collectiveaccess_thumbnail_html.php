<?php
	$id 			= $this->getVar("id");
	$display_label	= $this->getVar("display_label");
	$idno			= $this->getVar("idno");
	$ca_table 		= $this->getVar('ca_table');
	$name_singular 	= $this->getVar('name_singular');
?>

<figure class='gallery-item'>
	 <div class='gallery-icon landscape'> 
		<a href="<?php print get_site_url(); ?>/collections/<?php echo $name_singular; ?>/detail/<?php print $id; ?>" > 
			<div class='collectiveaccess-cropping-image'><img class="attachment-thumbnail" data-table="<?php print $ca_table; ?>" data-id="<?php print $id; ?>"/> </div>
		</a> 
	</div>
	<figcaption class='wp-caption-text gallery-caption'>
		<?php print $display_label; ?> 
	</figcaption>
</figure>
