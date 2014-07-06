<?php
/**
 * Created by PhpStorm.
 * User: gautier
 * Date: 26/06/2014
 * Time: 06:38
 */
add_action( 'widgets_init', 'collectiveaccess_register_search_form_widget' );

//register our widget
function collectiveaccess_register_search_form_widget() {
    register_widget( 'search_form_widget' );
}

class search_form_widget extends WP_Widget {

    //process the new widget
    function search_form_widget() {

        $widget_ops = array(
            'classname' => 'search_form_widget_class',
            'description' => 'Display a CollectiveAccess search form'
        );

        $this->WP_Widget( 'search_form_widget', 'CollectiveAccess Search form', $widget_ops );
    }

    //build the widget settings form
    function form($instance) {
        $defaults = array(
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $search_domains = $instance['search_domains'];
        // TODO : make a list of ca_objects, ca_entities, etc.
        ?><p>No parameter now</p><?php
    }

    //save the widget settings
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        return $instance;
    }

    //display the widget
    function widget($args, $instance) {
        extract($args);

        $title = "Search the collections";
        echo $before_widget;
        $widget_body =
            "<FORM action=\"".get_site_url()."/collections/objects/search\" method=\"post\">\n".
                "<input type=\"text\" name=\"query\"> <input type=\"submit\" value=\"Submit\">\n".
            "</FORM>";

        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
        print $widget_body;
        echo $after_widget;
    }
}