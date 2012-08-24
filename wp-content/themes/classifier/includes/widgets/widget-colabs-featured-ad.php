<?php
/*---------------------------------------------------------------------------------*/
/* Featured Ad widget */
/*---------------------------------------------------------------------------------*/
class CoLabs_Featured_Ad extends WP_Widget {

   function CoLabs_Featured_Ad() {
	   $widget_ops = array('description' => 'Show your featured ad.' );
       parent::WP_Widget(false, __('ColorLabs - Featured Ad', 'colabsthemes'),$widget_ops);      
   }

   function widget($args, $instance) {  
    extract( $args );
   	$title = $instance['title'];
	$cat_id = $instance['cat_id'];
	$number = $instance['number'];
	if($number=='')$number=6;
	?>
		<?php echo $before_widget; ?>
        <?php if ($title) { echo $before_title . $title . $after_title; }else { echo $before_title .__('Featured Ad','colabsthemes'). $after_title;} ?>
        <ul class="feat-ad-sidebar">
		<?php
		query_posts( array(
				'showposts' => $number,
				'post_type' => COLABS_POST_TYPE,
				'tax_query' => array(
									array(
									'taxonomy' => COLABS_TAX_CAT,
									'field' => 'id',
									'terms' => $cat_id
									)
								)
		));
		
		while ( have_posts() ) : the_post();
		?>
		<li>
		<?php colabs_image('width=133&height=133');?>
		<a href="<?php the_permalink();?>" alt="<?php the_title();?>">
		<?php the_title();?>
		</a></li>
		<?php 
		endwhile;?>
		</ul>
		<?php echo $after_widget; ?>   
   <?php
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {        
   
       $title = esc_attr($instance['title']);
	   $number = esc_attr($instance['number']);
	   $cat_id = esc_attr($instance['cat_id']);

       ?>
       <p>
	   	   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','colabsthemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
       </p>
	    <p>
	   	   <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number:','colabsthemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('number'); ?>"  value="<?php echo $number; ?>" class="widefat" id="<?php echo $this->get_field_id('number'); ?>" />
       </p>
	   <p>
	   	   <label for="<?php echo $this->get_field_id('cat_id'); ?>"><?php _e('Ad Category:','colabsthemes'); ?></label>
		   <select class="widefat" name="<?php echo $this->get_field_name('cat_id'); ?>">
				<?php
			
				$categories=get_categories('taxonomy='.COLABS_TAX_CAT);
			
				if  ($categories) {
				  foreach ($categories  as $category ) {
					  
						if ($category->term_id==$cat_id)$selected='selected="selected"';
						echo '<option value="'. $category->term_id. '" '.$selected.'>'.$category->name.'</option>';
						$selected='';
					  
				  }
				}
				?>	
			</select>
	       
       </p>
      <?php
   }
} 

register_widget('CoLabs_Featured_Ad');
?>