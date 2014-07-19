<?php
	$criterias = $this->getVar("criterias");
	$url = $this->getVar("url");
?>
<div class="entry-meta">
	<span class="tag-links">
<?php 
	foreach ($criterias as $criteria) :
?>
<a style='text-decoration:none;' onclick="document.forms['browse_facets'].removecriteria.value = '<?php print $criteria[key]; ?>__<?php print $criteria[value]; ?>';document.forms['browse_facets'].submit();" >
	<?php print $criteria[name]; ?>
	<u>x</u>
</a>
<?php
	endforeach;
?>
	</span>
</div>
<div>
	<a class=button href=<?php print $url; ?>>Remove all criterias</a>
</div>