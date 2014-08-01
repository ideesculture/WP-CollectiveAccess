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
            'description' => __('Display a CollectiveAccess search form','collectiveaccess')
        );
        $title = __('CollectiveAccess Search form',"collectiveaccess");
        $this->WP_Widget('search_form_widget', $title, $widget_ops );
    }

    //build the widget settings form
    function form($instance) {
        $title = __('Search the collections','collectiveaccess');
        $defaults = array(
            'title' => $title
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = $instance['title'];
        $search_domains = $instance['search_domains'];
        ?>
        // TODO : make a list of ca_objects, ca_entities, etc.
        <p><?php _e('Title:','collectiveaccess');?> <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>"  type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
        <?php
    }

    //save the widget settings
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;
    }

    //display the widget
    function widget($args, $instance) {
        extract($args);

        $title = (!empty($instance['title']) ? $instance['title'] : __('Search the collections','collectiveaccess'));

        echo $before_widget;
        $widget_body =
            "<FORM action=\"".get_site_url()."/collections/objects/search\" method=\"post\">\n".
                "<input type=\"text\" name=\"query\"> <input type=\"submit\" value=\"".__("Search",'collectiveaccess')."\">\n".
            "</FORM>";

        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
        print $widget_body;
        echo $after_widget;
    }
}