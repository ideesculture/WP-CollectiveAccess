<?php 
	// include common css, through an include to make it easier when overriding this view
	include(__DIR__.'/collectiveaccess_css.php'); 

	// asset path
	$expand_icon_url = plugins_url( 'assets/images/icon-expand.png', WP_CA_MAIN_FILE);
	$loading_icon_url = plugins_url( 'assets/images/icon-loading.png', WP_CA_MAIN_FILE);
	$shrink_icon_url = plugins_url( 'assets/images/icon-shrink.png', WP_CA_MAIN_FILE);
	$empty_icon_url = plugins_url( 'assets/images/icon-empty.png', WP_CA_MAIN_FILE);
?>

.wpca-parent-container {
	margin:4px 0 4px 20px;
}

.toggler {
	background:#009112;
	margin-right:4px;
	padding:3px 3px 0 3px;
	border-radius:3px;
}

.fold > .toggler-icon {
	content:url('<?php print $expand_icon_url; ?>');
}
.unfold > .toggler-icon {
	content:url('<?php print $shrink_icon_url; ?>');
}
.empty > .toggler-icon {
	content:url('<?php print $empty_icon_url; ?>');
}

.loading > .toggler-icon {
	content:url('<?php print $loading_icon_url; ?>');
	-webkit-animation: spin 0.5s infinite linear;
	-moz-animation: spin 0.5s infinite linear;
	-ms-animation: spin 0.5s infinite linear;
	animation: spin 0.5s infinite linear;
}


.wpca-parent-title {
}

@-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
@-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
@keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }
