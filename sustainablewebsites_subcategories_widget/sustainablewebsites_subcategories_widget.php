<?php
/*
 * Plugin Name: Subcategories Widget
 * Plugin URI: http://sustainablewebsites.com/wordpress-subcategories-widget
 * Description: Lists subcategories on a category page
 * Author: Ivan Storck
 * Version: 0.2
 * Author URI: http://ivanstorck.com
 *
 * Like WordPress, this code is GPL v2 (copyleft), and is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * Thanks to:
 * http://yoast.com/showing-subcategories-on-wordpress-category-pages/
 * http://justintadlock.com/archives/2009/05/26/the-complete-guide-to-creating-widgets-in-wordpress-28
 * http://codex.wordpress.org/Function_Reference/wp_list_categories
 * http://best-beaches.com and Dan Taylor for inspiration
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'sustainablewebsites_load_widgets' );

/**
 * Register our widget.
 * 'SW_Subcategories_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function sustainablewebsites_load_widgets() {
	register_widget( 'SW_Subcategories_Widget' );
}

class SW_Subcategories_Widget extends WP_Widget {

	function SW_Subcategories_Widget() {
		$widget_options = array( 'classname' => 'subcategories', 'description' => __('Displays subcategories on category archive pages', 'subcategories') );
		$control_options = array( 'width' => '300', 'height' => 350, 'id_base' => 'subcategories-widget' );
		$this->WP_Widget( 'subcategories-widget', __('Subcategories Widget', 'subcategories'), $widget_options, $control_options );
	}

	function widget( $args, $instance )  {
		
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );

		if (is_category()) {
			$this_category = get_category( get_query_var( 'cat' ), false );
			if ( get_category_children( $this_category->cat_ID ) != "" ) {
				// widget header
				echo $before_widget;
				echo $before_title; echo $title; echo $after_title;
				echo "<ul>";
				wp_list_categories( 'orderby=id&show_count=0&use_desc_for_title=1&child_of='.$this_category->cat_ID.'&title_li=' );
				// widget footer
				echo $after_widget;			
				echo "</ul>";
			}
		} 
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		/* Strip tags for title to remove HTML (important security for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		return $instance;
		
	}
	
	function form( $instance ) {
	
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Subcategories', 'subcategories') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
	
				<!-- Widget Title: Text Input -->
				<p>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
					<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
				</p>
	
	<?php
	}
	
}

?>