<?php
/**
 * Created by PhpStorm.
 * User: gautier
 * Date: 05/07/2014
 * Time: 15:01
 */

add_action('admin_menu','collectiveaccess_add_admin_page');
add_action('admin_init','collectiveaccess_admin_init');

function collectiveaccess_add_admin_page() {
    add_options_page('CollectiveAccess', 'CollectiveAccess','manage_options',
        'collectiveaccess','collectiveaccess_options_page');
}

function collectiveaccess_options_page(){
    echo "<div class='wrap'>";
    screen_icon();
    echo "<h2>".__("CollectiveAccess WordPress Plugin","collectiveaccess")."</h2>";
    echo "<form action='options.php' method='post'>";
    settings_fields('collectiveaccess_options');
    do_settings_sections('collectiveaccess');
    echo "<input name='Submit' type='submit' value='".__("Save Changes","collectiveaccess")."' />";
    echo "</form></div>";
}

/**
 *
 */
function collectiveaccess_admin_init() {
    register_setting('collectiveaccess_options', 'collectiveaccess_options','collectiveaccess_validate_options');
    add_settings_section('collectiveaccess_main',__('Identifiers',"collectiveaccess"),'collectiveaccess_main_text',
        'collectiveaccess');
    add_settings_field('collectiveaccess_url_base',__('URL Base',"collectiveaccess"),'collectiveaccess_url_base_input',
        'collectiveaccess','collectiveaccess_main');
    add_settings_field('collectiveaccess_login',__('Login',"collectiveaccess"),'collectiveaccess_login_input',
        'collectiveaccess','collectiveaccess_main');
    add_settings_field('collectiveaccess_password',__('Password',"collectiveaccess"),'collectiveaccess_password_input',
        'collectiveaccess','collectiveaccess_main');
    add_settings_field('collectiveaccess_cache_duration',__('Cache duration',"collectiveaccess"),'collectiveaccess_cache_duration_input',
        'collectiveaccess','collectiveaccess_main');
    add_settings_field('collectiveaccess_empty_cache',__('Empty the cache',"collectiveaccess"),'collectiveaccess_empty_cache_input',
        'collectiveaccess','collectiveaccess_main');
    add_settings_section('collectiveaccess_templates',__('Templates',"collectiveaccess"),'collectiveaccess_templates_text',
        'collectiveaccess');
    add_settings_field('collectiveaccess_object_template',__('Object details template',"collectiveaccess"),'collectiveaccess_object_template_input',
        'collectiveaccess','collectiveaccess_templates');
//    add_settings_field('collectiveaccess_object_bundles',__('Object bundles',"collectiveaccess"),'collectiveaccess_object_bundles_input',
//        'collectiveaccess','collectiveaccess_templates');
    add_settings_field('collectiveaccess_entity_template',__('Entitie details template',"collectiveaccess"),'collectiveaccess_entity_template_input',
        'collectiveaccess','collectiveaccess_templates');
    add_settings_field('collectiveaccess_place_template',__('Place details template',"collectiveaccess"),'collectiveaccess_place_template_input',
        'collectiveaccess','collectiveaccess_templates');
    add_settings_field('collectiveaccess_occurrence_template',__('Occurrence details template',"collectiveaccess"),'collectiveaccess_occurrence_template_input',
        'collectiveaccess','collectiveaccess_templates');
    add_settings_field('collectiveaccess_collection_template',__('Collection details template',"collectiveaccess"),'collectiveaccess_collection_template_input',
        'collectiveaccess','collectiveaccess_templates');
}

function collectiveaccess_main_text() {
    _e("<p>CollectiveAccess Wordpress plugin requires access through Web Services to your CollectiveAccess
        installation.<br/>
        Please provide here login and password to an accound having WebServices authorized in your Providence back
        office.</p>","collectiveaccess");
}

function collectiveaccess_templates_text() {
    echo "<p>".__("You'll define here templates for displaying objects inside Wordpress.","collectiveaccess")."</p>";
}

function collectiveaccess_url_base_input() {
    // get option 'login' value from the database
    $options = get_option('collectiveaccess_options');
    $url_base = $options['url_base'];
    //var_dump($url_base);die();
    // echo the field
    echo "<input id='url_base' name='collectiveaccess_options[url_base]' type='text' class='regular-text code' value='$url_base' />";
    echo "<p class='description'>".__('Define here the full URL path to service.php inside your CollectiveAccess Providence installation.',"collectiveaccess")."</p>";
    echo "<p class='description'>".__('Note that the string will be cleaned up on save, to keep only necessary parts : server & path (no protocol, no /service.php)',"collectiveaccess")."</p>";
    echo "<p class='description'>".__("<b>Example</b>: for <b><u>http://localhost/collectiveaccess/providence/service.php</u></b>, we need only <b></u>localhost/collectiveaccess/providence</u></b>","collectiveaccess")."</p>";
}

function collectiveaccess_login_input() {
    // get option 'login' value from the database
    $options = get_option('collectiveaccess_options');
    $login = $options['login'];
    // echo the field
    echo "<input id='login' name='collectiveaccess_options[login]' type='text' class='regular-text code' value='$login' />";
    echo "<p class='description'>".__("WP-CollectiveAccess requires a connection to a CA profile with WebServices rights. <br/>
        Check your CollectiveAccess installation under Manage > Access Control > Roles if you can't connect. <br/>At worst, try to connect as an
        'administrator'.","collectiveaccess")."</p>";
}

function collectiveaccess_password_input() {
    // get option 'login' value from the database
    $options = get_option('collectiveaccess_options');
    $password = $options['password'];
    // echo the field
    echo "<input id='password' type='password' name='collectiveaccess_options[password]' value='$password' />";
}

function collectiveaccess_cache_duration_input() {
    // get option 'login' value from the database
    $options = get_option('collectiveaccess_options');
    $cache_duration = $options['cache_duration'];
    //var_dump($url_base);die();
    // echo the field
    echo "<input id='cache_duration' name='collectiveaccess_options[cache_duration]' type='text' value='$cache_duration' />";
    echo "<p class='description'>".__("Enter a duration in seconds. During this time, data will be fetched from local storage instead of using Webservices.<br/>Default recommended value is 3600.","collectiveaccess")."</p>";
}

function collectiveaccess_empty_cache_input() {
    // get option 'login' value from the database
    $options = get_option('collectiveaccess_options');
    $empty_cache = $options['empty_cache'];
    //var_dump($url_base);die();
    // echo the field
    echo "<INPUT type='radio' name='collectiveaccess_options[empty_cache]' value='0' ".(!$empty_cache ? "checked" : "")."> Ne pas vider le cache ";
    echo "<INPUT type='radio' name='collectiveaccess_options[empty_cache]' value='1' ".($empty_cache ? "checked" : "")."> Vider le cache";
    echo "<p class='description'>".__("Option to empty cache on save. We recommend it to you if you change URL, login and/or password to your CA database.","collectiveaccess")."</p>";
}

function collectiveaccess_object_template_input() {
    // get option 'login' value from the database
    $options = get_option('collectiveaccess_options');
    $object_template = $options['object_template'];
    // echo the field
    echo "<textarea rows='12' cols='50' id='object_template' name='collectiveaccess_options[object_template]'>";
    echo $object_template;
    echo "</textarea>";
    echo "<p class='description'>".
        __("Need some info on what shoud be put there ?","collectiveaccess").
        " <a href=https://github.com/ideesculture/WP-CollectiveAccess/wiki/Templates>".
        __("Take a look at the plugin wiki.","collectiveaccess").
        "</a></p>";
}

function collectiveaccess_object_bundles_input() {
    // get option 'login' value from the database
    $options = get_option('collectiveaccess_options');
    $object_bundles = $options['object_bundles'];
    // echo the field
    echo "<textarea rows='12' cols='50' id='object_bundles' name='collectiveaccess_options[object_bundles]'>";
    echo $object_bundles;
    echo "</textarea>";
}

function collectiveaccess_entity_template_input() {
    $options = get_option('collectiveaccess_options');
    $entity_template = $options['entity_template'];
    echo "<textarea rows='12' cols='50' id='entity_template' name='collectiveaccess_options[entity_template]'>";
    echo $entity_template;
    echo "</textarea>";
}

function collectiveaccess_place_template_input() {
    $options = get_option('collectiveaccess_options');
    $place_template = $options['place_template'];
    echo "<textarea rows='12' cols='50' id='place_template' name='collectiveaccess_options[place_template]'>";
    echo $place_template;
    echo "</textarea>";
}

function collectiveaccess_occurrence_template_input() {
    $options = get_option('collectiveaccess_options');
    $occurrence_template = $options['occurrence_template'];
    echo "<textarea rows='12' cols='50' id='occurrence_template' name='collectiveaccess_options[occurrence_template]'>";
    echo $occurrence_template;
    echo "</textarea>";
}

function collectiveaccess_collection_template_input() {
    $options = get_option('collectiveaccess_options');
    $collection_template = $options['collection_template'];
    echo "<textarea rows='12' cols='50' id='collection_template' name='collectiveaccess_options[collection_template]'>";
    echo $collection_template;
    echo "</textarea>";
}

function collectiveaccess_validate_options($input) {
    // Empty cache stays always null in the database, this is just a temporary token.
    $valid['empty_cache'] = 0;
    // If empty_cache has been tickled, call empty_cache()
    if($input['empty_cache']) collectiveaccess_empty_cache();
    $valid = array();
    $valid['login'] = preg_replace('/[^a-zA_Z]/','',$input['login']);
    $valid['password'] = preg_replace('/[^a-zA_Z0-9]/','',$input['password']);
    // removing protocol from url_base (only http for now)
    $url = str_replace("http://", "", $input['url_base']);
    // removing ending /service.php or /index.php
    $url = str_replace("/service.php", "", $url);
    $url = str_replace("/index.php", "", $url);
    // removing trailing slash if any
    $url = rtrim($url, '/\\'); 
    $valid['url_base'] = preg_replace('/[^a-zA_Z0-9\.\/]/','',$url);
    $valid['cache_duration'] = preg_replace('/[^\d]/','',$input['cache_duration']);
    // TODO : sanitize html & javascript for templates !
    $valid['object_template'] = $input['object_template'];
    $valid['entity_template'] = $input['entity_template'];
    $valid['place_template'] = $input['place_template'];
    $valid['occurrence_template'] = $input['occurrence_template'];
    $valid['collection_template'] = $input['collection_template'];

    return $valid;
}
