<?php
    $pages = $this->getVar("pages");
?>
</form>
<div class="page-links"><span class="page-links-title">Pages:</span>
<?php
    // Loop through pages
    for ($i = 1; $i <= $pages; $i++) : ?>
<a href='#' onclick="document.forms['browse_facets'].page.value = <?php print $i; ?>;document.forms['browse_facets'].submit();"><span><?php print $i; ?></span></a>
<?php 
    endfor; 
?>
</div>
