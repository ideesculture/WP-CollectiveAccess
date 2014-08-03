<?php include(__DIR__.'/collectiveaccess_js.php'); ?>
<?php
	$tilepicphp_url = plugins_url( 'tilepic.php', WP_CA_MAIN_FILE);
    $tpc_image_url = $this->getVar('tilepic_image_url');
    $tpc_height = $this->getVar('tilepic_height');
    $tpc_width = $this->getVar('tilepic_width');
    $tpc_layers = $this->getVar('tilepic_layers');
    $tpc_remoteviewer_url = $this->getVar('tilepic_remoteviewer_url');
    $url = $tilepicphp_url."?tpc=".urlencode($tpc_image_url)."&viewer=".urlencode($tpc_remoteviewer_url)."&height=".$tpc_height."&width=".$tpc_width."&layers=".$tpc_layers;
?>

jQuery(document).ready(function(){
    jQuery('#mediaview').on("click", function(e) {
        var overlay = jQuery("<div id='wpca-tilepic-overlay'><div id='wpca-tilepic-close'><a href='#'>x</a></div><iframe id='wpca-tilepic-iframe' src='<?php print $url; ?>' /></div>");
        overlay.appendTo(document.body);
        jQuery('#wpca-tilepic-close').on("click", function(e) {
            jQuery("#wpca-tilepic-overlay").remove();
        });
    });
});