<?php
	$pawtucket_url = "http://musee.idcultu.re/";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Viewer test</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	
	<link href="<?php print $pawtucket_url; ?>test.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php print $pawtucket_url; ?>/js/jquery/jquery-tileviewer/jquery.tileviewer.css" type="text/css" media="screen" />
<script src='<?php print $pawtucket_url; ?>/js/jquery/jquery.js' type='text/javascript'></script>
<script src='<?php print $pawtucket_url; ?>/js/jquery/jquery-migrate-1.1.1.js' type='text/javascript'></script>
<script src='<?php print $pawtucket_url; ?>/js/jquery/jquery-ui/jquery-ui-1.9.2.custom.min.js' type='text/javascript'></script>
<link rel='stylesheet' href='<?php print $pawtucket_url; ?>/js/jquery/jquery-ui/smoothness/jquery-ui-1.9.2.custom.css' type='text/css' media='screen'/>
<script src='<?php print $pawtucket_url; ?>/js/jquery/jquery.mousewheel.js' type='text/javascript'></script>
<script src='<?php print $pawtucket_url; ?>/js/jquery/jquery-tileviewer/jquery.tileviewer.js' type='text/javascript'></script>
<script src='<?php print $pawtucket_url; ?>/js/jquery/jquery.hotkeys.js' type='text/javascript'></script>
<script src='<?php print $pawtucket_url; ?>/js/ca/ca.genericpanel.js' type='text/javascript'></script>
<script src='<?php print $pawtucket_url; ?>/js/jquery/jquery.tools.min.js' type='text/javascript'></script>
<script src='<?php print $pawtucket_url; ?>/js/ca/ca.browsepanel.js' type='text/javascript'></script>
</head>
<body>
<a href='#' onclick='caMediaPanel.showPanel("<?php print $pawtucket_url; ?>/index.php/Detail/Object/GetRepresentationInfo/object_id/69/representation_id/57"); return false;' ><img src='http://musee.idcultu.re/media/musee/images/0/90185_ca_object_representations_media_57_mediumlarge.jpg' id='caMediaDisplayContentMedia' width='325' height='450' /></a>	

	<div id="caMediaPanel"> 
		<div id="caMediaPanelContentArea">
			<?php print $get_representation_information; ?>
		</div>
	</div>
	<script type="text/javascript">
	/*
		Set up the "caMediaPanel" panel that will be triggered by links in object detail
		Note that the actual <div>'s implementing the panel are located here in views/pageFormat/pageFooter.php
	*/
	var caMediaPanel;
	jQuery(document).ready(function() {
		if (caUI.initPanel) {
			caMediaPanel = caUI.initPanel({ 
				panelID: 'caMediaPanel',										/* DOM ID of the <div> enclosing the panel */
				panelContentID: 'caMediaPanelContentArea',		/* DOM ID of the content area <div> in the panel */
				exposeBackgroundColor: '#000000',						/* color (in hex notation) of background masking out page content; include the leading '#' in the color spec */
				exposeBackgroundOpacity: 0.8,							/* opacity of background color masking out page content; 1.0 is opaque */
				panelTransitionSpeed: 400, 									/* time it takes the panel to fade in/out in milliseconds */
				allowMobileSafariZooming: true,
				mobileSafariViewportTagID: '_msafari_viewport',
				closeButtonSelector: '.close'					/* anything with the CSS classname "close" will trigger the panel to close */
			});
		}
	});
	</script>
	</body>
</html>

