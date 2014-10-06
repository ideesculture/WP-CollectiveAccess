<?php
    $pages 			= $this->getVar("pages");
    $current_page 	= $this->getVar("current_page");
    $formname 		= $this->getVar("formname");
?>
</form>
<div class="page-links"><span class="page-links-title"><?php _e("Pages:","collectiveaccess") ?></span>
<?php
    // Loop through pages
    for ($i = 1; $i <= $pages; $i++) : ?>
    	<? $current = ""; if ($current_page == $i) $current = "class=\"current\""; ?> 
		<a href='#' <?php echo $current; ?> onclick="document.forms['<?php print $formname; ?>'].page.value = <?php print $i; ?>;document.forms['browse_facets'].submit();">
			<span><?php print $i; ?></span>
		</a>
<?php  endfor; ?>
</div>