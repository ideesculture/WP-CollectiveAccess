<?php
	$criterias = $this->getVar("criterias");
	$url = $this->getVar("url");
?>
<div class="entry-meta">
	<span class="tag-links remove-criterias">
<?php 
	foreach ($criterias as $criteria) :
?>
<a title="Remove this criteria" onclick="document.forms['browse_facets'].removecriteria.value = '<?php print $criteria[key]; ?>__<?php print $criteria[value]; ?>';document.forms['browse_facets'].submit();" >
	<?php print $criteria[name]; ?>
</a>
<?php
	endforeach;
?>
	</span>
	<span class="reset-criterias">
		<a  style='text-decoration:none;' href="<?php print $url; ?>">Reset criterias</a>
	</span>
</div>