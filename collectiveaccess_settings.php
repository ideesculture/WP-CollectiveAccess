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
    add_settings_section('collectiveaccess_templates',__('Templates',"collectiveaccess"),'collectiveaccess_templates_text',
        'collectiveaccess');
    add_settings_field('collectiveaccess_object_template',__('Object details template',"collectiveaccess"),'collectiveaccess_object_template_input',
        'collectiveaccess','collectiveaccess_templates');
    add_settings_field('collectiveaccess_object_bundles',__('Object bundles',"collectiveaccess"),'collectiveaccess_object_bundles_input',
        'collectiveaccess','collectiveaccess_templates');
    add_settings_field('collectiveaccess_entity_template',__('Entitie details template',"collectiveaccess"),'collectiveaccess_entity_template_input',
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
    echo "<input id='url_base' name='collectiveaccess_options[url_base]' type='text' value='$url_base' />";
    echo "<p class='description'>".__('Define here the full URL to service.php inside your CollectiveAccess Providence installation.',"collectiveaccess")."</p>";
}

function collectiveaccess_media_dir_input() {
    // get option 'login' value from the database
    $options = get_option('collectiveaccess_options');
    $media_dir = $options['media_dir'];
    //var_dump($url_base);die();
    // echo the field
    echo "<input id='url_base' name='collectiveaccess_options[url_base]' type='text' value='$url_base' />";
    echo "<p class='description'>".__('Define here the full URL to service.php inside your CollectiveAccess Providence installation.',"collectiveaccess")."</p>";
}

function collectiveaccess_login_input() {
    // get option 'login' value from the database
    $options = get_option('collectiveaccess_options');
    $login = $options['login'];
    // echo the field
    echo "<input id='login' name='collectiveaccess_options[login]' type='text' value='$login' />";
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

function collectiveaccess_object_template_input() {
    // get option 'login' value from the database
    $options = get_option('collectiveaccess_options');
    $object_template = $options['object_template'];
    // echo the field
    echo "<textarea rows='12' cols='50' id='object_template' name='collectiveaccess_options[object_template]'>";
    echo $object_template;
    echo "</textarea>";
    //var_dump($options);//die();
}

function collectiveaccess_object_bundles_input() {
    // get option 'login' value from the database
    $options = get_option('collectiveaccess_options');
    $object_bundles = $options['object_bundles'];
    // echo the field
    echo "<textarea rows='12' cols='50' id='object_bundles' name='collectiveaccess_options[object_bundles]'>";
    echo $object_bundles;
    echo "</textarea>";
    //var_dump($options);//die();
}

function collectiveaccess_entity_template_input() {
    $options = get_option('collectiveaccess_options');
    $entity_template = $options['entity_template'];
    echo "<textarea rows='12' cols='50' id='entity_template' name='collectiveaccess_options[entity_template]'>";
    echo $entity_template;
    echo "</textarea>";
}

function collectiveaccess_validate_options($input) {

    // TODO : if user, base_url or password is changed, clear the cache.

    $valid = array();
    $valid['login'] = preg_replace('/[^a-zA_Z]/','',$input['login']);
    $valid['password'] = preg_replace('/[^a-zA_Z0-9]/','',$input['password']);
    $valid['url_base'] = preg_replace('/[^a-zA_Z0-9\.\/]/','',$input['url_base']);
    $valid['cache_duration'] = preg_replace('/[^\d]/','',$input['cache_duration']);
    // TODO : sanitize html & javascript for templates !
    $valid['object_template'] = $input['object_template'];
    $valid['object_bundles'] = $input['object_bundles'];
    $valid['entity_template'] = $input['entity_template'];
    return $valid;
}
