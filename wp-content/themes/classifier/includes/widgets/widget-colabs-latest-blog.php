<?php
// custom sidebar blog posts widget
class Colabs_Widget_Blog_Posts extends WP_Widget {

    function Colabs_Widget_Blog_Posts() {
        $widget_ops = array( 'description' => __( 'Your most recent blog posts', 'colabsthemes') );
        $this->WP_Widget(false, __('ColorLabs - Recent Blog Posts', 'colabsthemes'), $widget_ops);
    }

    function widget( $args, $instance ) {

        extract($args);

		$title = apply_filters('widget_title', $instance['title'] );
		$count = $instance['count'];

		if (!is_numeric ($count)) $count = 5;
        

		echo $before_widget;

		if ($title) echo $before_title . $title . $after_title;

		// include the main blog loop
		include(TEMPLATEPATH . '/includes/sidebar-blog-posts.php');


		echo $after_widget;

	        
    }

   function update($new_instance, $old_instance) {
        $instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = (trim(strip_tags($new_instance['count'])));

		return $instance;
    }

	function form( $instance ) {

		// load up the default values
		$defaults = array( 'title' => 'From the Blog', 'count' => 5 );
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<p>
			<label><?php _e('Title:', 'colabsthemes') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" value="<?php echo $instance['count']; ?>" style="width:30px;" />
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Posts Shown', 'colabsthemes') ?></label>
		</p>


<?php
	}
}

register_widget('Colabs_Widget_Blog_Posts');
?>