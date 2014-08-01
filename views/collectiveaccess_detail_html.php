<?php
	$template = $this->getVar("template");

	$tilepicphp_url = plugins_url( 'tilepic.php', WP_CA_MAIN_FILE);
    $tpc_image_url = $this->getVar('tilepic_image_url');
    $tpc_remoteviewer_url = $this->getVar('tilepic_remoteviewer_url');
    $url = $tilepicphp_url."?tpc=".urlencode($tpc_image_url)."&viewer=".urlencode($tpc_remoteviewer_url);
    //var_dump($url);die();

?>

<?php print $template; ?>