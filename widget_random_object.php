<?php
/**
 * Created by PhpStorm.
 * User: gautier
 * Date: 26/06/2014
 * Time: 06:38
 */
require_once(plugin_dir_path( __FILE__ ) ."lib/cawrappercache/ItemServiceCache.php");

add_action( 'widgets_init', 'collectiveaccess_register_random_object_widget' );

//register our widget
function collectiveaccess_register_random_object_widget() {
    register_widget( 'random_object_widget' );
}

class random_object_widget extends WP_Widget {

    //process the new widget
    function random_object_widget() {

        $widget_ops = array(
            'classname' => 'random_object_widget_class',
            'description' => __('Display a random object from CollectiveAccess','collectiveaccess')
        );

        $this->WP_Widget( 'random_object_widget', __('CollectiveAccess Random object','collectiveaccess'), $widget_ops );
    }

    //build the widget settings form
    function form($instance) {
        $defaults = array(
            'title' => 'RSS Feed',
            'ca_url_base' => 'localhost',
            'id' => '1'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = $instance['title'];
        $ca_url_base = $instance['ca_url_base'];
        $id = $instance["id"];
        ?>
        <p><?php _e("Title: ","collectiveaccess"); ?><input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>"  type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
        <p><?php _e("CollectiveAccess URL base: ","collectiveaccess"); ?><input class="widefat" name="<?php echo $this->get_field_name( 'ca_url_base' ); ?>"  type="text" value="<?php echo esc_attr( $ca_url_base ); ?>" /></p>
        <p><?php _e("Object ID to display: ","collectiveaccess"); ?><input class="widefat" name="<?php echo $this->get_field_name( 'id' ); ?>"  type="text" value="<?php echo esc_attr( $id); ?>" /></p>
    <?php
    }

    //save the widget settings
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['ca_url_base'] = strip_tags( $new_instance['ca_url_base'] );
        $instance['id'] = strip_tags( $new_instance['id'] );
        return $instance;
    }

    //display the widget
    function widget($args, $instance) {
        extract($args);

        echo $before_widget;

        //load the widget settings
        //$title = apply_filters( 'widget_title', $instance['title'] );
        $ca_url_base = empty( $instance['ca_url_base'] ) ? '' : $instance['ca_url_base'];
        $id = empty( $instance['id'] ) ? '' : $instance['id'];

        $options = get_option('collectiveaccess_options');
        $url_base = empty( $options[url_base] ) ? 'localhost' : $options[url_base];
        $login = empty($options[login]) ? 'admin' : $options[login];
        $password = empty($options[password]) ? 'admin' : $options[password];



        // TODO : do not show anything if no password, send an error message on screen

        if ( $ca_url_base ) {
            $client = new ItemService("http://".$login.":".$password."@".$url_base,"ca_objects","GET",$id);
            $result = $client->request();
            $record = $result->getRawData();
            $title = $record["preferred_labels"]["fr_FR"][0];
            $widget_body = "<p><a href='/collections/object/detail/".$id."'><b>".$record["preferred_labels"]["fr_FR"][0]."</b></a></p>";
            $widget_body .= "<p><img src=\"".$record["representations"][1][urls][preview170]."\"></b></p>";
        }
        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
        print $widget_body;
        echo $after_widget;
    }
}