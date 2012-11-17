<?php
/*---------------------------------------------------------------------------------*/
/* Adspace Widget */
/*---------------------------------------------------------------------------------*/

class CoLabs_AdWidget extends WP_Widget {

	function CoLabs_AdWidget() {
		$widget_ops = array('description' => 'A set of advertisements (full width= 300px), use in Sidebar only.' );
		parent::WP_Widget(false, __('ColorLabs - Ads Widget', 'colabsthemes'),$widget_ops);      
	}

	function widget($args, $instance) {
	   extract( $args );
        $title = apply_filters('widget_title', $instance['title'] );
		$adcode = $instance['adcode'];
		$image = $instance['image'];
		$href = $instance['href'];
		$alt = $instance['alt'];
        echo $before_widget;
        ?>
        <?php if ( empty($title) ){ ?>
        <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery('#<?php echo $this->id; ?>').addClass('notitle');
        })
        </script>
        <?php } ?>
        <div class="adspace-widget">
        <?php

		if($title != '')
			
            echo $before_title .$title. $after_title;
?>

<?php
		if($adcode != ''){
		?>
		
		<?php echo $adcode; ?>
		
		<?php } else { ?>
		
			<a href="<?php echo $href; ?>"><img src="<?php echo $image; ?>" width="300px" alt="<?php echo $alt; ?>" /></a>
	
		<?php
		}
		
		echo '</div>';
        echo $after_widget;
	}

	function update($new_instance, $old_instance) {                
		return $new_instance;
	}

	function form($instance) {        
		$title = esc_attr($instance['title']);
		$adcode = esc_attr($instance['adcode']);
		$image = esc_attr($instance['image']);
		$href = esc_attr($instance['href']);
		$alt = esc_attr($instance['alt']);
		?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):','colabsthemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('adcode'); ?>"><?php _e('Ad Code:','colabsthemes'); ?></label>
            <textarea name="<?php echo $this->get_field_name('adcode'); ?>" class="widefat" id="<?php echo $this->get_field_id('adcode'); ?>"><?php echo $adcode; ?></textarea>
        </p>
        <p><strong>or</strong></p>
        <p>
            <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image Url:','colabsthemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('image'); ?>" value="<?php echo $image; ?>" class="widefat" id="<?php echo $this->get_field_id('image'); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('href'); ?>"><?php _e('Link URL:','colabsthemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('href'); ?>" value="<?php echo $href; ?>" class="widefat" id="<?php echo $this->get_field_id('href'); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('alt'); ?>"><?php _e('Alt text:','colabsthemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('alt'); ?>" value="<?php echo $alt; ?>" class="widefat" id="<?php echo $this->get_field_id('alt'); ?>" />
        </p>
        <?php
	}
} 

register_widget('CoLabs_AdWidget');
?>