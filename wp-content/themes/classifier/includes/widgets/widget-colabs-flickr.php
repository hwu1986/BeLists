<?php
/*---------------------------------------------------------------------------------*/
/* Flickr widget */
/*---------------------------------------------------------------------------------*/
class CoLabs_flickr extends WP_Widget {

	function CoLabs_flickr() {
		$widget_ops = array('description' => 'A Flickr widget that populates photos from a Flickr ID, use in Sidebar only.' );

		parent::WP_Widget(false, __('ColorLabs - Flickr', 'colabsthemes'),$widget_ops);      
	}

	function widget($args, $instance) {  
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Photos on flickr') : $instance['title'], $instance, $this->id_base);
		$id = $instance['id'];
		$count = $instance['number'];
		
		
		echo $before_widget;
		echo $before_title; 
		echo $title;
        echo $after_title; 
		if ($count==''){
		$count=8; }	
		
		getmyflickr($id,$count);
		echo $after_widget;
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {
        $title = esc_attr($instance['title']);
		$id = esc_attr($instance['id']);
		$number = esc_attr($instance['number']);

		?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):','colabsthemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>        
        <p>
            <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Flickr ID (<a href="http://www.idgettr.com">idGettr</a>):','colabsthemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('id'); ?>" value="<?php echo $id; ?>" class="widefat" id="<?php echo $this->get_field_id('id'); ?>" />
        </p>
       	<p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number:','colabsthemes'); ?></label>
             <input type="text" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $number; ?>" class="widefat" id="<?php echo $this->get_field_id('number'); ?>" />
        </p>
       
		<?php
	}
} 

register_widget('colabs_flickr');
?>