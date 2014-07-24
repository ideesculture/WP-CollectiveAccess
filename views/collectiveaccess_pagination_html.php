<?php
    $pages = $this->getVar("pages");
    $formname = $this->getVar("formname");
?>
</form>
<div class="page-links"><span class="page-links-title"><?php _e("Pages:","collectiveaccess") ?></span>
<?php
    // Loop through pages
    for ($i = 1; $i <= $pages; $i++) : ?>
<a href='#' onclick="document.forms['<?php print $formname; ?>'].page.value = <?php print $i; ?>;document.forms['browse_facets'].submit();"><span><?php print $i; ?></span></a>
<?php 
    endfor; 
?>
</div>
