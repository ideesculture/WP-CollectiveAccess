<?php
	$base_url = dirname($_SERVER['REQUEST_URI']);
	$tilepic_icon_url = $base_url."/assets/images";
	
	if (isset($_GET["tpc"])) {
		$tilepic_tpc = $_GET["tpc"];
		$tilepic_viewer_url = $_GET["viewer"];
		$tilepic_height = $_GET["height"];
		$tilepic_width = $_GET["width"];
		$tilepic_layers = $_GET["layers"];
	} 	
	$tilepic_src = $tilepic_viewer_url."?p=".$tilepic_tpc."&t=";
	//http://paw.calendar.dev/viewers/apps/tilepic.php?p=http://paw.calendar.dev/media/collectiveaccess/tilepics/0/15176_ca_object_representations_media_57_tilepic.tpc&t=
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Tilepic viewer</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	
<link rel="stylesheet" href="<?php print $base_url; ?>/js/jquery.tileviewer.css" type="text/css" media="screen" />
<script src='<?php print $base_url; ?>/js/jquery.js' type='text/javascript'></script>
<script src='<?php print $base_url; ?>/js/jquery-migrate-1.1.1.js' type='text/javascript'></script>
<script src='<?php print $base_url; ?>/js/jquery-ui-1.9.2.custom.min.js' type='text/javascript'></script>
<link rel='stylesheet' href='<?php print $base_url; ?>/js/jquery-ui-1.9.2.custom.css' type='text/css' media='screen'/>
<script src='<?php print $base_url; ?>/js/jquery.mousewheel.js' type='text/javascript'></script>
<script src='<?php print $base_url; ?>/js/jquery.tileviewer.js' type='text/javascript'></script>
<script src='<?php print $base_url; ?>/js/jquery.hotkeys.js' type='text/javascript'></script>
<script src='<?php print $base_url; ?>/js/jquery.tools.min.js' type='text/javascript'></script>
<link rel="stylesheet" href="assets/css/tilepic.css" type="text/css" media="screen" />
</head>
<body>
	<div id="caMediaPanel"> 
		<div id="caMediaPanelContentArea">
			<div id="caMediaOverlayContent">
						<div id='caMediaOverlayContentMedia' style='width:100%; height: 100%; position: absolute; top:0; left:0;z-index: 0;'>
							
						</div>
						<script type='text/javascript'>
						jQuery(document).ready(function() {
							jQuery('#caMediaPanel').show();
							var elem = document.createElement('canvas');
							if (elem.getContext && elem.getContext('2d')) {
								jQuery('#caMediaOverlayContentMedia').tileviewer({
									id: 'caMediaOverlayContentMedia_viewer',
									src: '<?php print $tilepic_src; ?>',
									width: '100%',
									height: '100%',
									magnifier: false,
									buttonUrlPath: "<?php print $tilepic_icon_url; ?>",
									annotationLoadUrl: '',
									annotationSaveUrl: '',
									helpLoadUrl: '',
									info: {
										width: '<?php print $tilepic_width; ?>',
										height: '<?php print $tilepic_height; ?>',
										tilesize: 256,
										levels: '<?php print $tilepic_layers; ?>'
									}
								}); 
							} else {
								// No fall-back to Flash-based viewer for now, also there is one in Pawtucket
							}						
						});
						</script>
			</div><!-- end caMediaOverlayContent -->
		</div>
	</div>
	</body>
</html>

