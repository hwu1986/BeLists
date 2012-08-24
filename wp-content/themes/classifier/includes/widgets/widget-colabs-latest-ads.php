<?php
/*---------------------------------------------------------------------------------*/
/* Latest Custom Type widget */
/*---------------------------------------------------------------------------------*/
class CoLabs_Latest_Ads extends WP_Widget {

   function CoLabs_Latest_Ads() {
	   $widget_ops = array('description' => 'Add your latest Ads for the sidebar with this widget.' );
       parent::WP_Widget(false, __('ColorLabs - Latest Ads', 'colabsthemes'),$widget_ops);      
   }

   function widget($args, $instance) {  
    extract( $args );
   	$title = $instance['title'];
	$posttype = $instance['posttype'];
	if($posttype=='')$posttype=COLABS_POST_TYPE;
	$number = $instance['number'];
	if($number=='')$number=5;
    
    echo $before_widget;
         
    if ($title) { echo $before_title . $title . $after_title; }else { echo $before_title .__('Latest Ads','colabsthemes'). $after_title;}
        
		query_posts( array(
						'showposts' => $number,
						'post_type' => COLABS_POST_TYPE,
					));
        $count = 0;

        if ( have_posts() ) : ?>
        
        <ul class="widget-item-list">

    		<?php while ( have_posts() ) : the_post(); $count++; global $post; ?>
            
    		<li <?php if( $count == 1) echo 'class="with-image"'; ?>>
            <?php if( $count == 1) colabs_image('width=222&height=239&class=item-images'); ?>
                <h4 class="item-name"><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
                <span class="price"><?php if ( get_post_meta($post->ID, 'colabs_price', true) ) colabs_get_price_legacy($post->ID); else colabs_get_price($post->ID, 'colabs_price'); ?></span>
            </li>
            
    		<?php 
    		endwhile;?>
            <a href="<?php echo get_post_type_archive_link( COLABS_POST_TYPE ); ?>" class="more-link"><?php _e('View All','colabsthemes'); ?></a>
            
		</ul>
        
        <?php endif;
        
        echo $after_widget;
        
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {        
   
       $title = esc_attr($instance['title']);
	   $number = esc_attr($instance['number']);
	   //$posttype = esc_attr($instance['posttype']);

       ?>
       <p>
	   	   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','colabsthemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
       </p>
	    <p>
	   	   <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number:','colabsthemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('number'); ?>"  value="<?php echo $number; ?>" class="widefat" id="<?php echo $this->get_field_id('number'); ?>" />
       </p>
	   <!--p>
	   	   <label for="<?php echo $this->get_field_id('posttype'); ?>"><?php _e('Post Type:','colabsthemes'); ?></label>
		   <select name="<?php echo $this->get_field_name('posttype'); ?>">
				<?php
			
				$listposttype=get_post_types('','names');
				$badtypes=array('nav_menu_item','revision','attachment','colabsframework');
				if  ($listposttype) {
				  foreach ($listposttype  as $itemlistposttype ) {
					  if (!in_array($itemlistposttype,$badtypes)) {
						if ($itemlistposttype==$posttype)$selected='selected="selected"';
						echo '<option value="'. $itemlistposttype. '" '.$selected.'>'.$itemlistposttype.'</option>';
						$selected='';
					  }	
				  }
				}
				?>	
			</select>
	       
       </p-->
      <?php
   }
} 

register_widget('CoLabs_Latest_Ads');
?>