<?php
/**
 * WP-CollectiveAccess
 * User: gautier
 * Date: 13/07/2014
 * Time: 10:21
 *
 * Simple AJAX handler, no need to use admin-ajax.php since almost none of our data is inside WordPress
 * Origin :  http://www.coderrr.com/create-your-own-admin-ajax-php-type-handler/ by Brian Fegter
 * (Little) Adaptation Gautier Michelin
 *
 */

// Requiring ajax handlers
//require_once(__DIR__."/collectiveaccess_ajax_getimage.php");

require_once(__DIR__."/collectiveaccess_common_functions.php");

define('DOING_AJAX', true);

if (!isset( $_POST['action']))
    die('-1');

//relative to where your plugin is located
require_once('../../../wp-load.php');

$action = esc_attr($_POST['action']);

// A bit of security : the allowed_actions array must contain all allowed actions
$allowed_actions = array(
    'getimage','getchildren'
);

//Typical headers
header('Content-Type: text/html');
send_nosniff_header();
//Disable caching
header('Cache-Control: no-cache');
header('Pragma: no-cache');

function collectiveaccess_ajax_getimage()
{
    // Checking if XHR (ajax) request
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    if(!$isAjax) die('-1');

    $result = collectiveaccess_getpreviewimage($_REQUEST[table], $_REQUEST[id]);
    if($result == false) die('-1');
    echo json_encode($result);
}

function collectiveaccess_ajax_getchildren()
{
    // Checking if XHR (ajax) request
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    if(!$isAjax) die('-1');

    $result = collectiveaccess_getchildrenrecords($_REQUEST[table], $_REQUEST[id]);
    if($result == false) die('-1');
    echo json_encode($result);
}


if(in_array($action, $allowed_actions)) {
    add_action('collectiveaccess_ajax_'.$action, 'collectiveaccess_ajax_'.$action);
    do_action('collectiveaccess_ajax_'.$action);
} else {
    die('-1');
}
