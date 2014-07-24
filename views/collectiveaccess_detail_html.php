<?php
	$template = $this->getVar("template");
?>

<?php print $template; ?>

<?php 
/*
	Work in progress...
	
<div id="caMediaPanel" style="display: block; z-index: 31000;">
	<div id="caMediaPanelContentArea">
		<!-- Controls - only for media overlay -->
		<div class="caMediaOverlayControls">
				<div class='close'><a href="#" onclick="caMediaPanel.hidePanel(); return false;" title="close">x</a></div>
				<div class='objectInfo'>2013.15.1.d.jpg [.5.1]</div>
				<div class='repNav'>
				</div>
		</div><!-- end caMediaOverlayControls -->
		<div id="caMediaOverlayContent">

					<div id='caMediaOverlayContentMedia' style='width:100%; height: 100%; position: relative; z-index: 0;'>
						
					</div>
					<script type='text/javascript'>
						var elem = document.createElement('canvas');
						if (elem.getContext) {
							if (elem.getContext('2d')) {
								jQuery(document).ready(function() {
									jQuery('#caMediaOverlayContentMedia').tileviewer({
										id: 'caMediaOverlayContentMedia_viewer',
										src: 'http://musee.idcultu.re/viewers/apps/tilepic.php?p=http://musee.idcultu.re/media/musee/tilepics/0/15176_ca_object_representations_media_57_tilepic.tpc&t=',
										width: '100%',
										height: '100%',
										magnifier: false,
										buttonUrlPath: '/themes/default/graphics/buttons',
										annotationLoadUrl: '',
										annotationSaveUrl: '',
										helpLoadUrl: '',
										info: {
											width: '1603',
											height: '2222',
											tilesize: 256,
											levels: '6'
										}
									}); 
								});
							}
						}  
					</script>
		</div><!-- end caMediaOverlayContent -->
	</div>
</div>